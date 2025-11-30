<?php
// app/Services/TemplateParser.php

namespace App\Services;

class TemplateParser
{
    /**
     * Анализирует текст шаблона, заменяет плейсхолдеры вида {{key}} и переписывает ссылки на редиректы с трекингом.
     *
     * Специальные ключи в $data:
     *  - 'click_base' : базовый URL для редиректа кликов; должен оканчиваться на '='
     *                   (к нему будет добавлен base64-код целевого URL)
     *
     * Пример использования:
     *  $data = [
     *      'name' => 'Сергей',
     *      'tracking' => '<img ...>',
     *      'click_base' => url('/mailer/c/123?u=')  // примечание: urlencode здесь не требуется
     *  ];
     */
    public static function parse(string $text, array $data): string
    {
        // 1. Нормализация сохранённого текста
        $text = stripcslashes($text);
        $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        // 2. Замена простых плейсхолдеров {{key}} (без экранирования — данные должны быть уже безопасными/закодированными)
        foreach ($data as $key => $value) {
            // пропускаем click_base — он обрабатывается отдельно
            if ($key === 'click_base') {
                continue;
            }
            $pattern = '/\{\{\s*' . preg_quote($key, '/') . '\s*\}\}/u';
            $text = preg_replace($pattern, $value ?? '', $text);
        }

        // 3. Если задан click_base, переписываем все href="http..." и href='http...' на редиректы с трекингом
        if (!empty($data['click_base'])) {
            $clickBase = rtrim($data['click_base'], '=') . '='; // убеждаемся, что строка оканчивается на '=' для добавления
            // Заменяем href="...":
            $text = preg_replace_callback(
                '/href=(["\'])(https?:\/\/[^"\']+)\1/ui',
                function ($m) use ($clickBase) {
                    $quote = $m[1];
                    $url = $m[2];
                    // кодируем URL безопасно для передачи в параметре запроса:
                    $encoded = rtrim(strtr(base64_encode($url), '+/', '-_'), '=');
                    $new = $clickBase . $encoded;
                    return 'href=' . $quote . $new . $quote;
                },
                $text
            );
        }

        return $text;
    }
}