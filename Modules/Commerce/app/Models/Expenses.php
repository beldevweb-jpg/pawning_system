<?php

namespace Modules\Commerce\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;
// use Modules\Commerce\Database\Factories\ExpensesFactory;

class Expenses extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];
    protected $table = 'expenses';

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    // protected static function newFactory(): ExpensesFactory
    // {
    //     // return ExpensesFactory::new();
    // }
}
