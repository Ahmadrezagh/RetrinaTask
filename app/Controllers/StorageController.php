<?php

namespace App\Controllers;

class StorageController extends BaseController
{
    /**
     * Serve profile image files
     */
    public function serveProfileImage($filename)
    {
        $filePath = __DIR__ . '/../../storage/uploads/profiles/' . $filename;
        
        if (!file_exists($filePath)) {
            http_response_code(404);
            echo '404 - File not found';
            return;
        }
        
        // Security check: ensure filename doesn't contain path traversal
        if (strpos($filename, '..') !== false || strpos($filename, '/') !== false) {
            http_response_code(403);
            echo '403 - Forbidden';
            return;
        }
        
        $mimeType = mime_content_type($filePath);
        
        // Only allow image files
        if (!in_array($mimeType, ['image/jpeg', 'image/png', 'image/gif', 'image/webp'])) {
            http_response_code(403);
            echo '403 - Invalid file type';
            return;
        }
        
        header('Content-Type: ' . $mimeType);
        header('Content-Length: ' . filesize($filePath));
        header('Cache-Control: public, max-age=3600'); // Cache for 1 hour
        
        readfile($filePath);
    }
} 