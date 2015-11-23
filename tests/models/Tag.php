<?php

namespace Themsaid\Transformer\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use Themsaid\Multilingual\Translatable;

class Tag extends Model
{
    protected $table = 'tags';
    protected $guarded = ['id'];
    public $timestamps = false;
    protected $casts = [
        'id' => 'integer',
    ];
}