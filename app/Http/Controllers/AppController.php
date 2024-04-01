<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Log;
use App\Models\Master\Scenario;
use App\Models\Master\Hospital;
use App\Models\Master\Doctor;
use App\Models\SearchCondition;
use App\Models\ProcessManagement;

class AppController extends Controller
{
    public function index()
    {
        // Log::debug('AppController');
        // TODO ユーザID取得
        $userId = '1';

        // 施設ID設定読み込み　//TODO ログインユーザ情報から取得
        $hospitalId = config('hospital.hospital_id');
        // 施設情報取得
        $hospital = Hospital::where('hpid', $hospitalId)->first();

        // マスタ取得
        $masterScenarios = Scenario::getScenarios();
        $masterDoctors = Doctor::getDoctors();

        /**
         * 初期検索条件 searchCondition
         */
        // 初期検索条件 searchCondition取得
        $searchCondition = SearchCondition::where('create_user', $userId)->get();

        // 医師
        $searchConditionDoctor = [];
        if (count($searchCondition) > 0) {
            $resultSearchConditionDoctor = SearchCondition::getSearchConditionDoctor($userId);
            foreach($resultSearchConditionDoctor[0]->searchConditionDetail as $s) {
                $searchConditionDoctor[] = [
                    'id' => $s->code,
                    'name' => $s->name,
                    'type' => 1
                ];
            }
        }
        // unsearchConditionDoctorも作成
        $unSearchConditionDoctor = [];
        foreach($masterDoctors as $masterDoctor) {
            $keyIndex = array_search($masterDoctor->doctor_id, array_column($searchConditionDoctor, 'id'));
            if ($keyIndex === false) {
                $unSearchConditionDoctor[] = [
                    'id' => $masterDoctor->doctor_id,
                    'name' => $masterDoctor->name,
                    'type' => 1
                ];
            }
        }

        // シナリオ
        $searchConditionScenario = [];
        if (count($searchCondition) > 0) {
            $resultSearchConditionScenario = SearchCondition::getSearchConditionByType($userId, 4);
            foreach($resultSearchConditionScenario[0]->searchConditionDetail as $s) {
                $searchConditionScenario[] = [
                    'id' => $s->code,
                    'name' =>  $s->display_name,
                    'color' => $s->color_code,
                    'type' => 4
                ];
            }
        }
        
        // unSearchCondition（選択画面の左側のリスト）も作成しておく
        $unSearchConditionScenario = [];
        foreach($masterScenarios as $masterScenario) {
            $keyIndex = array_search($masterScenario->scenario_control_sysid, array_column($searchConditionScenario, 'id')); //初期条件になければ
            if ($keyIndex === false) {
                // searchConditionにないからunSearchConditionに格納する
                $unSearchConditionScenario[] = [
                    'id' => $masterScenario->scenario_control_sysid,
                    'name' =>  $masterScenario->display_name,
                    'color' => $masterScenario->color_code,
                    'type' => 4
                ];
            }
        }

        /**
         * 日付・期間系取得
         */
        // 最新更新日取得
        $processManagement = ProcessManagement::getFirstExtractingDate();
        // Log::debug(print_r($extractingDate[0]->extracting_date, true)); // 2022-08-25
        $extractingDate = $processManagement->extracting_date;
        $dt = Carbon::parse($processManagement->extracting_date); // 2022-08-25 00:00:00
        $year = $dt->year; // 2022
        $month = $dt->month; // 8
        $day = $dt->day; // 25 TODO: うるう年は考慮必要
        $lastYear = $year - 1;
        $lastMonth = $month - 1;

        $prevDate = $prevDate = $dt->copy()->subDay()->toDateString(); // 前日 2022-08-24 TODO 前日がない初日は考慮必要
        $prevMonthDate = Carbon::parse($year . '-' .  $lastMonth . '-' . $day)->toDateString();// 先月同日 2022-07-25 //TODO月末の場合あふれ考慮必要
 
        // 今月・過去30日
        $firstDate = $dt->copy()->startOfMonth()->toDateString(); // 今月の始まり
        $lastDate = $dt->copy()->endOfMonth()->toDateString(); // 今月の終わり
        $sub30Date = $dt->copy()->subDay(30)->toDateString(); // 30日前
        $monthDates = CarbonPeriod::create($firstDate, $lastDate)->toArray();
        $thirtyDates = CarbonPeriod::create($sub30Date, $extractingDate)->toArray();
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

        $subYearMonth =  Carbon::parse($lastYear . '-' . $month)->toDateString();// 1年前の同月
        $startSubYearDate = Carbon::parse($lastYear . '-' . $month . '-' . $day)->toDateString(); // 1年前の同日 月次過去1年間の検索に使う // 2021-08-25
        
        // 年度の月リスト
        $yearMonths = CarbonPeriod::create($fiscalYearStartDt, $fiscalYearEndDt)->months()->toArray();
        $yearMonthLabels = [];
        foreach($yearMonths as $month) { array_push($yearMonthLabels, $month->format('Y-m')); }
        
        // 過去1年の月リスト
        $subYearMonths = CarbonPeriod::create($subYearMonth, $extractingDate)->months()->toArray();
        $subYearMonthLabels = [];
        foreach($subYearMonths as $month) { array_push($subYearMonthLabels, $month->format('Y-m')); }
        // Log::debug($subYearMonthLabels);
        
        // Log::debug('app controller end.');
        
        return Inertia::render('App', [
            'borderMoney' => $hospital->border_money, // 逆紹介ボーダー金額
            'masterDoctor' => $masterDoctors,
            'initSearchConditionDoctor' => $searchConditionDoctor, // 初期検索条件医師
            'initUnSearchConditionDoctor' => $unSearchConditionDoctor,
            'masterScenario' => $masterScenarios,
            'searchConditionScenario' => $searchConditionScenario, // 初期検索条件算定シナリオ
            'unSearchConditionScenario' => $unSearchConditionScenario,
            'extractingDate' => $extractingDate, // 最新更新日 2022-08-25
            'prevDate' => $prevDate,
            'prevMonthDate' => $prevMonthDate, // 先月同日 2022-07-25
            'startYearDate' => $fiscalYearStartDt, // 今年の開始日 月次年間の検索に使う 2021-01-01
            'startSubYearDate' => $startSubYearDate, // 1年前の同日 月次過去1年間の検索に使う 2021-08-25
            'monthDateLabels' => $monthDateLabels, // 今月の日付配列
            'thirtyDateLabels' => $thirtyDateLabels, // 過去30日の日付配列
            'yearMonthLabels' => $yearMonthLabels, // 今年の月配列
            'subYearMonthLabels' => $subYearMonthLabels, // 過去1年の月配列
        ]);
    }
}
