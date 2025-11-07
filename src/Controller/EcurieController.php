<?php

namespace App\Controller;

use App\Entity\Ecurie;
use App\Entity\Pilote;
use App\Repository\EcurieRepository;
use App\Repository\PiloteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api')]
class EcurieController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private SerializerInterface $serializer,
        private LoggerInterface $logger
    ) {
    }

    #[Route('/ecuries/{id}/pilotes', name: 'ecurie_update_pilotes', methods: ['PATCH'])]
    public function updatePilotes(
        int $id,
        Request $request,
        EcurieRepository $ecurieRepository,
        PiloteRepository $piloteRepository
    ): JsonResponse {
        try {
            $ecurie = $ecurieRepository->find($id);
            
            if (!$ecurie) {
                $this->logger->error("Écurie non trouvée", ['id' => $id]);
                return new JsonResponse(['error' => 'Écurie non trouvée'], Response::HTTP_NOT_FOUND);
            }

            $data = json_decode($request->getContent(), true);
            
            if (!isset($data['pilotes']) || !is_array($data['pilotes'])) {
                $this->logger->error("Format de données invalide", ['data' => $data]);
                return new JsonResponse(['error' => 'Le champ "pilotes" est requis et doit être un tableau'], Response::HTTP_BAD_REQUEST);
            }

            foreach ($data['pilotes'] as $piloteData) {
                if (!isset($piloteData['id'])) {
                    return new JsonResponse(['error' => 'Chaque pilote doit avoir un ID'], Response::HTTP_BAD_REQUEST);
                }

                $pilote = $piloteRepository->find($piloteData['id']);
                
                if (!$pilote) {
                    $this->logger->error("Pilote non trouvé", ['pilote_id' => $piloteData['id']]);
                    return new JsonResponse(['error' => "Pilote ID {$piloteData['id']} non trouvé"], Response::HTTP_NOT_FOUND);
                }

                if (isset($piloteData['action']) && $piloteData['action'] === 'remove') {
                    $ecurie->removePilote($pilote);
                    $this->logger->info("Pilote retiré de l'écurie", ['pilote_id' => $pilote->getId(), 'ecurie_id' => $id]);
                } else {
                    $pilote->setEcurie($ecurie);
                    
                    if (isset($piloteData['statut']) && in_array($piloteData['statut'], ['titulaire', 'reserviste'])) {
                        $pilote->setStatut($piloteData['statut']);
                    }
                    
                    $this->logger->info("Pilote ajouté/modifié dans l'écurie", ['pilote_id' => $pilote->getId(), 'ecurie_id' => $id]);
                }
            }

            $this->entityManager->flush();

            $jsonData = $this->serializer->serialize($ecurie, 'json', ['groups' => 'ecurie:read']);
            
            return new JsonResponse(
                json_decode($jsonData, true),
                Response::HTTP_OK
            );

        } catch (\Exception $e) {
            $this->logger->error("Erreur lors de la mise à jour des pilotes", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return new JsonResponse(['error' => 'Erreur serveur'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
