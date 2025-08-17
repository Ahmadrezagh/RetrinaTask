@extends('layouts.app')

@section('title', 'Documentation - Retrina Framework')

@section('content')
<div class="container py-5">
    <!-- Hero Section -->
    <div class="row mb-5">
        <div class="col-12 text-center">
            <div class="mb-4">
                <i class="bi bi-book display-1 text-primary"></i>
            </div>
            <h1 class="display-4 fw-bold mb-3">Documentation</h1>
            <p class="lead text-muted">
                Complete guide to building amazing applications with Retrina Framework
            </p>
        </div>
    </div>

    <div class="row">
        <!-- Sticky Sidebar Navigation -->
        <div class="col-lg-3 mb-5">
            <div class="position-sticky" style="top: 2rem;">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-list"></i> Table of Contents
                        </h5>
                    </div>
                    <div class="list-group list-group-flush">
                        <a href="#getting-started" class="list-group-item list-group-item-action">
                            <i class="bi bi-rocket text-primary me-2"></i>Getting Started
                        </a>
                        <a href="#routing" class="list-group-item list-group-item-action">
                            <i class="bi bi-signpost text-success me-2"></i>Routing
                        </a>
                        <a href="#controllers" class="list-group-item list-group-item-action">
                            <i class="bi bi-controller text-info me-2"></i>Controllers
                        </a>
                        <a href="#views" class="list-group-item list-group-item-action">
                            <i class="bi bi-eye text-warning me-2"></i>Views & Templates
                        </a>
                        <a href="#database" class="list-group-item list-group-item-action">
                            <i class="bi bi-database text-secondary me-2"></i>Database & ORM
                        </a>
                        <a href="#migrations" class="list-group-item list-group-item-action">
                            <i class="bi bi-arrow-up-circle text-primary me-2"></i>Migrations
                        </a>
                        <a href="#seeders" class="list-group-item list-group-item-action">
                            <i class="bi bi-flower1 text-success me-2"></i>Seeders
                        </a>
                        <a href="#models" class="list-group-item list-group-item-action">
                            <i class="bi bi-boxes text-info me-2"></i>Models
                        </a>
                        <a href="#middleware" class="list-group-item list-group-item-action">
                            <i class="bi bi-shield text-danger me-2"></i>Middleware
                        </a>
                        <a href="#authentication" class="list-group-item list-group-item-action">
                            <i class="bi bi-lock text-warning me-2"></i>Authentication
                        </a>
                        <a href="#security" class="list-group-item list-group-item-action">
                            <i class="bi bi-shield-check text-success me-2"></i>Security
                        </a>
                        <a href="#cli" class="list-group-item list-group-item-action">
                            <i class="bi bi-terminal text-dark me-2"></i>CLI Tools
                        </a>
                        <a href="#api" class="list-group-item list-group-item-action">
                            <i class="bi bi-globe text-primary me-2"></i>API Development
                        </a>
                        <a href="#testing" class="list-group-item list-group-item-action">
                            <i class="bi bi-bug text-info me-2"></i>Testing
                        </a>
                        <a href="#deployment" class="list-group-item list-group-item-action">
                            <i class="bi bi-cloud-upload text-success me-2"></i>Deployment
                        </a>
                        <a href="#troubleshooting" class="list-group-item list-group-item-action">
                            <i class="bi bi-tools text-warning me-2"></i>Troubleshooting
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-lg-9">
            <!-- Getting Started -->
            <section id="getting-started" class="mb-5">
                <h2 class="h3 mb-4">🚀 Getting Started</h2>
                
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Installation & Setup</h5>
                    </div>
                    <div class="card-body">
                        <h6>Prerequisites</h6>
                        <ul>
                            <li>PHP 8.2 or higher</li>
                            <li>MySQL, PostgreSQL, or SQLite</li>
                            <li>Composer (optional)</li>
                        </ul>
                        
                        <h6>Quick Setup</h6>
                        <pre class="bg-light p-3 rounded"><code># 1. Configure environment
cp .env.example .env
# Edit .env with your database settings

# 2. Set up database
php retrina migrate:fresh --seed

# 3. Start development server
php retrina serve</code></pre>
                        
                        <div class="alert alert-info">
                            <strong>Demo Credentials:</strong><br>
                            Admin: <code>admin</code> / <code>admin123</code><br>
                            User: <code>user</code> / <code>user123</code>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Routing -->
            <section id="routing" class="mb-5">
                <h2 class="h3 mb-4">🛤️ Routing</h2>
                
                <div class="card mb-4">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="mb-0">Route Definition</h5>
                    </div>
                    <div class="card-body">
                        <pre class="bg-light p-3 rounded"><code>// routes/web.php
$router->get('/', function() {
    return view('home/index');
});

$router->get('/users/{id}', 'UserController@show');

// Route groups with middleware
$router->group(['middleware' => ['auth']], function($router) {
    $router->get('/dashboard', 'DashboardController@index');
    $router->resource('/posts', 'PostController');
});

// API routes (routes/api.php)
$router->group(['middleware' => ['api']], function($router) {
    $router->get('/api/users', function() {
        return json_encode(User::all());
    });
});</code></pre>
                    </div>
                </div>
            </section>

            <!-- Controllers -->
            <section id="controllers" class="mb-5">
                <h2 class="h3 mb-4">🎮 Controllers</h2>
                
                <div class="card mb-4">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">Creating Controllers</h5>
                    </div>
                    <div class="card-body">
                        <pre class="bg-light p-3 rounded"><code># Generate a controller
php retrina make:controller UserController

# Generate a resource controller
php retrina make:controller PostController -r</code></pre>
                        
                        <h6 class="mt-3">Controller Example</h6>
                        <pre class="bg-light p-3 rounded"><code>&lt;?php
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
}</code></pre>
                    </div>
                </div>
            </section>

            <!-- Views & Templates -->
            <section id="views" class="mb-5">
                <h2 class="h3 mb-4">👁️ Views & Templates</h2>
                
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Template Syntax</h5>
                    </div>
                    <div class="card-body">
                        <h6>Layout (app.retrina.php)</h6>
                        <pre class="bg-light p-3 rounded"><code>&lt;!DOCTYPE html&gt;
&lt;html&gt;
&lt;head&gt;
    &lt;title&gt;@yield('title', 'Retrina Framework')&lt;/title&gt;
&lt;/head&gt;
&lt;body&gt;
    &lt;nav&gt;@include('partials/navigation')&lt;/nav&gt;
    &lt;main&gt;@yield('content')&lt;/main&gt;
    &lt;footer&gt;@yield('footer')&lt;/footer&gt;
&lt;/body&gt;
&lt;/html&gt;</code></pre>
                        
                        <h6>View (users/index.retrina.php)</h6>
                        <pre class="bg-light p-3 rounded"><code>@extends('layouts.app')

@section('title', 'Users')

@section('content')
&lt;div class="container"&gt;
    &lt;h1&gt;Users&lt;/h1&gt;
    @foreach($users as $user)
        &lt;div class="user-card"&gt;
            &lt;h3&gt;{{ $user['username'] }}&lt;/h3&gt;
            &lt;p&gt;{{ $user['email'] }}&lt;/p&gt;
            @if($user['role'] === 'admin')
                &lt;span class="badge"&gt;Admin&lt;/span&gt;
            @endif
        &lt;/div&gt;
    @endforeach
&lt;/div&gt;
@endsection</code></pre>
                    </div>
                </div>
            </section>

            <!-- Database & ORM -->
            <section id="database" class="mb-5">
                <h2 class="h3 mb-4">🗄️ Database & ORM</h2>
                
                <div class="card mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">Configuration</h5>
                    </div>
                    <div class="card-body">
                        <p>Configure your database in <code>.env</code>:</p>
                        <pre class="bg-light p-3 rounded"><code>DB_DRIVER=mysql          # mysql, sqlite, postgres
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=retrina_framework
DB_USERNAME=root
DB_PASSWORD=your_password</code></pre>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">Query Builder</h5>
                    </div>
                    <div class="card-body">
                        <pre class="bg-light p-3 rounded"><code>use Core\Database\DB;

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
    ->get();</code></pre>
                    </div>
                </div>
            </section>

            <!-- Migrations -->
            <section id="migrations" class="mb-5">
                <h2 class="h3 mb-4">📊 Migrations</h2>
                
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Creating Migrations</h5>
                    </div>
                    <div class="card-body">
                        <pre class="bg-light p-3 rounded"><code># Create a new migration
php retrina make:migration create_posts_table

# Create migration with model
php retrina make:model Post -m</code></pre>
                        
                        <h6 class="mt-3">Migration Example</h6>
                        <pre class="bg-light p-3 rounded"><code>&lt;?php
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
}</code></pre>
                    </div>
                </div>
                
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Migration Commands</h5>
                    </div>
                    <div class="card-body">
                        <pre class="bg-light p-3 rounded"><code># Run migrations
php retrina migrate

# Fresh migration (drops all tables)
php retrina migrate:fresh

# Fresh migration with seeders
php retrina migrate:fresh --seed

# Rollback migrations
php retrina migrate:rollback</code></pre>
                    </div>
                </div>
            </section>

            <!-- Seeders -->
            <section id="seeders" class="mb-5">
                <h2 class="h3 mb-4">🌱 Seeders</h2>
                
                <div class="card mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">Creating Seeders</h5>
                    </div>
                    <div class="card-body">
                        <pre class="bg-light p-3 rounded"><code># Create a new seeder
php retrina make:seeder UserSeeder
php retrina make:seeder PostSeeder</code></pre>
                        
                        <h6 class="mt-3">Seeder Example</h6>
                        <pre class="bg-light p-3 rounded"><code>&lt;?php
use Core\Database\Seeder;
use Core\Database\DB;

class UserSeeder extends Seeder
{
    public function run()
    {
        $users = [
            [
                'username' => 'admin',
                'email' => 'admin@retrina.local',
                'password' => $this->hash('admin123'),
                'role' => 'admin',
                'created_at' => $this->now(),
                'updated_at' => $this->now(),
            ],
            [
                'username' => 'user',
                'email' => 'user@retrina.local',
                'password' => $this->hash('user123'),
                'role' => 'user',
                'created_at' => $this->now(),
                'updated_at' => $this->now(),
            ]
        ];
        
        foreach ($users as $user) {
            DB::table('users')->insert($user);
        }
    }
}</code></pre>
                    </div>
                </div>
                
                <div class="card mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">Seeder Commands</h5>
                    </div>
                    <div class="card-body">
                        <pre class="bg-light p-3 rounded"><code># Run all seeders
php retrina db:seed

# Run specific seeder
php retrina db:seed --class=UserSeeder

# Register in DatabaseSeeder.php
$this->callSeeders([
    UserSeeder::class,
    PostSeeder::class,
]);</code></pre>
                    </div>
                </div>
            </section>

            <!-- Models -->
            <section id="models" class="mb-5">
                <h2 class="h3 mb-4">🏗️ Models</h2>
                
                <div class="card mb-4">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">Basic Model Usage</h5>
                    </div>
                    <div class="card-body">
                        <pre class="bg-light p-3 rounded"><code>&lt;?php
namespace App\Models;

use Core\Database\Model;

class User extends Model
{
    protected $table = 'users';
    protected $fillable = ['username', 'email', 'password', 'role'];
    
    // Usage examples
    public static function getAdmins()
    {
        return static::where('role', 'admin')->get();
    }
}

// Usage
$users = User::all();
$user = User::find(1);
$admins = User::where('role', 'admin')->get();
$user = User::create([
    'username' => 'john',
    'email' => 'john@example.com',
    'role' => 'user'
]);</code></pre>
                    </div>
                </div>
            </section>

            <!-- Middleware -->
            <section id="middleware" class="mb-5">
                <h2 class="h3 mb-4">🛡️ Middleware</h2>
                
                <div class="card mb-4">
                    <div class="card-header bg-danger text-white">
                        <h5 class="mb-0">Available Middleware</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <ul class="list-unstyled">
                                    <li><strong>auth</strong> - Require authentication</li>
                                    <li><strong>guest</strong> - Allow only non-authenticated users</li>
                                    <li><strong>admin</strong> - Require admin role</li>
                                    <li><strong>cors</strong> - Handle CORS headers</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <ul class="list-unstyled">
                                    <li><strong>csrf</strong> - CSRF protection</li>
                                    <li><strong>throttle</strong> - Rate limiting</li>
                                    <li><strong>json</strong> - JSON request/response handling</li>
                                    <li><strong>session</strong> - Session management</li>
                                </ul>
                            </div>
                        </div>
                        
                        <h6 class="mt-3">Usage Examples</h6>
                        <pre class="bg-light p-3 rounded"><code>// Apply to individual routes
$router->get('/dashboard', 'DashboardController@index', ['auth']);

// Apply to route groups
$router->group(['middleware' => ['auth', 'admin']], function($router) {
    $router->get('/admin', 'AdminController@index');
});

// API middleware with rate limiting
$router->group(['middleware' => ['api', 'throttle:60,1']], function($router) {
    $router->get('/api/data', 'ApiController@getData');
});</code></pre>
                    </div>
                </div>
            </section>

            <!-- Authentication -->
            <section id="authentication" class="mb-5">
                <h2 class="h3 mb-4">🔐 Authentication</h2>
                
                <div class="card mb-4">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0">Login Process</h5>
                    </div>
                    <div class="card-body">
                        <pre class="bg-light p-3 rounded"><code>// Login controller method
public function login()
{
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    $user = User::where('username', $username)->first();
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['user_role'] = $user['role'];
        
        return redirect('/dashboard');
    }
    
    return redirect('/login?error=invalid');
}</code></pre>
                    </div>
                </div>
                
                <div class="card mb-4">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0">Logout Process</h5>
                    </div>
                    <div class="card-body">
                        <pre class="bg-light p-3 rounded"><code>// Logout controller method
public function logout()
{
    unset($_SESSION['user_id']);
    unset($_SESSION['username']);
    unset($_SESSION['user_role']);
    
    return redirect('/login');
}</code></pre>
                    </div>
                </div>
                
                <div class="card mb-4">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0">Authentication Middleware</h5>
                    </div>
                    <div class="card-body">
                        <pre class="bg-light p-3 rounded"><code>// Protect routes with auth middleware
$router->group(['middleware' => ['auth']], function($router) {
    $router->get('/dashboard', 'DashboardController@index');
    $router->get('/profile', 'ProfileController@show');
});

// Admin-only routes
$router->group(['middleware' => ['auth', 'admin']], function($router) {
    $router->get('/admin', 'AdminController@index');
    $router->resource('/admin/users', 'AdminUserController');
});</code></pre>
                    </div>
                </div>
            </section>

            <!-- Security -->
            <section id="security" class="mb-5">
                <h2 class="h3 mb-4">🔒 Security</h2>
                
                <div class="card mb-4">
                    <div class="card-header bg-danger text-white">
                        <h5 class="mb-0">CSRF Protection</h5>
                    </div>
                    <div class="card-body">
                        <pre class="bg-light p-3 rounded"><code>{{-- In forms --}}
&lt;form method="POST" action="/users"&gt;
    @csrf
    &lt;input type="text" name="username" required&gt;
    &lt;button type="submit"&gt;Create User&lt;/button&gt;
&lt;/form&gt;</code></pre>
                    </div>
                </div>
                
                <div class="card mb-4">
                    <div class="card-header bg-danger text-white">
                        <h5 class="mb-0">Role-based Access</h5>
                    </div>
                    <div class="card-body">
                        <pre class="bg-light p-3 rounded"><code>// Check roles in controllers
if (!User::isAdmin($_SESSION)) {
    return redirect('/unauthorized');
}

// Or use admin middleware
$router->get('/admin', 'AdminController@index', ['admin']);</code></pre>
                    </div>
                </div>
            </section>

            <!-- CLI Tools -->
            <section id="cli" class="mb-5">
                <h2 class="h3 mb-4">⚡ CLI Tools</h2>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header bg-dark text-white">
                                <h5 class="mb-0">Database Commands</h5>
                            </div>
                            <div class="card-body">
                                <pre class="bg-light p-3 rounded"><code># Migrations
php retrina migrate
php retrina migrate:fresh
php retrina migrate:fresh --seed
php retrina migrate:rollback

# Seeding
php retrina db:seed
php retrina db:seed --class=UserSeeder</code></pre>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header bg-dark text-white">
                                <h5 class="mb-0">Code Generation</h5>
                            </div>
                            <div class="card-body">
                                <pre class="bg-light p-3 rounded"><code># Generate files
php retrina make:model User
php retrina make:controller UserController
php retrina make:migration create_posts_table
php retrina make:seeder UserSeeder
php retrina make:view posts.index

# With options
php retrina make:model Post -m
php retrina make:controller PostController -r</code></pre>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card mb-4">
                    <div class="card-header bg-dark text-white">
                        <h5 class="mb-0">Development Tools</h5>
                    </div>
                    <div class="card-body">
                        <pre class="bg-light p-3 rounded"><code># Development server
php retrina serve                    # Default port 8585
php retrina serve --port=8080        # Custom port

# Utilities
php retrina route:list               # Show all routes
php retrina list                     # Show all commands</code></pre>
                    </div>
                </div>
            </section>

            <!-- API Development -->
            <section id="api" class="mb-5">
                <h2 class="h3 mb-4">🌐 API Development</h2>
                
                <div class="card mb-4">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">API Routes</h5>
                    </div>
                    <div class="card-body">
                        <pre class="bg-light p-3 rounded"><code>// routes/api.php
$router->group(['middleware' => ['api']], function($router) {
    $router->get('/api/users', function() {
        $users = User::all();
        return json_encode($users);
    });
    
    $router->group(['middleware' => ['auth']], function($router) {
        $router->post('/api/users', 'ApiUserController@store');
        $router->put('/api/users/{id}', 'ApiUserController@update');
    });
});</code></pre>
                    </div>
                </div>
            </section>

            <!-- Testing -->
            <section id="testing" class="mb-5">
                <h2 class="h3 mb-4">🧪 Testing</h2>
                
                <div class="card mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">Development Testing</h5>
                    </div>
                    <div class="card-body">
                        <pre class="bg-light p-3 rounded"><code># Fresh environment with demo data
php retrina migrate:fresh --seed

# Test API endpoints
curl http://localhost:8585/api/users
curl -X POST http://localhost:8585/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"username":"admin","password":"admin123"}'

# Test authentication
curl http://localhost:8585/dashboard</code></pre>
                    </div>
                </div>
            </section>

            <!-- Deployment -->
            <section id="deployment" class="mb-5">
                <h2 class="h3 mb-4">🚀 Deployment</h2>
                
                <div class="card mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">Production Setup</h5>
                    </div>
                    <div class="card-body">
                        <h6>Environment Configuration</h6>
                        <pre class="bg-light p-3 rounded"><code># Production .env settings
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_DRIVER=mysql
DB_HOST=your-db-host
DB_DATABASE=your-production-db
DB_USERNAME=your-db-user
DB_PASSWORD=your-secure-password</code></pre>
                        
                        <h6 class="mt-3">Server Requirements</h6>
                        <ul>
                            <li>PHP 8.2+ with required extensions (PDO, OpenSSL, JSON)</li>
                            <li>Web server (Apache/Nginx) with rewrite support</li>
                            <li>Database (MySQL 5.7+, PostgreSQL 10+, or SQLite 3.25+)</li>
                            <li>SSL certificate for HTTPS</li>
                        </ul>
                        
                        <h6 class="mt-3">Apache Configuration</h6>
                        <pre class="bg-light p-3 rounded"><code>&lt;VirtualHost *:80&gt;
    ServerName yourdomain.com
    DocumentRoot /path/to/retrina
    
    &lt;Directory /path/to/retrina&gt;
        AllowOverride All
        Require all granted
        
        RewriteEngine On
        RewriteCond %{REQUEST_FILENAME} !-f
        RewriteCond %{REQUEST_FILENAME} !-d
        RewriteRule ^(.*)$ index.php [QSA,L]
    &lt;/Directory&gt;
&lt;/VirtualHost&gt;</code></pre>
                    </div>
                </div>
                
                <div class="card mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">Deployment Steps</h5>
                    </div>
                    <div class="card-body">
                        <ol>
                            <li><strong>Upload Files:</strong> Transfer your application files to the server</li>
                            <li><strong>Configure Environment:</strong> Set up production <code>.env</code> file</li>
                            <li><strong>Set Permissions:</strong>
                                <pre class="bg-light p-2 rounded mt-2"><code>chmod -R 755 storage/
chmod -R 755 storage/cache/
chmod -R 755 storage/logs/</code></pre>
                            </li>
                            <li><strong>Run Migrations:</strong>
                                <pre class="bg-light p-2 rounded mt-2"><code>php retrina migrate --force</code></pre>
                            </li>
                            <li><strong>Optimize for Production:</strong>
                                <ul>
                                    <li>Enable OPcache</li>
                                    <li>Configure proper error logging</li>
                                    <li>Set up SSL/HTTPS</li>
                                </ul>
                            </li>
                        </ol>
                    </div>
                </div>
            </section>

            <!-- Troubleshooting -->
            <section id="troubleshooting" class="mb-5">
                <h2 class="h3 mb-4">🔧 Troubleshooting</h2>
                
                <div class="card mb-4">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0">Common Issues</h5>
                    </div>
                    <div class="card-body">
                        <h6>Database Connection Issues</h6>
                        <div class="alert alert-info">
                            <strong>Problem:</strong> "PDO connection failed"<br>
                            <strong>Solution:</strong>
                            <ul class="mb-0 mt-2">
                                <li>Check <code>.env</code> database credentials</li>
                                <li>Verify database server is running</li>
                                <li>Ensure database exists</li>
                                <li>Check PHP PDO extensions are installed</li>
                            </ul>
                        </div>
                        
                        <h6>Migration Errors</h6>
                        <div class="alert alert-warning">
                            <strong>Problem:</strong> "Migration class not found"<br>
                            <strong>Solution:</strong>
                            <pre class="mb-0 mt-2"><code># Clear and regenerate migrations
php retrina migrate:fresh</code></pre>
                        </div>
                        
                        <h6>Template Not Found</h6>
                        <div class="alert alert-danger">
                            <strong>Problem:</strong> "View file not found"<br>
                            <strong>Solution:</strong>
                            <ul class="mb-0 mt-2">
                                <li>Check file path and extension (<code>.retrina.php</code>)</li>
                                <li>Verify file exists in <code>views/</code> directory</li>
                                <li>Clear template cache: <code>rm -rf storage/cache/views/*</code></li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <div class="card mb-4">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0">Debug Tools</h5>
                    </div>
                    <div class="card-body">
                        <h6>Enable Debug Mode</h6>
                        <pre class="bg-light p-3 rounded"><code># In .env file
APP_DEBUG=true
APP_ENV=development</code></pre>
                        
                        <h6>Check Routes</h6>
                        <pre class="bg-light p-3 rounded"><code># List all registered routes
php retrina route:list</code></pre>
                        
                        <h6>Database Debugging</h6>
                        <pre class="bg-light p-3 rounded"><code># Test database connection
php -r "
require_once 'core/Database/Connection.php';
try {
    \$conn = \Core\Database\Connection::getInstance();
    echo 'Database connected successfully';
} catch (Exception \$e) {
    echo 'Error: ' . \$e->getMessage();
}
"</code></pre>
                    </div>
                </div>
                
                <div class="card mb-4">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0">Performance Tips</h5>
                    </div>
                    <div class="card-body">
                        <ul>
                            <li><strong>Template Caching:</strong> Ensure <code>storage/cache/views/</code> is writable</li>
                            <li><strong>Database Indexing:</strong> Add indexes to frequently queried columns</li>
                            <li><strong>OPcache:</strong> Enable PHP OPcache in production</li>
                            <li><strong>Static Files:</strong> Use a CDN for CSS/JS assets</li>
                            <li><strong>Database Optimization:</strong> Use query builder efficiently</li>
                        </ul>
                    </div>
                </div>
            </section>

            <!-- Help Resources -->
            <div class="row mt-5">
                <div class="col-12">
                    <div class="card border-0 bg-light">
                        <div class="card-body text-center py-5">
                            <h3>Need More Help?</h3>
                            <p class="text-muted mb-4">
                                Explore more resources and get support for your Retrina Framework projects.
                            </p>
                            <div class="d-flex gap-3 justify-content-center flex-wrap">
                                <a href="/api" class="btn btn-primary">
                                    <i class="bi bi-api"></i> API Reference
                                </a>
                                <a href="https://github.com/your-repo/retrina" class="btn btn-outline-secondary">
                                    <i class="bi bi-github"></i> GitHub Repository
                                </a>
                                <a href="/hello" class="btn btn-outline-primary">
                                    <i class="bi bi-code-slash"></i> Examples
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Smooth scrolling for anchor links */
html {
    scroll-behavior: smooth;
}

/* Code block styling */
pre {
    white-space: pre-wrap;
    word-wrap: break-word;
    font-size: 0.875rem;
}

/* Section spacing */
section {
    scroll-margin-top: 100px;
}

/* Sidebar styling */
.list-group-item-action:hover {
    background-color: #f8f9fa;
}

.list-group-item-action.active {
    background-color: #007bff;
    color: white;
}

/* Card hover effects */
.card {
    transition: transform 0.2s ease-in-out;
}

.card:hover {
    transform: translateY(-2px);
}

/* Make sidebar truly sticky */
@media (min-width: 992px) {
    .position-sticky {
        position: sticky !important;
        top: 2rem !important;
    }
}
</style>

<script>
// Add active class to current section in sidebar
document.addEventListener('DOMContentLoaded', function() {
    const sections = document.querySelectorAll('section[id]');
    const navLinks = document.querySelectorAll('.list-group-item-action[href^="#"]');
    
    function updateActiveLink() {
        let current = '';
        sections.forEach(section => {
            const sectionTop = section.offsetTop;
            const sectionHeight = section.clientHeight;
            if (window.scrollY >= sectionTop - 200) {
                current = section.getAttribute('id');
            }
        });
        
        navLinks.forEach(link => {
            link.classList.remove('active');
            if (link.getAttribute('href') === '#' + current) {
                link.classList.add('active');
            }
        });
    }
    
    window.addEventListener('scroll', updateActiveLink);
    updateActiveLink(); // Call once on load
});
</script>
@endsection 