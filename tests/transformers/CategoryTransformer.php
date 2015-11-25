<?php

namespace Themsaid\Transformer\Tests\Transformers;

use Illuminate\Database\Eloquent\Model;
use Themsaid\Transformer\AbstractTransformer;

class CategoryTransformer extends AbstractTransformer
{

    public function transformModel(Model $item)
    {
        $output = [
            'name'       => $item->name,
            'dummy_item' => "I'm dummy",
        ];

        if ($item->relationLoaded('products')) {
            $output['products'] = ProductTransformer::transform($item->products);
        }

        return $output;
    }

}