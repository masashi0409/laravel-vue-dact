<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use App\Models\CalcAchieve;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class CalcChartDataApiController extends Controller
{
    function index (Request $request)
    {
        // Log::debug('calc chart data api index');
        
        $result = CalcAchieve::getCalcArchiveChartData($request->scenarios, $request->fromDate, $request->toDate);
        
        // 日付とdataのindexの関係
        // 開始と終了から期間作成
        $dataKeyDateObjs = CarbonPeriod::create($request->fromDate, $request->toDate)->toArray();
        $dataKeyDate = [];
        foreach($dataKeyDateObjs as $keyDate) {
            // Log::debug($keyDate->toDateString());
            array_push($dataKeyDate, $keyDate->toDateString());
        }
        
        $senarioLabels = $result->unique('scenariocontrol_sysid')->pluck('scenario_control_name', 'scenariocontrol_sysid'); //uniqueで重複削除、pluckで指定カラムだけの配列

        $datasets = [];
        // datasetsにlabelを入れる
        foreach ($senarioLabels as $senarioId =>  $senarioLabel) {
            $datasets[$senarioId] = [
                    'label' => $senarioLabel,
                    'jissekiData' => array_fill(0, count($dataKeyDate), 0),
                    'ruikeiData' => array_fill(0, count($dataKeyDate), 0)
                ];
        }

        // 実績データをdatasetに詰めていく
        $result->each(function ($row) use($dataKeyDate, &$datasets) { // 更新したいので&をつけてuse https://teratail.com/questions/76248
            // Log::debug($row->scenariocontrol_sysid);
            // Log::debug($row->key_date);
            // Log::debug($row->santeicount);
            
            $key = array_search($row->key_date, $dataKeyDate); // key_dateがdataの何番目か
            // Log::debug($key);
            $datasets[$row->scenariocontrol_sysid]['jissekiData'][$key] = $row->santeicount;
            // Log::debug($datasets[$row->scenariocontrol_sysid]['jissekiData'][$key]);
        });
        
        // 累計データ作成
        foreach ($datasets as $scenarioId => $dataset)
        {
            $ruikeiCount = 0;
            foreach($dataset['jissekiData'] as $dateIndex => $jissekiCount)
            {
                $ruikeiCount += $jissekiCount;
                $datasets[$scenarioId]['ruikeiData'][$dateIndex] = $ruikeiCount;
            }
        }
        
        return response()->json([
            'data' => $datasets,
        ], Response::HTTP_OK);
    }
    
    function getTermMonthData(Request $request) {
        Log::debug('calc chart data api getTermMonthData');
        // Log::debug($request);
        $monthLabels = $request->monthLabels;
        // Log::debug($monthLabels);
        // Log::debug($request->toDate);
        $toDate = new Carbon($request->toDate);
        $toMonthKey = array_search($toDate->format('Y-m'), $monthLabels);
        // Log::debug($toMonthKey);
        
        $monthLabels = array_slice($monthLabels, 0, $toMonthKey+1);
        // Log::debug($monthLabels);
        
        $result = CalcAchieve::getTermMonthCalcChartData($request->scenarios, $request->fromDate, $request->toDate);
        
        $scenarioLabels = $result->unique('scenariocontrol_sysid')->pluck('scenario_control_name', 'scenariocontrol_sysid'); //uniqueで重複削除、pluckで指定カラムだけの配列

        $datasets = [];
        //senarioLabelを入れる
        foreach ($scenarioLabels as $senarioId => $senarioLabel) {
            $datasets[$senarioId] = [
                'label' => $senarioLabel,
                'jissekiData' => array_fill(0, count($monthLabels), 0),
                'ruikeiData' => array_fill(0, count($monthLabels), 0)
            ];   
        }
        
        // 実績データをdatasetに詰めていく
        $result->each(function ($row) use ($monthLabels, &$datasets) {
            // Log::debug($row->scenariocontrol_sysid);
            // Log::debug($row->scenario_control_name);
            // Log::debug($row->target_year);
            // Log::debug($row->target_month);
            // Log::debug($row->achievement_count);
            
            $dataYearMonth = $row->target_year . '-' . $row->target_month;
            // Log::debug($datayearMonth);
            $key = array_search($dataYearMonth, $monthLabels); // 取得データrowのdatasetのkey(何番目か)
            // Log::debug($key);
            $datasets[$row->scenariocontrol_sysid]['jissekiData'][$key] = $row->achievement_count;
        });
        
        // 累計データ作成
        foreach ($datasets as $scenarioId => $dataset)
        {
            $ruikeiCount = 0;
            foreach($dataset['jissekiData'] as $dataIndex => $jissekiCount)
            {
                $ruikeiCount += $jissekiCount;
                $datasets[$scenarioId]['ruikeiData'][$dataIndex] = $ruikeiCount;
            }
            
        }
        
        return response()->json([
            'data' => $datasets,
        ], Response::HTTP_OK);
    }
}
