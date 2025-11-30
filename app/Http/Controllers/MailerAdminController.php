<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MailTemplate;
use App\Models\Recipient;
use App\Models\ContactList;
use App\Models\MailLog;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewsletterMail;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\RecipientsImport;
use Illuminate\Support\Facades\Artisan;

class MailerAdminController extends Controller
{
    public function dashboard() {
    $templates = \App\Models\MailTemplate::count();
    $recipients = \App\Models\Recipient::count();
    $lists = \App\Models\ContactList::count();
    $logs = \App\Models\MailLog::count();

    return view('mailer.dashboard', compact('templates','recipients','lists','logs'));
}
    
    // ------------------ ШАБЛОНЫ ------------------
    public function templatesIndex() {
        $templates = MailTemplate::all();
        return view('mailer.templates.index', compact('templates'));
    }

    public function templatesCreate() {
        return view('mailer.templates.create');
    }

    public function templatesStore(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'body' => 'required|string'
        ]);

        MailTemplate::create($request->all());
        return redirect()->route('mailer.templates.index')->with('success', 'Шаблон создан');
    }

    public function templatesEdit(MailTemplate $template) {
        return view('mailer.templates.edit', compact('template'));
    }

    public function templatesUpdate(Request $request, MailTemplate $template) {
        $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'body' => 'required|string'
        ]);

        $template->update($request->all());
        return redirect()->route('mailer.templates.index')->with('success', 'Шаблон обновлен');
    }

    public function templatesDestroy(MailTemplate $template) {
        $template->delete();
        return redirect()->route('mailer.templates.index')->with('success', 'Шаблон удален');
    }

    // ------------------ КОНТАКТЫ ------------------
    public function recipientsIndex() {
        $recipients = Recipient::all();
        return view('mailer.recipients.index', compact('recipients'));
    }

    public function recipientsCreate() {
        return view('mailer.recipients.create');
    }

    public function recipientsStore(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:recipients,email'
        ]);

        Recipient::create($request->all());
        return redirect()->route('mailer.recipients.index')->with('success', 'Контакт добавлен');
    }

    public function recipientsEdit(Recipient $recipient) {
        return view('mailer.recipients.edit', compact('recipient'));
    }

    public function recipientsUpdate(Request $request, Recipient $recipient) {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:recipients,email,' . $recipient->id
        ]);

        $recipient->update($request->all());
        return redirect()->route('mailer.recipients.index')->with('success', 'Контакт обновлен');
    }

    public function recipientsDestroy(Recipient $recipient) {
        $recipient->delete();
        return redirect()->route('mailer.recipients.index')->with('success', 'Контакт удален');
    }

    public function recipientsImportForm() {
        return view('mailer.recipients.import');
    }

    public function recipientsImport(Request $request)
{
    $request->validate(['file' => 'required|file|mimes:csv,xlsx']);

    $import = new \App\Imports\RecipientsImport;
    \Maatwebsite\Excel\Facades\Excel::import($import, $request->file('file'));

    return back()->with('success', sprintf(
        'Импорт завершён! Добавлено: %d, пропущено: %d.',
        $import->imported,
        $import->skipped
    ));
}

    // ------------------ СПИСКИ ------------------
    public function listsIndex() {
        $lists = ContactList::all();
        return view('mailer.contact_lists.index', compact('lists'));
    }

    public function listsCreate() {
        $recipients = Recipient::all();
        return view('mailer.contact_lists.create', compact('recipients'));
    }

    public function listsStore(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'recipients' => 'array'
        ]);

        $list = ContactList::create(['name' => $request->name]);
        if ($request->has('recipients')) {
            $list->recipients()->sync($request->recipients);
        }

        return redirect()->route('mailer.lists.index')->with('success', 'Список создан');
    }

    public function listsEdit(ContactList $list) {
        $recipients = Recipient::all();
        return view('mailer.contact_lists.edit', compact('list', 'recipients'));
    }

    public function listsUpdate(Request $request, ContactList $list) {
        $request->validate([
            'name' => 'required|string|max:255',
            'recipients' => 'array'
        ]);

        $list->update(['name' => $request->name]);
        $list->recipients()->sync($request->recipients);
        return redirect()->route('mailer.lists.index')->with('success', 'Список обновлен');
    }

    public function listsDestroy(ContactList $list) {
        $list->delete();
        return redirect()->route('mailer.lists.index')->with('success', 'Список удален');
    }

    // ------------------ РАССЫЛКИ ------------------
    public function sendForm() {
        $templates = MailTemplate::all();
        $lists = ContactList::all();
        return view('mailer.newsletter.send', compact('templates', 'lists'));
    }

    public function send(Request $request) {
        $request->validate([
            'template_id' => 'required|exists:mail_templates,id',
            'list_id' => 'required|exists:contact_lists,id'
        ]);

        $template = MailTemplate::findOrFail($request->template_id);
        $list = ContactList::findOrFail($request->list_id);

        foreach ($list->recipients as $recipient) {
            $log = MailLog::create([
                'template_id' => $template->id,
                'recipient_id' => $recipient->id,
                'status' => 'pending'
            ]);

            Mail::to($recipient->email)->queue(new NewsletterMail($log, $template->subject, $template->body));
        }


// После постановки задач в очередь — запускаем воркер
    Artisan::call('queue:work', [
        '--stop-when-empty' => true,
    ]);


        return redirect()->back()->with('success', 'Рассылка запущена!');
    }

   public function history(Request $request)
{
    $status = $request->get('status'); // фильтр по статусу

    $query = \App\Models\MailLog::with(['recipient', 'template'])->latest();

    if ($status && in_array($status, ['pending', 'sent', 'read', 'failed'])) {
        $query->where('status', $status);
    }

    $logs = $query->paginate(20)->appends(['status' => $status]);

    $sentCount  = \App\Models\MailLog::where('status', 'sent')->count();
    $readCount  = \App\Models\MailLog::where('status', 'read')->count();
    $pendingCount = \App\Models\MailLog::where('status', 'pending')->count();
    $failedCount  = \App\Models\MailLog::where('status', 'failed')->count();
    $totalCount = \App\Models\MailLog::count();

    $openRate = $totalCount > 0 ? round($readCount / $totalCount * 100, 1) : 0;

    return view('mailer.newsletter.history', compact(
        'logs', 'sentCount', 'readCount', 'failedCount', 'pendingCount', 'openRate', 'totalCount', 'status'
    ));
}



    public function trackEmail($logId) {
        $log = MailLog::find($logId);
        if ($log && !$log->read_at) {
            $log->update(['status' => 'read', 'read_at' => now()]);
        }
        return response()->file(public_path('1x1.png'), ['Content-Type' => 'image/png']);
    }
}
