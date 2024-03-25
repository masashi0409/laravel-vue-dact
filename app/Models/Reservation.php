<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Sdm\Diagnosis;
use App\Models\CalcPatient;

class Reservation extends Model
{
    use HasFactory;
    
    protected $table = 'dmart_reservation_list';
    
    public function diagnosis() {
        return $this->hasMany(Diagnosis::class, 'personal_id', 'personal_id');
    }
    
    public function calcPatient() {
        return $this->hasMany(CalcPatient::class, 'personal_id', 'personal_id');
    }
    
    public function scopeKeyDate($query, $keyDate)
    {
        return $query->where('dmart_reservation_list.key_date', $keyDate);
    }
    
    public function scopeDoctors($query, $doctors = [])
    {
        if (!empty($doctors)) {
            return $query->whereIn('dmart_reservation_list.reserved_doctor_id', $doctors);
        } else {
            return $query;
        }
    }
}
