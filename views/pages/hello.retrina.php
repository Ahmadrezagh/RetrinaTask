@extends('layouts.app')

@section('title', 'Hello - Retrina Framework')

@section('content')
<div class="container py-5">
    <!-- Hero Section -->
    <div class="row mb-5">
        <div class="col-12 text-center">
            <div class="mb-4">
                <i class="bi bi-hand-wave display-3 text-warning animated-wave"></i>
            </div>
            <h1 class="display-3 fw-bold mb-3">Hello, World! 👋</h1>
            <p class="lead text-muted mb-4">
                Welcome to the friendly corner of Retrina Framework
            </p>
            <div class="current-time mb-4">
                <div class="card border-0 bg-light d-inline-block">
                    <div class="card-body p-3">
                        <small class="text-muted">Current Server Time</small><br>
                        <strong id="current-time" class="h5">{{ date('Y-m-d H:i:s T') }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Interactive Greeting -->
    <div class="row mb-5">
        <div class="col-lg-8 mx-auto">
            <div class="card border-0 shadow-lg">
                <div class="card-body p-5">
                    <h2 class="h3 mb-4 text-center">
                        <i class="bi bi-person-heart text-success"></i> Personal Greeting
                    </h2>
                    
                    <div class="mb-4">
                        <label for="user-name" class="form-label">What's your name?</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="bi bi-person"></i>
                            </span>
                            <input type="text" class="form-control form-control-lg" id="user-name" 
                                   placeholder="Enter your name" value="Friend">
                            <button class="btn btn-primary btn-lg" onclick="updateGreeting()">
                                <i class="bi bi-arrow-right"></i> Greet Me!
                            </button>
                        </div>
                    </div>
                    
                    <div id="personal-greeting" class="text-center p-4 bg-gradient-primary text-white rounded">
                        <h3 class="h4 mb-2">Hello, <span id="greeting-name">Friend</span>! 🎉</h3>
                        <p class="mb-0">Nice to meet you! Welcome to Retrina Framework.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Fun Facts -->
    <div class="row mb-5">
        <div class="col-12 text-center mb-4">
            <h2 class="h3">
                <i class="bi bi-lightbulb text-warning"></i> Fun Framework Facts
            </h2>
        </div>
    </div>
    
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm text-center">
                <div class="card-body p-4">
                    <div class="mb-3">
                        <i class="bi bi-speedometer2 display-4 text-primary"></i>
                    </div>
                    <h5 class="card-title">Lightning Fast</h5>
                    <p class="card-text text-muted">
                        Built for performance with intelligent caching and optimized routing.
                    </p>
                    <div class="mt-auto">
                        <span class="badge bg-primary">Performance</span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm text-center">
                <div class="card-body p-4">
                    <div class="mb-3">
                        <i class="bi bi-heart display-4 text-danger"></i>
                    </div>
                    <h5 class="card-title">Developer Friendly</h5>
                    <p class="card-text text-muted">
                        Designed with developer experience in mind - clean, intuitive, and powerful.
                    </p>
                    <div class="mt-auto">
                        <span class="badge bg-danger">DX</span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm text-center">
                <div class="card-body p-4">
                    <div class="mb-3">
                        <i class="bi bi-globe display-4 text-success"></i>
                    </div>
                    <h5 class="card-title">Multi-Database</h5>
                    <p class="card-text text-muted">
                        Works seamlessly with MySQL, PostgreSQL, and SQLite databases.
                    </p>
                    <div class="mt-auto">
                        <span class="badge bg-success">Flexibility</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Random Programming Quote -->
    <div class="row mb-5">
        <div class="col-lg-8 mx-auto">
            <div class="card border-0 bg-dark text-white">
                <div class="card-body p-5 text-center">
                    <h3 class="mb-4">
                        <i class="bi bi-quote"></i> Daily Inspiration
                    </h3>
                    <div id="quote-container">
                        <blockquote class="blockquote mb-4">
                            <p id="quote-text" class="h5 fw-normal">
                                "The best error message is the one that never shows up."
                            </p>
                        </blockquote>
                        <figcaption id="quote-author" class="blockquote-footer text-light">
                            Thomas Fuchs
                        </figcaption>
                    </div>
                    <button class="btn btn-outline-light mt-3" onclick="getNewQuote()">
                        <i class="bi bi-arrow-clockwise"></i> Get New Quote
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- System Information -->
    <div class="row mb-5">
        <div class="col-lg-10 mx-auto">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-5">
                    <h3 class="h4 mb-4 text-center">
                        <i class="bi bi-info-circle text-info"></i> System Information
                    </h3>
                    
                    <div class="row text-center">
                        <div class="col-md-3">
                            <div class="p-3">
                                <i class="bi bi-server display-5 text-primary mb-2"></i>
                                <h6>PHP Version</h6>
                                <code>{{ phpversion() }}</code>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="p-3">
                                <i class="bi bi-memory display-5 text-success mb-2"></i>
                                <h6>Memory Usage</h6>
                                <code>{{ round(memory_get_usage() / 1024 / 1024, 2) }} MB</code>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="p-3">
                                <i class="bi bi-clock display-5 text-warning mb-2"></i>
                                <h6>Server Time</h6>
                                <code>{{ date('H:i:s') }}</code>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="p-3">
                                <i class="bi bi-calendar display-5 text-info mb-2"></i>
                                <h6>Today</h6>
                                <code>{{ date('M d, Y') }}</code>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row">
        <div class="col-12 text-center">
            <div class="p-5 bg-light rounded">
                <h3 class="mb-4">Where to go next?</h3>
                <div class="d-flex gap-3 justify-content-center flex-wrap">
                    <a href="/docs" class="btn btn-primary btn-lg">
                        <i class="bi bi-book"></i> Read Documentation
                    </a>
                    <a href="/demo/template-syntax" class="btn btn-outline-primary btn-lg">
                        <i class="bi bi-play-circle"></i> View Demo
                    </a>
                    <a href="/api" class="btn btn-outline-secondary btn-lg">
                        <i class="bi bi-api"></i> API Reference
                    </a>
                    <a href="/about" class="btn btn-outline-info btn-lg">
                        <i class="bi bi-info-circle"></i> About Framework
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@keyframes wave {
    0%, 100% { transform: rotate(0deg); }
    25% { transform: rotate(20deg); }
    75% { transform: rotate(-10deg); }
}

.animated-wave {
    animation: wave 2s ease-in-out infinite;
    transform-origin: 70% 70%;
}

.card {
    transition: transform 0.2s ease-in-out;
}

.card:hover {
    transform: translateY(-5px);
}

#personal-greeting {
    transition: all 0.3s ease-in-out;
}
</style>
@endsection

@section('scripts')
<script>
// Programming quotes
const quotes = [
    {
        text: "The best error message is the one that never shows up.",
        author: "Thomas Fuchs"
    },
    {
        text: "Code is like humor. When you have to explain it, it's bad.",
        author: "Cory House"
    },
    {
        text: "Programs must be written for people to read, and only incidentally for machines to execute.",
        author: "Harold Abelson"
    },
    {
        text: "First, solve the problem. Then, write the code.",
        author: "John Johnson"
    },
    {
        text: "Experience is the name everyone gives to their mistakes.",
        author: "Oscar Wilde"
    },
    {
        text: "In order to be irreplaceable, one must always be different.",
        author: "Coco Chanel"
    },
    {
        text: "The most important single aspect of software development is to be clear about what you are trying to build.",
        author: "Bjarne Stroustrup"
    },
    {
        text: "Simplicity is the ultimate sophistication.",
        author: "Leonardo da Vinci"
    }
];

// Update greeting function
function updateGreeting() {
    const name = document.getElementById('user-name').value.trim() || 'Friend';
    const greetingElement = document.getElementById('greeting-name');
    const container = document.getElementById('personal-greeting');
    
    // Add animation
    container.style.transform = 'scale(0.95)';
    setTimeout(() => {
        greetingElement.textContent = name;
        container.style.transform = 'scale(1)';
    }, 150);
}

// Get new quote
function getNewQuote() {
    const randomQuote = quotes[Math.floor(Math.random() * quotes.length)];
    const quoteText = document.getElementById('quote-text');
    const quoteAuthor = document.getElementById('quote-author');
    const container = document.getElementById('quote-container');
    
    // Add fade effect
    container.style.opacity = '0.5';
    setTimeout(() => {
        quoteText.textContent = `"${randomQuote.text}"`;
        quoteAuthor.textContent = randomQuote.author;
        container.style.opacity = '1';
    }, 150);
}

// Update time every second
function updateTime() {
    const now = new Date();
    const timeString = now.toLocaleString();
    const timeElement = document.getElementById('current-time');
    if (timeElement) {
        timeElement.textContent = timeString;
    }
}

// Update greeting when user types
document.getElementById('user-name').addEventListener('input', function(e) {
    if (e.target.value.trim()) {
        updateGreeting();
    }
});

// Enter key support
document.getElementById('user-name').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        updateGreeting();
    }
});

// Update time every second
setInterval(updateTime, 1000);

// Initial setup
document.addEventListener('DOMContentLoaded', function() {
    updateTime();
    updateGreeting();
});
</script>
@endsection 