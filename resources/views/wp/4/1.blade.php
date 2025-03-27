    <h2>{{ __('wp/4/1.title') }}</h2>
    <p>{{ __('wp/4/1.paragraph1') }}</p>

    <h3>{{ __('wp/4/1.differences_title') }}</h3>
    <ul>
        @foreach (__('wp/4/1.differences_list') as $item)
            @php
                $parts = explode(':', $item, 2); // Разделяем строку на 2 части
                $title = $parts[0]; // Первая часть (до ":")
                $description = isset($parts[1]) ? $parts[1] : ''; // Вторая часть (после ":"), если есть
            @endphp
            <li><strong>{{ $title }}:</strong> {{ $description }}</li>
        @endforeach
    </ul>

    <h3>{{ __('wp/4/1.dao_title') }}</h3>
    <p>{{ __('wp/4/1.dao_paragraph1') }}</p>
    <ul>
        @foreach (__('wp/4/1.dao_list') as $item)
            <li>{{ $item }}</li>
        @endforeach
    </ul>
    <p>{{ __('wp/4/1.dao_paragraph2') }}</p>
