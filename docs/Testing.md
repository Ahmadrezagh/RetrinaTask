# Testing Framework

Retrina Framework includes a comprehensive testing system for both API and web UI testing.

## Overview

The testing framework provides:
- **Unit Tests**: Test individual components and functions
- **Feature Tests**: Test application features end-to-end
- **API Tests**: Test REST API endpoints
- **Web Tests**: Test web UI interactions

## Running Tests

### Basic Usage

```bash
# Run all tests
php retrina test

# Run with verbose output
php retrina test --verbose

# Generate HTML report
php retrina test --report

# Run specific directory
php retrina test --directory=tests/Unit

# Run without colors
php retrina test --no-colors
```

## Writing Tests

### Unit Tests

Create unit tests in `tests/Unit/` directory:

```php
<?php

use Core\Testing\TestCase;

class ExampleTest extends TestCase
{
    public function testBasicAssertion()
    {
        $this->assertTrue(true);
        $this->assertEquals(2, 1 + 1);
    }
    
    public function testStringOperations()
    {
        $text = "Hello World";
        $this->assertStringContains("World", $text);
    }
}
```

### API Tests

Create API tests in `tests/Api/` directory:

```php
<?php

use Core\Testing\ApiTestCase;

class UserApiTest extends ApiTestCase
{
    public function testGetUsers()
    {
        $response = $this->get('/api/users');
        
        $this->assertOk();
        $this->assertJson();
        $this->assertJsonStructure([
            'data' => [
                '*' => ['id', 'username', 'email']
            ]
        ]);
    }
    
    public function testCreateUser()
    {
        $userData = [
            'username' => 'testuser',
            'email' => 'test@example.com'
        ];
        
        $response = $this->post('/api/users', $userData);
        
        $this->assertCreated();
        $this->assertJsonHas('id');
    }
}
```

### Web/UI Tests

Create web tests in `tests/Web/` directory:

```php
<?php

use Core\Testing\WebTestCase;

class LoginTest extends WebTestCase
{
    public function testLoginPage()
    {
        $response = $this->visit('/login');
        
        $this->assertOk();
        $this->assertSee('Login');
        $this->assertFormExists('/login');
        $this->assertInputExists('username');
    }
    
    public function testLogin()
    {
        $this->visit('/login');
        $csrfToken = $this->getCsrfToken();
        
        $response = $this->submitForm('/login', [
            'username' => 'admin',
            'password' => 'admin123',
            '_token' => $csrfToken
        ]);
        
        $this->assertRedirected();
    }
}
```

## Available Assertions

### Basic Assertions

- `assertTrue($condition)` - Assert condition is true
- `assertFalse($condition)` - Assert condition is false
- `assertEquals($expected, $actual)` - Assert values are equal
- `assertNotEquals($expected, $actual)` - Assert values are not equal
- `assertNull($value)` - Assert value is null
- `assertNotNull($value)` - Assert value is not null

### String Assertions

- `assertStringContains($needle, $haystack)` - Assert string contains substring
- `assertContains($needle, $array)` - Assert array contains value

### API Assertions

- `assertStatus($code)` - Assert HTTP status code
- `assertOk()` - Assert 200 status
- `assertCreated()` - Assert 201 status
- `assertNotFound()` - Assert 404 status
- `assertUnauthorized()` - Assert 401 status
- `assertJson()` - Assert response is JSON
- `assertJsonHas($key)` - Assert JSON has key
- `assertJsonStructure($structure)` - Assert JSON structure

### Web Assertions

- `assertSee($text)` - Assert page contains text
- `assertDontSee($text)` - Assert page doesn't contain text
- `assertTitle($title)` - Assert page title
- `assertElementExists($selector)` - Assert element exists
- `assertFormExists($action)` - Assert form exists
- `assertInputExists($name)` - Assert input field exists
- `assertLinkExists($href)` - Assert link exists

## Test Structure

### Directory Structure

```
tests/
├── Unit/           # Unit tests
├── Feature/        # Feature tests
├── Api/            # API tests
└── Web/            # Web/UI tests
```

### Test Classes

All test classes must:
1. Extend the appropriate base class (`TestCase`, `ApiTestCase`, `WebTestCase`)
2. Have methods starting with `test`
3. Be named with `Test` suffix

### Setup and Teardown

```php
protected function setUp(): void
{
    parent::setUp();
    // Set up test environment
}

protected function tearDown(): void
{
    // Clean up after test
    parent::tearDown();
}
```

## HTTP Testing

### Making Requests

```php
// GET request
$response = $this->get('/api/users');

// POST request
$response = $this->post('/api/users', $data);

// PUT request
$response = $this->put('/api/users/1', $data);

// DELETE request
$response = $this->delete('/api/users/1');
```

### Setting Headers

```php
$this->setHeader('Authorization', 'Bearer ' . $token);
$this->setHeader('Content-Type', 'application/json');
```

### Working with Cookies

```php
$this->setCookie('session_id', 'abc123');
```

## Web Testing

### Page Interactions

```php
// Visit a page
$this->visit('/login');

// Submit a form
$this->submitForm('/login', [
    'username' => 'admin',
    'password' => 'password'
]);

// Extract CSRF token
$token = $this->getCsrfToken();
```

### Element Testing

```php
// Check if element exists
$this->assertElementExists('#login-form');
$this->assertElementExists('.btn-primary');

// Check element content
$this->assertElementContains('h1', 'Welcome');
```

## Test Configuration

### Base URLs

```php
// Set different base URL for testing
protected function setUp(): void
{
    parent::setUp();
    $this->setBaseUrl('http://test.localhost:8585');
}
```

### Authentication

```php
protected function authenticateAsAdmin(): void
{
    $this->visit('/login');
    $token = $this->getCsrfToken();
    
    $this->submitForm('/login', [
        'username' => 'admin',
        'password' => 'admin123',
        '_token' => $token
    ]);
}
```

## Best Practices

1. **Keep tests focused** - Each test should test one thing
2. **Use descriptive names** - Test names should describe what they test
3. **Clean up after tests** - Use tearDown() to clean up test data
4. **Use factories** - Create test data factories for complex objects
5. **Mock external services** - Don't rely on external APIs in tests
6. **Test edge cases** - Test both success and failure scenarios

## Continuous Integration

The test suite can be integrated with CI/CD pipelines:

```bash
#!/bin/bash
# Run tests and generate report
php retrina test --report

# Exit with error code if tests fail
if [ $? -ne 0 ]; then
    echo "Tests failed!"
    exit 1
fi
```

## Debugging Tests

### Verbose Output

```bash
php retrina test --verbose
```

### Skip Tests

```php
public function testSomething()
{
    $this->markTestSkipped('Not implemented yet');
}
```

### Debug Output

```php
public function testSomething()
{
    $response = $this->get('/api/users');
    
    // Debug response
    var_dump($this->getResponse());
    var_dump($this->getJson());
}
``` 