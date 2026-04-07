<?php

namespace Modules\Commerce\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Commerce\Database\Factories\SettingsFactory;

class Settings extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = 'settings';
    protected $fillable = ['company_name', 'phone', 'running_no'];
    public $timestamps = false;

    // protected static function newFactory(): SettingsFactory
    // {
    //     // return SettingsFactory::new();
    // }
}
