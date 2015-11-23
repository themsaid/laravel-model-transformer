<?php

namespace Themsaid\Transformer\Tests\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $table = 'tags';
    protected $guarded = ['id'];
    public $timestamps = false;
    protected $casts = [
        'id' => 'integer',
    ];
}