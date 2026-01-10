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
            position: relative;
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
            position: relative;
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

        .edit-actions {
            position: absolute;
            top: 4px;
            right: 6px;
            opacity: 0;
            transition: opacity 0.2s;
            z-index: 5;
        }

        .edit-actions .dots {
            cursor: pointer;
            font-size: 1.1rem;
            color: gold;
            user-select: none;
        }

        .card-title {
            color: gold;
            font-size: 0.9rem;
            margin-bottom: 5px;
            padding-right: 20px;
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
            color: #fff;
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
            transition: 0.2s;
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

        .chat-header {
            text-align: center;
            margin-bottom: 15px;
        }

        .chat-status {
            color: #aaa;
            font-size: 0.9rem;
            margin-top: 4px;
            display: flex;
            gap: 6px;
            justify-content: center;
        }

        .status-indicator {
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

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .context-menu {
            position: absolute;
            background: #2a2a2a;
            border: 1px solid gold;
            border-radius: 6px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.5);
            z-index: 1000;
            min-width: 120px;
            font-size: 0.9rem;
            padding: 4px 0;
        }

        .context-menu>div {
            padding: 8px 12px;
            color: white;
            cursor: pointer;
        }

        .context-menu>div:not(:last-child) {
            border-bottom: 1px solid #444;
        }
    </style>

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
                    data-id="{{ $message->id }}">
                    <div class="{{ $message->sender_id === auth()->id() ? 'my-card-body' : 'card-body' }}">
                        <p class="card-title">
                            {{ $message->sender->name }}
                            <small>{{ $message->created_at->format('H:i, d M') }}</small>
                        </p>
                        <p class="card-text" data-encrypted="{{ $message->full_payload }}">Загрузка...</p>
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

<script src="https://cdn.jsdelivr.net/npm/tweetnacl/nacl.min.js    "></script>
<script>
    /* ============================================================
       ======================= УТИЛИТЫ ============================
       ============================================================ */
    if (typeof b64ToU8 !== 'function') {
        function b64ToU8(b64) {
            return Uint8Array.from(atob(b64), c => c.charCodeAt(0));
        }

        function u8ToB64(u8) {
            return btoa(String.fromCharCode(...u8));
        }
    }

    /* ============================================================
       ======================= ГЛОБАЛЫ =============================
       ============================================================ */
    let CURRENT_CHAT_KEY = null; // ключ чата
    let editingMessageId = null; // id редактируемого сообщения
    let editingBackupText = null; // текст до редактирования (rollback)
    let isSending = false; // защита от дублей
    let enterPressed = false; // флаг для предотвращения дублирования при удержании Enter

    const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    const CURRENT_USER_ID = {{ auth()->id() }};

    /* ============================================================
       ==================== РАСШИФРОВКА ===========================
       ============================================================ */
    function decryptMessage(encryptedPayload) {
        if (!CURRENT_CHAT_KEY) return '[Ключ загружается...]';

        try {
            const [nonceB64, cipherB64] = encryptedPayload.split('|');
            const decrypted = nacl.secretbox.open(
                b64ToU8(cipherB64),
                b64ToU8(nonceB64),
                CURRENT_CHAT_KEY
            );
            return decrypted ?
                new TextDecoder().decode(decrypted) :
                '[Ошибка]';
        } catch {
            return '[Ошибка]';
        }
    }

    function decryptAndAttachActions() {
        if (!CURRENT_CHAT_KEY) return;

        document.querySelectorAll('.card-text[data-encrypted]').forEach(el => {
            const messageEl = el.closest('.message');
            if (!messageEl) return;

            const isOwn = messageEl.classList.contains('sent');

            if (!el.dataset.decrypted) {
                const txt = decryptMessage(el.dataset.encrypted);
                el.innerHTML = txt.replace(/\n/g, '<br>');
                el.dataset.plaintext = txt;
                el.dataset.decrypted = '1';
            }

            if (isOwn) {
                const nonceB64 = el.dataset.encrypted.split('|')[0];
                attachEditButton(messageEl, el.dataset.plaintext, nonceB64);
            }
        });
    }

    /* ============================================================
       ===================== КНОПКА ⋮ =============================
       ============================================================ */
    function attachEditButton(messageEl, plaintext, nonceB64) {
        if (messageEl.querySelector('.edit-actions')) return;

        const body = messageEl.querySelector('.my-card-body');
        if (!body) return;

        const actions = document.createElement('div');
        actions.className = 'edit-actions';
        actions.innerHTML = '<span class="dots">⋮</span>';
        body.appendChild(actions);

        actions.querySelector('.dots').onclick = e => {
            e.stopPropagation();
            createContextMenu(messageEl.dataset.id, messageEl, plaintext, nonceB64);
        };

        body.onmouseenter = () => actions.style.opacity = '1';
        body.onmouseleave = () => actions.style.opacity = '0';
    }

    /* ============================================================
       ================== КОНТЕКСТНОЕ МЕНЮ ========================
       ============================================================ */
    function createContextMenu(messageId, messageEl, text, nonceB64) {
        document.querySelectorAll('.context-menu').forEach(e => e.remove());

        const menu = document.createElement('div');
        menu.className = 'context-menu';

        const editBtn = document.createElement('div');
        editBtn.textContent = 'Редактировать';
        editBtn.onclick = () => {
            menu.remove();
            // Берём текст из HTML элемента, чтобы получить последнюю версию
            const currentTextElement = messageEl.querySelector('.card-text');
            const currentText = currentTextElement ? currentTextElement.dataset.plaintext || currentTextElement
                .innerText : text;
            startEditing(messageId, messageEl, currentText, nonceB64);
        };

        const delBtn = document.createElement('div');
        delBtn.textContent = 'Удалить';
        delBtn.style.color = '#ff6b6b';
        delBtn.onclick = () => {
            menu.remove();
            confirmDelete(messageId, messageEl);
        };

        menu.append(editBtn, delBtn);
        document.body.appendChild(menu);

        const r = messageEl.getBoundingClientRect();
        menu.style.top = (r.top + window.scrollY + 10) + 'px';
        menu.style.left = (r.right + window.scrollX - 120) + 'px';

        setTimeout(() => {
            document.addEventListener('click', e => {
                if (!menu.contains(e.target)) menu.remove();
            }, {
                once: true
            });
        }, 0);
    }

    /* ============================================================
       ==================== РЕДАКТИРОВАНИЕ ========================
       ============================================================ */
    function startEditing(messageId, messageEl, text, nonceB64) {
        cancelEditing(); // на случай если уже что-то редактируется

        editingMessageId = messageId;
        editingBackupText = text;
        window.editingNonceB64 = nonceB64;

        messageEl.classList.add('editing'); // 🔥 подсветка

        const input = document.getElementById('messageInput');
        input.value = text;
        input.focus();

        document.getElementById('sendBtn').textContent = 'Сохранить';
    }

    /* Отмена редактирования (Esc) */
    function cancelEditing() {
        if (!editingMessageId) return;

        const msgEl = document.querySelector(`.message[data-id="${editingMessageId}"]`);
        if (msgEl) msgEl.classList.remove('editing');

        editingMessageId = null;
        editingBackupText = null;
        delete window.editingNonceB64;

        document.getElementById('messageInput').value = '';
        document.getElementById('sendBtn').textContent = '{{ __('chats.send') }}';
    }

    /* ============================================================
       ======================= УДАЛЕНИЕ ===========================
       ============================================================ */
    async function confirmDelete(messageId, messageEl) {
        if (!confirm('Удалить сообщение?')) return;

        const res = await fetch(`/messages/${messageId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': CSRF_TOKEN,
                Accept: 'application/json'
            }
        });

        const data = await res.json();
        if (data.status === 'success') {
            messageEl.remove();
            if (editingMessageId == messageId) cancelEditing();
        }
    }

    /* ============================================================
       ======================= ОСНОВНОЙ ===========================
       ============================================================ */
    document.addEventListener('DOMContentLoaded', () => {

        const form = document.getElementById('messageForm');
        const input = document.getElementById('messageInput');
        const sendBtn = document.getElementById('sendBtn');
        const chatMessages = document.getElementById('chat-messages');

        const chatId = {{ $chat->id }};
        const userId = {{ auth()->id() }};
        let lastMessageId = {{ $chat->messages->last()?->id ?? 0 }};

        /* ❌ отключаем submit формы полностью */
        form.addEventListener('submit', e => {
            e.preventDefault();
            e.stopPropagation();
            return false;
        });

        const scrollToBottom = () => {
            chatMessages.scrollTop = chatMessages.scrollHeight;
        };
        scrollToBottom();

        /* ENTER + ESC */
        input.addEventListener('keydown', e => {
            if (e.key === 'Escape') {
                e.preventDefault();
                cancelEditing();
                return;
            }

            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                
                // Проверяем, не было ли уже нажатие Enter
                if (!enterPressed && !isSending) {
                    enterPressed = true;
                    sendMessageNow();
                    
                    // Сброс флага через короткий промежуток времени
                    setTimeout(() => {
                        enterPressed = false;
                    }, 300); // 300 мс должно быть достаточно для предотвращения дублирования
                }
            }
        });

        /* ========================================================
           ============== ОТПРАВКА / РЕДАКТИРОВАНИЕ ===============
           ======================================================== */
        async function sendMessageNow() {
            if (isSending) return;

            const text = input.value.trim();
            if (!text) return;

            isSending = true;
            sendBtn.disabled = true;
            input.value = '';

            let optimisticEl = null;

            try {
                const bytes = new TextEncoder().encode(text);
                let nonce, nonceB64;

                if (editingMessageId) {
                    nonceB64 = window.editingNonceB64;
                    nonce = b64ToU8(nonceB64);
                } else {
                    nonce = nacl.randomBytes(nacl.secretbox.nonceLength);
                    nonceB64 = u8ToB64(nonce);
                }

                const encrypted = nacl.secretbox(bytes, nonce, CURRENT_CHAT_KEY);

                /* ===== OPTIMISTIC UI для редактирования ===== */
                if (editingMessageId) {
                    optimisticEl = document.querySelector(
                        `.message[data-id="${editingMessageId}"] .card-text`);
                    if (optimisticEl) {
                        optimisticEl.innerHTML = text.replace(/\n/g, '<br>');
                        // Обновляем plaintext атрибут
                        optimisticEl.setAttribute('data-plaintext', text);
                    }
                }

                const res = await fetch(
                    editingMessageId ? `/messages/${editingMessageId}` : form.action, {
                        method: editingMessageId ? 'PATCH' : 'POST',
                        headers: {
                            'X-CSRF-TOKEN': CSRF_TOKEN,
                            'Content-Type': 'application/json',
                            Accept: 'application/json'
                        },
                        body: JSON.stringify({
                            message: u8ToB64(encrypted),
                            nonce: nonceB64
                        })
                    }
                );

                const data = await res.json();
                if (data.status !== 'success') throw new Error();

                /* ===== COMMIT ===== */
                if (editingMessageId) {
                    const msgEl = document.querySelector(`.message[data-id="${editingMessageId}"]`);
                    if (msgEl) {
                        msgEl.classList.remove('editing');
                        // Обновляем plaintext атрибут в HTML элементе
                        const cardTextEl = msgEl.querySelector('.card-text');
                        if (cardTextEl) {
                            cardTextEl.setAttribute('data-plaintext', text);
                        }
                        // Обновляем backup текст на текущий после успешного редактирования
                        editingBackupText = text;
                    }
                } else {
                    chatMessages.insertAdjacentHTML('beforeend', `
                <div class="message sent" data-id="${data.message.id}">
                    <div class="my-card-body">
                        <p class="card-title">
                            ${data.message.sender}
                            <small>${new Date().toLocaleTimeString()}</small>
                        </p>
                        <p class="card-text"
                           data-encrypted="${nonceB64}|${u8ToB64(encrypted)}"
                           data-decrypted="1"
                           data-plaintext="${text.replace(/"/g,'&quot;')}">
                           ${text.replace(/\n/g,'<br>')}
                        </p>
                    </div>
                </div>
            `);

                    attachEditButton(chatMessages.lastElementChild, text, nonceB64);
                    lastMessageId = data.message.id;
                    scrollToBottom();
                }

                cancelEditing();

            } catch {
                /* ===== ROLLBACK ===== */
                if (optimisticEl && editingBackupText !== null) {
                    optimisticEl.innerHTML = editingBackupText.replace(/\n/g, '<br>');
                }
                alert('Ошибка отправки');
            } finally {
                isSending = false;
                sendBtn.disabled = false;
                // Сбрасываем флаг при завершении отправки
                enterPressed = false;
            }
        }

        sendBtn.addEventListener('click', sendMessageNow);

        /* ========================================================
           ================ CHAT KEY + POLLING ====================
           ======================================================== */
        (function loadChatKey() {
            const privKeyB64 = localStorage.getItem(`userPrivateKey_${CURRENT_USER_ID}`);
            if (!privKeyB64) {
                sessionStorage.setItem('url.intended', location.href);
                location.href = '/setup-keys?new_device=1';
                return;
            }

            fetch(`/chats/${chatId}/my-key`, {
                    headers: {
                        'X-CSRF-TOKEN': CSRF_TOKEN,
                        Accept: 'application/json'
                    }
                })
                .then(r => r.json())
                .then(data => {
                    CURRENT_CHAT_KEY = nacl.box.open(
                        b64ToU8(data.encrypted_key),
                        b64ToU8(data.nonce),
                        b64ToU8(data.initiator_public_key),
                        b64ToU8(privKeyB64)
                    );
                    decryptAndAttachActions();
                    setInterval(loadNewMessages, 2000);
                });
        })();

        async function loadNewMessages() {
            const res = await fetch(`/chats/${chatId}/messages?last_id=${lastMessageId}`);
            if (!res.ok) return;

            const msgs = await res.json();
            msgs.forEach(msg => {
                if (msg.id <= lastMessageId) return;
                if (document.querySelector(`.message[data-id="${msg.id}"]`)) return;

                const isOwn = msg.sender.id === userId;

                chatMessages.insertAdjacentHTML('beforeend', `
            <div class="message ${isOwn ? 'sent' : 'received'}" data-id="${msg.id}">
                <div class="${isOwn ? 'my-card-body' : 'card-body'}">
                    <p class="card-title">
                        ${msg.sender.name}
                        <small>${new Date(msg.created_at).toLocaleTimeString()}</small>
                    </p>
                    <p class="card-text" data-encrypted="${msg.message}">
                        Загрузка...
                    </p>
                </div>
            </div>
        `);

                lastMessageId = msg.id;
            });

            decryptAndAttachActions();
            scrollToBottom();
        }
    });
</script>
@endsection
