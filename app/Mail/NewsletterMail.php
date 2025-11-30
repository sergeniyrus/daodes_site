<?php
// app/Mail/NewsletterMail.php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\MailLog;
use App\Services\TemplateParser;

class NewsletterMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $log;
    protected $subjectLine;
    protected $body;
    protected $recipientName;
    protected $recipientEmail;

    public function __construct(MailLog $log, $subjectLine, $body)
    {
        $this->log = $log;
        $this->subjectLine = $subjectLine;
        $this->body = $body;

        $this->recipientName  = optional($log->recipient)->name;
        $this->recipientEmail = optional($log->recipient)->email;
    }

    public function build()
    {
        // Absolute tracking pixel URL (for image)
        $trackingUrl = url(route('mailer.track', ['logId' => $this->log->id], false));
        $trackingPixel = '<img src="' . e($trackingUrl) . '" width="1" height="1" alt="" style="display:none;">';

        // Base for click redirects: will be appended with base64(url)
        $clickBase = url('/mailer/c/' . $this->log->id . '?u='); // will become ...?u=BASE64

        // Data for template replacement
        $data = [
            'name'       => $this->recipientName ?: 'Partner',
            'email'      => $this->recipientEmail ?: '',
            'tracking'   => $trackingPixel,   // use {{tracking}} in template to insert pixel
            'click_base' => $clickBase,       // TemplateParser will rewrite hrefs to use this
        ];

        // Parse template (will replace {{name}} and rewrite links to click redirects)
        $personalizedBody = TemplateParser::parse($this->body, $data);

        // Ensure pixel present if template didn't include {{tracking}}
        if (!str_contains($personalizedBody, $trackingPixel)) {
            $personalizedBody .= $trackingPixel;
        }

        // Update log status -> sent
        try {
            $this->log->update([
                'status'  => 'sent',
                'sent_at' => now(),
            ]);
        } catch (\Throwable $e) {
            \Log::error('MailLog update failed in NewsletterMail::build', [
                'log_id' => $this->log->id,
                'error'  => $e->getMessage(),
            ]);
        }

        // Pass log_id for event listeners if needed
        return $this->subject($this->subjectLine)
                    ->with(['log_id' => $this->log->id])
                    ->view('mailer.emails.dynamic', [
                        'body' => $personalizedBody,
                    ]);
    }
}
