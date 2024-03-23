<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\Inpatient;
use App\Models\Sdm\Personal;
use App\Models\Master\Section;
use App\Models\Master\Ward;
use App\Models\Master\Doctor;

class InpatientApiController extends Controller
{
    function index (Request $request)
    {
        // Log::debug('inpatient api index');
        
        $extractingDate = $request->extractingDate;
        $doctors = $request->doctors;
        $scene = $request->scene;
        
        // 患者subquery
        $personal = Personal::
            selectRaw('
                personal_id,
                full_name,
                record_age as age
            ')
            ->enable();
        
        // 診療科サブクエリ
        $section = Section::
            selectRaw('
                section_code,
                section_name
            ');
            
        // 病棟サブクエリ
        $ward = Ward::
            selectRaw('
                ward_code,
                ward_type
            ');
            
        // 医師サブクエリ
        $doctor = Doctor::
            selectRaw('
                doctor_id,
                doctor_name
            ');
        
        $query = Inpatient::
            keyDate($extractingDate)
            ->doctors($doctors)
            ->selectRaw("
                admission_date,
                DATEDIFF(dmart_inpatient_list.key_date, dmart_inpatient_list.admission_date) as inpatient_date,
                personal.personal_id,
                full_name,
                age,
                section_code,
                section_name,
                ward.ward_code,
                ward.ward_type,
                doctor.doctor_id,
                doctor_name
            ")
            ->joinSub($personal, 'personal', function ($join) {
                $join->on('dmart_inpatient_list.personal_id', '=' , 'personal.personal_id');
            })
            ->joinSub($section, 'section', function ($join) {
                $join->on('dmart_inpatient_list.hospital_section_code', '=', DB::raw('section.section_code COLLATE utf8mb4_general_ci'));
            })
            ->joinSub($ward, 'ward', function ($join) {
                $join->on('dmart_inpatient_list.ward_code', '=', DB::raw('ward.ward_code COLLATE utf8mb4_general_ci'));
            })
            ->leftJoinSub($doctor, 'doctor', function ($join) {
                $join->on('dmart_inpatient_list.doctor_id', '=', DB::raw('doctor.doctor_id COLLATE utf8mb4_general_ci'));
            })
            ->orderBy('admission_date', 'asc');
        
        $data = [];
        if ($scene === 'inpatient') {
            $data = $query->get();
        }
        
        if ($scene === 'top') {
            $data = $query->limit(5)->get();
        }

        // Log::debug($data);
        
        return response()->json([
            'data' => $data,
        ], Response::HTTP_OK);
    }
}
