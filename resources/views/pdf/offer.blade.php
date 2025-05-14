<!DOCTYPE html>
<html lang="ru">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <title>Отчёт по предложению</title>
    <style>
        @font-face {
            font-family: 'Firefly';
            font-style: normal;
            font-weight: normal;
            src: url(http://example.com/fonts/firefly.ttf) format('truetype');
        }
        body {}
        .container { width: 100%; padding: 20px; font-family: Firefly, DejaVu Sans, sans-serif; }
        h1, h2 { text-align: center; }
        .stats, .section { margin: 20px 0; }
        .comments, .votes { margin-top: 20px; }
        .comment, .vote { margin-bottom: 10px; }
        .date { text-align: right; margin-bottom: 5px; }
        .link { text-align: right; margin-bottom: 20px; font-size: 12px; }
        .stamp {
            font-size: 24px;
            font-weight: bold;
            color: #c00;
            border: 3px solid #c00;
            padding: 10px 20px;
            display: inline-block;
            transform: rotate(-5deg);
            text-transform: uppercase;
            margin: 30px auto;
            text-align: center;
        }
        .stamp-container {
            display: flex;
            justify-content: center;
            align-items: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="date">Дата: {{ \Carbon\Carbon::now()->format('d.m.Y') }}</div>
        <div class="link">
            Ссылка на предложение:
            <a href="{{ url('/offers/' . $offer->id) }}">{{ url('/offers/' . $offer->id) }}</a>
        </div>

        <h1>Решение по предложению</h1>
        <div class="section">
            <h2>{{ $offer->title_ru }}</h2>
            {!! $offer->content_ru !!}
        </div>

        <div class="stats">
            <h3>Результаты голосования</h3>
            <p><strong>За:</strong> {{ $za_percentage }}%</p>
            <p><strong>Против:</strong> {{ $no_percentage }}%</p>
            <p><strong>Воздержались:</strong> {{ $vozd_percentage }}%</p>
        </div>

        @php
            if ($za_percentage > $no_percentage) {
                $decisionText = 'Принято';
            } elseif ($za_percentage < $no_percentage) {
                $decisionText = 'Отклонено';
            } else {
                $decisionText = 'Нет решения';
            }
        @endphp

        <div class="stamp-container">
            <div class="stamp">{{ $decisionText }}</div>
        </div>

        <div class="section comments">
            <h3>Обсуждение:</h3>
            @foreach ($comments as $comment)
                <div class="comment">
                    <p><strong>{{ $comment->author }}:</strong> {!! $comment->content !!}</p>
                </div>
            @endforeach
        </div>

        <div class="section votes">
            <h3>Голоса участников:</h3>
            @foreach ($votes as $vote)
                <div class="vote">
                    <p><strong>{{ $vote->user }}:</strong> {{ $vote->vote == 1 ? 'За' : 'Против' }}</p>
                </div>
            @endforeach
        </div>
    </div>
</body>
</html>
