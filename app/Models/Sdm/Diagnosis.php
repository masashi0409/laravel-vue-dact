<?php

namespace App\Models\Sdm;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Reservation;

class Diagnosis extends Model
{
    use HasFactory;
    
    protected $table = 'sample_sdm_diagnosis';
    
    public function reservation()
    {
        return $this->belongsTo(Reservation::class, 'personal_id');
    }
    
    public function scopeEnable($query)
    {
        return $query->whereNull('cancel_date')
            ->orWhereNull('stop_date');
    }
}
