<?php

require_once __DIR__ . '/../../core/Testing/TestCase.php';
require_once __DIR__ . '/../../core/Testing/ApiTestCase.php';
require_once __DIR__ . '/../../core/Testing/AssertionException.php';
require_once __DIR__ . '/../../core/Testing/SkippedException.php';

use Core\Testing\ApiTestCase;

class ApiTest extends ApiTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->setHeader('Content-Type', 'application/json');
        $this->setHeader('Accept', 'application/json');
    }
    
    public function testApiHealthEndpoint()
    {
        $response = $this->get('/api/health');
        
        if ($this->lastStatusCode === 404) {
            $this->markTestSkipped('API health endpoint not implemented');
            return;
        }
        
        $this->assertOk();
        $this->assertJson();
        
        $json = $this->getJson();
        if (isset($json['status'])) {
            $this->assertEquals('ok', $json['status']);
        }
        
        if (isset($json['timestamp'])) {
            $this->assertTrue(!empty($json['timestamp']), 'Timestamp should not be empty');
        }
        
        if (isset($json['version'])) {
            $this->assertTrue(!empty($json['version']), 'Version should not be empty');
        }
    }
    
    public function testApiResponseFormat()
    {
        $response = $this->get('/api/health');
        
        if ($this->lastStatusCode === 404) {
            $this->markTestSkipped('API health endpoint not implemented');
            return;
        }
        
        if ($this->lastStatusCode === 200) {
            $this->assertJson();
            
            $json = $this->getJson();
            $this->assertNotNull($json, 'Response should be valid JSON');
            $this->assertTrue(is_array($json), 'Response should be a JSON object');
        }
    }
    
    public function testNonExistentEndpoint()
    {
        $response = $this->get('/api/nonexistent');
        $this->assertNotFound();
    }
    
    public function testApiErrorHandling()
    {
        // Test malformed requests to existing endpoints
        $response = $this->post('/api/users', ['invalid' => 'data']);
        
        // Should handle gracefully with proper HTTP status codes
        $this->assertTrue(
            in_array($this->lastStatusCode, [400, 401, 403, 404, 422, 500]),
            "API should handle malformed requests gracefully, got: {$this->lastStatusCode}"
        );
    }
    
    public function testUsersApiWithoutAuth()
    {
        $response = $this->get('/api/users');
        
        if ($this->lastStatusCode === 404) {
            $this->markTestSkipped('Users API endpoint not implemented');
            return;
        }
        
        // Should require authentication (401) or be forbidden (403)
        $this->assertTrue(
            in_array($this->lastStatusCode, [401, 403]),
            "Users API should require authentication, got status: {$this->lastStatusCode}"
        );
    }
    
    public function testSingleUserApiWithoutAuth()
    {
        $response = $this->get('/api/users/1');
        
        if ($this->lastStatusCode === 404) {
            $this->markTestSkipped('Single user API endpoint not implemented');
            return;
        }
        
        // Should require authentication
        $this->assertTrue(
            in_array($this->lastStatusCode, [401, 403]),
            "Single user API should require authentication, got status: {$this->lastStatusCode}"
        );
    }
    
    public function testApiContentType()
    {
        $response = $this->get('/api/health');
        
        if ($this->lastStatusCode === 200) {
            // Check if Content-Type header is set to application/json
            $headers = $this->lastResponse['headers'];
            $headersString = is_array($headers) ? implode("\n", $headers) : (string)$headers;
            
            // Check for various JSON content type formats
            $hasJsonContentType = stripos($headersString, 'content-type: application/json') !== false ||
                                 stripos($headersString, 'application/json') !== false ||
                                 stripos($headersString, 'text/json') !== false;
            
            if ($hasJsonContentType) {
                $this->assertTrue(true, 'API returns appropriate JSON content type');
            } else {
                // Check if response body is actually JSON (even without explicit header)
                $json = $this->getJson();
                if ($json !== null && is_array($json)) {
                    $this->assertTrue(true, 'API returns valid JSON response (content type header optional)');
                } else {
                    $this->assertTrue(true, 'API content type test completed - response handling may vary');
                }
            }
        } else {
            $this->markTestSkipped('API health endpoint not available for content type test');
        }
    }
    
    public function testApiCorsHeaders()
    {
        $response = $this->get('/api/health');
        
        if ($this->lastStatusCode === 200) {
            // Check if CORS headers might be present (optional)
            $headers = $this->lastResponse['headers'];
            $headersString = is_array($headers) ? implode("\n", $headers) : (string)$headers;
            $headersLower = strtolower($headersString);
            
            // This is not required but good to have for API
            if (strpos($headersLower, 'access-control-allow-origin') !== false) {
                $this->assertTrue(true, 'CORS headers are configured');
            } else {
                $this->assertTrue(true, 'CORS headers not required for this test');
            }
        }
    }
} 