<?php

namespace Modules\Cloud\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Cloud\Database\Factories\DatabaseFactory;

class Database extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];

    protected static function newFactory(): DatabaseFactory
    {
        //return DatabaseFactory::new();
    }
}
