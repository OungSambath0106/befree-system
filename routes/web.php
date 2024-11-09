<?php

use App\Models\BusinessSetting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Backends\BanerController;
use App\Http\Controllers\Backends\BrandController;
use App\Http\Controllers\Backends\RoleController;
use App\Http\Controllers\Backends\UserController;
use App\Http\Controllers\Backends\LanguageController;
use App\Http\Controllers\Backends\DashboardController;
use App\Http\Controllers\Backends\FileManagerController;
use App\Http\Controllers\Backends\BusinessSettingController;
use App\Http\Controllers\Backends\OnboardController;
use App\Http\Controllers\Backends\ProductController;
use App\Http\Controllers\Backends\PromotionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// change language
Route::get('language/{locale}', function ($locale) {
    app()->setLocale($locale);
    session()->put('locale', $locale);
    $language = \App\Models\BusinessSetting::where('type', 'language')->first();
    session()->put('language_settings', $language);
    return redirect()->back();
})->name('change_language');

Route::get('/', function () {
    return redirect('/login');
});

Auth::routes();

// save temp file
Route::post('save_temp_file', [FileManagerController::class, 'saveTempFile'])->name('save_temp_file');

Route::redirect('/admin', '/admin/dashboard');
// Route::redirect('/admin', '/admin/highlight');

Route::post('save_temp_file', [FileManagerController::class, 'saveTempFile'])->name('save_temp_file');
Route::get('remove_temp_file', [FileManagerController::class, 'removeTempFile'])->name('remove_temp_file');

// back-end
Route::middleware(['auth','CheckUserLogin', 'SetSessionData'])->group(function () {

    Route::group(['prefix'=>'admin', 'as'=>'admin.'], function () {

        Route::get('/dashboard',[DashboardController::class, 'index'])->name('dashboard');

        // setting
        Route::group(['prefix' => 'setting', 'as' => 'setting.'], function () {
            Route::get('/', [BusinessSettingController::class, 'index'])->name('index');
            Route::put('/update', [BusinessSettingController::class, 'update'])->name('update');

            // mail
            Route::get('/mail', [BusinessSettingController::class, 'smtp_settings'])->name('smtp_settings.index');
            Route::put('/mail', [BusinessSettingController::class, 'update_environment'])->name('update.environment');

            // language setup
            Route::group(['prefix' => 'language', 'as' => 'language.'], function () {
                Route::get('/', [LanguageController::class, 'index'])->name('index');
                Route::get('/create', [LanguageController::class, 'create'])->name('create');
                Route::post('/', [LanguageController::class, 'store'])->name('store');
                Route::get('/edit', [LanguageController::class, 'edit'])->name('edit');
                Route::put('/update', [LanguageController::class, 'update'])->name('update');
                Route::delete('delete/', [LanguageController::class, 'delete'])->name('delete');

                Route::get('/update-status', [LanguageController::class, 'updateStatus'])->name('update-status');
                Route::get('/update-default-status', [LanguageController::class, 'update_default_status'])->name('update-default-status');
                Route::get('/translate', [LanguageController::class, 'translate'])->name('translate');
                Route::post('translate-submit/{lang}', [LanguageController::class, 'translate_submit'])->name('translate.submit');
            });
        });
        // Route for user
        Route::get('/show_info/{id}', [UserController::class, 'showProfile'])->name('show_info');
        Route::POST('/update_info/{id}', [UserController::class, 'updateProfile'])->name('update_info');
        Route::resource('user', UserController::class);

        //Route for role
        Route::resource('roles', RoleController::class);

        //Route for promotion
        Route::resource('promotion', PromotionController::class);
        Route::post('promotion/update_status', [PromotionController::class, 'updateStatus'])->name('promotion.update_status');

        //Route for onboard
        Route::resource('onboard', OnboardController::class);
        Route::post('onboard/update_status', [OnboardController::class, 'updateStatus'])->name('onboard.update_status');

        // Route Baner-Slider
        Route::get('baner-slider/update_status', [BanerController::class, 'updateStatus'])->name('baner-slider.update_status');
        Route::resource('baner-slider', BanerController::class);

        // Route Brand
        Route::get('brand/update_status', [BrandController::class, 'updateStatus'])->name('brand.update_status');
        Route::resource('brand', BrandController::class);

        // Route Product
        Route::get('product/update_status', [ProductController::class, 'updateStatus'])->name('product.update_status');
        Route::resource('product', ProductController::class);
    });

});

Route::middleware(['auth:web'])->group(function () {
    Route::get('/logout', [LoginController::class,'logout'])->name('logout');
});
