<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use App\Http\Controllers\UserStatusController;
use App\Http\Controllers\TelegramBotController;
use App\Http\Controllers\Api\AppVersionController;
use App\Http\Controllers\AIController;
use App\Http\Controllers\Api\MenuController;
use App\Http\Controllers\Api\AppReleaseController;
use App\Http\Middleware\ReleaseAdmin;


/*
|--------------------------------------------------------------------------
| Telegram webhook
|--------------------------------------------------------------------------
*/

// Route::post('/telegram/webhook', [TelegramBotController::class, 'handle']);

/*
|--------------------------------------------------------------------------
| ONLINE  API
|--------------------------------------------------------------------------
|
| ВАЖНО: sendBeacon использует cookie → значит нужна web-сессия.
| Поэтому добавляется middleware 'web'. Аутентификация — 'auth'.
| CSRF — отключён индивидуально.
|
*/
Route::middleware(['web', 'auth'])->group(function () {

    Route::post('/user/online', function () {
        Log::info('📡 ONLINE ROUTE HIT', [
            'user_id' => auth()->id(),
            'time'    => now()->toDateTimeString(),
            'route'   => request()->path(),
        ]);

        return app(UserStatusController::class)->online(request());
    })
        ->withoutMiddleware([VerifyCsrfToken::class])
        ->name('user.online');
});




Route::get('/app/version', [AppVersionController::class, 'check']);


Route::get('/ai/patches', [AIController::class, 'patches']);
Route::post('/ai/patch/approve', [AIController::class, 'approve']);
Route::post('/ai/patch/reject', [AIController::class, 'reject']);

Route::get(
    '/organizations/{organization}/menu',
    [MenuController::class, 'index']
);

Route::get(
    '/recipe-calculator/releases/latest',
    [AppReleaseController::class, 'latest']
);

/*
|--------------------------------------------------------------------------
| RELEASE ADMIN PANEL
|--------------------------------------------------------------------------
|
| Управление релизами приложения "Ёлки Иголки".
|
| Доступ только DAO_Root — user.id = 3.
|
*/

// Route::middleware([
//     'auth',
//     ReleaseAdmin::class,
// ])
//     ->prefix('admin/releases')
//     ->name('admin.releases.')
//     ->group(function () {

//         /*
//          * Список релизов
//          */
//         Route::get('/', [
//             ReleaseAdminController::class,
//             'index'
//         ])->name('index');


//         /*
//          * Создание релиза
//          */
//         Route::get('/create', [
//             ReleaseAdminController::class,
//             'create'
//         ])->name('create');


//         /*
//          * Сохранение релиза
//          */
//         Route::post('/', [
//             ReleaseAdminController::class,
//             'store'
//         ])->name('store');


//         /*
//          * Редактирование релиза
//          */
//         Route::get('/{release}/edit', [
//             ReleaseAdminController::class,
//             'edit'
//         ])->name('edit');


//         /*
//          * Обновление релиза
//          */
//         Route::put('/{release}', [
//             ReleaseAdminController::class,
//             'update'
//         ])->name('update');


//         /*
//          * Удаление релиза
//          */
//         Route::delete('/{release}', [
//             ReleaseAdminController::class,
//             'destroy'
//         ])->name('destroy');


//         /*
//          * Активация релиза
//          */
//         Route::post('/{release}/activate', [
//             ReleaseAdminController::class,
//             'activate'
//         ])->name('activate');
//     });