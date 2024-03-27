<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SearchCondition;

class SearchConditionDetail extends Model
{
    use HasFactory;
    
    protected $table = 'dmart_search_condition_detail';

    protected $primaryKey = 'search_detail‗id'; // primaryKey指定

    const CREATED_AT = 'record_date'; // 登録日時カラム
    const UPDATED_AT = 'update_date'; // 更新日時カラム

    protected $fillable = [
        'search_id',
        'search_type',
        'code',
        'create_user',
    ];
    
    public function searchCondition() {
        return $this->belongsTo(SearchCondition::class, 'search_id', 'search_id');
    }

    /**
     * searchIdで削除
     */
    public static function deleteSearchCondtionDetail($searchId) {
        SearchConditionDetail::where('search_id', $searchId)->delete();
    }
}