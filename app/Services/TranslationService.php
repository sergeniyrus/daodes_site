<?php
namespace App\Services;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Illuminate\Support\Facades\Log;

class TranslationService
{
    // Указываем путь к исполняемому файлу Python скрипта
    protected string $pythonScriptPath = '/var/www/daodes/public/argos-translate/translate_text.py';
    protected string $virtualEnvPath = '/var/www/daodes/public/myenv/bin/activate'; // Путь к вашему виртуальному окружению

    public function translate(string $text, string $from, string $to): ?string
    {
        // Формируем команду для активации виртуальной среды и запуска перевода
        $escapedText = escapeshellarg($text);

        // Команда для активации виртуальной среды и запуска Python скрипта
        $command = "/var/www/daodes/public/myenv/bin/python3 {$this->pythonScriptPath} {$from} {$to} {$escapedText}";


        // Логируем команду для отладки
        Log::info('Running translation command: ' . $command);

        // Запускаем процесс
        $process = new Process([$command], null, null, null, null);
        $process->run();

        // Проверяем, если процесс не успешен, выбрасываем исключение
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        // Возвращаем результат перевода
        return trim($process->getOutput());
    }
}




