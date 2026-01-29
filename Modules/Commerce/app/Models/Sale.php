<?php

namespace Modules\Commerce\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Commerce\Database\Factories\SaleFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Members\Models\Member;

class Sale extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = 'sales';
    protected $primaryKey = 'id';
    public $incrementing = true;

    protected $fillable = [
        'member_id',
        'brand',
        'model',
        'serial_number',
        'note',
        'price',
        'others',
        'type_category',
        'subcategories',
        'type_serve',
        'status',
    ];

    public function member_r(): HasOne
    {
        return $this->HasOne(Member::class, 'member_id', 'member_id');
    }


    // protected static function newFactory(): SaleFactory
    // {
    //     // return SaleFactory::new();
    // }
}
