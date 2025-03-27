    <h2 class="pros-cons">{{ __('wp/3/1.title') }}</h2>
    <p>{{ __('wp/3/1.paragraph1') }}</p>
    <ul>
        @foreach (__('wp/3/1.features_list') as $item)
            @php
                $parts = explode(':', $item, 2); // Разделяем строку на 2 части
                $title = $parts[0]; // Первая часть (до ":")
                $description = isset($parts[1]) ? $parts[1] : ''; // Вторая часть (после ":"), если есть
            @endphp
            <li><strong>{{ $title }}:</strong> {{ $description }}</li>
        @endforeach
    </ul>

    <h3>{{ __('wp/3/1.token_issuance_title') }}</h3>
    <p>{{ __('wp/3/1.token_issuance_paragraph') }}</p>
    <ol>
        @foreach (__('wp/3/1.token_issuance_list') as $item)
            @php
                $parts = explode(':', $item, 2); // Разделяем строку на 2 части
                $title = $parts[0]; // Первая часть (до ":")
                $description = isset($parts[1]) ? $parts[1] : ''; // Вторая часть (после ":"), если есть
            @endphp
            <li><strong>{{ $title }}:</strong> {{ $description }}</li>
            @if ($loop->first) <!-- Если это первый элемент, добавляем вложенный список -->
                <ul>
                    @foreach (__('wp/3/1.sub_list') as $subItem)
                        @php
                            $subParts = explode(':', $subItem, 2); // Разделяем строку на 2 части
                            $subTitle = $subParts[0]; // Первая часть (до ":")
                            $subDescription = isset($subParts[1]) ? $subParts[1] : ''; // Вторая часть (после ":"), если есть
                        @endphp
                        <li><strong>{{ $subTitle }}:</strong> {{ $subDescription }}</li>
                    @endforeach
                </ul>
            @endif
        @endforeach
    </ol>
