@extends('layouts.app')

@section('title', 'Documentation - Retrina Framework')

@section('content')
<div class="container py-5">
    <!-- Hero Section -->
    <div class="row mb-5">
        <div class="col-12 text-center">
            <div class="mb-4">
                <i class="bi bi-book display-3 text-primary"></i>
            </div>
            <h1 class="display-4 fw-bold mb-3">Documentation</h1>
            <p class="lead text-muted">
                Everything you need to know to build amazing applications with Retrina Framework
            </p>
        </div>
    </div>

    <div class="row">
        <!-- Sidebar Navigation -->
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
                            <i class="bi bi-play-circle"></i> Getting Started
                        </a>
                        <a href="#routing" class="list-group-item list-group-item-action">
                            <i class="bi bi-signpost"></i> Routing
                        </a>
                        <a href="#middleware" class="list-group-item list-group-item-action">
                            <i class="bi bi-shield-check"></i> Middleware
                        </a>
                        <a href="#database" class="list-group-item list-group-item-action">
                            <i class="bi bi-database"></i> Database & ORM
                        </a>
                        <a href="#views" class="list-group-item list-group-item-action">
                            <i class="bi bi-eye"></i> Views & Templates
                        </a>
                        <a href="#cli" class="list-group-item list-group-item-action">
                            <i class="bi bi-terminal"></i> CLI Tools
                        </a>
                        <a href="#api" class="list-group-item list-group-item-action">
                            <i class="bi bi-api"></i> API Development
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-lg-9">
            <!-- Getting Started -->
            <section id="getting-started" class="mb-5">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-5">
                        <h2 class="h3 mb-4">
                            <i class="bi bi-play-circle text-success"></i> Getting Started
                        </h2>
                        
                        <h4>Installation</h4>
                        <p>Retrina Framework requires PHP 8.0 or higher. Simply clone or download the framework:</p>
                        
                        <div class="bg-dark text-light p-3 rounded mb-4">
                            <code>
                                git clone https://github.com/yourname/retrina-framework.git<br>
                                cd retrina-framework<br>
                                php retrina serve --port=8585
                            </code>
                        </div>
                        
                        <h4>Configuration</h4>
                        <p>Configure your database connection in the <code>.env</code> file:</p>
                        
                        <div class="bg-light p-3 rounded mb-4">
                            <code>
                                DB_DRIVER=mysql<br>
                                DB_HOST=127.0.0.1<br>
                                DB_PORT=3306<br>
                                DB_DATABASE=your_database<br>
                                DB_USERNAME=your_username<br>
                                DB_PASSWORD=your_password
                            </code>
                        </div>
                        
                        <h4>First Route</h4>
                        <p>Create your first route in <code>routes/web.php</code>:</p>
                        
                        <div class="bg-light p-3 rounded">
                            <code>
                                $router->get('/hello', function() {<br>
                                &nbsp;&nbsp;&nbsp;&nbsp;return 'Hello, World!';<br>
                                });
                            </code>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Routing -->
            <section id="routing" class="mb-5">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-5">
                        <h2 class="h3 mb-4">
                            <i class="bi bi-signpost text-primary"></i> Routing
                        </h2>
                        
                        <h4>Basic Routing</h4>
                        <p>Define routes using the router instance:</p>
                        
                        <div class="bg-light p-3 rounded mb-4">
                            <code>
                                // Basic routes<br>
                                $router->get('/users', 'UserController@index');<br>
                                $router->post('/users', 'UserController@store');<br>
                                $router->put('/users/{id}', 'UserController@update');<br>
                                $router->delete('/users/{id}', 'UserController@destroy');
                            </code>
                        </div>
                        
                        <h4>Route Parameters</h4>
                        <div class="bg-light p-3 rounded mb-4">
                            <code>
                                $router->get('/users/{id}', function($id) {<br>
                                &nbsp;&nbsp;&nbsp;&nbsp;return "User ID: " . $id;<br>
                                });
                            </code>
                        </div>
                        
                        <h4>Route Groups</h4>
                        <div class="bg-light p-3 rounded">
                            <code>
                                $router->group(['middleware' => ['auth'], 'prefix' => 'admin'], function($router) {<br>
                                &nbsp;&nbsp;&nbsp;&nbsp;$router->get('/dashboard', 'AdminController@dashboard');<br>
                                &nbsp;&nbsp;&nbsp;&nbsp;$router->get('/users', 'AdminController@users');<br>
                                });
                            </code>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Middleware -->
            <section id="middleware" class="mb-5">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-5">
                        <h2 class="h3 mb-4">
                            <i class="bi bi-shield-check text-warning"></i> Middleware
                        </h2>
                        
                        <h4>Available Middleware</h4>
                        <ul class="mb-4">
                            <li><strong>auth</strong> - Requires authentication</li>
                            <li><strong>admin</strong> - Requires admin privileges</li>
                            <li><strong>guest</strong> - Only for non-authenticated users</li>
                            <li><strong>cors</strong> - CORS headers for API</li>
                            <li><strong>throttle:max,minutes</strong> - Rate limiting</li>
                            <li><strong>csrf</strong> - CSRF protection</li>
                        </ul>
                        
                        <h4>Using Middleware</h4>
                        <div class="bg-light p-3 rounded mb-4">
                            <code>
                                // Single middleware<br>
                                $router->get('/profile', 'UserController@profile', ['auth']);<br><br>
                                
                                // Multiple middleware<br>
                                $router->get('/admin', 'AdminController@index', ['auth', 'admin']);<br><br>
                                
                                // Rate limiting<br>
                                $router->post('/api/upload', 'ApiController@upload', ['throttle:5,1']);
                            </code>
                        </div>
                        
                        <h4>Middleware Groups</h4>
                        <div class="bg-light p-3 rounded">
                            <code>
                                // Web group (csrf, session)<br>
                                $router->group(['middleware' => ['web']], function($router) {<br>
                                &nbsp;&nbsp;&nbsp;&nbsp;// Your web routes<br>
                                });<br><br>
                                
                                // API group (cors, throttle)<br>
                                $router->group(['middleware' => ['api']], function($router) {<br>
                                &nbsp;&nbsp;&nbsp;&nbsp;// Your API routes<br>
                                });
                            </code>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Database -->
            <section id="database" class="mb-5">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-5">
                        <h2 class="h3 mb-4">
                            <i class="bi bi-database text-info"></i> Database & ORM
                        </h2>
                        
                        <h4>Query Builder</h4>
                        <div class="bg-light p-3 rounded mb-4">
                            <code>
                                use Core\Database\DB;<br><br>
                                
                                // Basic queries<br>
                                $users = DB::table('users')->get();<br>
                                $user = DB::table('users')->where('id', 1)->first();<br>
                                $activeUsers = DB::table('users')->where('is_active', 1)->get();<br><br>
                                
                                // Insert<br>
                                DB::table('users')->insert([<br>
                                &nbsp;&nbsp;&nbsp;&nbsp;'name' => 'John Doe',<br>
                                &nbsp;&nbsp;&nbsp;&nbsp;'email' => 'john@example.com'<br>
                                ]);
                            </code>
                        </div>
                        
                        <h4>Models</h4>
                        <div class="bg-light p-3 rounded mb-4">
                            <code>
                                // Generate a model<br>
                                php retrina make:model User -m<br><br>
                                
                                // Using models<br>
                                $users = User::all();<br>
                                $user = User::find(1);<br>
                                $activeUsers = User::where('is_active', 1)->get();
                            </code>
                        </div>
                        
                        <h4>Migrations</h4>
                        <div class="bg-light p-3 rounded">
                            <code>
                                // Create migration<br>
                                php retrina make:migration create_users_table<br><br>
                                
                                // Run migrations<br>
                                php retrina migrate<br><br>
                                
                                // Rollback migrations<br>
                                php retrina migrate:rollback
                            </code>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Views -->
            <section id="views" class="mb-5">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-5">
                        <h2 class="h3 mb-4">
                            <i class="bi bi-eye text-secondary"></i> Views & Templates
                        </h2>
                        
                        <h4>Template Syntax</h4>
                        <div class="bg-light p-3 rounded mb-4">
                            <code>
                                {{-- This is a comment --}}<br><br>
                                
                                {{ $variable }} {{-- Escaped output --}}<br>
                                {!! $html !!} {{-- Raw output --}}<br><br>
                                
                                @if($condition)<br>
                                &nbsp;&nbsp;&nbsp;&nbsp;This is shown<br>
                                @else<br>
                                &nbsp;&nbsp;&nbsp;&nbsp;This is shown instead<br>
                                @endif<br><br>
                                
                                @foreach($items as $item)<br>
                                &nbsp;&nbsp;&nbsp;&nbsp;{{ $item }}<br>
                                @endforeach
                            </code>
                        </div>
                        
                        <h4>Layouts</h4>
                        <div class="bg-light p-3 rounded mb-4">
                            <code>
                                {{-- In your view --}}<br>
                                @extends('layouts.app')<br><br>
                                
                                @section('title', 'Page Title')<br><br>
                                
                                @section('content')<br>
                                &nbsp;&nbsp;&nbsp;&nbsp;Your content here<br>
                                @endsection
                            </code>
                        </div>
                        
                        <h4>Rendering Views</h4>
                        <div class="bg-light p-3 rounded">
                            <code>
                                // In controllers<br>
                                return view('users.index', ['users' => $users]);<br><br>
                                
                                // Helper function<br>
                                return view('welcome', compact('title', 'users'));
                            </code>
                        </div>
                    </div>
                </div>
            </section>

            <!-- CLI -->
            <section id="cli" class="mb-5">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-5">
                        <h2 class="h3 mb-4">
                            <i class="bi bi-terminal text-success"></i> CLI Tools
                        </h2>
                        
                        <h4>Available Commands</h4>
                        <div class="bg-dark text-light p-3 rounded mb-4">
                            <code>
                                php retrina list                    # List all commands<br>
                                php retrina serve                   # Start dev server<br>
                                php retrina make:controller User    # Generate controller<br>
                                php retrina make:model User -m      # Generate model with migration<br>
                                php retrina make:migration create_posts_table<br>
                                php retrina migrate                 # Run migrations<br>
                                php retrina migrate:rollback        # Rollback migrations<br>
                                php retrina route:list              # List all routes
                            </code>
                        </div>
                        
                        <h4>Resource Controllers</h4>
                        <div class="bg-dark text-light p-3 rounded">
                            <code>
                                # Generate resource controller<br>
                                php retrina make:controller PostController -r<br><br>
                                
                                # Generate API resource controller<br>
                                php retrina make:api-controller PostController -r
                            </code>
                        </div>
                    </div>
                </div>
            </section>

            <!-- API -->
            <section id="api" class="mb-5">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-5">
                        <h2 class="h3 mb-4">
                            <i class="bi bi-api text-danger"></i> API Development
                        </h2>
                        
                        <h4>API Routes</h4>
                        <div class="bg-light p-3 rounded mb-4">
                            <code>
                                // In routes/api.php<br>
                                $router->group(['middleware' => ['api']], function($router) {<br>
                                &nbsp;&nbsp;&nbsp;&nbsp;$router->get('/users', 'ApiController@users');<br>
                                &nbsp;&nbsp;&nbsp;&nbsp;$router->post('/users', 'ApiController@createUser');<br>
                                });
                            </code>
                        </div>
                        
                        <h4>JSON Responses</h4>
                        <div class="bg-light p-3 rounded mb-4">
                            <code>
                                // Success response<br>
                                return json_encode([<br>
                                &nbsp;&nbsp;&nbsp;&nbsp;'status' => 'success',<br>
                                &nbsp;&nbsp;&nbsp;&nbsp;'data' => $users<br>
                                ]);<br><br>
                                
                                // Error response<br>
                                http_response_code(404);<br>
                                return json_encode([<br>
                                &nbsp;&nbsp;&nbsp;&nbsp;'status' => 'error',<br>
                                &nbsp;&nbsp;&nbsp;&nbsp;'message' => 'User not found'<br>
                                ]);
                            </code>
                        </div>
                        
                        <h4>Authentication</h4>
                        <div class="bg-light p-3 rounded">
                            <code>
                                // Protected API routes<br>
                                $router->group(['middleware' => ['api', 'auth']], function($router) {<br>
                                &nbsp;&nbsp;&nbsp;&nbsp;$router->get('/profile', 'ApiController@profile');<br>
                                &nbsp;&nbsp;&nbsp;&nbsp;$router->post('/posts', 'ApiController@createPost');<br>
                                });
                            </code>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Quick Links -->
            <div class="card border-0 bg-light">
                <div class="card-body p-5 text-center">
                    <h3 class="mb-4">Need More Help?</h3>
                    <div class="d-flex gap-3 justify-content-center flex-wrap">
                        <a href="/demo/template-syntax" class="btn btn-primary">
                            <i class="bi bi-play-circle"></i> View Demo
                        </a>
                        <a href="/api" class="btn btn-outline-primary">
                            <i class="bi bi-api"></i> API Reference
                        </a>
                        <a href="https://github.com" class="btn btn-outline-secondary">
                            <i class="bi bi-github"></i> GitHub
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 