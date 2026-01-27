<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ReparationController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VehiculeController;
use App\Http\Controllers\TechnicienController;
use App\Http\Controllers\ServiceController;
use App\Http\Middleware\IncreaseUploadLimits;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\BreakdownController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\GestionClientController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserManagementController;




//Route d'acceuil
Route::get('/', [WelcomeController::class, 'welcome'])->name('welcome');

// Routes Véhicules
Route::resource('vehicules', VehiculeController::class);

// Routes Techniciens (remplace Client)
Route::resource('techniciens', TechnicienController::class)->middleware('role:responsable_services,admin');

//Routes Reparations 
Route::resource('reparations', ReparationController::class)->middleware('role:responsable_services,admin');

//Routes Services avec middleware pour les uploads
Route::resource('services', ServiceController::class)->middleware('role:responsable_services,admin')->middleware(IncreaseUploadLimits::class);

// Routes Déclaration de Pannes (Breakdowns)
Route::resource('breakdowns', BreakdownController::class)->middleware('auth');

// Routes API pour les requêtes AJAX
Route::get('/breakdowns/api/my-vehicules', [BreakdownController::class, 'getMyVehicules'])
    ->name('breakdowns.api.vehicules')
    ->middleware('auth');

Route::get('/breakdowns/api/techniciens', [BreakdownController::class, 'getTechniciens'])
    ->name('breakdowns.api.techniciens')
    ->middleware('auth');

// Route A Propos
Route::view('/apropos', 'apropos')->name('apropos');


// Routes Contact
Route::get('/contact', [ContactController::class, 'show'])->name('contact');
Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');

// Authentication Routes
Auth::routes();
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Admin Routes with 'auth' middleware
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'admin'])->name('admin.dashboard');
    Route::patch('/admin/users/{user}/approve', [AdminController::class, 'approveGestionClient'])->name('admin.approve-user');
    Route::post('/admin/users/{user}/approve-gestion-client', [AdminController::class, 'approveGestionClient'])->name('admin.users.approve-gestion-client');
    Route::put('/admin/users/{user}/deactivate', [AdminController::class, 'deactivateUser'])->name('admin.users.deactivate');
    Route::post('/admin/users/{user}/reset-password', [AdminController::class, 'resetPassword'])->name('admin.users.reset-password');
    Route::delete('/admin/breakdowns/{breakdown}/soft-delete', [AdminController::class, 'softDeleteBreakdown'])->name('admin.breakdowns.soft-delete');
    Route::post('/admin/breakdowns/{id}/restore', [AdminController::class, 'restoreBreakdown'])->name('admin.breakdowns.restore');
    Route::delete('/admin/breakdowns/{breakdown}/hard-delete', [AdminController::class, 'hardDeleteBreakdown'])->name('admin.breakdowns.hard-delete');
    Route::get('/admin/users', [AdminController::class, 'listUsers'])->name('admin.users.index');
    Route::get('/admin/audit-log', [AdminController::class, 'auditLog'])->name('admin.audit-log');
    Route::get('/admin/export-data', [AdminController::class, 'exportData'])->name('admin.export-data');
});

// Routes Gestion Client
// Décommenter et placer la route du tableau de bord à l'intérieur du groupe
Route::prefix('gestion-client')->middleware(['auth', 'role:gestion_client'])->group(function () {
    Route::get('/dashboard', [GestionClientController::class, 'index'])->name('gestion_client.dashboard');
    // Ajoutez ici d'autres routes spécifiques au rôle gestion_client (ex: listes de clients, de véhicules, etc.)
    // Route::get('/clients', [GestionClientController::class, 'listClients'])->name('gestion_client.clients.index');
    // Route::get('/clients/{user}', [GestionClientController::class, 'showClient'])->name('gestion_client.clients.show');
});

//Routes Profil
Route::middleware(['auth'])->group(function () {
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/{user}', [ProfileController::class, 'show'])->name('profile.show');
});

// Routes de gestion des utilisateurs (Création, Approbation, Suppression)
Route::middleware(['auth'])->group(function () {
    // Admin: Gérer tous les utilisateurs
    Route::get('/users', [UserManagementController::class, 'index'])->name('users.index')->middleware('role:admin');
    Route::patch('/users/{user}/approve', [UserManagementController::class, 'approve'])->name('users.approve')->middleware('role:admin');
    Route::patch('/users/{user}/deactivate', [UserManagementController::class, 'deactivate'])->name('users.deactivate')->middleware('role:admin');
    Route::delete('/users/{user}', [UserManagementController::class, 'destroy'])->name('users.destroy')->middleware('role:admin');
    Route::post('/users/{user}/restore', [UserManagementController::class, 'restore'])->name('users.restore')->middleware('role:admin');
    
    // Responsable services et Gestion client: Créer des utilisateurs
    Route::get('/users/create', [UserManagementController::class, 'create'])->name('users.create')->middleware('role:responsable_services,gestion_client,admin');
    Route::post('/users', [UserManagementController::class, 'store'])->name('users.store')->middleware('role:responsable_services,gestion_client,admin');
    
    // Vérification du code pour les clients
    Route::get('/verify-code', [UserManagementController::class, 'showVerifyCode'])->name('verify-code');
    Route::post('/verify-code', [UserManagementController::class, 'verifyCode'])->name('verify-code.store');
});

    // Routes Dashboards spécifiques par rôle
    Route::get('/dashboards/responsable-services', [App\Http\Controllers\HomeController::class, 'responsableServicesDashboard'])->name('dashboards.responsable-services')->middleware(['auth', 'role:responsable_services']);
    Route::get('/dashboards/client', [App\Http\Controllers\HomeController::class, 'clientDashboard'])->name('dashboards.client')->middleware(['auth', 'role:client']);
    