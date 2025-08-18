<?php

namespace Core\Testing;

abstract class ApiTestCase extends TestCase
{
    protected $baseUrl = 'http://localhost:8585';
    protected $headers = [];
    protected $cookies = [];
    protected $lastResponse = null;
    protected $lastStatusCode = null;
    
    /**
     * Set base URL for API requests
     */
    protected function setBaseUrl(string $url): void
    {
        $this->baseUrl = rtrim($url, '/');
    }
    
    /**
     * Set header for requests
     */
    protected function setHeader(string $name, string $value): void
    {
        $this->headers[$name] = $value;
    }
    
    /**
     * Set cookie for requests
     */
    protected function setCookie(string $name, string $value): void
    {
        $this->cookies[$name] = $value;
    }
    
    /**
     * Make GET request
     */
    protected function get(string $uri, array $headers = []): array
    {
        return $this->makeRequest('GET', $uri, null, $headers);
    }
    
    /**
     * Make POST request
     */
    protected function post(string $uri, $data = [], array $headers = []): array
    {
        return $this->makeRequest('POST', $uri, $data, $headers);
    }
    
    /**
     * Make PUT request
     */
    protected function put(string $uri, array $data = [], array $headers = []): array
    {
        return $this->makeRequest('PUT', $uri, $data, $headers);
    }
    
    /**
     * Make DELETE request
     */
    protected function delete(string $uri, array $headers = []): array
    {
        return $this->makeRequest('DELETE', $uri, null, $headers);
    }
    
    /**
     * Make HTTP request
     */
    protected function makeRequest(string $method, string $uri, $data = null, array $headers = []): array
    {
        $url = $this->baseUrl . '/' . ltrim($uri, '/');
        
        // Initialize cURL
        $ch = curl_init();
        
        // Set basic options
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HEADER => true,
            CURLOPT_TIMEOUT => 30
        ]);
        
        // Set headers
        $requestHeaders = array_merge($this->headers, $headers);
        if (!empty($requestHeaders)) {
            $headerList = [];
            foreach ($requestHeaders as $name => $value) {
                $headerList[] = "$name: $value";
            }
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headerList);
        }
        
        // Set cookies
        if (!empty($this->cookies)) {
            $cookieString = '';
            foreach ($this->cookies as $name => $value) {
                $cookieString .= "$name=$value; ";
            }
            curl_setopt($ch, CURLOPT_COOKIE, rtrim($cookieString, '; '));
        }
        
        // Set data for POST/PUT requests
        if ($data && in_array($method, ['POST', 'PUT', 'PATCH'])) {
            if (is_array($data)) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            } else {
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            }
        }
        
        // Execute request
        $response = curl_exec($ch);
        $this->lastStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        
        if (curl_error($ch)) {
            throw new \Exception('cURL Error: ' . curl_error($ch));
        }
        
        curl_close($ch);
        
        // Parse response
        $headers = substr($response, 0, $headerSize);
        $body = substr($response, $headerSize);
        
        $this->lastResponse = [
            'status' => $this->lastStatusCode,
            'headers' => $this->parseHeaders($headers),
            'body' => $body,
            'json' => json_decode($body, true)
        ];
        
        return $this->lastResponse;
    }
    
    /**
     * Parse response headers
     */
    protected function parseHeaders(string $headerString): array
    {
        $headers = [];
        $lines = explode("\n", $headerString);
        
        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line) || strpos($line, 'HTTP/') === 0) {
                continue;
            }
            
            $parts = explode(':', $line, 2);
            if (count($parts) === 2) {
                $headers[trim($parts[0])] = trim($parts[1]);
            }
        }
        
        return $headers;
    }
    
    /**
     * Assert response status code
     */
    protected function assertStatus(int $expectedStatus): void
    {
        $this->assertEquals($expectedStatus, $this->lastStatusCode, 
            "Expected status $expectedStatus but got {$this->lastStatusCode}");
    }
    
    /**
     * Assert response is OK (200)
     */
    protected function assertOk(): void
    {
        $this->assertStatus(200);
    }
    
    /**
     * Assert response is created (201)
     */
    protected function assertCreated(): void
    {
        $this->assertStatus(201);
    }
    
    /**
     * Assert response is not found (404)
     */
    protected function assertNotFound(): void
    {
        $this->assertStatus(404);
    }
    
    /**
     * Assert response is unauthorized (401)
     */
    protected function assertUnauthorized(): void
    {
        $this->assertStatus(401);
    }
    
    /**
     * Assert response is forbidden (403)
     */
    protected function assertForbidden(): void
    {
        $this->assertStatus(403);
    }
    
    /**
     * Assert response contains JSON
     */
    protected function assertJson(): void
    {
        $this->assertNotNull($this->lastResponse['json'], 'Response is not valid JSON');
    }
    
    /**
     * Assert JSON response contains key
     */
    protected function assertJsonHas(string $key): void
    {
        $this->assertJson();
        $this->assertTrue(isset($this->lastResponse['json'][$key]), "JSON response missing key: $key");
    }
    
    /**
     * Assert JSON response has structure
     */
    protected function assertJsonStructure(array $structure): void
    {
        $this->assertJson();
        $this->validateJsonStructure($structure, $this->lastResponse['json']);
    }
    
    /**
     * Validate JSON structure recursively
     */
    protected function validateJsonStructure(array $structure, array $data, string $path = ''): void
    {
        foreach ($structure as $key => $value) {
            $currentPath = $path ? "$path.$key" : $key;
            
            if (is_numeric($key)) {
                // Array item
                $this->assertTrue(isset($data[$value]), "Missing key '$value' at path '$currentPath'");
            } elseif (is_array($value)) {
                // Nested structure
                $this->assertTrue(isset($data[$key]), "Missing key '$key' at path '$currentPath'");
                $this->validateJsonStructure($value, $data[$key], $currentPath);
            } else {
                // Simple key
                $this->assertTrue(isset($data[$key]), "Missing key '$key' at path '$currentPath'");
            }
        }
    }
    
    /**
     * Assert response body contains string
     */
    protected function assertResponseContains(string $text): void
    {
        $this->assertStringContains($text, $this->lastResponse['body']);
    }
    
    /**
     * Get last response
     */
    protected function getResponse(): array
    {
        return $this->lastResponse;
    }
    
    /**
     * Get last response JSON
     */
    protected function getJson(): ?array
    {
        return $this->lastResponse['json'] ?? null;
    }
} 