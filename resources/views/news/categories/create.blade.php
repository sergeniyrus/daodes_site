@extends('template')
@section('title_page', 'Добавить категорию Новостей')
@section('main')

<link href="{{ asset('css/category.css') }}" rel="stylesheet">
<div class="container my-5">
    <h1>{{ __('category.add_category_title') }}</h1>

    <div id="message" class="alert" style="display: none;"></div>

    <form id="category-form">
        @csrf
        <div class="form-group">
            <label for="name_ru">{{ __('admin_news.category_name_ru') }}</label>
            <input type="text" name="name_ru" id="name_ru" class="input_dark" required>
        </div>

        <div class="form-group">
            <label for="name_en">{{ __('admin_news.category_name_en') }}</label>
            <input type="text" name="name_en" id="name_en" class="input_dark" required>
        </div>

        <button type="submit" class="des-btn">
            {!! __('category.add_category_button') !!}
        </button>
    </form>
</div>

<script>
document.getElementById('category-form').addEventListener('submit', function(e) {
    e.preventDefault();

    const form = e.target;
    const data = new FormData(form);
    const messageBox = document.getElementById('message');

    fetch("{{ route('newscategories.categoryStore') }}", {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': data.get('_token')
        },
        body: data
    })
    .then(async response => {
        const result = await response.json();
        messageBox.style.display = 'block';

        if (result.success) {
            messageBox.className = 'custom-alert custom-alert-success';
            messageBox.innerText = result.message;

            // Очистка формы
            form.reset();
        } else {
            messageBox.className = 'custom-alert custom-alert-error';

            messageBox.innerHTML = result.errors ? result.errors.join('<br>') : result.message;
        }
    })
    .catch(error => {
        messageBox.className = 'custom-alert custom-alert-error';

        messageBox.style.display = 'block';
        messageBox.innerText = '{{ __("message.unknown_error") }}';
        console.error(error);
    });
});
</script>


@endsection
