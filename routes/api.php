<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Admin\MsTypeWasteController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\auth\ApiAuthController;
use App\Http\Controllers\Client\ListWasteController;
use App\Http\Controllers\Client\SellWasteController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


// route dengan auth user role admin
Route::group(['middleware' => ['auth:sanctum','restrictRole:admin'],'api'], function(){
    Route::apiResource('/jenisSampah', MsTypeWasteController::class);
    Route::post('/updateJenisSampah/{id}', [MsTypeWasteController::class,'updateData']);
});
Route::group(['middleware' => ['auth:sanctum','restrictRole:client'],'api'], function(){
    Route::apiResource('/jualSampah', SellWasteController::class);
    Route::apiResource('/listSampah', ListWasteController::class);

});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout',[ApiAuthController::class,'logout']);
});
Route::post('/register',[ApiAuthController::class,'register']);
Route::post('/login',[ApiAuthController::class,'login']);





