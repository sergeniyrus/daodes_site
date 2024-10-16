@extends('template')

@section('title_page')
    Биржа заданий
@endsection

@section('main')
    <style>
        .task-details,
        .bid,
        form {
            background-color: #3a3b3c;
            border-radius: 10px;
            border: 2px solid #f8f9fa;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        label {
            color: #f8f9fa;
        }

        input,
        textarea {
            background-color: #494a4b;
            color: #fff;
            border: 1px solid #6c757d;
            border-radius: 5px;
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
        }

        button {
            background-color: #007bff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            color: #fff;
            cursor: pointer;
            font-size: 1rem;
            margin-right: 10px;
        }

        button:hover {
            background-color: #0056b3;
        }

        .btn-warning {
            background-color: #02bac0;
            color: #212529;
        }

        .btn-danger {
            background-color: #dc3545;
            color: #fff;
        }

        .btn-success {
            background-color: #28a745;
            color: #fff;
        }

        .btn-danger:hover,
        .btn-warning:hover,
        .btn-success:hover {
            filter: brightness(0.9);
        }

        .rating-stars {
            display: flex;
            gap: 5px;
        }

        .star {
            font-size: 2rem;
            color: transparent;
            cursor: pointer;
            /*border: 1px solid #ffdf00;  Добавление контура для звёзд */
            border-radius: 5px;
            padding: 2px;
        }

        .star_off {
            border: 1px solid #ffdf00; /* Добавление контура для звёзд */
        }

        .star.filled {
            color: #ffdf00; /* Желтый цвет для заполненных звёзд */
            border-color: #ffdf00; /* Цвет контура для заполненных звёзд */
        }

        .timer {
            font-size: 1.5rem;
            color: #f8f9fa;
            margin-top: 10px;
        }
    </style>

    <div class="container my-5">
        <div class="task-details">
            <h1>{{ $task->title }}</h1>
            <p>{{ $task->description }}</p>
            <p><strong>Категория:</strong> {{ $task->category ? $task->category->name : 'Без категории' }}</p>
            <p><strong>Бюджет:</strong> {{ $task->budget }} руб.</p>
            <p><strong>Срок:</strong> {{ $task->deadline->format('Y-m-d H:i:s') }}</p><br>

            <!-- Показать информацию о завершенной задаче и её рейтинг -->
            @if ($task->completed && $task->rating)
                <div class="task-rating">
                    <p><strong>Задача выполнена и оценена на:</strong></p>
                    <div class="rating-stars">
                        @for ($i = 1; $i <= 10; $i++)
                            <span class="star {{ $i <= $task->rating ? 'filled' : '' }}">★</span>
                        @endfor
                    </div>
                </div><br>
            @endif

            <!-- Кнопки управления заданием -->
            <div class="task-controls">
                <!-- Лайк и дизлайк -->
                <form action="{{ route('tasks.like', $task) }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-success">
                        👍 Лайк ({{ $task->votes()->where('is_like', true)->count() }})
                    </button>
                </form>
                <form action="{{ route('tasks.dislike', $task) }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-danger">
                        👎 Дизлайк ({{ $task->votes()->where('is_like', false)->count() }})
                    </button>
                </form>

                <!-- Кнопки редактировать и удалить для автора задания -->
                @if (Auth::id() == $task->user_id)
                    <form action="{{ route('tasks.edit', $task) }}" method="GET" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn-warning">✏️ Редактировать</button>
                    </form>

                    <form action="{{ route('tasks.destroy', $task) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-danger">🗑️ Удалить</button>
                    </form>

                    <!-- Кнопка "Приступить к заданию" для автора -->
                    @if ($task->accepted_bid_id && !$task->in_progress)
                        <form action="{{ route('tasks.start_work', $task) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn-warning">🚀 Приступить к заданию</button>
                        </form>
                    @endif
                @endif

                @if($task->in_progress && !$task->completed)
                    <!-- Кнопка "Задание выполнено" -->
                    <form action="{{ route('tasks.complete', $task) }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn-success">✅ Задание выполнено</button>
                    </form>

                    <!-- Кнопка "Задание провалено" -->
                    <form action="{{ route('tasks.fail', $task) }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn-danger">❌ Задание провалено</button>
                    </form>
                @endif
            </div>
            <br>
            <!-- Раздел предложений -->
            <div class="bids-section">
                <h3>Предложения</h3><br>
                @foreach ($task->bids as $bid)
                    <div class="bid">
                        <p><strong>Фрилансер:</strong> {{ $bid->user->name }}</p>
                        <p><strong>Цена:</strong> {{ $bid->price }} руб.</p>
                        <p><strong>Время выполнения:</strong> {{ $bid->days }} дней {{ $bid->hours }} часов</p>
                        <p><strong>Комментарий:</strong> {{ $bid->comment }}</p>
                        <br>

                        <!-- Кнопка для фрилансера "Приступить к заданию", если предложение принято -->
                        @if (Auth::id() == $bid->user_id && $task->accepted_bid_id == $bid->id && !$task->in_progress)
                            <form action="{{ route('tasks.start_work', $task) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn-warning">🚀 Приступить к заданию</button>
                            </form>
                        @endif

                        <!-- Кнопка для автора задания "Принять предложение" -->
                        @if (Auth::id() == $task->user_id && !$task->accepted_bid_id)
                            <form action="{{ route('bids.accept', $bid) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-success">
                                    ✔️ Принять предложение
                                </button>
                            </form>
                        @endif
                    </div>
                @endforeach
            </div>

            <!-- Раздел оценивания -->
            @if ($task->completed && Auth::id() == $task->user_id && !$task->rating)
                <div class="rating-section">
                    <h3>Оценить исполнителя</h3>
                    <form action="{{ route('tasks.rate', $task) }}" method="POST">
                        @csrf
                        <div class="rating-stars">
                            @for ($i = 1; $i <= 10; $i++)
                                <span class="star star_off" data-value="{{ $i }}">★</span>
                            @endfor
                        </div>
                        <input type="hidden" name="rating" id="rating" value="0">
                        <button type="submit" class="btn btn-primary mt-3">Оставить оценку</button>
                    </form>
                </div>
            @endif

            <!-- Раздел для подачи предложения -->
            @if (Auth::check() && Auth::id() !== $task->user_id && !$task->accepted_bid_id)
                @if ($task->bids()->where('user_id', Auth::id())->exists())
                    <p>Вы уже подали предложение на это задание.</p>
                @else
                    <div class="bid-form">
                        <h3>Подать предложение</h3>
                        <form action="{{ route('bids.store', ['task_id' => $task->id]) }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="price">Цена (в рублях):</label>
                                <input type="number" name="price" id="price" required>
                            </div>
                            <div class="form-group">
                                <label for="days">Срок выполнения (дни):</label>
                                <input type="number" name="days" id="days" required>
                            </div>
                            <div class="form-group">
                                <label for="hours">Срок выполнения (часы):</label>
                                <input type="number" name="hours" id="hours" required>
                            </div>
                            <div class="form-group">
                                <label for="comment">Комментарий:</label>
                                <textarea name="comment" id="comment" rows="3"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Отправить предложение</button>
                        </form>
                    </div>
                @endif
            @endif
        </div>
    </div>

    <script>
        // Скрипт для динамического выставления рейтинга
        document.querySelectorAll('.star').forEach(star => {
            star.addEventListener('click', function () {
                const ratingValue = this.getAttribute('data-value');
                document.getElementById('rating').value = ratingValue;

                document.querySelectorAll('.star').forEach(s => {
                    s.classList.remove('filled');
                });

                this.classList.add('filled');
                let prevSibling = this.previousElementSibling;
                while (prevSibling) {
                    prevSibling.classList.add('filled');
                    prevSibling = prevSibling.previousElementSibling;
                }
            });
        });
    </script>
@endsection
