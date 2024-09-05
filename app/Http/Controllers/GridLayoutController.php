<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\GridLayout;

class GridLayoutController extends Controller
{
    public function index()
    {
        $userId = 'default';
        $gridLayout = GridLayout::
        join('grid_contents', 'grid_layout.grid_id', '=', 'grid_contents.grid_id')
        ->where('user_id', $userId)
        ->get();

        logger()->debug($gridLayout);

        return Inertia::render('GridLayout', [
            'gridLayout' => $gridLayout
        ]);
    }
}