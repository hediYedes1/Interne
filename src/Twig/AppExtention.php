<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('enum_label', function ($enum) {
                if (is_object($enum) && method_exists($enum, 'getLabel')) {
                    return $enum->getLabel();
                }
                if ($enum instanceof \BackedEnum) {
                    return $enum->value;
                }
                return (string) $enum;
            }),
        ];
    }
}