<?php

namespace Core\Command\Commands;

use Core\Command\BaseCommand;

class ServeCommand extends BaseCommand
{
    protected $signature = 'serve';
    protected $description = 'Start the development server';
    protected $help = 'This command starts the PHP development server for the Retrina Framework.

Usage:
  serve [options]

Options:
  --port=PORT          Port to run the server on (default: 8585)
  --host=HOST          Host to bind the server to (default: localhost)
  -o, --open           Open the application in the browser after starting

Examples:
  php retrina serve                    # Start server on localhost:8585
  php retrina serve --port=8080        # Start server on port 8080
  php retrina serve --host=0.0.0.0     # Make server accessible from other devices
  php retrina serve --port=3000 --open # Start on port 3000 and open browser';

    public function handle(array $arguments = [], array $options = []): int
    {
        $port = $options['port'] ?? 8585;
        $host = $options['host'] ?? 'localhost';
        $openBrowser = isset($options['o']) || isset($options['open']);

        // Validate port
        if (!is_numeric($port) || $port < 1 || $port > 65535) {
            $this->error('Invalid port number. Port must be between 1 and 65535.');
            return 1;
        }

        // Check if port is already in use
        if ($this->isPortInUse($host, $port)) {
            $this->error("Port {$port} is already in use on {$host}.");
            $this->info("Try using a different port with --port=XXXX");
            return 1;
        }

        $this->startServer($host, $port, $openBrowser);
        return 0;
    }

    private function isPortInUse(string $host, int $port): bool
    {
        $connection = @fsockopen($host, $port, $errno, $errstr, 1);
        if ($connection) {
            fclose($connection);
            return true;
        }
        return false;
    }

    private function startServer(string $host, int $port, bool $openBrowser): void
    {
        $documentRoot = dirname(__DIR__, 3);
        $url = "http://{$host}:{$port}";

        $this->line();
        $this->colored("🚀 Retrina Framework Development Server", self::COLOR_GREEN . self::BOLD);
        $this->line();
        $this->info("Starting server on {$url}");
        $this->info("Document root: {$documentRoot}");
        $this->info("Press Ctrl+C to stop the server");
        $this->line();

        // Show some helpful URLs
        $this->colored("📋 Quick Links:", self::COLOR_BLUE . self::BOLD);
        $this->line("  🏠 Home:        {$url}");
        $this->line("  🔧 API Info:    {$url}/api");
        $this->line("  💚 Health:      {$url}/api/health");
        $this->line("  🧪 Demo:        {$url}/demo/template-syntax");
        $this->line("  🔐 Login:       {$url}/login");
        $this->line();

        // Environment info
        $this->colored("⚡ Server Information:", self::COLOR_YELLOW);
        $this->line("  PHP Version:    " . PHP_VERSION);
        $this->line("  Server:         PHP Built-in Development Server");
        $this->line("  Environment:    Development");
        $this->line();

        // Open browser if requested
        if ($openBrowser) {
            $this->info("Opening {$url} in your default browser...");
            $this->openInBrowser($url);
        }

        $this->warning("Note: This server is for development only. Do not use in production!");
        $this->line();

        // Start the server
        $command = "php -S {$host}:{$port} -t " . escapeshellarg($documentRoot);
        
        // Use passthru to show real-time output
        passthru($command);
    }

    private function openInBrowser(string $url): void
    {
        $os = strtolower(PHP_OS);
        
        if (strpos($os, 'darwin') !== false) {
            // macOS
            exec("open " . escapeshellarg($url));
        } elseif (strpos($os, 'win') !== false) {
            // Windows
            exec("start " . escapeshellarg($url));
        } elseif (strpos($os, 'linux') !== false) {
            // Linux
            exec("xdg-open " . escapeshellarg($url));
        }
    }
} 