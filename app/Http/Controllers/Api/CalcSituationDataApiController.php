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

class CalcSituationDataApiController extends Controller
{
    function index (Request $request)
    {
        // Log::debug('calc situation data api');
        // Log::debug($request->toDate);
        $scenarios = $request->scenarios;
        
        $fromDate = $request->fromDate;
        $toDate = $request->toDate;
        // $toDate = '2022-08-23';

        // 前日
        $dt = Carbon::parse($toDate);
        $prevDate = $dt->subDay()->toDateString();
        // Log::debug($prevDate);
        
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
        // Log::debug($prevCalcAchieve->get());
        
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
        // Log::debug($calcStatusCount->get());
            
        // 上から算定率も取得
        $calcStatusCountRatio = DB::query()
                ->selectRaw('
                    scenario_id
                    , calc_count
                    , uncalc_count
                    , CONCAT(ROUND(calc_count / enable_calc_count * 100, 1), " %") as calc_ratio
                ')
                ->fromSub($calcStatusCount, 'calc_status_count');
        
        // まとめて取得
        $result = Scenario::
        selectRaw('
            scenario_control_sysid,
            display_name,
            calc_archieve.calc_archive_count,
            calc_archieve.calc_archive_count - prev_calc_archieve.calc_archive_count as diffPrevArchiveCount, 
            calc_count,
            uncalc_count,
            calc_ratio
            ')
        ->leftJoinSub($calcAchieve, 'calc_archieve', function ($join) {
            $join->on('dmart_m_scenario_control.scenario_control_sysid', '=', 'calc_archieve.scenario_id');
        })
        ->leftJoinSub($prevCalcAchieve, 'prev_calc_archieve', function ($join) {
            $join->on('dmart_m_scenario_control.scenario_control_sysid', '=', 'prev_calc_archieve.scenario_id');
        })
        ->leftJoinSub($calcStatusCountRatio, 'calc_status_count_ratio', function ($join) {
            $join->on('dmart_m_scenario_control.scenario_control_sysid', '=', 'calc_status_count_ratio.scenario_id');
        })
        ->whereIn('scenario_control_sysid', $scenarios)
        ->orderBy('scenario_control_sysid', 'asc')
        ->get();
        
        // Log::debug($result);
        
        return response()->json([
            'data' => $result
        ], Response::HTTP_OK);
    }
}