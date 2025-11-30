<?php
// app/Http/Controllers/MailerTrackController.php

namespace App\Http\Controllers;

use App\Models\MailLog;

class MailerTrackController extends Controller
{
    public function track($logId)
    {
        $log = MailLog::find($logId);

        if ($log && !$log->read_at) {
            $log->update([
                'read_at' => now(),
                'status'  => 'read',
            ]);
        }

        \Log::info('Tracking pixel hit', [
            'logId' => $logId,
            'email' => $log->recipient->email ?? null,
        ]);

        $transparentPng = base64_decode(
            'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8Xw8AAnsB9fQmWnUAAAAASUVORK5CYII='
        );

        return response($transparentPng, 200)
            ->header('Content-Type', 'image/png')
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }
}
