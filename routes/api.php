<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use App\Http\Controllers\UserStatusController;
use App\Http\Controllers\TelegramBotController;
use App\Http\Controllers\Api\AppVersionController;
use App\Http\Controllers\AIController;


/*
|--------------------------------------------------------------------------
| Telegram webhook
|--------------------------------------------------------------------------
*/

Route::post('/telegram/webhook', [TelegramBotController::class, 'handle']);

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

