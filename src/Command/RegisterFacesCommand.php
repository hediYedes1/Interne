<?php
namespace App\Command;

use App\Entity\Utilisateur;
use App\Service\FaceRecognitionService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RegisterFacesCommand extends Command
{
    protected static $defaultName = 'app:register-faces';

    public function __construct(
        private EntityManagerInterface $em,
        private FaceRecognitionService $faceService
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $users = $this->em->getRepository(Utilisateur::class)
            ->findBy(['faceEmbedding' => null]);

        foreach ($users as $user) {
            if ($user->getProfilepictureurl()) {
                try {
                    $faces = $this->faceService->detectFaces($user->getProfilepictureurl());
                    if (count($faces) === 1) {
                        $user->setFaceEmbedding($faces[0]['face_token']);
                        $this->em->persist($user);
                        $output->writeln(sprintf('Registered face for user %s', $user->getEmailutilisateur()));
                    }
                } catch (\Exception $e) {
                    $output->writeln(sprintf('Error for user %s: %s', $user->getEmailutilisateur(), $e->getMessage()));
                }
            }
        }

        $this->em->flush();
        return Command::SUCCESS;
    }
}