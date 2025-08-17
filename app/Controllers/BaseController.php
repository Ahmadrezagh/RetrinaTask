<?php

namespace App\Controllers;

class BaseController
{
    protected $data = [];
    
    /**
     * Set data to be passed to the view
     */
    protected function setData($key, $value)
    {
        $this->data[$key] = $value;
    }
    
    /**
     * Render a view with data
     */
    protected function view($viewName, $data = [])
    {
        $data = array_merge($this->data, $data);
        
        $viewPath = __DIR__ . '/../../views/' . $viewName . '.php';
        
        if (!file_exists($viewPath)) {
            throw new \Exception("View file not found: {$viewPath}");
        }
        
        extract($data);
        require $viewPath;
    }
    
    /**
     * Return JSON response
     */
    protected function json($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
    
    /**
     * Redirect to another URL
     */
    protected function redirect($url, $statusCode = 302)
    {
        http_response_code($statusCode);
        header("Location: {$url}");
        exit;
    }
} 