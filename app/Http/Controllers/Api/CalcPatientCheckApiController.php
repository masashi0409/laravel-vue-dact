<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\ProcessManagement;
use App\Models\CalcPatientCheck;

class CalcPatientCheckApiController extends Controller
{
    /**
     * 外来予約リストと在院患者リストの指導・管理のアイコン
     * 未算定！を算定チェック✓にする
     *   calcPatientCheckに登録またはcheck_flgをオンに更新
     * 算定チェック✓を未算定！にする
     *   calcPatientCcheckのcheck_flgをオフに更新
     * 
     */
    public function calcPatientCheck(Request $request)
    {
        // target_date取得
        // extractingDate
        $processManagement = ProcessManagement::getFirstExtractingDate();
        $extractingDate = $processManagement->extracting_date;

        DB::beginTransaction();
        try {
            CalcPatientCheck::upsert(
                [[
                    'personal_id' => $request->personal_id,
                    'scenariocontrol_sysid' => $request->scenario_id,
                    'target_date' => $extractingDate,
                    'check_flg' => $request->check_flg
                ]],
                ['personal_id', 'scenariocontrol_sysid', 'target_date'], // 既存レコードの特定に利用するカラム、uniqueにする必要がある
                ['check_flg'], // updateが行われる際に更新したいカラム
            );

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            Log::debug($e);
        }
        return response()->json(
            [
                'message' => '算定チェックを更新しました'
            ],
            Response::HTTP_OK // 200
        );
    }
}