<?php

namespace Themsaid\Transformers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class AbstractTransformer
{
    protected $options;

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

        return $static->transformModel($modelOrCollection);
    }

    /**
     * @param $item
     * @param $tableName
     * @return bool
     */
    protected function isLoadedFromPivotTable(Model $item, $tableName)
    {
        return $item->pivot && $item->pivot->getTable() == $tableName;
    }

    /**
     * @param Model $item
     * @param $relationshipName
     * @return bool
     */
    protected function isRelationshipLoaded(Model $item, $relationshipName)
    {
        return $item->relationLoaded($relationshipName);
    }

    /**
     * @return mixed
     */
    protected function transformModel(Model $modelOrCollection)
    {
    }
}