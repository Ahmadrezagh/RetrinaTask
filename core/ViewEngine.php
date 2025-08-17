<?php

namespace Core;

class ViewEngine
{
    private $viewPath;
    private $layoutPath;
    private $data = [];
    private $sections = [];
    private $currentSection = null;
    private $layout = null;
    private $extends = null;
    
    public function __construct($viewPath = null, $layoutPath = null)
    {
        $this->viewPath = $viewPath ?: __DIR__ . '/../views/';
        $this->layoutPath = $layoutPath ?: __DIR__ . '/../views/layouts/';
    }
    
    /**
     * Render a view with optional layout
     */
    public function render($view, $data = [], $layout = null)
    {
        $this->data = array_merge($this->data, $data);
        $this->layout = $layout;
        
        // Start output buffering
        ob_start();
        
        // Extract data to variables
        extract($this->data);
        
        // Include the view file
        $viewFile = $this->getViewPath($view);
        if (!file_exists($viewFile)) {
            throw new \Exception("View file not found: {$viewFile}");
        }
        
        include $viewFile;
        
        // Get the view content
        $content = ob_get_clean();
        
        // If view extends a layout, render with layout
        if ($this->extends) {
            return $this->renderWithLayout($this->extends, $content);
        }
        
        // If layout is specified, render with layout
        if ($this->layout) {
            return $this->renderWithLayout($this->layout, $content);
        }
        
        return $content;
    }
    
    /**
     * Render view with layout
     */
    private function renderWithLayout($layout, $content)
    {
        // Set the main content section
        $this->sections['content'] = $content;
        
        // Start output buffering for layout
        ob_start();
        
        // Extract data to variables
        extract($this->data);
        
        // Include the layout file
        $layoutFile = $this->getLayoutPath($layout);
        if (!file_exists($layoutFile)) {
            throw new \Exception("Layout file not found: {$layoutFile}");
        }
        
        include $layoutFile;
        
        return ob_get_clean();
    }
    
    /**
     * Start a section
     */
    public function section($name)
    {
        $this->currentSection = $name;
        ob_start();
    }
    
    /**
     * End current section
     */
    public function endSection()
    {
        if ($this->currentSection) {
            $this->sections[$this->currentSection] = ob_get_clean();
            $this->currentSection = null;
        }
    }
    
    /**
     * Yield a section content
     */
    public function yield($section, $default = '')
    {
        return $this->sections[$section] ?? $default;
    }
    
    /**
     * Check if section exists
     */
    public function hasSection($section)
    {
        return isset($this->sections[$section]);
    }
    
    /**
     * Extend a layout
     */
    public function extends($layout)
    {
        $this->extends = $layout;
    }
    
    /**
     * Include a partial view
     */
    public function include($view, $data = [])
    {
        $mergedData = array_merge($this->data, $data);
        extract($mergedData);
        
        $viewFile = $this->getViewPath($view);
        if (!file_exists($viewFile)) {
            throw new \Exception("Partial view file not found: {$viewFile}");
        }
        
        include $viewFile;
    }
    
    /**
     * Set global data for all views
     */
    public function share($key, $value = null)
    {
        if (is_array($key)) {
            $this->data = array_merge($this->data, $key);
        } else {
            $this->data[$key] = $value;
        }
    }
    
    /**
     * Add data to be passed to views
     */
    public function with($key, $value = null)
    {
        if (is_array($key)) {
            $this->data = array_merge($this->data, $key);
        } else {
            $this->data[$key] = $value;
        }
        return $this;
    }
    
    /**
     * Get view file path
     */
    private function getViewPath($view)
    {
        $view = str_replace('.', '/', $view);
        return $this->viewPath . $view . '.php';
    }
    
    /**
     * Get layout file path
     */
    private function getLayoutPath($layout)
    {
        return $this->layoutPath . $layout . '.php';
    }
    
    /**
     * Escape HTML output
     */
    public function escape($value)
    {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }
    
    /**
     * Generate URL
     */
    public function url($path = '')
    {
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'];
        $script = $_SERVER['SCRIPT_NAME'];
        $baseUrl = $protocol . '://' . $host . dirname($script);
        
        return rtrim($baseUrl, '/') . '/' . ltrim($path, '/');
    }
    
    /**
     * Generate asset URL
     */
    public function asset($path)
    {
        return $this->url('assets/' . ltrim($path, '/'));
    }
    
    /**
     * Old input value (for forms)
     */
    public function old($key, $default = '')
    {
        return $_SESSION['old_input'][$key] ?? $default;
    }
    
    /**
     * CSRF token
     */
    public function csrf()
    {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
    
    /**
     * CSRF hidden input field
     */
    public function csrfField()
    {
        return '<input type="hidden" name="_token" value="' . $this->csrf() . '">';
    }
    
    /**
     * Method field for forms (PUT, DELETE, etc.)
     */
    public function method($method)
    {
        return '<input type="hidden" name="_method" value="' . strtoupper($method) . '">';
    }
    
    /**
     * Clear all sections
     */
    public function clearSections()
    {
        $this->sections = [];
        $this->currentSection = null;
    }
    
    /**
     * Reset view engine state
     */
    public function reset()
    {
        $this->clearSections();
        $this->extends = null;
        $this->layout = null;
    }
} 