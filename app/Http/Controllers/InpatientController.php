<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Master\Scenario;
use App\Models\Master\Hospital;
use App\Models\Master\Doctor;
use App\Models\SearchCondition;
use App\Models\ProcessManagement;

class InpatientController extends Controller
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
        // 算定シナリオ
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
        
        return Inertia::render('Inpatient', [
            'masterDoctor' => $masterDoctors,
            'initSearchConditionDoctor' => $searchConditionDoctor, // 初期検索条件医師
            'initUnSearchConditionDoctor' => $unSearchConditionDoctor,
            'masterScenario' => $masterScenarios,
            'initSearchConditionScenario' => $searchConditionScenario, // 初期検索条件算定シナリオ
            'initUnSearchConditionScenario' => $unSearchConditionScenario,
            'extractingDate' => $extractingDate, // 最新更新日 2022-08-25
        ]);
    }
}
