@extends('template')

@section('title_page', __('user_profile.edit_profile'))

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

        .des-btn {
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

        .des-btn:hover {
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
        
        .file-error {
            color: #ff4c4c;
            font-size: 0.9rem;
            margin-top: 5px;
        }

        .file-size-note {
            color: #aaa;
            font-size: 0.85rem;
            margin-top: 5px;
        }
    </style>

<div class="container">
    <h2 style="text-align: center">{{ __('user_profile.edit_profile') }}</h2><br>

    <form action="{{ route('user_profile.update', $profile->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Image Upload -->
        <div class="form-group">
            <label for="filename">{{ __('user_profile.avatar') }}</label>
            <div class="file-input-wrapper">
                <img id="preview" src="{{ $profile->avatar_url ?? '#' }}" alt="Image Preview"
                     style="display: {{ $profile->avatar_url ? 'block' : 'none' }}; max-width: 100px;">

                <div class="file-info">
                    <span id="file-name" class="file-name">
                        {{ $profile->avatar_url ? basename($profile->avatar_url) : __('user_profile.no_file_chosen') }}
                    </span>
                    <button type="button" class="des-btn"
                            onclick="document.getElementById('file-input').click();">
                        {{ __('user_profile.choose_file') }}
                    </button>
                    <input type="file" id="file-input" name="filename" accept="image/*" style="display: none;">
                    <span class="file-size-note">Максимальный размер: 2 МБ</span>
                    <span id="file-error" class="file-error" style="display: none;"></span>
                </div>
            </div>
        </div>

        <!-- Role -->
        <div class="form-group">
            <label for="role">{{ __('user_profile.role') }}</label>
            <select class="input_dark" name="role">
                <option value="executor" {{ old('role', $profile->role) == 'executor' ? 'selected' : '' }}>{{ __('user_profile.executor') }}</option>
                <option value="client" {{ old('role', $profile->role) == 'client' ? 'selected' : '' }}>{{ __('user_profile.client') }}</option>
                <option value="both" {{ old('role', $profile->role) == 'both' ? 'selected' : '' }}>{{ __('user_profile.both') }}</option>
            </select>
        </div>

        <!-- Telegram Nickname -->
        <div class="form-group">
            <label for="nickname">{{ __('user_profile.telegram_nickname') }}</label>
            <input type="text" class="input_dark" name="nickname" value="{{ old('nickname', $profile->nickname) }}"
                placeholder="{{ $profile->nickname ?: __('user_profile.not_specified') }}">
        </div>

        <!-- Gender -->
        <div class="form-group">
            <label for="gender">{{ __('user_profile.gender') }}:</label>
            <select name="gender" class="input_dark">
                <option value="" {{ old('gender', $profile->gender) == '' ? 'selected' : '' }}>{{ __('user_profile.not_specified') }}</option>
                <option value="male" {{ old('gender', $profile->gender) == 'male' ? 'selected' : '' }}>{{ __('user_profile.male') }}</option>
                <option value="female" {{ old('gender', $profile->gender) == 'female' ? 'selected' : '' }}>{{ __('user_profile.female') }}</option>
            </select>
        </div>

        <!-- Date of Birth -->
        <div class="form-group">
            <label for="birthdate">{{ __('user_profile.date_of_birth') }}:</label>
            <input type="date" class="input_dark" name="birth_date"
                value="{{ old('birth_date', $profile->birth_date) }}">
        </div>

        <!-- Communication Languages -->
        <div class="form-group">
            <label for="languages">{{ __('user_profile.languages') }}:</label>
            <textarea class="input_dark" name="languages"
                placeholder="{{ $profile->languages ? $profile->languages : __('user_profile.not_specified') }}">{{ old('languages', $profile->languages) }}</textarea>
        </div>

        <!-- Timezone -->
        <div class="form-group">
            <label for="timezone">{{ __('user_profile.timezone') }}:</label>
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

        <!-- Education -->
        <div class="form-group">
            <label for="education">{{ __('user_profile.education') }}:</label>
            <textarea class="input_dark" name="education" placeholder="{{ $profile->education ?: __('user_profile.not_specified') }}">{{ old('education', $profile->education) }}</textarea>
        </div>

        <!-- Specialization -->
        <div class="form-group">
            <label for="specialization">{{ __('user_profile.specialization') }}:</label>
            <input type="text" class="input_dark" name="specialization"
                value="{{ old('specialization', $profile->specialization) }}"
                placeholder="{{ $profile->specialization ?: __('user_profile.not_specified') }}">
        </div>

        <!-- Resume -->
        <div class="form-group">
            <label for="resume">{{ __('user_profile.resume') }}:</label>
            <textarea class="input_dark" name="resume" placeholder="{{ $profile->resume ?: __('user_profile.not_specified') }}">{{ old('resume', $profile->resume) }}</textarea>
        </div>

        <!-- Portfolio -->
        <div class="form-group">
            <label for="portfolio">{{ __('user_profile.portfolio') }}:</label>
            <textarea class="input_dark" name="portfolio" placeholder="{{ $profile->portfolio ?: __('user_profile.not_specified') }}">{{ old('portfolio', $profile->portfolio) }}</textarea>
        </div>

        <div class="text-center">
            <button type="submit" class="des-btn"><i class="fas fa-save"></i> {{ __('user_profile.save_changes') }}</button>
        </div>
    </form>
</div>

<script>
    document.getElementById("file-input").onchange = function(event) {
        const file = event.target.files[0];
        const fileNameElement = document.getElementById("file-name");
        const previewElement = document.getElementById("preview");
        const errorElement = document.getElementById("file-error");
        const maxSize = 2 * 1024 * 1024; // 2MB

        if (file) {
            if (file.size > maxSize) {
                errorElement.style.display = 'block';
                errorElement.textContent = "Файл превышает допустимый размер (2 МБ)";
                fileNameElement.textContent = "{{ __('user_profile.no_file_chosen') }}";
                previewElement.style.display = 'none';
                event.target.value = ''; // Сброс выбранного файла
                return;
            }

            errorElement.style.display = 'none';
            const reader = new FileReader();
            reader.onload = function(e) {
                previewElement.src = e.target.result;
                previewElement.style.display = 'block';
            };
            reader.readAsDataURL(file);
            fileNameElement.textContent = file.name;
        } else {
            previewElement.style.display = 'none';
            fileNameElement.textContent = "{{ __('user_profile.no_file_chosen') }}";
            errorElement.style.display = 'none';
        }
    };
</script>
@endsection