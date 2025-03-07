@extends('template')
@section('title_page', 'Создать чат')
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

    .input_dark, textarea {
        background-color: #1a1a1a;
        color: #a0ff08;
        border: 1px solid #d7fc09;
        border-radius: 5px;
        width: 100%;
        padding: 12px;
        margin-top: 5px;
        transition: border 0.3s ease;
    }

    .input_dark:focus, textarea:focus {
        border: 1px solid #a0ff08;
        outline: none;
        box-shadow: 0 0 5px #d7fc09;
    }

    .blue_btn {
        display: inline-block;
        color: #ffffff;
        background: #0b0c18;
        padding: 10px 20px;
        font-size: 1.3rem;
        border: 1px solid gold;
        border-radius: 10px;
        transition: box-shadow 0.3s ease, transform 0.3s ease;
        text-decoration: none;
        margin: 20px auto; /* Центрирование кнопки */
        display: block; /* Чтобы margin-auto работал */
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

    .chat-messages {
        height: 400px;
        overflow-y: scroll;
        border: 1px solid #ddd;
        padding: 10px;
        border-radius: 5px;
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

    .form-group {
        margin-bottom: 20px; /* Отступ между элементами формы */
    }

    .form-group select {
        height: auto; /* Автоматическая высота */
        min-height: 4em; /* Минимум 4 строки */
        max-height: 10em; /* Максимум 10 строк */
        overflow-y: auto; /* Добавляем скролл, если элементов много */
    }

    .form-text.text-muted {
        margin-left: 16px; /* Отступ для текста подсказки */
        margin-top: 8px; /* Отступ сверху */
        color: #6c757d; /* Цвет текста подсказки */
    }

    .user-list {
        margin-top: 20px;
    }

    .user-list .user-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px;
        border-bottom: 1px solid #d7fc09;
    }

    .user-list .user-item:last-child {
        border-bottom: none;
    }

    .user-list .user-item button {
        background: #0b0c18;
        color: #ffffff;
        border: 1px solid gold;
        border-radius: 10px;
        padding: 5px 10px;
        cursor: pointer;
        transition: box-shadow 0.3s ease, transform 0.3s ease;
    }

    .user-list .user-item button:hover {
        box-shadow: 0 0 20px goldenrod;
        transform: scale(1.05);
    }
</style>
<div class="container">
  <h1>Создать чат</h1>
  @if ($errors->any())
      <div class="alert alert-danger">
          <ul>
              @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
              @endforeach
          </ul>
      </div>
  @endif
  <form method="POST" action="{{ route('chats.store') }}">
    @csrf
    <div class="form-group">
        <label for="name" class="form-label">Название чата</label>
        <input type="text" class="form-control input_dark" id="name" name="name" value="{{ old('name') }}" required>
        <!-- Отображение ошибки для поля "name" -->
        @error('name')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="form-group">
        <label for="users" class="form-label">Участники</label>
        <select multiple class="form-control input_dark" id="users" name="users[]" required>
            @foreach ($users as $user)
                @if ($user->id != 1 && $user->id != 2) <!-- Скрываем пользователей с id = 1 и id = 2 -->
                    <option value="{{ $user->id }}" {{ in_array($user->id, old('users', [])) ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                @endif
            @endforeach
        </select>
        <!-- Отображение ошибки для поля "users" -->
        @error('users')
            <div class="text-danger">{{ $message }}</div>
        @enderror
        <p class="form-text text-muted ml-4 mt-4">Удерживайте Ctrl (или Cmd на Mac) для выбора нескольких участников. </p>
        <p class="form-text text-muted ml-4">Удерживайте Ctrl + A, чтобы выбрать всех участников.</p>
    </div>
    <button type="submit" class="blue_btn">Создать</button>
</form>

  <!-- Список пользователей с кнопками для создания чата -->
  <div class="user-list">
      <h3>Создать чат с пользователем:</h3>
      @foreach ($users as $user)
          @if ($user->id != 1 && $user->id != 2) <!-- Скрываем пользователей с id = 1 и id = 2 -->
              <div class="user-item">
                  <span>{{ $user->name }}</span>
                  <form method="POST" action="{{ route('chats.createWithUser', $user->id) }}" style="display: inline;">
                      @csrf
                      <button type="submit" class="blue_btn">Создать чат</button>
                  </form>
              </div>
          @endif
      @endforeach
  </div>
</div>
<script>
    // Обработка Ctrl + A для выбора всех пользователей
    document.getElementById('users').addEventListener('keydown', function (e) {
        if (e.ctrlKey && e.key === 'a') { // Проверяем, нажаты ли Ctrl + A
            e.preventDefault(); // Отменяем стандартное поведение
            const options = this.options; // Получаем все элементы списка
            for (let i = 0; i < options.length; i++) {
                options[i].selected = true; // Выбираем все элементы
            }
        }
    });

    // Динамическое управление высотой списка
    const selectElement = document.getElementById('users');
    selectElement.addEventListener('focus', function () {
        this.size = Math.min(10, this.options.length); // Устанавливаем размер до 10 строк
    });
    selectElement.addEventListener('blur', function () {
        this.size = Math.min(4, this.options.length); // Возвращаем размер к 4 строкам
    });
</script>
@endsection