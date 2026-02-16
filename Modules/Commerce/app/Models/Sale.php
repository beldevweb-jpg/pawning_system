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
    protected $table = 'sales';
    protected $primaryKey = 'id';

    protected $fillable = [
        'member_id',
        'brand',
        'model',
        'serial_number',
        'note',
        'price',
        'type_price',
        'lock_pass',
        'others',
        'type_category',
        'type_serve',
        'status',
    ];

    public function member_r(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'member_id', 'member_id');
    }
    
    protected $casts = [
        'appointment_date' => 'date',
    ];
}
