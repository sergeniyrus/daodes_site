<?php

namespace App\Http\Controllers;

use App\Models\MailTemplate;
use Illuminate\Http\Request;

class MailTemplateController extends Controller
{
    public function index()
    {
        $templates = MailTemplate::latest()->paginate(20);
        return view('mailer.templates.index', compact('templates'));
    }

    public function create()
    {
        return view('mailer.templates.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'subject' => 'required',
            'body' => 'required',
        ]);

        MailTemplate::create($request->only('name', 'subject', 'body'));

        return redirect()->route('mailer.templates.index')->with('success', 'Шаблон добавлен.');
    }

    public function edit(MailTemplate $template)
{
    return view('mailer.templates.form', [
        'template' => $template,
        'mode' => 'edit', // редактирование
    ]);
}

    public function update(Request $request, MailTemplate $template)
    {
        $request->validate([
            'name' => 'required',
            'subject' => 'required',
            'body' => 'required',
        ]);

        $template->update($request->only('name', 'subject', 'body'));

        return redirect()->route('mailer.templates.index')->with('success', 'Шаблон обновлён.');
    }

    public function destroy(MailTemplate $template)
    {
        $template->delete();
        return redirect()->route('mailer.templates.index')->with('success', 'Шаблон удалён.');
    }

    public function show(MailTemplate $template)
{
    return view('mailer.templates.form', [
        'template' => $template,
        'mode' => 'view', // просмотр
    ]);
}



}
