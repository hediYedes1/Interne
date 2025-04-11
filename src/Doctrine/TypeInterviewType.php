<?php

namespace App\Doctrine;

use App\Enum\TypeInterview; // Make sure this enum exists and is correct
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use InvalidArgumentException;

class TypeInterviewType extends Type
{
    public const NAME = 'typeinterview';

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform): string
    {
        // Adjust this if your database doesn't support ENUM natively (PostgreSQL, for example)
        return "ENUM('ENLIGNE', 'ENPERSONNE')";
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?TypeInterview
    {
        return $value !== null ? TypeInterview::tryFrom($value) : null;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): string
    {
        if (!$value instanceof TypeInterview) {
            throw new InvalidArgumentException("Invalid value for enum 'TypeInterview'.");
        }

        return $value->value;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
