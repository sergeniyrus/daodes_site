@extends('template')
@section('title_page', __('chats.your_chats'))
@section('main')
    <style>
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
        .text-center {
    text-align: center;
}
.chat-table td {
    text-align: center;
}
.big {
            font-style: bold;
            font-size: 3rem;
        }
    </style>
    <div class="container">
        <h1 class="big text-center">DESChat</h1>

        <!-- Групповые чаты -->
        <h2 class="text-center" style="margin-top: 20px; color: gold;">{{ __('chats.group_chats') }}</h2>
        <table class="chat-table">
            <thead>
                <tr>
                    <th>{{ __('chats.chat_name') }}</th>
                    {{-- <th>{{ __('chats.messages_count') }}</th> --}}
                    <th>{{ __('chats.participants') }}</th>
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
                        {{-- <td>
                            <span class="badge">{{ $chat->unread_messages_count }}</span>
                        </td> --}}
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
        
        <table class="chat-table">
            <thead>
                <tr>
                    <th><h4 class="text-center" style="color: gold;">{{ __('chats.private_messages') }}</h4></th>
                    {{-- <th>{{ __('chats.messages_count') }}</th> --}}
                </tr>
            </thead>
            <tbody>
                @foreach ($privateChats as $chat)
                    <tr>
                        <td class="text-center">
                            <a href="{{ route('chats.show', $chat->id) }}">
                                {{ $chat->getChatNameForUser(auth()->id()) }}
                            </a>
                        </td>
                        {{-- <td class="text-center">
                            <span class="badge">{{ $chat->unread_messages_count }}</span>
                        </td> --}}
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
            <a href="{{ route('chats.create') }}" class="des-btn">{{ __('chats.create_chat') }}</a>
        </div>
    </div>
@endsection