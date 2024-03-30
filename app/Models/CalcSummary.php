<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalcSummary extends Model
{
    use HasFactory;
    
    // 算定状況サマリテーブル
    protected $table = 'dmart_daily_calc_summary';

    public function scopeKeyDate($query, $dt)
    {
         return $query->where('key_date', $dt);
    }

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
