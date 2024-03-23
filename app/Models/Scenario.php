<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Scenario extends Model
{
    use HasFactory;
    
    protected $table = 'dmart_m_scenario_control';
    
    public function scopeEnable($query)
    {
        return $query->where('dmart_m_scenario_control.enable_flg', 1);
    }
}
