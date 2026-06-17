<?php

namespace App\Helpers;

class ImageKit
{
    private static function getPrivateKey(): string
    {
        return $_ENV['IMAGEKIT_PRIVATE_KEY'] ?? '';
    }

    private static function getEndpoint(): string
    {
        return "https://upload.imagekit.io/api/v1/files/upload";
    }

    public static function upload(string $fileField, string $folder = '/pos-stock'): ?string
    {
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
            'folder' => $folder,
        ];
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
        curl_setopt($ch, CURLOPT_USERPWD, self::getPrivateKey() . ':');

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode === 200) {
            $result = json_decode($response, true);
            if (isset($result['filePath'])) {
                $endpoint = $_ENV['IMAGEKIT_URL_ENDPOINT'] ?? 'https://ik.imagekit.io/ze1uqcd3p';
                $filePath = ltrim($result['filePath'], '/');
                return rtrim($endpoint, '/') . '/' . $filePath . '?updatedAt=' . time();
            }
            return $result['url'] ?? null;
        }

        return null;
    }
}
