@extends('template')
@section('title_page', 'Уведомления')
@section('main')
    <style>
        .container {
            padding: 20px;
            margin: 20px auto;
            max-width: 800px;
            background-color: #000000cf;
            border-radius: 15px;
            border: 1px solid gold;
            color: #f8f9fa;
            font-family: Verdana, Geneva, Tahoma, sans-serif;
        }

        h1,
        h2,
        h3,
        h4 {
            text-align: center;
        }

        p {
            text-align: justify;
            width: 100%;
        }

        .big {
            font-style: bold;
            font-size: 3rem;
        }

        .list-group {
            margin-top: 20px;
        }

        .list-group-item {
            background-color: #000000cf;
            border: 1px solid gold;
            color: #f8f9fa;
            margin-bottom: 10px;
            border-radius: 10px;
            padding: 15px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .chat-link {
            font-size: 1.2rem;
            color: gold;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .chat-link:hover {
            color: goldenrod;
            text-decoration: underline;
        }

        .btn-blue {
            display: inline-block;
            color: #ffffff;
            background: #0b0c18;
            padding: 5px 10px;
            font-size: 1rem;
            border: 1px solid gold;
            border-radius: 10px;
            transition: box-shadow 0.3s ease, transform 0.3s ease;
            text-decoration: none;
            cursor: pointer;
        }

        .btn-blue:hover {
            box-shadow: 0 0 20px goldenrod;
            transform: scale(1.05);
            color: #ffffff;
        }

        .unread-count {
            background-color: goldenrod;
            color: #000;
            padding: 2px 6px;
            border-radius: 50%;
            font-size: 0.9rem;
            margin-left: 10px;
        }

        .no-notifications {
            text-align: center;
            font-size: 1.2rem;
            color: #d7fc09;
            margin-top: 20px;
        }
    </style>
    <div class="container">
        @if ($uniqueChats->isEmpty())
            <h1 class="no-notifications">Непрочитанных сообщений нет.</h1>
        @else
            <h1>Ваши уведомления</h1>
            <ul class="list-group">
                @foreach ($uniqueChats as $uniqueChat)
                    <li class="list-group-item">
                        <p>Непрочитанные сообщения в чате: 
                            <a href="{{ route('chats.show', $uniqueChat['chat']->id) }}" class="chat-link">
                                {{ $uniqueChat['chat']->getChatNameForUser(auth()->id()) }}
                            </a>
                            <!-- Выводим количество непрочитанных сообщений -->
                            @if ($uniqueChat['unread_count'] > 0)
                                <span class="unread-count">
                                    {{ $uniqueChat['unread_count'] }}
                                </span>
                            @endif
                        </p>
                        <a href="{{ route('chats.show', $uniqueChat['chat']->id) }}" class="btn-blue">
                            Прочитать
                        </a>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
@endsection