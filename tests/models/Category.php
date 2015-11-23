<?php

namespace Themsaid\Transformer\Tests\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';
    protected $guarded = ['id'];
    public $timestamps = false;
    protected $casts = [
        'id' => 'integer',
    ];
}