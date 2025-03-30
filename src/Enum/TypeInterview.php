<?php
 namespace App\Enum;

enum TypeInterview: string
{
    case ENLIGNE = 'ENLIGNE';
    case ENPERSONNE = 'ENPERSONNE';
    
    public function getLabel(): string
    {
        return match($this) {
            self::ENLIGNE => 'En ligne',
            self::ENPERSONNE => 'En personne',
        };
    }
    
    public static function tryFromName(string $name): ?self
    {
        foreach (self::cases() as $case) {
            if ($case->name === $name) {
                return $case;
            }
        }
        return null;
    }
}