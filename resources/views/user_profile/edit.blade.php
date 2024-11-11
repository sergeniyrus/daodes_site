@extends('template')

@section('title_page')
    Редактировать профиль
@endsection

@section('main')
    <style>
        /* Стили аналогичны шаблону для создания */
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
        <h2 style="text-align: center">Редактирование профиля</h2><br>

        <!-- Вывод сообщения из сессии, если оно есть -->
        @if (session('info'))
            <div class="alert alert-info" style="text-align: center">{{ session('info') }}</div>
        @elseif(session('error'))
            <div class="alert alert-danger" style="text-align: center">{{ session('error') }}</div>
        @endif

        <form action="{{ route('user_profile.update', $profile->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Загрузка изображения -->
            <div class="form-group">
                <label for="filename">Аватар</label>
                <div class="file-input-wrapper">
                    <!-- Если аватар есть, показываем его, иначе не показываем изображение -->
                    <img id="preview" src="{{ $profile->avatar_url ?? '#' }}" alt="Image Preview"
                        style="display: {{ $profile->avatar_url ? 'block' : 'none' }};">

                    <div class="file-info">
                        <!-- Если аватар есть, выводим имя файла, иначе пишем "Файл не выбран" -->
                        <span id="file-name"
                            class="file-name">{{ $profile->avatar_url ? basename($profile->avatar_url) : 'Файл не выбран' }}</span>
                        <button type="button" class="blue_btn"
                            onclick="document.getElementById('file-input').click();">Выберите файл</button>
                        <input type="file" id="file-input" name="filename" accept="image/*" style="display: none;">
                    </div>
                </div>
            </div>

            <!-- Роль -->
            <div class="form-group">
                <label for="role">Роль</label>
                <select class="input_dark" name="role">
                    <option value="executor" {{ old('role', $profile->role) == 'executor' ? 'selected' : '' }}>Исполнитель
                    </option>
                    <option value="client" {{ old('role', $profile->role) == 'client' ? 'selected' : '' }}>Заказчик</option>
                    <option value="both" {{ old('role', $profile->role) == 'both' ? 'selected' : '' }}>Оба</option>
                </select>
            </div>

            <!-- Никнейм в Telegram -->
            <div class="form-group">
                <label for="nickname">Никнейм в Telegramm</label>
                <input type="text" class="input_dark" name="nickname" value="{{ old('nickname', $profile->nickname) }}"
                    placeholder="{{ $profile->nickname ?: 'нет данных' }}">
            </div>

            <!-- Пол -->
            <div class="form-group">
                <label for="gender">Пол:</label>
                <select name="gender" class="input_dark">
                    <option value="" {{ old('gender', $profile->gender) == '' ? 'selected' : '' }}>Не указан</option>
                    <option value="male" {{ old('gender', $profile->gender) == 'male' ? 'selected' : '' }}>Мужской
                    </option>
                    <option value="female" {{ old('gender', $profile->gender) == 'female' ? 'selected' : '' }}>Женский
                    </option>
                </select>
            </div>

            <!-- Дата рождения -->
            <div class="form-group">
                <label for="birthdate">Дата рождения:</label>
                <input type="date" class="input_dark" name="birth_date"
                    value="{{ old('birth_date', $profile->birth_date) }}">
            </div>

            <!-- Языки общения -->
            <div class="form-group">
                <label for="languages">Языки общения:</label>
                <textarea class="input_dark" name="languages"
                    placeholder="{{ $profile->languages ? $profile->languages : 'нет данных' }}">{{ old('languages', $profile->languages) }}</textarea>
            </div>

            <!-- Часовой пояс -->
            <div class="form-group">
                <label for="timezone">Часовой пояс:</label>
                <select class="input_dark" name="timezone">
                    <option value="UTC-12:00" {{ $profile->timezone == 'UTC-12:00' ? 'selected' : '' }}>UTC-12:00</option>
                    <option value="UTC-11:00" {{ $profile->timezone == 'UTC-11:00' ? 'selected' : '' }}>UTC-11:00</option>
                    <option value="UTC-10:00" {{ $profile->timezone == 'UTC-10:00' ? 'selected' : '' }}>UTC-10:00</option>
                    <option value="UTC-09:00" {{ $profile->timezone == 'UTC-09:00' ? 'selected' : '' }}>UTC-09:00</option>
                    <option value="UTC-08:00" {{ $profile->timezone == 'UTC-08:00' ? 'selected' : '' }}>UTC-08:00</option>
                    <option value="UTC-07:00" {{ $profile->timezone == 'UTC-07:00' ? 'selected' : '' }}>UTC-07:00</option>
                    <option value="UTC-06:00" {{ $profile->timezone == 'UTC-06:00' ? 'selected' : '' }}>UTC-06:00</option>
                    <option value="UTC-05:00" {{ $profile->timezone == 'UTC-05:00' ? 'selected' : '' }}>UTC-05:00</option>
                    <option value="UTC-04:00" {{ $profile->timezone == 'UTC-04:00' ? 'selected' : '' }}>UTC-04:00</option>
                    <option value="UTC-03:00" {{ $profile->timezone == 'UTC-03:00' ? 'selected' : '' }}>UTC-03:00</option>
                    <option value="UTC-02:00" {{ $profile->timezone == 'UTC-02:00' ? 'selected' : '' }}>UTC-02:00</option>
                    <option value="UTC-01:00" {{ $profile->timezone == 'UTC-01:00' ? 'selected' : '' }}>UTC-01:00</option>
                    <option value="UTC+00:00" {{ $profile->timezone == 'UTC+00:00' ? 'selected' : '' }}>UTC+00:00</option>
                    <option value="UTC+01:00" {{ $profile->timezone == 'UTC+01:00' ? 'selected' : '' }}>UTC+01:00</option>
                    <option value="UTC+02:00" {{ $profile->timezone == 'UTC+02:00' ? 'selected' : '' }}>UTC+02:00</option>
                    <option value="UTC+03:00" {{ $profile->timezone == 'UTC+03:00' ? 'selected' : '' }}>UTC+03:00</option>
                    <option value="UTC+04:00" {{ $profile->timezone == 'UTC+04:00' ? 'selected' : '' }}>UTC+04:00</option>
                    <option value="UTC+05:00" {{ $profile->timezone == 'UTC+05:00' ? 'selected' : '' }}>UTC+05:00</option>
                    <option value="UTC+06:00" {{ $profile->timezone == 'UTC+06:00' ? 'selected' : '' }}>UTC+06:00</option>
                    <option value="UTC+07:00" {{ $profile->timezone == 'UTC+07:00' ? 'selected' : '' }}>UTC+07:00</option>
                    <option value="UTC+08:00" {{ $profile->timezone == 'UTC+08:00' ? 'selected' : '' }}>UTC+08:00</option>
                    <option value="UTC+09:00" {{ $profile->timezone == 'UTC+09:00' ? 'selected' : '' }}>UTC+09:00</option>
                    <option value="UTC+10:00" {{ $profile->timezone == 'UTC+10:00' ? 'selected' : '' }}>UTC+10:00</option>
                    <option value="UTC+11:00" {{ $profile->timezone == 'UTC+11:00' ? 'selected' : '' }}>UTC+11:00</option>
                    <option value="UTC+12:00" {{ $profile->timezone == 'UTC+12:00' ? 'selected' : '' }}>UTC+12:00</option>
                </select>
            </div>

            <!-- Образование -->
            <div class="form-group">
                <label for="education">Образование:</label>
                <textarea class="input_dark" name="education" placeholder="{{ $profile->education ?: 'нет данных' }}">{{ old('education', $profile->education) }}</textarea>
            </div>

            <!-- Специализация -->
            <div class="form-group">
                <label for="specialization">Специализация:</label>
                <input type="text" class="input_dark" name="specialization"
                    value="{{ old('specialization', $profile->specialization) }}"
                    placeholder="{{ $profile->specialization ?: 'нет данных' }}">
            </div>

            <!-- Резюме -->
            <div class="form-group">
                <label for="resume">Резюме:</label>
                <textarea class="input_dark" name="resume" placeholder="{{ $profile->resume ?: 'нет данных' }}">{{ old('resume', $profile->resume) }}</textarea>
            </div>

            <!-- Портфолио -->
            <div class="form-group">
                <label for="portfolio">Портфолио:</label>
                <textarea class="input_dark" name="portfolio" placeholder="{{ $profile->portfolio ?: 'нет данных' }}">{{ old('portfolio', $profile->portfolio) }}</textarea>
            </div>

            <div class="text-center">
                <button type="submit" class="blue_btn"><i class="fas fa-save"></i> Сохранить изменения</button>
            </div>
        </form>
    </div>

    <script>
        document.getElementById("file-input").onchange = function(event) {
            const file = event.target.files[0];
            const fileNameElement = document.getElementById("file-name");
            const previewElement = document.getElementById("preview");

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
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
