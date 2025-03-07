@extends('template')
@section('title_page', 'Чат')
@section('main')
<style>
  .container {
            padding: 20px;
            margin: 20px auto;
            max-width: 800px;
            background-color: #000000cf;
            border-radius: 15px;
            border: 1px solid gold;
            color: #f8f9fa;
            font-family: Verdana, Geneva, Tahoma, sans-serif;
        }

        .blue_btn {
            display: inline-block;
            color: #ffffff;
            background: #0b0c18;
            padding: 5px 10px;
            font-size: 1.3rem;
            border: 1px solid gold;
            border-radius: 10px;
            transition: box-shadow 0.3s ease, transform 0.3s ease;
            text-decoration: none;
            text-align: center; /* Выравнивание текста по центру */
        }

        .blue_btn:hover {
            box-shadow: 0 0 20px goldenrod;
            transform: scale(1.05);
            color: #ffffff;
        }

        h1,
        h2,
        h3,
        h4 {
            text-align: center;
        }

        p {
            text-align: justify;
            width: 100%;
        }

        .big {
            font-style: bold;
            font-size: 3rem;
        }

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
        border: 1px solid #d7fc09;
        border-radius: 5px;
        width: 100%;
        padding: 12px;
        margin-top: 5px;
        transition: border 0.3s ease;
        resize: none; /* Отключаем ручное изменение размера */
        overflow-y: hidden; /* Скрываем вертикальный скролл */
        line-height: 1.5; /* Высота строки */
    }

    .input_dark:focus, textarea:focus {
        border: 1px solid #a0ff08;
        outline: none;
        box-shadow: 0 0 5px #d7fc09;
    }

  .chat-messages {
    height: 400px;
    overflow-y: scroll;
    border: 1px solid #ddd;
    padding: 10px;
    border-radius: 5px;
}

.card-title{
  color: gold;
}
.message {
    margin-bottom: 10px;
    padding: 10px;
    border-radius: 5px;
}

.message.sent {
    background-color: #d1e7dd;
    text-align: right;
}

.message.received {
    background-color: #f8d7da;
    text-align: left;
}

.input-group {
    display: flex;
    align-items: stretch;
}

.input-group textarea {
    flex: 1;
    margin-right: 10px;
    min-height: 50px; /* Минимальная высота одной строки */
    max-height: 200px; /* Максимальная высота */
    overflow-y: auto; /* Добавляем скролл, если текст превышает максимальную высоту */
}

.input-group .blue_btn {
    height: auto;
    align-self: stretch;
}

.additional-buttons {
    display: flex;
    justify-content: space-between;
    margin-top: 20px; /* Отступ от формы ввода */
}

.additional-buttons .blue_btn {
    flex: 1;
    margin: 0 5px;
    display: flex;
    align-items: center; /* Выравнивание текста по центру */
    justify-content: center; /* Выравнивание текста по центру */
}
</style>
<div class="container">
    <h1 class="big"> {{ $chat->getChatNameForUser(auth()->id()) }}</h1>
    <div class="chat-messages mb-4">
        @foreach ($chat->messages as $message)
            @php
                // Логируем информацию о сообщении
                Log::info('Отображение сообщения', [
                    'message_id' => $message->id,
                    'sender_id' => $message->sender_id,
                    'chat_id' => $chat->id,
                    'message_content' => $message->message,
                ]);
            @endphp
            <div class="card mb-2 {{ $message->sender_id === auth()->id() ? 'bg-primary text-white' : 'bg-light' }}">
                <div class="card-body">
                    <h5 class="card-title">{{ $message->sender->name }} 
                      <small>{{ $message->created_at->format('H:i, d M') }}</small></h5>
                    <p class="card-text">
                        {{ $message->message }} <!-- Отображаем сообщение как есть -->
                    </p>
                </div>
            </div>
        @endforeach
    </div>
    <form method="POST" action="{{ route('messages.send', $chat->id) }}" id="messageForm">
        @csrf
        <div class="input-group">
            <textarea name="message" class="form-control input_dark" rows="1" required id="messageInput"></textarea>
            <button type="submit" class="blue_btn">Отправить</button>
        </div>
    </form>
    <div class="additional-buttons">
        <a href="/chats" class="blue_btn">К чатам</a>
        <a href="/chats/create" class="blue_btn">Новый чат</a>
        <a href="/notifications" class="blue_btn">Уведомления</a>
    </div>
</div>
<script>
    // Автоматическое увеличение высоты textarea при вводе текста
    const textarea = document.getElementById('messageInput');
    textarea.addEventListener('input', function () {
        this.style.height = 'auto'; // Сброс высоты
        this.style.height = (this.scrollHeight) + 'px'; // Установка новой высоты
    });

    // Отправка сообщения по нажатию Enter (без Shift)
    textarea.addEventListener('keydown', function (e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault(); // Предотвращаем перенос строки
            document.getElementById('messageForm').submit(); // Отправляем форму
        }
    });
</script>
@endsection