<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InstallationController;
use App\Http\Controllers\ComponentTypeController;
use App\Http\Controllers\ComponentController;
use App\Http\Controllers\InspectionController;
use App\Http\Controllers\MeasurementController;
use App\Http\Controllers\MaintenanceScheduleController;
use App\Http\Controllers\MaintenanceRecordController;
use App\Http\Controllers\CostRecordController;
use App\Http\Controllers\ReplacementForecastController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;


/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('dashboard');
});


/*
|--------------------------------------------------------------------------
| Dashboard
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');


/*
|--------------------------------------------------------------------------
| Authenticated Application Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Installation Management
    |--------------------------------------------------------------------------
    | Admin:
    |   Full access
    |
    | Technician:
    |   View only
    |
    | Supervisor:
    |   View only
    |--------------------------------------------------------------------------
    */

    // View installations
    Route::middleware('role:Admin,Technician,Supervisor')->group(function () {

        Route::get(
            '/installations',
            [InstallationController::class, 'index']
        )->name('installations.index');

        Route::get(
            '/installations/{installation}',
            [InstallationController::class, 'show']
        )->name('installations.show');
    });

    // Admin-only installation management
    Route::middleware('role:Admin')->group(function () {

        Route::get(
            '/installations/create',
            [InstallationController::class, 'create']
        )->name('installations.create');

        Route::post(
            '/installations',
            [InstallationController::class, 'store']
        )->name('installations.store');

        Route::get(
            '/installations/{installation}/edit',
            [InstallationController::class, 'edit']
        )->name('installations.edit');

        Route::put(
            '/installations/{installation}',
            [InstallationController::class, 'update']
        )->name('installations.update');

        Route::patch(
            '/installations/{installation}',
            [InstallationController::class, 'update']
        );

        Route::delete(
            '/installations/{installation}',
            [InstallationController::class, 'destroy']
        )->name('installations.destroy');
    });


    /*
    |--------------------------------------------------------------------------
    | Component Types
    |--------------------------------------------------------------------------
    */

    Route::resource(
        'component-types',
        ComponentTypeController::class
    );


    /*
    |--------------------------------------------------------------------------
    | Components
    |--------------------------------------------------------------------------
    */

    Route::resource(
        'components',
        ComponentController::class
    );


    /*
    |--------------------------------------------------------------------------
    | Inspections
    |--------------------------------------------------------------------------
    */

    Route::resource(
        'inspections',
        InspectionController::class
    );


    /*
    |--------------------------------------------------------------------------
    | Print Inspection Report
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/inspections/{inspection}/print',
        [InspectionController::class, 'print']
    )->name('inspections.print');


    /*
    |--------------------------------------------------------------------------
    | Measurements
    |--------------------------------------------------------------------------
    */

    Route::resource(
        'measurements',
        MeasurementController::class
    );


    /*
    |--------------------------------------------------------------------------
    | Maintenance Schedules
    |--------------------------------------------------------------------------
    */

    Route::resource(
        'maintenance-schedules',
        MaintenanceScheduleController::class
    );


    /*
    |--------------------------------------------------------------------------
    | Maintenance Records
    |--------------------------------------------------------------------------
    */

    Route::resource(
        'maintenance-records',
        MaintenanceRecordController::class
    );


    /*
    |--------------------------------------------------------------------------
    | Degradation Analysis
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/degradation',
        [DashboardController::class, 'degradation']
    )->name('degradation.index');


    /*
    |--------------------------------------------------------------------------
    | Cost Management
    |--------------------------------------------------------------------------
    */

    Route::resource(
        'cost-records',
        CostRecordController::class
    );


    /*
    |--------------------------------------------------------------------------
    | Replacement Forecast
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/replacement-forecasts',
        [ReplacementForecastController::class, 'index']
    )->name('replacement-forecasts.index');

    Route::get(
        '/replacement-forecasts/generate/{component}',
        [ReplacementForecastController::class, 'generate']
    )->name('replacement-forecasts.generate');

    Route::post(
        '/replacement-forecasts/refresh/{component}',
        [ReplacementForecastController::class, 'refresh']
    )->name('replacement-forecasts.refresh');

    Route::get(
        '/replacement-forecasts/{replacementForecast}',
        [ReplacementForecastController::class, 'show']
    )->name('replacement-forecasts.show');

    Route::delete(
        '/replacement-forecasts/{replacementForecast}',
        [ReplacementForecastController::class, 'destroy']
    )->name('replacement-forecasts.destroy');


    /*
    |--------------------------------------------------------------------------
    | Reports
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/reports',
        [ReportController::class, 'index']
    )->name('reports.index');

    Route::get(
        '/reports/print',
        [ReportController::class, 'print']
    )->name('reports.print');


    /*
    |--------------------------------------------------------------------------
    | User Management
    |--------------------------------------------------------------------------
    | ADMIN ONLY
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:Admin')->group(function () {

        Route::resource(
            'users',
            UserController::class
        );

    });


    /*
    |--------------------------------------------------------------------------
    | User Profile
    |--------------------------------------------------------------------------
    */

    Route::view(
        '/profile',
        'profile'
    )->name('profile');

    Route::view(
        '/profile/edit',
        'profile'
    )->name('profile.edit');

});


/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

require __DIR__ . '/auth.php';