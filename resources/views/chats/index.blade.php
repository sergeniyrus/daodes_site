@extends('template')
@section('title_page', __('chats.your_chats'))
@section('main')

    
{{-- Отправляем CSS в стек 'styles' --}}
@push('styles')
    @vite('resources/css/chat_index.css')
@endpush

    <div class="container">
        <h1 class="big text-center">DESChat</h1>

        <!-- Групповые чаты -->
        <h2 class="text-center" style="margin-top: 20px; color: gold;">{{ __('chats.group_chats') }}</h2>
        <table class="chat-table">
            <thead>
                <tr>
                    <th>{{ __('chats.chat_name') }}</th>
                    <th>{{ __('chats.messages_count') }}</th>
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
                        <td>
                            <span class="badge">
                                {{ $uniqueChats[$chat->id] ?? 0 }}
                            </span>
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

        <table class="chat-table">
            <thead>
                <tr>
                    <th>
                        <h4 class="text-center" style="color: gold;">{{ __('chats.private_messages') }}</h4>
                    </th>
                    <th>{{ __('chats.messages_count') }}</th>
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
                        <td class="text-center">
                            <span class="badge">
                                {{ $uniqueChats[$chat->id] ?? 0 }}
                            </span>
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
            <a href="{{ route('chats.create') }}" class="des-btn">{{ __('chats.create_chat') }}</a>
        </div>
    </div>

    @if (auth()->check())

@push('scripts')
        @vite('resources/js/chat_index.js')
@endpush

    @endif

@endsection
