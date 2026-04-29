<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HelpController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ZoneController;
use App\Http\Controllers\AuditController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\RegionController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\ContactUsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AnimalController;
use App\Http\Controllers\ArchiveCrimeController;
use App\Http\Controllers\SiteAdminController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\ModesOfOperandController;
use App\Http\Controllers\CustomExceptionController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\CaseStatusController;
use App\Http\Controllers\ChartController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\CountUnitController;
use App\Http\Controllers\CrimeCategoryController;
use App\Http\Controllers\CrimeController;
use App\Http\Controllers\CrimeTypeController;
use App\Http\Controllers\CrudGeneratorController;
use App\Http\Controllers\DetectionMethodController;
use App\Http\Controllers\FloraController;
use App\Http\Controllers\HabitatController;
use App\Http\Controllers\ItemCategoryController;
use App\Http\Controllers\ItemDetailController;
use App\Http\Controllers\ItemSeizedController;
use App\Http\Controllers\ItemTypeController;
use App\Http\Controllers\LawController;
use App\Http\Controllers\LoginAttemptController;
use App\Http\Controllers\MeasurementUnitController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SeizuringBodyController;
use App\Http\Controllers\SpeciesController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\SuggestionController;
use App\Http\Controllers\SuspectController;
use App\Http\Controllers\TrafficingCrimeController;
use App\Http\Controllers\TrafficingStatusController;
use App\Http\Controllers\TransportMethodController;
use App\Http\Controllers\VerdictTypeController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\SeizedProductController;
use App\Http\Controllers\TrashedCrimeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::post('contact-us', [ContactUsController::class, 'save'])->name('contact-us.save');


##forgotPassword
Route::get('forget-password', [ForgotPasswordController::class, 'showForgetPasswordForm'])->name('forget.password.get');
Route::post('forget-password', [ForgotPasswordController::class, 'submitForgetPasswordForm'])->name('forget.password.post');
Route::get('reset-password/{token}', [ForgotPasswordController::class, 'showResetPasswordForm'])->name('reset.password.get');
Route::post('reset-password', [ForgotPasswordController::class, 'submitResetPasswordForm'])->name('reset.password.post');

##Auth
// Route::get('/', [WelcomeController::class, 'index'])->name('welcome');

Route::get('', [AuthController::class, 'index'])->name('login');
Route::get('login', [AuthController::class, 'index'])->name('login');
Route::post('/login-user', [AuthController::class, 'loginUser'])->name('login.user');
// Route::get('/register', [AuthController::class, 'create'])->name('auth.create');
Route::post('/signup', [AuthController::class, 'signup'])->name('auth.signup');

Route::resource('/user', UserController::class);

Route::post('/delete-all-data', [CustomExceptionController::class, 'deleteAllData'])->name('delete.all.data');


// Route::resource('contact-us', ContactUsController::class)->only('store');



Route::middleware(['web', 'auth'])->group(function () {
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::prefix('admin')->group(function () {
        Route::name('admin.')->group(function () {
            Route::resource('users', UserController::class);
            Route::resource('helps', HelpController::class);
            Route::resource('emails', EmailController::class);
            Route::resource('regions', RegionController::class);
            Route::resource('countries', CountryController::class);

            Route::resource('zones', ZoneController::class);
            Route::resource('custom-exceptions', CustomExceptionController::class);
            Route::resource('audit', AuditController::class);

            Route::resource('login-attempts', LoginAttemptController::class);
            Route::resource('settings', SettingController::class);
            Route::resource('contact-us', ContactUsController::class);
            Route::post('contact-message', [ContactUsController::class, 'storeReply'])->name('contact-message.storeReply');
            Route::post('update-address/{id}', [SiteAdminController::class, 'update'])->name('updateaddress');
            Route::get('/site-admin', [SiteAdminController::class, 'index'])->name('siteAdmins.index');
            Route::resource('roles', RoleController::class);
            Route::resource('permissions', PermissionController::class);
            Route::get('/change-password', [AuthController::class, 'changePassword'])->name('changePassword');
            Route::get('/two-f-a', [AuthController::class, 'twofa'])->name('2fa');
            Route::get('/reset', [AuditController::class, 'reset'])->name('reset');
            Route::post('/change-password', [AuthController::class, 'changePasswordSave'])->name('postChangePassword');

            Route::get('/profile', [ProfileController::class, 'profile'])->name('profile');
            Route::post('/change-profile', [ProfileController::class, 'changeProfile'])->name('postProfile');
            Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');


            Route::resource('archive-crimes', ArchiveCrimeController::class);
            Route::get("/notifications",[NotificationController::class,"index"])->name("notifications.index");
            Route::DELETE("/notifications/{id}",[NotificationController::class,"destroy"])->name("notification.destroy");
            Route::post("/notification-update",[NotificationController::class,"update"])->name("update.notification");

            #CRUD
            Route::resource('crud-generator', CrudGeneratorController::class);
            Route::post('crud-generator', [CrudGeneratorController::class, 'crudGenerator'])->name('crud-generator');


        });
    });
});

