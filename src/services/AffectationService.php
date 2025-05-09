<?php
// src/services/AffectationService.php
namespace App\services;

use App\Entity\Interview;
use App\Entity\Affectationinterview;
use App\Entity\Utilisateur;
use App\Utils\EmailService;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class AffectationService
{
    public function __construct(
        private EntityManagerInterface $em,
        private EmailService $emailService,
        private LoggerInterface $logger
    ) {}

    public function affecterInterview(Interview $interview, Utilisateur $user): bool
    {
        try {
            $affectation = new Affectationinterview();
            $affectation->setIdinterview($interview);
            $affectation->setIdutilisateur($user);
            $affectation->setDateaffectationinterview(new \DateTime());

            $this->em->persist($interview);
            $this->em->persist($affectation);
            $this->em->flush();

            $email = $user->getEmailutilisateur();
            if ($email) {
                return $this->emailService->sendInterviewAssignmentEmail($email, $interview);
            }
        } catch (\Exception $e) {
            $this->logger->error('Erreur lors de l\'affectation automatique : '.$e->getMessage());
        }

        return false;
    }
}
