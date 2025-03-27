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
        // Получаем задачу, связанную с предложением
        $task = $bid->task;

        // Проверяем, что задача существует
        if (!$task) {
            return redirect()->back()->with('error', __('message.task_not_found'));
        }

        // Проверяем, что текущий пользователь — автор задачи
        if (auth()->id() !== $task->user_id) {
            return redirect()->back()->with('error', __('message.not_task_author'));
        }

        // Проверяем, что предложение еще не принято
        if ($task->accepted_bid_id) {
            return redirect()->back()->with('error', __('message.offer_already_accepted'));
        }

        // Проверяем, что значения days и hours корректны
        if ($bid->days === null || $bid->hours === null || $bid->days < 0 || $bid->hours < 0) {
            return redirect()->back()->with('error', __('message.invalid_days_hours'));
        }

        // Устанавливаем принятое предложение
        $task->accepted_bid_id = $bid->id;

        // Устанавливаем время завершения на основе дней и часов предложения
        $completionTime = now()->addDays($bid->days)->addHours($bid->hours);
        $task->completion_time = $completionTime; // Убедитесь, что у вас есть это поле в модели Task

        // Устанавливаем статус задачи на "Обсуждение" (STATUS_NEGOTIATION)
        $task->status = Task::STATUS_NEGOTIATION;

        // Сохраняем изменения в модели задачи
        if ($task->save()) {
            return redirect()->back()->with('success', __('message.offer_accepted'));
        }

        // Если что-то пошло не так при сохранении
        return redirect()->back()->with('error', __('message.accept_offer_failed'));
    }
}