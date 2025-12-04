@extends('template')

@section('title_page')
    {{ __('user_profile.create') }}
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

        .save-btn-wrapper {
            width: 100%;
            display: flex;
            justify-content: center;
            margin-top: 30px;
        }

        .telegram-block {
            width: 100%;
            margin-bottom: 20px;
        }

        .telegram-column {
            flex: 2;
            display: flex;
            flex-direction: column;
        }

        .telegram-row {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .telegram-input {
            flex: 1;
        }

        .telegram-link-btn {
            margin-top: 0;
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 1rem;
            white-space: nowrap;
        }

        .telegram-info {
            font-size: 0.9rem;
            color: #a0ff08;
        }

        .hint-row {
            margin-top: 6px;
        }

        @media (max-width: 768px) {
            .telegram-row {
                flex-direction: column;
                align-items: stretch;
            }

            .telegram-link-btn {
                width: 100%;
                text-align: center;
            }
        }
    </style>




    <div class="container">
        <h2 class="text-center">{{ __('user_profile.create') }}</h2>

        @if (session('info'))
            <div class="alert alert-info">{{ session('info') }}</div>
        @endif

        <form action="{{ route('user_profile.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Avatar -->
            <div class="form-group">
                <label for="filename">{{ __('user_profile.avatar') }}</label>
                <div class="file-input-wrapper">
                    <img id="preview" src="#" alt="Image Preview">

                    <div class="file-info">
                        <span id="file-name" class="file-name">{{ __('user_profile.file_not_chosen') }}</span>
                        <button type="button" class="des-btn"
                            onclick="document.getElementById('file-input').click();">
                            {{ __('user_profile.choose_file') }}
                        </button>
                        <input type="file" id="file-input" name="filename" accept="image/*" style="display: none;">
                    </div>
                </div>
            </div>

            <!-- Role -->
            <div class="form-group">
                <label for="role">{{ __('user_profile.role') }}</label>
                <select class="input_dark" name="role">
                    <option value="executor" {{ old('role') == 'executor' ? 'selected' : '' }}>{{ __('user_profile.executor') }}</option>
                    <option value="client" {{ old('role') == 'client' ? 'selected' : '' }}>{{ __('user_profile.client') }}</option>
                    <option value="both" {{ old('role') == 'both' ? 'selected' : '' }}>{{ __('user_profile.both') }}</option>
                </select>
            </div>

            <!-- Telegram -->
            <div class="form-group telegram-block">
                <label for="nickname">{{ __('user_profile.telegram_nickname') }} <span class="required">*</span></label>

                <div class="telegram-column">
                    <div class="telegram-row">
                        <input type="text" class="input_dark telegram-input" name="nickname"
                            value="{{ old('nickname') }}"
                            placeholder="{{ __('user_profile.enter_telegram_nickname') }}">

                        <a href="https://t.me/DESChat_bot" target="_blank" rel="noopener"
                           class="des-btn telegram-link-btn">
                            🔗 {{ __('user_profile.link_telegram') }}
                        </a>
                    </div>

                    <div class="telegram-row hint-row">
                        <small class="telegram-info">
                            ⚠️ {{ __('user_profile.telegram_nickname_info') }}
                        </small>
                    </div>
                </div>
            </div>

            <!-- Gender -->
            <div class="form-group">
                <label for="gender">{{ __('user_profile.gender') }}:</label>
                <select name="gender" class="input_dark">
                    <option value="">{{ __('user_profile.not_specified') }}</option>
                    <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>{{ __('user_profile.male') }}</option>
                    <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>{{ __('user_profile.female') }}</option>
                </select>
            </div>

            <!-- Birthdate -->
            <div class="form-group">
                <label for="birthdate">{{ __('user_profile.date_of_birth') }}:</label>
                <input type="date" class="input_dark" name="birth_date" value="{{ old('birthdate') }}">
            </div>

            <!-- Languages -->
            <div class="form-group">
                <label for="languages">{{ __('user_profile.languages') }}:</label>
                <textarea class="input_dark" name="languages"
                    placeholder="{{ __('user_profile.languages_placeholder') }}">{{ old('languages') }}</textarea>
            </div>

            <!-- Timezone -->
            <div class="form-group">
                <label for="timezone">{{ __('user_profile.timezone') }}:</label>
                <select class="input_dark" name="timezone">
                    @foreach (['UTC-12','UTC-11','UTC-10','UTC-09','UTC-08','UTC-07','UTC-06','UTC-05','UTC-04','UTC-03','UTC-02','UTC-01','UTC+00','UTC+01','UTC+02','UTC+03','UTC+04','UTC+05','UTC+06','UTC+07','UTC+08','UTC+09','UTC+10','UTC+11','UTC+12'] as $zone)
                        <option value="{{ $zone }}:00" {{ old('timezone') == $zone . ':00' ? 'selected' : '' }}>
                            {{ $zone }}:00
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Education -->
            <div class="form-group">
                <label for="education">{{ __('user_profile.education') }}:</label>
                <textarea class="input_dark" name="education"
                    placeholder="{{ __('user_profile.education_placeholder') }}">{{ old('education') }}</textarea>
            </div>

            <!-- Specialization -->
            <div class="form-group">
                <label for="specialization">{{ __('user_profile.specialization') }}:</label>
                <input type="text" class="input_dark" name="specialization" value="{{ old('specialization') }}"
                    placeholder="{{ __('user_profile.specialization_placeholder') }}">
            </div>

            <!-- Resume -->
            <div class="form-group">
                <label for="resume">{{ __('user_profile.resume') }}:</label>
                <textarea class="input_dark" name="resume"
                    placeholder="{{ __('user_profile.resume_placeholder') }}">{{ old('resume') }}</textarea>
            </div>

            <!-- Portfolio -->
            <div class="form-group">
                <label for="portfolio">{{ __('user_profile.portfolio') }}:</label>
                <textarea class="input_dark" name="portfolio"
                    placeholder="{{ __('user_profile.portfolio_placeholder') }}">{{ old('portfolio') }}</textarea>
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
            <div class="save-btn-wrapper">
                <button type="submit" class="des-btn">
                    <i class="fas fa-plus-circle"></i> {{ __('user_profile.create') }}
                </button>
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
                fileNameElement.textContent = "{{ __('user_profile.file_not_chosen') }}";
            }
        };
    </script>
@endsection
