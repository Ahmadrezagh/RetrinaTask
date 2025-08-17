<?php

namespace Core;

class TemplateCompiler
{
    private $patterns = [];
    private $replacements = [];
    private $cachePath;
    
    public function __construct($cachePath = null)
    {
        $this->cachePath = $cachePath ?: __DIR__ . '/../storage/cache/views/';
        $this->initializePatterns();
        $this->ensureCacheDirectory();
    }
    
    /**
     * Initialize template patterns and replacements
     */
    private function initializePatterns()
    {
        // Comments (must be first to avoid conflicts)
        $this->patterns[] = '/\{\{--(.+?)--\}\}/s';
        $this->replacements[] = '<?php /* $1 */ ?>';
        
        // Raw output (unescaped) - triple braces, must come before double braces
        $this->patterns[] = '/\{\{\{\s*(.+?)\s*\}\}\}/';
        $this->replacements[] = '<?php echo $this->escape($1); ?>';
        
        // Raw output (unescaped) - {!! !!} syntax for unescaped HTML
        $this->patterns[] = '/\{\!\!\s*(.+?)\s*\!\!\}/';
        $this->replacements[] = '<?php echo $1; ?>';
        
        // Basic output patterns - double braces (must come after triple braces)
        $this->patterns[] = '/\{\{\s*(.+?)\s*\}\}/';
        $this->replacements[] = '<?php echo $1; ?>';
        
        // Control structures - using balanced parentheses regex
        $this->patterns[] = '/\@if\s*\(([^()]*(?:\([^()]*\)[^()]*)*)\)/s';
        $this->replacements[] = '<?php if($1): ?>';
        
        $this->patterns[] = '/\@elseif\s*\(([^()]*(?:\([^()]*\)[^()]*)*)\)/s';
        $this->replacements[] = '<?php elseif($1): ?>';
        
        $this->patterns[] = '/\@else/';
        $this->replacements[] = '<?php else: ?>';
        
        $this->patterns[] = '/\@endif/';
        $this->replacements[] = '<?php endif; ?>';
        
        // Loops - using balanced parentheses regex
        $this->patterns[] = '/\@foreach\s*\(([^()]*(?:\([^()]*\)[^()]*)*)\)/s';
        $this->replacements[] = '<?php foreach($1): ?>';
        
        $this->patterns[] = '/\@endforeach/';
        $this->replacements[] = '<?php endforeach; ?>';
        
        $this->patterns[] = '/\@for\s*\(([^()]*(?:\([^()]*\)[^()]*)*)\)/s';
        $this->replacements[] = '<?php for($1): ?>';
        
        $this->patterns[] = '/\@endfor/';
        $this->replacements[] = '<?php endfor; ?>';
        
        $this->patterns[] = '/\@while\s*\(([^()]*(?:\([^()]*\)[^()]*)*)\)/s';
        $this->replacements[] = '<?php while($1): ?>';
        
        $this->patterns[] = '/\@endwhile/';
        $this->replacements[] = '<?php endwhile; ?>';
        
        // Template inheritance
        $this->patterns[] = '/\@extends\s*\(\s*[\'"](.+?)[\'"]\s*\)/';
        $this->replacements[] = '<?php $this->extends(\'$1\'); ?>';
        
        $this->patterns[] = '/\@section\s*\(\s*[\'"](.+?)[\'"]\s*\)/';
        $this->replacements[] = '<?php $this->section(\'$1\'); ?>';
        
        $this->patterns[] = '/\@endsection/';
        $this->replacements[] = '<?php $this->endSection(); ?>';
        
        // Yield with default value
        $this->patterns[] = '/\@yield\s*\(\s*[\'"](.+?)[\'"]\s*,\s*[\'"](.+?)[\'"]\s*\)/';
        $this->replacements[] = '<?php echo $this->yield(\'$1\', \'$2\'); ?>';
        
        // Yield without default value
        $this->patterns[] = '/\@yield\s*\(\s*[\'"](.+?)[\'"]\s*\)/';
        $this->replacements[] = '<?php echo $this->yield(\'$1\'); ?>';
        
        // Includes
        $this->patterns[] = '/\@include\s*\(\s*[\'"](.+?)[\'"]\s*(?:,\s*(.+?))?\)/s';
        $this->replacements[] = '<?php $this->include(\'$1\'$2 ? \', $2\' : \'\'); ?>';
        
        // Isset and empty checks
        $this->patterns[] = '/\@isset\s*\((.+?)\)/s';
        $this->replacements[] = '<?php if(isset($1)): ?>';
        
        $this->patterns[] = '/\@endisset/';
        $this->replacements[] = '<?php endif; ?>';
        
        $this->patterns[] = '/\@empty\s*\((.+?)\)/s';
        $this->replacements[] = '<?php if(empty($1)): ?>';
        
        $this->patterns[] = '/\@endempty/';
        $this->replacements[] = '<?php endif; ?>';
        
        // Authentication helpers
        $this->patterns[] = '/\@auth/';
        $this->replacements[] = '<?php if(isset($_SESSION[\'user_id\'])): ?>';
        
        $this->patterns[] = '/\@endauth/';
        $this->replacements[] = '<?php endif; ?>';
        
        $this->patterns[] = '/\@guest/';
        $this->replacements[] = '<?php if(!isset($_SESSION[\'user_id\'])): ?>';
        
        $this->patterns[] = '/\@endguest/';
        $this->replacements[] = '<?php endif; ?>';
        
        // Form helpers
        $this->patterns[] = '/\@csrf_token/';
        $this->replacements[] = '<?php echo $this->csrf(); ?>';
        
        $this->patterns[] = '/\@csrf/';
        $this->replacements[] = '<?php echo $this->csrfField(); ?>';
        
        $this->patterns[] = '/\@method\s*\(\s*[\'"](.+?)[\'"]\s*\)/';
        $this->replacements[] = '<?php echo $this->method(\'$1\'); ?>';
        
        // URL helpers
        $this->patterns[] = '/\@url\s*\(\s*[\'"](.+?)[\'"]\s*\)/';
        $this->replacements[] = '<?php echo $this->url(\'$1\'); ?>';
        
        $this->patterns[] = '/\@asset\s*\(\s*[\'"](.+?)[\'"]\s*\)/';
        $this->replacements[] = '<?php echo $this->asset(\'$1\'); ?>';
        
        // PHP blocks
        $this->patterns[] = '/\@php/';
        $this->replacements[] = '<?php';
        
        $this->patterns[] = '/\@endphp/';
        $this->replacements[] = '?>';
        
        // JSON output
        $this->patterns[] = '/\@json\s*\((.+?)\)/';
        $this->replacements[] = '<?php echo json_encode($1); ?>';
        
        // dd() debug helper
        $this->patterns[] = '/\@dd\s*\((.+?)\)/';
        $this->replacements[] = '<?php dd($1); ?>';
        
        // Custom directives for Bootstrap
        $this->patterns[] = '/\@card\s*\(\s*[\'"](.+?)[\'"]\s*\)/';
        $this->replacements[] = '<div class="card"><div class="card-header">$1</div><div class="card-body">';
        
        $this->patterns[] = '/\@endcard/';
        $this->replacements[] = '</div></div>';
        
        $this->patterns[] = '/\@alert\s*\(\s*[\'"](.+?)[\'"]\s*,\s*[\'"](.+?)[\'"]\s*\)/';
        $this->replacements[] = '<div class="alert alert-$2" role="alert">$1</div>';
    }
    
    /**
     * Compile template content
     */
    public function compile($content)
    {
        // Protect code blocks from compilation
        $protectedBlocks = [];
        $blockIndex = 0;
        
        // Protect <code>...</code> blocks
        $content = preg_replace_callback('/<code[^>]*>.*?<\/code>/s', function($matches) use (&$protectedBlocks, &$blockIndex) {
            $placeholder = "___PROTECTED_CODE_BLOCK_{$blockIndex}___";
            $protectedBlocks[$placeholder] = $matches[0];
            $blockIndex++;
            return $placeholder;
        }, $content);
        
        // Protect <pre><code>...</code></pre> blocks
        $content = preg_replace_callback('/<pre[^>]*><code[^>]*>.*?<\/code><\/pre>/s', function($matches) use (&$protectedBlocks, &$blockIndex) {
            $placeholder = "___PROTECTED_PRE_CODE_BLOCK_{$blockIndex}___";
            $protectedBlocks[$placeholder] = $matches[0];
            $blockIndex++;
            return $placeholder;
        }, $content);
        
        // Apply all patterns
        $compiled = preg_replace($this->patterns, $this->replacements, $content);
        
        // Clean up any remaining whitespace around PHP tags
        $compiled = preg_replace('/\?>\s+<\?php/', '', $compiled);
        
        // Restore protected blocks
        foreach ($protectedBlocks as $placeholder => $originalContent) {
            $compiled = str_replace($placeholder, $originalContent, $compiled);
        }
        
        return $compiled;
    }
    
    /**
     * Compile template file
     */
    public function compileFile($templatePath, $compiledPath = null)
    {
        if (!file_exists($templatePath)) {
            throw new \Exception("Template file not found: {$templatePath}");
        }
        
        $content = file_get_contents($templatePath);
        $compiled = $this->compile($content);
        
        if ($compiledPath) {
            $this->ensureDirectory(dirname($compiledPath));
            file_put_contents($compiledPath, $compiled);
        }
        
        return $compiled;
    }
    
    /**
     * Get compiled template path
     */
    public function getCompiledPath($templatePath)
    {
        $hash = md5($templatePath . filemtime($templatePath));
        return $this->cachePath . $hash . '.php';
    }
    
    /**
     * Check if template needs recompilation
     */
    public function needsRecompilation($templatePath, $compiledPath)
    {
        if (!file_exists($compiledPath)) {
            return true;
        }
        
        return filemtime($templatePath) > filemtime($compiledPath);
    }
    
    /**
     * Ensure cache directory exists
     */
    private function ensureCacheDirectory()
    {
        $this->ensureDirectory($this->cachePath);
    }
    
    /**
     * Ensure directory exists
     */
    private function ensureDirectory($path)
    {
        if (!is_dir($path)) {
            mkdir($path, 0755, true);
        }
    }
    
    /**
     * Clear compiled templates cache
     */
    public function clearCache()
    {
        $files = glob($this->cachePath . '*.php');
        foreach ($files as $file) {
            unlink($file);
        }
    }
    
    /**
     * Add custom directive
     */
    public function directive($name, $callback)
    {
        $pattern = '/\@' . preg_quote($name) . '\s*\((.+?)\)/';
        $this->patterns[] = $pattern;
        $this->replacements[] = function($matches) use ($callback) {
            return $callback($matches[1]);
        };
    }
    
    /**
     * Get cache path
     */
    public function getCachePath()
    {
        return $this->cachePath;
    }
    
    /**
     * Set cache path
     */
    public function setCachePath($path)
    {
        $this->cachePath = rtrim($path, '/') . '/';
        $this->ensureCacheDirectory();
    }
} 