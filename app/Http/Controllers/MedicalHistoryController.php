<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Support\Facades\Log;
use App\Models\Sdm\Personal;
use App\Models\Sdm\Diagnosis;

class MedicalHistoryController extends Controller
{
    public function show ($personalId)
    {
        // personal取得
        $personal = Personal::select(
            'personal_id',
            'full_name'
        )->enable()->where('personal_id', $personalId)->first();
        Log::debug($personal);
        // diagnosis
        $diagnosis = Diagnosis::selectRaw('
                personal_id,
                disease_code,
                disease_name,
                suspicion,
                primary_disease,
                problem_begin,
                problem_end,
                problem_outcome
            ')->enable()->where('personal_id', $personalId)->get();

        Log::debug($diagnosis);
        
        return Inertia::render('MedicalHistory', [
            'personal' => $personal,
            'diagnosis' => $diagnosis
        ]);
    }
}