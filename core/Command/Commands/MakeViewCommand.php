<?php

namespace Core\Command\Commands;

use Core\Command\BaseCommand;

class MakeViewCommand extends BaseCommand
{
    protected $signature = 'make:view';
    protected $description = 'Create a new view file';
    protected $help = 'This command creates a new view file in the views directory using Retrina template syntax.

Usage:
  make:view view_name [options]

Arguments:
  name                 The name of the view (can include subdirectories)

Options:
  --extends=layout     Specify the layout to extend
  --resource           Create CRUD views (index, create, show, edit)
  -f, --force          Overwrite the view if it already exists

Examples:
  php retrina.php make:view home/index
  php retrina.php make:view posts/index --extends=app
  php retrina.php make:view posts --resource';

    public function handle(array $arguments = [], array $options = []): int
    {
        if (empty($arguments)) {
            $this->error('View name is required.');
            $this->line('Usage: make:view view_name [options]');
            return 1;
        }

        $viewName = $arguments[0];
        $extends = $options['extends'] ?? 'app';
        $isResource = isset($options['resource']);
        $force = isset($options['f']) || isset($options['force']);

        if ($isResource) {
            return $this->createResourceViews($viewName, $extends, $force);
        }

        return $this->createView($viewName, $extends, $force);
    }

    private function createView(string $name, string $extends, bool $force): int
    {
        $viewPath = $this->getViewPath($name);

        if (!$force && !$this->checkFileExists($viewPath)) {
            $this->warning('View creation cancelled.');
            return 1;
        }

        $this->ensureDirectory(dirname($viewPath));

        $content = $this->generateViewContent($name, $extends);

        if (file_put_contents($viewPath, $content) === false) {
            $this->error('Failed to create view file.');
            return 1;
        }

        $this->success("View created successfully: {$viewPath}");
        return 0;
    }

    private function createResourceViews(string $name, string $extends, bool $force): int
    {
        $views = ['index', 'create', 'show', 'edit'];
        $created = 0;

        foreach ($views as $view) {
            $viewName = "{$name}/{$view}";
            $viewPath = $this->getViewPath($viewName);

            if (!$force && file_exists($viewPath)) {
                if (!$this->confirm("View already exists: {$viewPath}. Overwrite?", false)) {
                    continue;
                }
            }

            $this->ensureDirectory(dirname($viewPath));

            $content = $this->generateResourceViewContent($view, $name, $extends);

            if (file_put_contents($viewPath, $content) !== false) {
                $this->success("View created: {$viewPath}");
                $created++;
            } else {
                $this->error("Failed to create: {$viewPath}");
            }
        }

        $this->info("Created {$created} resource views for {$name}");
        return $created > 0 ? 0 : 1;
    }

    private function getViewPath(string $name): string
    {
        $name = str_replace('.', '/', $name);
        return dirname(__DIR__, 3) . "/views/{$name}.retrina.php";
    }

    private function generateViewContent(string $name, string $extends): string
    {
        $title = ucwords(str_replace(['/', '_', '-'], [' > ', ' ', ' '], $name));

        return "@extends('{$extends}')

@section('title')
{$title} - Retrina Framework
@endsection

@section('content')
<div class=\"container my-4\">
    <div class=\"row\">
        <div class=\"col-12\">
            <h1>{$title}</h1>
            
            <div class=\"card\">
                <div class=\"card-body\">
                    {{-- Your content goes here --}}
                    <p>Welcome to the {$title} page!</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
";
    }

    private function generateResourceViewContent(string $view, string $resource, string $extends): string
    {
        $resourceTitle = ucwords(str_replace(['_', '-'], ' ', $resource));
        $resourceSingular = $this->singular($resource);
        $resourcePlural = $this->plural($resource);

        switch ($view) {
            case 'index':
                return $this->getIndexViewTemplate($resourceTitle, $resourcePlural, $extends);
            case 'create':
                return $this->getCreateViewTemplate($resourceTitle, $resourceSingular, $extends);
            case 'show':
                return $this->getShowViewTemplate($resourceTitle, $resourceSingular, $extends);
            case 'edit':
                return $this->getEditViewTemplate($resourceTitle, $resourceSingular, $extends);
            default:
                return $this->generateViewContent("{$resource}/{$view}", $extends);
        }
    }

    private function getIndexViewTemplate(string $title, string $resourcePlural, string $extends): string
    {
        return "@extends('{$extends}')

@section('title')
{$title} - Retrina Framework
@endsection

@section('content')
<div class=\"container my-4\">
    <div class=\"d-flex justify-content-between align-items-center mb-4\">
        <h1>{$title}</h1>
        <a href=\"@url('/{$resourcePlural}/create')\" class=\"btn btn-primary\">
            <i class=\"bi bi-plus-circle me-1\"></i>
            Create New
        </a>
    </div>

    <div class=\"card\">
        <div class=\"card-body\">
            @isset(\${$resourcePlural})
                @if(count(\${$resourcePlural}) > 0)
                    <div class=\"table-responsive\">
                        <table class=\"table table-striped\">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(\${$resourcePlural} as \$item)
                                    <tr>
                                        <td>{{ \$item->id }}</td>
                                        <td>{{ \$item->name ?? 'N/A' }}</td>
                                        <td>{{ \$item->created_at ?? 'N/A' }}</td>
                                        <td>
                                            <div class=\"btn-group\" role=\"group\">
                                                <a href=\"@url('/{$resourcePlural}/' . \$item->id)\" class=\"btn btn-sm btn-outline-primary\">
                                                    <i class=\"bi bi-eye\"></i>
                                                </a>
                                                <a href=\"@url('/{$resourcePlural}/' . \$item->id . '/edit')\" class=\"btn btn-sm btn-outline-secondary\">
                                                    <i class=\"bi bi-pencil\"></i>
                                                </a>
                                                <button type=\"button\" class=\"btn btn-sm btn-outline-danger\" onclick=\"confirmDelete(\$item->id)\">
                                                    <i class=\"bi bi-trash\"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class=\"text-center py-4\">
                        <i class=\"bi bi-inbox display-1 text-muted\"></i>
                        <h4 class=\"text-muted\">No {$resourcePlural} found</h4>
                        <p class=\"text-muted\">Get started by creating your first item.</p>
                        <a href=\"@url('/{$resourcePlural}/create')\" class=\"btn btn-primary\">
                            <i class=\"bi bi-plus-circle me-1\"></i>
                            Create New
                        </a>
                    </div>
                @endif
            @else
                <div class=\"text-center py-4\">
                    <div class=\"spinner-border\" role=\"status\">
                        <span class=\"visually-hidden\">Loading...</span>
                    </div>
                </div>
            @endisset
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function confirmDelete(id) {
    if (confirm('Are you sure you want to delete this item?')) {
        // Create a form and submit it
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/{$resourcePlural}/' + id + '/delete';
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '@csrf_token';
        csrfToken.value = document.querySelector('meta[name=\"csrf-token\"]').getAttribute('content');
        
        form.appendChild(csrfToken);
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endsection
";
    }

    private function getCreateViewTemplate(string $title, string $resourceSingular, string $extends): string
    {
        return "@extends('{$extends}')

@section('title')
Create {$title} - Retrina Framework
@endsection

@section('content')
<div class=\"container my-4\">
    <div class=\"row justify-content-center\">
        <div class=\"col-md-8\">
            <div class=\"card\">
                <div class=\"card-header\">
                    <h4 class=\"mb-0\">Create New {$title}</h4>
                </div>
                <div class=\"card-body\">
                    <form action=\"@url('/{$resourceSingular}')\" method=\"POST\">
                        @csrf
                        
                        <div class=\"mb-3\">
                            <label for=\"name\" class=\"form-label\">Name</label>
                            <input type=\"text\" class=\"form-control\" id=\"name\" name=\"name\" 
                                   value=\"{{ old('name') }}\" required>
                        </div>
                        
                        <div class=\"mb-3\">
                            <label for=\"description\" class=\"form-label\">Description</label>
                            <textarea class=\"form-control\" id=\"description\" name=\"description\" 
                                      rows=\"3\">{{ old('description') }}</textarea>
                        </div>
                        
                        <div class=\"d-flex justify-content-between\">
                            <a href=\"@url('/{$resourceSingular}')\" class=\"btn btn-secondary\">
                                <i class=\"bi bi-arrow-left me-1\"></i>
                                Back
                            </a>
                            <button type=\"submit\" class=\"btn btn-primary\">
                                <i class=\"bi bi-check-circle me-1\"></i>
                                Create {$title}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
";
    }

    private function getShowViewTemplate(string $title, string $resourceSingular, string $extends): string
    {
        return "@extends('{$extends}')

@section('title')
{$title} Details - Retrina Framework
@endsection

@section('content')
<div class=\"container my-4\">
    <div class=\"row justify-content-center\">
        <div class=\"col-md-8\">
            @isset(\${$resourceSingular})
                <div class=\"card\">
                    <div class=\"card-header d-flex justify-content-between align-items-center\">
                        <h4 class=\"mb-0\">{$title} Details</h4>
                        <div class=\"btn-group\">
                            <a href=\"@url('/{$resourceSingular}/' . \${$resourceSingular}->id . '/edit')\" class=\"btn btn-outline-primary btn-sm\">
                                <i class=\"bi bi-pencil me-1\"></i>
                                Edit
                            </a>
                            <button type=\"button\" class=\"btn btn-outline-danger btn-sm\" onclick=\"confirmDelete()\">
                                <i class=\"bi bi-trash me-1\"></i>
                                Delete
                            </button>
                        </div>
                    </div>
                    <div class=\"card-body\">
                        <div class=\"row\">
                            <div class=\"col-sm-3\">
                                <strong>ID:</strong>
                            </div>
                            <div class=\"col-sm-9\">
                                {{ \${$resourceSingular}->id }}
                            </div>
                        </div>
                        <hr>
                        <div class=\"row\">
                            <div class=\"col-sm-3\">
                                <strong>Name:</strong>
                            </div>
                            <div class=\"col-sm-9\">
                                {{ \${$resourceSingular}->name ?? 'N/A' }}
                            </div>
                        </div>
                        <hr>
                        <div class=\"row\">
                            <div class=\"col-sm-3\">
                                <strong>Description:</strong>
                            </div>
                            <div class=\"col-sm-9\">
                                {{ \${$resourceSingular}->description ?? 'N/A' }}
                            </div>
                        </div>
                        <hr>
                        <div class=\"row\">
                            <div class=\"col-sm-3\">
                                <strong>Created:</strong>
                            </div>
                            <div class=\"col-sm-9\">
                                {{ \${$resourceSingular}->created_at ?? 'N/A' }}
                            </div>
                        </div>
                        <hr>
                        <div class=\"row\">
                            <div class=\"col-sm-3\">
                                <strong>Updated:</strong>
                            </div>
                            <div class=\"col-sm-9\">
                                {{ \${$resourceSingular}->updated_at ?? 'N/A' }}
                            </div>
                        </div>
                    </div>
                    <div class=\"card-footer\">
                        <a href=\"@url('/{$resourceSingular}')\" class=\"btn btn-secondary\">
                            <i class=\"bi bi-arrow-left me-1\"></i>
                            Back to List
                        </a>
                    </div>
                </div>
            @else
                <div class=\"alert alert-danger\">
                    <h4>Not Found</h4>
                    <p>The requested {$resourceSingular} could not be found.</p>
                    <a href=\"@url('/{$resourceSingular}')\" class=\"btn btn-primary\">
                        <i class=\"bi bi-arrow-left me-1\"></i>
                        Back to List
                    </a>
                </div>
            @endisset
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function confirmDelete() {
    if (confirm('Are you sure you want to delete this {$resourceSingular}?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/{$resourceSingular}/' + {{ \${$resourceSingular}->id ?? 0 }} + '/delete';
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '@csrf_token';
        csrfToken.value = document.querySelector('meta[name=\"csrf-token\"]').getAttribute('content');
        
        form.appendChild(csrfToken);
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endsection
";
    }

    private function getEditViewTemplate(string $title, string $resourceSingular, string $extends): string
    {
        return "@extends('{$extends}')

@section('title')
Edit {$title} - Retrina Framework
@endsection

@section('content')
<div class=\"container my-4\">
    <div class=\"row justify-content-center\">
        <div class=\"col-md-8\">
            @isset(\${$resourceSingular})
                <div class=\"card\">
                    <div class=\"card-header\">
                        <h4 class=\"mb-0\">Edit {$title}</h4>
                    </div>
                    <div class=\"card-body\">
                        <form action=\"@url('/{$resourceSingular}/' . \${$resourceSingular}->id)\" method=\"POST\">
                            @csrf
                            <input type=\"hidden\" name=\"_method\" value=\"PUT\">
                            
                            <div class=\"mb-3\">
                                <label for=\"name\" class=\"form-label\">Name</label>
                                <input type=\"text\" class=\"form-control\" id=\"name\" name=\"name\" 
                                       value=\"{{ old('name', \${$resourceSingular}->name) }}\" required>
                            </div>
                            
                            <div class=\"mb-3\">
                                <label for=\"description\" class=\"form-label\">Description</label>
                                <textarea class=\"form-control\" id=\"description\" name=\"description\" 
                                          rows=\"3\">{{ old('description', \${$resourceSingular}->description) }}</textarea>
                            </div>
                            
                            <div class=\"d-flex justify-content-between\">
                                <a href=\"@url('/{$resourceSingular}/' . \${$resourceSingular}->id)\" class=\"btn btn-secondary\">
                                    <i class=\"bi bi-arrow-left me-1\"></i>
                                    Cancel
                                </a>
                                <button type=\"submit\" class=\"btn btn-primary\">
                                    <i class=\"bi bi-check-circle me-1\"></i>
                                    Update {$title}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @else
                <div class=\"alert alert-danger\">
                    <h4>Not Found</h4>
                    <p>The requested {$resourceSingular} could not be found.</p>
                    <a href=\"@url('/{$resourceSingular}')\" class=\"btn btn-primary\">
                        <i class=\"bi bi-arrow-left me-1\"></i>
                        Back to List
                    </a>
                </div>
            @endisset
        </div>
    </div>
</div>
@endsection
";
    }
} 