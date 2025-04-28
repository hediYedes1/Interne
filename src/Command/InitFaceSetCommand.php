<?php

namespace App\Command;

use App\Service\FaceRecognitionService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class InitFaceSetCommand extends Command
{
    protected static $defaultName = 'app:init-face-set';
    protected static $defaultDescription = 'Initialize Face++ FaceSet';

    private $faceService;
    private $params;

    public function __construct(FaceRecognitionService $faceService, ParameterBagInterface $params)
    {
        $this->faceService = $faceService;
        $this->params = $params;
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Creating FaceSet...');
        
        $faceSetToken = $this->faceService->createFaceSet();
        
        if ($faceSetToken) {
            $output->writeln([
                '',
                'FaceSet created successfully!',
                '',
                'Add this to your .env file:',
                'FACE_SET_TOKEN='.$faceSetToken,
                '',
                'Then clear cache with: php bin/console cache:clear'
            ]);
            return Command::SUCCESS;
        }
        
        $output->writeln('<error>Failed to create FaceSet</error>');
        return Command::FAILURE;
    }
}
