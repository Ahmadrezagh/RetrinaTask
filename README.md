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

# Test your changes
curl http://localhost:8585/api/users
```

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