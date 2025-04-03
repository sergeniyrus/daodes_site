@extends('template')
@section('title_page', __('chats.chat'))
@section('main')
    <style>
        /* Обновленные стили чата */
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

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
            /* white-space: pre-wrap; */
            word-break: break-word;
            font-size: 1.05rem;
            line-height: 1.4;
        }

        /* Стили формы ввода */
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
            color: #a0ff08;
            border: 1px solid gold;
            border-radius: 25px;
            resize: none;
            outline: none;
            font-size: 1.1rem;
            line-height: 1.5;
            transition: border 0.3s ease;
        }

        #messageInput:focus {
            border-color: #d7fc09;
            box-shadow: 0 0 5px rgba(215, 252, 9, 0.5);
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
            background: #ffd700;
            transform: scale(1.03);
            box-shadow: 0 0 10px rgba(255, 215, 0, 0.5);
        }

        /* Дополнительные кнопки */
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

        /* Скроллбар */
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

        /* Анимации */
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const chatForm = document.getElementById('messageForm');
            const messageInput = document.getElementById('messageInput');
            const chatMessages = document.getElementById('chat-messages');
            const sendBtn = document.querySelector('.send-btn');
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
                fetch(`/chats/${chatId}/messages?last_id=${lastMessageId}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.text().then(text => {
                            try {
                                return text ? JSON.parse(text) : [];
                            } catch {
                                return [];
                            }
                        });
                    })
                    .then(messages => {
                        if (messages && messages.length > 0) {
                            lastMessageId = messages[messages.length - 1].id;
                            messages.forEach(message => {
                                if (message && message.sender && message.message) {
                                    const isSent = message.sender.id === userId;
                                    const messageHTML = `
                                    <div class="message ${isSent ? 'sent' : 'received'}">
                                        <div class="${isSent ? 'my-card-body' : 'card-body'}">
                                            <p class="card-title">${message.sender.name}
                                                <small>${message.created_at}</small>
                                            </p>
                                            <p class="card-text">${message.message.replace(/\n/g, '<br>')}</p>
                                        </div>
                                    </div>
                                `;
                                    chatMessages.insertAdjacentHTML('beforeend', messageHTML);
                                }
                            });
                            scrollToBottom();
                        }
                    })
                    .catch(error => console.log('Ошибка загрузки сообщений:', error));
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
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .content,
                            },
                            body: JSON.stringify({
                                message: message.replace(/\n/g, '\\n')
                            }),
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            // Вместо обработки ответа просто обновляем страницу
                            window.location.reload();
                        })
                        .catch(error => {
                            console.error('Ошибка:', error);
                            alert('Ошибка при отправке сообщения');
                            sendBtn.disabled = false;
                            sendBtn.textContent = 'Отправить';
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
