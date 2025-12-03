@extends('template')
@section('title_page', __('chats.create_chat_title'))
@section('main')
<style>
  .container {
    padding: 20px;
    margin: 0 auto;
    max-width: 800px;
    background-color: rgba(20, 20, 20, 0.9);
    border-radius: 20px;
    border: 1px solid #d7fc09;
    color: #f8f9fa;
    font-family: 'Verdana', 'Geneva', 'Tahoma', sans-serif;
    margin-top: 30px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
  }

  .form-group label {
    color: #d7fc09;
    font-size: 1.2rem;
    display: block;
    margin: 10px 0;
    text-align: left;
    font-weight: bold;
  }

  .input_dark {
    background-color: #1a1a1a;
    color: #a0ff08;
    border: 1px solid #d7fc09;
    border-radius: 5px;
    width: 100%;
    padding: 12px;
    margin-top: 5px;
    transition: border 0.3s ease;
  }

  .input_dark:focus {
    border: 1px solid #a0ff08;
    outline: none;
    box-shadow: 0 0 5px #d7fc09;
  }

  .des-btn {
    display: inline-block;
    color: #ffffff;
    background: #0b0c18;
    padding: 10px 20px;
    font-size: 1.3rem;
    border: 1px solid gold;
    border-radius: 10px;
    transition: box-shadow 0.3s ease, transform 0.3s ease;
    text-decoration: none;
    margin: 20px auto;
    display: block;
  }

  .des-btn:hover {
    box-shadow: 0 0 20px goldenrod;
    transform: scale(1.05);
    color: #ffffff;
  }

  h1, h2, h3 {
    text-align: center;
  }

  /* --- Новые стили --- */
  .selected-users {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-bottom: 15px;
  }

  .selected-user {
    background: #2a2a2a;
    color: #d7fc09;
    padding: 6px 12px;
    border-radius: 20px;
    display: flex;
    align-items: center;
    gap: 6px;
  }

  .selected-user img {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    object-fit: cover;
  }

  .user-list {
    max-height: 300px;
    overflow-y: auto;
    border: 1px solid #444;
    border-radius: 8px;
    margin-top: 10px;
  }

  .user-item {
    display: flex;
    align-items: center;
    padding: 12px;
    cursor: pointer;
    transition: background 0.2s;
  }

  .user-item:hover {
    background: #333;
  }

  .user-item img {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
    margin-right: 12px;
    border: 2px solid #d7fc09;
  }

  .user-item .name {
    font-size: 1.1rem;
    color: #f8f9fa;
  }

  .user-item.selected {
    background: rgba(215, 252, 9, 0.15);
  }

  .user-item.selected::after {
    content: "✓";
    color: #d7fc09;
    margin-left: auto;
    font-weight: bold;
  }

  .mode-toggle {
    display: flex;
    justify-content: center;
    gap: 15px;
    margin: 15px 0;
  }

  .mode-btn {
    padding: 8px 16px;
    background: #333;
    color: #d7fc09;
    border: 1px solid #d7fc09;
    border-radius: 5px;
    cursor: pointer;
  }

  .mode-btn.active {
    background: #d7fc09;
    color: #000;
  }
</style>

<div class="container">
  <h1>{{ __('chats.create_chat_title') }}</h1>

  <div class="mode-toggle">
    <div class="mode-btn active" data-mode="group" onclick="setChatMode('group')">
      {{ __('chats.group_chat') }}
    </div>
    <div class="mode-btn" data-mode="direct" onclick="setChatMode('direct')">
      {{ __('chats.direct_chat') }}
    </div>
  </div>

  <form id="createChatForm" method="POST" action="{{ route('chats.store') }}">
    @csrf
    <input type="hidden" name="chat_type" id="chatType" value="group">

    <div class="form-group">
      <label for="chatName">{{ __('chats.chat_name_label') }}</label>
      <input type="text" class="input_dark" id="chatName" name="name" value="{{ old('name') }}">
      <div class="form-text text-muted">{{ __('chats.group_name_optional') }}</div>
    </div>

    <div class="form-group">
      <label>{{ __('chats.participants_label') }}</label>
      <input type="text" id="userSearch" class="input_dark" placeholder="{{ __('chats.search_users') }}">

      <div class="selected-users" id="selectedUsers"></div>

      <input type="hidden" name="users" id="selectedUsersInput" value="">

      <div class="user-list" id="userList">
        @foreach ($users as $user)
          <div class="user-item" data-id="{{ $user['id'] }}" data-name="{{ $user['name'] }}" data-avatar="{{ $user['avatar'] }}">
            <img src="{{ $user['avatar'] }}" alt="{{ $user['name'] }}">
            <span class="name">{{ $user['name'] }}</span>
          </div>
        @endforeach
      </div>
    </div>

    <button type="submit" class="des-btn">{{ __('chats.create') }}</button>
  </form>
</div>

<script>
  let selectedUsers = new Set();
  let chatMode = 'group';

  function setChatMode(mode) {
    chatMode = mode;
    document.getElementById('chatType').value = mode;
    
    // Обновляем UI кнопок
    document.querySelectorAll('.mode-btn').forEach(btn => btn.classList.remove('active'));
    document.querySelector(`.mode-btn[data-mode="${mode}"]`).classList.add('active');

    // В личном чате — макс. 1 участник
    if (mode === 'direct') {
      if (selectedUsers.size > 1) {
        // Оставляем только первого
        const first = [...selectedUsers][0];
        selectedUsers.clear();
        selectedUsers.add(first);
      }
    }

    updateSelectedUsersUI();
    toggleChatName();
  }

  function toggleChatName() {
    const nameField = document.getElementById('chatName');
    if (chatMode === 'direct') {
      nameField.disabled = true;
      nameField.placeholder = "{{ __('chats.direct_chat_no_name') }}";
    } else {
      nameField.disabled = false;
      nameField.placeholder = "{{ __('chats.chat_name_label') }}";
    }
  }

  function updateSelectedUsersUI() {
    const container = document.getElementById('selectedUsers');
    container.innerHTML = '';

    selectedUsers.forEach(id => {
      const el = document.querySelector(`.user-item[data-id="${id}"]`);
      if (!el) return;

      const name = el.dataset.name;
      const avatar = el.dataset.avatar;

      const tag = document.createElement('div');
      tag.className = 'selected-user';
      tag.innerHTML = `
        <img src="${avatar}" alt="${name}">
        <span>${name}</span>
        <span style="cursor:pointer;color:#ff6b6b;" onclick="removeUser(${id})">×</span>
      `;
      container.appendChild(tag);
    });

    // Обновляем скрытое поле
    document.getElementById('selectedUsersInput').value = JSON.stringify([...selectedUsers]);
  }

  function removeUser(id) {
    if (chatMode === 'direct') {
      selectedUsers.clear();
    } else {
      selectedUsers.delete(id);
    }
    updateUI();
  }

  function updateUI() {
    // Обновляем выделение
    document.querySelectorAll('.user-item').forEach(item => {
      const id = parseInt(item.dataset.id);
      if (selectedUsers.has(id)) {
        item.classList.add('selected');
      } else {
        item.classList.remove('selected');
      }
    });
    updateSelectedUsersUI();
  }

  // Инициализация
  document.querySelectorAll('.user-item').forEach(item => {
    item.addEventListener('click', () => {
      const id = parseInt(item.dataset.id);
      
      if (chatMode === 'direct') {
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

  // Поиск
  document.getElementById('userSearch').addEventListener('input', (e) => {
    const term = e.target.value.toLowerCase();
    document.querySelectorAll('.user-item').forEach(item => {
      const name = item.dataset.name.toLowerCase();
      item.style.display = name.includes(term) ? 'flex' : 'none';
    });
  });

  // Отправка формы — валидация
//   document.getElementById('createChatForm').addEventListener('submit', (e) => {
//     if (selectedUsers.size === 0) {
//       e.preventDefault();
//       alert("{{ __('chats.select_at_least_one_user') }}");
//       return;
//     }
//     if (chatMode === 'direct' && selectedUsers.size !== 1) {
//       e.preventDefault();
//       alert("{{ __('chats.select_exactly_one_user') }}");
//       return;
//     }
//   });

  // Инициализация
  toggleChatName();
</script>
@endsection