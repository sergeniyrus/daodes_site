<h2>{{ __('wp/2/2.title') }}</h2>
<p>{{ __('wp/2/2.paragraph1') }}</p>
<ol>
    @foreach (__('wp/2/2.ecosystem_list') as $item)
        @php
            $parts = explode(':', $item, 2); // Разделяем строку на 2 части
            $title = $parts[0]; // Первая часть (до ":")
            $description = isset($parts[1]) ? $parts[1] : ''; // Вторая часть (после ":"), если есть
        @endphp
        <li><strong>{{ $title }}:</strong> {{ $description }}</li>
    @endforeach
</ol>
<h3>{{ __('wp/2/2.decentralized_section_title') }}</h3>
<p>{{ __('wp/2/2.decentralized_section_paragraph') }}</p>
<ol>
    @foreach (__('wp/2/2.decentralized_section_list') as $item)
        @php
            $parts = explode(':', $item, 2); // Разделяем строку на 2 части
            $title = $parts[0]; // Первая часть (до ":")
            $description = isset($parts[1]) ? $parts[1] : ''; // Вторая часть (после ":"), если есть
        @endphp
        <li><strong>{{ $title }}:</strong> {{ $description }}</li>
    @endforeach
</ol>
<p>{{ __('wp/2/2.paragraph2') }}</p>
