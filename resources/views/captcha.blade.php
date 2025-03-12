<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm you are not a robot</title>
    <script src="https://www.google.com/recaptcha/api.js?hl=en" async defer></script>
    <style>
        /* Тёмный фон */
        body {
            background-color: #1a1a1a;
            color: #ffffff;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        /* Центрирование формы */
        .captcha-container {
            text-align: center;
            background-color: #2d2d2d;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        }

        h1 {
            margin-bottom: 1.5rem;
            font-size: 1.5rem;
        }

        .recaptcha-wrapper {
            margin-bottom: 1.5rem;
            width: 100%;
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
</head>
<body>
    <div class="captcha-container">
        <h1>Please confirm you are not a robot</h1>
        <form action="{{ route('captcha.verify') }}" method="POST">
            @csrf
            <div class="recaptcha-wrapper">
                <div class="g-recaptcha" data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}"></div>
            </div>
            @if ($errors->has('g-recaptcha-response'))
                <span class="error-message">
                    {{ $errors->first('g-recaptcha-response') }}
                </span>
            @endif
            <button type="submit">Submit</button>
        </form>
    </div>
</body>
</html>