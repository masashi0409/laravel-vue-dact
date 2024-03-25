<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Inpatient;

/**
 * 算定トリガーモデル
 */
class CalcTrigger extends Model
{
    use HasFactory;
    
    // 算定トリガーテーブル
    protected $table = 'dmart_daily_calc_trigger';

    public function inpatients(){
        return $this->belongsTo(Inpatient::class, 'personal_id', 'personal_id');
    }

    public function scopeKeyDate($query, $keyDate)
    {
        return $query->where('dmart_reservation_list.key_date', $keyDate);
    }
}
