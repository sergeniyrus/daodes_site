<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>@yield('title') - Админка рассылки</title>
    <style>
        body { background-color: #0b0c18; color: #fff; font-family: Arial, sans-serif; padding: 20px; }
        a { color: #00f3c3; text-decoration: none; }
        .btn { background-color: gold; color: #0b0c18; padding: 6px 12px; border-radius: 8px; font-weight: bold; margin: 2px; display:inline-block; }
        .btn:hover { background-color: #e6c200; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid gold; padding: 8px; text-align: left; }
        th { background-color: #1a1b2a; }
        tr:nth-child(even) { background-color: #141526; }
        input, select, textarea { width: 100%; background-color: #1a1b2a; color: #fff; border: 1px solid gold; border-radius: 6px; padding: 6px; }
        label { display: block; margin-bottom: 4px; color: gold; }
    </style>
</head>
<body>
    <header>
        <h1 style="color: gold;">@yield('title')</h1>
        <nav>
            <a href="{{ route('mailer.templates.index') }}">Шаблоны</a> |
            <a href="{{ route('mailer.recipients.index') }}">Контакты</a> |
            <a href="{{ route('mailer.lists.index') }}">Списки</a> |
            <a href="{{ route('mailer.send.form') }}">Отправка</a> |
            <a href="{{ route('mailer.history') }}">История</a>
        </nav>
        <hr style="border-color: gold; margin: 10px 0;">
    </header>

    @if(session('success'))
        <div style="background: #006644; padding: 10px; border-radius: 8px; margin-bottom: 10px;">
            {{ session('success') }}
        </div>
    @endif

    @yield('content')
</body>
</html>
