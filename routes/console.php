<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

// Команда, которая выводит вдохновляющую цитату
Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// Команда для обработки предложений на основе результатов голосования
// Artisan::command('offers:process', function () {
//     $this->call('offers:process');
// })->describe('Process offers based on voting results every minute');
