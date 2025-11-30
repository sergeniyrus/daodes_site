<?php

namespace App\Listeners;

use Illuminate\Mail\Events\MessageSent;
use App\Models\MailLog;

class UpdateMailLogStatus
{
    /**
     * Обработка события успешной отправки письма.
     */
    public function handle(MessageSent $event): void
    {
        $logId = $event->data['log_id'] ?? null;

        if ($logId) {
            MailLog::where('id', $logId)->update([
                'status'  => 'sent',
                'sent_at' => now(),
            ]);
        }
    }
}
