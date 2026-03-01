<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\CompanyController;
use App\Http\Controllers\Admin\FinancialController;
use App\Http\Controllers\Admin\EmailCampaignController;
use App\Http\Controllers\Admin\ContactController;

// Auth routes
Route::get('/login', fn() => view('auth.login'))->name('login');
Route::post('/login', [\App\Http\Controllers\Auth\LoginController::class, 'login']);
Route::post('/logout', [\App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

// Admin area (requires auth)
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Projects (portfolio)
    Route::resource('projects', ProjectController::class);
    Route::get('projects-featured', [ProjectController::class, 'featuredPage'])->name('projects.featured');
    Route::get('projects-categories', [ProjectController::class, 'categoriesPage'])->name('projects.categories');
    Route::patch('projects/{project}/toggle-featured', [ProjectController::class, 'toggleFeatured'])->name('projects.toggleFeatured');

    // Companies / Clients
    Route::resource('companies', CompanyController::class);

    // Financial tracking
    Route::resource('financials', FinancialController::class);

    // Contacts (incoming)
    Route::get('contacts', [ContactController::class, 'index'])->name('contacts.index');
    Route::get('contacts/{contact}', [ContactController::class, 'show'])->name('contacts.show');
    Route::patch('contacts/{contact}/status', [ContactController::class, 'updateStatus'])->name('contacts.status');
    Route::post('contacts/{contact}/reply', [ContactController::class, 'reply'])->name('contacts.reply');
    Route::delete('contacts/{contact}', [ContactController::class, 'destroy'])->name('contacts.destroy');

    // Email Campaigns
    Route::resource('campaigns', EmailCampaignController::class);
    Route::get('campaigns/{campaign}/recipients', [EmailCampaignController::class, 'recipients'])->name('campaigns.recipients');
    Route::post('campaigns/{campaign}/recipients', [EmailCampaignController::class, 'addRecipients'])->name('campaigns.recipients.add');
    Route::post('campaigns/{campaign}/send', [EmailCampaignController::class, 'send'])->name('campaigns.send');

});

// Redirect root to admin
Route::get('/', fn() => redirect('/admin'));