<!DOCTYPE html>
<html lang="ru">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <title>Offer Report</title>
    <style>
        @font-face {
            font-family: 'Firefly';
            font-style: normal;
            font-weight: normal;
            src: url(http://example.com/fonts/firefly.ttf) format('truetype');
        }
        body {
            
        }
        .container { width: 100%; padding: 20px; font-family: Firefly, DejaVu Sans, sans-serif; }
        h1, h2 { text-align: center; }
        .stats { margin: 20px 0; }
        .section { margin: 20px 0; }
        .comments, .votes { margin-top: 20px; }
        .comment, .vote { margin-bottom: 10px; }
        .date { text-align: right; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="date">Дата: {{ \Carbon\Carbon::now()->format('d.m.Y') }} г.</div>
        <h1>Решение по предложению</h1>
        <div class="section">
            <h2>{{ $offer->title }}</h2>
            {!! $offer->content !!}
        </div>
        <div class="stats">
            <h3>Результаты голосования</h3>
            <p><strong>За:</strong> {{ $za_percentage }}%</p>
            <p><strong>Против:</strong> {{ $no_percentage }}%</p>
            <p><strong>Не голосовало:</strong> {{ $vozd_percentage }}%</p>
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
        <?php 
if ($za_percentage > $no_percentage) {
    echo '<h1>Предложение принято.</h1>';
} elseif ($za_percentage < $no_percentage) {
    echo '<h1>Предложение отклонено.</h1>';
} else {
    // Обработка случая, когда проценты равны
    echo '<h1>Решение не принято, так как голоса за и против равны.</h1>';
}
?>

    </div>
</body>
</html>
