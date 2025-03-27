  <h4>{{ __('wp/3/5.title') }}</h4>
  <p>{{ __('wp/3/5.paragraph1') }}</p>
  <ul>
      @foreach (__('wp/3/5.distribution_list') as $item)
          @php
              $parts = explode(':', $item, 2); // Разделяем строку на 2 части
              $title = $parts[0]; // Первая часть (до ":")
              $description = isset($parts[1]) ? $parts[1] : ''; // Вторая часть (после ":"), если есть
          @endphp
          <li><strong>{{ $title }}:</strong> {{ $description }}</li>
      @endforeach
  </ul>
