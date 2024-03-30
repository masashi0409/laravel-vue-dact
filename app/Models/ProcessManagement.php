<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProcessManagement extends Model
{
    use HasFactory;
    
    protected $table = 'dmart_process_management';

    public static function getFirstExtractingDate() {
        return ProcessManagement::selectRaw('
            max(extracting_date) as extracting_date 
        ')->first();
    }
}