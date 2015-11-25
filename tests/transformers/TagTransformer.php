<?php

namespace Themsaid\Transformer\Tests\Transformers;

use Illuminate\Database\Eloquent\Model;
use Themsaid\Transformer\AbstractTransformer;

class TagTransformer extends AbstractTransformer
{

    public function transformModel(Model $item)
    {
        return [
            'name' => $item->name,
        ];
    }

}