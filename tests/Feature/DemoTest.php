<?php

require_once __DIR__ . '/../../core/Testing/TestCase.php';
require_once __DIR__ . '/../../core/Testing/WebTestCase.php';
require_once __DIR__ . '/../../core/Testing/AssertionException.php';
require_once __DIR__ . '/../../core/Testing/SkippedException.php';

use Core\Testing\WebTestCase;

class DemoTest extends WebTestCase
{
    public function testTemplateSyntaxPageLoads()
    {
        $response = $this->visit('/demo/template-syntax');
        
        $this->assertStatus(200);
        $this->assertSee('Template Syntax Demo');
        $this->assertSee('Template syntax demo loaded!');
    }
    
    public function testTemplateSyntaxUsesAppLayout()
    {
        $response = $this->visit('/demo/template-syntax');
        
        $this->assertStatus(200);
        
        // Check for app layout elements
        $body = $this->lastResponse['body'];
        
        // Should have Bootstrap CSS (from app layout)
        $this->assertStringContains('bootstrap', $body);
        
        // Should have navbar/header elements (from app layout)
        $this->assertTrue(
            strpos($body, 'navbar') !== false || 
            strpos($body, 'nav-') !== false ||
            strpos($body, 'header') !== false,
            'Page should include navigation elements from app layout'
        );
        
        // Should have footer elements (from app layout)
        $this->assertTrue(
            strpos($body, 'footer') !== false ||
            strpos($body, '© 2025') !== false,
            'Page should include footer elements from app layout'
        );
    }
    
    public function testTemplateSyntaxHasRequiredContent()
    {
        $response = $this->visit('/demo/template-syntax');
        
        $this->assertStatus(200);
        
        // Check for specific template demo content
        $this->assertSee('Template Syntax Demo');
        $this->assertSee('Variable Output');
        $this->assertSee('Control Structures');
        $this->assertSee('Quick Reference');
        
        // Check for template syntax examples
        $this->assertSee('@section');
        $this->assertSee('@foreach');
        $this->assertSee('@if');
    }
} 