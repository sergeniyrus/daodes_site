@extends('template')
@section('title_page')
    Биржа заданий
@endsection
@section('main')
<style>
    

    .task-details, .bid, form {
         background-color: #3a3b3c; /* Тёмно-серый фон для блоков */
        border-radius: 10px; /* Скруглённые углы */
        border:2px solid #f8f9fa; 
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Тень для объёма */
    }

    label {
        color: #f8f9fa;
    }

    input, textarea {
        background-color: #494a4b;
        color: #fff;
        border: 1px solid #6c757d;
        border-radius: 5px;
        width: 100%;
        padding: 10px;
        margin-bottom: 15px;
    }

    input[type="number"], input[type="date"], textarea {
        max-width: 300px; /* Ограничим ширину полей для ввода */
    }

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

<div class="container my-5">
    <div class="task-details">
        <h1>{{ $task->title }}</h1>
        <p>{{ $task->description }}</p>
        <p><strong>Бюджет:</strong> {{ $task->budget }} руб.</p>
        <p><strong>Срок:</strong> {{ $task->deadline }}</p>
    </div>

    <div class="bids-section">
        <h3>Предложения</h3>
        @foreach($task->bids as $bid)
            <div class="bid">
                <p><strong>Фрилансер:</strong> {{ $bid->user->name }}</p>
                <p><strong>Цена:</strong> {{ $bid->price }} руб.</p>
                <p><strong>Срок:</strong> {{ $bid->deadline }}</p>
                <p><strong>Комментарий:</strong> {{ $bid->comment }}</p>
            </div>
        @endforeach
    </div>

    @if(Auth::check() && Auth::id() !== $task->user_id)
        <div class="bid-form">
            <form action="{{ route('tasks.bid', $task) }}" method="POST">
                @csrf
                <div>
                    <label for="price">Цена:</label>
                    <input type="number" name="price" style="color: rgb(19, 18, 17)" required>
                </div>
                <div>
                    <label for="deadline">Срок:</label>
                    <input type="date" name="deadline" style="color: rgb(19, 18, 17)" required>
                </div>
                <div>
                    <label for="comment">Комментарий (необязательно):</label>
                    <textarea name="comment"></textarea>
                </div>
                <button type="submit">Подать предложение</button>
            </form>
        </div>
    @endif
</div>
@endsection
