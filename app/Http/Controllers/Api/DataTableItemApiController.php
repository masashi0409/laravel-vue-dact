<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DataTableItem;
use Illuminate\Support\Facades\Log;

class DataTableItemApiController extends Controller
{
    public function index(Request $request)
    {

        usleep(500000);
        
        $status = 200;
        $message = null;
        Log::debug($request);
        if ($request->has('sortBy')) { // scopeで対応する？
            $data = DataTableItem::orderBy($request->sortBy[0]['key'], $request->sortBy[0]['order'])
            ->paginate($request->itemsPerPage, ['*'], 'page', $request->page);
            return response()->json(['result' => $data, 'status' => $status, 'message' => $message]);
        } else {
            $data = DataTableItem::orderBy('id', 'asc')->paginate($request->itemsPerPage, ['*'], 'page', $request->page);
            return response()->json(['result' => $data, 'status' => $status, 'message' => $message]);
        }
    }
}
