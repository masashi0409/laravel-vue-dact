<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 算定実績モデル
 */
class CalcAchieve extends Model
{
    use HasFactory;
    
    // 算定実績テーブル
    protected $table = 'dmart_daily_calc_achieve';
    
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
    
    public static function getCalcArchiveChartData($scenarios, $fromDate, $toDate)
    {
        return CalcAchieve::
            selectRaw('
                scenariocontrol_sysid
                , display_name
                , key_date
                , count(*) as santeicount
            ')
            ->leftJoin('dmart_m_scenario_control', 'scenariocontrol_sysid', 'dmart_m_scenario_control.scenario_control_sysid')
            ->betWeenDate($fromDate, $toDate)
            ->scenario($scenarios)
            ->groupBy('scenariocontrol_sysid', 'key_date')
            ->orderBy('scenariocontrol_sysid', 'asc')
            ->orderBy('key_date', 'asc')
            ->get()
            ;
    }
    
    
    /**
     * 月次の算定実績チャート用データ取得
     */
    public static function getTermMonthCalcChartData($scenarios, $fromDate, $toDate)
    {
        return CalcAchieve::
            selectRaw('
                scenariocontrol_sysid
                , display_name
                , target_year
                , target_month
                , count(*) as achievement_count
            ')
            ->leftJoin('dmart_m_scenario_control', 'scenariocontrol_sysid', 'dmart_m_scenario_control.scenario_control_sysid')
            ->betWeenDate($fromDate, $toDate)
            ->scenario($scenarios)
            ->groupBy('scenariocontrol_sysid', 'target_year', 'target_month')
            ->orderBy('scenariocontrol_sysid', 'asc')
            ->orderBy('target_year', 'asc')
            ->orderBy('target_month', 'asc')
            ->get();
    }
}
