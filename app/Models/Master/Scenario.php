<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Scenario extends Model
{
    use HasFactory;
    
    protected $table = 'dmart_m_scenario_control';

    public static function getScenarios() {
        return Scenario::select('scenario_control_sysid', 'display_name')
        ->orderBy('scenario_control_sysid', 'asc')
        ->get();
    }
}
