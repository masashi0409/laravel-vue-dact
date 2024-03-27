<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

use App\Models\SearchCondition;
use App\Models\SearchConditionDetail;

class SearchConditionApiController extends Controller
{

    /**
     * ユーザ設定保存ボタン押されたら、既存の検索条件を削除して画面で選択されたものを追加
     */
    public function updateSearchCondition (Request $request)
    {
        // Log::debug('update search condition');
        // Log::debug($request->searchConditions);

        $requestedSearchCondtions = $request->searchConditions;

        // user_idからsearch_id取得してsearchConditionとdetail削除
        // TODO ユーザID取得
        $userId = '1';
        $searchCondition = SearchCondition::getSearchIdByUserId($userId);
        $searchId = $searchCondition->search_id;

        // 既存の検索条件削除
        DB::beginTransaction();
        try {
            // $searchCondition->update_date = now();
            $searchCondition->touch(); // serachCondtionは更新日だけ更新する

            SearchConditionDetail::deleteSearchCondtionDetail($searchId); // 既存detail削除

            // detail登録
            foreach($requestedSearchCondtions as $s) {
                SearchConditionDetail::create([
                    'search_id' => $searchId,
                    'search_type' => $s['type'],
                    'code' => $s['id'],
                    'create_user' => $userId,
                ]);
            }

            DB::commit();

            return response()->json(
                [
                    'message' => '検索条件を保存しました。'
                ],
                Response::HTTP_OK // 200
            );

        } catch (\Exception $e) {
            DB::rollback();
            Log::debug($e);
        }
 
        // requestの検索条件をinsert
    }
}