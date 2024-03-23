<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Scenario;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Log;

class AppController extends Controller
{
    public function index()
    {
        // Log::debug('AppController');
        
        // シナリオマスタ取得
        $scenarios = Scenario::selectRaw('scenario_control_sysid, scenario_control_name')
        ->enable()
        ->orderBy('sort_num', 'asc')
        ->get();
        
        // 最新更新日取得
        $extractingDate = DB::select('
            select
                max(extracting_date) as extracting_date 
            from
            dmart_process_management
        ');
        
        // Log::debug(print_r($extractingDate[0]->extracting_date, true)); // 2022-08-25
        $dt = Carbon::parse($extractingDate[0]->extracting_date); // 2022-08-25 00:00:00
        $year = $dt->year; // 2022
        $month = $dt->month; // 8
        $day = $dt->day; // 25 TODO: うるう年は考慮必要
        $lastYear = $year - 1;
        
        // 今月・過去30日
        $firstDate = $dt->copy()->startOfMonth()->toDateString(); // 今月の始まり
        $lastDate = $dt->copy()->endOfMonth()->toDateString(); // 今月の終わり
        $sub30Date = $dt->copy()->subDay(30)->toDateString(); // 30日前
        $monthDates = CarbonPeriod::create($firstDate, $lastDate)->toArray();
        $thirtyDates = CarbonPeriod::create($sub30Date, $dt->toDateString())->toArray();
        $monthDateLabels = [];
        $thirtyDateLabels = [];
        foreach($monthDates as $date) { array_push($monthDateLabels, $date->format('Y-m-d')); }
        foreach($thirtyDates as $date) { array_push($thirtyDateLabels, $date->format('Y-m-d')); }
        
        
        // 今年度・過去1年
        // 年度を取得
        $fiscalYear = $dt->copy()->subMonthsNoOverflow(3)->format('Y'); // 2022
        $fiscalYearPlus = $fiscalYear+1;
        // Log::debug($fiscalYearPlus);
        // その年度の4月1日、+1年3月31日を取得
        $fiscalYearStartDt = Carbon::parse("$fiscalYear-04-01")->format('Y-m-d'); // 今年度開始日付
        $fiscalYearEndDt = Carbon::parse("$fiscalYearPlus-03-31")->format('Y-m-d'); // 今年度終了日付
        // $firstMonth = $dt->copy()->startOfYear()->toDateString();
        // $lastMonth = $dt->copy()->endOfYear()->toDateString();

        $subYearMonth =  new Carbon($lastYear . '-' . $month);// 1年前の同月
        $startSubYearDate = new Carbon($lastYear . '-' . $month . '-' . $day); // 1年前の同日 月次過去1年間の検索に使う // 2021-08-25
        
        // 年度の月リスト
        $yearMonths = CarbonPeriod::create($fiscalYearStartDt, $fiscalYearEndDt)->months()->toArray();
        $yearMonthLabels = [];
        foreach($yearMonths as $month) { array_push($yearMonthLabels, $month->format('Y-m')); }
        
        // 過去1年の月リスト
        $subYearMonths = CarbonPeriod::create($subYearMonth->toDateString(), $dt->toDateString())->months()->toArray();
        $subYearMonthLabels = [];
        foreach($subYearMonths as $month) { array_push($subYearMonthLabels, $month->format('Y-m')); }
        // Log::debug($subYearMonthLabels);
        
        // Log::debug('app controller end.');
        
        return Inertia::render('App', [
            'scenarios' => $scenarios,
            'extractingDate' => $extractingDate[0]->extracting_date, // 最新更新日 2022-08-25
            'startYearDate' => $fiscalYearStartDt, // 今年の開始日 月次年間の検索に使う 2021-01-01
            'startSubYearDate' => $startSubYearDate->toDateString(), // 1年前の同日 月次過去1年間の検索に使う 2021-08-25
            'monthDateLabels' => $monthDateLabels, // 今月の日付配列
            'thirtyDateLabels' => $thirtyDateLabels, // 過去30日の日付配列
            'yearMonthLabels' => $yearMonthLabels, // 今年の月配列
            'subYearMonthLabels' => $subYearMonthLabels // 過去1年の月配列
        ]);
    }
}
