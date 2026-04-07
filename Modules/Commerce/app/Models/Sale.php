<?php



namespace Modules\Commerce\Models;



use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

// use Modules\Commerce\Database\Factories\SaleFactory;

use Illuminate\Database\Eloquent\Relations\HasMany;

use Illuminate\Database\Eloquent\Relations\HasOne;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

use Modules\Members\Models\Member;



class Sale extends Model

{

    protected $table = 'sales';

    protected $primaryKey = 'id';



    protected $fillable = [
        'running_no',
        'user_id',
        'member_id',

        'brand',
        'model',
        'serial_number',

        'note',
        'note_dok',

        'transfer',
        'cash',

        'type_price',
        'type_category',
        'status',

        'locker_pass',
        'drawn_lock',

        'product_images',
        'product_images_behind',

        'other',
        'other_brand',
        'other_type',

        'dok',
        'qr_code',
        'appointment_date',
    ];
    
    public function user_r()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function member_r(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'member_id', 'member_id');
    }


    protected $casts = [
        'appointment_date' => 'date',
    ];
}
