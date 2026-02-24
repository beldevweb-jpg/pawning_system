<?php

namespace Modules\Commerce\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Commerce\Database\Factories\InterestFactory;

class interest extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];
    protected $table = 'interest';
    

    // protected static function newFactory(): InterestFactory
    // {
    //     // return InterestFactory::new();
    // }
}
