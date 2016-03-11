<?php

namespace Themsaid\Transformers\Tests\Transformers;

use Illuminate\Database\Eloquent\Model;
use Themsaid\Transformers\AbstractTransformer;

class THEMSAID_PRODUCT_MODELTransformer extends AbstractTransformer
{
    public function transformModel(Model $item)
    {
        $output = [
            'name' => $item->name,
        ];

        if ($this->isRelationshipLoaded($item, 'tags')) {
            $output['tags'] = THEMSAID_TAG_MODELTransformer::transform($item->tags);
        }

        return $output;
    }
}