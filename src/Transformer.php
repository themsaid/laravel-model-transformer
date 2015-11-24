<?php

namespace Themsaid\Transformer;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

abstract class AbstractTransformer
{
    /**
     * @param Model $item
     * @return mixed
     */
    public abstract function transform(Model $item);

    /**
     * @param Collection $items
     * @return static
     */
    public function transformCollection(Collection $items)
    {
        return $items->map([$this, 'transform']);
    }

    /**
     * Checks if a relation is loaded via eager load, the $relation name
     * is the snake_case form of the relationship method name
     * inside the model
     *
     * @param Model $item
     * @param String $relation
     * @return bool
     */
    public function isRelationLoaded(Model $item, $relation)
    {
        return array_get($item->relationsToArray(), $relation) !== null;
    }

}