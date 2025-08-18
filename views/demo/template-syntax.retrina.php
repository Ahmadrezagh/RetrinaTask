@extends('layouts.app')

@section('title')
{{ $title ?? 'Template Syntax Demo - Retrina Framework' }}
@endsection

@section('content')
<div class="container">
    <div class="template-demo">
        <!-- Hero Section -->
        <div class="card mb-4">
            <div class="card-header">🎨 New Template Syntax Demo</div>
            <div class="card-body">
                <h4 class="text-primary mb-3">Welcome to the new Retrina template syntax!</h4>
                <p class="lead">
                    You can now use <code>{{ }}</code> instead of <code>&lt;?php ?&gt;</code> tags for cleaner, more readable templates.
                </p>
                
                <div class="row">
                    <div class="col-md-6">
                        <h6>Old Syntax:</h6>
                        <pre class="bg-light p-2 rounded"><code>&lt;?php $this->section('title'); ?&gt;
My Page Title
&lt;?php $this->endSection(); ?&gt;</code></pre>
                    </div>
                    <div class="col-md-6">
                        <h6>New Syntax:</h6>
                        <pre class="bg-success text-white p-2 rounded"><code>@section('title')
My Page Title
@endsection</code></pre>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Variable Output -->
        <div class="card mb-4">
            <div class="card-header">📊 Variable Output</div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <h6>Escaped Output (Safe):</h6>
                        <code>{{{ $message }}}</code>
                        <p class="mt-2 p-2 bg-light rounded">{{{ $message ?? 'Default message' }}}</p>
                    </div>
                    <div class="col-md-4">
                        <h6>Raw Output:</h6>
                        <code>{{ $message }}</code>
                        <p class="mt-2 p-2 bg-light rounded">{{ $message ?? 'Default message' }}</p>
                    </div>
                    <div class="col-md-4">
                        <h6>Unescaped Output:</h6>
                        <code>{!! $html_content !!}</code>
                        <p class="mt-2 p-2 bg-light rounded">{!! $html_content ?? '<strong>Bold text</strong>' !!}</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Control Structures -->
        <div class="card mb-4">
            <div class="card-header">🔀 Control Structures</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Conditional Statements:</h6>
                        @if(isset($show_features) && $show_features)
                            <div class="alert alert-success">
                                <i class="bi bi-check-circle me-2"></i>
                                Features are enabled!
                            </div>
                        @elseif(isset($demo_mode))
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle me-2"></i>
                                Running in demo mode
                            </div>
                        @else
                            <div class="alert alert-warning">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                Default state
                            </div>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <h6>Isset/Empty Checks:</h6>
                        @isset($demo_data)
                            <p class="text-success">✅ Demo data is set</p>
                        @endisset
                        
                        @empty($empty_var)
                            <p class="text-info">ℹ️ Empty variable detected</p>
                        @endempty
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Loops -->
        <div class="card mb-4">
            <div class="card-header">🔄 Loops & Iteration</div>
            <div class="card-body">
                @isset($features)
                <h6>Framework Features:</h6>
                <div class="row">
                    @foreach($features as $index => $feature)
                        <div class="col-md-6 col-lg-4 mb-2">
                            <div class="badge bg-primary me-2">{{ $index + 1 }}</div>
                            {{ $feature }}
                        </div>
                    @endforeach
                </div>
                @endisset
                
                <h6 class="mt-3">Number Loop:</h6>
                <div class="d-flex gap-2 flex-wrap">
                    @for($i = 1; $i <= 5; $i++)
                        <span class="badge bg-secondary">{{ $i }}</span>
                    @endfor
                </div>
            </div>
        </div>
        
        <!-- Authentication -->
        <div class="card mb-4">
            <div class="card-header">🔐 Authentication Helpers</div>
            <div class="card-body">
                @auth
                    <div class="alert alert-success">
                        <i class="bi bi-person-check me-2"></i>
                        User is authenticated
                    </div>
                @endauth
                
                @guest
                    <div class="alert alert-info">
                        <i class="bi bi-person-x me-2"></i>
                        User is not authenticated (guest)
                    </div>
                @endguest
            </div>
        </div>
        
        <!-- Form Helpers -->
        <div class="card mb-4">
            <div class="card-header">📝 Form Helpers</div>
            <div class="card-body">
                <form class="row g-3">
                    
                    <div class="col-md-6">
                        <label for="demo-name" class="form-label">Name:</label>
                        <input type="text" id="demo-name" name="name" class="form-control" 
                               value="Demo User">
                    </div>
                    <div class="col-md-6">
                        <label for="demo-email" class="form-label">Email:</label>
                        <input type="email" id="demo-email" name="email" class="form-control" 
                               value="demo@example.com">
                    </div>
                    <div class="col-12">
                        <button type="button" class="btn btn-primary">
                            Submit Demo Form
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Syntax Reference -->
        <div class="card mb-4">
            <div class="card-header">📖 Quick Reference</div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Feature</th>
                                <th>Old Syntax</th>
                                <th>New Syntax</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Output</td>
                                <td><code>&lt;?php echo $var; ?&gt;</code></td>
                                <td><code>{{ $var }}</code></td>
                            </tr>
                            <tr>
                                <td>Escaped Output</td>
                                <td><code>&lt;?php echo htmlspecialchars($var); ?&gt;</code></td>
                                <td><code>{{{ $var }}}</code></td>
                            </tr>
                            <tr>
                                <td>If Statement</td>
                                <td><code>&lt;?php if($condition): ?&gt;</code></td>
                                <td><code>@if($condition)</code></td>
                            </tr>
                            <tr>
                                <td>Foreach Loop</td>
                                <td><code>&lt;?php foreach($items as $item): ?&gt;</code></td>
                                <td><code>@foreach($items as $item)</code></td>
                            </tr>
                            <tr>
                                <td>Section</td>
                                <td><code>&lt;?php $this->section('name'); ?&gt;</code></td>
                                <td><code>@section('name')</code></td>
                            </tr>
                            <tr>
                                <td>CSRF</td>
                                <td><code>&lt;?php echo $this->csrfField(); ?&gt;</code></td>
                                <td><code>@csrf</code></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .template-demo {
        animation: fadeInUp 0.6s ease-out;
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .card {
        transition: transform 0.2s ease;
    }
    
    .card:hover {
        transform: translateY(-2px);
    }
    
    code {
        background: #f8f9fa;
        padding: 2px 6px;
        border-radius: 3px;
        font-size: 0.9em;
    }
    
    pre code {
        background: transparent;
        padding: 0;
    }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('🎨 Template syntax demo loaded!');
    });
</script>
@endsection 