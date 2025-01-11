<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FamilyCardController;
use App\Http\Controllers\ProjectMonitoringController;

Route::group(['middleware' => ['web', 'guest']], function () {
    Route::get('/', [ProjectMonitoringController::class, 'index'])->name('home');
    Route::resource('project-monitoring', ProjectMonitoringController::class);
    Route::get('project-monitorings', [ProjectMonitoringController::class, 'datas'])->name('project-monitoring.data');
});

