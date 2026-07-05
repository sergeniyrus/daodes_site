@extends('template')

@section('title_page', __('chats.chat'))

@section('main')
    @push('styles')
        @vite('resources/css/chat_show.css')
    @endpush

    <div class="chat-container">
        <div class="chat-header">
            @if ($chat->type === 'personal' && $otherUser)
                <h2 class="chat-title">{{ $otherUser->name }}</h2>
                <p class="chat-status">
                    @if ($otherUser->isOnline())
                        <span class="status-indicator online"></span> {{ __('chats.online') }}
                    @else
                        <span class="status-indicator offline"></span> {{ $otherUser->lastSeenHuman() }}
                    @endif
                </p>
            @else
                <h2 class="chat-title">{{ $chat->name }}</h2>
                <p class="chat-status">
                    {{ __('chats.online_participants', [
                        'online' => $chat->onlineParticipantsCount(),
                        'total' => $chat->totalParticipantsCount(),
                    ]) }}
                </p>
            @endif
        </div>

        <div id="chat-messages" class="chat-messages">
    @foreach ($chat->messages as $message)
        <div class="message {{ $message->sender_id === auth()->id() ? 'sent' : 'received' }}"
            data-id="{{ $message->id }}"
            data-ipfs-cid="{{ $message->ipfs_cid }}">
            
            @if ($message->reply_to_message_id)
                <div class="reply-preview" data-reply-id="{{ $message->reply_to_message_id }}">
                    <div class="reply-indicator">↩️</div>
                    <div class="reply-content">Загрузка...</div>
                </div>
            @endif
            
            <div class="{{ $message->sender_id === auth()->id() ? 'my-card-body' : 'card-body' }}">
                <p class="card-title">
                    {{-- Для своих сообщений показываем "Вы" --}}
                    {{ $message->sender_id === auth()->id() ? 'Вы' : $message->sender->name }}
                    <small>{{ $message->created_at->format('H:i, d M') }}</small>
                    @if ($message->edited_at)
                        <small class="message-status edited">✏️</small>
                    @endif
                </p>
                <p class="card-text">Загрузка...</p>
            </div>
        </div>
    @endforeach
</div>

{{-- Превью ответа над полем ввода --}}
<div id="replyPreview" class="reply-preview-form" style="display: none;">
    <div class="reply-info">
        <span class="reply-icon">↩️</span>
        <span class="reply-to-name"></span>
        <span class="reply-to-text"></span>
    </div>
    <button type="button" id="cancelReply" class="cancel-reply">✕</button>
</div>

        <form id="messageForm" onsubmit="return false;" action="{{ route('messages.send', $chat->id) }}">
            @csrf
            <div class="input-group">
                <div class="input-wrapper">
                    <textarea id="messageInput" name="message" placeholder="{{ __('chats.type_message') }}" rows="1" required></textarea>
                </div>
                <button type="button" id="sendBtn" class="send-btn">{{ __('chats.send') }}</button>
            </div>
        </form>

        <div class="additional-buttons">
            <a href="/chats" class="chat-btn">{{ __('chats.to_chats') }}</a>
            <a href="/chats/create" class="chat-btn">{{ __('chats.new_chat') }}</a>
            <a href="/notifications" class="chat-btn">{{ __('chats.notifications') }}</a>
        </div>

        <div id="translations" data-edit="{{ __('chats.edit') }}" data-delete="{{ __('chats.delete') }}"
            style="display:none;">
        </div>
    </div>

    <audio id="notificationSound" preload="auto">
        <source src="/sounds/notification.mp3" type="audio/mpeg">
    </audio>



<div id="chat-config"
     data-chat-id="{{ $chat->id }}"
     data-user-id="{{ auth()->id() }}"
     data-first-unread="{{ $firstUnreadMessageId }}"
     data-send-url="{{ route('messages.send', $chat->id) }}"
     data-csrf="{{ csrf_token() }}"
     data-translate-edit="{{ __('chats.edit') }}"
     data-translate-delete="{{ __('chats.delete') }}"
     data-translate-reply="{{ __('chats.reply') }}"
     data-translate-send="{{ __('chats.send') }}">
</div>

    @push('scripts')
        @vite('resources/js/chat/index.js')
    @endpush

@endsection