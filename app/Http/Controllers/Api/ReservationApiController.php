<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\Reservation;
use App\Models\Sdm\Personal;
use App\Models\Master\Section;
use App\Models\Master\Doctor;

class ReservationApiController extends Controller
{
    function index (Request $request)
    {
        
        Log::debug('reservation api index');
        // Log::debug($request->doctors);
        // Log::debug($request->scene);

        // withの確認
        // $testSql = Reservation::
        //     with('diagnosis:personal_id,disease_name')
        //     ->where('personal_id', '03985357')
        //     ->orderBy('reserved_datetime', 'asc');
        // Log::debug($testSql->toSql());
        // $result = $testSql->get();
        // Log::debug(print_r($result, true));
        // Log::debug($result);
        
        // $testSql = Reservation::
        //     with(['calcPatient' =>
        //         function($query) {
        //             $query->join('dmart_m_scenario_control', function($join) {
        //                 $join->on('dmart_daily_calc_patient.scenariocontrol_sysid',
        //                 '=', 'dmart_m_scenario_control.scenario_control_sysid');
        //             })
        //             ->select(
        //                 'dmart_daily_calc_patient.*',
        //                 'dmart_m_scenario_control.scenario_control_sysid',
        //                 'dmart_m_scenario_control.display_name',
        //                 )
        //             ;
        //         }
        //     ])
        //     ->whereIn('personal_id', ['04011889', '03985357', '03963381']);
        // $result = $testSql->get();
        //     // Log::debug(print_r($result, true));
        //     Log::debug($result);   
        
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
            
        // 医師サブクエリ
        $doctor = Doctor::
            selectRaw('
                doctor_id,
                doctor_name
            ');
        
        
        $query = Reservation::
            doctors($doctors)
            ->selectRaw('
                reserved_datetime,
                dmart_reservation_list.personal_id,
                full_name,
                age,
                section_code,
                section_name,
                reserved_type,
                doctor_id,
                doctor_name
            ')
            ->with('diagnosis:personal_id,disease_name,primary_disease') // Reservation(予約患者)とdiagnosis（病気）は1対多 withで取得する
            ->with(['calcPatient' => // Reservation(予約患者)とCalcPatient（患者別算定状況）は1対多リレーション withで取得する
                function($query) use($extractingDate) {
                    $query->join('dmart_m_scenario_control', function($join) { // 患者別算定状況のシナリオ名を取得したいのでscenario_controlとjoin
                        $join->on('dmart_daily_calc_patient.scenariocontrol_sysid',
                        '=', 'dmart_m_scenario_control.scenario_control_sysid');
                    })
                    ->where('dmart_daily_calc_patient.key_date', $extractingDate)
                    ->select(
                        'dmart_daily_calc_patient.*',
                        'dmart_m_scenario_control.scenario_control_sysid',
                        'dmart_m_scenario_control.display_name',
                        )
                    ;
                }
            ])
            ->leftJoinSub($personal, 'personal', function ($join) {
                $join->on('dmart_reservation_list.personal_id', '=' , 'personal.personal_id');
            })
            ->leftJoinSub($section, 'section', function ($join) {
                $join->on('dmart_reservation_list.reserved_section_code', '=', DB::raw('section.section_code COLLATE utf8mb4_general_ci'));
            })
            ->leftJoinSub($doctor, 'doctor', function ($join) {
                $join->on('dmart_reservation_list.reserved_doctor_id', '=', DB::raw('doctor.doctor_id COLLATE utf8mb4_general_ci'));
            })
            ->orderBy('reserved_datetime', 'asc');
        
        $data = [];
        if ($scene === 'reservation') {
            $data = $query->get();
        }
        
        if ($scene === 'top') {
            $data = $query->limit(5)->get();
        }
        
        // $data = Reservation::
        //     selectRaw('personal_id, reserved_datetime, reserved_type')
        //     ->orderBy('reserved_datetime', 'asc')
        //     ->limit(5)->get();
        
        // Log::debug('reserved_list');
        // Log::debug($data);
        
        return response()->json([
            'data' => $data,
        ], Response::HTTP_OK);
    }
}
