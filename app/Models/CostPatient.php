<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 患者別診療金額
 */
class CostPatient extends Model
{
    use HasFactory;

    protected $table = 'dmart_daily_cost_patient';

    public function scopePerson($query, $personalId)
    {
        return $query->where('personal_id', $personalId);
    }

    // 
    public function scopeBetweenDate($query, $from = null, $to = null)
    {
        return $query->whereBetween('key_date', [$from, $to]);
    }
    
    // 入外タイプ
    public function scopeIoType($query, $ioType)
    {
        return $query->where('io_type', $ioType);
    }
}