<?php

namespace App\Controllers;

class HomeController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        
        // Share common data across all views in this controller
        $this->shareData([
            'app_name' => 'Retrina Framework',
            'version' => '1.0.0'
        ]);
    }
    
    public function index()
    {
        $data = [
            'title' => 'Welcome to Retrina Framework',
            'message' => 'Your custom PHP MVC framework with advanced view engine is working!',
            'data' => [
                'framework' => 'Retrina',
                'version' => '1.0',
                'author' => 'Custom Framework',
                'features' => [
                    'MVC Architecture',
                    'Advanced Routing',
                    'Template Engine',
                    'Layout System',
                    'Section Support',
                    'CSRF Protection',
                    'Autoloading',
                    'Database Layer'
                ]
            ]
        ];
        
        // Use the new view structure with layout
        $this->view('home.index', $data, 'app');
    }
    
    public function about()
    {
        $data = [
            'title' => 'About Retrina Framework',
            'message' => 'A lightweight, powerful PHP MVC framework with advanced view engine'
        ];
        
        // Demonstrate using the same view with different data
        $this->view('home.index', $data, 'app');
    }
    
    public function user($id)
    {
        $data = [
            'user_id' => $id,
            'route_param' => 'Successfully captured from URL',
            'data' => [
                'user_id' => $id,
                'route_parameter_demonstration' => true,
                'view_engine_features' => [
                    'Layout inheritance',
                    'Section management',
                    'Parameter passing',
                    'XSS protection',
                    'CSRF tokens',
                    'URL generation',
                    'Old input values'
                ]
            ]
        ];
        
        // Use the dedicated user profile view
        $this->view('user.profile', $data, 'app');
    }
    
    public function api()
    {
        $data = [
            'status' => 'success',
            'message' => 'API endpoint working with new view engine',
            'framework' => 'Retrina',
            'timestamp' => date('Y-m-d H:i:s'),
            'features' => [
                'view_engine' => 'Advanced templating system',
                'layouts' => 'Multiple layout support',
                'sections' => 'Section-based content management',
                'csrf' => 'CSRF protection enabled',
                'xss' => 'XSS protection built-in'
            ]
        ];
        
        $this->json($data);
    }
    
    public function contact()
    {
        if ($this->getRequestMethod() === 'POST') {
            try {
                // Validate CSRF token
                $this->validateCsrf();
                
                // Process form data
                $name = $_POST['name'] ?? '';
                $email = $_POST['email'] ?? '';
                $message = $_POST['message'] ?? '';
                
                // Basic validation
                if (empty($name) || empty($email) || empty($message)) {
                    throw new \Exception('All fields are required');
                }
                
                // Here you would typically save to database or send email
                
                $this->redirectWithSuccess('/', 'Thank you for your message! We will get back to you soon.');
                
            } catch (\Exception $e) {
                $this->redirectWithError('/contact', $e->getMessage());
            }
        }
        
        // Show contact form
        $this->viewWithLayout('contact.form', 'app', [
            'title' => 'Contact Us - Retrina Framework'
        ]);
    }
    
    public function login()
    {
        $data = [
            'title' => 'Login - Retrina Framework'
        ];
        
        // Demonstrate using a different layout
        $this->view('auth.login', $data, 'auth');
    }
    
    public function demo()
    {
        // Demonstrate sharing global data
        $this->shareData('global_message', 'This data is shared across all views!');
        
        $data = [
            'title' => 'View Engine Demo',
            'message' => 'Demonstrating advanced view engine features',
            'demo_features' => [
                'sections' => 'Custom content sections',
                'layouts' => 'Multiple layout inheritance',
                'partials' => 'Reusable view components',
                'helpers' => 'Built-in helper functions',
                'security' => 'XSS and CSRF protection'
            ]
        ];
        
        $this->view('demo.showcase', $data, 'app');
    }
} 