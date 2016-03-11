<?php

namespace Themsaid\Transformers\Tests\Transformers;

use Illuminate\Database\Eloquent\Model;
use Themsaid\Transformers\AbstractTransformer;

class THEMSAID_TAG_MODELTransformer extends AbstractTransformer
{

    public function transformModel(Model $item)
    {
        $output = [
            'name' => $item->name,
        ];

        if ($this->isLoadedFromPivotTable($item, 'products_tags')) {
            $output['relationship_data'] = [
                'is_active' => $item->pivot->is_active,
            ];
        }

        return $output;
    }

}