@extends('template')
@section('title_page')
    Биржа заданий
@endsection
@section('main')
<style>
    button {
        background-color: #007bff;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        color: #fff;
        cursor: pointer;
        font-size: 1rem;
    }

    button:hover {
        background-color: #0056b3;
    }
</style>

<div class="container new_post">
  <div class="name_str">
  <h1>Создать новое задание</h1>
  </div>
  
  <!-- Вывод флеш-сообщений об успехе -->
  @if(session('success'))
      <div class="alert alert-success">
          {{ session('success') }}
      </div>
  @endif

  <!-- Вывод ошибок валидации -->
  @if($errors->any())
      <div class="alert alert-danger">
          <ul>
              @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
              @endforeach
          </ul>
      </div>
  @endif

  <!-- Форма создания задачи -->
  <form action="{{ route('tasks.store') }}" method="POST">
    @csrf

    <div class="form-group">
        <label for="title" style="color: antiquewhite">Заголовок задачи:</label></br>
        <input type="text" name="title" id="title" class="form-control" required>
    </div>

    <div class="form-group">
        <label for="description" style="color: antiquewhite">Описание задачи:</label></br>
        <textarea name="description" id="description" class="form-control" rows="5" required></textarea>
    </div>
    <div class="form-group">
        <label for="deadline" style="color: antiquewhite">Дедлайн:</label></br>
        <input type="date" name="deadline" id="deadline" class="form-control" required>
    </div>

    <div class="form-group">
        <label for="budget" style="color: antiquewhite">Бюджет:</label></br>
        <input type="number" name="budget" id="budget" class="form-control" step="0.01" required>
    </div>

    <!-- Добавляем выбор категории -->
    <div class="form-group">
        <label for="category_id" style="color: antiquewhite">Категория:</label></br>
        <select name="category_id" id="category_id" class="form-control" required>
            <option value="">Выберите категорию</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </select>
    </div>

    <br>

    <button type="submit" class="btn btn-primary" style="color: antiquewhite">Создать задачу</button>
  </form>

</div>
@endsection
