<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Log;

class SuspiciousActivityMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $ip = $request->ip();
        $key = "ip:{$ip}:requests";
        $blockKey = "ip:{$ip}:blocked";
        
        // Проверяем, заблокирован ли IP
        if (Redis::exists($blockKey)) {
            abort(403, __('message.ip_blocked'));
        }
        
        // Увеличиваем счетчик запросов
        Redis::incr($key);
        Redis::expire($key, 60); // Истекает через 60 секунд
        
        // Получаем текущее количество запросов
        $requestCount = Redis::get($key);
        
        // Если больше 30 запросов в минуту, считаем это подозрительным
        if ($requestCount > 30) {
            // Блокируем IP на 1 час
            Redis::setex($blockKey, 3600, 1);
            
            Log::warning("Заблокирован подозрительный IP: {$ip} ({$requestCount} запросов/мин)");
            abort(403, __('message.too_many_requests'));
        }
        
        return $next($request);
    }
}