<?php

namespace Core\Testing;

abstract class WebTestCase extends TestCase
{
    protected $baseUrl = 'http://localhost:8585';
    protected $session = [];
    protected $lastResponse = null;
    protected $lastStatusCode = null;
    
    /**
     * Set base URL for web requests
     */
    protected function setBaseUrl(string $url): void
    {
        $this->baseUrl = rtrim($url, '/');
    }
    
    /**
     * Visit a page
     */
    protected function visit(string $uri): array
    {
        return $this->makeRequest('GET', $uri);
    }
    
    /**
     * Submit a form
     */
    protected function submitForm(string $uri, array $data = [], string $method = 'POST'): array
    {
        return $this->makeRequest($method, $uri, $data);
    }
    
    /**
     * Make HTTP request
     */
    protected function makeRequest(string $method, string $uri, array $data = []): array
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
            CURLOPT_TIMEOUT => 30,
            CURLOPT_COOKIEJAR => '/tmp/retrina_test_cookies.txt',
            CURLOPT_COOKIEFILE => '/tmp/retrina_test_cookies.txt'
        ]);
        
        // Set data for POST/PUT requests
        if (!empty($data) && in_array($method, ['POST', 'PUT', 'PATCH'])) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
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
            'body' => $body
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
     * Assert page is redirected
     */
    protected function assertRedirected(): void
    {
        $this->assertTrue(in_array($this->lastStatusCode, [301, 302, 303, 307, 308]), 
            "Expected redirect status but got {$this->lastStatusCode}");
    }
    
    /**
     * Assert page contains text
     */
    protected function assertSee(string $text): void
    {
        $this->assertStringContains($text, $this->lastResponse['body'], 
            "Expected to see '$text' on the page");
    }
    
    /**
     * Assert page does not contain text
     */
    protected function assertDontSee(string $text): void
    {
        $this->assertFalse(strpos($this->lastResponse['body'], $text) !== false, 
            "Did not expect to see '$text' on the page");
    }
    
    /**
     * Assert response body contains string
     */
    protected function assertResponseContains(string $text): void
    {
        $this->assertStringContains($text, $this->lastResponse['body'], 
            "Expected response to contain: $text");
    }
    
    /**
     * Assert page title contains text
     */
    protected function assertTitle(string $expectedTitle): void
    {
        preg_match('/<title[^>]*>(.*?)<\/title>/i', $this->lastResponse['body'], $matches);
        $actualTitle = $matches[1] ?? '';
        $this->assertStringContains($expectedTitle, $actualTitle, 
            "Expected title to contain '$expectedTitle' but got '$actualTitle'");
    }
    
    /**
     * Assert element exists
     */
    protected function assertElementExists(string $selector): void
    {
        $dom = new \DOMDocument();
        @$dom->loadHTML($this->lastResponse['body']);
        $xpath = new \DOMXPath($dom);
        
        $elements = $xpath->query($this->cssToXPath($selector));
        $this->assertTrue($elements->length > 0, "Element '$selector' not found on page");
    }
    
    /**
     * Assert element contains text
     */
    protected function assertElementContains(string $selector, string $text): void
    {
        $dom = new \DOMDocument();
        @$dom->loadHTML($this->lastResponse['body']);
        $xpath = new \DOMXPath($dom);
        
        $elements = $xpath->query($this->cssToXPath($selector));
        $this->assertTrue($elements->length > 0, "Element '$selector' not found on page");
        
        $found = false;
        foreach ($elements as $element) {
            if (strpos($element->textContent, $text) !== false) {
                $found = true;
                break;
            }
        }
        
        $this->assertTrue($found, "Element '$selector' does not contain text '$text'");
    }
    
    /**
     * Assert form exists
     */
    protected function assertFormExists(string $action = null): void
    {
        $selector = $action ? "form[action='$action']" : 'form';
        $this->assertElementExists($selector);
    }
    
    /**
     * Assert input field exists
     */
    protected function assertInputExists(string $name, string $type = null): void
    {
        $selector = "input[name='$name']";
        if ($type) {
            $selector = "input[name='$name'][type='$type']";
        }
        $this->assertElementExists($selector);
    }
    
    /**
     * Assert link exists
     */
    protected function assertLinkExists(string $href): void
    {
        $this->assertElementExists("a[href='$href']");
    }
    
    /**
     * Convert CSS selector to XPath
     */
    protected function cssToXPath(string $css): string
    {
        // Simple CSS to XPath conversion
        $css = trim($css);
        
        // Handle basic selectors
        if (strpos($css, '#') === 0) {
            // ID selector
            return "//*[@id='" . substr($css, 1) . "']";
        } elseif (strpos($css, '.') === 0) {
            // Class selector
            return "//*[contains(@class, '" . substr($css, 1) . "')]";
        } elseif (strpos($css, '[') !== false && strpos($css, ']') !== false) {
            // Attribute selector
            preg_match('/([a-zA-Z]+)?\[([^=]+)=?[\'"]?([^\]\'"]*)[\'"]\]?/', $css, $matches);
            $element = $matches[1] ?: '*';
            $attribute = $matches[2];
            $value = $matches[3] ?? '';
            
            if ($value) {
                return "//{$element}[@{$attribute}='{$value}']";
            } else {
                return "//{$element}[@{$attribute}]";
            }
        } else {
            // Element selector
            return "//{$css}";
        }
    }
    
    /**
     * Extract CSRF token from response
     */
    protected function getCsrfToken(): ?string
    {
        // Try multiple patterns to find CSRF token
        $patterns = [
            '/name="_token"[^>]*value="([^"]*)"/',
            '/value="([^"]*)"[^>]*name="_token"/',
            '/<input[^>]*name="_token"[^>]*value="([^"]*)"[^>]*>/',
            '/<input[^>]*value="([^"]*)"[^>]*name="_token"[^>]*>/'
        ];
        
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $this->lastResponse['body'], $matches)) {
                return $matches[1];
            }
        }
        
        return null;
    }
    
    /**
     * Clean up test cookies
     */
    protected function tearDown(): void
    {
        parent::tearDown();
        
        // Clean up cookie file
        if (file_exists('/tmp/retrina_test_cookies.txt')) {
            unlink('/tmp/retrina_test_cookies.txt');
        }
    }
} 