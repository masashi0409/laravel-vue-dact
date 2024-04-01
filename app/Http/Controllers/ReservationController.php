<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Master\Scenario;
use Illuminate\Support\Facades\DB;
use App\Models\Master\Hospital;
use App\Models\SearchCondition;
use App\Models\ProcessManagement;

class ReservationController extends Controller
{
    public function index()
    {
        // Log::debug('AppController');
        // TODO ユーザID取得
        $userId = '1';
        // 施設ID設定読み込み
        $hospitalId = config('hospital.hospital_id');
        // 施設情報取得
        $hospital = Hospital::where('hpid', $hospitalId)->first();
        
        // シナリオマスタ取得
        $masterScenarios = Scenario::getScenarios();

        /**
         * 初期検索条件 searchCondition
         */
        // 初期検索条件 searchCondition取得
        $searchCondition = SearchCondition::where('create_user', $userId)->get();

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
            $keyIndex = array_search($masterScenario->scenario_control_sysid, array_column($searchConditionScenario, 'id'));
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
        
        // 最新更新日取得
        $processManagement = ProcessManagement::getFirstExtractingDate();
        $extractingDate = $processManagement->extracting_date;
        
        return Inertia::render('Reservation', [
            'borderMoney' => $hospital->border_money, // 逆紹介ボーダー金額
            'masterScenario' => $masterScenarios,
            'initSearchConditionScenario' => $searchConditionScenario, // 初期検索条件算定シナリオ
            'initUnSearchConditionScenario' => $unSearchConditionScenario,
            'extractingDate' => $extractingDate, // 最新更新日 2022-08-25
        ]);
    }
}

