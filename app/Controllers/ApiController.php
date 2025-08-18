<?php

namespace App\Controllers;

class ApiController extends BaseController
{
    /**
     * API health check endpoint
     */
    public function health()
    {
        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'ok',
            'timestamp' => date('Y-m-d H:i:s'),
            'version' => '1.0.0'
        ]);
    }
} 