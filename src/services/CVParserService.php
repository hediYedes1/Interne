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

    private const REPLACEMENTS = [
        'cacket cracer' => 'packet tracer',
        'cisco cacket cracer' => 'cisco packet tracer',
        'wiresh ark' => 'wireshark',
        'wiresh' => 'wireshark',
        'android stud io' => 'android studio',
        'mic rosoft' => 'microsoft',
        'ms office' => 'microsoft office',
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

            // Extraction du texte brut du fichier
            $text = $this->extractTextFromFile($file);

            // Nettoyage du texte extrait
            $cleanText = $this->cleanText($text);

            // Résumer le texte pour un format plus concis
            $summarizedText = $this->summarizeText($cleanText);

            $this->logger->debug('Extracted, cleaned, and summarized text', [
                'original_length' => strlen($text),
                'clean_length' => strlen($cleanText),
                'summarized_length' => strlen($summarizedText)
            ]);

            return $summarizedText;

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

    public function cleanText(string $text): string
    {
        // Convert the text to lowercase
        $text = strtolower($text);

        // Replace known parsing errors
        foreach (self::REPLACEMENTS as $wrong => $correct) {
            $text = str_ireplace($wrong, $correct, $text);
        }

        // Replace punctuation marks with space
        $text = preg_replace('/[.,;:!?()\[\]{}<>\/\\\|"\']+/', ' ', $text);

        // Remove non-alphabetic characters
        $text = preg_replace('/[^a-zA-ZÀ-ÿ\s]/u', '', $text);

        // Remove accents
        $text = iconv('UTF-8', 'ASCII//TRANSLIT', $text);

        // Tokenize text
        $words = preg_split('/\s+/', $text);

        // Filter out stopwords and words shorter than 3 characters
        $filtered = array_unique(array_filter($words, function ($word) {
            return strlen($word) > 2 && !in_array($word, self::STOPWORDS);
        }));

        $cleanedText = implode(' ', $filtered);

        $this->logger->info('Cleaned Text:', ['cleaned_text' => $cleanedText]);

        return $cleanedText;
    }

    private function summarizeText(string $text, int $summaryLength = 5): string
    {
        $sentences = preg_split('/(?<=[.!?])\s+/', $text);

        if (count($sentences) <= $summaryLength) {
            return $text;
        }

        $wordFrequencies = [];
        foreach ($sentences as $sentence) {
            $words = array_filter(explode(' ', $sentence), fn($word) => !in_array(strtolower($word), self::STOPWORDS));
            foreach ($words as $word) {
                $word = strtolower($word);
                $wordFrequencies[$word] = ($wordFrequencies[$word] ?? 0) + 1;
            }
        }

        $sentenceScores = [];
        foreach ($sentences as $index => $sentence) {
            $score = 0;
            $words = array_filter(explode(' ', $sentence), fn($word) => !in_array(strtolower($word), self::STOPWORDS));
            foreach ($words as $word) {
                $score += $wordFrequencies[strtolower($word)] ?? 0;
            }
            $sentenceScores[$index] = $score;
        }

        arsort($sentenceScores);
        $topSentences = array_slice($sentenceScores, 0, $summaryLength, true);
        ksort($topSentences);

        $summary = '';
        foreach ($topSentences as $index => $score) {
            $summary .= $sentences[$index] . ' ';
        }

        return trim($summary);
    }
}