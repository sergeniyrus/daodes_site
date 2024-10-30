@extends('template')

@section('title_page')
    Биржа заданий
@endsection

@section('main')
    <style>
        .task-details,
        .bid,
        form {
            background-color: #0b0c18ce;
            padding: 20px;
            margin: 0px auto 20px auto;
            width: 90%;
        }

        .rating-stars {
            display: flex;
            gap: 5px;
        }

        .star {
            font-size: 2rem;
            color: transparent;
            cursor: pointer;
            border-radius: 5px;
            padding: 2px;
        }

        .star_off {
            border: 1px solid #ffdf00;
        }

        .star.filled {
            color: #ffdf00;
            border-color: #ffdf00;
        }

        .timer {
            font-size: 1.5rem;
            color: #f8f9fa;
            margin-top: 10px;
        }

        .task-details {
            border-radius: 10px;
            border: 2px solid #f8f9fa;
            margin: 20px auto;
        }

        .bid {
            border: 1px solid #d7fc09;
            border-radius: 10px;
        }

        .bid-form {
            padding: 20px;
            border: 2px solid #007bff;
            border-top: none;
            width: 45%;
            margin: 0% auto;
        }

        .form-group {
            margin: 20px 50px;
        }

        .task-line {
            color: #00ccff;
        }

        .task-line2 {
            color: #ffffff;
        }

        .task-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: #d7fc09;
            margin-top: 10px;
        }

        .task-title {
            flex: 1;
            text-align: center;
            color: #00ccff;
        }

        .task-wrapper {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .task-budget,
        .task-deadline,
        .task-category {
            font-size: 0.875rem;
            margin: 0 10px;
        }

        .bid-title {
            text-align: center;
            color: #00ccff;
            margin: 20px;
        }

        /* основные для кнопок */

        .button-container {
    text-align: center;
    background: none;
}

.blue_btn {
    display: inline-block;
    color: #ffffff;
    font-size: large;
    background: #0b0c18;
    padding: 15px 30px;
    border: 1px solid #d7fc09;
    border-radius: 10px;
    box-shadow: 0 0 20px #000;
    transition: box-shadow 0.3s ease, transform 0.3s ease;
    gap: 15px;
}

.blue_btn:hover {
    box-shadow: 0 0 20px #d7fc09, 0 0 40px #d7fc09, 0 0 60px #d7fc09;
    transform: scale(1.05);
    color: #ffffff;
    background: #0b0c18;
}
.icon-like,
.icon-dislike,
.icon-edit,
.icon-delete {
    font-size: 1.2em; /* Размер иконок */
    margin-right: 5px; /* Отступ справа */
    color: #d7fc09; /* Общий цвет иконок, если нужен */
}

/* Индивидуальные цвета для каждой иконки */
.icon-like {
    color: #4CAF50; /* Зеленый для лайка */
}

.icon-dislike {
    color: #FF5722; /* Красный для дизлайка */
}

.icon-edit {
    color: #2196F3; /* Синий для редактирования */
}

.icon-delete {
    color: #F44336; /* Красный для удаления */
}

form {
    background-color: #0b0c18ce;
    padding: 20px;
    margin: 0px auto 20px auto;
    width: 90%;
}

input,
textarea {
    background-color: #000000;
    color: #fff;
    border: 1px solid #a0ff08;
    border-radius: 5px;
    width: 100%;
    padding: 10px;
    margin: 10px auto 15px auto;
}

label {
    color: #f8f9fa;
}

@media (max-width: 768px) {
    .task-wrapper {
        flex-direction: column;
        align-items: center;
    }

    .task-info {
        justify-content: space-around;
        width: 100%;
        margin-top: 10px;
    }

    .task-info p {
        margin: 5px 0;
    }
}

@media (max-width: 480px) {
    .task-info {
        flex-direction: column;
        align-items: center;
    }
}

    </style>
    <!-- Задание биржи -->
    <div class="task-details">
        <div class="bid">
            <div class="task-wrapper">
                <div class="task-title">
                    <h1 style="text-align: center">{{ $task->title }}</h1>
                </div>
                <div class="task-info">
                    <p class="task-category"><i class="fas fa-folder-open"></i>
                        {{ $task->category ? $task->category->name : 'Без категории' }}</p>
                    <p class="task-budget"><i class="fas fa-dollar-sign"></i> {{ $task->budget }}</p>
                    <p class="task-deadline"><i class="fas fa-clock"></i> {{ $task->deadline }}</p>
                </div>
            </div>
            <div>
                <p>{{ $task->description }}</p>
            </div>
        </div>

        <br>

        <!-- Таймер задания, виден всем пользователям -->
        @if ($task->status === 'in_progress')
            <div id="timer" class="timer" style="display:none;"></div>
            <p id="start_time_display" style="color: #f8f9fa; font-size: 1.2rem;"></p>
            <p id="end_time_display" style="color: #f8f9fa; font-size: 1.2rem;"></p>
            <p id="current_time_display" style="color: #f8f9fa; font-size: 1.2rem;"></p>
        @endif

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
        <div class="button-container">
            <!-- Лайк и дизлайк -->
            <form action="{{ route('tasks.like', $task) }}" method="POST" class="likebtn" style="display:inline;">
                @csrf
                <button type="submit" class="blue_btn">
                    <i class="fas fa-thumbs-up icon-like"></i> <!-- Иконка лайка -->
                    ({{ $task->votes()->where('is_like', true)->count() }})
                </button>
            </form>
            <form action="{{ route('tasks.dislike', $task) }}" method="POST" class="likebtn" style="display:inline;">
                @csrf
                <button type="submit" class="blue_btn">
                    <i class="fas fa-thumbs-down icon-dislike"></i> <!-- Иконка дизлайка -->
                    ({{ $task->votes()->where('is_like', false)->count() }})
                </button>
            </form>

            @if (Auth::id() == $task->user_id && !$task->accepted_bid_id)
                <form action="{{ route('tasks.edit', $task) }}" method="GET" class="likebtn" style="display:inline;">
                    @csrf
                    <button type="submit" class="blue_btn" title="Редактировать">
                        <i class="fas fa-edit icon-edit"></i> <!-- Иконка редактирования -->
                    </button>
                </form>

                <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="likebtn" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="blue_btn" title="Удалить">
                        <i class="fas fa-trash-alt icon-delete"></i> <!-- Иконка удаления -->
                    </button>
                </form>
            @endif

            @if ($task->accepted_bid_id && !$task->in_progress && Auth::id() == $task->user_id)
                <form action="{{ route('tasks.start_work', $task) }}" method="POST" style="display:inline;"
                    class="likebtn">
                    @csrf
                    <button type="submit" class="blue_btn">🚀 Приступить</button>
                </form>
            @endif

            @if ($task->in_progress && $task->status === 'on_review')
                @if (Auth::id() == $task->user_id)
                    <form action="{{ route('tasks.complete', $task) }}" method="POST" class="likebtn"
                        style="display:inline;">
                        @csrf
                        <button type="submit" class="blue_btn">✅ Задание выполнено</button>
                    </form>

                    <!-- Кнопка "Продолжить" добавлена здесь -->
                    <form action="{{ route('tasks.continue', $task) }}" method="POST" class="likebtn"
                        style="display:inline;">
                        @csrf
                        <button type="submit" class="blue_btn">▶️ Продолжить</button>
                    </form>

                    <form action="{{ route('tasks.fail', $task) }}" method="POST" class="likebtn" style="display:inline;">
                        @csrf
                        <button type="submit" class="blue_btn">❌ Задание провалено</button>
                    </form>
                @endif
            @endif
        </div>
        <br>

        @if (session('success'))
            <div class="alert alert-success" style="color:#ffdf00; text-align:center">
                {{ session('success') }}
            </div>
        @endif

        <br>
        <hr>

        <!-- Раздел предложений -->
        <div class="bids-section">
            <div class="bid-title">
                <h1>
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
                </h1>
            </div>

            @if ($task->accepted_bid_id)
                @php
                    $acceptedBid = $task
                        ->bids()
                        ->where('id', $task->accepted_bid_id)
                        ->first();
                @endphp
                <div class="bid">
                    <p><strong class="task-line">Фрилансер:</strong> {{ $acceptedBid->user->name }}</p>
                    <p><strong class="task-line">Цена:</strong> {{ $acceptedBid->price }} DESCoin</p>
                    <p><strong class="task-line">Время выполнения:</strong> {{ $acceptedBid->days }} дней
                        {{ $acceptedBid->hours }} часов</p>
                    <p><strong class="task-line">Комментарий:</strong> {{ $acceptedBid->comment }}</p>

                    @if ($task->accepted_bid_id && !$task->in_progress && Auth::id() == $task->acceptedBid->user_id)
                        <form action="{{ route('tasks.start_work', $task) }}" method="POST" class="likebtn"
                            style="display:inline;">
                            @csrf
                            <br>
                            <button type="submit" class="blue_btn">🚀 Приступить</button>
                        </form>
                    @endif

                    @if (Auth::id() == $acceptedBid->user_id)
                        @if ($task->status === 'in_progress' && !$task->completed)
                            <form action="{{ route('tasks.freelancer-complete', $task) }}" method="POST" class="likebtn">
                                @csrf
                                <button type="submit" class="blue_btn">✅ Задание выполнено</button>
                            </form>
                        @elseif ($task->status === 'on_review')
                            <p style="text-align: center; color:#ffdf00"><span>Задание на проверке</span></p>
                        @endif
                    @endif
                </div>
            @else
                @foreach ($task->bids as $bid)
                    <div class="bid">
                        <p class="task-line2"><strong class="task-line">Фрилансер:</strong> {{ $bid->user->name }}</p>
                        <p class="task-line2"><strong class="task-line">Цена:</strong> {{ $bid->price }} DESCoin</p>
                        <p class="task-line2"><strong class="task-line">Время выполнения:</strong> {{ $bid->days }}
                            дней {{ $bid->hours }} часов</p>
                        <p class="task-line2"><strong class="task-line">Комментарий:</strong> {{ $bid->comment }}</p>

                        @if (Auth::id() == $task->user_id && !$task->accepted_bid_id)
                            <form action="{{ route('bids.accept', $bid) }}" method="POST" style="display:inline;">
                                @csrf
                                <br><button type="submit" class="blue_btn">✔️ Принять предложение</button>
                            </form>
                        @endif
                    </div>
                @endforeach
            @endif
        </div>

        <!-- Раздел оценки исполнителя -->
        @if ($task->completed && $task->accepted_bid_id && Auth::id() == $task->user_id)
            <div class="rating-form">
                <h3>Оценить исполнителя</h3>
                <form action="{{ route('tasks.rate', $task) }}" method="POST">
                    @csrf
                    <div class="rating-stars">
                        @for ($i = 1; $i <= 10; $i++)
                            <span class="star star_off" data-value="{{ $i }}">★</span>
                        @endfor
                    </div>
                    <input type="hidden" name="rating" id="rating" value="0">
                    <button type="submit" class="blue_btn">Поставить оценку</button>
                </form>
            </div>
        @endif

        <!-- Раздел для подачи предложения -->
        @if (Auth::check() && Auth::id() !== $task->user_id && !$task->accepted_bid_id)
            @if ($task->bids()->where('user_id', Auth::id())->exists())
                <p style="text-align: center; color:#ffdf00">Вы уже подали предложение на это задание.</p>
            @else
                <div class="bid-form">
                    <form action="{{ route('tasks.bid', $task) }}" method="POST">
                        @csrf
                        <fieldset>
                            <legend>
                                <h3 style="text-align: center">Подать предложение</h3>
                            </legend>
                            <div class="form-group">
                                <label for="price">Цена (DESCoin):</label>
                                <input type="number" name="price" id="price"
                                    style="color: #d7fc09; font-size:xx-large" required>
                            </div>
                            <div class="form-group">
                                <label for="days">Срок выполнения (дни):</label>
                                <input type="number" name="days" id="days"
                                    style="color: #d7fc09; font-size:xx-large" required>
                            </div>
                            <div class="form-group">
                                <label for="hours">Срок выполнения (часы):</label>
                                <input type="number" name="hours" id="hours"
                                    style="color: #d7fc09; font-size:xx-large" required>
                            </div>
                            <div class="form-group">
                                <label for="comment">Комментарий:</label>
                                <textarea name="comment" id="comment" style="color: #d7fc09; font-size:xx-large" rows="3"></textarea>
                            </div>
                            <button type="submit" class="blue_btn">Отправить предложение</button>
                        </fieldset>
                    </form>
                </div>
            @endif
        @endif
    </div>



    <script>
        // Скрипт для динамического выставления рейтинга
        document.querySelectorAll('.star').forEach(star => {
            star.addEventListener('click', function() {
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

                timerDiv.innerHTML =
                    `До завершения работы над заданием осталось: ${daysLeft} дн. ${hoursLeft} ч. ${minutes} мин. ${seconds} сек.`;
            }, 1000);

            // Лог для проверки значений
            console.log("Переданные значения: дни = ", days, "часы = ", hours);


        }

        // Если задача в работе, запускаем таймер при загрузке страницы
        @if ($task->in_progress && $task->start_time)
            @php
                // Получаем предложение, которое было принято
                $acceptedBid = $task
                    ->bids()
                    ->where('id', $task->accepted_bid_id)
                    ->first();
            @endphp
            @if ($acceptedBid)
                startTimer({{ $acceptedBid->days }}, {{ $acceptedBid->hours }}, '{{ $task->start_time }}');
            @endif
        @endif

        function stopTimer() {
            clearInterval(timer); // Здесь 'timer' — это переменная, где хранится ваш таймер
        }
    </script>


@endsection
