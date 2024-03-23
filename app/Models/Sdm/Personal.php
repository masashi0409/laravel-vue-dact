<?php

namespace App\Models\Sdm;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Personal extends Model
{
    use HasFactory;
    
    protected $table = 'sample_sdm_personal';
    
    
    public function scopeEnable($query)
    {
        return $query->whereNull('cancel_date')
            ->orWhereNull('stop_date');
    }
}
