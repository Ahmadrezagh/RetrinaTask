<?php

namespace App\Controllers;

class HomeController extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Welcome to Retrina Framework',
            'message' => 'Your custom PHP MVC framework is working!',
            'data' => [
                'framework' => 'Retrina',
                'version' => '1.0',
                'author' => 'Custom Framework',
                'features' => ['MVC', 'Routing', 'Autoloading', 'Database']
            ]
        ];
        
        $this->view('home', $data);
    }
    
    public function about()
    {
        $data = [
            'title' => 'About Retrina Framework',
            'message' => 'A lightweight, custom-built PHP MVC framework'
        ];
        
        $this->view('home', $data);
    }
    
    public function user($id)
    {
        $data = [
            'title' => 'User Profile',
            'message' => "Displaying user profile for ID: {$id}",
            'data' => [
                'user_id' => $id,
                'route_param' => 'Successfully captured from URL'
            ]
        ];
        
        $this->view('home', $data);
    }
    
    public function api()
    {
        $data = [
            'status' => 'success',
            'message' => 'API endpoint working',
            'framework' => 'Retrina',
            'timestamp' => date('Y-m-d H:i:s')
        ];
        
        $this->json($data);
    }
} 