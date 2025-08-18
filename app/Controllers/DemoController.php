<?php

namespace App\Controllers;

class DemoController extends BaseController
{
    /**
     * Demo throttle endpoint
     */
    public function throttle()
    {
        header('Content-Type: application/json');
        echo json_encode([
            'message' => 'This route is rate limited to 5 requests per minute',
            'timestamp' => date('Y-m-d H:i:s')
        ]);
    }
    
    /**
     * Demo logged endpoint
     */
    public function logged()
    {
        header('Content-Type: application/json');
        echo json_encode([
            'message' => 'This request is being logged',
            'timestamp' => date('Y-m-d H:i:s')
        ]);
    }
    
    /**
     * Test endpoint
     */
    public function test()
    {
        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'success',
            'message' => 'Test route working',
            'session' => $_SESSION ?? [],
            'timestamp' => date('Y-m-d H:i:s')
        ]);
    }
    
    /**
     * Test view endpoint
     */
    public function testView()
    {
        return view('home/index', ['test' => true]);
    }
    
    /**
     * Test home endpoint
     */
    public function testHome()
    {
        $data = [
            'title' => 'Test Home',
            'message' => 'This is a test'
        ];
        return view('home/index', $data);
    }
    
    /**
     * Template syntax demo
     */
    public function templateSyntax()
    {
        return view('demo/template-syntax', [
            'title' => 'Template Syntax Demo',
            'items' => ['Apple', 'Banana', 'Cherry'],
            'user' => ['name' => 'John Doe', 'email' => 'john@example.com'],
            'message' => 'Hello from Retrina Framework!',
            'html_content' => '<strong>Bold HTML content</strong>',
            'features' => [
                'MVC Architecture',
                'Custom Template Engine', 
                'Middleware Support',
                'Database ORM',
                'CLI Commands',
                'Testing Framework'
            ],
            'show_features' => true,
            'demo_mode' => true,
            'demo_data' => 'Sample data for isset check'
        ]);
    }
} 