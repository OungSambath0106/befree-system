<?php

use App\Models\Comment;
use App\Models\Category;
use App\Models\ContactUs;
use App\Models\Transaction;
use App\Models\Notification;
use App\helpers\GlobalFunction;
use App\Models\BusinessSetting;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backends\PageController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Backends\SectionTitleController;
use App\Http\Controllers\Backends\BlogController;
use App\Http\Controllers\Backends\MenuController;
use App\Http\Controllers\Backends\RoleController;
use App\Http\Controllers\Backends\RoomController;
use App\Http\Controllers\Backends\UserController;
use App\Http\Controllers\Backends\ReportController;
use App\Http\Controllers\Backends\SliderController;
use App\Http\Controllers\Backends\BlogTagController;
use App\Http\Controllers\Backends\CommentController;
use App\Http\Controllers\Backends\GalleryController;
use App\Http\Controllers\Backends\ServiceController;
use App\Http\Controllers\Backends\CategoryController;
use App\Http\Controllers\Backends\CustomerController;
use App\Http\Controllers\Backends\FacilityController;
use App\Http\Controllers\Backends\LanguageController;
use App\Http\Controllers\Backends\RatePlanController;
use App\Http\Controllers\Backends\ContactUsController;
use App\Http\Controllers\Backends\DashboardController;
use App\Http\Controllers\Backends\FileManagerController;
use App\Http\Controllers\Backends\MenuExploreController;
use App\Http\Controllers\Backends\BlogCategoryController;
use App\Http\Controllers\Backends\ExtraServiceController;
use App\Http\Controllers\Backends\RoomCalendarController;
use App\Http\Controllers\Backends\BusinessSettingController;
use App\Http\Controllers\Backends\HighlightContoller;
use App\Http\Controllers\Backends\HomeStayAmenityController;
use App\Http\Controllers\Backends\RoomAllotmentCalendarController;
use App\Http\Controllers\Backends\StaycationController;
use Illuminate\Support\Facades\Auth;

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

// Route::redirect('/admin', '/admin/dashboard');
Route::redirect('/admin', '/admin/highlight');

Route::post('save_temp_file', [FileManagerController::class, 'saveTempFile'])->name('save_temp_file');
Route::get('remove_temp_file', [FileManagerController::class, 'removeTempFile'])->name('remove_temp_file');

// back-end
Route::middleware(['auth','CheckUserLogin', 'SetSessionData'])->group(function () {

    Route::group(['prefix'=>'admin', 'as'=>'admin.'], function () {

        Route::get('get_notification', function () {
            $transaction_count = GlobalFunction::countNotification('Transaction');
            $comment_count = GlobalFunction::countNotification('Comment');
            $contact_us_count = GlobalFunction::countNotification('ContactUs');

            return response()->json([
                'success'             => true,
                'transaction_count'   => $transaction_count,
                'comment_count'       => $comment_count,
                'contact_us_count'    => $contact_us_count,
            ]);
        })->name('get_notification');

        Route::get('get_booking_notification', function () {
            $notifications = Notification::where('notificationable_type', 'App\Models\Transaction')->where('send_date', null)->pluck('notificationable_id')->toArray();
            $transactions = Transaction::whereIn('id', $notifications)->get();
            $transactions_invoice_no = $transactions->pluck('invoice_no')->toArray();

            Notification::where('notificationable_type', 'App\Models\Transaction')->where('send_date', null)->update(['send_date' => now()]);
            // dd($transactions_invoice_no);
            // dd($notifications);

            return response()->json($transactions_invoice_no);
        })->name('get_booking_notification');

        Route::get('get_comment_notification', function () {
            $notifications = Notification::where('notificationable_type', 'App\Models\Comment')->where('send_date', null)->pluck('notificationable_id')->toArray();
            $comments = Comment::whereIn('id', $notifications)->get();
            $comment_message = $comments->pluck('content')->toArray();

            Notification::where('notificationable_type', 'App\Models\Comment')->where('send_date', null)->update(['send_date' => now()]);
            // dd($transactions_message);
            // dd($notifications);

            return response()->json($comment_message);
        })->name('get_comment_notification');

        Route::get('get_contact_us_notification', function () {
            $notifications = Notification::where('notificationable_type', 'App\Models\ContactUs')->where('send_date', null)->pluck('notificationable_id')->toArray();
            $contact_uses = ContactUs::whereIn('id', $notifications)->get();
            $contact_us_message = $contact_uses->pluck('message')->toArray();

            Notification::where('notificationable_type', 'App\Models\ContactUs')->where('send_date', null)->update(['send_date' => now()]);
            // dd($transactions_message);
            // dd($notifications);

            return response()->json($contact_us_message);
        })->name('get_contact_us_notification');

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
        // Route for Menu
        Route::get('menu/update_status', [MenuController::class, 'updateStatus'])->name('menu.update_status');
        Route::resource('/menu', MenuController::class);

        Route::get('explore_menu/update_status', [MenuExploreController::class, 'updateStatus'])->name('explore_menu.update_status');
        Route::resource('/explore_menu', MenuExploreController::class);
        // Route for backend slider
        Route::get('slider/update_status', [SliderController::class, 'updateStatus'])->name('slider.update_status');
        Route::resource('/slider', SliderController::class);

        // Route of calendar
        Route::group(['prefix' => 'room', 'as' => 'room.'], function () {

            Route::get('room_rate_calendar/data',[ RoomCalendarController::class, 'getData']);
            Route::get('room_rate_calendar/loadDates', [RoomCalendarController::class, 'loadDates'])->name('room_rate_calendar.load_dates');
            Route::post('room_rate_calendar/store', [RoomCalendarController::class, 'store'])->name('room_rate_calendar.store');
            Route::resource('room_rate_calendar', RoomCalendarController::class);

            // Route::get('allotment_calendar/data',[ RoomCalendarController::class, 'getData']);
            Route::get('allotment_calendar/loadDates', [RoomAllotmentCalendarController::class, 'loadDates'])->name('allotment_calendar.load_dates');
            Route::post('allotment_calendar/store', [RoomAllotmentCalendarController::class, 'store'])->name('allotment_calendar.store');

            Route::resource('allotment_calendar', RoomAllotmentCalendarController::class);

        });

        // Route for staycation
        Route::get('staycation/update_status', [StaycationController::class, 'updateStatus'])->name('staycation.update_status');
        Route::resource('/staycation', StaycationController::class);

        // Route for room
        Route::get('room/update_status', [RoomController::class, 'updateStatus'])->name('room.update_status');
        Route::resource('/room', RoomController::class);

        // Route for gallery
        Route::get('gallery/update_status', [GalleryController::class, 'updateStatus'])->name('gallery.update_status');
        Route::resource('/gallery', GalleryController::class);

        // Route for gallery-category
        Route::get('gallery-category/update_status', [CategoryController::class, 'updateStatus'])->name('gallery-category.update_status');
        Route::resource('/gallery-category', CategoryController::class);

        // Route for amenities
        Route::get('amenities/update_status', [HomeStayAmenityController::class, 'updateStatus'])->name('amenities.update_status');
        Route::resource('/amenities', HomeStayAmenityController::class);

        // Route for rate plan
        Route::get('rate_plan/update_status', [RatePlanController::class, 'updateStatus'])->name('rate_plan.update_status');
        Route::resource('/rate_plan', RatePlanController::class);

        // Route for service
        Route::get('services/update_status', [ServiceController::class, 'updateStatus'])->name('service.update_status');
        Route::resource('/services', ServiceController::class);

        // Route for blog Category
        Route::get('blog-category/update_status', [BlogCategoryController::class, 'updateStatus'])->name('blog-category.update_status');
        Route::resource('blog-category', BlogCategoryController::class);

        // Route for blog Tag
        Route::get('blog-tag/update_status', [BlogTagController::class, 'updateStatus'])->name('blog-tag.update_status');
        Route::resource('blog-tag', BlogTagController::class);

        //Route for facility
        Route::get('highlight/update_status', [HighlightContoller::class, 'updateStatus'])->name('highlight.update_status');
        Route::resource('highlight', HighlightContoller::class);

        //Route for facility
        Route::get('facility/update_status', [FacilityController::class, 'updateStatus'])->name('facility.update_status');
        Route::resource('facility', FacilityController::class);

        //Route for blog
        Route::get('blog/update_status', [BlogController::class, 'updateStatus'])->name('blog.update_status');
        Route::resource('blog', BlogController::class);

        //Route for customer
        Route::get('customer/update_status', [CustomerController::class, 'updateStatus'])->name('customer.update_status');
        Route::resource('customer', CustomerController::class);

        //Route for Extr Service
        Route::get('extra-service/update_status', [ExtraServiceController::class, 'updateStatus'])->name('extra-service.update_status');
        Route::resource('extra-service', ExtraServiceController::class);

        //Route for comment
        Route::get('comment', [CommentController::class, 'index'])->name('comment.index');
        Route::get('comment/reply/{id}', [CommentController::class, 'show'])->name('comment.show');
        Route::post('comment/store/{id}', [CommentController::class, 'adminReply'])->name('comment.reply');
        Route::delete('comment/delete/{id}', [CommentController::class, 'destroy'])->name('comment.destroy');
        Route::get('comment/update_status', [CommentController::class, 'updateStatus'])->name('comment.update_status');


        //Route for contact us
        Route::get('contact', [ContactUsController::class, 'contact'])->name('contact.index');
        Route::get('contact/{id}', [ContactUsController::class, 'show'])->name('contact.view');
        Route::post('contact/{id}/reply', [ContactUsController::class, 'adminReply'])->name('contact.reply');
        Route::delete('contact/delete/{id}', [ContactUsController::class, 'destroy'])->name('contact.destroy');

        //Route for role
        Route::resource('roles', RoleController::class);

        // Route for report
        Route::get('booking_report/update_status', [ReportController::class, 'bookingReportUpdateStatus'])->name('booking_report.update_status');
        Route::get('booking_report/{id}', [ReportController::class, 'viewBookingReport'])->name('booking_report.detail');
        Route::get('booking_report', [ReportController::class, 'bookingReport'])->name('booking_report');

        // Route for  pages
        Route::resource('pages', PageController::class);
        // Route for  section title
        Route::resource('section_title', SectionTitleController::class);

    });

});

Route::middleware(['auth:web'])->group(function () {
    Route::get('/logout', [LoginController::class,'logout'])->name('logout');
});