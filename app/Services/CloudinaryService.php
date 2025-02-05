<?php

namespace App\Services;

use Cloudinary\Cloudinary;
use Illuminate\Support\Facades\Log;

class CloudinaryService
{
    protected $cloudinary;

    public function __construct()
    {
        try {
            $this->cloudinary = new Cloudinary([
                'cloud' => [
                    'cloud_name' => config('services.cloudinary.cloud_name'),
                    'api_key' => config('services.cloudinary.api_key'),
                    'api_secret' => config('services.cloudinary.api_secret'),
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Cloudinary initialization failed: ' . $e->getMessage());
            throw $e;
        }
    }

    public function upload($file)
    {
        try {
            return $this->cloudinary->uploadApi()->upload($file);
        } catch (\Exception $e) {
            Log::error('Cloudinary upload failed: ' . $e->getMessage());
            throw $e;
        }
    }
}
