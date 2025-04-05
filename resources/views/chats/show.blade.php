@extends('template')
@section('title_page', __('chats.chat'))
@section('main')
    <!-- Стили остаются без изменений -->
    <style>
        /* Все стили из предыдущей версии 
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }*/

        .chat-messages {
            height: 500px;
            overflow-y: auto;
            border: 1px solid gold;
            padding: 15px;
            margin-bottom: 20px;
            background-color: #1a1a1a;
            border-radius: 8px;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .message {
            max-width: 80%;
            padding: 5px;
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
            margin: 0;
            padding: 8px 8px;
            border-radius: 18px;
            display: inline-block;
            max-width: 100%;
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
            margin-bottom: 5px;
            font-size: 0.95rem;
        }

        .card-title small {
            color: #aaa;
            font-size: 0.85rem;
            margin-left: 8px;
        }

        .card-text {
            color: #fff;
            word-break: break-word;
            font-size: 1.05rem;
            line-height: 1.4;
        }

        .input-group {
            display: flex;
            gap: 12px;
            align-items: flex-end;
        }

        .input-wrapper {
            flex: 1;
            position: relative;
        }

        #messageInput {
            width: 100%;
            min-height: 50px;
            max-height: 150px;
            padding: 12px 18px;
            background-color: #1a1a1a;
            color: #ffffff;
            border: 1px solid gold;
            border-radius: 25px;
            resize: none;
            outline: none;
            font-size: 1.1rem;
            line-height: 1.5;
            transition: border 0.3s ease;
        }

        .send-btn {
            height: 50px;
            padding: 0 20px;
            background: gold;
            color: #0b0c18;
            border: none;
            border-radius: 25px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.2s;
        }

        .send-btn:hover {
            background: gold;
            transform: scale(1.05);
            box-shadow: 0 0 10px rgba(255, 215, 0, 0.5);
        }

        .additional-buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
            gap: 10px;
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
        }

        .des-btn:hover {
            background: gold;
            color: #0b0c18;
        }

        .chat-messages::-webkit-scrollbar {
            width: 8px;
        }

        .chat-messages::-webkit-scrollbar-track {
            background: #1a1a1a;
        }

        .chat-messages::-webkit-scrollbar-thumb {
            background: #444;
            border-radius: 4px;
        }

        .chat-messages::-webkit-scrollbar-thumb:hover {
            background: #555;
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

        .message {
            animation: fadeIn 0.3s ease;
        }
    </style>

    <div class="container">
        <h1 style="color: gold; text-align: center; margin-bottom: 20px;">
            {{ $chat->getChatNameForUser(auth()->id()) }}
        </h1>

        <div id="chat-messages" class="chat-messages">
            @foreach ($chat->messages as $message)
                <div class="message {{ $message->sender_id === auth()->id() ? 'sent' : 'received' }}">
                    <div class="{{ $message->sender_id === auth()->id() ? 'my-card-body' : 'card-body' }}">
                        <p class="card-title">{{ $message->sender->name }}
                            <small>{{ $message->created_at->format('H:i, d M') }}</small>
                        </p>
                        <p class="card-text">
                            {!! nl2br(e(str_replace('\\n', "\n", $message->message))) !!}
                        </p>
                    </div>
                </div>
            @endforeach
        </div>

        <form method="POST" action="{{ route('messages.send', $chat->id) }}" id="messageForm">
            @csrf
            <div class="input-group">
                <div class="input-wrapper">
                    <textarea name="message" id="messageInput" rows="1" placeholder="{{ __('chats.type_message') }}" autofocus
                        required></textarea>
                </div>
                <button type="submit" class="send-btn">{{ __('chats.send') }}</button>
            </div>
        </form>

        <div class="additional-buttons">
            <a href="/chats" class="des-btn">{{ __('chats.to_chats') }}</a>
            <a href="/chats/create" class="des-btn">{{ __('chats.new_chat') }}</a>
            <a href="/notifications" class="des-btn">{{ __('chats.notifications') }}</a>
        </div>
    </div>

    <!-- Аудио элемент для уведомлений (скрытый) -->
    <audio id="notificationSound" preload="auto">
        <source src="/sounds/notification.mp3" type="audio/mpeg">
    </audio>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const chatForm = document.getElementById('messageForm');
            const messageInput = document.getElementById('messageInput');
            const chatMessages = document.getElementById('chat-messages');
            const sendBtn = document.querySelector('.send-btn');
            const notificationSound = document.getElementById('notificationSound');
            const chatId = {{ $chat->id }};
            const userId = {{ auth()->id() }};
            let lastMessageId = {{ $chat->messages->last()?->id ?? 0 }};

            // Прокрутка вниз при загрузке
            scrollToBottom();

            function scrollToBottom() {
                chatMessages.scrollTop = chatMessages.scrollHeight;
            }

            // Автоматическое изменение высоты textarea
            messageInput.addEventListener('input', function() {
                this.style.height = 'auto';
                this.style.height = `${Math.min(this.scrollHeight, 150)}px`;
            });

            // Обработка нажатия Enter
            messageInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    chatForm.dispatchEvent(new Event('submit'));
                }
            });

            // Функция загрузки новых сообщений
            function loadNewMessages() {
    fetch(`/chats/${chatId}/messages?last_id=${lastMessageId}`, {
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        credentials: 'include' // Важно для передачи куки
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`Ошибка HTTP! Статус: ${response.status}`);
        }
        return response.json();
    })
    .then(messages => {
        if (messages && messages.length > 0) {
            const wasScrolledToBottom = 
                chatMessages.scrollTop + chatMessages.clientHeight >= chatMessages.scrollHeight - 50;
            
            let hasNewMessages = false;
            
            messages.forEach(message => {
                if (message && message.sender && message.message) {
                    if (message.id > lastMessageId) {
                        hasNewMessages = true;
                        lastMessageId = message.id;
                    }
                    
                    const isSent = message.sender.id === userId;
                    const messageHTML = `
                        <div class="message ${isSent ? 'sent' : 'received'}">
                            <div class="${isSent ? 'my-card-body' : 'card-body'}">
                                <p class="card-title">${message.sender.name}
                                    <small>${new Date(message.created_at).toLocaleTimeString()}</small>
                                </p>
                                <p class="card-text">${message.message.replace(/\n/g, '<br>')}</p>
                            </div>
                        </div>
                    `;
                    chatMessages.insertAdjacentHTML('beforeend', messageHTML);
                }
            });
            
            if (hasNewMessages) {
                if (!wasScrolledToBottom && messages.some(m => m.sender.id !== userId)) {
                    notificationSound.play().catch(e => console.log('Ошибка воспроизведения звука:', e));
                }
                if (wasScrolledToBottom) {
                    scrollToBottom();
                }
            }
        }
    })
    .catch(error => {
        console.error('Ошибка загрузки сообщений:', error);
        // Можно добавить повторный запрос через некоторое время
        setTimeout(loadNewMessages, 5000);
    });
}

            // Отправка формы с автообновлением
            chatForm.addEventListener('submit', function(e) {
    e.preventDefault();
    const message = messageInput.value.trim();

    if (message) {
        sendBtn.disabled = true;
        sendBtn.textContent = 'Отправка...';

        fetch(this.action, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                message: message.replace(/\n/g, '\\n')
            }),
            credentials: 'include'
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Ошибка сети');
            }
            return response.json();
        })
        .then(data => {
            if (data.status === 'success') {
                messageInput.value = '';
                messageInput.style.height = 'auto';
                // Добавляем новое сообщение в чат без перезагрузки
                const newMessage = data.message;
                const messageHTML = `
                    <div class="message sent">
                        <div class="my-card-body">
                            <p class="card-title">${newMessage.sender}
                                <small>${new Date().toLocaleTimeString()}</small>
                            </p>
                            <p class="card-text">${newMessage.text.replace(/\n/g, '<br>')}</p>
                        </div>
                    </div>
                `;
                chatMessages.insertAdjacentHTML('beforeend', messageHTML);
                scrollToBottom();
            }
        })
        .catch(error => {
            console.error('Ошибка:', error);
            alert('Ошибка при отправке сообщения');
        })
        .finally(() => {
            sendBtn.disabled = false;
            sendBtn.textContent = '{{ __('chats.send') }}';
        });
    }
});

            // Автоматическое обновление каждые 3 секунды
            setInterval(loadNewMessages, 3000);

            // Фокус на поле ввода
            messageInput.focus();
        });
    </script>
@endsection