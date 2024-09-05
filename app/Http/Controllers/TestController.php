<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TestController extends Controller
{
    public function index()
    {
        $response = Http::get('localhost:8081/api/analysis');

        log::debug($response);

        return Inertia::render('Test', []);
    }
}