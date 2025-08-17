<?php

namespace Core\Command\Commands;

use Core\Command\BaseCommand;

class MakeControllerCommand extends BaseCommand
{
    protected $signature = 'make:controller';
    protected $description = 'Create a new controller class';
    protected $help = 'This command creates a new controller class in the app/Controllers directory.

Usage:
  make:controller ControllerName [options]

Arguments:
  name                 The name of the controller

Options:
  -r, --resource       Create a resource controller with CRUD methods
  -f, --force          Overwrite the controller if it already exists

Examples:
  php retrina.php make:controller UserController
  php retrina.php make:controller PostController --resource
  php retrina.php make:controller AdminController -r';

    public function handle(array $arguments = [], array $options = []): int
    {
        if (empty($arguments)) {
            $this->error('Controller name is required.');
            $this->line('Usage: make:controller ControllerName [options]');
            return 1;
        }

        $controllerName = $this->studly($arguments[0]);
        if (substr($controllerName, -10) !== 'Controller') {
            $controllerName .= 'Controller';
        }

        $isResource = isset($options['r']) || isset($options['resource']);
        $force = isset($options['f']) || isset($options['force']);

        return $this->createController($controllerName, $isResource, $force);
    }

    private function createController(string $name, bool $isResource, bool $force): int
    {
        $controllerPath = $this->getControllerPath($name);

        if (!$force && !$this->checkFileExists($controllerPath)) {
            $this->warning('Controller creation cancelled.');
            return 1;
        }

        $this->ensureDirectory(dirname($controllerPath));

        $content = $this->generateControllerContent($name, $isResource);

        if (file_put_contents($controllerPath, $content) === false) {
            $this->error('Failed to create controller file.');
            return 1;
        }

        $this->success("Controller created successfully: {$controllerPath}");

        if ($isResource) {
            $this->info('Resource controller created with the following methods:');
            $this->line('  - index()    : Display a listing of the resource');
            $this->line('  - show()     : Display the specified resource');
            $this->line('  - create()   : Show the form for creating a new resource');
            $this->line('  - store()    : Store a newly created resource');
            $this->line('  - edit()     : Show the form for editing the specified resource');
            $this->line('  - update()   : Update the specified resource');
            $this->line('  - destroy()  : Remove the specified resource');
        }

        return 0;
    }

    private function getControllerPath(string $name): string
    {
        return dirname(__DIR__, 3) . "/app/Controllers/{$name}.php";
    }

    private function generateControllerContent(string $name, bool $isResource): string
    {
        $modelName = str_replace('Controller', '', $name);
        $modelVariable = '$' . $this->camel($modelName);
        $pluralModel = $this->plural(strtolower($modelName));

        if ($isResource) {
            return $this->getResourceControllerTemplate($name, $modelName, $modelVariable, $pluralModel);
        }

        return $this->getBasicControllerTemplate($name);
    }

    private function getBasicControllerTemplate(string $name): string
    {
        return "<?php

namespace App\Controllers;

class {$name} extends BaseController
{
    /**
     * Display the index page
     */
    public function index()
    {
        // TODO: Implement index method
        return \$this->view('index');
    }
}
";
    }

    private function getResourceControllerTemplate(string $name, string $modelName, string $modelVariable, string $pluralModel): string
    {
        return "<?php

namespace App\Controllers;

use App\Models\\{$modelName};

class {$name} extends BaseController
{
    /**
     * Display a listing of the resource
     */
    public function index()
    {
        \${$pluralModel} = {$modelName}::all();
        
        return \$this->view('{$pluralModel}/index', [
            '{$pluralModel}' => \${$pluralModel}
        ]);
    }

    /**
     * Show the form for creating a new resource
     */
    public function create()
    {
        return \$this->view('{$pluralModel}/create');
    }

    /**
     * Store a newly created resource in storage
     */
    public function store()
    {
        // Validate the request
        \$data = \$this->validate([
            // Add your validation rules here
        ]);

        {$modelVariable} = {$modelName}::create(\$data);

        // Redirect with success message
        \$_SESSION['flash_success'] = '{$modelName} created successfully!';
        return \$this->redirect('/{$pluralModel}');
    }

    /**
     * Display the specified resource
     */
    public function show(\$id)
    {
        {$modelVariable} = {$modelName}::find(\$id);
        
        if (!{$modelVariable}) {
            \$_SESSION['flash_error'] = '{$modelName} not found.';
            return \$this->redirect('/{$pluralModel}');
        }

        return \$this->view('{$pluralModel}/show', [
            strtolower('{$modelName}') => {$modelVariable}
        ]);
    }

    /**
     * Show the form for editing the specified resource
     */
    public function edit(\$id)
    {
        {$modelVariable} = {$modelName}::find(\$id);
        
        if (!{$modelVariable}) {
            \$_SESSION['flash_error'] = '{$modelName} not found.';
            return \$this->redirect('/{$pluralModel}');
        }

        return \$this->view('{$pluralModel}/edit', [
            strtolower('{$modelName}') => {$modelVariable}
        ]);
    }

    /**
     * Update the specified resource in storage
     */
    public function update(\$id)
    {
        {$modelVariable} = {$modelName}::find(\$id);
        
        if (!{$modelVariable}) {
            \$_SESSION['flash_error'] = '{$modelName} not found.';
            return \$this->redirect('/{$pluralModel}');
        }

        // Validate the request
        \$data = \$this->validate([
            // Add your validation rules here
        ]);

        {$modelVariable}->update(\$data);

        // Redirect with success message
        \$_SESSION['flash_success'] = '{$modelName} updated successfully!';
        return \$this->redirect('/{$pluralModel}/' . \$id);
    }

    /**
     * Remove the specified resource from storage
     */
    public function destroy(\$id)
    {
        {$modelVariable} = {$modelName}::find(\$id);
        
        if (!{$modelVariable}) {
            \$_SESSION['flash_error'] = '{$modelName} not found.';
            return \$this->redirect('/{$pluralModel}');
        }

        {$modelVariable}->delete();

        // Redirect with success message
        \$_SESSION['flash_success'] = '{$modelName} deleted successfully!';
        return \$this->redirect('/{$pluralModel}');
    }
}
";
    }
} 