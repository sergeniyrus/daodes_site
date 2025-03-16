@extends('template')
@section('title_page', __('chats.chat'))
@section('main')
<style>
    .form-group label {
        color: #d7fc09;
        font-size: 1.2rem;
        display: block;
        margin: 10px 0;
        text-align: left;
        font-weight: bold;
    }

    .input_dark, textarea {
        background-color: #1a1a1a;
        color: #a0ff08;
        border-radius: 5px;
        width: 100%;
        padding: 12px;
        margin-top: 15px;
        transition: border 0.3s ease;
        resize: none;
        line-height: 1.5;
        font-size: 1.3rem;
    }

    .input_dark:focus, textarea:focus {
        border: 1px solid gold;
        outline: none;
    }

    .chat-messages {
        height: 400px;
        overflow-y: auto;
        border: 1px solid gold;
        padding: 10px;
        border-radius: 10px;
    }

    .card-title {
        color: gold;
    }

    .message.sent {
        text-align: right;
    }

    .message.received {
        text-align: left;
    }

    .input-group {
        display: flex;
        align-items: stretch;
        position: relative;
    }

    .input-wrapper {
        flex: 1;
        position: relative;
        display: flex;
        align-items: stretch;
    }

    .input-wrapper textarea {
        flex: 1;
        min-height: 30px;
        max-height: 200px;
        overflow-y: auto;
        line-height: 1.5;
        padding: 10px;
    }

    .send-button {
        position: absolute;
        right: 20px;
        top: 25px;
        height: 40px;
        color: #ffffff;
        background: #0b0c18;
        border: 1px solid gold;
        border-radius: 10px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background-color 0.3s ease;
    }

    .send-button:hover {
        box-shadow: 0 0 20px goldenrod;
        transform: scale(1.05);
        color: #ffffff;
    }

    .additional-buttons {
        display: flex;
        justify-content: space-between;
        margin-top: 20px;
    }

    .additional-buttons {
        /* flex: 1; */
        margin: 0 5px;
        display: flex;
        align-items: center;
        /* justify-content: center;*/
        gap: 10px; 
        
    }
    
    @media (max-width: 810px) {
    .additional-buttons .des-btn {
        font-size: 0.9rem; 
    }
}

    .card-body {
        margin: 5px;
        padding: 5px 15px;
        background: #2B2C2E;
        border: 1px solid gold;
        border-radius: 50px 50px 50px 0;
        display: inline-block;
    }

    .my-card-body {
        margin: 5px;
        padding: 5px 15px;
        background:#2B2C2E;
        border: 1px solid gold;
        border-radius: 50px 50px 0 50px;
        display: inline-block;
    }

    .message .my-card-body {
        margin-left: auto;
    }

    .footnote p {
        color: #747474;
        text-align: center;
        margin: 10px auto 20px auto;
    }
</style>

    <div class="container">
        <h1>{{ $chat->getChatNameForUser(auth()->id()) }}</h1>
        <div class="chat-messages mb-4">
            @foreach ($chat->messages as $message)
                <div class="message {{ $message->sender_id === auth()->id() ? 'sent' : 'received' }}">
                    <div class="{{ $message->sender_id === auth()->id() ? 'my-card-body' : 'card-body' }}">
                        <p class="card-title">{{ $message->sender->name }}
                            <small>{{ $message->created_at->format('H:i, d M') }}</small>
                        </p>
                        <p class="card-text">
                            {{ $message->message }}
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
        <form method="POST" action="{{ route('messages.send', $chat->id) }}" id="messageForm">
            @csrf
            <div class="input-group">
                <div class="input-wrapper">
                    <textarea name="message" id="messageInput" class="form-control input_dark" rows="1" required></textarea>
                    {{-- <button type="submit" class="des-btn send-button">{{ __('chats.send') }}</button> --}}
                </div>
            </div>
        </form>
        <div class="footnote">
            <p> {{ __('chats.footnote') }} <br> {{ __('chats.footnote2') }} </p>
        </div>
        <div class="additional-buttons">
            <a href="/chats" class="des-btn">{{ __('chats.to_chats') }}</a>
            <a href="/chats/create" class="des-btn">{{ __('chats.new_chat') }}</a>
            <a href="/notifications" class="des-btn">{{ __('chats.notifications') }}</a>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const textarea = document.getElementById('messageInput');

            // Высота одной строки (line-height + padding + border)
            const singleLineHeight = parseInt(getComputedStyle(textarea).lineHeight) +
                parseInt(getComputedStyle(textarea).paddingTop) +
                parseInt(getComputedStyle(textarea).paddingBottom) +
                parseInt(getComputedStyle(textarea).borderTopWidth) +
                parseInt(getComputedStyle(textarea).borderBottomWidth);

            // Автоматическое увеличение высоты textarea при вводе текста
            textarea.addEventListener('input', function() {
                this.style.height = singleLineHeight + 'px'; // Устанавливаем высоту одной строки
                this.style.height = Math.min(this.scrollHeight, 200) +
                    'px'; // Устанавливаем новую высоту, но не более 200px
            });

            // Отправка сообщения по нажатию Enter (без Shift)
            textarea.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault(); // Предотвращаем перенос строки
                    document.getElementById('messageForm').submit(); // Отправляем форму
                }
            });

            // Установка фокуса на поле ввода
            textarea.focus();
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const chatMessages = document.querySelector('.chat-messages');

            // Прокрутка вниз при загрузке страницы
            chatMessages.scrollTop = chatMessages.scrollHeight;

            // Прокрутка вниз при добавлении новых сообщений
            const observer = new MutationObserver(() => {
                chatMessages.scrollTop = chatMessages.scrollHeight;
            });

            observer.observe(chatMessages, {
                childList: true
            });
        });
    </script>
@endsection
