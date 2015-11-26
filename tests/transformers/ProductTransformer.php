<?php

namespace Themsaid\Transformers\Tests\Transformers;

use Illuminate\Database\Eloquent\Model;
use Themsaid\Transformers\AbstractTransformer;

class ProductTransformer extends AbstractTransformer
{
    public function transformModel(Model $item)
    {
        $output = [
            'name' => $item->name,
        ];

        if ($this->isRelationshipLoaded($item, 'tags')) {
            $output['tags'] = TagTransformer::transform($item->tags);
        }

        return $output;
    }
}