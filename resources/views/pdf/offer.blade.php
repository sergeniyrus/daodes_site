<!DOCTYPE html>
<html lang="en">
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
        <div class="date">Date: {{ \Carbon\Carbon::now()->format('d.m.Y') }}</div>
        <h1>Offer Decision</h1>
        <div class="section">
            <h2>{{ $offer->title }}</h2>
            {!! $offer->content !!}
        </div>
        <div class="stats">
            <h3>Voting Results</h3>
            <p><strong>For:</strong> {{ $za_percentage }}%</p>
            <p><strong>Against:</strong> {{ $no_percentage }}%</p>
            <p><strong>Did not vote:</strong> {{ $vozd_percentage }}%</p>
        </div>
        <div class="section comments">
            <h3>Discussion:</h3>
            @foreach ($comments as $comment)
                <div class="comment">
                    <p><strong>{{ $comment->author }}:</strong> {!! $comment->content !!}</p>
                </div>
            @endforeach
        </div>
        <div class="section votes">
            <h3>Participants' Votes:</h3>
            @foreach ($votes as $vote)
                <div class="vote">
                    <p><strong>{{ $vote->user }}:</strong> {{ $vote->vote == 1 ? 'For' : 'Against' }}</p>
                </div>
            @endforeach
        </div>
        <?php 
if ($za_percentage > $no_percentage) {
    echo '<h1>The offer has been accepted.</h1>';
} elseif ($za_percentage < $no_percentage) {
    echo '<h1>The offer has been rejected.</h1>';
} else {
    // Handling the case where percentages are equal
    echo '<h1>No decision was made, as the votes for and against are equal.</h1>';
}
?>

    </div>
</body>
</html>