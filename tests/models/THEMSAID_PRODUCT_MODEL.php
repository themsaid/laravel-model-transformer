<?php

namespace Themsaid\Transformers\Tests\Models;

use Illuminate\Database\Eloquent\Model;

class THEMSAID_PRODUCT_MODEL extends Model
{
    protected $table = 'products';
    protected $guarded = ['id'];
    public $timestamps = false;
    protected $casts = [
        'id' => 'integer',
    ];

    public function tags()
    {
        return $this->belongsToMany(THEMSAID_TAG_MODEL::class, 'products_tags', 'product_id', 'tag_id')
            ->withPivot('is_active');
    }
}