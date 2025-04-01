<?php
 namespace App\Enum;

enum StatutTestTechnique: string
{
    case REFUSE = 'REFUSE';
    case ACCEPTE = 'ACCEPTE';
    case ENATTENTE = 'ENATTENTE';
    
    public function getLabel(): string
    {
        return match($this) {
            self::REFUSE => 'REFUSER',
            self::ACCEPTE => 'ACCEPTE',
            self::ENATTENTE => 'ENATTENTE',
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
    public static function values(): array
    {
        return array_map(fn(self $case) => $case->value, self::cases());
    }
    public function label(): string
    {
        return $this->value;
    }
 
}