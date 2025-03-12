@extends('template')
@section('title_page')
    {{ __('tasks.title_page') }}
@endsection

@section('main')
    <style>
        .task-details,
        .bid {
            background-color: #0b0c18ce;
            padding: 20px;
            margin: 0px auto 20px auto;
            width: 90%;
        }

        form {

            padding: 20px;
            margin: 0px auto 20px auto;
            width: 90%;
        }

        .rating-stars,
        .rating-circle {
            gap: 5px;
            margin-bottom: 20px;
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
            text-align: center;
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
            margin-right: 20px;
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
            background-color: transparent !important;
            /* Убираем фон */
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
            font-size: 1.2em;
            margin-right: 5px;
            color: #d7fc09;
        }

        .icon-like {
            color: #4CAF50;
        }

        .icon-dislike {
            color: #FF5722;
        }

        .icon-edit {
            color: #2196F3;
        }

        .icon-delete {
            color: #F44336;
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

        .bid-form {
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

        .bid-form .form-group label {
            color: #d7fc09;
            font-size: 1.2rem;
            display: block;
            margin: 10px 0;
            text-align: left;
            font-weight: bold;

        }

        .input_dark,
        textarea {
            background-color: #1a1a1a;
            color: #a0ff08;
            border: 1px solid #d7fc09;
            border-radius: 5px;
            width: 100%;
            padding: 12px;
            margin-top: 5px;
            transition: border 0.3s ease;
        }

        .input_dark:focus,
        textarea:focus {
            border: 1px solid #a0ff08;
            outline: none;
            box-shadow: 0 0 5px #d7fc09;
        }

        .alert {
            padding: 5px;
            background-color: green;
            display: table;
            max-width: 100%;
            text-align: center;
            margin: 30px auto 0 auto;
            border: 1px solid gold;
            border-radius: 5px;
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



        .circle {
            font-size: 2rem;
            color: transparent;
            cursor: pointer;
            border-radius: 5px;
            padding: 2px;

        }

        .circle_off {

            border: 1px solid #ffdf00;
        }

        .circle.filled {
            color: #ff0000;
            /* Красный цвет для заполненных кружков */

        }

        .review-actions {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin: 20px 0;
        }
    </style>

    <!-- Task Exchange -->
    <div class="task-details">
        <div class="bid">
            <div class="task-wrapper">
                <div class="task-title">
                    <h1 style="text-align: center">{{ $task->title }}</h1>
                </div>
                <div class="task-info">
                    <p class="task-category"><i class="fas fa-folder-open"></i>
                        {{ $task->category ? $task->category->name : __('tasks.no_category') }}
                    </p>
                    <p class="task-budget"><i class="fas fa-dollar-sign"></i> {{ $task->budget }}</p>
                    <p class="task-deadline">
                        <i class="fas fa-clock"></i> {{ \Carbon\Carbon::parse($task->deadline)->translatedFormat('j.m.Y') }}
                    </p>
                    <div class="task-author">
                        <p><strong>&copy;</strong>
                            <a href="{{ route('user_profile.show', ['id' => $task->user_id]) }}" title="Profile"
                                style="color: #d7fc09; text-decoration: none;">
                                {{ $task->user->name }}
                            </a>
                        </p>
                    </div>
                </div>
            </div>
            <div>
                <p>{!! $task->content !!}</p>
            </div>
        </div>

        <br>

        <!-- Task timer, visible to all users -->
        @if ($task->status === 'in_progress')
            <div class="timer">
                <div id="timer" class="timer" style="display:none;"></div>
                <p id="start_time_display" style="color: #f8f9fa; font-size: 1.2rem;"></p>
                <p id="end_time_display" style="color: #f8f9fa; font-size: 1.2rem;"></p>
                <p id="current_time_display" style="color: #f8f9fa; font-size: 1.2rem;"></p>
            </div>
        @endif

        <!-- Task rating, visible if task is completed and rated -->
        @if ($task->status === 'completed' && $task->rating)
            <div class="task-rating" style="text-align: center">
                <p><strong>{{ __('tasks.task_completed_rated') }}</strong></p>
                <div class="rating-stars" style="display: flex; justify-content: center; align-items: center;">
                    @for ($i = 1; $i <= 10; $i++)
                        <span class="star {{ $i <= $task->rating ? 'filled' : '' }}" style="font-size: 24px;">★</span>
                    @endfor
                </div>
            </div>
            <br>
        @endif

        <!-- Task rating, visible if task is failed and rated -->
        @if ($task->status === 'failed' && $task->rating)
            <div class="task-rating" style="text-align: center">
                <p><strong>{{ __('tasks.task_failed_rated') }}</strong></p>
                <div class="rating-stars" style="display: flex; justify-content: center; align-items: center;">
                    @for ($i = 1; $i <= abs($task->rating); $i++)
                        <span class="circle filled" style="font-size: 24px;">💩</span>
                    @endfor
                </div>
            </div>
            <br>
        @endif

        <!-- Task management buttons -->
        <div class="button-container">
            @if (Auth::id() == $task->user_id && !$task->accepted_bid_id)
                <form action="{{ route('tasks.edit', $task) }}" method="GET" style="display:inline;">
                    @csrf
                    <button type="submit" class="blue_btn" title="{{ __('tasks.edit') }}">
                        <i class="fas fa-edit icon-edit"></i>
                    </button>
                </form>

                <form action="{{ route('tasks.destroy', $task) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="blue_btn" title="{{ __('tasks.delete') }}">
                        <i class="fas fa-trash-alt icon-delete"></i>
                    </button>
                </form>
            @endif

            @if ($task->status === 'negotiation')
                @if (Auth::id() == $task->acceptedBid->user_id)
                    <form action="{{ route('tasks.start_work', $task) }}" method="POST">
                        @csrf
                        <button type="submit" class="blue_btn">{{ __('tasks.start_work') }}</button>
                    </form>
                @endif
            @endif

            @if ($task->status === 'in_progress' && Auth::id() == $task->acceptedBid->user_id)
                <form action="{{ route('tasks.freelancerComplete', $task) }}" method="POST">
                    @csrf
                    <button type="submit" class="blue_btn">{{ __('tasks.done_check') }}</button>
                </form>
            @endif

            @if ($task->status === 'on_review' && Auth::id() == $task->user_id)
                <div class="review-actions">
                    <form action="{{ route('tasks.complete', $task) }}" method="POST">
                        @csrf
                        <button type="submit" class="blue_btn">{{ __('tasks.accept') }}</button>
                    </form>

                    <form action="{{ route('tasks.continue', $task) }}" method="POST">
                        @csrf
                        <button type="submit" class="blue_btn">{{ __('tasks.needs_more_work') }}</button>
                    </form>

                    <form action="{{ route('tasks.fail', $task) }}" method="POST">
                        @csrf
                        <button type="submit" class="blue_btn">{{ __('tasks.task_failed_button') }}</button>
                    </form>
                </div>
            @endif

            @if (session('success'))
                <div class="alert" style="color:#ffdf00; text-align:center">
                    {{ session('success') }}
                </div>
            @endif
        </div>
        <br>
        <br>
        <hr>

        <!-- Bids section -->
        <div class="bids-section">
            <div class="bid-title">
                <h1>
                    @if ($task->status === 'completed')
                        {{ __('tasks.task_completed') }}
                    @elseif ($task->status === 'on_review')
                        {{ __('tasks.task_on_review') }}
                    @elseif ($task->status === 'in_progress')
                        {{ __('tasks.task_in_progress') }}
                    @elseif ($task->status === 'failed')
                        {{ __('tasks.task_failed') }}
                    @elseif ($task->status === 'negotiation')
                        {{ __('tasks.offer_negotiation') }}
                    @else
                        {{ __('tasks.freelancer_offers') }}
                    @endif
                </h1>
            </div>

            @if ($task->accepted_bid_id)
                @php
                    $acceptedBid = $task->bids()->where('id', $task->accepted_bid_id)->first();
                @endphp

                <div class="bid">
                    <p><strong class="task-line">{{ __('tasks.freelancer') }}:</strong>
                        <a href="{{ route('user_profile.show', ['id' => $acceptedBid->user->id]) }}" title="Profile"
                            style="color: #d7fc09; text-decoration: none;">
                            {{ $acceptedBid->user->name }}
                        </a>
                    </p>
                    <p><strong class="task-line">{{ __('tasks.price') }}:</strong> {{ $acceptedBid->price }} $</p>
                    <p><strong class="task-line">{{ __('tasks.completion_time') }}:</strong> {{ $acceptedBid->days }} {{ __('tasks.completion_days') }}
                        {{ $acceptedBid->hours }} {{ __('tasks.completion_hours') }}</p>
                    <p><strong class="task-line">{{ __('tasks.comment') }}:</strong> {{ $acceptedBid->comment }}</p>
                </div>
            @else
                @foreach ($task->bids as $bid)
                    <div class="bid">
                        <p class="task-line2">
                            <strong class="task-line">{{ __('tasks.freelancer') }}:</strong>
                            <a href="{{ route('user_profile.show', ['id' => $bid->user->id]) }}" title="Profile"
                                style="color: #d7fc09; text-decoration: none;">
                                {{ $bid->user->name }}
                            </a>
                        </p>
                        <p class="task-line2"><strong class="task-line">{{ __('tasks.price') }}:</strong> {{ $bid->price }} DESCoin</p>
                        <p class="task-line2"><strong class="task-line">{{ __('tasks.completion_time') }}:</strong> {{ $bid->days }}
                            {{ __('tasks.completion_days') }} {{ $bid->hours }} {{ __('tasks.completion_hours') }}</p>
                        <p class="task-line2"><strong class="task-line">{{ __('tasks.comment') }}:</strong> {{ $bid->comment }}</p>

                        @if (Auth::id() == $task->user_id && !$task->accepted_bid_id)
                            <form action="{{ route('bids.accept', $bid) }}" method="POST" style="display:block;">
                                @csrf
                                <br><button type="submit" class="blue_btn">{{ __('tasks.accept_offer') }}</button>
                            </form>
                        @endif
                    </div>
                @endforeach
            @endif
        </div>

        <!-- Freelancer rating section -->
        @if ($task->status === 'completed' && Auth::id() == $task->user_id && $task->rating == null)
            <div class="rating-form">
                <div class="bid-title">{{ __('tasks.rate_freelancer') }}
                    <form action="{{ route('tasks.rate', $task) }}" method="POST">
                        @csrf
                        <div class="rating-stars">
                            @for ($i = 1; $i <= 10; $i++)
                                <span class="star star_off" data-value="{{ $i }}">★</span>
                            @endfor
                        </div>
                        <input type="hidden" name="rating" id="rating" value="0">
                        <button type="submit" class="blue_btn">{{ __('tasks.submit_rating') }}</button>
                    </form>
                </div>
            </div>
        @endif

        @if ($task->status === 'failed' && Auth::id() == $task->user_id && $task->rating == null)
            <div class="rating-form">
                <div class="bid-title">{{ __('tasks.rate_freelancer') }}
                    <form action="{{ route('tasks.rate', $task) }}" method="POST">
                        @csrf
                        <div class="rating-circle">
                            @for ($i = 1; $i <= 10; $i++)
                                <span class="circle circle_off" data-value="{{ -$i }}">💩</span>
                            @endfor
                        </div>
                        <input type="hidden" name="rating" id="rating" value="0">
                        <button type="submit" class="blue_btn">{{ __('tasks.submit_rating') }}</button>
                    </form>
                </div>
            </div>
        @endif

        <!-- Proposal submission section -->
        <div class="container my-5">
            @if (Auth::check() && Auth::id() !== $task->user_id && !$task->accepted_bid_id)
                @if ($task->bids()->where('user_id', Auth::id())->exists())
                    <p style="text-align: center; color:#ffdf00">{{ __('tasks.already_submitted') }}</p>
                @else
                    <div class="bid-form">
                        <form action="{{ route('tasks.bid', $task) }}" method="POST"
                            style="border: 1px solid #00ccff; border-radius: 15px;">
                            @csrf
                            <fieldset>
                                <legend style="text-align: center">
                                    <h3>{{ __('tasks.leave_suggestion') }}</h3>
                                </legend>

                                <!-- Вывод ошибок валидации -->
                                {{-- @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif --}}

                                <!-- Вывод сообщений об успехе или ошибке -->
                                {{-- @if (session('success'))
                                    <div class="alert alert-success">
                                        {{ session('success') }}
                                    </div>
                                @endif
                                @if (session('error'))
                                    <div class="alert alert-danger">
                                        {{ session('error') }}
                                    </div>
                                @endif --}}

                                <!-- Поле "Your price" -->
                                <div class="form-group">
                                    <label for="price">{{ __('tasks.your_price') }}:</label>
                                    <input type="number" name="price" id="price" class="input_dark" required>
                                </div>

                                <!-- Поле "Completion time (days)" -->
                                <div class="form-group">
                                    <label for="days">{{ __('tasks.completion_days') }}:</label>
                                    <input type="number" name="days" id="days" class="input_dark" required>
                                </div>

                                <!-- Поле "Completion time (hours)" -->
                                <div class="form-group">
                                    <label for="hours">{{ __('tasks.completion_hours') }}:</label>
                                    <input type="number" name="hours" id="hours" class="input_dark" required>

                                    <small class="form-text text-muted">{{ __('tasks.completion_hours_hint') }}</small>
                                </div>
                                <!-- Поле "Message" -->
                                <div class="form-group">
                                    <label for="comment">{{ __('tasks.message') }}:</label>
                                    <textarea name="comment" id="comment" class="input_dark" rows="3" maxlength="500" required></textarea>
                                    <small class="form-text text-muted">
                                        {{ __('tasks.message_hint') }}
                                    </small>
                                </div>

                                <!-- Кнопка отправки -->
                                <div class="text-center">
                                    <button type="submit" class="blue_btn">{{ __('tasks.submit_proposal') }}</button>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                @endif
            @endif
        </div>
    </div>

    <script>
        // Script for dynamic rating
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
    </script>
    <script>
        // Script for dynamic negative rating
        document.querySelectorAll('.circle').forEach(circle => {
            circle.addEventListener('click', function() {
                const ratingValue = this.getAttribute('data-value');
                document.getElementById('rating').value = ratingValue;

                document.querySelectorAll('.circle').forEach(c => {
                    c.classList.remove('filled');
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
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let countdownTimer;

            function startTimer(days, hours, startTime) {
                console.log("Received: ", days, " days,", hours, " hours, starting at", startTime);

                const startTimeMillis = new Date(startTime.replace(" ", "T") + "Z").getTime();
                if (isNaN(startTimeMillis)) {
                    console.error("Error: Invalid start time format:", startTime);
                    return;
                }

                const totalTime = (days * 24 * 60 * 60 * 1000) + (hours * 60 * 60 * 1000);
                const endTime = startTimeMillis + totalTime;

                const timerDiv = document.getElementById('timer');
                const startTimeDisplay = document.getElementById('start_time_display');
                const endTimeDisplay = document.getElementById('end_time_display');
                const currentTimeDisplay = document.getElementById('current_time_display');

                if (!timerDiv) {
                    console.error("Error: #timer element not found");
                    return;
                }

                timerDiv.style.display = 'block';

                if (startTimeDisplay) {
                    startTimeDisplay.innerHTML = `{{ __('tasks.start_time') }}: ${new Date(startTimeMillis).toLocaleString()}`;
                }

                if (endTimeDisplay) {
                    endTimeDisplay.innerHTML = `{{ __('tasks.end_time') }}: ${new Date(endTime).toLocaleString()}`;
                }

                setInterval(() => {
                    if (currentTimeDisplay) {
                        currentTimeDisplay.innerHTML = `{{ __('tasks.current_time') }}: ${new Date().toLocaleString()}`;
                    }
                }, 1000);

                countdownTimer = setInterval(() => {
                    const now = new Date().getTime();
                    const remainingTime = endTime - now;

                    if (remainingTime <= 0) {
                        clearInterval(countdownTimer);
                        timerDiv.innerHTML = "{{ __('tasks.times_up') }}";
                        return;
                    }

                    const seconds = Math.floor((remainingTime / 1000) % 60);
                    const minutes = Math.floor((remainingTime / 1000 / 60) % 60);
                    const hoursLeft = Math.floor((remainingTime / (1000 * 60 * 60)) % 24);
                    const daysLeft = Math.floor(remainingTime / (1000 * 60 * 60 * 24));

                    timerDiv.innerHTML =
                        `{{ __('tasks.time_left') }} ${daysLeft} d. ${hoursLeft} h. ${minutes} min. ${seconds} sec.`;
                }, 1000);
            }

            @if ($task->status === 'in_progress' && $task->start_time)
                @php
                    $acceptedBid = $task->bids()->where('id', $task->accepted_bid_id)->first();
                @endphp
                @if ($acceptedBid)
                    startTimer({{ $acceptedBid->days }}, {{ $acceptedBid->hours }}, '{{ $task->start_time }}');
                @endif
            @endif
        });
    </script>
@endsection