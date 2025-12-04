@extends('template')
@section('title_page', __('chats.your_chats'))
@section('main')
    <style>
        .chat-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .chat-table th,
        .chat-table td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid gold;
        }

        .chat-table td:first-child {
            text-align: left;
        }

        .chat-table thead {
            border-top: 1px solid gold;
        }

        .chat-table th {
            background-color: #0b0c18;
            color: gold;
            font-size: 1.2rem;
        }

        .chat-table td {
            background-color: #000000cf;
            color: #f8f9fa;
        }

        .chat-table a {
            color: gold;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .chat-table a:hover {
            color: #ffffff;
        }

        .badge {
            background-color: gold;
            color: #0b0c18;
            padding: 5px 10px;
            border-radius: 10px;
            font-size: 0.9rem;
        }
        .text-center {
    text-align: center;
}
.chat-table td {
    text-align: center;
}
.big {
            font-style: bold;
            font-size: 3rem;
        }
    </style>
    
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
                    <th><h4 class="text-center" style="color: gold;">{{ __('chats.private_messages') }}</h4></th>
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

<script>
@if(auth()->check())
(function () {

    // === Base64 ⇄ Uint8Array ===
    function b64ToU8(b64) { return Uint8Array.from(atob(b64), c => c.charCodeAt(0)); }
    function u8ToB64(u8) { return btoa(String.fromCharCode(...u8)); }

    const csrf = document.querySelector('meta[name="csrf-token"]').content;

    // === Проверяем приватный ключ ===
    let privKey = localStorage.getItem('userPrivateKey');

    if (!privKey) {
        console.log("🔑 Нет приватного ключа — генерируем новый");

        const pair = nacl.box.keyPair();
        privKey = u8ToB64(pair.secretKey);
        const pubKey = u8ToB64(pair.publicKey);

        localStorage.setItem('userPrivateKey', privKey);

        fetch('/profile/set-public-key', {
            method: 'POST',
            credentials: 'include',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf
            },
            body: JSON.stringify({ public_key: pubKey })
        });

        return;
    }

    // === Проверяем публичный ключ на сервере ===
    fetch('/profile/has-public-key', {
        method: 'GET',
        credentials: 'include',
        headers: { Accept: 'application/json' }
    })
    .then(r => r.json())
    .then(data => {

        if (data.has_public_key) {
            console.log("🟢 Публичный ключ уже есть на сервере");
            return;
        }

        console.log("🔴 Сервер не имеет публичного ключа — восстанавливаем");

        const secret = b64ToU8(privKey);

        if (secret.length !== 32) {
            console.error("❌ Приватный ключ повреждён. Удаляю…");
            localStorage.removeItem('userPrivateKey');
            return;
        }

        const pair = nacl.box.keyPair.fromSecretKey(secret);
        const pubKey = u8ToB64(pair.publicKey);

        fetch('/profile/set-public-key', {
            method: 'POST',
            credentials: 'include',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf
            },
            body: JSON.stringify({ public_key: pubKey })
        })
        .then(res => {
            if (res.ok) {
                console.log("🟢 Публичный ключ успешно восстановлен и сохранён");
            } else {
                console.error("❌ Ошибка при сохранении:", res.status);
            }
        });
    })
    .catch(err => console.error("🚨 Ошибка запроса:", err));

})();
@endif
</script>

@endsection