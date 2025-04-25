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
        'ne', 'pas', 'plus', 'moins', 'faire', 'compétence', 'nécessite', 'avoir',
        'informations', 'professionnelles', 'formations', 'exp', 'eriences', 'mod', 'elisation'
    ];

    private const REPLACEMENTS = [
        'cacket cracer' => 'packet tracer',
        'cisco cacket cracer' => 'cisco packet tracer',
        'wiresh ark' => 'wireshark',
        'wiresharkark' => 'wireshark',
        'wiresharkarkark' => 'wireshark',
        'wiresh' => 'wireshark',
        'android stud io' => 'android studio',
        'mic rosoft' => 'microsoft',
        'ms office' => 'microsoft office',
        'openss l' => 'openssl',
        'open ssl' => 'openssl',
        'zaineb' => 'zeineb',
        'tayari comp' => 'tayari',
        'ecouverte' => 'découverte',
        'eseau' => 'réseau',
        'ephonique' => 'téléphonique'
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
        $content = file_get_contents($path);
        return strip_tags($content);
    }

    public function cleanText(string $text): string
    {
        $text = strtolower($text);

        foreach (self::REPLACEMENTS as $wrong => $correct) {
            $text = str_ireplace($wrong, $correct, $text);
        }

        $text = preg_replace('/[.,;:!?()\[\]{}<>\/\\\|"\']+/', ' ', $text);
        $text = preg_replace('/[^a-zA-ZÀ-ÿ\s]/u', '', $text);
        $text = iconv('UTF-8', 'ASCII//TRANSLIT', $text);
        $text = preg_replace('/\s+/', ' ', $text);

        $words = preg_split('/\s+/', $text);
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