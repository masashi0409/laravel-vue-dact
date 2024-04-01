<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;
    
    protected $table = 'dmart_m_doctor';

    public function scopeIds($query, $ids = []) {
        if (count($ids)) {
            return $query->whereIn('doctor_id', $ids);
        } else {
            return $query;
        }
    }

    public static function getDoctors($ids = []) {
        return Doctor::select('doctor_id', 'doctor_name as name') // ここでdoctor_id as idにすると000がなくなりint扱いになってしまった
        ->ids($ids)
        ->orderBy('doctor_id', 'asc')
        ->get();
    }
}
