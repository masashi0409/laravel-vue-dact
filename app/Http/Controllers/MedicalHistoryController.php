<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Support\Facades\Log;
use App\Models\Sdm\Personal;
use App\Models\Sdm\Diagnosis;

/**
 * 診療履歴Controller
 */
class MedicalHistoryController extends Controller
{
    public function show ($personalId)
    {
        // 患者情報personal取得
        $personal = Personal::select(
            'personal_id',
            'full_name'
        )->enable()->where('personal_id', $personalId)->first();

        // 傷病 diagnosis取得
        $diagnosis = Diagnosis::selectRaw("
                personal_id,
                disease_code,
                disease_name,
                suspicion,
                primary_disease,
                DATE_FORMAT(problem_begin, '%Y/%m/%d') as problem_begin,
                DATE_FORMAT(problem_end, '%Y/%m/%d') as problem_end,
                problem_outcome
            ")->enable()->where('personal_id', $personalId)->get();
        
        return Inertia::render('MedicalHistory', [
            'personal' => $personal,
            'diagnosis' => $diagnosis
        ]);
    }
}