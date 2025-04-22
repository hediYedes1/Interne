<?php
// src/services/CVParserService.php

namespace App\services;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Smalot\PdfParser\Parser as PdfParser;
use Psr\Log\LoggerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class CVParserService
{
    private const STOPWORDS = [
        'le', 'la', 'les', 'un', 'une', 'des', 'de', 'du', 'au', 'aux', 'et', 'à', 'en',
        'par', 'pour', 'avec', 'sans', 'sur', 'dans', 'se', 'est', 'sont', 'qui', 'que',
        'dont', 'comme', 'ça', 'ce', 'cet', 'cette', 'ces', 'nous', 'vous', 'ils', 'elles',
        'ne', 'pas', 'plus', 'moins', 'faire', 'compétence', 'nécessite', 'avoir'
    ];

    private PdfParser $pdfParser;
    private LoggerInterface $logger;
    private ParameterBagInterface $params;
    private SluggerInterface $slugger;

    public function __construct(
        LoggerInterface $logger,
        ParameterBagInterface $params,
        SluggerInterface $slugger
    ) {
        $this->pdfParser = new PdfParser();
        $this->logger = $logger;
        $this->params = $params;
        $this->slugger = $slugger;
    }

    public function extractText(UploadedFile $file): string
    {
        try {
            $this->logger->info('Starting CV extraction', [
                'filename' => $file->getClientOriginalName(),
                'mimeType' => $file->getMimeType()
            ]);

            $text = $this->extractTextFromFile($file);
            $cleanText = $this->cleanText($text);

            $this->logger->debug('Extracted and cleaned text', [
                'original_length' => strlen($text),
                'clean_length' => strlen($cleanText)
            ]);

            return $cleanText;

        } catch (\Exception $e) {
            $this->logger->error('CV parsing failed', [
                'error' => $e->getMessage(),
                'file' => $file->getClientOriginalName()
            ]);
            return '';
        }
    }

    private function extractTextFromFile(UploadedFile $file): string
    {
        $mimeType = $file->getMimeType();
        $path = $file->getRealPath();

        switch ($mimeType) {
            case 'application/pdf':
                $pdf = $this->pdfParser->parseFile($path);
                return $pdf->getText();
            
            case 'application/msword':
            case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document':
                return $this->extractTextFromWord($path);
                
            case 'text/plain':
                return file_get_contents($path);
                
            default:
                throw new \RuntimeException("Unsupported file type: $mimeType");
        }
    }

    private function extractTextFromWord(string $path): string
    {
        // Solution temporaire pour les fichiers Word - à remplacer par une librairie dédiée
        $content = file_get_contents($path);
        return strip_tags($content); // Basic extraction
    }

    private function cleanText(string $text): string
    {
        // Normaliser les caractères
        $text = iconv('UTF-8', 'ASCII//TRANSLIT', $text);
        
        // Supprimer la ponctuation et les caractères spéciaux
        $text = preg_replace('/[^a-zA-Z0-9\s]/', ' ', $text);
        
        // Supprimer les espaces multiples
        $text = preg_replace('/\s+/', ' ', trim(strtolower($text)));
        
        // Filtrer les stopwords
        $words = array_filter(
            explode(' ', $text),
            fn($word) => !in_array($word, self::STOPWORDS) && strlen($word) > 2
        );
        
        return implode(' ', $words);
    }

    public function compareCVWithOffre(string $cvText, string $offreDescription): float
    {
        if (empty($cvText)) {
            $this->logger->warning('Empty CV text provided for comparison');
            return 0.0;
        }

        if (empty($offreDescription)) {
            $this->logger->warning('Empty job description provided for comparison');
            return 0.0;
        }

        $cvWords = $this->getWeightedWords($cvText);
        $offerWords = $this->getWeightedWords($offreDescription);

        $commonScore = 0;
        foreach ($offerWords as $word => $count) {
            if (isset($cvWords[$word])) {
                $commonScore += min($count, $cvWords[$word]);
            }
        }

        $totalWords = array_sum($offerWords);
        $similarity = ($totalWords > 0) ? ($commonScore / $totalWords) * 100 : 0;

        $this->logger->info('Similarity calculation completed', [
            'score' => $similarity,
            'common_words' => array_intersect_key($cvWords, $offerWords)
        ]);

        return round($similarity, 2);
    }

    private function getWeightedWords(string $text): array
    {
        $words = explode(' ', $text);
        $weighted = [];
        
        foreach ($words as $word) {
            if (strlen($word) > 2) { // Ignorer les mots trop courts
                $weighted[$word] = ($weighted[$word] ?? 0) + 1;
            }
        }
        
        return $weighted;
    }
}