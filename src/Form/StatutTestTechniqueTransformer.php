<?php

namespace App\Form\DataTransformer;

use App\Enum\StatutTestTechnique;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class StatutTestTechniqueTransformer implements DataTransformerInterface
{
    public function transform($value): ?string
    {
        if (null === $value) {
            return null;
        }

        if (!$value instanceof StatutTestTechnique) {
            throw new TransformationFailedException('Expected a StatutTestTechnique.');
        }

        return $value->value;
    }

    public function reverseTransform($value): ?StatutTestTechnique
    {
        if (null === $value || '' === $value) {
            return null;
        }

        if (!is_string($value)) {
            throw new TransformationFailedException('Expected a string.');
        }

        try {
            return StatutTestTechnique::from($value);
        } catch (\ValueError $e) {
            throw new TransformationFailedException(sprintf(
                'The value "%s" is not a valid statut.',
                $value
            ));
        }
    }
}