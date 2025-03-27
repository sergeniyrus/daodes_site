@extends('template')

@section('title_page', __('seed.title_page'))

@section('main')
    @inject('encryptionService', 'App\Services\EncryptionService')
    <style>
        .modal-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            margin: 15px auto;
        }

        .container {
            width: 100%;
            text-align: center;
        }

        .save-phrase {
            color: red;
            font-size: 14px;
            text-align: center;
            margin-top: 20px;
        }

        .seed-phrase {
            color: gray;
            font-size: 14px;
            text-align:justify;
            border: 1px solid gold;
            border-radius: 10px;
            padding: 10px;
        }

        h2 {
            text-align: center;
            font-size: 16px;
            color: rgb(0, 255, 0);
        }

        .hidden-seed {
            display: none;
        }

        .des-btn {
        display: inline-block;
        color: #ffffff;
        background: #0b0c18;
        padding: 5px 10px;
        font-size: 1.3rem;
        border: 1px solid gold;
        border-radius: 10px;
        transition: all 0.3s ease;
        text-decoration: none;
        margin: 5px;
         /* Добавьте эти свойства для button 
    border: none;
    outline: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;*/
    }

    /* Убедитесь, что стили применяются и к button */
button.des-btn {
    display: inline-block;
    color: #ffffff;
    background: #0b0c18;
    padding: 5px 10px;
    font-size: 1.3rem;
    border: 1px solid gold;
    border-radius: 10px;
    transition: all 0.3s ease;
    text-decoration: none;
    margin: 5px;
}

    /* Стили для активной кнопки */
    .des-btn:not([disabled]) {
        cursor: pointer;
    }
    
    .des-btn:not([disabled]):hover {
        box-shadow: 0 0 20px goldenrod;
        transform: scale(1.05);
    }

    /* Стили для неактивной кнопки */
    .des-btn[disabled] {
        opacity: 0.6;
        border-color: #666;
        cursor: not-allowed;
    }

    /* Стиль кнопки во время сохранения */
.des-btn[disabled].saving {
    background-color: #0a5c36 !important;
    transition: background-color 0.3s ease;
}

        @media (min-width: 768px) {

            .save-phrase,
            .seed-phrase,
            h2 {
                font-size: 18px;
            }
        }

        @media (min-width: 1024px) {

            .save-phrase,
            .seed-phrase,
            h2 {
                font-size: 20px;
            }
        }
    </style>

<div class="modal-content">
    <img src="/img/main/img_avatar.jpg" alt="Avatar" class="avatar">
    <div class="container">
        <form method="post" action="{{ route('seed.save') }}">
                    @csrf
                    <div class="tabseed">
                        <h2>{{ __('seed.seed_phrase_title') }}</h2><br>
                        <div id="seedPhrase" class="seed-phrase" style="display: inline-block;">
                            @foreach ($words as $index => $word)
                                <span>{{ $word }}</span>
                                <input type="hidden" name="word{{ $index }}" value="{{ $word }}">
                            @endforeach
                            <span>{{ $keyword }}</span>
                            <input type="hidden" name="word23" value="{{ $keyword }}">
                            
                        </div>
                        <div class="save-phrase">{{ __('seed.save_phrase_warning') }}</div>
                    </div>
                    <br>
                    <button class="des-btn" id="saveButton" type="submit" disabled>
                        {{ __('seed.save_button_text') }}
                    </button>
                </form>
<br>
                <div class="des-btn" onclick="copytext('#hiddenSeed')">
                    {{ __('seed.copy_button_text') }}
                </div>

                <div id="hiddenSeed" class="hidden-seed">
                    @foreach ($words as $word)
                        {{ $word }}
                    @endforeach
                    {{ $keyword }}
                </div>
        </div>
</div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Обработчик копирования сид-фразы
        function copytext(el) {
            const copyBtn = event.currentTarget;
            copyBtn.style.transform = 'scale(0.95)';
            
            const textToCopy = document.querySelector(el).textContent
                .trim()
                .replace(/\s+/g, ' ');
            
            navigator.clipboard.writeText(textToCopy)
                .then(() => {
                    setTimeout(() => {
                        copyBtn.style.transform = '';
                    }, 200);
                    
                    alert("{{ __('seed.copy_success_message') }}");
                    
                    const saveButton = document.getElementById('saveButton');
                    saveButton.disabled = false;
                    saveButton.style.opacity = '1';
                    saveButton.style.borderColor = 'gold';
                    saveButton.style.cursor = 'pointer';
                })
                .catch(err => {
                    copyBtn.style.transform = '';
                    console.error('Ошибка копирования:', err);
                });
        }
    
        // Обработчик отправки формы
        document.querySelector('form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const saveButton = document.getElementById('saveButton');
            const originalText = saveButton.textContent;
            
            // Блокируем кнопку и меняем текст
            saveButton.disabled = true;
            saveButton.textContent = "{{ __('seed.saving_text') }}";
            saveButton.classList.add('saving');
            
            // Ждем 3 секунды перед отправкой
            setTimeout(() => {
                // Удаляем класс сохранения
                saveButton.classList.remove('saving');
                
                // Отправляем форму (теперь будет работать, так как это настоящая submit-кнопка)
                this.submit();
            }, 3000);
        });
    </script>
@endsection
