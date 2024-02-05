<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

//Call Controllers
use App\Http\Controllers\Guest\Dashboard as GuestDashboardController;
use App\Http\Controllers\Staff\Dashboard as StaffDashboardController;

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
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

// Routes for guests on login or registration
Route::middleware(['auth'])->group( function() {
    Route::get('/dashboard/profile', [GuestDashboardController::class, 'index'])->name('dashboard.profile');
});

// Routes for staff members
Route::middleware(['auth', 'redirectIfNotStaff'])->group( function() {
    /** ======== RESERVATIONS ROUTES ======== */
    Route::get('/dashboard/reservations', [StaffDashboardController::class, 'getReservationsList'])->name('dashboard.reservations.list'); // Routes for reservations list
    Route::post('/dashboard/create-reservation', [StaffDashboardController::class, 'createReservation'])->name('dashboard.reservation.create'); // Route to Create reservation
    Route::put('/dashboard/update-reservation/{id}', [StaffDashboardController::class, 'updateReservation'])->name('dashboard.reservation.update'); // Route to view and update reservation
    Route::delete('/dashboard/delete-reservation/{id}', [StaffDashboardController::class, 'deleteReservation'])->name('dashboard.reservation.delete'); // Route to delete reservation
    Route::post('/dashboard/search-reservation', [StaffDashboardController::class, 'searchReservation'])->name('dashboard.reservation.search'); // Route to search reservation
    Route::post('/dashboard/search-client', [StaffDashboardController::class, 'searchclient'])->name('dashboard.client.search'); // Route for client search

    /** ======== ACCOMMODATION ROUTES FOR THIS GROUP AND HIGHER ======== */
    Route::get('/dashboard/acommodations', [StaffDashboardController::class, 'getAccommodationsList'])->name('dashboard.accommodations.list'); // Routes for accommodations list
    Route::get('/dashboard/acommodations/{id}', [StaffDashboardController::class, 'getAccommodation'])->name('dashboard.accommodations.view'); // Routes for accommodations view, this group cannot update acommodations
    Route::post('/dashboard/acommodation-search', [StaffDashboardController::class, 'searchAccommodations'])->name('dashboard.accommodations.search'); // Routes for accommodations search

});

// Routes for senior staff members
Route::middleware(['auth', 'redirectIfNotSeniorStaff'])->group( function() {
    /** ======== STAFF ROUTES ======== */    
    Route::get('/dashboard/staff', [StaffDashboardController::class, 'getStaffList'])->name('dashboard.staff.list'); // Routes to view staff list    
    Route::post('/dashboard/create-staff', [StaffDashboardController::class, 'createStaffMember'])->name('dashboard.staff.create'); // Routes to create staff member    
    Route::put('/dashboard/update-staff/{id}', [StaffDashboardController::class, 'updateStaffMember'])->name('dashboard.staff.update'); // Routes to view and update staff member
    Route::delete('/dashboard/delete-staff/{id}', [StaffDashboardController::class, 'deleteStaffMember'])->name('dashboard.staff.delete'); // Routes to delete staff member
    Route::post('/dashboard/search-staff', [StaffDashboardController::class, 'searchStaffMember'])->name('dashboard.staff.search'); // Routes to search for staff member 

    /** ======== ACCOMMODATION ROUTES FOR THIS GROUP AND HIGHER ======== */ 
    Route::post('/dashboard/acommodation-create', [StaffDashboardController::class, 'createAccommodations'])->name('dashboard.accommodations.create'); // Routes for accommodations list
    Route::put('/dashboard/acommodation-update', [StaffDashboardController::class, 'updateAccommodations'])->name('dashboard.accommodations.update'); // Routes for accommodations view and update, for this group and higher
    Route::delete('/dashboard/acommodation-delete', [StaffDashboardController::class, 'deleteAccommodations'])->name('dashboard.accommodations.delete'); // Routes for accommodations delete

    /** ======== RESORT CONFIGURATIONS ROUTES FOR THIS GROUP AND HIGHER ======== */
    Route::get('/dashboard/resort-amenities', [StaffDashboardController::class, 'resortAmenityList'])->name('dashboard.resort-amenities.list'); // Route to get a list of the resort amenities
    Route::post('/dashboard/create-resort-amenities', [StaffDashboardController::class, 'createResortAmenity'])->name('dashboard.resort-amenities.create'); // Route to create the resort amenities
    Route::put('/dashboard/update-resort-amenities', [StaffDashboardController::class, 'updateResortAmenity'])->name('dashboard.resort-amenities.update'); // Route to update the resort amenities
    Route::delete('/dashboard/delete-resort-amenities', [StaffDashboardController::class, 'deleteResortAmenity'])->name('dashboard.resort-amenities.delete'); // Route to delete the resort amenities

    Route::get('/dashboard/accommodation-amenity-list', [StaffDashboardController::class, 'accommodationAmenitiesList'])->name('dashboard.accommodation-amenity.list'); // Route to get a list of amenities which needs to be called in the accommodations create/update page for example sleeper, self catering, towels
    Route::post('/dashboard/create-accommodation-amenity', [StaffDashboardController::class, 'createAccommodationAmenities'])->name('dashboard.accommodation-amenity.create'); // Route to create an amenity 
    Route::put('/dashboard/update-accommodation-amenity', [StaffDashboardController::class, 'updateAccommodationAmenities'])->name('dashboard.accommodation-amenity.update'); // Route to update an amenity 
    Route::delete('/dashboard/delete-accommodation-amenity', [StaffDashboardController::class, 'deleteAccommodationAmenities'])->name('dashboard.accommodation-amenity.delete'); // Route to delete an amenity
});

// Routes for admin members
Route::middleware(['auth', 'redirectIfNotAdmin'])->group( function() {
    Route::get('/dashboard/system-health', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard.systemhealth');
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
