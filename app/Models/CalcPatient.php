<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Reservation;

/**
 * 患者別算定状況モデル
 */
class CalcPatient extends Model
{
    use HasFactory;
    
    // 患者別算定状況テーブル
    protected $table = 'dmart_daily_calc_patient';
    
    public function reservations(){
        return $this->belongsTo(Reservation::class, 'personal_id', 'personal_id');
    }
    
    public function scopeKeyDate($query, $keyDate)
    {
        return $query->where('dmart_reservation_list.key_date', $keyDate);
    }
}
