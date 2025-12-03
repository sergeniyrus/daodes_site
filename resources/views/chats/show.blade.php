@extends('template')

@section('title_page', __('chats.chat'))

@section('main')
<style>
    .chat-container {
        max-width: 900px;
        margin: 0 auto;
        padding: 20px;
        background-color: #0b0c18;
        border-radius: 15px;
        border: 1px solid gold;
        color: #fff;
    }

    .chat-title {
        color: gold;
        text-align: center;
        margin-bottom: 15px;
        font-size: 1.5rem;
    }

    .chat-messages {
        height: 500px;
        overflow-y: auto;
        background: #1a1a1a;
        border: 1px solid gold;
        border-radius: 12px;
        padding: 15px;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .message {
        max-width: 80%;
        animation: fadeIn 0.3s ease;
    }

    .message.sent {
        align-self: flex-end;
        text-align: right;
    }

    .message.received {
        align-self: flex-start;
        text-align: left;
    }

    .card-body,
    .my-card-body {
        border-radius: 18px;
        padding: 10px 15px;
        display: inline-block;
        word-wrap: break-word;
    }

    .card-body {
        background: #2B2C2E;
        border: 1px solid #444;
        border-radius: 18px 18px 18px 0;
    }

    .my-card-body {
        background: #313335;
        border: 1px solid gold;
        border-radius: 18px 18px 0 18px;
    }

    .card-title {
        color: gold;
        font-size: 0.9rem;
        margin-bottom: 5px;
    }

    .card-title small {
        color: #aaa;
        margin-left: 8px;
    }

    .card-text {
        color: #fff;
        font-size: 1rem;
        line-height: 1.4;
    }

    .input-group {
        display: flex;
        align-items: flex-end;
        gap: 10px;
        margin-top: 15px;
    }

    .input-wrapper {
        flex: 1;
        position: relative;
    }

    #messageInput {
        width: 100%;
        min-height: 50px;
        max-height: 150px;
        background-color: #1a1a1a;
        color: #ffffff;
        border: 1px solid gold;
        border-radius: 25px;
        resize: none;
        outline: none;
        font-size: 1.05rem;
        line-height: 1.4;
        padding: 12px 18px;
        transition: border 0.3s ease;
    }

    .send-btn {
        height: 50px;
        padding: 0 25px;
        background: gold;
        color: #0b0c18;
        border: none;
        border-radius: 25px;
        font-weight: bold;
        cursor: pointer;
        transition: 0.2s;
    }

    .send-btn:hover {
        transform: scale(1.05);
        box-shadow: 0 0 10px rgba(255, 215, 0, 0.5);
    }

    .des-btn {
        flex: 1;
        text-align: center;
        padding: 10px;
        background: #0b0c18;
        color: gold;
        border: 1px solid gold;
        border-radius: 5px;
        transition: all 0.2s;
        text-decoration: none;
    }

    .des-btn:hover {
        background: gold;
        color: #0b0c18;
    }

    .additional-buttons {
        display: flex;
        gap: 10px;
        margin-top: 20px;
    }

    .chat-messages::-webkit-scrollbar {
        width: 8px;
    }

    .chat-messages::-webkit-scrollbar-thumb {
        background: #444;
        border-radius: 4px;
    }

    .chat-messages::-webkit-scrollbar-thumb:hover {
        background: #666;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

.chat-header {
    text-align: center;
    margin-bottom: 15px;
}

.chat-status {
    color: #aaa;
    font-size: 0.9rem;
    margin-top: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
}

.status-indicator {
    display: inline-block;
    width: 8px;
    height: 8px;
    border-radius: 50%;
}

.status-indicator.online {
    background-color: #4caf50; /* Зелёный */
    box-shadow: 0 0 6px #4caf50;
}

.status-indicator.offline {
    background-color: #777; /* Серый */
}

    
</style>

<div class="chat-container">
    {{-- <h2 class="chat-title">{{ $chat->getChatNameForUser(auth()->id()) }}</h2> --}}

<div class="chat-header">
    @if($chat->type === 'personal' && $otherUser)
        <h2 class="chat-title">{{ $otherUser->name }}</h2>
        <p class="chat-status">
            @if($otherUser->isOnline())
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
        'total' => $chat->totalParticipantsCount()
    ]) }}
</p>
    @endif
</div>


    <div id="chat-messages" class="chat-messages">
        @foreach ($chat->messages as $message)
            <div class="message {{ $message->sender_id === auth()->id() ? 'sent' : 'received' }}">
                <div class="{{ $message->sender_id === auth()->id() ? 'my-card-body' : 'card-body' }}">
                    <p class="card-title">
                        {{ $message->sender->name }}
                        <small>{{ $message->created_at->format('H:i, d M') }}</small>
                    </p>
                    <p class="card-text">{!! nl2br(e(str_replace('\\n', "\n", $message->message))) !!}</p>
                </div>
            </div>
        @endforeach
    </div>

    {{-- форма с защитой от обычного submit --}}
    <form id="messageForm" onsubmit="return false;" action="{{ route('messages.send', $chat->id) }}">
        @csrf
        <div class="input-group">
            <div class="input-wrapper">
                <textarea id="messageInput" name="message" placeholder="{{ __('chats.type_message') }}" rows="1" required></textarea>
            </div>
            {{-- type="button" чтобы Enter не вызывал сабмит --}}
            <button type="button" id="sendBtn" class="send-btn">{{ __('chats.send') }}</button>
        </div>
    </form>

    <div class="additional-buttons">
        <a href="/chats" class="des-btn">{{ __('chats.to_chats') }}</a>
        <a href="/chats/create" class="des-btn">{{ __('chats.new_chat') }}</a>
        <a href="/notifications" class="des-btn">{{ __('chats.notifications') }}</a>
    </div>
</div>

<audio id="notificationSound" preload="auto">
    <source src="/sounds/notification.mp3" type="audio/mpeg">
</audio>
@endsection

@section('scripts')
<script defer>
document.addEventListener('DOMContentLoaded', () => {
    console.log('Chat script initialized');

    const form = document.querySelector('#messageForm');
    const input = document.querySelector('#messageInput');
    const chatMessages = document.querySelector('#chat-messages');
    const sendBtn = document.querySelector('#sendBtn');
    const notificationSound = document.getElementById('notificationSound');

    const chatId = {{ $chat->id }};
    const userId = {{ auth()->id() }};
    let lastMessageId = {{ $chat->messages->last()?->id ?? 0 }};

    const scrollToBottom = () => chatMessages.scrollTop = chatMessages.scrollHeight;
    scrollToBottom();

    async function sendMessage() {
        const text = input.value.trim();
        if (!text) return;

        sendBtn.disabled = true;
        sendBtn.textContent = 'Отправка...';

        try {
            const response = await fetch(form.getAttribute('action'), {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ message: text })
            });

            if (!response.ok) throw new Error('Network error');
            const data = await response.json();

            if (data.status === 'success') {
                const msg = data.message;
                chatMessages.insertAdjacentHTML('beforeend', `
                    <div class="message sent">
                        <div class="my-card-body">
                            <p class="card-title">${msg.sender}<small>${new Date().toLocaleTimeString()}</small></p>
                            <p class="card-text">${msg.text.replace(/\n/g, '<br>')}</p>
                        </div>
                    </div>`);
                input.value = '';
                input.style.height = 'auto';
                scrollToBottom();
            } else {
                alert('Ошибка: ' + (data.message || 'Неизвестная ошибка'));
            }
        } catch (err) {
            console.error('Ошибка отправки:', err);
            alert('Ошибка соединения с сервером');
        } finally {
            sendBtn.disabled = false;
            sendBtn.textContent = '{{ __("chats.send") }}';
        }
    }

    // кнопка отправки
    sendBtn.addEventListener('click', sendMessage);

    // Enter = отправка (Shift+Enter — новая строка)
    input.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            sendMessage();
        }
    });

    // автоизменение высоты textarea
    input.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = Math.min(this.scrollHeight, 150) + 'px';
    });

    // подгрузка новых сообщений
    async function loadNewMessages() {
        try {
            const res = await fetch(`/chats/${chatId}/messages?last_id=${lastMessageId}`, {
                headers: { 'Accept': 'application/json' }
            });
            if (!res.ok) return;

            const msgs = await res.json();
            if (!Array.isArray(msgs) || msgs.length === 0) return;

            const wasAtBottom = chatMessages.scrollTop + chatMessages.clientHeight >= chatMessages.scrollHeight - 50;
            let gotNew = false;

            msgs.forEach(msg => {
                if (msg.id > lastMessageId) lastMessageId = msg.id;
                const isSent = msg.sender.id === userId;
                chatMessages.insertAdjacentHTML('beforeend', `
                    <div class="message ${isSent ? 'sent' : 'received'}">
                        <div class="${isSent ? 'my-card-body' : 'card-body'}">
                            <p class="card-title">${msg.sender.name}<small>${new Date(msg.created_at).toLocaleTimeString()}</small></p>
                            <p class="card-text">${msg.message.replace(/\n/g, '<br>')}</p>
                        </div>
                    </div>`);
                if (!isSent) gotNew = true;
            });

            if (gotNew) notificationSound.play().catch(() => {});
            if (wasAtBottom) scrollToBottom();

        } catch (e) {
            console.error('Ошибка при обновлении сообщений:', e);
        }
    }

    setInterval(loadNewMessages, 3000);
});

// Внутри твоего chat script
async function updatePeerStatus() {
    if ({{ $chat->type === 'personal' ? 'true' : 'false' }}) {
        const otherUserId = {{ $otherUser->id ?? 0 }};
        if (!otherUserId) return;

        const res = await fetch(`/user/${otherUserId}/status`);
        const data = await res.json();
        const statusEl = document.querySelector('.chat-status');
        if (statusEl) {
            if (data.is_online) {
                statusEl.innerHTML = '<span class="status-indicator online"></span> В сети';
            } else {
                statusEl.innerHTML = `<span class="status-indicator offline"></span> ${data.last_seen_human}`;
            }
        }
    }
}

// Обновляем раз в 15 сек
if ({{ $chat->type === 'personal' ? 'true' : 'false' }}) {
    setInterval(updatePeerStatus, 15_000);
}




</script>
@endsection
