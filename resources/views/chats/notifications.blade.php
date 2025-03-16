@extends('template')
@section('title_page', __('chats.notifications_title'))
@section('main')
    <style>
        

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
            font-size: 1.5rem;
            color: #d7fc09;
            margin: 20px;
        }
    </style>
    <div class="container">
        @if ($uniqueChats->isEmpty())
            <h1 class="no-notifications">{{ __('chats.no_notifications') }}</h1>
        @else
            <h1>{{ __('chats.notifications_title') }}</h1>
            <ul class="list-group">
                @foreach ($uniqueChats as $uniqueChat)
                    <li class="list-group-item">
                        <p>{{ __('chats.unread_messages_in_chat') }} 
                            <a href="{{ route('chats.show', $uniqueChat['chat']->id) }}" class="chat-link">
                                {{ $uniqueChat['chat']->getChatNameForUser(auth()->id()) }}
                            </a>
                            @if ($uniqueChat['unread_count'] > 0)
                                <span class="unread-count">
                                    {{ $uniqueChat['unread_count'] }}
                                </span>
                            @endif
                        </p>
                        <a href="{{ route('chats.show', $uniqueChat['chat']->id) }}" class="des-btn">
                            {{ __('chats.read') }}
                        </a>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
@endsection