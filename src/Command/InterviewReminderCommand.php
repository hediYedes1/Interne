<?php
// src/Command/InterviewReminderCommand.php
namespace App\Command;

use App\Entity\Interview;
use App\Repository\InterviewRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class InterviewReminderCommand extends Command
{
    protected static $defaultName = 'app:interview-reminder';

    public function __construct(
        private InterviewRepository $interviewRepository,
        private ParameterBagInterface $params
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $interviews = $this->interviewRepository->findInterviewsInNext24Hours();
        $storageDir = $this->params->get('kernel.project_dir').'/var/storage';
        
        // Créer le dossier s'il n'existe pas
        if (!file_exists($storageDir)) {
            mkdir($storageDir, 0777, true);
        }

        $notifications = [];
        foreach ($interviews as $interview) {
            $notifications[] = [
                'message' => sprintf(
                    'Interview "%s" prévue le %s à %s',
                    $interview->getTitreoffre(),
                    $interview->getDateinterview()->format('d/m/Y'),
                    $interview->getTimeinterview()->format('H:i')
                ),
                'created_at' => (new \DateTime())->format('Y-m-d H:i:s'),
                'interview_id' => $interview->getIdinterview()
            ];

            $output->writeln(sprintf(
                'Notification pour interview: "%s" prévue le %s à %s',
                $interview->getTitreoffre(),
                $interview->getDateinterview()->format('d/m/Y'),
                $interview->getTimeinterview()->format('H:i')
            ));
        }

        // Écrire dans le fichier JSON
        file_put_contents(
            $this->params->get('kernel.project_dir').'/var/storage/notifications.json',
        json_encode($notifications, JSON_PRETTY_PRINT)
        );

        return Command::SUCCESS;
    }
}