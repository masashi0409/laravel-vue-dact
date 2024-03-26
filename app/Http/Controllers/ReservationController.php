<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Master\Scenario;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Log;
use App\Models\Master\Hospital;

class ReservationController extends Controller
{
    public function index()
    {
        // Log::debug('AppController');

        // 施設ID設定読み込み
        $hospitalId = config('hospital.hospital_id');
        // 施設情報取得
        $hospital = Hospital::where('hpid', $hospitalId)->first();
        
        // シナリオマスタ取得
        $scenarios = Scenario::getScenarios();
        
        // 最新更新日取得
        $extractingDate = DB::select('
            select
                max(extracting_date) as extracting_date 
            from
            dmart_process_management
        ');
        
        return Inertia::render('Reservation', [
            'borderMoney' => $hospital->border_money, // 逆紹介ボーダー金額
            'scenarios' => $scenarios,
            'extractingDate' => $extractingDate[0]->extracting_date, // 最新更新日 2022-08-25
        ]);
    }
}
