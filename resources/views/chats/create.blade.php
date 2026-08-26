@extends('template')

@section('title_page', __('chats.create_chat_title'))

@section('main')

<style>

/* =========================================================
   МАСШТАБ КОНТЕНТА — ВИЗУАЛЬНО КАК ПРИ ZOOM 160%
   ========================================================= */

.chat-create-container {
    --chat-scale: 1.6;
}


/* =========================================================
   ОСНОВНАЯ КАРТОЧКА
   ========================================================= */

.chat-create-container {
    position: relative;

    max-width: 1200px;

    margin: 48px auto 64px;

    padding: 45px;

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

    border-radius: 30px;

    color: #fff;

    box-shadow:
        0 16px 56px rgba(0, 0, 0, 0.65),
        0 0 40px rgba(255, 215, 0, 0.08);

    overflow: hidden;
}


/* =========================================================
   ВЕРХНЯЯ ДЕКОРАТИВНАЯ ЛИНИЯ
   ========================================================= */

.chat-create-container::before {
    content: "";

    position: absolute;

    top: 0;
    left: 8%;
    right: 8%;

    height: 3px;

    background: linear-gradient(
        90deg,
        transparent,
        gold,
        #fff176,
        gold,
        transparent
    );

    box-shadow:
        0 0 18px rgba(255, 215, 0, 0.7);
}


/* =========================================================
   НИЖНЯЯ ДЕКОРАТИВНАЯ ЛИНИЯ
   ========================================================= */

.chat-create-container::after {
    content: "";

    position: absolute;

    bottom: 0;
    left: 15%;
    right: 15%;

    height: 2px;

    background: linear-gradient(
        90deg,
        transparent,
        rgba(0, 229, 255, 0.8),
        transparent
    );
}


/* =========================================================
   ЗАГОЛОВОК
   ========================================================= */

.chat-create-header {
    text-align: center;

    margin-bottom: 40px;
}

.chat-create-header h1 {
    margin: 0;

    color: gold;

    font-size: 3.2rem;

    line-height: 1.2;

    font-weight: 700;

    letter-spacing: 0.8px;

    text-shadow:
        0 0 12px rgba(255, 215, 0, 0.35),
        0 0 28px rgba(255, 215, 0, 0.12);
}

.chat-create-header-line {
    width: 145px;

    height: 5px;

    margin: 16px auto 0;

    border-radius: 8px;

    background: linear-gradient(
        90deg,
        #00e5ff,
        gold,
        #ff9800
    );

    box-shadow:
        0 0 16px rgba(255, 215, 0, 0.5);
}


/* =========================================================
   КНОПКА НАЗАД
   ========================================================= */

.chat-back-wrapper {
    margin-bottom: 35px;
}

.chat-back-btn {
    display: inline-flex;

    align-items: center;
    justify-content: center;

    gap: 13px;

    min-height: 67px;

    padding: 14px 29px;

    background:
        linear-gradient(
            135deg,
            rgba(0, 229, 255, 0.10),
            rgba(255, 215, 0, 0.06)
        );

    color: #00e5ff;

    border: 1px solid rgba(0, 229, 255, 0.7);

    border-radius: 34px;

    text-decoration: none;

    font-size: 1.52rem;

    font-weight: 600;

    transition:
        background 0.25s ease,
        color 0.25s ease,
        border-color 0.25s ease,
        box-shadow 0.25s ease,
        transform 0.25s ease;
}

.chat-back-btn:hover {
    background:
        linear-gradient(
            135deg,
            #00e5ff,
            #00bcd4
        );

    color: #071018;

    border-color: #00e5ff;

    box-shadow:
        0 0 24px rgba(0, 229, 255, 0.45);

    transform: translateX(-5px);
}


/* =========================================================
   РЕЖИМЫ ЧАТА
   ========================================================= */

.mode-toggle {
    display: flex;

    justify-content: center;

    gap: 19px;

    margin: 16px auto 45px;

    padding: 10px;

    max-width: 800px;

    background:
        rgba(0, 0, 0, 0.35);

    border: 1px solid rgba(255, 215, 0, 0.25);

    border-radius: 48px;

    box-shadow:
        inset 0 0 24px rgba(0, 0, 0, 0.35);
}

.mode-btn {
    flex: 1;

    min-height: 70px;

    display: flex;

    align-items: center;
    justify-content: center;

    padding: 14px 29px;

    background:
        linear-gradient(
            135deg,
            #11131f,
            #181a27
        );

    color: #c8c8c8;

    border: 1px solid #3d3f4b;

    border-radius: 37px;

    cursor: pointer;

    font-size: 1.35rem;

    font-weight: 700;

    transition:
        all 0.25s ease;
}

.mode-btn:hover {
    color: gold;

    border-color: gold;

    box-shadow:
        0 0 19px rgba(255, 215, 0, 0.25);
}

.mode-btn.active {
    background:
        linear-gradient(
            135deg,
            #ffd700,
            #ffb300
        );

    color: #0b0c18;

    border-color: gold;

    box-shadow:
        0 0 24px rgba(255, 215, 0, 0.45);

    text-shadow: none;
}


/* =========================================================
   ФОРМЫ
   ========================================================= */

.form-group {
    margin-bottom: 40px;
}

.form-group label {
    display: block;

    color: gold;

    font-size: 1.68rem;

    margin: 0 0 14px;

    font-weight: 700;

    letter-spacing: 0.3px;
}

.form-group small,
.form-group .text-muted,
.form-text {
    display: block;

    color: #9ca3af;

    font-size: 1.36rem;

    margin-top: 11px;

    line-height: 1.5;
}


/* =========================================================
   НАЗВАНИЕ ЧАТА
   ========================================================= */

.chat-name-group {
    padding: 29px;

    background:
        linear-gradient(
            145deg,
            rgba(255, 215, 0, 0.055),
            rgba(255, 255, 255, 0.015)
        );

    border: 1px solid rgba(255, 215, 0, 0.22);

    border-radius: 26px;

    box-shadow:
        inset 0 0 32px rgba(0, 0, 0, 0.2);
}

.form-group input[name="name"] {
    width: 100%;

    box-sizing: border-box;
}


/* =========================================================
   ПОЛЯ ВВОДА
   ========================================================= */

.input_dark {
    width: 100%;

    box-sizing: border-box;

    min-height: 77px;

    padding: 18px 27px;

    background:
        linear-gradient(
            145deg,
            #15161f,
            #1c1d25
        );

    color: #fff;

    border: 1px solid rgba(255, 215, 0, 0.55);

    border-radius: 38px;

    outline: none;

    font-size: 1.6rem;

    line-height: 1.4;

    transition:
        border-color 0.3s ease,
        box-shadow 0.3s ease,
        background 0.3s ease;
}

.input_dark::placeholder {
    color: #777;
}

.input_dark:hover {
    border-color: rgba(255, 215, 0, 0.85);
}

.input_dark:focus {
    border-color: gold;

    background:
        linear-gradient(
            145deg,
            #191a25,
            #22242e
        );

    box-shadow:
        0 0 0 3px rgba(255, 215, 0, 0.08),
        0 0 24px rgba(255, 215, 0, 0.25);
}

.input_dark:disabled {
    opacity: 0.55;

    cursor: not-allowed;

    background: #111218;

    border-color: #444;
}

.input_dark.error {
    border-color: #ff5252;

    box-shadow:
        0 0 16px rgba(255, 82, 82, 0.35);
}


/* =========================================================
   ОШИБКИ
   ========================================================= */

.error-message {
    color: #ff6b6b;

    font-size: 1.44rem;

    margin-top: 11px;

    display: none;

    padding-left: 8px;
}


/* =========================================================
   БЛОК УЧАСТНИКОВ
   ========================================================= */

.participants-group {
    margin-left: 40px;

    margin-right: 16px;

    padding: 32px;

    background:
        linear-gradient(
            145deg,
            rgba(0, 229, 255, 0.035),
            rgba(255, 215, 0, 0.025)
        );

    border: 1px solid rgba(0, 229, 255, 0.20);

    border-radius: 29px;

    box-shadow:
        inset 0 0 40px rgba(0, 0, 0, 0.25);
}

.participants-group > label {
    color: #00e5ff;

    font-size: 1.84rem;

    text-shadow:
        0 0 13px rgba(0, 229, 255, 0.25);
}


/* =========================================================
   ВЫБРАННЫЕ ПОЛЬЗОВАТЕЛИ
   ========================================================= */

.selected-users {
    display: flex;

    flex-wrap: wrap;

    align-items: center;

    gap: 14px;

    min-height: 8px;

    margin-top: 21px;

    margin-bottom: 22px;

    margin-left: 16px;

    padding: 6px;
}

.selected-user {
    display: flex;

    align-items: center;

    gap: 13px;

    padding: 11px 16px 11px 13px;

    background:
        linear-gradient(
            135deg,
            rgba(255, 215, 0, 0.18),
            rgba(255, 152, 0, 0.08)
        );

    color: #fff;

    border: 1px solid gold;

    border-radius: 35px;

    box-shadow:
        0 0 16px rgba(255, 215, 0, 0.12);

    transition:
        border-color 0.2s ease,
        transform 0.2s ease,
        box-shadow 0.2s ease;
}

.selected-user:hover {
    border-color: #fff176;

    transform: translateY(-3px);

    box-shadow:
        0 0 22px rgba(255, 215, 0, 0.3);
}

.selected-user img {
    width: 43px;

    height: 43px;

    border-radius: 50%;

    object-fit: cover;

    border: 1px solid gold;

    box-shadow:
        0 0 10px rgba(255, 215, 0, 0.3);
}

.selected-user span:nth-child(2) {
    color: #fff;

    font-size: 1.44rem;

    font-weight: 600;
}

.selected-user span:last-child {
    display: inline-flex;

    align-items: center;
    justify-content: center;

    width: 35px;
    height: 35px;

    color: #ff6b6b !important;

    border-radius: 50%;

    font-size: 1.92rem;

    transition:
        background 0.2s ease,
        color 0.2s ease;
}

.selected-user span:last-child:hover {
    background: #ff5252;

    color: #fff !important;
}


/* =========================================================
   СПИСОК ПОЛЬЗОВАТЕЛЕЙ
   ========================================================= */

.user-list {
    max-height: 480px;

    overflow-y: auto;

    margin-top: 19px;

    background:
        linear-gradient(
            145deg,
            #111218,
            #181a22
        );

    border: 1px solid rgba(255, 215, 0, 0.55);

    border-radius: 24px;

    box-shadow:
        0 11px 32px rgba(0, 0, 0, 0.45),
        inset 0 0 32px rgba(0, 0, 0, 0.2);
}


/* =========================================================
   ПОЛЬЗОВАТЕЛЬ
   ========================================================= */

.user-item {
    position: relative;

    display: flex;

    align-items: center;

    padding: 21px 26px;

    cursor: pointer;

    background:
        linear-gradient(
            90deg,
            rgba(255, 255, 255, 0.025),
            rgba(255, 255, 255, 0.01)
        );

    border-bottom: 1px solid rgba(255, 255, 255, 0.07);

    transition:
        background 0.2s ease,
        border-color 0.2s ease,
        padding-left 0.2s ease;
}

.user-item:last-child {
    border-bottom: none;
}

.user-item:hover {
    background:
        linear-gradient(
            90deg,
            rgba(0, 229, 255, 0.10),
            rgba(255, 215, 0, 0.06)
        );

    border-color: rgba(0, 229, 255, 0.35);

    padding-left: 32px;
}

.user-item img {
    width: 69px;

    height: 69px;

    border-radius: 50%;

    object-fit: cover;

    margin-right: 21px;

    border: 3px solid #00e5ff;

    box-shadow:
        0 0 14px rgba(0, 229, 255, 0.25);

    transition:
        border-color 0.2s ease,
        box-shadow 0.2s ease;
}

.user-item:hover img {
    border-color: gold;

    box-shadow:
        0 0 19px rgba(255, 215, 0, 0.35);
}

.user-item .name {
    font-size: 1.6rem;

    color: #f5f5f5;

    font-weight: 500;
}

.user-item.selected {
    background:
        linear-gradient(
            90deg,
            rgba(255, 215, 0, 0.16),
            rgba(255, 152, 0, 0.07)
        );

    border-left: 6px solid gold;

    padding-left: 19px;

    box-shadow:
        inset 0 0 32px rgba(255, 215, 0, 0.04);
}

.user-item.selected .name {
    color: #fff176;

    font-weight: 700;
}

.user-item.selected::after {
    content: "✓";

    display: flex;

    align-items: center;
    justify-content: center;

    width: 43px;
    height: 43px;

    margin-left: auto;

    color: #0b0c18;

    background:
        linear-gradient(
            135deg,
            #fff176,
            gold
        );

    border-radius: 50%;

    font-weight: 900;

    font-size: 1.6rem;

    box-shadow:
        0 0 16px rgba(255, 215, 0, 0.45);
}


/* =========================================================
   КНОПКА СОЗДАНИЯ
   ========================================================= */

.des-btn {
    display: block;

    width: min(100%, 480px);

    min-height: 80px;

    box-sizing: border-box;

    padding: 18px 38px;

    margin: 45px auto 8px;

    background:
        linear-gradient(
            135deg,
            #ffd700,
            #ffb300,
            #ff9800
        );

    color: #0b0c18;

    border: 1px solid #ffe45c;

    border-radius: 42px;

    font-size: 1.68rem;

    font-weight: 800;

    text-decoration: none;

    cursor: pointer;

    box-shadow:
        0 8px 29px rgba(255, 215, 0, 0.20);

    transition:
        box-shadow 0.25s ease,
        transform 0.25s ease,
        filter 0.25s ease;
}

.des-btn:hover {
    color: #000;

    filter: brightness(1.1);

    transform: translateY(-3px) scale(1.02);

    box-shadow:
        0 13px 40px rgba(255, 215, 0, 0.45),
        0 0 29px rgba(255, 215, 0, 0.25);
}

.des-btn:active {
    transform: translateY(0) scale(0.99);
}


/* =========================================================
   СКРОЛЛБАР
   ========================================================= */

.user-list::-webkit-scrollbar {
    width: 14px;
}

.user-list::-webkit-scrollbar-track {
    background: #0c0d14;

    border-radius: 16px;
}

.user-list::-webkit-scrollbar-thumb {
    background:
        linear-gradient(
            #555,
            #888
        );

    border-radius: 16px;

    border: 3px solid #0c0d14;
}

.user-list::-webkit-scrollbar-thumb:hover {
    background:
        linear-gradient(
            gold,
            #ff9800
        );
}


/* =========================================================
   АДАПТИВНОСТЬ
   ========================================================= */

@media (max-width: 1100px) {

    .chat-create-container {
        margin: 40px 20px 55px;

        padding: 38px;
    }

    .participants-group {
        margin-left: 25px;

        margin-right: 10px;
    }
}


@media (max-width: 700px) {

    .chat-create-container {
        margin: 24px 10px 45px;

        padding: 29px;

        border-radius: 27px;
    }

    .chat-create-header {
        margin-bottom: 32px;
    }

    .chat-create-header h1 {
        font-size: 2.48rem;
    }

    .chat-create-header-line {
        width: 115px;

        height: 4px;
    }

    .chat-back-wrapper {
        margin-bottom: 28px;
    }

    .chat-back-btn {
        width: 100%;

        min-height: 67px;

        font-size: 1.42rem;
    }

    .mode-toggle {
        flex-direction: column;

        max-width: none;

        border-radius: 27px;

        gap: 12px;
    }

    .mode-btn {
        width: 100%;

        min-height: 67px;

        font-size: 1.38rem;
    }

    .chat-name-group {
        padding: 24px;
    }

    .form-group label {
        font-size: 1.55rem;
    }

    .form-group small,
    .form-group .text-muted,
    .form-text {
        font-size: 1.25rem;
    }

    .participants-group {
        margin-left: 8px;

        margin-right: 0;

        padding: 24px;

        border-radius: 24px;
    }

    .participants-group > label {
        font-size: 1.62rem;
    }

    .selected-users {
        margin-left: 0;
    }

    .input_dark {
        min-height: 70px;

        padding: 16px 23px;

        font-size: 1.42rem;
    }

    .user-list {
        max-height: 400px;
    }

    .user-item {
        padding: 17px;
    }

    .user-item:hover {
        padding-left: 21px;
    }

    .user-item img {
        width: 58px;

        height: 58px;

        margin-right: 15px;
    }

    .user-item .name {
        font-size: 1.38rem;
    }

    .user-item.selected::after {
        width: 38px;

        height: 38px;
    }

    .des-btn {
        width: 100%;

        min-height: 72px;

        font-size: 1.52rem;
    }
}


@media (max-width: 420px) {

    .chat-create-container {
        margin-left: 6px;

        margin-right: 6px;

        padding: 21px;

        border-radius: 23px;
    }

    .chat-create-header h1 {
        font-size: 2.15rem;
    }

    .chat-back-btn {
        width: 100%;

        min-height: 62px;

        font-size: 1.25rem;
    }

    .mode-toggle {
        padding: 7px;
    }

    .mode-btn {
        min-height: 61px;

        font-size: 1.25rem;
    }

    .chat-name-group {
        padding: 19px;
    }

    .participants-group {
        margin-left: 3px;

        padding: 19px;
    }

    .input_dark {
        min-height: 64px;

        padding: 14px 19px;

        font-size: 1.28rem;
    }

    .user-item {
        padding: 15px 12px;
    }

    .user-item:hover {
        padding-left: 15px;
    }

    .user-item img {
        width: 51px;

        height: 51px;

        margin-right: 12px;
    }

    .user-item .name {
        font-size: 1.22rem;
    }

    .user-item.selected::after {
        width: 34px;

        height: 34px;

        font-size: 1.3rem;
    }

    .selected-user {
        padding: 9px 12px 9px 10px;
    }

    .selected-user img {
        width: 38px;

        height: 38px;
    }

    .selected-user span:nth-child(2) {
        font-size: 1.22rem;
    }

    .des-btn {
        min-height: 67px;

        font-size: 1.38rem;
    }
}

</style>


<div class="chat-create-container">

    {{-- =====================================================
         КНОПКА НАЗАД
         ===================================================== --}}

    <div class="chat-back-wrapper">

        <a
            href="{{ route('chats.index') }}"
            class="chat-back-btn"
        >
            <span>←</span>

            <span>Назад к чатам</span>
        </a>

    </div>


    {{-- =====================================================
         ЗАГОЛОВОК
         ===================================================== --}}

    <div class="chat-create-header">

        <h1>
            {{ __('chats.create_chat_title') }}
        </h1>

        <div class="chat-create-header-line"></div>

    </div>


    {{-- =====================================================
         ВЫБОР РЕЖИМА
         ===================================================== --}}

    <div class="mode-toggle">

        <div
            class="mode-btn active"
            data-mode="group"
            onclick="setChatMode('group')"
        >
            {{ __('chats.group_chat') }}
        </div>

        <div
            class="mode-btn"
            data-mode="personal"
            onclick="setChatMode('personal')"
        >
            {{ __('chats.direct_chat') }}
        </div>

    </div>


    {{-- =====================================================
         ФОРМА
         ===================================================== --}}

    <form
        id="create-chat-form"
        method="POST"
        action="{{ route('chats.store') }}"
    >

        @csrf

        <input
            type="hidden"
            name="chat_type"
            id="chatType"
            value="group"
        >


        {{-- =================================================
             НАЗВАНИЕ
             ================================================= --}}

        <div class="form-group chat-name-group">

            <label for="chatName">
                {{ __('chats.chat_name_label') }}
            </label>

            <input
                type="text"
                class="input_dark"
                id="chatName"
                name="name"
                value="{{ old('name') }}"
            >

            <div
                class="error-message"
                id="chatNameError"
            >
                {{ __('chats.group_name_required') }}
            </div>

            <div class="form-text text-muted">
                {{ __('chats.group_name_optional') }}
            </div>

        </div>


        {{-- =================================================
             УЧАСТНИКИ
             ================================================= --}}

        <div class="form-group participants-group">

            <label>
                {{ __('chats.participants_label') }}
            </label>


            {{-- ПОИСК --}}

            <input
                type="text"
                id="userSearch"
                class="input_dark"
                placeholder="{{ __('chats.search_users') }}"
            >


            {{-- ВЫБРАННЫЕ ПОЛЬЗОВАТЕЛИ --}}

            <div
                class="selected-users"
                id="selectedUsers"
            ></div>


            <input
                type="hidden"
                name="users"
                id="selectedUsersInput"
                value=""
            >


            {{-- СПИСОК ПОЛЬЗОВАТЕЛЕЙ --}}

            <div
                class="user-list"
                id="userList"
            >

                @foreach ($users as $user)

                    <div
                        class="user-item"
                        data-id="{{ $user['id'] }}"
                        data-name="{{ $user['name'] }}"
                        data-avatar="{{ $user['avatar'] }}"
                    >

                        <img
                            src="{{ $user['avatar'] }}"
                            alt="{{ $user['name'] }}"
                        >

                        <span class="name">
                            {{ $user['name'] }}
                        </span>

                    </div>

                @endforeach

            </div>

        </div>


        {{-- =================================================
             СОЗДАТЬ
             ================================================= --}}

        <button
            type="submit"
            class="des-btn"
        >
            {{ __('chats.create') }}
        </button>

    </form>

</div>


<script>

let selectedUsers = new Set();

let chatMode = 'group';


function setChatMode(mode) {

    chatMode = mode;

    document.getElementById('chatType').value = mode;


    document
        .querySelectorAll('.mode-btn')
        .forEach(btn => btn.classList.remove('active'));


    document
        .querySelector(`.mode-btn[data-mode="${mode}"]`)
        .classList.add('active');


    if (mode === 'personal') {

        if (selectedUsers.size > 1) {

            const first = [...selectedUsers][0];

            selectedUsers.clear();

            selectedUsers.add(first);
        }
    }


    updateSelectedUsersUI();

    toggleChatName();
}


function toggleChatName() {

    const nameField =
        document.getElementById('chatName');

    const errorEl =
        document.getElementById('chatNameError');


    if (chatMode === 'personal') {

        nameField.disabled = true;

        nameField.placeholder =
            "{{ __('chats.direct_chat_no_name') }}";

        errorEl.style.display = 'none';

        nameField.classList.remove('error');

    } else {

        nameField.disabled = false;

        nameField.placeholder =
            "{{ __('chats.chat_name_label') }}";
    }
}


function updateSelectedUsersUI() {

    const container =
        document.getElementById('selectedUsers');

    container.innerHTML = '';


    selectedUsers.forEach(id => {

        const el =
            document.querySelector(
                `.user-item[data-id="${id}"]`
            );


        if (!el) return;


        const name =
            el.dataset.name;

        const avatar =
            el.dataset.avatar;


        const tag =
            document.createElement('div');


        tag.className =
            'selected-user';


        tag.innerHTML = `
            <img src="${avatar}" alt="${name}">
            <span>${name}</span>
            <span
                style="cursor:pointer;color:#ff6b6b;"
                onclick="removeUser(${id})"
            >×</span>
        `;


        container.appendChild(tag);
    });


    document.getElementById(
        'selectedUsersInput'
    ).value =
        JSON.stringify([...selectedUsers]);
}


function removeUser(id) {

    if (chatMode === 'personal') {

        selectedUsers.clear();

    } else {

        selectedUsers.delete(id);
    }


    updateUI();
}


function updateUI() {

    document
        .querySelectorAll('.user-item')
        .forEach(item => {

            const id =
                parseInt(item.dataset.id);


            if (selectedUsers.has(id)) {

                item.classList.add('selected');

            } else {

                item.classList.remove('selected');
            }
        });


    updateSelectedUsersUI();
}


document
    .querySelectorAll('.user-item')
    .forEach(item => {

        item.addEventListener('click', () => {

            const id =
                parseInt(item.dataset.id);


            if (chatMode === 'personal') {

                selectedUsers.clear();

                selectedUsers.add(id);

            } else {

                if (selectedUsers.has(id)) {

                    selectedUsers.delete(id);

                } else {

                    selectedUsers.add(id);
                }
            }


            updateUI();
        });
    });


document
    .getElementById('userSearch')
    .addEventListener('input', (e) => {

        const term =
            e.target.value.toLowerCase();


        document
            .querySelectorAll('.user-item')
            .forEach(item => {

                const name =
                    item.dataset.name.toLowerCase();


                item.style.display =
                    name.includes(term)
                        ? 'flex'
                        : 'none';
            });
    });


toggleChatName();

</script>


<script>

/* =========================================================
   BASE64 ↔ UINT8ARRAY
   ========================================================= */

function b64ToU8(b64) {

    return Uint8Array.from(
        atob(b64),
        c => c.charCodeAt(0)
    );
}


function u8ToB64(u8) {

    return btoa(
        String.fromCharCode(...u8)
    );
}


/* =========================================================
   ПОЛУЧЕНИЕ ПУБЛИЧНЫХ КЛЮЧЕЙ
   ========================================================= */

async function fetchPublicKeys(userIds) {

    console.log(
        "📡 Запрашиваем публичные ключи для:",
        userIds
    );


    const res = await fetch(
        '/users/public-keys',
        {
            method: 'POST',

            credentials: 'include',

            headers: {
                'Content-Type': 'application/json',

                'X-CSRF-TOKEN':
                    document
                        .querySelector(
                            'meta[name="csrf-token"]'
                        )
                        .content
            },

            body: JSON.stringify({
                user_ids: userIds
            })
        }
    );


    console.log(
        "📡 Статус ответа public-keys:",
        res.status
    );


    if (!res.ok) {

        throw new Error(
            `Ошибка загрузки ключей: ${res.status}`
        );
    }


    const data =
        await res.json();


    console.log(
        "📡 Публичные ключи получены:",
        data
    );


    return data;
}


/* =========================================================
   СОЗДАНИЕ ЧАТА
   ========================================================= */

document.addEventListener(
    'DOMContentLoaded',
    function() {

        const form =
            document.getElementById(
                'create-chat-form'
            );


        if (!form) {

            console.log(
                "⚠️ create-chat-form не найден"
            );

            return;
        }


        form.addEventListener(
            'submit',
            async function(e) {

                const chatType =
                    document.getElementById(
                        'chatType'
                    ).value;


                const chatName =
                    document.getElementById(
                        'chatName'
                    );


                const errorEl =
                    document.getElementById(
                        'chatNameError'
                    );


                /* =========================================
                   ПРОВЕРКА НАЗВАНИЯ ГРУППЫ
                   ========================================= */

                if (chatType === 'group') {

                    const nameValue =
                        chatName.value.trim();


                    if (!nameValue) {

                        e.preventDefault();

                        chatName.classList.add(
                            'error'
                        );

                        errorEl.style.display =
                            'block';

                        chatName.focus();

                        return;

                    } else {

                        chatName.classList.remove(
                            'error'
                        );

                        errorEl.style.display =
                            'none';
                    }
                }


                e.preventDefault();


                console.log(
                    "🚀 Отправка формы создания чата..."
                );


                const usersInput =
                    form.querySelector(
                        'input[name="users"]'
                    );


                let userIDs =
                    JSON.parse(
                        usersInput.value || '[]'
                    );


                if (userIDs.length === 0) {

                    alert(
                        '{{ __("chats.select_at_least_one_participant") }}'
                    );

                    return;
                }


                console.log(
                    "👥 Пользователи:",
                    userIDs
                );


                const currentUserId =
                    {{ auth()->id() }};


                const allUserIds =
                    [
                        ...new Set(
                            [
                                ...userIDs,
                                currentUserId
                            ]
                        )
                    ];


                console.log(
                    "👥 Все участники:",
                    allUserIds
                );


                try {

                    const publicKeys =
                        await fetchPublicKeys(
                            allUserIds
                        );


                    for (
                        const uid of allUserIds
                    ) {

                        if (!publicKeys[uid]) {

                            console.error(
                                `❌ У UID=${uid} нет публичного ключа`
                            );

                            alert(
                                `У пользователя ID=${uid} нет публичного ключа`
                            );

                            return;
                        }
                    }


                    console.log(
                        "🔑 Все ключи присутствуют, генерируем chatKey…"
                    );


                    const chatKey =
                        nacl.randomBytes(
                            nacl.secretbox.keyLength
                        );


                    const CURRENT_USER_ID =
                        {{ auth()->id() }};


                    const myPrivKeyString =
                        localStorage.getItem(
                            `userPrivateKey_${CURRENT_USER_ID}`
                        );


                    console.log(
                        "🔐 Приватный ключ из localStorage:",
                        myPrivKeyString
                    );


                    const myPrivKey =
                        b64ToU8(
                            myPrivKeyString
                        );


                    const encryptedKeys = {};


                    for (
                        const uid of allUserIds
                    ) {

                        const pubKey =
                            b64ToU8(
                                publicKeys[uid]
                            );


                        const nonce =
                            nacl.randomBytes(
                                nacl.box.nonceLength
                            );


                        const encrypted =
                            nacl.box(
                                chatKey,
                                nonce,
                                pubKey,
                                myPrivKey
                            );


                        encryptedKeys[uid] = {

                            encrypted_key:
                                u8ToB64(
                                    encrypted
                                ),

                            nonce:
                                u8ToB64(
                                    nonce
                                )
                        };
                    }


                    console.log(
                        "📦 encryptedKeys готов:",
                        encryptedKeys
                    );


                    /* =====================================
                       УДАЛЯЕМ СТАРЫЕ HIDDEN
                       ===================================== */

                    document
                        .querySelectorAll(
                            'input[name^="encrypted_keys"]'
                        )
                        .forEach(
                            el => el.remove()
                        );


                    /* =====================================
                       ДОБАВЛЯЕМ ЗАШИФРОВАННЫЕ КЛЮЧИ
                       ===================================== */

                    for (
                        const uid in encryptedKeys
                    ) {

                        const ek =
                            encryptedKeys[uid];


                        let keyInput =
                            document.createElement(
                                'input'
                            );


                        keyInput.type =
                            'hidden';


                        keyInput.name =
                            `encrypted_keys[${uid}][encrypted_key]`;


                        keyInput.value =
                            ek.encrypted_key;


                        form.appendChild(
                            keyInput
                        );


                        let nonceInput =
                            document.createElement(
                                'input'
                            );


                        nonceInput.type =
                            'hidden';


                        nonceInput.name =
                            `encrypted_keys[${uid}][nonce]`;


                        nonceInput.value =
                            ek.nonce;


                        form.appendChild(
                            nonceInput
                        );
                    }


                    console.log(
                        "📨 Отправляем форму…"
                    );


                    form.submit();


                } catch (err) {

                    console.error(
                        "🚨 Ошибка JS при создании чата:",
                        err
                    );


                    alert(
                        err.message
                    );
                }
            }
        );
    }
);

</script>

@endsection