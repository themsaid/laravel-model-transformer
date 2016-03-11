<?php

namespace Themsaid\Transformers\Tests\Models;

use Illuminate\Database\Eloquent\Model;

class THEMSAID_TAG_MODEL extends Model
{
    protected $table = 'tags';
    protected $guarded = ['id'];
    public $timestamps = false;
    protected $casts = [
        'id' => 'integer',
    ];
}