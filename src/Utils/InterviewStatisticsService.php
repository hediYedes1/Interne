<?php

namespace App\Utils;

use App\Repository\InterviewRepository;
use App\Enum\TypeInterview;

class InterviewStatisticsService
{
    public function __construct(
        private InterviewRepository $interviewRepository
    ) {
    }

    public function getInterviewStatistics(): array
{
    $rawStats = $this->interviewRepository->getStatisticsByType();
    
    $stats = [];
    $total = 0;
    
    // Initialiser tous les types avec des valeurs par défaut
    foreach (TypeInterview::cases() as $type) {
        $stats[$type->value] = [
            'count' => 0,
            'percentage' => 0,
            'earliest_date' => null,
            'latest_date' => null,
            'label' => $type->getLabel()
        ];
    }
    
    // Remplir avec les données existantes
    foreach ($rawStats as $stat) {
        $type = $stat['type'] instanceof TypeInterview ? $stat['type']->value : $stat['type'];
        $stats[$type] = [
            'count' => (int)$stat['count'],
            'percentage' => 0, // Calculé plus tard
            'earliest_date' => $stat['earliest_date'],
            'latest_date' => $stat['latest_date'],
            'label' => TypeInterview::tryFrom($type)?->getLabel() ?? $type
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
        'types' => $stats,
        'total' => $total,
        'has_data' => $total > 0
    ];
}
}