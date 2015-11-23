<?php

namespace Themsaid\Transformer\Tests\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';
    protected $guarded = ['id'];
    public $timestamps = false;
    protected $casts = [
        'id' => 'integer',
    ];
}