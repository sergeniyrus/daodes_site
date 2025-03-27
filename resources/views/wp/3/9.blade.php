    <h2>{{ __('wp/3/9.title') }}</h2>
    <p>{{ __('wp/3/9.paragraph1') }}</p>

    <h3>{{ __('wp/3/9.registration_title') }}</h3>
    <ol>
        @foreach (__('wp/3/9.registration_list') as $item)
            @php
                $parts = explode(':', $item, 2); // Разделяем строку на 2 части
                $title = $parts[0]; // Первая часть (до ":")
                $description = isset($parts[1]) ? $parts[1] : ''; // Вторая часть (после ":"), если есть
            @endphp
            <li><strong>{{ $title }}:</strong> {{ $description }}</li>
        @endforeach
    </ol>

    <h3>{{ __('wp/3/9.wallets_title') }}</h3>
    <ul>
        @foreach (__('wp/3/9.wallets_list') as $item)
            @php
                $parts = explode(':', $item, 2); // Разделяем строку на 2 части
                $title = $parts[0]; // Первая часть (до ":")
                $description = isset($parts[1]) ? $parts[1] : ''; // Вторая часть (после ":"), если есть
            @endphp
            <li><strong>{{ $title }}:</strong> {{ $description }}</li>
        @endforeach
    </ul>

    <h3>{{ __('wp/3/9.revenue_title') }}</h3>
    <p>{{ __('wp/3/9.revenue_paragraph') }}</p>
    <ul>
        @foreach (__('wp/3/9.revenue_list') as $item)
            @php
                $parts = explode(':', $item, 2); // Разделяем строку на 2 части
                $title = $parts[0]; // Первая часть (до ":")
                $description = isset($parts[1]) ? $parts[1] : ''; // Вторая часть (после ":"), если есть
            @endphp
            <li><strong>{{ $title }}:</strong> {{ $description }}</li>
        @endforeach
    </ul>

    <h3>{{ __('wp/3/9.consulting_title') }}</h3>
    <p>{{ __('wp/3/9.consulting_paragraph') }}</p>
