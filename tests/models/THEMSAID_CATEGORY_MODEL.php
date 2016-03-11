<?php

namespace Themsaid\Transformers\Tests\Models;

use Illuminate\Database\Eloquent\Model;

class THEMSAID_CATEGORY_MODEL extends Model
{
    protected $table = 'categories';
    protected $guarded = ['id'];
    public $timestamps = false;
    protected $casts = [
        'id' => 'integer',
    ];

    public function products()
    {
        return $this->hasMany(THEMSAID_PRODUCT_MODEL::class, 'category_id');
    }
}