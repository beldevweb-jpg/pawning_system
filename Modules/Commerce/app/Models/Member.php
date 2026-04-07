<?php

namespace Modules\Commerce\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Commerce\Database\Factories\MemberFactory;

class Member extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];

    // protected static function newFactory(): MemberFactory
    // {
    //     // return MemberFactory::new();
    // }
}
