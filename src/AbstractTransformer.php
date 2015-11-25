<?php

namespace Themsaid\Transformer;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class AbstractTransformer
{
    private $options;

    /**
     * Initialize transformer
     *
     * @param $options
     */
    private function __construct($options)
    {
        $this->options = $options;
    }

    /**
     * @param $modelOrCollection
     * @param array $options
     * @return mixed
     */
    static function transform($modelOrCollection, $options = [])
    {
        $static = new static($options);

        if ($modelOrCollection instanceof Collection) {
            return $modelOrCollection->map([$static, 'transformModel'])->toArray();
        }

        return $static->prepare(
            $static->transformModel($modelOrCollection)
        );
    }

    /**
     * @param $output
     * @return \Illuminate\Support\Collection
     */
    private function prepare($output)
    {
        $collection = collect($output);

        if (isset($this->options['except'])) {
            $collection = $collection->except(
                $this->options['except']
            );
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