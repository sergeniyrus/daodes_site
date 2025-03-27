    <h4>{{ __('wp/3/3.title') }}</h4>

    <h2>{{ __('wp/3/3.validators_title') }}</h2>
    <p>{{ __('wp/3/3.validators_paragraph') }}</p>
    <ol>
        @foreach (__('wp/3/3.validators_list') as $item)
            @php
                $parts = explode(':', $item, 2); // Разделяем строку на 2 части
                $title = $parts[0]; // Первая часть (до ":")
                $description = isset($parts[1]) ? $parts[1] : ''; // Вторая часть (после ":"), если есть
            @endphp
            <li><strong>{{ $title }}:</strong> {{ $description }}</li>
        @endforeach
    </ol>

    <h2>{{ __('wp/3/3.delegators_title') }}</h2>
    <p>{{ __('wp/3/3.delegators_paragraph') }}</p>
    <ul>
        @foreach (__('wp/3/3.delegators_list') as $item)
            <li>{{ $item }}</li>
        @endforeach
    </ul>

    <h2>{{ __('wp/3/3.activists_title') }}</h2>
    <p>{{ __('wp/3/3.activists_paragraph') }}</p>
    <ol>
        @foreach (__('wp/3/3.activists_list') as $item)
            @php
                $parts = explode(':', $item, 2); // Разделяем строку на 2 части
                $title = $parts[0]; // Первая часть (до ":")
                $description = isset($parts[1]) ? $parts[1] : ''; // Вторая часть (после ":"), если есть
            @endphp
            <li><strong>{{ $title }}:</strong> {{ $description }}</li>
        @endforeach
    </ol>
