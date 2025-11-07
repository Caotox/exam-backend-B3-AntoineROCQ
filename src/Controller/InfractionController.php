<?php

namespace App\Controller;

use App\Entity\Infraction;
use App\Repository\EcurieRepository;
use App\Repository\InfractionRepository;
use App\Repository\PiloteRepository;
use App\Service\PiloteSuspensionService;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api')]
class InfractionController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private SerializerInterface $serializer,
        private LoggerInterface $logger,
        private ValidatorInterface $validator,
        private PiloteSuspensionService $suspensionService
    ) {
    }

    #[Route('/infractions', name: 'infraction_list', methods: ['GET'])]
    public function list(Request $request, InfractionRepository $infractionRepository): JsonResponse
    {
        try {
            $ecurieId = $request->query->get('ecurie');
            $piloteId = $request->query->get('pilote');
            $date = $request->query->get('date');

            if ($ecurieId !== null && !is_numeric($ecurieId)) {
                return new JsonResponse(['error' => 'Le paramètre "ecurie" doit être un nombre'], Response::HTTP_BAD_REQUEST);
            }

            if ($piloteId !== null && !is_numeric($piloteId)) {
                return new JsonResponse(['error' => 'Le paramètre "pilote" doit être un nombre'], Response::HTTP_BAD_REQUEST);
            }

            if ($date !== null && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
                return new JsonResponse(['error' => 'Le paramètre "date" doit être au format YYYY-MM-DD'], Response::HTTP_BAD_REQUEST);
            }

            $infractions = $infractionRepository->findByFilters(
                $ecurieId ? (int)$ecurieId : null,
                $piloteId ? (int)$piloteId : null,
                $date
            );

            $jsonData = $this->serializer->serialize($infractions, 'json', ['groups' => 'infraction:read']);

            return new JsonResponse(
                json_decode($jsonData, true),
                Response::HTTP_OK
            );

        } catch (\Exception $e) {
            $this->logger->error("Erreur lors de la récupération des infractions", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return new JsonResponse(['error' => 'Erreur serveur'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/infractions', name: 'infraction_create', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function create(
        Request $request,
        PiloteRepository $piloteRepository,
        EcurieRepository $ecurieRepository
    ): JsonResponse {
        try {
            $data = json_decode($request->getContent(), true);

            if (!$data) {
                return new JsonResponse(['error' => 'Données JSON invalides'], Response::HTTP_BAD_REQUEST);
            }

            if (!isset($data['description']) || !isset($data['nomCourse'])) {
                return new JsonResponse(['error' => 'Les champs "description" et "nomCourse" sont requis'], Response::HTTP_BAD_REQUEST);
            }

            if (!isset($data['piloteId']) && !isset($data['ecurieId'])) {
                return new JsonResponse(['error' => 'Au moins un pilote ou une écurie doit être spécifié'], Response::HTTP_BAD_REQUEST);
            }

            if (!isset($data['penalitePoints']) && !isset($data['amendeEuros'])) {
                return new JsonResponse(['error' => 'Au moins une pénalité (points) ou une amende (euros) doit être spécifiée'], Response::HTTP_BAD_REQUEST);
            }

            $infraction = new Infraction();
            $infraction->setDescription($data['description']);
            $infraction->setNomCourse($data['nomCourse']);

            if (isset($data['penalitePoints'])) {
                if (!is_numeric($data['penalitePoints']) || $data['penalitePoints'] < 0) {
                    return new JsonResponse(['error' => 'La pénalité doit être un nombre positif ou zéro'], Response::HTTP_BAD_REQUEST);
                }
                $infraction->setPenalitePoints((int)$data['penalitePoints']);
            }

            if (isset($data['amendeEuros'])) {
                if (!is_numeric($data['amendeEuros']) || $data['amendeEuros'] < 0) {
                    return new JsonResponse(['error' => 'L\'amende doit être un nombre positif ou zéro'], Response::HTTP_BAD_REQUEST);
                }
                $infraction->setAmendeEuros((string)$data['amendeEuros']);
            }

            if (isset($data['dateInfraction'])) {
                try {
                    $dateInfraction = new \DateTime($data['dateInfraction']);
                    $infraction->setDateInfraction($dateInfraction);
                } catch (\Exception $e) {
                    return new JsonResponse(['error' => 'Format de date invalide'], Response::HTTP_BAD_REQUEST);
                }
            }

            if (isset($data['piloteId'])) {
                if (!is_numeric($data['piloteId'])) {
                    return new JsonResponse(['error' => 'L\'ID du pilote doit être un nombre'], Response::HTTP_BAD_REQUEST);
                }
                
                $pilote = $piloteRepository->find((int)$data['piloteId']);
                
                if (!$pilote) {
                    $this->logger->error("Pilote non trouvé", ['pilote_id' => $data['piloteId']]);
                    return new JsonResponse(['error' => 'Pilote non trouvé'], Response::HTTP_NOT_FOUND);
                }
                
                $infraction->setPilote($pilote);
            }

            if (isset($data['ecurieId'])) {
                if (!is_numeric($data['ecurieId'])) {
                    return new JsonResponse(['error' => 'L\'ID de l\'écurie doit être un nombre'], Response::HTTP_BAD_REQUEST);
                }
                
                $ecurie = $ecurieRepository->find((int)$data['ecurieId']);
                
                if (!$ecurie) {
                    $this->logger->error("Écurie non trouvée", ['ecurie_id' => $data['ecurieId']]);
                    return new JsonResponse(['error' => 'Écurie non trouvée'], Response::HTTP_NOT_FOUND);
                }
                
                $infraction->setEcurie($ecurie);
            }

            $errors = $this->validator->validate($infraction);
            if (count($errors) > 0) {
                $errorMessages = [];
                foreach ($errors as $error) {
                    $errorMessages[] = $error->getMessage();
                }
                return new JsonResponse(['errors' => $errorMessages], Response::HTTP_BAD_REQUEST);
            }

            $this->entityManager->persist($infraction);
            $this->entityManager->flush();

            $this->suspensionService->checkAndSuspendPilote($infraction);

            $this->logger->info("Infraction créée avec succès", ['infraction_id' => $infraction->getId()]);

            $jsonData = $this->serializer->serialize($infraction, 'json', ['groups' => 'infraction:read']);

            return new JsonResponse(
                json_decode($jsonData, true),
                Response::HTTP_CREATED
            );

        } catch (\Exception $e) {
            $this->logger->error("Erreur lors de la création de l'infraction", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return new JsonResponse(['error' => 'Erreur serveur'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
