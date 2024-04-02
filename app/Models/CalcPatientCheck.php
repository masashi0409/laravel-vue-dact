<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalcPatientCheck extends Model
{
    use HasFactory;

    protected $table = 'dmart_calc_patient_check';

    const CREATED_AT = 'record_date'; // 登録日時カラム
    const UPDATED_AT = 'update_date'; // 更新日時カラム

    protected $fillable = [
        'personal_id',
        'scenariocontrol_sysid',
        'target_date'
    ];
}