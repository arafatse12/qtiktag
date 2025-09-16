<?php
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\GtinMappingController;
use App\Http\Controllers\ContentSectionController;




Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::get('/admin-dashboard', function () {
        return response()->json(['message' => 'Welcome Admin']);
    });
});

Route::middleware(['auth:sanctum', 'role:user'])->group(function () {
    Route::get('/user-dashboard', function () {
        return response()->json(['message' => 'Welcome User']);
    });
});

Route::get('/sections', [ContentSectionController::class, 'index']);
Route::get('/sections/{slug}', [ContentSectionController::class, 'show']);
Route::post('/sections', [ContentSectionController::class, 'store']); 


Route::get('/gtins/{gtin}', [GtinMappingController::class, 'show']);
