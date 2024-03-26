<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;

use App\Models\Master\Scenario;
use App\Models\SearchCondition;


class UserSettingController extends Controller
{
    public function index()
    {
        // TODO ユーザID取得
        $userId = '1';

        // シナリオマスタ取得
        $masterScenarios = Scenario::getScenarios();
        // Log::debug($scenarios);

        // シナリオのsearchCondition取得
        $searchConditionScenario = [];
        $resultSearchConditionScenario = SearchCondition::getSearchConditionByType($userId, 4);
        // Log::debug($resultSearchConditionScenario[0]->searchConditionDetail); // withで取得したデータを取る
        foreach($resultSearchConditionScenario[0]->searchConditionDetail as $s) {
            $searchConditionScenario[] = [
                'id' => $s->code,
                'name' =>  $s->display_name
            ];
        }
        // Log::debug($searchConditionScenario);

        // unSearchCondition（選択画面の左側のリスト）も作成しておく
        $unSearchConditionScenario = [];
        foreach($masterScenarios as $masterScenario) {
            $keyIndex = array_search($masterScenario->scenario_control_sysid, array_column($searchConditionScenario, 'id'));
            if ($keyIndex === false) {
                // searchConditionにないからunSearchConditionに格納する
                $unSearchConditionScenario[] = [
                    'id' => $masterScenario->scenario_control_sysid,
                    'name' =>  $masterScenario->display_name
                ];
            }
        }

        return Inertia::render('UserSetting', [
            'searchConditionScenario' => $searchConditionScenario,
            'unSearchConditionScenario' => $unSearchConditionScenario
        ]);
    }
}