<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Inpatient extends Model
{
    use HasFactory;
    
    protected $table = 'dmart_inpatient_list';
    
    public function scopeKeyDate($query, $keyDate)
    {
        return $query->where('dmart_inpatient_list.key_date', $keyDate);
    }
    
    public function scopeDoctors($query, $doctors = [])
    {
        if (!empty($doctors)) {
            return $query->whereIn('dmart_inpatient_list.doctor_id', $doctors);
        } else {
            return $query;
        }
    }
}
