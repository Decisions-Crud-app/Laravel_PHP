<?php

namespace App\Models;

use Illuminate\database\Eloquent\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\support\str;

class Item extends Model
{

    protected $primaryKey = 'uuid';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'name',
        'description',
        'code',
        'status',
    ];

    protected static function booted()
    {
        static::creating(function ($item) {
            $item->uuid = (string) Str::uuid();
        });
    }
}
