<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SearchConditionDetail;

class SearchCondition extends Model
{
    use HasFactory;
    
    protected $table = 'dmart_search_condition';

    public function searchConditionDetail() {
        return $this->hasMany(SearchConditionDetail::class, 'search_id', 'search_id');
    }

    // ユーザの検索条件 typeごと を取得
    public static function getSearchConditionByType($userId, $searchType) {
        return SearchCondition::
            select(
                'dmart_search_condition.search_id'
            )
            ->with(['searchConditionDetail' => function ($query) use ($userId, $searchType) {
                $query
                ->select(
                    'dmart_search_condition_detail.search_id',
                    'dmart_search_condition_detail.code',
                    'dmart_m_scenario_control.display_name'
                )
                ->join('dmart_m_scenario_control', function($join) {
                    $join->on('dmart_search_condition_detail.code',
                    '=', 'dmart_m_scenario_control.scenario_control_sysid');
                })
                ->where('dmart_search_condition_detail.create_user', $userId)
                ->where('dmart_search_condition_detail.search_type', $searchType);
            }])
            ->where('create_user', $userId)
            ->get();
    }
}