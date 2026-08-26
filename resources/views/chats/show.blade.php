@extends('template')

@section('title_page', __('chats.chat'))

@section('main')

<style>

/* =========================================================
   ОСНОВНАЯ КАРТОЧКА ЧАТА
   ========================================================= */

.chat-container {
    position: relative;

    width: min(92%, 1100px);

    margin: 30px auto 40px;

    padding: 30px;

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
   ВЕРХНЯЯ ЛИНИЯ
   ========================================================= */

.chat-container::before {
    content: "";

    position: absolute;

    top: 0;
    left: 8%;
    right: 8%;

    height: 2px;

    background:
        linear-gradient(
            90deg,
            transparent,
            gold,
            #fff176,
            gold,
            transparent
        );

    box-shadow:
        0 0 12px rgba(255, 215, 0, 0.7);
}


/* =========================================================
   НИЖНЯЯ ЛИНИЯ
   ========================================================= */

.chat-container::after {
    content: "";

    position: absolute;

    bottom: 0;
    left: 15%;
    right: 15%;

    height: 1px;

    background:
        linear-gradient(
            90deg,
            transparent,
            rgba(0, 229, 255, 0.8),
            transparent
        );
}


/* =========================================================
   ЗАГОЛОВОК
   ========================================================= */

.chat-header {
    text-align: center;

    margin-bottom: 25px;

    padding-bottom: 20px;

    border-bottom: 1px solid rgba(255, 215, 0, 0.20);
}


.chat-title {
    margin: 0;

    color: gold;

    font-size: 2rem;

    font-weight: 700;

    letter-spacing: 0.5px;

    text-shadow:
        0 0 8px rgba(255, 215, 0, 0.35),
        0 0 18px rgba(255, 215, 0, 0.12);
}


.chat-status {
    margin: 10px 0 0;

    color: #9ca3af;

    font-size: 1rem;
}


/* =========================================================
   ИНДИКАТОРЫ ONLINE / OFFLINE
   ========================================================= */

.status-indicator {
    display: inline-block;

    width: 10px;

    height: 10px;

    margin-right: 7px;

    border-radius: 50%;

    vertical-align: middle;
}


.status-indicator.online {
    background: #00e676;

    box-shadow:
        0 0 8px rgba(0, 230, 118, 0.8);
}


.status-indicator.offline {
    background: #777;

    box-shadow:
        0 0 6px rgba(150, 150, 150, 0.4);
}


/* =========================================================
   ОБЛАСТЬ СООБЩЕНИЙ
   ========================================================= */

.chat-messages {
    height: 550px;

    overflow-y: auto;

    padding: 20px;

    box-sizing: border-box;

    background:
        linear-gradient(
            145deg,
            rgba(0, 0, 0, 0.35),
            rgba(255, 255, 255, 0.015)
        );

    border: 1px solid rgba(0, 229, 255, 0.20);

    border-radius: 18px;

    box-shadow:
        inset 0 0 25px rgba(0, 0, 0, 0.35);

    scroll-behavior: smooth;
}


/* =========================================================
   СООБЩЕНИЕ
   ========================================================= */

.message {
    position: relative;

    display: flex;

    flex-direction: column;

    max-width: 78%;

    margin-bottom: 16px;

    padding: 0;

    box-sizing: border-box;
}


.message.sent {
    margin-left: auto;

    align-items: flex-end;
}


.message.received {
    margin-right: auto;

    align-items: flex-start;
}


/* =========================================================
   КАРТОЧКА МОЕГО СООБЩЕНИЯ
   ========================================================= */

.my-card-body {
    width: fit-content;

    max-width: 100%;

    padding: 13px 17px;

    background:
        linear-gradient(
            135deg,
            rgba(255, 215, 0, 0.20),
            rgba(255, 152, 0, 0.08)
        );

    border: 1px solid rgba(255, 215, 0, 0.65);

    border-radius: 18px 18px 4px 18px;

    box-shadow:
        0 4px 15px rgba(255, 215, 0, 0.08);
}


/* =========================================================
   КАРТОЧКА ПОЛУЧЕННОГО СООБЩЕНИЯ
   ========================================================= */

.card-body {
    width: fit-content;

    max-width: 100%;

    padding: 13px 17px;

    background:
        linear-gradient(
            135deg,
            rgba(0, 229, 255, 0.09),
            rgba(255, 255, 255, 0.025)
        );

    border: 1px solid rgba(0, 229, 255, 0.35);

    border-radius: 18px 18px 18px 4px;

    box-shadow:
        0 4px 15px rgba(0, 0, 0, 0.25);
}


/* =========================================================
   ЗАГОЛОВОК СООБЩЕНИЯ
   ========================================================= */

.card-title {
    margin: 0 0 7px;

    color: #00e5ff;

    font-size: 0.95rem;

    font-weight: 700;
}


.my-card-body .card-title {
    color: gold;
}


.card-title small {
    margin-left: 8px;

    color: #888;

    font-size: 0.78rem;

    font-weight: 400;
}


.message-status {
    margin-left: 5px;
}


/* =========================================================
   ТЕКСТ СООБЩЕНИЯ
   ========================================================= */

.card-text {
    margin: 0;

    color: #f2f2f2;

    font-size: 1rem;

    line-height: 1.5;

    word-break: break-word;

    overflow-wrap: anywhere;
}


/* =========================================================
   REPLY PREVIEW В СООБЩЕНИИ
   ========================================================= */

.reply-preview {
    display: flex;

    align-items: center;

    gap: 9px;

    width: 100%;

    box-sizing: border-box;

    margin-bottom: 7px;

    padding: 8px 11px;

    background:
        rgba(0, 229, 255, 0.06);

    border-left: 3px solid #00e5ff;

    border-radius: 8px;

    color: #aaa;

    font-size: 0.85rem;
}


.reply-indicator {
    color: #00e5ff;
}


.reply-content {
    overflow: hidden;

    text-overflow: ellipsis;

    white-space: nowrap;
}


/* =========================================================
   ПРЕВЬЮ ОТВЕТА НАД ПОЛЕМ
   ========================================================= */

.reply-preview-form {
    display: flex;

    align-items: center;

    justify-content: space-between;

    gap: 15px;

    margin-top: 15px;

    padding: 12px 15px;

    background:
        linear-gradient(
            135deg,
            rgba(0, 229, 255, 0.08),
            rgba(255, 215, 0, 0.04)
        );

    border: 1px solid rgba(0, 229, 255, 0.35);

    border-radius: 14px;
}


.reply-info {
    display: flex;

    align-items: center;

    gap: 10px;

    min-width: 0;

    overflow: hidden;
}


.reply-icon {
    flex-shrink: 0;

    color: #00e5ff;

    font-size: 1rem;
}


.reply-to-name {
    flex-shrink: 0;

    color: gold;

    font-weight: 700;
}


.reply-to-text {
    min-width: 0;

    overflow: hidden;

    text-overflow: ellipsis;

    white-space: nowrap;

    color: #aaa;
}


.cancel-reply {
    flex-shrink: 0;

    width: 32px;

    height: 32px;

    border: 1px solid rgba(255, 82, 82, 0.5);

    border-radius: 50%;

    background: rgba(255, 82, 82, 0.08);

    color: #ff6b6b;

    cursor: pointer;

    font-size: 1rem;

    transition:
        background 0.2s ease,
        color 0.2s ease,
        box-shadow 0.2s ease;
}


.cancel-reply:hover {
    background: #ff5252;

    color: #fff;

    box-shadow:
        0 0 12px rgba(255, 82, 82, 0.35);
}


/* =========================================================
   ПОЛЕ ВВОДА
   ========================================================= */

#messageForm {
    margin-top: 18px;
}


.input-group {
    display: flex;

    align-items: stretch;

    gap: 10px;
}


.input-wrapper {
    flex: 1;

    min-width: 0;
}


#messageInput {
    display: block;

    width: 100%;

    min-height: 52px;

    max-height: 180px;

    padding: 14px 18px;

    box-sizing: border-box;

    resize: none;

    overflow-y: auto;

    background:
        linear-gradient(
            145deg,
            #15161f,
            #1c1d25
        );

    color: #fff;

    border: 1px solid rgba(255, 215, 0, 0.55);

    border-radius: 27px;

    outline: none;

    font-family: inherit;

    font-size: 1rem;

    line-height: 1.4;

    transition:
        border-color 0.3s ease,
        box-shadow 0.3s ease,
        background 0.3s ease;
}


#messageInput::placeholder {
    color: #777;
}


#messageInput:hover {
    border-color: rgba(255, 215, 0, 0.85);
}


#messageInput:focus {
    border-color: gold;

    background:
        linear-gradient(
            145deg,
            #191a25,
            #22242e
        );

    box-shadow:
        0 0 0 2px rgba(255, 215, 0, 0.08),
        0 0 15px rgba(255, 215, 0, 0.25);
}


/* =========================================================
   КНОПКА ОТПРАВКИ
   ========================================================= */

.send-btn {
    flex-shrink: 0;

    width: 54px;

    min-height: 52px;

    border: 1px solid #ffe45c;

    border-radius: 50%;

    background:
        linear-gradient(
            135deg,
            #ffd700,
            #ffb300,
            #ff9800
        );

    color: #0b0c18;

    cursor: pointer;

    font-size: 1.2rem;

    box-shadow:
        0 5px 18px rgba(255, 215, 0, 0.20);

    transition:
        transform 0.25s ease,
        filter 0.25s ease,
        box-shadow 0.25s ease;
}


.send-btn:hover {
    filter: brightness(1.1);

    transform: translateY(-2px);

    box-shadow:
        0 8px 25px rgba(255, 215, 0, 0.45),
        0 0 18px rgba(255, 215, 0, 0.25);
}


.send-btn:active {
    transform: scale(0.95);
}


/* =========================================================
   ДОПОЛНИТЕЛЬНЫЕ КНОПКИ
   ========================================================= */

.additional-buttons {
    display: flex;

    justify-content: center;

    flex-wrap: wrap;

    gap: 12px;

    margin-top: 22px;
}


.chat-btn {
    display: inline-flex;

    align-items: center;

    justify-content: center;

    min-height: 44px;

    padding: 9px 20px;

    box-sizing: border-box;

    background:
        linear-gradient(
            135deg,
            rgba(0, 229, 255, 0.10),
            rgba(255, 215, 0, 0.06)
        );

    color: #00e5ff;

    border: 1px solid rgba(0, 229, 255, 0.65);

    border-radius: 22px;

    text-decoration: none;

    font-size: 0.95rem;

    font-weight: 600;

    transition:
        background 0.25s ease,
        color 0.25s ease,
        border-color 0.25s ease,
        box-shadow 0.25s ease,
        transform 0.25s ease;
}


.chat-btn:hover {
    background:
        linear-gradient(
            135deg,
            #00e5ff,
            #00bcd4
        );

    color: #071018;

    border-color: #00e5ff;

    box-shadow:
        0 0 15px rgba(0, 229, 255, 0.45);

    transform: translateY(-2px);

    text-decoration: none;
}


/* =========================================================
   СКРОЛЛБАР
   ========================================================= */

.chat-messages::-webkit-scrollbar {
    width: 9px;
}


.chat-messages::-webkit-scrollbar-track {
    background: #0c0d14;

    border-radius: 10px;
}


.chat-messages::-webkit-scrollbar-thumb {
    background:
        linear-gradient(
            #555,
            #888
        );

    border-radius: 10px;

    border: 2px solid #0c0d14;
}


.chat-messages::-webkit-scrollbar-thumb:hover {
    background:
        linear-gradient(
            gold,
            #ff9800
        );
}


/* =========================================================
   АДАПТИВНОСТЬ
   ========================================================= */

@media (max-width: 700px) {

    .chat-container {
        width: calc(100% - 20px);

        margin: 15px auto 30px;

        padding: 18px;

        border-radius: 17px;
    }


    .chat-title {
        font-size: 1.55rem;
    }


    .chat-status {
        font-size: 0.9rem;
    }


    .chat-messages {
        height: 58vh;

        min-height: 400px;

        padding: 12px;
    }


    .message {
        max-width: 88%;
    }


    .my-card-body,
    .card-body {
        padding: 11px 13px;
    }


    .card-title {
        font-size: 0.88rem;
    }


    .card-text {
        font-size: 0.95rem;
    }


    /* =====================================================
       ПОЛЕ ВВОДА + КНОПКА ОТПРАВКИ
       ВСЕГДА В ОДНОЙ СТРОКЕ
       ===================================================== */

    .input-group {
        display: flex;

        flex-direction: row;

        align-items: center;

        flex-wrap: nowrap;

        width: 100%;

        gap: 7px;
    }


    .input-wrapper {
        flex: 1 1 auto;

        min-width: 0;

        width: auto;
    }


    #messageInput {
        display: block;

        width: 100%;

        min-width: 0;

        min-height: 48px;

        padding: 12px 15px;

        box-sizing: border-box;

        font-size: 0.95rem;
    }


    .send-btn {
        flex: 0 0 48px;

        width: 48px;

        height: 48px;

        min-width: 48px;

        min-height: 48px;

        padding: 0;

        display: flex;

        align-items: center;

        justify-content: center;
    }


    .additional-buttons {
        flex-direction: column;
    }


    .chat-btn {
        width: 100%;
    }
}


@media (max-width: 420px) {

    .chat-container {
        width: calc(100% - 12px);

        padding: 13px;
    }


    .chat-title {
        font-size: 1.35rem;
    }


    .chat-messages {
        min-height: 350px;

        height: 55vh;

        padding: 9px;
    }


    .message {
        max-width: 93%;
    }


    .my-card-body,
    .card-body {
        padding: 10px 11px;
    }


    .card-text {
        font-size: 0.9rem;
    }


    .reply-preview-form {
        padding: 9px 11px;
    }


    .reply-to-text {
        display: none;
    }
}

</style>


<div class="chat-container">

    {{-- =====================================================
         ЗАГОЛОВОК ЧАТА
         ===================================================== --}}

    <div class="chat-header">

        @if ($chat->type === 'personal' && $otherUser)

            <h2 class="chat-title">
                {{ $otherUser->name }}
            </h2>

            <p class="chat-status">

                @if ($otherUser->isOnline())

                    <span class="status-indicator online"></span>

                    {{ __('chats.online') }}

                @else

                    <span class="status-indicator offline"></span>

                    {{ $otherUser->lastSeenHuman() }}

                @endif

            </p>

        @else

            <h2 class="chat-title">
                {{ $chat->name }}
            </h2>

            <p class="chat-status">

                {{ __('chats.online_participants', [
                    'online' => $chat->onlineParticipantsCount(),
                    'total' => $chat->totalParticipantsCount(),
                ]) }}

            </p>

        @endif

    </div>


    {{-- =====================================================
         СООБЩЕНИЯ
         ===================================================== --}}

    <div
        id="chat-messages"
        class="chat-messages"
    >

        @foreach ($chat->messages as $message)

            <div
                class="message {{ $message->sender_id === auth()->id() ? 'sent' : 'received' }}"
                data-id="{{ $message->id }}"
                data-ipfs-cid="{{ $message->ipfs_cid }}"
            >

                @if ($message->reply_to_message_id)

                    <div
                        class="reply-preview"
                        data-reply-id="{{ $message->reply_to_message_id }}"
                    >

                        <div class="reply-indicator">
                            <i
                                class="fa fa-reply"
                                aria-hidden="true"
                            ></i>
                        </div>

                        <div class="reply-content">

                            {{ __('chats.download') }}

                            <i
                                class="fa fa-spinner"
                                aria-hidden="true"
                            ></i>

                        </div>

                    </div>

                @endif


                <div
                    class="{{ $message->sender_id === auth()->id() ? 'my-card-body' : 'card-body' }}"
                >

                    <p class="card-title">

                        {{ $message->sender_id === auth()->id()
                            ? 'Вы'
                            : $message->sender->name
                        }}

                        <small>
                            {{ $message->created_at->format('H:i, d M') }}
                        </small>

                        @if ($message->edited_at)

                            <small class="message-status edited">
                                ✏️
                            </small>

                        @endif

                    </p>


                    <p class="card-text">

                        {{ __('chats.download') }}

                        <i
                            class="fa fa-spinner"
                            aria-hidden="true"
                        ></i>

                    </p>

                </div>

            </div>

        @endforeach

    </div>


    {{-- =====================================================
         ПРЕВЬЮ ОТВЕТА
         ===================================================== --}}

    <div
        id="replyPreview"
        class="reply-preview-form"
        style="display: none;"
    >

        <div class="reply-info">

            <span class="reply-icon">

                <i
                    class="fa fa-reply"
                    aria-hidden="true"
                ></i>

            </span>

            <span class="reply-to-name"></span>

            <span class="reply-to-text"></span>

        </div>


        <button
            type="button"
            id="cancelReply"
            class="cancel-reply"
        >
            ✕
        </button>

    </div>


    {{-- =====================================================
         ФОРМА ОТПРАВКИ
         ===================================================== --}}

    <form
        id="messageForm"
        onsubmit="return false;"
        action="{{ route('messages.send', $chat->id) }}"
    >

        @csrf

        <div class="input-group">

            <div class="input-wrapper">

                <textarea
                    id="messageInput"
                    name="message"
                    placeholder="{{ __('chats.type_message') }}"
                    rows="1"
                    required
                ></textarea>

            </div>


            <button
                type="button"
                id="sendBtn"
                class="send-btn"
            >

                <i
                    class="fa fa-paper-plane"
                    aria-hidden="true"
                ></i>

            </button>

        </div>

    </form>


    {{-- =====================================================
         ДОПОЛНИТЕЛЬНЫЕ КНОПКИ
         ===================================================== --}}

    <div class="additional-buttons">

        <a
            href="{{ route('chats.index') }}"
            class="chat-btn"
        >
            {{ __('chats.to_chats') }}
        </a>


        <a
            href="{{ route('chats.create') }}"
            class="chat-btn"
        >
            {{ __('chats.new_chat') }}
        </a>


        <a
            href="/notifications"
            class="chat-btn"
        >
            {{ __('chats.notifications') }}
        </a>

    </div>


    {{-- =====================================================
         ПЕРЕВОДЫ
         ===================================================== --}}

    <div
        id="translations"
        data-reply="{{ __('chats.reply') }}"
        data-edit="{{ __('chats.edit') }}"
        data-delete="{{ __('chats.delete') }}"
        data-copy="{{ __('chat.copy') }}"
        style="display:none;"
    >
    </div>

</div>


{{-- =========================================================
     ЗВУК УВЕДОМЛЕНИЯ
     ========================================================= --}}

<audio
    id="notificationSound"
    preload="auto"
>

    <source
        src="/sounds/notification.mp3"
        type="audio/mpeg"
    >

</audio>


{{-- =========================================================
     КОНФИГУРАЦИЯ ЧАТА
     ========================================================= --}}

<div
    id="chat-config"
    data-chat-id="{{ $chat->id }}"
    data-user-id="{{ auth()->id() }}"
    data-user-name="{{ auth()->user()->name }}"
    data-first-unread="{{ $firstUnreadMessageId ?? '' }}"
    data-send-url="{{ route('messages.send', $chat->id) }}"
    data-csrf="{{ csrf_token() }}"
    data-translate-edit="{{ __('chats.edit') }}"
    data-translate-delete="{{ __('chats.delete') }}"
    data-translate-reply="{{ __('chats.reply') }}"
    data-translate-send="{{ __('chats.send') }}"
    data-translate-save="{{ __('chats.save') }}"
    data-translate-copy="{{ __('chats.copy') }}"
    data-translate-download="{{ __('chats.download') }}"
    data-translate-message-deleted="{{ __('chats.message_deleted') }}"
    data-translate-delete-confirm="{{ __('chats.delete_confirm') }}"
    data-translate-send-error="{{ __('chats.send_error') }}"
    data-translate-you="{{ __('chats.you') }}"
></div>


{{-- =========================================================
     JAVASCRIPT
     ========================================================= --}}

@push('scripts')

    @vite('resources/js/chat/index.js')

@endpush

@endsection