@extends('template')

@section('title_page')
    Заполнить профиль
@endsection

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

        .form-group {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 15px;
        }

        .form-group label {
            flex: 1;
            color: #d7fc09;
            font-size: 1.2rem;
            font-weight: bold;
            margin-right: 10px;
            text-align: left;
        }

        .input_dark,
        textarea {
            flex: 2;
            background-color: #1a1a1a;
            color: #a0ff08;
            border: 1px solid #d7fc09;
            border-radius: 5px;
            padding: 12px;
            font-size: 16px;
            margin-top: 5px;
            transition: border 0.3s ease;
        }

        .input_dark:focus,
        textarea:focus {
            border: 1px solid #a0ff08;
            outline: none;
            box-shadow: 0 0 5px #d7fc09;
        }

        .blue_btn {
            display: inline-block;
            color: #ffffff;
            font-size: 1.2rem;
            background: #0b0c18;
            padding: 12px 25px;
            border: 1px solid #d7fc09;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            cursor: pointer;
            transition: box-shadow 0.3s ease, transform 0.3s ease, background-color 0.3s ease;
            margin-top: 20px;
        }

        .blue_btn:hover {
            box-shadow: 0 0 20px #d7fc09, 0 0 40px #d7fc09, 0 0 60px #d7fc09;
            transform: scale(1.05);
            background: #1a1a1a;
        }

        @media (max-width: 768px) {
            .form-group {
                flex-direction: column;
                align-items: stretch;
            }

            .form-group label {
                margin-bottom: 5px;
            }
        }

        .file-input-wrapper {
            display: flex;
            align-items: center;
        }

        #preview {
            max-width: 100px;
            max-height: 100px;
            margin-right: 15px;
            display: none;
            /* Скрываем по умолчанию */
            border: 1px solid #d7fc09;
            border-radius: 5px;
        }

        .file-info {
            display: flex;
            flex-direction: column;
            text-align: center;
        }

        .file-name {
            font-size: 0.9rem;
            color: #a0ff08;
            margin-bottom: 8px;
        }
    </style>

    <div class="container">
        <h2 class="text-center">Создать профиль</h2>

        @if (session('info'))
            <div class="alert alert-info">{{ session('info') }}</div>
        @endif

        <form action="{{ route('user_profile.store') }}" method="POST">
            @csrf

            <!-- Основные данные -->
            {{-- <div class="form-group">
                <label for="wallet_address">Адрес кошелька</label>
                <input type="text" class="input_dark" name="wallet_address" value="{{ old('wallet_address') }}"
                    placeholder="Введите адрес кошелька">
            </div> --}}

                        <!-- Загрузка изображения -->
            <div class="form-group">
                <label for="filename">Аватар</label>
                <div class="file-input-wrapper">
                    <img id="preview" src="#" alt="Image Preview" style="display: none;">

                    <div class="file-info">
                        <span id="file-name" class="file-name">Файл не выбран</span>
                        <button type="button" class="blue_btn"
                            onclick="document.getElementById('file-input').click();">Выберите файл</button>
                        <input type="file" id="file-input" name="filename" accept="image/*" style="display: none;">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="role">Роль</label>
                <select class="input_dark" name="role">
                    <option value="executor" {{ old('role') == 'executor' ? 'selected' : '' }}>Исполнитель</option>
                    <option value="client" {{ old('role') == 'client' ? 'selected' : '' }}>Заказчик</option>
                    <option value="both" {{ old('role') == 'both' ? 'selected' : '' }}>Оба</option>
                </select>
            </div>

            <div class="form-group">
                <label for="nickname">Никнейм в Telegramm</label>
                <input type="text" class="input_dark" name="nickname" value="{{ old('nickname') }}"
                    placeholder="Введите никнейм">
            </div>

            <!-- Пол -->
        <div class="form-group">
            <label for="gender">Пол:</label>
            <select name="gender" class="input_dark">
                <option value="">Не указан</option>
                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Мужской</option>
                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Женский</option>
            </select>
        </div>

            <!-- Дата рождения -->
        <div class="form-group">
            <label for="birthdate">Дата рождения:</label>
            <input type="date" class="input_dark" name="birth_date" value="{{ old('birthdate') }}" placeholder="Выберите дату рождения">
        </div>

            <div class="form-group">
                <label for="languages">Языки общения:</label>
                <textarea class="input_dark" name="languages" placeholder='Пример: {"English": "Intermediate", "Russian": "Native"}'>{{ old('languages') }}</textarea>
            </div>

            <!-- Часовой пояс -->
        <div class="form-group">
            <label for="timezone">Часовой пояс:</label>
            <select class="input_dark" name="timezone">
                <option value="UTC-12:00" {{ old('timezone') == 'UTC-12:00' ? 'selected' : '' }}>UTC-12:00</option>
                <option value="UTC-11:00" {{ old('timezone') == 'UTC-11:00' ? 'selected' : '' }}>UTC-11:00</option>
                <option value="UTC-10:00" {{ old('timezone') == 'UTC-10:00' ? 'selected' : '' }}>UTC-10:00</option>
                <option value="UTC-09:00" {{ old('timezone') == 'UTC-09:00' ? 'selected' : '' }}>UTC-09:00</option>
                <option value="UTC-08:00" {{ old('timezone') == 'UTC-08:00' ? 'selected' : '' }}>UTC-08:00</option>
                <option value="UTC-07:00" {{ old('timezone') == 'UTC-07:00' ? 'selected' : '' }}>UTC-07:00</option>
                <option value="UTC-06:00" {{ old('timezone') == 'UTC-06:00' ? 'selected' : '' }}>UTC-06:00</option>
                <option value="UTC-05:00" {{ old('timezone') == 'UTC-05:00' ? 'selected' : '' }}>UTC-05:00</option>
                <option value="UTC-04:00" {{ old('timezone') == 'UTC-04:00' ? 'selected' : '' }}>UTC-04:00</option>
                <option value="UTC-03:00" {{ old('timezone') == 'UTC-03:00' ? 'selected' : '' }}>UTC-03:00</option>
                <option value="UTC-02:00" {{ old('timezone') == 'UTC-02:00' ? 'selected' : '' }}>UTC-02:00</option>
                <option value="UTC-01:00" {{ old('timezone') == 'UTC-01:00' ? 'selected' : '' }}>UTC-01:00</option>
                <option value="UTC+00:00" {{ old('timezone') == 'UTC+00:00' ? 'selected' : '' }}>UTC+00:00</option>
                <option value="UTC+01:00" {{ old('timezone') == 'UTC+01:00' ? 'selected' : '' }}>UTC+01:00</option>
                <option value="UTC+02:00" {{ old('timezone') == 'UTC+02:00' ? 'selected' : '' }}>UTC+02:00</option>
                <option value="UTC+03:00" {{ old('timezone') == 'UTC+03:00' ? 'selected' : '' }}>UTC+03:00</option>
                <option value="UTC+04:00" {{ old('timezone') == 'UTC+04:00' ? 'selected' : '' }}>UTC+04:00</option>
                <option value="UTC+05:00" {{ old('timezone') == 'UTC+05:00' ? 'selected' : '' }}>UTC+05:00</option>
                <option value="UTC+06:00" {{ old('timezone') == 'UTC+06:00' ? 'selected' : '' }}>UTC+06:00</option>
                <option value="UTC+07:00" {{ old('timezone') == 'UTC+07:00' ? 'selected' : '' }}>UTC+07:00</option>
                <option value="UTC+08:00" {{ old('timezone') == 'UTC+08:00' ? 'selected' : '' }}>UTC+08:00</option>
                <option value="UTC+09:00" {{ old('timezone') == 'UTC+09:00' ? 'selected' : '' }}>UTC+09:00</option>
                <option value="UTC+10:00" {{ old('timezone') == 'UTC+10:00' ? 'selected' : '' }}>UTC+10:00</option>
                <option value="UTC+11:00" {{ old('timezone') == 'UTC+11:00' ? 'selected' : '' }}>UTC+11:00</option>
                <option value="UTC+12:00" {{ old('timezone') == 'UTC+12:00' ? 'selected' : '' }}>UTC+12:00</option>
            </select>
        </div>
            

            <div class="form-group">
                <label for="education">Образование:</label>
                <textarea class="input_dark" name="education" placeholder="Введите информацию об образовании">{{ old('education') }}</textarea>
            </div>

            <div class="form-group">
                <label for="specialization">Специализация:</label>
                <input type="text" class="input_dark" name="specialization" value="{{ old('specialization') }}"
                    placeholder="Введите вашу специализацию">
            </div>

            <div class="form-group">
                <label for="resume">Резюме:</label>
                <textarea class="input_dark" name="resume" placeholder="Краткое описание ваших навыков">{{ old('resume') }}</textarea>
            </div>

            <div class="form-group">
                <label for="portfolio">Портфолио:</label>
                <textarea class="input_dark" name="portfolio"
                    placeholder='Пример: {"project1": "https://example.com/project1", "project2": "https://example.com/project2"}'>{{ old('portfolio') }}</textarea>
            </div>

            

            <!-- Репутация и рейтинг -->
            {{-- <div class="form-group">
                <label for="rating">Рейтинг</label>
                <input type="number" step="0.1" class="input_dark" name="rating" value="{{ old('rating') }}"
                    placeholder="Введите ваш рейтинг">
            </div>

            <div class="form-group">
                <label for="trust_level">Уровень доверия</label>
                <input type="number" step="0.1" class="input_dark" name="trust_level" value="{{ old('trust_level') }}"
                    placeholder="Введите уровень доверия">
            </div>

            <div class="form-group">
                <label for="sbt_tokens">Количество SBT-токенов</label>
                <input type="number" class="input_dark" name="sbt_tokens" value="{{ old('sbt_tokens') }}"
                    placeholder="Введите количество SBT-токенов">
            </div> --}}
            <div class="text-center">
                <button type="submit" class="blue_btn"><i class="fas fa-plus-circle"></i> Создать профиль</button>
            </div>
        </form>
    </div>
    <script>
        document.getElementById("file-input").onchange = function (event) {
    const file = event.target.files[0];
    const fileNameElement = document.getElementById("file-name");
    const previewElement = document.getElementById("preview");

    if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
            previewElement.src = e.target.result;
            previewElement.style.display = 'block';
        };
        reader.readAsDataURL(file);
        fileNameElement.textContent = file.name;
    } else {
        previewElement.style.display = 'none';
        fileNameElement.textContent = "Файл не выбран";
    }
};

    </script>
@endsection
