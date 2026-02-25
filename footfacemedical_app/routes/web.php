<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FullCalendarController;

Route::get('fullcalender', [FullCalenderController::class, 'index']);
Route::post('fullcalenderAjax', [FullCalenderController::class, 'ajax']);
Route::get('/calendar', function () {
    return view('fullcalendar');
}); 
Route::get('/', function () {
    return view('main');
});