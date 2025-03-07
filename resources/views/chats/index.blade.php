@extends('template')
@section('title_page', 'Ваши чаты')
@section('main')
    <style>
        .container {
            padding: 20px;
            margin: 20px auto;
            max-width: 1200px;
            background-color: #000000cf;
            border-radius: 15px;
            border: 1px solid gold;
            color: #f8f9fa;
            font-family: Verdana, Geneva, Tahoma, sans-serif;
        }

        .blue_btn {
            display: inline-block;
            color: #ffffff;
            background: #0b0c18;
            padding: 5px 10px;
            font-size: 1.3rem;
            border: 1px solid gold;
            border-radius: 10px;
            transition: box-shadow 0.3s ease, transform 0.3s ease;
            text-decoration: none;
        }

        .blue_btn:hover {
            box-shadow: 0 0 20px goldenrod;
            transform: scale(1.05);
            color: #ffffff;
        }

        .big {
            font-style: bold;
            font-size: 3rem;
        }

        .chat-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .chat-table th,
        .chat-table td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid gold;
        }

        .chat-table td:first-child {
            text-align: left;
        }

        .chat-table thead {
            border-top: 1px solid gold;
        }

        .chat-table th {
            background-color: #0b0c18;
            color: gold;
            font-size: 1.2rem;
        }

        .chat-table td {
            background-color: #000000cf;
            color: #f8f9fa;
        }

        .chat-table a {
            color: gold;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .chat-table a:hover {
            color: #ffffff;
        }

        .badge {
            background-color: gold;
            color: #0b0c18;
            padding: 5px 10px;
            border-radius: 10px;
            font-size: 0.9rem;
        }
    </style>
    <div class="container">
        <h1 class="big text-center">Ваши чаты</h1>

        <!-- Групповые чаты -->
        <h2 class="text-center" style="margin-top: 20px; color: gold;">Групповые чаты</h2>
        <table class="chat-table">
            <thead>
                <tr>
                    <th>Название чата</th>
                    <th>Кол-во сообщений</th>
                    <th>Участники</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($groupChats as $chat)
                    <tr>
                        <td>
                            <a href="{{ route('chats.show', $chat->id) }}">
                                {{ $chat->getChatNameForUser(auth()->id()) }}
                            </a>
                        </td>
                        <td>
                            <span class="badge">{{ $chat->unread_messages_count }}</span>
                        </td>
                        <td>
                            @foreach ($chat->participants as $participant)
                                {{ $participant->name }}@if (!$loop->last)
                                    ,
                                @endif
                            @endforeach
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Пагинация для групповых чатов -->
        <div class="pagination" style="margin-top: 20px; display: flex; justify-content: center;">
            {{ $groupChats->links() }}
        </div>

        <!-- Личные сообщения -->
        <h2 class="text-center" style="margin-top: 40px; color: gold;">Личные сообщения</h2>
        <table class="chat-table">
            <thead>
                <tr>
                    <th>Собеседник</th>
                    <th>Кол-во сообщений</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($privateChats as $chat)
                    <tr>
                        <td>
                            <a href="{{ route('chats.show', $chat->id) }}">
                                {{ $chat->getChatNameForUser(auth()->id()) }}
                            </a>
                        </td>
                        <td>
                            <span class="badge">{{ $chat->unread_messages_count }}</span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Пагинация для личных сообщений -->
        <div class="pagination" style="margin-top: 20px; display: flex; justify-content: center;">
            {{ $privateChats->links() }}
        </div>

        <!-- Кнопка "Создать чат" -->
        <div class="text-center" style="margin-top: 20px;">
            <a href="{{ route('chats.create') }}" class="blue_btn">Создать чат</a>
        </div>
    </div>
@endsection