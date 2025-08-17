# Retrina Framework

A lightweight, powerful custom PHP MVC framework with beautiful template syntax, modern UI components, and enterprise-level features.

## 🌟 Features

- 🏗️ **MVC Architecture** - Clean separation of concerns with Models, Views, and Controllers
- 🎨 **Beautiful Template Syntax** - Laravel Blade-like syntax with `{{ }}` and `@directives`
- 🚀 **Advanced Router** - Flexible routing with parameter support, method spoofing, and closures
- 🖼️ **Professional UI** - Fully integrated with Bootstrap 5 and Bootstrap Icons
- 🔧 **PSR-4 Autoloading** - Automatic class loading without manual includes
- 💾 **Database Layer** - PDO-based abstraction with prepared statements and ORM-like features
- 🛡️ **Security First** - Built-in CSRF protection, XSS prevention, and input validation
- ⚡ **Performance** - Template compilation, caching, and optimized rendering
- 📱 **Responsive Design** - Mobile-first approach with Bootstrap components
- 🎯 **Developer Experience** - Intuitive syntax, helpful debugging, and comprehensive documentation

## 🎨 Template Syntax Highlights

### Before (Old PHP Syntax):
```php
<?php $this->section('title'); ?>
My Page Title
<?php $this->endSection(); ?>

<?php if(isset($user)): ?>
    Hello <?php echo htmlspecialchars($user); ?>!
<?php endif; ?>
```

### After (Beautiful Template Syntax):
```php
@extends('app')

@section('title')
My Page Title
@endsection

@section('content')
@if(isset($user))
    Hello {{{ $user }}}!
@endif
@endsection
```

## 📁 Directory Structure

```
RetrinaTask/
├── app/
│   ├── Controllers/
│   │   ├── BaseController.php      # Enhanced base controller with view engine
│   │   └── HomeController.php      # Example controller with demos
│   └── Models/
│       ├── BaseModel.php          # PDO-based model with CRUD operations
│       └── User.php               # Example user model
├── config/
│   └── database.php               # Database configuration
├── core/
│   ├── Application.php            # Main application bootstrapper
│   ├── Router.php                 # Advanced routing system
│   ├── ViewEngine.php             # Template engine with compilation
│   ├── TemplateCompiler.php       # Beautiful syntax compiler
│   ├── View.php                   # Static view facade
│   └── helpers.php                # Global helper functions
├── routes/
│   └── web.php                    # Route definitions with examples
├── views/
│   ├── layouts/
│   │   ├── app.php                # Main layout with Bootstrap 5
│   │   └── auth.php               # Authentication layout
│   ├── home/
│   │   ├── index.php              # Original PHP syntax view
│   │   └── index.retrina.php      # New template syntax version
│   ├── user/
│   │   └── profile.php            # User profile with Bootstrap components
│   ├── auth/
│   │   └── login.php              # Login form with validation
│   ├── demo/
│   │   └── template-syntax.retrina.php  # Comprehensive syntax demo
│   └── partials/
│       └── header.php             # Reusable components
├── storage/
│   └── cache/
│       └── views/                 # Compiled template cache
├── .htaccess                      # URL rewriting configuration
├── .gitignore                     # Version control exclusions
├── index.php                      # Application entry point
└── README.md                      # This file
```

## 🚀 Quick Start

### Requirements

- PHP 7.4 or higher
- Apache/Nginx web server with mod_rewrite
- MySQL/MariaDB (optional, for database features)

### Installation

1. **Clone or download** the framework to your web server directory
2. **Configure your web server** to point to the framework's root directory
3. **Update database settings** in `config/database.php`
4. **Set permissions** for the `storage/cache/views/` directory (755)
5. **Access your application** through the web browser

### Example Usage

#### Creating Routes
```php
// In routes/web.php
$app->router()->get('/', 'HomeController@index');
$app->router()->get('/user/{id}', 'UserController@show');
$app->router()->post('/contact', 'ContactController@store');

// Closure routes
$app->router()->get('/hello/{name}', function($name) {
    return "Hello, {$name}!";
});
```

#### Creating Controllers
```php
<?php
namespace App\Controllers;

class UserController extends BaseController
{
    public function show($id)
    {
        $user = (new \App\Models\User())->findById($id);
        $this->view('user.profile', ['user' => $user], 'app');
    }
    
    public function api()
    {
        $this->json(['status' => 'success', 'data' => $data]);
    }
}
```

#### Creating Models
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

#### Creating Views with Template Syntax
```php
{{-- views/user/profile.retrina.php --}}
@extends('app')

@section('title')
User Profile - {{ $user['name'] }}
@endsection

@section('content')
<div class="container">
    @card('User Information')
        <h4>{{{ $user['name'] }}}</h4>
        <p class="text-muted">{{{ $user['email'] }}}</p>
        
        @if($user['active'])
            @alert('User is active', 'success')
        @else
            @alert('User is inactive', 'warning')
        @endif
    @endcard
</div>
@endsection
```

## 🎯 Template Syntax Reference

### Output Directives
| Syntax | Description | Example |
|--------|-------------|---------|
| `{{ $var }}` | Raw output | `{{ $title }}` |
| `{{{ $var }}}` | Escaped output (safe) | `{{{ $user_input }}}` |
| `{!! $html !!}` | Unescaped HTML | `{!! $rich_content !!}` |

### Control Structures
| Syntax | Description |
|--------|-------------|
| `@if($condition)` ... `@endif` | Conditional statements |
| `@foreach($items as $item)` ... `@endforeach` | Loop through arrays |
| `@for($i = 0; $i < 10; $i++)` ... `@endfor` | For loops |
| `@while($condition)` ... `@endwhile` | While loops |

### Template Inheritance
| Syntax | Description |
|--------|-------------|
| `@extends('layout')` | Extend a layout |
| `@section('name')` ... `@endsection` | Define content sections |
| `@yield('section', 'default')` | Output section content |
| `@include('partial', $data)` | Include partial views |

### Utility Directives
| Syntax | Description |
|--------|-------------|
| `@csrf` | CSRF protection field |
| `@method('PUT')` | HTTP method spoofing |
| `@url('/path')` | Generate URLs |
| `@asset('css/style.css')` | Asset URLs |
| `@json($data)` | JSON output |
| `@auth` ... `@endauth` | Authenticated users only |
| `@guest` ... `@endguest` | Guest users only |

### Bootstrap Helpers
| Syntax | Description |
|--------|-------------|
| `@card('Title')` ... `@endcard` | Bootstrap card component |
| `@alert('Message', 'type')` | Bootstrap alert |

## 🎨 UI Components & Styling

### Bootstrap 5 Integration
- **Responsive Grid System** - Mobile-first layouts
- **Navigation Components** - Professional navbar with collapsible menu
- **Card Layouts** - Clean, modern content organization
- **Form Components** - Styled inputs with validation feedback
- **Alert System** - Flash messages with auto-dismiss
- **Button Styles** - Consistent, accessible buttons
- **Icons** - Bootstrap Icons throughout the interface

### Layouts Available
- **App Layout** (`layouts/app.php`) - Main application layout
- **Auth Layout** (`layouts/auth.php`) - Authentication pages
- **Custom Layouts** - Easy to create additional layouts

## 🛠️ Advanced Features

### Router Capabilities
- **Parameter Extraction** - `{id}`, `{slug}`, etc.
- **Method Spoofing** - PUT, DELETE via POST
- **Closure Routes** - Anonymous function handlers
- **Route Middleware** - Request filtering (extensible)
- **Route Groups** - Organized route management

### View Engine Features
- **Template Compilation** - Automatic compilation to PHP
- **Smart Caching** - Recompiles only when templates change
- **Layout Inheritance** - Nested layouts and sections
- **Partial Views** - Reusable components
- **Data Sharing** - Global and scoped data
- **Error Handling** - Detailed template error reporting

### Security Features
- **CSRF Protection** - Built-in token generation and validation
- **XSS Prevention** - Automatic output escaping
- **Input Validation** - Form data sanitization
- **Session Security** - Secure session configuration
- **SQL Injection Prevention** - PDO prepared statements

### Performance Optimizations
- **Template Caching** - Compiled templates cached for speed
- **Autoloader** - PSR-4 compliant class loading
- **Output Buffering** - Efficient content rendering
- **Asset Management** - Organized static file serving

## 📊 Demo Pages & Examples

### Available Demo Routes
- `/` - Enhanced homepage with template syntax showcase
- `/demo/template-syntax` - Comprehensive template syntax demonstration
- `/user/{id}` - User profile with Bootstrap components
- `/login` - Authentication form with validation
- `/api` - JSON API endpoint example
- `/hello/{name}` - Closure route demonstration

### Interactive Features
- **Copy-to-clipboard** code examples
- **Real-time form validation** with Bootstrap feedback
- **Auto-dismissing alerts** with smooth animations
- **Responsive navigation** with mobile support
- **Hover effects** and micro-interactions

## 🔧 Configuration

### Database Configuration
Update `config/database.php`:
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
Toggle debug mode in `index.php`:
```php
define('DEBUG', true); // Set to false in production
```

### Template Cache
Clear compiled templates:
```bash
# Via web endpoint
curl http://yoursite.com/cache/clear

# Or programmatically
$viewEngine = new \Core\ViewEngine();
$viewEngine->clearCache();
```

## 🚀 What Makes Retrina Special

### 1. **Developer Experience**
- **Beautiful syntax** that's easy to read and write
- **Comprehensive documentation** with examples
- **Intuitive structure** following modern PHP standards
- **Helpful error messages** for debugging

### 2. **Modern Architecture**
- **PSR-4 autoloading** for clean class organization
- **Dependency injection ready** for future extensions
- **Modular design** for easy customization
- **Test-friendly** structure for quality assurance

### 3. **Production Ready**
- **Security-first** approach with built-in protections
- **Performance optimized** with caching and compilation
- **Scalable architecture** that grows with your needs
- **Professional UI** that looks great out of the box

### 4. **Educational Value**
- **Clean, readable code** for learning MVC patterns
- **Comprehensive examples** for understanding concepts
- **Progressive complexity** from basic to advanced features
- **Best practices** demonstrated throughout

## 🤝 Contributing

We welcome contributions to make Retrina even better:

1. **Report bugs** and suggest features
2. **Submit pull requests** with improvements
3. **Improve documentation** and examples
4. **Share your projects** built with Retrina

## 📄 License

This framework is open-source and available under the MIT License.

## 🏆 Framework Comparison

| Feature | Retrina | Laravel | CodeIgniter | Raw PHP |
|---------|---------|---------|-------------|---------|
| **Learning Curve** | ⭐⭐⭐ | ⭐⭐ | ⭐⭐⭐⭐ | ⭐ |
| **Template Syntax** | ✅ Beautiful | ✅ Blade | ❌ Basic | ❌ PHP only |
| **Built-in Security** | ✅ CSRF, XSS | ✅ Comprehensive | ⭐ Basic | ❌ Manual |
| **UI Framework** | ✅ Bootstrap 5 | ❌ Separate | ❌ Separate | ❌ Manual |
| **Dependencies** | ✅ Zero | ❌ Many | ⭐ Few | ✅ None |
| **Performance** | ✅ Fast | ⭐ Good | ✅ Fast | ✅ Fastest |
| **Documentation** | ✅ Comprehensive | ✅ Excellent | ✅ Good | ❌ None |

---

**Ready to build something amazing?** Start with Retrina Framework and experience the perfect balance of simplicity, power, and beauty! 🚀 