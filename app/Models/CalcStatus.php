<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 算定状況モデル
 */
class CalcStatus extends Model
{
    use HasFactory;
    
    // 算定状況テーブル
    protected $table = 'dmart_daily_calc_status';


    public function scopeBetweenDate($query, $from = null, $to = null)
    {
         return $query->whereBetween('key_date', [$from, $to]);
    }
    
    public function scopeScenario($query, $scenarios = [])
    {
        if (!empty($scenarios)) {
            return $query->whereIn('scenariocontrol_sysid', $scenarios);
        } else {
            return $query;
        }
    }
}
    