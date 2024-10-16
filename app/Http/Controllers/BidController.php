<?php

namespace App\Http\Controllers;

use App\Models\Bid;
use App\Models\Task;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BidController extends Controller
{
    // Метод для принятия предложения
    public function accept(Request $request, Bid $bid)
    {
        // Получаем задачу, связанная с предложением
        $task = $bid->task;

        // Проверяем, что текущий пользователь — автор задания и предложение ещё не принято
        if (auth()->id() == $task->user_id && !$task->accepted_bid_id) {
            // Устанавливаем принятое предложение
            $task->accepted_bid_id = $bid->id;

            // Устанавливаем время завершения на основе дней и часов предложения
            // Проверяем, установлены ли дни и часы
            if ($bid->days >= 0 && $bid->hours >= 0) {
                $completionTime = now()->addDays($bid->days)->addHours($bid->hours);
                $task->completion_time = $completionTime; // Убедитесь, что у вас есть это поле в модели Task
            } else {
                return redirect()->back()->with('error', 'Недопустимые значения дней или часов.');
            }

            // Сохраняем изменения в модели задачи
            $task->save();

            return redirect()->back()->with('success', 'Предложение принято.');
        }

        return redirect()->back()->with('error', 'Вы не можете принять это предложение.');
    }
}
