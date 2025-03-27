    <h2>{{ __('wp/3/8.title') }}</h2>
    <p>{{ __('wp/3/8.paragraph1') }}</p>

    <h3>{{ __('wp/3/8.benefits_title') }}</h3>
    <ul>
        @foreach (__('wp/3/8.benefits_list') as $item)
            @php
                $parts = explode(':', $item, 2); // Разделяем строку на 2 части
                $title = $parts[0]; // Первая часть (до ":")
                $description = isset($parts[1]) ? $parts[1] : ''; // Вторая часть (после ":"), если есть
            @endphp
            <li><strong>{{ $title }}:</strong> {{ $description }}</li>
        @endforeach
    </ul>

    <h3>{{ __('wp/3/8.investment_mechanism_title') }}</h3>
    <p>{{ __('wp/3/8.investment_mechanism_paragraph') }}</p>
    <ol>
        @foreach (__('wp/3/8.investment_mechanism_list') as $item)
            @php
                $parts = explode(':', $item, 2); // Разделяем строку на 2 части
                $title = $parts[0]; // Первая часть (до ":")
                $description = isset($parts[1]) ? $parts[1] : ''; // Вторая часть (после ":"), если есть
            @endphp
            <li><strong>{{ $title }}:</strong> {{ $description }}</li>
        @endforeach
    </ol>

    <h3>{{ __('wp/3/8.future_development_title') }}</h3>
    <p>{{ __('wp/3/8.future_development_paragraph') }}</p>
    <ul>
        @foreach (__('wp/3/8.future_development_list') as $item)
            @php
                $parts = explode(':', $item, 2); // Разделяем строку на 2 части
                $title = $parts[0]; // Первая часть (до ":")
                $description = isset($parts[1]) ? $parts[1] : ''; // Вторая часть (после ":"), если есть
            @endphp
            <li><strong>{{ $title }}:</strong> {{ $description }}</li>
        @endforeach
    </ul>

    <h3>{{ __('wp/3/8.discussion_title') }}</h3>
    <ul>
        @foreach (__('wp/3/8.discussion_list') as $item)
            @php
                $parts = explode(':', $item, 2); // Разделяем строку на 2 части
                $title = $parts[0]; // Первая часть (до ":")
                $description = isset($parts[1]) ? $parts[1] : ''; // Вторая часть (после ":"), если есть
            @endphp
            <li><strong>{{ $title }}:</strong> {{ $description }}</li>
        @endforeach
    </ul>

    <h3>{{ __('wp/3/8.statistics_title') }}</h3>
    <p>{{ __('wp/3/8.statistics_paragraph') }}</p>
