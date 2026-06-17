<?php

namespace App\Helpers;

class ImageKit {
    private static function getPrivateKey() {
        return $_ENV['IMAGEKIT_PRIVATE_KEY'] ?? '';
    }

    private static function getEndpoint() {
        return "https://upload.imagekit.io/api/v1/files/upload";
    }

    public static function upload($fileField, $folder = '/rent_miss_clean') {
        if (!isset($_FILES[$fileField]) || $_FILES[$fileField]['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        $tmpName = $_FILES[$fileField]['tmp_name'];
        $fileName = time() . '_' . preg_replace('/[^A-Za-z0-9._-]/', '_', $_FILES[$fileField]['name']);
        $fileData = base64_encode(file_get_contents($tmpName));

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, self::getEndpoint());
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        
        $postFields = [
            'file' => $fileData,
            'fileName' => $fileName,
            'useUniqueFileName' => 'true',
            'folder' => $folder
        ];
        
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
        // Authenticate by providing your private key as the username. Leave the password blank.
        curl_setopt($ch, CURLOPT_USERPWD, self::getPrivateKey() . ':');

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($httpCode === 200) {
            $result = json_decode($response, true);
            
            if (isset($result['filePath'])) {
                $endpoint = $_ENV['IMAGEKIT_URL_ENDPOINT'] ?? 'https://ik.imagekit.io/ceo2gbv21';
                $filePath = ltrim($result['filePath'], '/');
                
                // Construct the URL manually to ensure it matches the pattern and is robust
                // Add updatedAt timestamp for cache busting as requested
                return rtrim($endpoint, '/') . '/' . $filePath . '?updatedAt=' . time();
            }
            
            return $result['url'] ?? null;
        }

        // Log error if needed (optional, depends on project standards)
        // error_log("ImageKit upload failed with code $httpCode. Response: $response. Curl Error: $error");

        return null;
    }
}
