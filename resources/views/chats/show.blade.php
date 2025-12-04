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
    background-color: #4caf50;
    box-shadow: 0 0 6px #4caf50;
}

.status-indicator.offline {
    background-color: #777;
}
</style>

<div class="chat-container">
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
                <p class="card-text" data-encrypted="{{ $message->message }}">Загрузка...</p>
            </div>
        </div>
    @endforeach
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
<script src="https://cdn.jsdelivr.net/npm/tweetnacl/nacl.min.js"></script>
<script>
// === Утилиты ===
if (typeof b64ToU8 !== 'function') {
    function b64ToU8(b64) {
        return Uint8Array.from(atob(b64), c => c.charCodeAt(0));
    }
    function u8ToB64(u8) {
        return btoa(String.fromCharCode(...u8));
    }
}

// === Расшифровка одного сообщения ===
function decryptMessage(encryptedPayload) {
    if (!window.CURRENT_CHAT_KEY) return '[Ключ не загружен]';
    try {
        const [nonceB64, ciphertextB64] = encryptedPayload.split('|');
        if (!nonceB64 || !ciphertextB64) return '[Повреждено]';
        const nonce = b64ToU8(nonceB64);
        const ciphertext = b64ToU8(ciphertextB64);
        const decrypted = nacl.secretbox.open(ciphertext, nonce, window.CURRENT_CHAT_KEY);
        if (!decrypted) return '[Не удалось расшифровать]';
        return new TextDecoder().decode(decrypted);
    } catch (e) {
        console.error('Ошибка расшифровки:', e);
        return '[Ошибка]';
    }
}

// === Расшифровка всех сообщений ===
function decryptExistingMessages() {
    document.querySelectorAll('.card-text[data-encrypted]').forEach(el => {
        const encrypted = el.getAttribute('data-encrypted');
        el.textContent = decryptMessage(encrypted);
        el.innerHTML = el.textContent.replace(/\n/g, '<br>');
    });
}

document.addEventListener('DOMContentLoaded', () => {
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

    // === Отправка сообщения ===
    async function sendMessage() {
        const text = input.value.trim();
        if (!text || !window.CURRENT_CHAT_KEY) return;

        sendBtn.disabled = true;
        sendBtn.textContent = 'Отправка...';

        try {
            const messageBytes = new TextEncoder().encode(text);
            const nonce = nacl.randomBytes(nacl.secretbox.nonceLength);
            const encrypted = nacl.secretbox(messageBytes, nonce, window.CURRENT_CHAT_KEY);
            const encryptedB64 = u8ToB64(encrypted);
            const nonceB64 = u8ToB64(nonce);

            const response = await fetch(form.getAttribute('action'), {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ message: encryptedB64, nonce: nonceB64 })
            });

            if (!response.ok) throw new Error('Network error');
            const data = await response.json();

            if (data.status === 'success') {
                // Отображаем исходный текст
                chatMessages.insertAdjacentHTML('beforeend', `
                    <div class="message sent">
                        <div class="my-card-body">
                            <p class="card-title">${data.message.sender}<small>${new Date().toLocaleTimeString()}</small></p>
                            <p class="card-text">${text.replace(/\n/g, '<br>')}</p>
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
            alert('Ошибка: ' + err.message);
        } finally {
            sendBtn.disabled = false;
            sendBtn.textContent = '{{ __("chats.send") }}';
        }
    }

    sendBtn.addEventListener('click', sendMessage);
    input.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            sendMessage();
        }
    });
    input.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = Math.min(this.scrollHeight, 150) + 'px';
    });

    // === Загрузка новых сообщений ===
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
                const decryptedText = decryptMessage(msg.message);
                chatMessages.insertAdjacentHTML('beforeend', `
                    <div class="message ${isSent ? 'sent' : 'received'}">
                        <div class="${isSent ? 'my-card-body' : 'card-body'}">
                            <p class="card-title">${msg.sender.name}<small>${new Date(msg.created_at).toLocaleTimeString()}</small></p>
                            <p class="card-text">${decryptedText.replace(/\n/g, '<br>')}</p>
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
    decryptExistingMessages();
});

// === Статус собеседника ===
@if($chat->type === 'personal' && isset($otherUser))
async function updatePeerStatus() {
    const otherUserId = {{ $otherUser->id }};
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
setInterval(updatePeerStatus, 15_000);
@endif
</script>

<script>
@if(auth()->check() && isset($chat))
(function () {
    const chatId = {{ $chat->id }};
    const privKeyBase64 = localStorage.getItem('userPrivateKey');
    if (!privKeyBase64) {
        console.error('🔒 Приватный ключ не найден');
        return;
    }

    fetch(`/chats/${chatId}/my-key`, {
        credentials: 'include',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        }
    })
    .then(res => {
        if (!res.ok) throw new Error('Не удалось загрузить ключ чата: ' + res.status);
        return res.json();
    })
    .then(data => {
        const encryptedKey = b64ToU8(data.encrypted_key);
        const nonce = b64ToU8(data.nonce);
        const initiatorPubKey = b64ToU8(data.initiator_public_key);
        const mySecretKey = b64ToU8(privKeyBase64);

        const chatKey = nacl.box.open(encryptedKey, nonce, initiatorPubKey, mySecretKey);
        if (!chatKey) {
            throw new Error('Не удалось расшифровать чат-ключ');
        }

        window.CURRENT_CHAT_KEY = chatKey;
        console.log('✅ Чат-ключ успешно расшифрован');
        decryptExistingMessages();
    })
    .catch(err => {
        console.error('🚨 Ошибка:', err);
        alert('Невозможно получить доступ к зашифрованному чату: ' + err.message);
    });
})();
@endif
</script>
@endsection