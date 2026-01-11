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

        .message-status {
            display: inline-block;
            font-size: 0.8rem;
            margin-left: 5px;
            color: #aaa;
        }

        .message-status.edited {
            color: gold;
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
                            @if ($message->edited_at)
                                <small class="message-status edited">✏️</small>
                            @endif
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

        <div id="translations" data-edit="{{ __('chats.edit') }}" data-delete="{{ __('chats.delete') }}"
            style="display:none;">
        </div>
    </div>

    <audio id="notificationSound" preload="auto">
        <source src="/sounds/notification.mp3" type="audio/mpeg">
    </audio>
@endsection





@section('scripts')

    <script src="https://cdn.jsdelivr.net/npm/tweetnacl/nacl.min.js"></script>
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
        let autoScrollEnabled = true; // флаг для автопрокрутки

        const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        const CURRENT_USER_ID = {{ auth()->id() }};
        const FIRST_UNREAD_MESSAGE_ID = {{ $firstUnreadMessageId ?? 'null' }};

        const TRANSLATIONS = @json([
            'edit' => __('chats.edit'),
            'delete' => __('chats.delete'),
        ]);

        // Храним ID всех сообщений в чате для отслеживания удалений
        const allMessageIds = new Set();
        // Храним последний известный highestMessageId для определения новых сообщений
        let highestKnownMessageId = 0;

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

                    // Проверяем, было ли сообщение отредактировано
                    const isEdited = messageEl.getAttribute('data-is-edited');
                    
                    // ДОБАВЛЯЕМ карандаш ТОЛЬКО если isEdited === 'true'
                    if (isEdited === 'true') {
                        const cardTitle = messageEl.querySelector('.card-title');
                        if (cardTitle && !cardTitle.querySelector('.message-status.edited')) {
                            const editedSpan = document.createElement('small');
                            editedSpan.className = 'message-status edited';
                            editedSpan.textContent = '✏️';
                            cardTitle.appendChild(editedSpan);
                        }
                    } else if (isEdited === 'false') {
                        // Удаляем карандаш если сообщение не редактировалось
                        const cardTitle = messageEl.querySelector('.card-title');
                        if (cardTitle) {
                            const editedSpans = cardTitle.querySelectorAll('.message-status.edited');
                            editedSpans.forEach(span => span.remove());
                        }
                    }
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
            // Удаляем старые меню
            document.querySelectorAll('.context-menu').forEach(e => e.remove());

            const menu = document.createElement('div');
            menu.className = 'context-menu';

            // Кнопка "Редактировать"
            const editBtn = document.createElement('div');
            editBtn.textContent = TRANSLATIONS.edit;
            editBtn.onclick = () => {
                menu.remove();
                const currentTextElement = messageEl.querySelector('.card-text');
                const currentText = currentTextElement ?
                    currentTextElement.dataset.plaintext || currentTextElement.innerText :
                    text;
                startEditing(messageId, messageEl, currentText, nonceB64);
            };

            // Кнопка "Удалить"
            const delBtn = document.createElement('div');
            delBtn.textContent = TRANSLATIONS.delete;
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

            // Закрытие по клику вне меню
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
                allMessageIds.delete(parseInt(messageId));
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

            /* Инициализируем allMessageIds с уже загруженными сообщениями */
            document.querySelectorAll('.message').forEach(msgEl => {
                const msgId = parseInt(msgEl.dataset.id);
                allMessageIds.add(msgId);
                highestKnownMessageId = Math.max(highestKnownMessageId, msgId);
            });

            /* ❌ отключаем submit формы полностью */
            form.addEventListener('submit', e => {
                e.preventDefault();
                e.stopPropagation();
                return false;
            });

            // Функция проверки, находится ли пользователь внизу чата
            function isAtBottom() {
                const threshold = 10; // порог в пикселях
                return chatMessages.scrollHeight - chatMessages.scrollTop <= chatMessages.clientHeight + threshold;
            }

            // Функция проверки, находится ли пользователь вверху чата
            function isAtTop() {
                return chatMessages.scrollTop <= 10;
            }

            // Отключаем автопрокрутку при скролле пользователя
            chatMessages.addEventListener('scroll', () => {
                autoScrollEnabled = isAtBottom();
            });

            // Включаем автопрокрутку, если пользователь проскроллил вниз
            function enableAutoScrollIfAtBottom() {
                if (isAtBottom()) {
                    autoScrollEnabled = true;
                }
            }

            // Обновим функцию scrollToBottom
            function scrollToBottom() {
                if (autoScrollEnabled) {
                    chatMessages.scrollTop = chatMessages.scrollHeight;
                }
            }

            // Прокрутка к первому непрочитанному сообщению при загрузке
            if (FIRST_UNREAD_MESSAGE_ID) {
                setTimeout(() => {
                    const unreadMessageEl = document.querySelector(
                        `.message[data-id="${FIRST_UNREAD_MESSAGE_ID}"]`);
                    if (unreadMessageEl) {
                        unreadMessageEl.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });
                        // Отключаем автопрокрутку, если пользователь просматривает историю
                        autoScrollEnabled = false;
                    } else {
                        // Если сообщение не найдено, прокручиваем вниз
                        scrollToBottom();
                    }
                }, 100);
            } else {
                // Если нет непрочитанных сообщений, прокручиваем вниз
                setTimeout(() => {
                    scrollToBottom();
                    autoScrollEnabled = true;
                }, 100);
            }

            /* ENTER + ESC */
            input.addEventListener('keydown', e => {
                if (e.key === 'Escape') {
                    e.preventDefault();
                    cancelEditing();
                    return;
                }

                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    if (!isSending) {
                        sendMessageNow();
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
                // Не очищаем input сразу — для rollback при ошибке
                const originalInputValue = input.value;
                input.value = '';

                let optimisticEl = null;
                let originalEditedStatus = null; // Сохраняем оригинальный статус редактирования

                try {
                    const bytes = new TextEncoder().encode(text);
                    // 🔒 ВСЕГДА генерируем НОВЫЙ nonce — даже при редактировании!
                    const nonce = nacl.randomBytes(nacl.secretbox.nonceLength);
                    const nonceB64 = u8ToB64(nonce);

                    const encrypted = nacl.secretbox(bytes, nonce, CURRENT_CHAT_KEY);

                    /* ===== OPTIMISTIC UI для редактирования ===== */
                    if (editingMessageId) {
                        optimisticEl = document.querySelector(
                            `.message[data-id="${editingMessageId}"] .card-text`);
                        if (optimisticEl) {
                            optimisticEl.innerHTML = text.replace(/\n/g, '<br>');
                            // Обновляем plaintext атрибут
                            optimisticEl.setAttribute('data-plaintext', text);

                            // Сохраняем оригинальный статус перед установкой нового
                            const cardTitle = optimisticEl.closest('.my-card-body')?.querySelector('.card-title');
                            if (cardTitle) {
                                originalEditedStatus = cardTitle.querySelector('.message-status.edited');
                            }

                            // Добавляем индикатор редактирования (если ещё не стоит)
                            if (cardTitle && !cardTitle.querySelector('.message-status.edited')) {
                                const editedSpan = document.createElement('small');
                                editedSpan.className = 'message-status edited';
                                editedSpan.textContent = '✏️';
                                cardTitle.appendChild(editedSpan);
                            }
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
                    if (data.status !== 'success') throw new Error('Server error');

                    /* ===== COMMIT ===== */
                    if (editingMessageId) {
                        const msgEl = document.querySelector(`.message[data-id="${editingMessageId}"]`);
                        if (msgEl) {
                            msgEl.classList.remove('editing');
                            const cardTextEl = msgEl.querySelector('.card-text');
                            if (cardTextEl) {
                                // Обновляем зашифрованные данные в DOM
                                cardTextEl.setAttribute('data-encrypted', `${nonceB64}|${u8ToB64(encrypted)}`);
                                cardTextEl.setAttribute('data-plaintext', text);
                                // Помечаем как расшифрованное (текст уже отображается)
                                cardTextEl.dataset.decrypted = '1';
                            }
                            editingBackupText = text;

                            // Обновляем data-is-edited атрибут на true
                            msgEl.setAttribute('data-is-edited', 'true');

                            // Убеждаемся, что значок редактирования отображается
                            const cardTitle = msgEl.querySelector('.card-title');
                            if (cardTitle) {
                                // Удаляем старые значки редактирования
                                const oldEditedSpans = cardTitle.querySelectorAll('.message-status.edited');
                                oldEditedSpans.forEach(span => span.remove());

                                // Добавляем новый
                                const editedSpan = document.createElement('small');
                                editedSpan.className = 'message-status edited';
                                editedSpan.textContent = '✏️';
                                cardTitle.appendChild(editedSpan);
                            }
                        }
                    } else {
                        // НОВОЕ СООБЩЕНИЕ - не добавляем индикатор редактирования
                        // Принудительно устанавливаем false для новых сообщений
                        chatMessages.insertAdjacentHTML('beforeend', `
                            <div class="message sent" data-id="${data.message.id}" data-is-edited="false">
                                <div class="my-card-body">
                                    <p class="card-title">
                                        ${data.message.sender}
                                        <small>${new Date().toLocaleTimeString()}</small>
                                        <!-- НЕ добавляем ✏️ для новых сообщений -->
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
                        allMessageIds.add(data.message.id);
                        highestKnownMessageId = Math.max(highestKnownMessageId, data.message.id);
                        lastMessageId = Math.max(lastMessageId, data.message.id);
                        scrollToBottom();
                    }

                    cancelEditing();

                } catch (err) {
                    /* ===== ROLLBACK ===== */
                    input.value = originalInputValue; // восстанавливаем текст в поле

                    if (optimisticEl && editingBackupText !== null) {
                        optimisticEl.innerHTML = editingBackupText.replace(/\n/g, '<br>');
                        optimisticEl.setAttribute('data-plaintext', editingBackupText);

                        const cardTitle = optimisticEl.closest('.my-card-body')?.querySelector('.card-title');
                        if (cardTitle) {
                            // Удаляем все значки редактирования
                            const editedSpans = cardTitle.querySelectorAll('.message-status.edited');
                            editedSpans.forEach(span => span.remove());

                            // Восстанавливаем предыдущий статус (если сообщение было отредактировано ранее)
                            if (originalEditedStatus) {
                                cardTitle.appendChild(originalEditedStatus.cloneNode(true));
                            }
                        }
                    }

                    alert('Ошибка отправки. Попробуйте снова.');
                } finally {
                    isSending = false;
                    sendBtn.disabled = false;
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

            // Разблокировка аудио при первом взаимодействии
            let audioUnlocked = false;
            function unlockAudio() {
                if (audioUnlocked) return;
                const sound = document.getElementById('notificationSound');
                if (sound) {
                    sound.play().then(() => {
                        sound.pause();
                        sound.currentTime = 0;
                        audioUnlocked = true;
                    }).catch(() => {});
                }
            }

            // Слушаем любое взаимодействие
            ['click', 'keydown', 'touchstart'].forEach(evt => {
                document.addEventListener(evt, unlockAudio, { once: true, passive: true });
            });

            async function loadNewMessages() {
                // Запрашиваем ВСЕ сообщения, чтобы видеть обновления и удаления
                const res = await fetch(`/chats/${chatId}/messages?last_id=0`);
                if (!res.ok) return;

                const msgs = await res.json();
                const wasAtBottom = isAtBottom();

                let hasIncoming = false;
                
                // Создаем Set из ID сообщений, которые получили от сервера
                const serverMessageIds = new Set();
                
                // Сначала обрабатываем все сообщения от сервера
                msgs.forEach(msg => {
                    const msgId = msg.id;
                    serverMessageIds.add(msgId);
                    
                    const existingMessage = document.querySelector(`.message[data-id="${msgId}"]`);
                    const isOwn = msg.sender.id === userId;

                    // ИСПРАВЛЕНИЕ: поле edited_at - tinyint (0/1)
                    const isEdited = msg.is_edited === true;

                    if (existingMessage) {
                        // Сообщение уже существует - проверяем обновления
                        const currentIsEdited = existingMessage.getAttribute('data-is-edited');
                        
                        // Обновляем данные сообщения ВСЕГДА (на случай повторного редактирования)
                        const cardTextEl = existingMessage.querySelector('.card-text');
                        if (cardTextEl) {
                            // Обновляем зашифрованные данные
                            cardTextEl.setAttribute('data-encrypted', msg.message);
                            // Сбрасываем флаг расшифровки
                            delete cardTextEl.dataset.decrypted;
                        }
                        
                        // Обновляем статус редактирования
                        existingMessage.setAttribute('data-is-edited', isEdited);
                        
                        // Обновляем карандаш
                        setTimeout(() => {
                            const cardTitle = existingMessage.querySelector('.card-title');
                            if (cardTitle) {
                                // Удаляем старые значки редактирования
                                const oldEditedSpans = cardTitle.querySelectorAll('.message-status.edited');
                                oldEditedSpans.forEach(span => span.remove());
                                
                                // Добавляем новый если нужно
                                if (isEdited) {
                                    const editedSpan = document.createElement('small');
                                    editedSpan.className = 'message-status edited';
                                    editedSpan.textContent = '✏️';
                                    cardTitle.appendChild(editedSpan);
                                }
                            }
                        }, 100);
                        
                    } else {
                        // НОВОЕ сообщение - проверяем, действительно ли оно новое
                        const isNewMessage = msgId > highestKnownMessageId && !isOwn;
                        
                        if (!isOwn && isNewMessage) {
                            hasIncoming = true;
                        }
                        
                        // Новое сообщение
                        const messageHTML = `
                            <div class="message ${isOwn ? 'sent' : 'received'}" data-id="${msgId}" data-is-edited="${isEdited}">
                                <div class="${isOwn ? 'my-card-body' : 'card-body'}">
                                    <p class="card-title">
                                        ${msg.sender.name}
                                        <small>${new Date(msg.created_at).toLocaleTimeString()}</small>
                                        <!-- НЕ добавляем карандаш здесь - он будет добавлен в decryptAndAttachActions если нужно -->
                                    </p>
                                    <p class="card-text" data-encrypted="${msg.message}">
                                        Загрузка...
                                    </p>
                                </div>
                            </div>
                        `;

                        chatMessages.insertAdjacentHTML('beforeend', messageHTML);
                        allMessageIds.add(msgId);
                        highestKnownMessageId = Math.max(highestKnownMessageId, msgId);
                        lastMessageId = Math.max(lastMessageId, msgId);
                    }
                });
                
                // Теперь проверяем, какие сообщения были удалены
                // Сравниваем allMessageIds с serverMessageIds
                allMessageIds.forEach(msgId => {
                    if (!serverMessageIds.has(msgId)) {
                        // Сообщение есть у нас, но нет на сервере - значит оно удалено
                        const deletedMessage = document.querySelector(`.message[data-id="${msgId}"]`);
                        if (deletedMessage) {
                            console.log('Удаляем сообщение:', msgId);
                            deletedMessage.remove();
                            allMessageIds.delete(msgId);
                        }
                    }
                });

                // Всегда расшифровываем сообщения
                decryptAndAttachActions();

                if (wasAtBottom) {
                    scrollToBottom();
                }

                // Проигрываем звук только на действительно новые входящие сообщения
                if (hasIncoming) {
                    const sound = document.getElementById('notificationSound');
                    if (sound) {
                        sound.play().catch(() => {});
                    }
                }
            }
        });
    </script>
@endsection