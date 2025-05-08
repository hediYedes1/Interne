<?php

namespace App\Enum;

enum Role: string
{
    case CANDIDAT = 'CANDIDAT';
    case RH = 'RH';
    case ADMIN = 'ADMIN';
    case MANAGER = 'MANAGER';

    public function getLabel(): string
    {
        return match($this) {
            self::CANDIDAT => 'Candidat',
            self::RH => 'Ressources Humaines',
            self::ADMIN => 'Administrateur',
            self::MANAGER => 'Manager',
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

