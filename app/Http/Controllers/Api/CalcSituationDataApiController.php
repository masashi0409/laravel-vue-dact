<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use App\Models\Master\Scenario;
use App\Models\CalcAchieve;
use App\Models\CalcStatus;
use App\Models\CalcSummary;

class CalcSituationDataApiController extends Controller
{
    public function index (Request $request)
    {
        // Log::debug('calc situation data api');
        // Log::debug($request->toDate);
        $scenarios = $request->scenarios;
        
        $fromDate = $request->fromDate;
        $toDate = $request->toDate;
        // $toDate = '2022-08-23';

        $dt = Carbon::parse($toDate);
        $keyDate = $dt->toDateString();
        $prevDate = $request->prevDate;
        $prevMonthDate = $request->prevMonthDate;
        $prevYearDate = $request->prevYearDate;

        // 算定実績を取得するサブクエリ
        $calcAchieve = CalcAchieve::
            selectRaw('
                scenariocontrol_sysid as scenario_id
                , count(*) as calc_archive_count
            ')
            ->betWeenDate($fromDate, $toDate)
            ->scenario($scenarios)
            ->groupBy('scenariocontrol_sysid');
        // Log::debug($calcAchieve->get());

        // 前日の算定実績を取得するサブクエリ
        // TODO 月初なら0にする
        $prevCalcAchieve = CalcAchieve::
            selectRaw('
                scenariocontrol_sysid as scenario_id
                , count(*) as calc_archive_count
            ')
            ->betWeenDate($fromDate, $prevDate)
            ->scenario($scenarios)
            ->groupBy('scenariocontrol_sysid');

        // calcSummaryから算定件数、未算定件数、算定率のサブクエリ
        // 当日
        $calcSummaryCountRatio = $this->getCalcSummaryCountRatio($keyDate, $scenarios);
        // 前日
        $prevCalcSummaryCountRatio = $this->getCalcSummaryCountRatio($prevDate, $scenarios);
        // 先月
        $prevMonthCalcSummaryCountRatio = $this->getCalcSummaryCountRatio($prevMonthDate, $scenarios);
        // 前年
        $prevYearCalcSummaryCountRatio = $this->getCalcSummaryCountRatio($prevYearDate, $scenarios);
        // Log::debug($calcSummaryCountRatio->get());

        // まとめて取得
        $result = Scenario::
            selectRaw('
                scenario_control_sysid,
                display_name,
                calc_archieve.calc_archive_count,
                calc_archieve.calc_archive_count - prev_calc_archieve.calc_archive_count as diffPrevArchiveCount, 
                calc_summary_count_ratio.calc_count,
                calc_summary_count_ratio.calc_count - prev_calc_summary_count_ratio.calc_count as diffPrevCalcCount,
                calc_summary_count_ratio.uncalc_count,
                calc_summary_count_ratio.uncalc_count - prev_calc_summary_count_ratio.uncalc_count as diffPrevUncalcCount,
                calc_summary_count_ratio.calc_ratio,
                prev_month_calc_summary_count_ratio.calc_ratio as prev_month_calc_ratio,
                ROUND(calc_summary_count_ratio.calc_ratio - prev_month_calc_summary_count_ratio.calc_ratio, 1) as diffPrevMonthCalcRatio,
                prev_year_calc_summary_count_ratio.calc_ratio as prev_year_calc_ratio,
                ROUND(calc_summary_count_ratio.calc_ratio - prev_year_calc_summary_count_ratio.calc_ratio, 1) as diffPrevYearCalcRatio
            ')
            ->leftJoinSub($calcAchieve, 'calc_archieve', function ($join) {
                $join->on('dmart_m_scenario_control.scenario_control_sysid', '=', 'calc_archieve.scenario_id');
            })
            ->leftJoinSub($prevCalcAchieve, 'prev_calc_archieve', function ($join) { // 前日算定実績
                $join->on('dmart_m_scenario_control.scenario_control_sysid', '=', 'prev_calc_archieve.scenario_id');
            })
            ->leftJoinSub($calcSummaryCountRatio, 'calc_summary_count_ratio', function ($join) {
                $join->on('dmart_m_scenario_control.scenario_control_sysid', '=', 'calc_summary_count_ratio.scenario_id');
            }) // 当日算定実績
            ->leftJoinSub($prevCalcSummaryCountRatio, 'prev_calc_summary_count_ratio', function ($join) {
                $join->on('dmart_m_scenario_control.scenario_control_sysid', '=', 'prev_calc_summary_count_ratio.scenario_id');
            }) // 前日算定実績
            ->leftJoinSub($prevMonthCalcSummaryCountRatio, 'prev_month_calc_summary_count_ratio', function ($join) {
                $join->on('dmart_m_scenario_control.scenario_control_sysid', '=', 'prev_month_calc_summary_count_ratio.scenario_id');
            }) // 前月算定実績
            ->leftJoinSub($prevYearCalcSummaryCountRatio, 'prev_year_calc_summary_count_ratio', function ($join) {
                $join->on('dmart_m_scenario_control.scenario_control_sysid', '=', 'prev_year_calc_summary_count_ratio.scenario_id');
            }) // 前年算定実績
            ->whereIn('scenario_control_sysid', $scenarios)
            ->orderBy('scenario_control_sysid', 'asc')
            ->get();
        
        return response()->json([
            'data' => $result
        ], Response::HTTP_OK);
    }

    /**
     * calcSummaryから算定件数、未算定件数、算定率取得
     * 
     * @param string $keyDate
     * @param array $scenario
     */
    private function getCalcSummaryCountRatio($keyDate, $scenarios) {
        $calcSummary = CalcSummary::
            selectRaw('
                scenariocontrol_sysid as scenario_id
                , sum(achievements_count) as calc_count
                , sum(possible_count) as possible_count
                , sum(uncalculated_count) as uncalc_count
            ')
            ->keyDate($keyDate)
            ->scenario($scenarios)
            ->groupBy('scenario_id');
        
        // 算定率も取得
        $calcSummaryCountRatio = DB::query()
            ->selectRaw('
                scenario_id
                , calc_count
                , uncalc_count
                , CONCAT(ROUND(calc_count / possible_count * 100, 1), " %") as calc_ratio
            ')
            ->fromSub($calcSummary, 'calc_summary');

        return $calcSummaryCountRatio;
    }

    /**
     * calcStatusから算定件数、未算定件数、算定率取得
     * 算定件数、未算定件数、算定率はcalcSummaryを使用する方針なので未使用
     */
    private function getStatusCalcCountRatio($fromDate, $toDate, $scenarios) {
        // 算定状況の算定件数、未算定件数
        $calcStatusCount = CalcStatus::
            selectRaw('
                scenariocontrol_sysid as scenario_id
                , count(*) as enable_calc_count
                , count(achievements_flg = 1 or null) as calc_count
                , count(achievements_flg = 0 or null) as uncalc_count
            ')
        ->betWeenDate($fromDate, $toDate)
        ->scenario($scenarios)
        ->groupBy('scenario_id');
            
        // 上から算定率も取得
        $calcStatusCountRatio = DB::query()
            ->selectRaw('
                scenario_id
                , calc_count
                , uncalc_count
                , CONCAT(ROUND(calc_count / enable_calc_count * 100, 1), " %") as calc_ratio
            ')
        ->fromSub($calcStatusCount, 'calc_status_count');
    }
}

