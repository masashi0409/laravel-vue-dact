<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

use App\Models\Master\Scenario;
use App\Models\SearchCondition;


class UserSettingController extends Controller
{
    public function index()
    {
        // TODO ユーザID取得
        $userId = '1';

        /**
         * 各マスタ取得
         */
        // シナリオマスタ取得
        $masterScenarios = Scenario::getScenarios();
        // Log::debug($scenarios);

        /**
         * searchCondtion
         */
        // userのsearchCondtionが無かったら作成する
        $searchCondition = SearchCondition::where('create_user', $userId)->get();
        Log::debug($searchCondition);

        if (count($searchCondition) === 0) {
            DB::beginTransaction();
            try{
                $searchCondtion = SearchCondition::create([
                    'enable_flg' => true,
                    'create_user' => $userId,
                ]);
                DB::commit();
                Log::debug($userId . 'searchConditionを作成しました。');
                Log::debug($searchCondtion);
            } catch (\Exception $e) {
                DB::rollback();
                Log::debug($e);
            }
        }

        // シナリオのsearchCondition取得
        $searchConditionScenario = [];
        $resultSearchConditionScenario = SearchCondition::getSearchConditionByType($userId, 4);
        // Log::debug($resultSearchConditionScenario[0]->searchConditionDetail); // withで取得したデータを取る
        foreach($resultSearchConditionScenario[0]->searchConditionDetail as $s) {
            $searchConditionScenario[] = [
                'id' => $s->code,
                'name' =>  $s->display_name,
                'type' => 4
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
                    'name' =>  $masterScenario->display_name,
                    'type' => 4
                ];
            }
        }

        return Inertia::render('UserSetting', [
            'searchConditionScenario' => $searchConditionScenario,
            'unSearchConditionScenario' => $unSearchConditionScenario
        ]);
    }
}