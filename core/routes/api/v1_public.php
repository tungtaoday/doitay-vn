<?php

use App\Http\Controllers\API\V1\Public\CompanyController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API v1 — Public namespace
|--------------------------------------------------------------------------
|
| Loaded from routes/api.php under prefix /api/v1/public, no auth.
| Used by the new Next.js frontend for browsable, indexable content.
*/

Route::get('companies',         [CompanyController::class, 'index'])->name('companies.index');
Route::get('companies/{id}',    [CompanyController::class, 'show'])->whereNumber('id')->name('companies.show');
