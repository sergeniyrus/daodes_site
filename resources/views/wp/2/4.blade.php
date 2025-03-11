  <h2>{{ __('wp/2/4.title') }}</h2>
  <p>{{ __('wp/2/4.paragraph1') }}</p>
  <ul>
      @foreach (__('wp/2/4.features_list') as $item)
          @php
              $parts = explode(':', $item, 2); // Разделяем строку на 2 части
              $title = $parts[0]; // Первая часть (до ":")
              $description = isset($parts[1]) ? $parts[1] : ''; // Вторая часть (после ":"), если есть
          @endphp
          <li><strong>{{ $title }}:</strong> {{ $description }}</li>
      @endforeach
  </ul>
  <h3>{{ __('wp/2/4.liquidity_management_title') }}</h3>
  <p>{{ __('wp/2/4.liquidity_management_paragraph') }}</p>
  <ul>
      @foreach (__('wp/2/4.liquidity_management_list') as $item)
          @php
              $parts = explode(':', $item, 2); // Разделяем строку на 2 части
              $title = $parts[0]; // Первая часть (до ":")
              $description = isset($parts[1]) ? $parts[1] : ''; // Вторая часть (после ":"), если есть
          @endphp
          <li><strong>{{ $title }}:</strong> {{ $description }}</li>
      @endforeach
  </ul>
