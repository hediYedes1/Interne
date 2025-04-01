<?php

namespace App\Doctrine;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use App\Enum\StatutTestTechnique;
use InvalidArgumentException;

class StatutTestTechniqueType extends Type
{
    const NAME = 'statuttesttechnique';

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform): string
    {
        return "ENUM('REFUSE', 'ACCEPTE', 'ENATTENTE')";
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?StatutTestTechnique
    {
        return $value !== null ? StatutTestTechnique::tryFrom($value) : null;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): string
    {
        if (!$value instanceof StatutTestTechnique) {
            throw new InvalidArgumentException("Invalid enum value.");
        }

        return $value->value;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
