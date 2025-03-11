    <h4>{{ __('wp/2/5.title') }}</h4>
    <ul>
        @foreach (__('wp/2/5.aspects_list') as $item)
            @php
                $parts = explode(':', $item, 2); // Разделяем строку на 2 части
                $title = $parts[0]; // Первая часть (до ":")
                $description = isset($parts[1]) ? $parts[1] : ''; // Вторая часть (после ":"), если есть
            @endphp
            <li><strong>{{ $title }}:</strong> {{ $description }}</li>
        @endforeach
    </ul>
    <p>{{ __('wp/2/5.note') }}</p>
