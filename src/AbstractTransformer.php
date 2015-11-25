<?php

namespace Themsaid\Transformer;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class AbstractTransformer
{
    protected $options;
    private $callback;

    /**
     * Initialize transformer
     *
     * @param $options
     */
    private function __construct($options, $callback)
    {
        $this->options = $options;
        $this->callback = $callback;
    }

    /**
     * @param $modelOrCollection
     * @param array $options
     * @param callable|null $callback
     * @return mixed
     */
    static function transform($modelOrCollection, $options = [], $callback = null)
    {
        $static = new static($options, $callback);

        if ($modelOrCollection instanceof Collection) {
            return $modelOrCollection->map([$static, 'transformModel'])->toArray();
        }

        return $static->present(
            $static->transformModel($modelOrCollection)
        );
    }

    /**
     * @param $output
     * @return \Illuminate\Support\Collection
     */
    private function present($output)
    {
        $collection = collect($output);

        if ($this->callback) {
            $callBack = $this->callback;
            $collection = $callBack($collection);
        }

        return $collection->toArray();
    }

    /**
     * @return mixed
     */
    protected function transformModel(Model $modelOrCollection)
    {
    }

}