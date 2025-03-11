    <h2>{{ __('wp/3/7.title') }}</h2>
    <p>{{ __('wp/3/7.paragraph1') }}</p>

    <h3>{{ __('wp/3/7.mechanism_title') }}</h3>
    <p>{{ __('wp/3/7.mechanism_paragraph') }}</p>
    <ul>
        @foreach (__('wp/3/7.mechanism_list') as $item)
            @php
                $parts = explode(':', $item, 2); // Разделяем строку на 2 части
                $title = $parts[0]; // Первая часть (до ":")
                $description = isset($parts[1]) ? $parts[1] : ''; // Вторая часть (после ":"), если есть
            @endphp
            <li><strong>{{ $title }}:</strong> {{ $description }}</li>
        @endforeach
    </ul>

    <h3>{{ __('wp/3/7.benefits_title') }}</h3>
    <ul>
        @foreach (__('wp/3/7.benefits_list') as $item)
            @php
                $parts = explode(':', $item, 2); // Разделяем строку на 2 части
                $title = $parts[0]; // Первая часть (до ":")
                $description = isset($parts[1]) ? $parts[1] : ''; // Вторая часть (после ":"), если есть
            @endphp
            <li><strong>{{ $title }}:</strong> {{ $description }}</li>
        @endforeach
    </ul>

    <h3>{{ __('wp/3/7.participation_title') }}</h3>
    <p>{{ __('wp/3/7.participation_paragraph') }}</p>
    <ul>
        @foreach (__('wp/3/7.participation_list') as $item)
            @php
                $parts = explode(':', $item, 2); // Разделяем строку на 2 части
                $title = $parts[0]; // Первая часть (до ":")
                $description = isset($parts[1]) ? $parts[1] : ''; // Вторая часть (после ":"), если есть
            @endphp
            <li><strong>{{ $title }}:</strong> {{ $description }}</li>
        @endforeach
    </ul>
