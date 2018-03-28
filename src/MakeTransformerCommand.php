<?php

namespace Themsaid\Transformers;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

class MakeTransformerCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:transformer {name} {--model=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new transformer class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Transformer';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        if ($this->option('model')) {
            return __DIR__. '/stubs/transformer.model.stub';
        } else {
            return __DIR__. '/stubs/transformer.stub';
        }
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Transformers';
    }

    /**
     * Build the class with the given name.
     *
     * @param  string  $name
     * @return string
     */
    protected function buildClass($name)
    {
        if ($this->option('model')) {

            $stub = parent::buildClass($name);

            return $this->replaceModel($stub, $this->option('model'));
        }

        return parent::buildClass($name);
    }

    /**
     * Replace the model for the given stub.
     *
     * @param  string  $stub
     * @param  string  $model
     * @return string
     */
    protected function replaceModel($stub, $model)
    {
        $model = str_replace('/', '\\', $model);

        $stub = str_replace('DummyClass', $this->argument('name'), $stub);

        $stub = str_replace('NamespaceDummyModel', config('modelTransformers.model_namespace')
          .$this->option('model'), $stub);

        $stub = str_replace('DummyModel', $model, $stub);

        $stub = str_replace('DummyNamespace', config('modelTransformers.model_namespace'), $stub);

        $dummyModel = Str::camel($model) === 'user' ? 'model' : Str::camel($model);

        $stub = str_replace('dummyModel', $dummyModel, $stub);

        return $stub;
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
          ['model', 'm', InputOption::VALUE_OPTIONAL, 'The model that the transformer applies to.'],
        ];
    }
}
