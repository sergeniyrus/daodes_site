@extends('template')

@section('title_page', __('chats.your_chats'))

@section('main')

{{-- @push('styles')
    @vite('resources/css/chat_index.css')
@endpush --}}


<style>

/* =========================================================
   КОНТЕЙНЕР ЧАТОВ
   ========================================================= */

.chats-page-container {
    width: min(100%, 1100px);

    margin: 30px auto 40px;

    padding: 28px;

    box-sizing: border-box;

    background:
        radial-gradient(
            circle at top right,
            rgba(255, 215, 0, 0.08),
            transparent 35%
        ),
        radial-gradient(
            circle at bottom left,
            rgba(0, 220, 255, 0.06),
            transparent 35%
        ),
        linear-gradient(
            145deg,
            #0b0c18 0%,
            #111323 50%,
            #0b0c18 100%
        );

    border: 1px solid rgba(255, 215, 0, 0.75);

    border-radius: 22px;

    color: #fff;

    box-shadow:
        0 10px 35px rgba(0, 0, 0, 0.65),
        0 0 25px rgba(255, 215, 0, 0.08);

    overflow: hidden;
}


/* =========================================================
   ЗАГОЛОВОК
   ========================================================= */

.chats-page-title {
    margin: 0 0 25px;

    text-align: center;

    color: gold;

    font-size: 2rem;

    font-weight: 700;

    letter-spacing: 0.5px;

    text-shadow:
        0 0 8px rgba(255, 215, 0, 0.35),
        0 0 18px rgba(255, 215, 0, 0.12);
}


.chats-section-title {
    margin: 25px 0 15px;

    color: gold;

    text-align: center;

    font-size: 1.25rem;

    font-weight: 700;
}


/* =========================================================
   ТАБЛИЦА
   ========================================================= */

.chat-table {
    width: 100%;

    border-collapse: separate;

    border-spacing: 0;

    overflow: hidden;

    background:
        linear-gradient(
            145deg,
            #111218,
            #181a22
        );

    border: 1px solid rgba(255, 215, 0, 0.40);

    border-radius: 16px;

    box-shadow:
        0 7px 20px rgba(0, 0, 0, 0.45),
        inset 0 0 20px rgba(0, 0, 0, 0.2);
}


/* =========================================================
   ЗАГОЛОВКИ ТАБЛИЦЫ
   ========================================================= */

.chat-table thead th {
    padding: 14px 16px;

    color: gold;

    font-size: 0.95rem;

    font-weight: 700;

    text-align: left;

    background:
        linear-gradient(
            90deg,
            rgba(255, 215, 0, 0.08),
            rgba(0, 229, 255, 0.04)
        );

    border-bottom: 1px solid rgba(255, 215, 0, 0.25);
}


/* =========================================================
   ЯЧЕЙКИ
   ========================================================= */

.chat-table tbody td {
    padding: 14px 16px;

    color: #f5f5f5;

    font-size: 1rem;

    border-bottom: 1px solid rgba(255, 255, 255, 0.07);

    vertical-align: middle;
}


.chat-table tbody tr:last-child td {
    border-bottom: none;
}


.chat-table tbody tr {
    transition:
        background 0.2s ease;
}


.chat-table tbody tr:hover {
    background:
        linear-gradient(
            90deg,
            rgba(0, 229, 255, 0.07),
            rgba(255, 215, 0, 0.05)
        );
}


/* =========================================================
   НАЗВАНИЕ ЧАТА + СЧЁТЧИК
   ========================================================= */

.chat-name-cell {
    width: 55%;
}


.chat-name-wrapper {
    display: flex;

    align-items: center;

    gap: 10px;

    min-width: 0;
}


.chat-name-link {
    display: inline-block;

    min-width: 0;

    color: #00e5ff;

    font-size: 1.05rem;

    font-weight: 700;

    text-decoration: none;

    white-space: nowrap;

    overflow: hidden;

    text-overflow: ellipsis;

    transition:
        color 0.2s ease,
        text-shadow 0.2s ease;
}


.chat-name-link:hover {
    color: gold;

    text-shadow:
        0 0 10px rgba(255, 215, 0, 0.35);
}


/* =========================================================
   СЧЁТЧИК СООБЩЕНИЙ
   ========================================================= */

.chat-message-count {
    display: inline-flex;

    align-items: center;

    justify-content: center;

    flex-shrink: 0;

    min-width: 30px;

    height: 27px;

    padding: 0 9px;

    box-sizing: border-box;

    color: #0b0c18;

    background:
        linear-gradient(
            135deg,
            #fff176,
            gold
        );

    border: 1px solid #ffe45c;

    border-radius: 15px;

    font-size: 0.82rem;

    font-weight: 800;

    line-height: 1;

    box-shadow:
        0 0 10px rgba(255, 215, 0, 0.25);
}


/* =========================================================
   УЧАСТНИКИ
   ========================================================= */

.chat-participants {
    color: #c8c8c8;

    line-height: 1.5;
}


/* =========================================================
   BADGE
   ========================================================= */

.badge {
    display: inline-flex;

    align-items: center;

    justify-content: center;

    min-width: 28px;

    height: 26px;

    padding: 0 8px;

    box-sizing: border-box;

    color: #0b0c18;

    background:
        linear-gradient(
            135deg,
            #fff176,
            gold
        );

    border-radius: 14px;

    font-size: 0.82rem;

    font-weight: 800;
}


/* =========================================================
   КНОПКА СОЗДАНИЯ
   ========================================================= */

.chat-create-wrapper {
    display: flex;

    justify-content: center;

    margin-top: 25px;
}


.des-btn {
    display: inline-flex;

    align-items: center;

    justify-content: center;

    min-height: 48px;

    padding: 10px 24px;

    box-sizing: border-box;

    color: #0b0c18;

    background:
        linear-gradient(
            135deg,
            #ffd700,
            #ffb300,
            #ff9800
        );

    border: 1px solid #ffe45c;

    border-radius: 25px;

    font-size: 1rem;

    font-weight: 800;

    text-decoration: none;

    box-shadow:
        0 5px 18px rgba(255, 215, 0, 0.20);

    transition:
        transform 0.25s ease,
        box-shadow 0.25s ease,
        filter 0.25s ease;
}


.des-btn:hover {
    color: #000;

    filter: brightness(1.1);

    transform: translateY(-2px);

    box-shadow:
        0 8px 25px rgba(255, 215, 0, 0.45),
        0 0 18px rgba(255, 215, 0, 0.25);
}


/* =========================================================
   ПАГИНАЦИЯ
   ========================================================= */

.chats-pagination {
    display: flex;

    justify-content: center;

    margin-top: 18px;
}


/* =========================================================
   МОБИЛЬНЫЕ
   ========================================================= */

@media (max-width: 700px) {

    .chats-page-container {
        width: auto;

        margin: 15px 10px 30px;

        padding: 18px;

        border-radius: 17px;
    }


    .chats-page-title {
        font-size: 1.55rem;

        margin-bottom: 20px;
    }


    .chats-section-title {
        font-size: 1.1rem;

        margin-top: 22px;
    }


    .chat-table {
        display: table;

        width: 100%;

        table-layout: fixed;
    }


    .chat-table thead th,
    .chat-table tbody td {
        padding: 11px 9px;

        font-size: 0.9rem;
    }


    .chat-name-cell {
        width: 55%;
    }


    .chat-name-wrapper {
        gap: 6px;
    }


    .chat-name-link {
        font-size: 0.95rem;
    }


    .chat-message-count {
        min-width: 25px;

        height: 23px;

        padding: 0 6px;

        font-size: 0.72rem;
    }


    .chat-participants {
        font-size: 0.82rem;

        word-break: break-word;
    }
}


/* =========================================================
   ОЧЕНЬ МАЛЕНЬКИЕ ЭКРАНЫ
   ========================================================= */

@media (max-width: 420px) {

    .chats-page-container {
        margin-left: 6px;

        margin-right: 6px;

        padding: 13px;
    }


    .chat-table thead th,
    .chat-table tbody td {
        padding: 9px 6px;

        font-size: 0.82rem;
    }


    .chat-name-link {
        font-size: 0.88rem;
    }


    .chat-message-count {
        min-width: 23px;

        height: 21px;

        padding: 0 5px;

        font-size: 0.68rem;
    }


    .chat-participants {
        font-size: 0.75rem;
    }
}


/* =========================================================
   ПУСТАЯ ЯЧЕЙКА СЧЁТЧИКА
   ========================================================= */

/* .chat-messages-placeholder {
    min-width: 70px;
} */


/* =========================================================
   УВЕЛИЧИВАЕМ ВЕРТИКАЛЬНЫЙ ИНТЕРВАЛ МЕЖДУ ЧАТАМИ
   ========================================================= */

.chat-table tbody tr td {
    padding-top: 18px;
    padding-bottom: 18px;
    border: #ff980 solid 5px;
}

</style>


<div class="container chats-page">

        {{-- =====================================================
             ЗАГОЛОВОК
             ===================================================== --}}

        <h1 class="big text-center chats-page-title">
            DESChat
        </h1>


        {{-- =====================================================
             ГРУППОВЫЕ ЧАТЫ
             ===================================================== --}}

        <h2 class="text-center chats-section-title">
            {{ __('chats.group_chats') }}
        </h2>


        <table class="chat-table">

            <thead>
                <tr>
                    <th>
                        {{ __('chats.chat_name') }}
                    </th>

                    <th>
                        {{ __('chats.messages_count') }}
                    </th>

                    <th>
                        {{ __('chats.participants') }}
                    </th>
                </tr>
            </thead>


            <tbody>

                @foreach ($groupChats as $chat)

                    <tr class="chat-row">

                        {{-- =================================================
                             НАЗВАНИЕ + СЧЁТЧИК
                             ================================================= --}}

                        <td class="chat-name-cell">

                            <div class="chat-name-line">

                                <a
                                    href="{{ route('chats.show', $chat->id) }}"
                                    class="chat-name-link"
                                >
                                    {{ $chat->getChatNameForUser(auth()->id()) }}
                                </a>


                                {{-- Счётчик показываем только если > 0 --}}

                                @if (($uniqueChats[$chat->id] ?? 0) > 0)

                                    <span class="badge chat-message-badge">
                                        {{ $uniqueChats[$chat->id] }}
                                    </span>

                                @endif

                            </div>

                        </td>


                        {{-- =================================================
                             СЧЁТЧИК ДЛЯ ДЕСКТОПНОЙ ВЕРСИИ
                             На мобильном скрывается CSS
                             ================================================= --}}

                        {{-- <td class="chat-count-cell">

                            @if (($uniqueChats[$chat->id] ?? 0) > 0)

                                <span class="badge">
                                    {{ $uniqueChats[$chat->id] }}
                                </span>

                            @endif

                        </td> --}}


                        {{-- =================================================
                             УЧАСТНИКИ
                             ================================================= --}}

                        <td class="chat-participants-cell">

                            <div class="chat-participants">

                                @foreach ($chat->participants as $participant)

                                    <span class="chat-participant">
                                        {{ $participant->name }}
                                    </span>

                                    @if (!$loop->last)
                                        <span class="chat-participant-separator">,</span>
                                    @endif

                                @endforeach

                            </div>

                        </td>

                    </tr>

                @endforeach

            </tbody>

        </table>


        {{-- =====================================================
             ПАГИНАЦИЯ ГРУППОВЫХ ЧАТОВ
             ===================================================== --}}

        <div class="pagination chat-pagination">

            {{ $groupChats->links() }}

        </div>



        {{-- =====================================================
             ЛИЧНЫЕ СООБЩЕНИЯ
             ===================================================== --}}

        <table class="chat-table private-chat-table">

            <thead>

                <tr>

                    <th>

                        <h4 class="text-center chats-private-title">
                            {{ __('chats.private_messages') }}
                        </h4>

                    </th>

                    <th>
                        {{ __('chats.messages_count') }}
                    </th>

                </tr>

            </thead>


            <tbody>

                @foreach ($privateChats as $chat)

                    <tr class="chat-row private-chat-row">

                        {{-- =================================================
                             НАЗВАНИЕ + СЧЁТЧИК
                             ================================================= --}}

                        <td class="chat-name-cell">

                            <div class="chat-name-line">

                                <a
                                    href="{{ route('chats.show', $chat->id) }}"
                                    class="chat-name-link"
                                >
                                    {{ $chat->getChatNameForUser(auth()->id()) }}
                                </a>


                                {{-- Счётчик показываем только если > 0 --}}

                                @if (($uniqueChats[$chat->id] ?? 0) > 0)

                                    <span class="badge chat-message-badge">
                                        {{ $uniqueChats[$chat->id] }}
                                    </span>

                                @endif

                            </div>

                        </td>


                        {{-- =================================================
                             СЧЁТЧИК ДЛЯ ДЕСКТОПА
                             ================================================= --}}

                        <td class="chat-count-cell">

                            @if (($uniqueChats[$chat->id] ?? 0) > 0)

                                <span class="badge">
                                    {{ $uniqueChats[$chat->id] }}
                                </span>

                            @endif

                        </td>

                    </tr>

                @endforeach

            </tbody>

        </table>


        {{-- =====================================================
             ПАГИНАЦИЯ ЛИЧНЫХ ЧАТОВ
             ===================================================== --}}

        <div class="pagination chat-pagination">

            {{ $privateChats->links() }}

        </div>



        {{-- =====================================================
             КНОПКА СОЗДАНИЯ ЧАТА
             ===================================================== --}}

        <div class="text-center chat-create-wrapper">

            <a
                href="{{ route('chats.create') }}"
                class="des-btn"
            >
                {{ __('chats.create_chat') }}
            </a>

        </div>

    </div>


    {{-- =========================================================
         JAVASCRIPT
         ========================================================= --}}

    @if (auth()->check())

        @push('scripts')

            @vite('resources/js/chat_index.js')

        @endpush

    @endif

@endsection