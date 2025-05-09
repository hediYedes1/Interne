<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('json_decode', [$this, 'jsonDecode']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('file_exists', [$this, 'fileExists']),
            new TwigFunction('file_get_contents', [$this, 'fileGetContents']),
        ];
    }

    public function fileExists(string $path): bool
    {
        return file_exists($path);
    }

    public function fileGetContents(string $path): string
    {
        return file_exists($path) ? file_get_contents($path) : '';
    }

    public function jsonDecode(string $json, bool $assoc = true)
    {
        return json_decode($json, $assoc);
    }
}