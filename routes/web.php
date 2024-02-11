<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

//Call Controllers
use App\Http\Controllers\Guest\Dashboard as GuestDashboardController;
use App\Http\Controllers\Staff\Dashboard as StaffDashboardController;
use App\Http\Controllers\AccommodationBookingController as ReservationController;
use App\Http\Controllers\AccommodationController;
use App\Http\Controllers\UserController;

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

Route::get('/', function () {
    return Inertia::render('HomePage', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

/** ======== ACCOMMODATION ROUTES FOR PUBLIC ======== */
Route::resource('/accommodations', AccommodationController::class)
->only(['index', 'show']); // Specify only the needed routes
Route::post('/accommodation-search', [AccommodationController::class, 'searchAccommodations'])->name('accommodations.search');

// Routes for guests on login or registration
Route::middleware(['auth'])->group( function() {
    Route::resource('/user-dashboard', GuestDashboardController::class)
    ->only(['index'])
    ->names([
        'index' => 'guest.dashboard', // Custom route name for the index method
    ]);
});

// Routes for staff members
Route::middleware(['auth', 'redirectIfNotStaff'])->group( function() {
    /** ======== ACCOMMODATION ROUTES FOR THIS GROUP AND HIGHER ======== */
    Route::resource('/dashboard/accommodations', AccommodationController::class)
    ->only(['index', 'show']); // Specify only the needed routes
    Route::post('/dashboard/accommodation-search', [AccommodationController::class, 'searchAccommodations'])->name('dashboard.accommodations.search');

    Route::resource('/staff-dashboard', StaffDashboardController::class)
    ->only(['index'])
    ->names([
        'index' => 'staff.dashboard', // Custom route name for the index method
    ]);
    /** ======== RESERVATIONS ROUTES ======== */
    Route::resource('/dashboard/reservations', ReservationController::class)
    ->only(['index', 'create', 'show', 'store', 'edit', 'update', 'destroy']); // Specify only the needed routes
    Route::post('/dashboard/search-reservation', [ReservationController::class, 'searchReservation'])->name('dashboard.reservation.search');
    Route::post('/dashboard/search-idnumber', [ReservationController::class, 'idNumber'])->name('dashboard.reservation.idnumber');
    Route::post('/dashboard/reservation/check-in', [ReservationController::class, 'checkIn'])->name('dashboard.reservation.checkIn');
});

// Routes for senior staff members
Route::middleware(['auth', 'redirectIfNotSeniorStaff'])->group( function() {
    /** ======== STAFF ROUTES ======== */    
    // Resourceful routes for staff members
    Route::resource('/dashboard/staff', UserController::class)
    ->only(['index', 'show', 'store', 'update', 'destroy']); // Specify only the needed routes

    // Custom route for searching staff members
    Route::post('/dashboard/staff/search', [UserController::class, 'searchStaffMember'])
    ->name('dashboard.staff.search');

    /** ======== ACCOMMODATION ROUTES FOR THIS GROUP AND HIGHER ======== */ 
    // Resourceful routes for accommodations
    Route::resource('/dashboard/accommodation', AccommodationController::class)
    ->only(['create', 'store', 'edit', 'update', 'destroy']); // Specify only the needed routes
    

    /** ======== RESORT CONFIGURATIONS ROUTES FOR THIS GROUP AND HIGHER (I will create this if I have enough Time) ======== */
    Route::resource('/dashboard/configuration', StaffDashboardController::class)
    ->only(['index', 'store', 'show', 'update', 'destroy']) // Specify only the needed routes
    ->names([
        'index' => 'staff.configurations', // Custom route name for the index method
    ]);

    // Resourceful routes for resort amenities
    Route::resource('/dashboard/resort-amenities', StaffDashboardController::class)
    ->only(['index', 'create', 'store', 'show', 'update', 'destroy']); // Specify only the needed routes

    // Custom route for searching resort amenities
    Route::post('/dashboard/resort-amenities/search', [StaffDashboardController::class, 'searchResortAmenities'])
    ->name('dashboard.resort-amenities.search');

    Route::resource('/dashboard/accommodation-amenities', StaffDashboardController::class)
    ->only(['index', 'store', 'show', 'update', 'destroy']); // Specify only the needed routes

    // Custom route for searching accommodation amenities
    Route::post('/dashboard/accommodation-amenities/search', [StaffDashboardController::class, 'searchAccommodationAmenities'])
        ->name('dashboard.accommodation-amenities.search');
});

// Routes for admin members
Route::middleware(['auth', 'redirectIfNotAdmin'])->group( function() {
    // Route::get('/dashboard/system-health', function () {
    //     return Inertia::render('Dashboard');
    // })->name('dashboard.systemhealth');
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

require __DIR__.'/auth.php';
