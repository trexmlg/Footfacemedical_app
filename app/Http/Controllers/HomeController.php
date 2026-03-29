<?php

namespace App\Http\Controllers;

use App\Models\Promotion;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $promotions = Promotion::orderBy('sort_order')->orderByDesc('id')->take(3)->get();

        return view('main', [
            'promotions' => $promotions,
        ]);
    }
}
