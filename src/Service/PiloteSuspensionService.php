<?php

namespace App\Service;

use App\Entity\Infraction;
use App\Entity\Pilote;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class PiloteSuspensionService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private LoggerInterface $logger
    ) {
    }

    public function checkAndSuspendPilote(Infraction $infraction): void
    {
        $pilote = $infraction->getPilote();
        
        if (!$pilote) {
            return;
        }

        if ($infraction->getPenalitePoints() !== null && $infraction->getPenalitePoints() > 0) {
            $nouveauxPoints = $pilote->getPointsLicence() - $infraction->getPenalitePoints();
            $pilote->setPointsLicence(max(0, $nouveauxPoints));

            if ($nouveauxPoints < 1) {
                $ancienStatut = $pilote->getStatut();
                $pilote->setStatut('suspendu');
                $pilote->setEtat('suspendu');
                
                $this->logger->warning("Pilote suspendu automatiquement (points < 1)", [
                    'pilote_id' => $pilote->getId(),
                    'nom' => $pilote->getPrenom() . ' ' . $pilote->getNom(),
                    'ancien_statut' => $ancienStatut,
                    'nouveau_statut' => 'suspendu',
                    'points_restants' => $pilote->getPointsLicence(),
                    'infraction_id' => $infraction->getId()
                ]);
            }

            $this->entityManager->flush();
        }
    }
}
