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
        .task-details, .bid, .task-details  {
            border-radius: 10px;
            border: 2px solid #f8f9fa;
        }

    </style>
<!-- Задание биржи-->
    <div class="container my-5">
        <div class="task-details">
            <div class="bid">
            <h1 style="text-align: center">{{ $task->title }}</h1>
            <p>{{ $task->description }}</p>
            </div>
            <p style="text-align: center"><strong>Категория:</strong> {{ $task->category ? $task->category->name : 'Без категории' }}
            <strong> Бюджет:</strong> {{ $task->budget }} руб.
            <strong> Срок:</strong> {{ $task->deadline->format('Y-m-d H:i:s') }}</p><br>

            <!-- Показать информацию о завершенной задаче и её рейтинг -->
@if ($task->completed && $task->rating)
<div class="task-rating" style="text-align: center">
    <p><strong>Задание выполнено и оценено на:</strong></p>
    <div class="rating-stars" style="display: flex; justify-content: center; align-items: center;">
        @for ($i = 1; $i <= 10; $i++)
            <span class="star {{ $i <= $task->rating ? 'filled' : '' }}" style="font-size: 24px;">★</span>
        @endfor
    </div>
</div><br>
@endif

            <!-- Кнопки управления заданием -->
            <div class="task-controls" style="text-align: center">
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
                

                <!-- Кнопки для управления статусом задания -->
                @if ($task->accepted_bid_id && !$task->in_progress && Auth::id() == $task->user_id)
                    <!-- Кнопка "Приступить к заданию" для автора задания -->
                    <form action="{{ route('tasks.start_work', $task) }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn-warning">🚀 Приступить к заданию</button>
                    </form>
                @endif

                @if($task->in_progress && $task->status === 'on_review')
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
                @endif
            </div>
            <br>
            <!-- вывод информационных сообщений  --> 
            @if (session('success'))
            <div class="alert alert-success" style="color:#ffdf00; text-align:center">
                {{ session('success') }}
            </div>
        @endif      
            <!-- Таймер -->
            @if ($task->status === 'in_progress') <!-- Убедитесь, что 'in_progress' - это правильное значение для статуса -->
            <div id="timer" class="timer" style="display:none;"></div>
            <p id="start_time_display" style="color: #f8f9fa; font-size: 1.2rem;"></p> <!-- Время начала -->
            <p id="end_time_display" style="color: #f8f9fa; font-size: 1.2rem;"></p>   <!-- Время завершения -->
            <p id="current_time_display" style="color: #f8f9fa; font-size: 1.2rem;"></p> <!-- Текущее время UTC -->
@endif
<br> <hr>
            <!-- Раздел предложений -->
<div class="bids-section">
    <h2 style="text-align:center; color:#029ac0">
        @if ($task->status === 'completed')
            Задание выполнено
        @elseif ($task->status === 'on_review')
            Задание на проверке
        @elseif ($task->status === 'in_progress')
            Задание в работе
        @elseif ($task->accepted_bid_id)
            Принятое предложение
        @else
            Предложения исполнителей
        @endif
    </h2>
    

    @if ($task->accepted_bid_id)
        <!-- Если есть принятое предложение, то выводим только его -->
        @php
            $acceptedBid = $task->bids()->where('id', $task->accepted_bid_id)->first();
        @endphp
        <div class="bid">
            <p><strong>Фрилансер:</strong> {{ $acceptedBid->user->name }}</p>
            <p><strong>Цена:</strong> {{ $acceptedBid->price }} руб.</p>
            <p><strong>Время выполнения:</strong> {{ $acceptedBid->days }} дней {{ $acceptedBid->hours }} часов</p>
            <p><strong>Комментарий:</strong> {{ $acceptedBid->comment }}</p>

            <!-- Кнопка "Приступить к заданию" для фрилансера -->
            @if ($task->accepted_bid_id && !$task->in_progress && Auth::id() == $task->acceptedBid->user_id)            
            <form action="{{ route('tasks.start_work', $task) }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit" class="btn-warning">🚀 Приступить к заданию</button>
            </form>
        @endif
            <!-- Кнопка "Задание выполнено" для фрилансера -->
            @if (Auth::id() == $acceptedBid->user_id)
    @if ($task->status === 'in_progress' && !$task->completed)
        <form action="{{ route('tasks.freelancer-complete', $task) }}" method="POST" style="display:inline;">
            @csrf
            <button type="submit" class="btn-success">✅ Задание выполнено</button>
        </form>
    @elseif ($task->status === 'on_review')
        <p style="text-align: center; color:#ffdf00"><span>Задание на проверке</span><p>
    @endif
@endif
        </div>
    @else
        <!-- Если предложение не принято, показываем все предложения -->
        @foreach ($task->bids as $bid)
            <div class="bid">
                <p><strong>Фрилансер:</strong> {{ $bid->user->name }}</p>
                <p><strong>Цена:</strong> {{ $bid->price }} руб.</p>
                <p><strong>Время выполнения:</strong> {{ $bid->days }} дней {{ $bid->hours }} часов</p>
                <p><strong>Комментарий:</strong> {{ $bid->comment }}</p>

                <!-- Кнопка "Принять предложение" -->
                @if (Auth::id() == $task->user_id && !$task->accepted_bid_id)
                    <form action="{{ route('bids.accept', $bid) }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn btn-success">✔️ Принять предложение</button>
                    </form>
                @endif
            </div>
        @endforeach
    @endif
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
                        <button type="submit" class="btn btn-primary mt-3">Поставить оценку</button>
                    </form>
                </div>
            @endif

            <!-- Раздел для подачи предложения -->
            @if (Auth::check() && Auth::id() !== $task->user_id && !$task->accepted_bid_id)
                @if ($task->bids()->where('user_id', Auth::id())->exists())
                    <p style="text-align: center; color:#ffdf00">Вы уже подали предложение на это задание.</p>
                @else
                    <div class="bid-form">
                        <h3>Подать предложение</h3>
                        <form action="{{ route('tasks.bid', $task)  }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="price">Цена (в рублях):</label>
                                <input type="number" name="price" id="price" style="color: #000" required>
                            </div>
                            <div class="form-group">
                                <label for="days">Срок выполнения (дни):</label>
                                <input type="number" name="days" id="days" style="color: #000" required>
                            </div>
                            <div class="form-group">
                                <label for="hours">Срок выполнения (часы):</label>
                                <input type="number" name="hours" id="hours" style="color: #000" required>
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

    //скрипт таймера и времени

    let countdownTimer;

        function startTimer(days, hours, startTime) {
    // Конвертируем время начала в миллисекунды (UTC)
    const startTimeMillis = new Date(startTime + 'Z').getTime(); // Обратите внимание на добавление 'Z'

    // Преобразуем дни и часы в миллисекунды
    const totalTime = (days * 24 * 60 * 60 * 1000) + (hours * 60 * 60 * 1000);

    // Рассчитываем конечное время в миллисекундах
    const endTime = startTimeMillis + totalTime;

    // Отображаем таймер
    const timerDiv = document.getElementById('timer');
    timerDiv.style.display = 'block';

    // Отображаем время начала задачи
    const startDate = new Date(startTimeMillis);
    const startTimeDisplay = document.getElementById('start_time_display');
    startTimeDisplay.innerHTML = `Время начала работы: ${startDate.toUTCString()}`;

    // Рассчитываем и отображаем время завершения задачи
    const endDate = new Date(endTime);
    const endTimeDisplay = document.getElementById('end_time_display');
    endTimeDisplay.innerHTML = `Предполагаемое время завершения: ${endDate.toUTCString()}`;
    // Обновление текущего времени каждую секунду
    setInterval(function() {
        const now = new Date(); // Получаем текущее время
        const currentTimeDisplay = document.getElementById('current_time_display');
        currentTimeDisplay.innerHTML = `Текущее время UTC: ${now.toUTCString()}`;
    }, 1000);

    countdownTimer = setInterval(function() {
        // Получаем текущее время в UTC
        const now = new Date().getTime(); // getTime() всегда возвращает UTC

        const remainingTime = endTime - now;

        // Когда время вышло
        if (remainingTime <= 0) {
            clearInterval(countdownTimer);
            timerDiv.innerHTML = "Время вышло, задание на проверке.";
            return;
        }

        // Вычисление дней, часов, минут и секунд
        const seconds = Math.floor((remainingTime / 1000) % 60);
        const minutes = Math.floor((remainingTime / 1000 / 60) % 60);
        const hoursLeft = Math.floor((remainingTime / (1000 * 60 * 60)) % 24);
        const daysLeft = Math.floor(remainingTime / (1000 * 60 * 60 * 24));

        timerDiv.innerHTML = `До завершения работы над заданием осталось: ${daysLeft} дн. ${hoursLeft} ч. ${minutes} мин. ${seconds} сек.`;
    }, 1000);

    // Лог для проверки значений
    console.log("Переданные значения: дни = ", days, "часы = ", hours);

    
}

        // Если задача в работе, запускаем таймер при загрузке страницы
        @if($task->in_progress && $task->start_time)
            @php
                // Получаем предложение, которое было принято
                $acceptedBid = $task->bids()->where('id', $task->accepted_bid_id)->first();
            @endphp
            @if($acceptedBid)
                startTimer({{ $acceptedBid->days }}, {{ $acceptedBid->hours }}, '{{ $task->start_time }}');
            @endif
        @endif

        function stopTimer() {
    clearInterval(timer); // Здесь 'timer' — это переменная, где хранится ваш таймер
}


    </script>


@endsection
