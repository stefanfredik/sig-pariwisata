<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\KecamatanController;
use App\Http\Controllers\Admin\ObjekWisataController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\FasilitasWisataController;

use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\ObjekWisataController as PublicObjekWisataController;
use App\Http\Controllers\Public\EventController as PublicEventController;
use App\Http\Controllers\Public\ReviewController as PublicReviewController;
use App\Http\Controllers\Public\ProfileController as PublicProfileController;
use App\Http\Controllers\Public\SearchController as PublicSearchController;
use App\Http\Controllers\Public\SitemapController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/destinasi', [PublicObjekWisataController::class, 'index'])->name('public.objek-wisata.index');
Route::get('/destinasi/{slug}', [PublicObjekWisataController::class, 'show'])->name('public.objek-wisata.show');
Route::get('/events', [PublicEventController::class, 'index'])->name('public.events.index');
Route::get('/events/{slug}', [PublicEventController::class, 'show'])->name('public.events.show');
Route::get('/peta', [PublicObjekWisataController::class, 'map'])->name('public.map');
Route::get('/api/search-suggest', [PublicSearchController::class, 'suggest'])->name('public.search.suggest');
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

// Authenticated public routes
Route::middleware('auth')->group(function () {
    Route::post('/reviews', [PublicReviewController::class, 'store'])->name('public.reviews.store');
    Route::get('/profil', [PublicProfileController::class, 'show'])->name('public.profile');
    Route::put('/profil', [PublicProfileController::class, 'update'])->name('public.profile.update');
});


// Authentication routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin routes (protected by admin middleware)
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    // Kecamatan CRUD
    Route::resource('kecamatan', KecamatanController::class)->names([
        'index' => 'admin.kecamatan.index',
        'create' => 'admin.kecamatan.create',
        'store' => 'admin.kecamatan.store',
        'show' => 'admin.kecamatan.show',
        'edit' => 'admin.kecamatan.edit',
        'update' => 'admin.kecamatan.update',
        'destroy' => 'admin.kecamatan.destroy',
    ]);

    // Objek Wisata CRUD
    Route::resource('objek-wisata', ObjekWisataController::class)->names([
        'index' => 'admin.objek-wisata.index',
        'create' => 'admin.objek-wisata.create',
        'store' => 'admin.objek-wisata.store',
        'show' => 'admin.objek-wisata.show',
        'edit' => 'admin.objek-wisata.edit',
        'update' => 'admin.objek-wisata.update',
        'destroy' => 'admin.objek-wisata.destroy',
    ]);
    Route::delete('objek-wisata/foto/{id}', [ObjekWisataController::class, 'deletePhoto'])->name('admin.objek-wisata.delete-photo');
    Route::post('objek-wisata/{objekId}/foto/{fotoId}/primary', [ObjekWisataController::class, 'setPrimaryPhoto'])->name('admin.objek-wisata.set-primary-photo');

    // Events CRUD
    Route::resource('events', EventController::class)->names([
        'index' => 'admin.events.index',
        'create' => 'admin.events.create',
        'store' => 'admin.events.store',
        'show' => 'admin.events.show',
        'edit' => 'admin.events.edit',
        'update' => 'admin.events.update',
        'destroy' => 'admin.events.destroy',
    ]);
    Route::delete('events/foto/{id}', [EventController::class, 'deletePhoto'])->name('admin.events.delete-photo');
    Route::post('events/{eventId}/foto/{fotoId}/primary', [EventController::class, 'setPrimaryPhoto'])->name('admin.events.set-primary-photo');

    // Fasilitas Wisata CRUD
    Route::resource('fasilitas-wisata', FasilitasWisataController::class)->names([
        'index' => 'admin.fasilitas-wisata.index',
        'create' => 'admin.fasilitas-wisata.create',
        'store' => 'admin.fasilitas-wisata.store',
        'show' => 'admin.fasilitas-wisata.show',
        'edit' => 'admin.fasilitas-wisata.edit',
        'update' => 'admin.fasilitas-wisata.update',
        'destroy' => 'admin.fasilitas-wisata.destroy',
    ]);
    Route::delete('fasilitas-wisata/foto/{id}', [FasilitasWisataController::class, 'deletePhoto'])->name('admin.fasilitas-wisata.delete-photo');

    // Review Moderation
    Route::get('/reviews', [AdminReviewController::class, 'index'])->name('admin.reviews.index');
    Route::post('/reviews/{id}/approve', [AdminReviewController::class, 'approve'])->name('admin.reviews.approve');
    Route::delete('/reviews/{id}', [AdminReviewController::class, 'destroy'])->name('admin.reviews.destroy');
});
