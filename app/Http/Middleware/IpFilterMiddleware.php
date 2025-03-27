<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class IpFilterMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $ip = $request->ip();
        
        // Кэшируем результат проверки на 24 часа для уменьшения количества запросов к API
        $isSuspicious = Cache::remember('ip_check_'.$ip, 60 * 24, function() use ($ip) {
            // Пример использования API IPQualityScore (требуется API ключ)
            $response = Http::get('https://ipqualityscore.com/api/json/ip/jQJjzJzd2g7mJ3hGG78WpdTEzkhUtpyG/'.$ip);
            
            if ($response->successful()) {
                $data = $response->json();

                // Проверяем, существуют ли ключи в ответе
                if (isset($data['proxy']) && isset($data['vpn']) && isset($data['bot_status'])) {
                    // Если это прокси, VPN или бот с высокой вероятностью
                    return $data['proxy'] || $data['vpn'] || $data['bot_status'] > 0.8;
                } else {
                    // Логируем ошибку, если ключи отсутствуют
                    Log::error('Неправильная структура ответа от API IPQualityScore', ['response' => $data]);
                    return false;
                }
            } else {
                // Логируем ошибку, если запрос к API не удался
                Log::error('Ошибка при запросе к API IPQualityScore', ['status' => $response->status(), 'response' => $response->body()]);
                return false;
            }
        });
        
        if ($isSuspicious) {
            abort(403, __('message.access_denied')); // Используем перевод
        }
        
        return $next($request);
    }
}