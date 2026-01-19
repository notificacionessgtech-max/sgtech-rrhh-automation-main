<?php

use App\Http\Controllers\HiringFormController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SendWelcomeEmailController;
use App\Models\PersonalData;
use App\Models\PersonalDocument;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Home
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('auth.login');
});
/*
|--------------------------------------------------------------------------
| Dashboard
|--------------------------------------------------------------------------
*/
// Dashboard con datos de usuarios
Route::get('/dashboard', [HiringFormController::class, 'getUsers'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Esta puede retornar una vista diferente si quieres
Route::get('/registered-users', [HiringFormController::class, 'getUsers'])
    ->name('registered.users');

Route::get('/dashboard', function () {
    $users = PersonalData::orderBy('created_at', 'desc')->paginate(10);
    return view('dashboard', compact('users')); // ← Pasa $users al dashboard
})->middleware(['auth', 'verified'])->name('dashboard');
/*
|--------------------------------------------------------------------------
| Invitaciones (admin / rrhh)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/invitation', [SendWelcomeEmailController::class, 'index'])
        ->name('send.email.view');

    Route::post('/send-invitation', [SendWelcomeEmailController::class, 'sendWelcomeEmail'])
        ->name('send.welcome.email');

    // Redirección defensiva para evitar error 405 si se accede por GET
    Route::get('/send-invitation', function () {
        return redirect()->route('send.email.view');
    });

    Route::get('/invitations', [HiringFormController::class, 'getInvitations'])
        ->name('invitations');

    Route::get('/registered-users', [HiringFormController::class, 'getUsers'])
        ->name('registered.users');

    Route::get('/employee/{id}', [HiringFormController::class, 'getEmployeeInformationForModal'])
        ->name('get.employee.data');

    Route::get('/employee/{id}', [HiringFormController::class, 'getEmployeeInformationForModal'])
        ->name('get.employee.data');
});
/*
|--------------------------------------------------------------------------
| Perfil
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});
/*
|--------------------------------------------------------------------------
| Formulario de contratación (PÚBLICO)
|--------------------------------------------------------------------------
*/
Route::get('/register/{uuid}', [HiringFormController::class, 'showHiringForm'])
    ->middleware('signed')
    ->name('hiring.form.view');

Route::post('/hiring/register', [HiringFormController::class, 'store'])
    ->name('hiring.post');

Route::get('/hiring/signature/{id}', [HiringFormController::class, 'showSignatureForm'])
    ->name('hiring.signature.view');

Route::post('/hiring/signature/{id}', [HiringFormController::class, 'saveSignature'])
    ->name('hiring.signature.save');

Route::get('/thank-you', function () {
    return view('hiring-form.thank-you');
})->name('hiring.form.thank_you');

/*
|--------------------------------------------------------------------------
| Auth
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';

use App\Http\Controllers\DocumentController;

// Rutas para documentos
Route::prefix('documents')->group(function () {
    // Verificar documentos (para AJAX)
    Route::get(
        '/employee/{id}/check',
        [DocumentController::class, 'checkDocuments']
    )
        ->name('documents.check');

    // Página de debug (opcional)
    Route::get(
        '/employee/{id}/debug',
        [DocumentController::class, 'debugDocuments']
    )
        ->name('documents.debug');

    // AÑADE ESTA LÍNEA NUEVA
    Route::get(
        '/employees/{id}/download-all',
        [DocumentController::class, 'downloadAllDocuments']
    )
        ->name('employees.download.all');
});
