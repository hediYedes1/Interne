<?php

namespace App\services;

use Cloudinary\Cloudinary;
use Cloudinary\Transformation\Resize;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Psr\Log\LoggerInterface;

class UploadService
{
    private Cloudinary $cloudinary;
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->cloudinary = new Cloudinary([
            'cloud' => [
                'cloud_name' => 'dkjyekxne',
                'api_key' => '635948861942666',
                'api_secret' => 'hoMRHm2K9a7LPaSEIg7AfkSqNU4',
            ],
            'url' => [
                'secure' => true
            ]
        ]);
        $this->logger = $logger;
    }

    public function uploadFile(UploadedFile $file): ?string
    {
        try {
            $this->logger->info('🔄 Upload in progress: ' . $file->getClientOriginalName());

            $uploadResult = $this->cloudinary->uploadApi()->upload(
                $file->getRealPath(),
                ['resource_type' => 'auto']
            );

            // Updated to use array access instead of getSecurePath()
            $url = $uploadResult['secure_url'] ?? null;
            
            if (!$url) {
                $this->logger->error('❌ Cloudinary did not return a secure URL');
                return null;
            }

            $this->logger->info('✅ Upload completed. URL: ' . $url);
            return $url;
        } catch (\Exception $e) {
            $this->logger->error('❌ Upload failed: ' . $e->getMessage());
            return null;
        }
    }
}