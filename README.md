# 🚀 Retrina Framework

A modern, powerful PHP framework inspired by Laravel, featuring an advanced ORM, comprehensive middleware system, template engine, CLI tools, and robust database management.

[![PHP Version](https://img.shields.io/badge/PHP-8.2%2B-blue.svg)](https://php.net/)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)
[![Framework](https://img.shields.io/badge/Framework-Retrina-purple.svg)](https://github.com)

## ✨ Features

### 🏗️ Core Architecture
- **MVC Pattern** - Clean separation of concerns with Models, Views, and Controllers
- **Dependency Injection** - Modern IoC container for better code organization
- **PSR-4 Autoloading** - Standard PHP autoloading with namespace support
- **Error Handling** - Comprehensive error reporting and debugging tools

### 🗄️ Database & ORM
- **Laravel-like ORM** - Eloquent-style model relationships and query building
- **Query Builder** - Fluent interface for complex database queries
- **Schema Builder** - Database-agnostic schema definitions
- **Multi-Database Support** - MySQL, PostgreSQL, and SQLite compatibility
- **Migration System** - Version control for your database schema
- **Database Seeding** - Populate your database with test data

### 🛡️ Security & Middleware
- **Comprehensive Middleware System** - Authentication, authorization, CORS, rate limiting
- **CSRF Protection** - Built-in cross-site request forgery protection
- **Session Management** - Secure session handling with customizable drivers
- **Role-based Access Control** - User roles and permissions system
- **Input Validation** - Request validation and sanitization

### 🎨 Templating & Views
- **Blade-like Template Engine** - Familiar syntax with advanced features
- **Template Inheritance** - Layout system with sections and yields
- **Component System** - Reusable template components
- **Template Caching** - Compiled templates for better performance
- **Responsive Views** - Bootstrap 5 integration for modern UI

### ⚡ CLI Tools (Retrina Artisan)
- **Code Generation** - Generate models, controllers, API controllers, views, and migrations
- **Database Management** - Migration and seeding commands with fresh migration support
- **Development Server** - Built-in development server with custom port support
- **Route Management** - List and analyze your application routes
- **Testing Framework** - Comprehensive test suite with Feature, API, Unit, and Web tests
- **Cache Management** - Clear compiled view cache and optimize performance

### 🌐 API & Routing
- **RESTful Routing** - Clean URL patterns with parameter binding
- **API Support** - JSON responses and API middleware
- **Route Groups** - Organize routes with shared middleware and prefixes
- **Route Caching** - Optimized routing for production environments

### 🧪 Testing Framework
- **Multiple Test Types** - Feature, API, Unit, and Web test suites
- **Test Utilities** - WebTestCase and ApiTestCase for comprehensive testing
- **Assertion Library** - Rich set of assertions for testing web and API responses
- **Test Runner** - CLI test command with verbose output and selective test execution
- **HTML Reports** - Detailed test reports with pass/fail statistics

## 🎯 Current Project Status

**Retrina Framework** is a **complete, production-ready PHP framework** with comprehensive features implemented and tested. This project demonstrates a full-stack web application framework with modern PHP practices.

### ✅ **What's Implemented**

#### **Core Architecture**
- ✅ Complete MVC pattern with clean separation of concerns
- ✅ PSR-4 autoloading and modern PHP 8.2+ features
- ✅ Comprehensive error handling and debugging tools
- ✅ Environment configuration with `.env` support

#### **Database & ORM System**
- ✅ Laravel-style Eloquent ORM with relationships
- ✅ Fluent Query Builder with method chaining
- ✅ Schema Builder for database-agnostic migrations
- ✅ Multi-database support (MySQL, PostgreSQL, SQLite)
- ✅ Complete migration system with version control
- ✅ Database seeding with demo data

#### **Security & Authentication**
- ✅ Complete user authentication system (login/register/logout)
- ✅ Role-based authorization (admin/user roles)
- ✅ 10+ middleware types (Auth, CSRF, CORS, Rate Limiting, etc.)
- ✅ Session management with flash messages
- ✅ Input validation and sanitization

#### **Template Engine & UI**
- ✅ Custom Blade-like template engine with compilation
- ✅ Template inheritance and component system
- ✅ 15+ template directives (@extends, @section, @foreach, @csrf, etc.)
- ✅ Bootstrap 5 integration for responsive design
- ✅ Complete user interface with dashboard and admin panel

#### **CLI Tools (Retrina Artisan)**
- ✅ 15+ CLI commands for development workflow
- ✅ Code generators (models, controllers, views, migrations, seeders)
- ✅ Database management (migrate, fresh, seed, rollback)
- ✅ Development server with custom port support
- ✅ Route listing and inspection tools
- ✅ Testing framework integration
- ✅ Cache management utilities

#### **Testing Framework**
- ✅ Complete testing system with 4 test types (Feature, API, Unit, Web)
- ✅ Test utilities (WebTestCase, ApiTestCase)
- ✅ Rich assertion library for comprehensive testing
- ✅ CLI test runner with filtering and verbose output
- ✅ Implemented test suites for authentication, admin, and API

#### **API Development**
- ✅ RESTful API architecture with JSON responses
- ✅ Separate API routing and controllers
- ✅ API-specific middleware stack
- ✅ CORS support for cross-origin requests
- ✅ Comprehensive API testing suite

#### **Production Features**
- ✅ File upload system (profile images)
- ✅ User profile management
- ✅ Admin panel with user CRUD operations
- ✅ Search and pagination functionality
- ✅ Error pages and logging
- ✅ Security headers and CSRF protection

### 🎨 **Demo Application Features**
- **User Authentication**: Complete login/register system
- **User Dashboard**: Personalized user interface
- **Profile Management**: Edit profile, change password, upload images
- **Admin Panel**: User management with search, filter, and CRUD operations
- **API Endpoints**: Health check, user management APIs
- **Documentation**: Complete framework documentation with examples
- **Demo Data**: Pre-populated users (admin/user accounts)

### 📊 **Technical Achievements**
- **15+ CLI Commands** implemented and tested
- **10+ Middleware Classes** for comprehensive request handling
- **4 Test Types** with 10+ test files covering major functionality
- **Multi-Database Support** with automatic schema detection
- **Template Compilation** with caching for performance
- **File Organization** following PSR-4 and modern PHP standards

## 🚀 Quick Start

### Prerequisites
- PHP 8.2 or higher
- Composer (optional, framework is self-contained)
- MySQL, PostgreSQL, or SQLite

### Installation
```bash
# Clone the repository
git clone https://github.com/your-repo/retrina-framework.git
cd retrina-framework

# Set up environment
cp .env.example .env
# Edit .env with your database configuration

# Set up database
php retrina migrate:fresh --seed
```

### Development Server
```bash
# Start the development server
php retrina serve

# Custom port
php retrina serve --port=8080

# Your app is now running at http://localhost:8585
```

## 📚 Documentation

### Database Configuration
Configure your database in the `.env` file:

```env
# Database Configuration
DB_DRIVER=mysql          # mysql, sqlite, postgres
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=retrina_framework
DB_USERNAME=root
DB_PASSWORD=your_password
```

### CLI Commands

#### Database Commands
```bash
# Run migrations
php retrina migrate

# Fresh migration with seeders
php retrina migrate:fresh --seed

# Rollback migrations
php retrina migrate:rollback

# Database seeding
php retrina db:seed
php retrina db:seed --class=UserSeeder
```

#### Code Generation
```bash
# Generate model
php retrina make:model User
php retrina make:model Post -m  # with migration

# Generate controller
php retrina make:controller UserController
php retrina make:controller PostController -r  # resource controller

# Generate API controller
php retrina make:api-controller ApiController

# Generate API controller
php retrina make:api-controller ApiController

# Generate migration
php retrina make:migration create_posts_table

# Generate seeder
php retrina make:seeder UserSeeder

# Generate view
php retrina make:view posts.index
```

#### Development Tools
```bash
# Start development server
php retrina serve --port=8585

# List all routes
php retrina route:list

# List available commands
php retrina list

# Run tests
php retrina test
php retrina test --verbose
php retrina test tests/Feature/

# Clear view cache
php retrina view:clear

# Run tests
php retrina test
php retrina test --verbose
php retrina test tests/Feature/

# Clear view cache
php retrina view:clear
```

### Database Usage

#### Models
```php
<?php
namespace App\Models;

use Core\Database\Model;

class User extends Model
{
    protected $table = 'users';
    protected $fillable = ['username', 'email', 'password', 'role'];
    
    // Relationships
    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}

// Usage examples
$users = User::all();
$user = User::find(1);
$admins = User::where('role', 'admin')->get();
$user = User::create([
    'username' => 'john',
    'email' => 'john@example.com',
    'password' => password_hash('secret', PASSWORD_DEFAULT),
    'role' => 'user'
]);
```

#### Query Builder
```php
use Core\Database\DB;

// Basic queries
$users = DB::table('users')->get();
$user = DB::table('users')->where('id', 1)->first();

// Advanced queries
$results = DB::table('users')
    ->select(['username', 'email'])
    ->where('role', 'admin')
    ->orderBy('created_at', 'desc')
    ->limit(10)
    ->get();

// Joins
$posts = DB::table('posts')
    ->join('users', 'posts.user_id', '=', 'users.id')
    ->select(['posts.*', 'users.username'])
    ->where('posts.published', true)
    ->get();
```

#### Migrations
```php
<?php
use Core\Database\Schema\Schema;
use Core\Migration;

class CreatePostsTable extends Migration
{
    public function up()
    {
        Schema::create('posts', function($table) {
            $table->id();
            $table->string('title');
            $table->text('content');
            $table->integer('user_id');
            $table->boolean('published')->default(false);
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('posts');
    }
}
```

### Routing & Controllers

#### Route Definition
```php
// routes/web.php
$router->get('/', function() {
    return view('home/index');
});

$router->get('/users/{id}', 'UserController@show');

$router->group(['middleware' => ['auth']], function($router) {
    $router->get('/dashboard', 'DashboardController@index');
    $router->resource('/posts', 'PostController');
});

// API routes (routes/api.php)
$router->group(['middleware' => ['api']], function($router) {
    $router->get('/api/users', function() {
        return json_encode(User::all());
    });
});
```

#### Controllers
```php
<?php
namespace App\Controllers;

use Core\BaseController;
use App\Models\User;

class UserController extends BaseController
{
    public function index()
    {
        $users = User::all();
        return view('users/index', compact('users'));
    }
    
    public function show($id)
    {
        $user = User::find($id);
        return view('users/show', compact('user'));
    }
    
    public function store()
    {
        $user = User::create($_POST);
        return redirect('/users');
    }
}
```

### Views & Templates

#### Template Syntax
```php
{{-- views/layouts/app.retrina.php --}}
<!DOCTYPE html>
<html>
<head>
    <title>@yield('title', 'Retrina Framework')</title>
</head>
<body>
    <nav>@include('partials/navigation')</nav>
    <main>@yield('content')</main>
    <footer>@yield('footer')</footer>
</body>
</html>

{{-- views/users/index.retrina.php --}}
@extends('layouts.app')

@section('title', 'Users')

@section('content')
<div class="container">
    <h1>Users</h1>
    @foreach($users as $user)
        <div class="user-card">
            <h3>{{ $user['username'] }}</h3>
            <p>{{ $user['email'] }}</p>
            @if($user['role'] === 'admin')
                <span class="badge">Admin</span>
            @endif
        </div>
    @endforeach
</div>
@endsection
```

### Middleware System

#### Available Middleware
- **auth** - Require authentication
- **guest** - Allow only non-authenticated users
- **admin** - Require admin role
- **cors** - Handle CORS headers
- **csrf** - CSRF protection
- **throttle** - Rate limiting
- **json** - JSON request/response handling

#### Middleware Usage
```php
// Apply to individual routes
$router->get('/dashboard', 'DashboardController@index', ['auth']);

// Apply to route groups
$router->group(['middleware' => ['auth', 'admin']], function($router) {
    $router->get('/admin', 'AdminController@index');
    $router->resource('/admin/users', 'AdminUserController');
});

// API middleware
$router->group(['middleware' => ['api', 'throttle:60,1']], function($router) {
    $router->get('/api/data', 'ApiController@getData');
});
```

## 🛡️ Security Features

### Authentication
```php
// Login
if (password_verify($password, $user['password'])) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['user_role'] = $user['role'];
}

// Logout
unset($_SESSION['user_id'], $_SESSION['username'], $_SESSION['user_role']);
```

### CSRF Protection
```php
{{-- In forms --}}
<form method="POST" action="/users">
    @csrf
    <input type="text" name="username" required>
    <button type="submit">Create User</button>
</form>
```

### Role-based Access
```php
// Check roles in controllers
if (!User::isAdmin($_SESSION)) {
    return redirect('/unauthorized');
}

// Or use admin middleware
$router->get('/admin', 'AdminController@index', ['admin']);
```

## 🧪 Testing & Development

### Demo Credentials
After running `php retrina migrate:fresh --seed`:

**Admin Account:**
- Username: `admin`
- Password: `admin123`
- Email: `admin@retrina.local`
- Role: `admin`

**User Account:**
- Username: `user`
- Password: `user123`
- Email: `user@retrina.local`
- Role: `user`

### Development Workflow
```bash
# Start fresh development environment
php retrina migrate:fresh --seed

# Start development server
php retrina serve

# Generate new components
php retrina make:model Product -m
php retrina make:controller ProductController -r
php retrina make:view products.index

# Run tests
php retrina test

# Test your changes
curl http://localhost:8585/api/users
```

### Testing
The framework includes a comprehensive testing system:

```bash
# Run all tests
php retrina test

# Run with verbose output
php retrina test --verbose

# Run specific test types
php retrina test tests/Feature/     # Feature tests
php retrina test tests/Api/         # API tests
php retrina test tests/Unit/        # Unit tests

# Run specific test files
php retrina test tests/Feature/LoginTest.php
```

**Available Test Types:**
- **Feature Tests**: End-to-end functionality testing
- **API Tests**: REST API endpoint testing
- **Unit Tests**: Individual component testing
- **Web Tests**: UI and form interaction testing

## 📁 Project Structure

```
retrina-framework/
├── app/
│   ├── Controllers/           # Application controllers
│   └── Models/               # Eloquent models
├── core/                     # Framework core files
│   ├── Command/              # CLI command system
│   ├── Database/             # ORM and database tools
│   ├── Middleware/           # Middleware classes
│   └── ...                   # Other core components
├── database/
│   ├── migrations/           # Database migrations
│   └── seeders/              # Database seeders
├── routes/
│   ├── web.php              # Web routes
│   └── api.php              # API routes
├── views/                    # Template files
│   ├── layouts/             # Layout templates
│   ├── auth/                # Authentication views
│   └── ...                  # Other view directories
├── storage/
│   ├── cache/               # Template and other cache
│   └── logs/                # Application logs
├── .env                     # Environment configuration
└── retrina                  # CLI entry point
```

## 🤝 Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🙏 Acknowledgments

- Inspired by Laravel's elegant syntax and features
- Built with modern PHP best practices
- Bootstrap 5 for beautiful, responsive UI components

---

**Retrina Framework** - *Modern PHP development made simple* 🚀 