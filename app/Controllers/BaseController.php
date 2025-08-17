<?php

namespace App\Controllers;

// Manually require dependencies to ensure they're loaded
require_once __DIR__ . '/../../core/TemplateCompiler.php';
require_once __DIR__ . '/../../core/ViewEngine.php';

use Core\ViewEngine;

class BaseController
{
    protected $data = [];
    protected $viewEngine;
    
    public function __construct()
    {
        $this->viewEngine = new ViewEngine();
    }
    
    /**
     * Set data to be passed to the view
     */
    protected function setData($key, $value)
    {
        $this->data[$key] = $value;
        return $this;
    }
    
    /**
     * Render a view with data and optional layout
     */
    protected function view($viewName, $data = [], $layout = null)
    {
        $data = array_merge($this->data, $data);
        echo $this->viewEngine->render($viewName, $data, $layout);
    }
    
    /**
     * Return a view response (useful for AJAX)
     */
    protected function viewResponse($viewName, $data = [], $layout = null)
    {
        $data = array_merge($this->data, $data);
        return $this->viewEngine->render($viewName, $data, $layout);
    }
    
    /**
     * Render view with specific layout
     */
    protected function viewWithLayout($viewName, $layout, $data = [])
    {
        $data = array_merge($this->data, $data);
        echo $this->viewEngine->render($viewName, $data, $layout);
    }
    
    /**
     * Share data globally across all views in this controller
     */
    protected function shareData($key, $value = null)
    {
        if (is_array($key)) {
            foreach ($key as $k => $v) {
                $this->viewEngine->share($k, $v);
            }
        } else {
            $this->viewEngine->share($key, $value);
        }
        return $this;
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
    
    /**
     * Redirect back to previous page
     */
    protected function back()
    {
        $referer = $_SERVER['HTTP_REFERER'] ?? '/';
        $this->redirect($referer);
    }
    
    /**
     * Redirect with success message
     */
    protected function redirectWithSuccess($url, $message)
    {
        $_SESSION['flash_success'] = $message;
        $this->redirect($url);
    }
    
    /**
     * Redirect with error message
     */
    protected function redirectWithError($url, $message)
    {
        $_SESSION['flash_error'] = $message;
        $this->redirect($url);
    }
    
    /**
     * Validate CSRF token
     */
    protected function validateCsrf()
    {
        $token = $_POST['_token'] ?? '';
        $sessionToken = $_SESSION['csrf_token'] ?? '';
        
        if (!hash_equals($sessionToken, $token)) {
            throw new \Exception('CSRF token mismatch');
        }
    }
    
    /**
     * Get request method (handles method spoofing)
     */
    protected function getRequestMethod()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        
        if ($method === 'POST' && isset($_POST['_method'])) {
            $method = strtoupper($_POST['_method']);
        }
        
        return $method;
    }
} 