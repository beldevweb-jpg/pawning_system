<?php

namespace Modules\Commerce\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DailyReport extends Model
{
    use HasFactory;

    protected $table = 'daily_report';

    /**
     * ปิดการใช้งาน timestamps เพราะใน Database ไม่มีคอลัมน์ created_at/updated_at
     */
    // public $timestamps = false;

    /**
     * ใช้ $guarded = [] เพื่ออนุญาตให้บันทึกข้อมูลได้ทุกฟิลด์ (Mass Assignment)
     */
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    // หากต้องการใช้ fillable แทนการใช้ guarded (เลือกอย่างใดอย่างหนึ่ง)
    // protected $fillable = ['report_date', 'total_sales', 'total_expenses', 'net_amount', 'user_id'];
}
