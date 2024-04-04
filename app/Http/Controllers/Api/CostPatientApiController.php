<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

use App\Models\CostPatient;

/**
 * 患者別診療金額取得Api
 */
class CostPatientApiController extends Controller
{
    /**
     * タイプが外来の診療金額を取得
     * request
     * param personalId
     * param startKeyDate 期間開始
     * param endKeyDate 期間終了
     */
    public function getOutpatientCost(Request $request)
    {

        // Log::debug($request);

        $outPatientCosts  = CostPatient::
        select(
            'key_date',
            'io_type',
            'total_cost'
            )
        ->person($request->personalId)->ioType('外来')
        ->get();
        // Log::debug($costPatients);

        return response()->json([
            'outPatientCosts' => $outPatientCosts,
        ], Response::HTTP_OK);
    }
}