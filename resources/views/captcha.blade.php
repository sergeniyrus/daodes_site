@extends('template')

@section('title_page')
{{ __('admin_lang.titles.captcha') }}
@endsection

@section('main')
<style>
    /* Тёмный фон на всю страницу */
    body {
        background-color: rgba(0, 0, 0, 0.8);
        margin: 0;
        padding: 0;
        height: 100vh;
        overflow: hidden;
        position: relative;
    }

    /* Затемнение и блокировка всего контента */
    .content-blocker {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.8);
        z-index: 9998;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    /* Центрирование формы */
    .captcha-container {
        text-align: center;
        background-color: #2d2d2d;
        padding: 2rem;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.5);
        max-width: 400px;
        width: 90%;
        border: 1px solid #fff;
        z-index: 9999;
        position: relative;
    }

    h1 {
        margin-bottom: 1.5rem;
        font-size: 1.5rem;
        color: #fff;
    }

    .recaptcha-wrapper {
        margin: 20px 0;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .error-message {
        color: #ff6b6b;
        font-size: 0.9rem;
        margin-top: 0.5rem;
        display: block;
    }

    button {
        background-color: #4CAF50;
        color: white;
        border: none;
        padding: 0.75rem 1.5rem;
        font-size: 1rem;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    button:hover {
        background-color: #45a049;
    }
</style>

<script src="https://www.google.com/recaptcha/api.js?hl=en" async defer></script>
<script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer></script>

<div class="content-blocker">
    <div class="captcha-container">
        <h6 style="color: #fff;">Please confirm you are not a robot</h6>
        <form action="{{ route('captcha.verify') }}" method="POST">
            @csrf
            <div class="recaptcha-wrapper">
                <div id="recaptcha-container"></div>        
            </div>

            @if ($errors->has('g-recaptcha-response'))
                <span class="error-message">
                    {{ $errors->first('g-recaptcha-response') }}
                </span>
            @endif

            <button type="submit">Submit</button>
        </form>
    </div>
</div>

<script type="text/javascript">
    var onloadCallback = function() {
        grecaptcha.render('recaptcha-container', {
            'sitekey': '{{ env('RECAPTCHA_SITE_KEY') }}'
        });
    };

    // Блокировка прокрутки страницы
    document.body.style.overflow = 'hidden';
    
    // Блокировка кликов вне формы капчи
    document.querySelector('.content-blocker').addEventListener('click', function(e) {
        if (!e.target.closest('.captcha-container')) {
            e.preventDefault();
            e.stopPropagation();
        }
    });
</script>
@endsection