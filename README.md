# Retrina Framework

A lightweight, custom-built PHP MVC framework with clean architecture and powerful routing capabilities.

## Features

- 🏗️ **MVC Architecture** - Clean separation of concerns
- 🚀 **Custom Router** - Flexible routing with parameter support
- 🔧 **PSR-4 Autoloading** - Automatic class loading
- 💾 **Database Layer** - PDO-based database abstraction
- 📁 **Organized Structure** - Well-structured directory layout
- 🛡️ **Error Handling** - Built-in error and exception handling
- 🎯 **Simple to Use** - Easy to understand and extend

## Directory Structure

```
RetrinaTask/
├── app/
│   ├── Controllers/
│   │   ├── BaseController.php
│   │   └── HomeController.php
│   └── Models/
│       ├── BaseModel.php
│       └── User.php
├── config/
│   └── database.php
├── core/
│   ├── Application.php
│   └── Router.php
├── routes/
│   └── web.php
├── views/
│   └── home.php
├── .htaccess
├── index.php
└── README.md
```

## Getting Started

### Requirements

- PHP 7.4 or higher
- Apache/Nginx web server
- MySQL/MariaDB (optional, for database features)

### Installation

1. Clone or download this framework to your web server directory
2. Configure your web server to point to the framework's root directory
3. Update database configuration in `config/database.php`
4. Access your application through the web browser

### Basic Usage

#### Defining Routes

Routes are defined in `routes/web.php`:

```php
// Basic routes
$app->router()->get('/', 'HomeController@index');
$app->router()->post('/contact', 'ContactController@store');

// Routes with parameters
$app->router()->get('/user/{id}', 'UserController@show');
$app->router()->get('/posts/{id}/comments/{commentId}', 'CommentController@show');

// Closure routes
$app->router()->get('/hello/{name}', function($name) {
    echo "Hello, {$name}!";
});
```

#### Creating Controllers

Controllers should extend `BaseController`:

```php
<?php

namespace App\Controllers;

class UserController extends BaseController
{
    public function index()
    {
        $data = ['users' => ['John', 'Jane', 'Bob']];
        $this->view('users/index', $data);
    }
    
    public function show($id)
    {
        $data = ['user_id' => $id];
        $this->view('users/show', $data);
    }
    
    public function api()
    {
        $this->json(['status' => 'success', 'data' => []]);
    }
}
```

#### Creating Models

Models should extend `BaseModel`:

```php
<?php

namespace App\Models;

class User extends BaseModel
{
    protected $table = 'users';
    
    public function findByEmail($email)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch();
    }
}
```

#### Creating Views

Views are PHP files in the `views/` directory:

```php
<!DOCTYPE html>
<html>
<head>
    <title><?= $title ?? 'My App' ?></title>
</head>
<body>
    <h1><?= $heading ?></h1>
    <p><?= $message ?></p>
</body>
</html>
```

### Available Methods

#### Router Methods

- `get($uri, $controller)` - Define GET route
- `post($uri, $controller)` - Define POST route
- `put($uri, $controller)` - Define PUT route
- `delete($uri, $controller)` - Define DELETE route

#### Controller Methods

- `view($viewName, $data)` - Render a view with data
- `json($data, $statusCode)` - Return JSON response
- `redirect($url, $statusCode)` - Redirect to URL
- `setData($key, $value)` - Set data for views

#### Model Methods

- `findAll()` - Get all records
- `findById($id)` - Find record by ID
- `create($data)` - Create new record
- `update($id, $data)` - Update record
- `delete($id)` - Delete record

## Configuration

### Database Configuration

Update `config/database.php` with your database credentials:

```php
return [
    'host' => 'localhost',
    'database' => 'your_database',
    'username' => 'your_username',
    'password' => 'your_password',
    'charset' => 'utf8mb4',
    'options' => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]
];
```

### Debug Mode

Set debug mode in `index.php`:

```php
define('DEBUG', true); // Set to false in production
```

## Example Routes

The framework comes with several example routes:

- `/` - Home page
- `/about` - About page
- `/user/{id}` - User profile with parameter
- `/api` - JSON API endpoint
- `/hello/{name}` - Closure route example

## Contributing

Feel free to contribute to this framework by:

1. Reporting bugs
2. Suggesting new features
3. Submitting pull requests
4. Improving documentation

## License

This framework is open-source and available under the MIT License.

## Support

For support or questions, please open an issue in the project repository. 