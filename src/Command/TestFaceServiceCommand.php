<?php

namespace App\Command;

use App\Service\FaceRecognitionService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TestFaceServiceCommand extends Command
{
    protected static $defaultName = 'test:face-service';
    private $faceService;

    public function __construct(FaceRecognitionService $faceService)
    {
        parent::__construct();
        $this->faceService = $faceService;
    }

    protected function configure()
    {
        $this->setDescription('Tests if FaceRecognitionService is working');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $output->writeln('Testing FaceRecognitionService...');
            
            // Add a simple test that doesn't require API calls
            $reflection = new \ReflectionClass($this->faceService);
            $output->writeln(sprintf(
                'Service loaded successfully: %s',
                $reflection->getName()
            ));
            
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $output->writeln(sprintf(
                '<error>Error: %s</error>',
                $e->getMessage()
            ));
            return Command::FAILURE;
        }
    }
}