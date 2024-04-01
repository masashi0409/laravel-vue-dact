<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Scenario extends Model
{
    use HasFactory;
    
    protected $table = 'dmart_m_scenario_control';

    public function scopeIds($query, $ids = []) {
        if (count($ids)) {
            return $query->whereIn('scenario_control_sysid', $ids);
        } else {
            return $query;
        }
    }

    public static function getScenarios($ids = []) {
        return Scenario::select('scenario_control_sysid', 'display_name', 'color_code')
        ->ids($ids)
        ->orderBy('scenario_control_sysid', 'asc')
        ->get();
    }
}
