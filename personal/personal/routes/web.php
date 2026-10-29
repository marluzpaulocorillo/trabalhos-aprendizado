<?php

use App\Http\Controllers\AssessmentController;
use Illuminate\Support\Facades\Route;

Route::controller(AssessmentController::class)->group(function () {
    Route::get('/', 'index')->name('assessments.index');
    Route::get('/assessments/create', 'create')->name('assessments.create');
    Route::post('/assessments', 'store')->name('assessments.store');
    Route::get('/assessments/history', 'history')->name('history');
    Route::get('/assessments/evolution', 'evolution')->name('assessments.evolution');
    Route::get('/assessments/{assessment}/report', 'report')->name('assessments.report');
    Route::get('/assessments/{assessment}/infographic', 'infographic')->name('assessments.infographic');
    Route::delete('/assessments/{assessment}', 'destroy')->name('assessments.destroy');
});
