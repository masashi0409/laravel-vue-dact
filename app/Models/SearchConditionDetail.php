<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SearchCondition;

class SearchConditionDetail extends Model
{
    use HasFactory;
    
    protected $table = 'dmart_search_condition_detail';

    public function searchCondition() {
        return $this->belongsTo(SearchCondition::class, 'search_id', 'search_id');
    }
}