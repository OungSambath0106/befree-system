<?php

use App\Http\Controllers\API\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// create api here


// (login)
Route::post('login', [ApiController::class, 'login']);

Route::middleware(['auth:api'])->group(function () {

    // Route::prefix('admin')->group(function () {

        // (get_config)
        Route::get('get_config', [ApiController::class, 'getConfig']);
        //Route for onboard
        Route::get('get_onboard_screen',[ApiController::class,'getOnboardScreen']);
        // (get_promotion)
        Route::get('get_promotion', [ApiController::class, 'getPromotion']);
        // (get_promotion_detail)
        Route::get('get_promotion_detail', [ApiController::class, 'getPromotionDetail']);
        // (get_user)
        Route::get('get_user', [ApiController::class, 'getUser']);
        // (get_baner_slider)
        Route::get('get_baner_slider', [ApiController::class, 'getBanerSlider']);
        
    // });

    // (logout)
    Route::get('logout', [ApiController::class, 'logout']);
});
