<?php

namespace Core\Command\Commands;

use Core\Command\BaseCommand;

class MakeApiControllerCommand extends BaseCommand
{
    protected $signature = 'make:api-controller';
    protected $description = 'Create a new API controller class';
    protected $help = 'This command creates a new API controller class in the app/Controllers/Api directory.

Usage:
  make:api-controller ControllerName [options]

Arguments:
  name                 The name of the API controller

Options:
  -r, --resource       Create a resource API controller with CRUD methods
  -f, --force          Overwrite the controller if it already exists

Examples:
  php retrina make:api-controller UserApiController
  php retrina make:api-controller PostApiController --resource
  php retrina make:api-controller ProductApiController -r';

    public function handle(array $arguments = [], array $options = []): int
    {
        if (empty($arguments)) {
            $this->error('API Controller name is required.');
            $this->line('Usage: make:api-controller ControllerName [options]');
            return 1;
        }

        $controllerName = $this->studly($arguments[0]);
        if (substr($controllerName, -10) !== 'Controller') {
            $controllerName .= 'Controller';
        }

        $isResource = isset($options['r']) || isset($options['resource']);
        $force = isset($options['f']) || isset($options['force']);

        return $this->createApiController($controllerName, $isResource, $force);
    }

    private function createApiController(string $name, bool $isResource, bool $force): int
    {
        $controllerPath = $this->getApiControllerPath($name);

        if (!$force && !$this->checkFileExists($controllerPath)) {
            $this->warning('API Controller creation cancelled.');
            return 1;
        }

        $this->ensureDirectory(dirname($controllerPath));

        $content = $this->generateApiControllerContent($name, $isResource);

        if (file_put_contents($controllerPath, $content) === false) {
            $this->error('Failed to create API controller file.');
            return 1;
        }

        $this->success("API Controller created successfully: {$controllerPath}");

        if ($isResource) {
            $this->info('Resource API controller created with the following methods:');
            $this->line('  - index()    : GET /api/resource - List all resources');
            $this->line('  - show()     : GET /api/resource/{id} - Show specific resource');
            $this->line('  - store()    : POST /api/resource - Create new resource');
            $this->line('  - update()   : PUT /api/resource/{id} - Update specific resource');
            $this->line('  - destroy()  : DELETE /api/resource/{id} - Delete specific resource');
        }

        return 0;
    }

    private function getApiControllerPath(string $name): string
    {
        return dirname(__DIR__, 3) . "/app/Controllers/Api/{$name}.php";
    }

    private function generateApiControllerContent(string $name, bool $isResource): string
    {
        if ($isResource) {
            $modelName = str_replace(['Api', 'Controller'], '', $name);
            return $this->getResourceApiControllerTemplate($name, $modelName);
        }

        return $this->getBasicApiControllerTemplate($name);
    }

    private function getBasicApiControllerTemplate(string $name): string
    {
        return "<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;

class {$name} extends BaseController
{
    /**
     * Handle API request
     */
    public function index()
    {
        \$data = [
            'message' => 'API endpoint working',
            'timestamp' => date('c'),
            'status' => 'success'
        ];

        return \$this->jsonResponse(\$data);
    }

    /**
     * Return JSON response
     */
    protected function jsonResponse(\$data, \$statusCode = 200)
    {
        http_response_code(\$statusCode);
        header('Content-Type: application/json');
        echo json_encode(\$data, JSON_PRETTY_PRINT);
        exit;
    }

    /**
     * Return JSON error response
     */
    protected function jsonError(\$message, \$statusCode = 400, \$errorCode = null)
    {
        \$data = [
            'status' => 'error',
            'message' => \$message
        ];

        if (\$errorCode) {
            \$data['error_code'] = \$errorCode;
        }

        return \$this->jsonResponse(\$data, \$statusCode);
    }

    /**
     * Validate JSON input
     */
    protected function getJsonInput()
    {
        \$input = json_decode(file_get_contents('php://input'), true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            \$this->jsonError('Invalid JSON input', 400, 'INVALID_JSON');
        }

        return \$input ?: [];
    }
}
";
    }

    private function getResourceApiControllerTemplate(string $name, string $modelName): string
    {
        $resourceName = strtolower($modelName);
        $resourcePlural = $this->plural($resourceName);

        return "<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\\{$modelName};

class {$name} extends BaseController
{
    /**
     * GET /api/{$resourcePlural}
     * List all {$resourcePlural}
     */
    public function index()
    {
        try {
            // TODO: Implement actual database fetching
            \${$resourcePlural} = []; // {$modelName}::all();
            
            return \$this->jsonResponse([
                'status' => 'success',
                'data' => \${$resourcePlural},
                'count' => count(\${$resourcePlural})
            ]);
        } catch (\Exception \$e) {
            return \$this->jsonError('Failed to fetch {$resourcePlural}', 500, 'FETCH_ERROR');
        }
    }

    /**
     * GET /api/{$resourcePlural}/{id}
     * Show specific {$resourceName}
     */
    public function show(\$id)
    {
        try {
            // TODO: Implement actual database fetching
            // \${$resourceName} = {$modelName}::find(\$id);
            
            // Mock data for demonstration
            if (\$id == 1) {
                \${$resourceName} = [
                    'id' => 1,
                    'name' => 'Sample {$modelName}',
                    'created_at' => date('c'),
                    'updated_at' => date('c')
                ];
                
                return \$this->jsonResponse([
                    'status' => 'success',
                    'data' => \${$resourceName}
                ]);
            }

            return \$this->jsonError('{$modelName} not found', 404, 'RESOURCE_NOT_FOUND');
        } catch (\Exception \$e) {
            return \$this->jsonError('Failed to fetch {$resourceName}', 500, 'FETCH_ERROR');
        }
    }

    /**
     * POST /api/{$resourcePlural}
     * Create new {$resourceName}
     */
    public function store()
    {
        try {
            \$input = \$this->getJsonInput();
            
            // Basic validation
            \$required = ['name']; // Add your required fields
            \$missing = [];
            
            foreach (\$required as \$field) {
                if (!isset(\$input[\$field]) || empty(\$input[\$field])) {
                    \$missing[] = \$field;
                }
            }
            
            if (!empty(\$missing)) {
                return \$this->jsonError('Missing required fields: ' . implode(', ', \$missing), 400, 'VALIDATION_ERROR');
            }
            
            // TODO: Implement actual database creation
            // \${$resourceName} = {$modelName}::create(\$input);
            
            // Mock created resource
            \${$resourceName} = array_merge(\$input, [
                'id' => rand(100, 999),
                'created_at' => date('c'),
                'updated_at' => date('c')
            ]);
            
            return \$this->jsonResponse([
                'status' => 'success',
                'message' => '{$modelName} created successfully',
                'data' => \${$resourceName}
            ], 201);
        } catch (\Exception \$e) {
            return \$this->jsonError('Failed to create {$resourceName}', 500, 'CREATE_ERROR');
        }
    }

    /**
     * PUT /api/{$resourcePlural}/{id}
     * Update specific {$resourceName}
     */
    public function update(\$id)
    {
        try {
            \$input = \$this->getJsonInput();
            
            // TODO: Implement actual database update
            // \${$resourceName} = {$modelName}::find(\$id);
            // if (!\${$resourceName}) {
            //     return \$this->jsonError('{$modelName} not found', 404, 'RESOURCE_NOT_FOUND');
            // }
            // \${$resourceName}->update(\$input);
            
            // Mock updated resource
            \${$resourceName} = array_merge(\$input, [
                'id' => (int)\$id,
                'updated_at' => date('c')
            ]);
            
            return \$this->jsonResponse([
                'status' => 'success',
                'message' => '{$modelName} updated successfully',
                'data' => \${$resourceName}
            ]);
        } catch (\Exception \$e) {
            return \$this->jsonError('Failed to update {$resourceName}', 500, 'UPDATE_ERROR');
        }
    }

    /**
     * DELETE /api/{$resourcePlural}/{id}
     * Delete specific {$resourceName}
     */
    public function destroy(\$id)
    {
        try {
            // TODO: Implement actual database deletion
            // \${$resourceName} = {$modelName}::find(\$id);
            // if (!\${$resourceName}) {
            //     return \$this->jsonError('{$modelName} not found', 404, 'RESOURCE_NOT_FOUND');
            // }
            // \${$resourceName}->delete();
            
            return \$this->jsonResponse([
                'status' => 'success',
                'message' => '{$modelName} deleted successfully',
                'deleted_id' => (int)\$id
            ]);
        } catch (\Exception \$e) {
            return \$this->jsonError('Failed to delete {$resourceName}', 500, 'DELETE_ERROR');
        }
    }

    /**
     * Return JSON response
     */
    protected function jsonResponse(\$data, \$statusCode = 200)
    {
        http_response_code(\$statusCode);
        header('Content-Type: application/json');
        echo json_encode(\$data, JSON_PRETTY_PRINT);
        exit;
    }

    /**
     * Return JSON error response
     */
    protected function jsonError(\$message, \$statusCode = 400, \$errorCode = null)
    {
        \$data = [
            'status' => 'error',
            'message' => \$message
        ];

        if (\$errorCode) {
            \$data['error_code'] = \$errorCode;
        }

        return \$this->jsonResponse(\$data, \$statusCode);
    }

    /**
     * Validate JSON input
     */
    protected function getJsonInput()
    {
        \$input = json_decode(file_get_contents('php://input'), true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            \$this->jsonError('Invalid JSON input', 400, 'INVALID_JSON');
        }

        return \$input ?: [];
    }
}
";
    }
} 