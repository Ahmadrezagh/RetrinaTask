# 🚀 Retrina Framework

A modern, lightweight PHP MVC framework with a powerful CLI assistant and advanced templating system. Built for developers who want Laravel-like elegance with the simplicity of pure PHP.

[![PHP Version](https://img.shields.io/badge/PHP-7.4%2B-blue.svg)](https://php.net)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)
[![Framework](https://img.shields.io/badge/Framework-Custom%20MVC-orange.svg)](/)

## ✨ Key Features

### 🎨 **Advanced Template Engine**
- **Blade-like syntax** with `{{ }}`, `{{{ }}}`, and `{!! !!}` expressions
- **Template inheritance** with `@extends` and `@section`
- **Partials support** with `@include`
- **Custom directives**: `@card`, `@csrf`, `@url`, `@foreach`, `@if`, `@isset`
- **Comment syntax** with `{{-- --}}`
- **Template compilation** and caching for performance
- **XSS protection** by default

### 🛠️ **Powerful CLI Assistant**
Complete command-line tools for rapid development:

```bash
# Development server
php retrina serve                    # Start on port 8585
php retrina serve --port=8080        # Custom port
php retrina serve --open             # Auto-open browser

# Code generation
php retrina make:controller UserController
php retrina make:controller PostController --resource
php retrina make:model User --migration
php retrina make:api-controller UserApiController --resource
php retrina make:view users/index --resource
php retrina make:migration CreatePostsTable --create=posts

# Database management
php retrina migrate                  # Run migrations
php retrina migrate --status         # Check migration status
php retrina migrate --rollback=3     # Rollback last 3 migrations

# Route management
php retrina route:list              # List all routes
php retrina route:list --method=GET # Filter by HTTP method
php retrina route:list --uri=api    # Filter by URI pattern
```

### 🗄️ **Elegant ORM & Database**
- **Active Record pattern** with Laravel-like syntax
- **Migration system** with up/down methods
- **Multi-database support**: SQLite, MySQL, PostgreSQL
- **Query builder** with method chaining
- **Model relationships** and attribute casting
- **Automatic timestamps** and password hashing

### 🌐 **RESTful API Support**
- **Separate API routes** in `routes/api.php`
- **JSON responses** with proper HTTP status codes
- **API controller generation** with CRUD methods
- **Built-in health check** and documentation endpoints

### 🔒 **Security Features**
- **CSRF protection** with token generation
- **XSS prevention** with automatic HTML escaping
- **Password hashing** with PHP's `password_hash()`
- **Input validation** and sanitization
- **Environment-based configuration**

## 🚀 Quick Start

### Installation

```bash
# Clone the repository
git clone <repository-url> retrina-project
cd retrina-project

# Start development server
php retrina serve

# Visit http://localhost:8585
```

### Create Your First Model

```bash
# Generate model with migration
php retrina make:model Product --migration

# Edit the migration file
# database/migrations/YYYY_MM_DD_HHMMSS_create_products_table.php

# Run migration
php retrina migrate
```

### Generate a Controller

```bash
# Basic controller
php retrina make:controller ProductController

# Resource controller with CRUD methods
php retrina make:controller ProductController --resource

# API controller
php retrina make:api-controller ProductApiController --resource
```

### Create Views

```bash
# Single view
php retrina make:view products/index

# Complete CRUD views
php retrina make:view products/index --resource
```

## 📁 Project Structure

```
retrina-framework/
├── 📁 app/
│   ├── 📁 Controllers/         # HTTP controllers
│   │   ├── 📁 Api/            # API controllers
│   │   ├── BaseController.php
│   │   └── HomeController.php
│   └── 📁 Models/             # Data models
│       ├── BaseModel.php      # ORM base class
│       └── User.php           # User model
├── 📁 config/
│   └── database.php           # Database configuration
├── 📁 core/                   # Framework core
│   ├── 📁 Command/            # CLI commands
│   ├── Application.php        # Main application
│   ├── Router.php             # HTTP routing
│   ├── ViewEngine.php         # Template engine
│   ├── Migration.php          # Database migrations
│   └── Environment.php        # Environment handling
├── 📁 database/
│   └── 📁 migrations/         # Database migrations
├── 📁 routes/
│   ├── web.php               # Web routes
│   └── api.php               # API routes
├── 📁 storage/
│   ├── 📁 cache/             # Template cache
│   └── database.sqlite       # SQLite database
├── 📁 views/                 # Template files
│   ├── 📁 layouts/           # Layout templates
│   ├── 📁 partials/          # Partial templates
│   └── 📁 components/        # Reusable components
├── .env                      # Environment variables
├── .env.example              # Environment template
├── index.php                 # Application entry point
├── retrina.php               # CLI entry point
└── retrina                   # CLI launcher script
```

## 🎨 Template System

### Basic Syntax

```php
{{-- resources/views/users/index.retrina.php --}}
@extends('layouts.app')

@section('title', 'Users List')

@section('content')
    <div class="container">
        <h1>{{ $pageTitle }}</h1>
        
        @if(isset($users) && count($users) > 0)
            @foreach($users as $user)
                @card
                    <h5>{{ $user->full_name }}</h5>
                    <p class="text-muted">{{ $user->email }}</p>
                    <small>Active: {!! $user->is_active ? '<span class="text-success">Yes</span>' : '<span class="text-danger">No</span>' !!}</small>
                @endcard
            @endforeach
        @else
            <div class="alert alert-info">
                No users found.
            </div>
        @endif
    </div>
@endsection
```

### Custom Directives

```php
{{-- CSRF Protection --}}
@csrf

{{-- URL Generation --}}
<a href="@url('/users/create')">Create User</a>

{{-- Custom Card Component --}}
@card
    <h5>Card Title</h5>
    <p>Card content goes here</p>
@endcard

{{-- Safe HTML Output --}}
{!! $htmlContent !!}

{{-- Escaped Output (default) --}}
{{ $userInput }}

{{-- Raw Output --}}
{{{ $trustedContent }}}
```

## 🗄️ Database & Models

### Model Definition

```php
<?php

namespace App\Models;

class User extends BaseModel
{
    protected $table = 'users';
    
    protected $fillable = [
        'username', 'email', 'password', 
        'first_name', 'last_name', 'is_active'
    ];
    
    protected $hidden = ['password', 'remember_token'];
    
    protected $casts = [
        'is_active' => 'boolean',
        'email_verified_at' => 'datetime'
    ];
    
    // Automatic password hashing
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = password_hash($value, PASSWORD_DEFAULT);
    }
    
    // Custom accessor
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }
    
    // Static query methods
    public static function findByEmail($email)
    {
        return static::where('email', $email)->first();
    }
    
    public static function active()
    {
        return static::where('is_active', true);
    }
}
```

### Using Models

```php
// Create users
$user = User::create([
    'username' => 'john_doe',
    'email' => 'john@example.com',
    'password' => 'secret123',
    'first_name' => 'John',
    'last_name' => 'Doe'
]);

// Query users
$users = User::all();
$activeUsers = User::active()->get();
$user = User::findByEmail('john@example.com');

// Update and verify
$user->updateLastLogin();
if ($user->verifyPassword('secret123')) {
    echo 'Password correct!';
}
```

### Migrations

```php
<?php

use Core\Migration;

class CreateUsersTable extends Migration
{
    public function up()
    {
        $columns = [
            '`id` INT AUTO_INCREMENT PRIMARY KEY',
            '`username` VARCHAR(50) NOT NULL UNIQUE',
            '`email` VARCHAR(100) NOT NULL UNIQUE',
            '`password` VARCHAR(255) NOT NULL',
            '`first_name` VARCHAR(50) NOT NULL',
            '`last_name` VARCHAR(50) NOT NULL',
            '`is_active` BOOLEAN NOT NULL DEFAULT TRUE',
            ...$this->timestamps()
        ];
        
        $this->createTable('users', $columns);
    }
    
    public function down()
    {
        $this->dropTable('users');
    }
}
```

## 🌐 API Development

### API Controller

```php
<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\User;

class UserApiController extends BaseController
{
    public function index()
    {
        $users = User::all();
        
        return $this->jsonResponse([
            'status' => 'success',
            'data' => $users,
            'count' => count($users)
        ]);
    }
    
    public function store()
    {
        $input = $this->getJsonInput();
        
        // Validation
        $required = ['username', 'email', 'first_name', 'last_name'];
        $missing = [];
        
        foreach ($required as $field) {
            if (!isset($input[$field]) || empty($input[$field])) {
                $missing[] = $field;
            }
        }
        
        if (!empty($missing)) {
            return $this->jsonError(
                'Missing required fields: ' . implode(', ', $missing),
                400,
                'VALIDATION_ERROR'
            );
        }
        
        $user = User::create($input);
        
        return $this->jsonResponse([
            'status' => 'success',
            'message' => 'User created successfully',
            'data' => $user
        ], 201);
    }
}
```

### API Routes

```php
<?php
// routes/api.php

// Health check
$router->get('/api/health', function() {
    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'healthy',
        'timestamp' => date('c'),
        'database' => 'connected'
    ]);
});

// User API endpoints  
$router->get('/api/users', 'Api\UserApiController@index');
$router->post('/api/users', 'Api\UserApiController@store');
$router->get('/api/users/(\d+)', 'Api\UserApiController@show');
$router->put('/api/users/(\d+)', 'Api\UserApiController@update');
$router->delete('/api/users/(\d+)', 'Api\UserApiController@destroy');
```

## ⚙️ Configuration

### Environment Variables

```bash
# .env
APP_NAME="Retrina Framework"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8585

DB_DRIVER=sqlite
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=retrina_framework
DB_USERNAME=root
DB_PASSWORD=
DB_SQLITE_PATH=storage/database.sqlite
```

### Database Configuration

```php
<?php
// config/database.php

use Core\Environment;

$driver = Environment::get('DB_DRIVER', 'sqlite');

if ($driver === 'sqlite') {
    return [
        'driver' => 'sqlite',
        'database' => __DIR__ . '/../' . Environment::get('DB_SQLITE_PATH', 'storage/database.sqlite')
    ];
} elseif ($driver === 'mysql') {
    return [
        'driver' => 'mysql',
        'host' => Environment::get('DB_HOST', 'localhost'),
        'port' => Environment::get('DB_PORT', 3306),
        'database' => Environment::get('DB_DATABASE'),
        'username' => Environment::get('DB_USERNAME'),
        'password' => Environment::get('DB_PASSWORD'),
        'charset' => Environment::get('DB_CHARSET', 'utf8mb4'),
        'options' => [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    ];
}
```

## 🧪 Testing & Development

### Development Server

```bash
# Start development server
php retrina serve

# Custom port
php retrina serve --port=8080

# Bind to all interfaces  
php retrina serve --host=0.0.0.0

# Auto-open browser
php retrina serve --open
```

### Route Debugging

```bash
# List all routes
php retrina route:list

# Filter by method
php retrina route:list --method=GET

# Filter by URI pattern
php retrina route:list --uri=api
```

### Migration Management

```bash
# Check migration status
php retrina migrate --status

# Run migrations
php retrina migrate

# Rollback migrations
php retrina migrate --rollback=3
php retrina migrate --rollback-all
```

## 🔧 Available CLI Commands

### Code Generation
- `make:controller` - Generate controller classes
- `make:model` - Generate model classes  
- `make:migration` - Generate migration files
- `make:view` - Generate view templates
- `make:api-controller` - Generate API controllers

### Development Tools
- `serve` - Start development server
- `migrate` - Database migration management
- `route:list` - Display registered routes

### Command Options
- `--resource` - Generate resource controllers/views with CRUD methods
- `--migration` / `-m` - Create migration when generating models
- `--force` / `-f` - Overwrite existing files
- `--create=table` - Create new table migration
- `--table=table` - Modify existing table migration

## 🚀 Performance Features

- **Template compilation** and caching
- **Optimized routing** with pattern matching
- **Database connection pooling**
- **Lazy loading** of framework components
- **Minimal memory footprint**

## 🛡️ Security

- **CSRF protection** built-in
- **XSS prevention** with automatic escaping
- **SQL injection protection** with prepared statements
- **Password hashing** with `password_hash()`
- **Environment-based secrets** management

## 📈 What's Next

- [ ] Middleware system
- [ ] Authentication guards
- [ ] Event system
- [ ] Queue management
- [ ] File upload handling
- [ ] Email integration
- [ ] Testing framework
- [ ] Package management

## 🤝 Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## 📄 License

This project is open-sourced software licensed under the [MIT license](LICENSE).

---

**Built with ❤️ for modern PHP development** 