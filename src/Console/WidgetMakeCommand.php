<?php

namespace Arrilot\Widgets\Console;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;
use RuntimeException;
use Symfony\Component\Console\Input\InputOption;

class WidgetMakeCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:widget';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new widget (arrilot/laravel-widgets)';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Widget';

    public function handle()
    {
        parent::handle();

        if (!$this->option('plain')) {
            $this->createView();
        }

        return null;
    }

    /**
     * Build the class with the given name.
     *
     * @param string $name
     *
     * @return string
     */
    protected function buildClass($name)
    {
        $stub = $this->files->get($this->getStub());

        $stub = $this->replaceNamespace($stub, $name)->replaceClass($stub, $name);

        if (!$this->option('plain')) {
            $stub = $this->replaceView($stub);
        }

        return $stub;
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        $stubName = $this->option('plain') ? 'widget_plain' : 'widget';
        $stubPath = $this->laravel->make('config')->get('laravel-widgets.'.$stubName.'_stub');

        // for BC
        if (is_null($stubPath)) {
            return __DIR__.'/stubs/'.$stubName.'.stub';
        }

        return $this->laravel->basePath().'/'.$stubPath;
    }

    /**
     * Replace the namespace for the given stub.
     *
     * @param string $stub
     * @param string $name
     *
     * @return $this
     */
    protected function replaceNamespace(&$stub, $name)
    {
        $stub = str_replace(
            '{{namespace}}', $this->getNamespace($name), $stub
        );

        $stub = str_replace(
            '{{rootNamespace}}', $this->laravel->getNamespace(), $stub
        );

        return $this;
    }

    /**
     * Replace the class name for the given stub.
     *
     * @param string $stub
     * @param string $name
     *
     * @return string
     */
    protected function replaceClass($stub, $name)
    {
        $class = str_replace($this->getNamespace($name).'\\', '', $name);

        return str_replace('{{class}}', $class, $stub);
    }

    /**
     * Replace the view name for the given stub.
     *
     * @param string $stub
     *
     * @return string
     */
    protected function replaceView($stub)
    {
        $view = 'widgets.'.str_replace('/', '.', $this->makeViewName());

        return str_replace('{{view}}', $view, $stub);
    }

    /**
     * Get the default namespace for the class.
     *
     * @param string $rootNamespace
     *
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        $namespace = $this->laravel->make('config')->get('laravel-widgets.default_namespace', $rootNamespace.'\Widgets');

        if (!Str::startsWith($namespace, $rootNamespace)) {
            throw new RuntimeException("You can not use the generator if the default namespace ($namespace) does not start with application namespace ($rootNamespace)");
        }

        return $namespace;
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['plain', null, InputOption::VALUE_NONE, 'Use plain stub. No view is being created too.'],
        ];
    }

    /**
     * Create a new view file for the widget.
     *
     * return void
     */
    protected function createView()
    {
        if ($this->files->exists($path = $this->getViewPath())) {
            $this->error('View already exists!');

            return;
        }

        $this->makeDirectory($path);

        $this->files->put($path, '');

        $this->info('View created successfully.');
    }

    /**
     * Get the destination view path.
     *
     * @return string
     */
    protected function getViewPath()
    {
        return $this->laravel->basePath('resources/views').'/widgets/'.$this->makeViewName().'.blade.php';
    }

    /**
     * Get the destination view name without extensions.
     *
     * @return string
     */
    protected function makeViewName()
    {
        $name = str_replace($this->laravel->getNamespace(), '', $this->argument('name'));
        $name = str_replace('\\', '/', $name);

        // convert to snake_case part by part to avoid unexpected underscores.
        $nameArray = explode('/', $name);
        array_walk($nameArray, function (&$part) {
            $part = Str::snake($part);
        });

        return implode('/', $nameArray);
    }
}
