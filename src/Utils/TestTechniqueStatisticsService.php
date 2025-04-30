<?php

namespace App\Utils;

use App\Repository\TesttechniqueRepository;
use App\Enum\StatutTestTechnique;

class TestTechniqueStatisticsService
{
    public function __construct(
        private TesttechniqueRepository $testTechniqueRepository
    ) {
    }

    public function getTestTechniqueStatistics(): array
    {
        $rawStats = $this->testTechniqueRepository->getStatisticsByStatus();
        
        $stats = [];
        $total = 0;
        
        // Initialiser tous les statuts avec des valeurs par défaut
        foreach (StatutTestTechnique::cases() as $status) {
            $stats[$status->value] = [
                'count' => 0,
                'percentage' => 0,
                'earliest_date' => null,
                'latest_date' => null,
                'label' => $status->getLabel()
            ];
        }
        
        // Remplir avec les données existantes
        foreach ($rawStats as $stat) {
            $status = $stat['status'] instanceof StatutTestTechnique ? $stat['status']->value : $stat['status'];
            $stats[$status] = [
                'count' => (int)$stat['count'],
                'percentage' => 0, // Calculé plus tard
                'earliest_date' => $stat['earliest_date'],
                'latest_date' => $stat['latest_date'],
                'label' => StatutTestTechnique::tryFrom($status)?->getLabel() ?? $status
            ];
            $total += (int)$stat['count'];
        }
        
        // Calculer les pourcentages
        if ($total > 0) {
            foreach ($stats as $key => $data) {
                $stats[$key]['percentage'] = round(($data['count'] / $total) * 100, 2);
            }
        }
        
        return [
            'statuses' => $stats,
            'total' => $total,
            'has_data' => $total > 0
        ];
    }
}