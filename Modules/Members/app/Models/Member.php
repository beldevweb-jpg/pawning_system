<?php

namespace Modules\Members\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Modules\Commerce\Models\Sale;

// use Modules\Members\Database\Factories\MemberFactory;

class Member extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = 'members';
    protected $primaryKey = 'member_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'name',
        'tax_number',
        'address',
        'phone',
        'email',
        'date_of_birth',
        'idcard_image', // ⭐ เพิ่มด้วย (แนะนำ)
    ];

    protected $casts = [
        'idcard_image' => 'array',
    ];

    public function sales_r(): HasMany
    {
        return $this->hasMany(Sale::class, 'member_id', 'member_id');
    }

    // คีย์หลังคือ localkey ของ table หน้านี้ คีย์ตัวแรกคือ localkey ที่จะเชื่อกับตัวหลัง localkey ของ table หน้านี้


    // protected static function newFactory(): MemberFactory
    // {
    //     // return MemberFactory::new();
    // }
}
