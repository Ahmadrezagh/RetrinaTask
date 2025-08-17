<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Retrina Framework' ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            text-align: center;
        }
        .welcome {
            text-align: center;
            color: #666;
            margin: 20px 0;
        }
        .features {
            margin: 20px 0;
        }
        .feature {
            background: #f8f9fa;
            padding: 15px;
            margin: 10px 0;
            border-left: 4px solid #007bff;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1><?= $title ?? 'Welcome to Retrina Framework' ?></h1>
        
        <div class="welcome">
            <p><?= $message ?? 'A custom PHP MVC Framework' ?></p>
        </div>
        
        <div class="features">
            <div class="feature">
                <h3>✅ MVC Architecture</h3>
                <p>Clean separation of concerns with Models, Views, and Controllers</p>
            </div>
            
            <div class="feature">
                <h3>🚀 Custom Router</h3>
                <p>Flexible routing system with parameter support</p>
            </div>
            
            <div class="feature">
                <h3>🔧 Auto-loading</h3>
                <p>PSR-4 compliant autoloader for easy class loading</p>
            </div>
            
            <div class="feature">
                <h3>💾 Database Ready</h3>
                <p>PDO-based database abstraction layer</p>
            </div>
        </div>
        
        <?php if (isset($data) && !empty($data)): ?>
        <div class="feature">
            <h3>📊 Data Passed to View:</h3>
            <pre><?= print_r($data, true) ?></pre>
        </div>
        <?php endif; ?>
    </div>
</body>
</html> 