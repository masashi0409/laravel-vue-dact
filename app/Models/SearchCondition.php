<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SearchConditionDetail;

class SearchCondition extends Model
{
    use HasFactory;
    
    protected $table = 'dmart_search_condition';

    protected $primaryKey = 'search_id'; // primaryKey指定

    const CREATED_AT = 'record_date'; // 登録日時カラム
    const UPDATED_AT = 'update_date'; // 更新日時カラム

    protected $fillable = [
        'enable_flg',
        'create_user',
    ];

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
                ->where('dmart_search_condition_detail.search_type', $searchType)
                ->orderBy('dmart_search_condition_detail.code', 'asc')
                ;
            }])
            ->where('create_user', $userId)
            ->get();
    }

    // ユーザのsearchCondition取得
    public static function getSearchIdByUserId($userId) {
        return SearchCondition::select('search_id')->where('create_user', $userId)->first();
    }

    /**
     * searchIdで削除
     */
    public static function deleteSearchCondition($searchId) {
        SearchCondition::where('search_id', $searchId)->delete();
    }
}