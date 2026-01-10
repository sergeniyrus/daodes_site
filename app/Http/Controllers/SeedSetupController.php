<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class SeedSetupController extends Controller
{
    /**
     * Возвращает текущий публичный ключ пользователя.
     * Используется для верификации сид-фразы на клиенте.
     * Маршрут: GET /profile/public-key
     */
    public function getPublicKey()
    {
        $user = Auth::user();
        return response()->json([
            'public_key' => $user->profile?->public_key ?? null
        ]);
    }

    /**
     * Проверка: есть ли публичный ключ.
     * Маршрут: GET /profile/has-public-key
     */
    public function hasPublicKey()
    {
        $user = Auth::user();
        return response()->json([
            'has_public_key' => (bool) $user->profile?->public_key
        ]);
    }

    /**
     * Показать форму ввода сид-фразы.
     * Маршрут: GET /setup-keys
     */
    public function show()
    {
        $user = Auth::user();

        // Проверка блокировки
        $blockKey = "seed_block_{$user->id}";
        $blockedUntil = Cache::get($blockKey);

        if ($blockedUntil && now()->lt($blockedUntil)) {
            $remaining = now()->diffInMinutes($blockedUntil, false);
            return redirect()->back()->withErrors([
                'seed' => __('auth.seed_blocked_for_minutes', ['minutes' => $remaining])
            ]);
        }

        return view('auth.seed-setup');
    }

    /**
 * Проверяет сид-фразу: клиент присылает сгенерированный public_key,
 * сервер сравнивает его с тем, что в БД.
 * Маршрут: POST /setup-keys/verify
 */
public function verifySeedPhrase(Request $request)
{
    $user = Auth::user();

    // Проверка блокировки
    $blockKey = "seed_block_{$user->id}";
    $blockedUntil = Cache::get($blockKey);
    if ($blockedUntil && now()->lt($blockedUntil)) {
        $remaining = now()->diffInMinutes($blockedUntil, false);
        return response()->json([
            'status' => 'error',
            'message' => __('auth.seed_blocked_for_minutes', ['minutes' => $remaining])
        ], 423);
    }

    $request->validate([
        'public_key' => 'required|string|regex:/^[A-Za-z0-9+\/=]+$/|max:255',
    ]);

    $submittedPublicKey = $request->public_key;
    $storedPublicKey = $user->profile?->public_key;

    if ($storedPublicKey) {
        if ($submittedPublicKey === $storedPublicKey) {
            return response()->json(['status' => 'success']);
        } else {
            $this->reportInvalidSeedInternal($user->id);
            return response()->json([
                'status' => 'error',
                'message' => __('auth.invalid_seed_phrase')
            ], 400);
        }
    } else {
        $user->profile()->updateOrCreate([], [
            'public_key' => $submittedPublicKey,
        ]);
        return response()->json(['status' => 'success']);
    }
}

    /**
     * Внутренний метод подсчёта неудачных попыток (без HTTP-запроса).
     */
    private function reportInvalidSeedInternal(int $userId)
    {
        $attemptsKey = "seed_attempts_{$userId}";
        $blockKey    = "seed_block_{$userId}";

        $attempts = (int) Cache::get($attemptsKey, 0);
        $attempts++;
        Cache::put($attemptsKey, $attempts, now()->addDay());

        if ($attempts <= 3) {
            // Ничего не блокируем, просто засчитываем
            return;
        }

        if ($attempts <= 6) {
            Cache::put($blockKey, now()->addMinutes(15), 15 * 60);
        } elseif ($attempts <= 9) {
            Cache::put($blockKey, now()->addHour(), 3600);
        } else {
            Cache::put($blockKey, now()->addDay(), 86400);
        }
    }

    /**
     * Отчёт о неверной сид-фразе (вызывается с фронтенда при явной ошибке).
     * Маршрут: POST /setup-keys/report-invalid
     */
    public function reportInvalidSeed(Request $request)
    {
        // Этот метод может быть вызван вручную из JS, если клиент сам определил ошибку
        $this->reportInvalidSeedInternal(Auth::id());
        return response()->json(['message' => 'ok']);
    }
}