<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AppController;

// Memanggil Tampilan Frontend
Route::get('/', function () {
    return view('index');
});

// Jalur Komunikasi Data (API Pengganti Google Apps Script)
Route::get('/api/getInitialData', [AppController::class, 'getInitialData']);
Route::post('/api/saveRecord', [AppController::class, 'saveRecord']);
Route::post('/api/saveMultipleRecords', [AppController::class, 'saveMultipleRecords']);
Route::post('/api/saveBatchRecords', [AppController::class, 'saveMultipleRecords']); 
Route::post('/api/saveDbTable', [AppController::class, 'saveDbTable']);
Route::post('/api/deleteRecord', [AppController::class, 'deleteRecord']);