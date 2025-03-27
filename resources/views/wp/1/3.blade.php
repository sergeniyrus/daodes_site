    <h3 class="pros-cons">{{ __('wp/1/3.title') }}</h3>

    <div class="pros-cons">
        <h3>{{ __('wp/1/3.centralized_title') }}</h3>
        <p>{{ __('wp/1/3.centralized_description') }}</p>
        <h4>{{ __('wp/1/3.centralized_pros_title') }}</h4>
        <ul>
            @foreach (__('wp/1/3.centralized_pros_list') as $pro)
                <li>{{ $pro }}</li>
            @endforeach
        </ul>
        <h4>{{ __('wp/1/3.centralized_cons_title') }}</h4>
        <ul>
            @foreach (__('wp/1/3.centralized_cons_list') as $con)
                <li>{{ $con }}</li>
            @endforeach
        </ul>
    </div>

    <div class="pros-cons">
        <h3>{{ __('wp/1/3.crypto_backed_title') }}</h3>
        <p>{{ __('wp/1/3.crypto_backed_description') }}</p>
        <h4>{{ __('wp/1/3.crypto_backed_pros_title') }}</h4>
        <ul>
            @foreach (__('wp/1/3.crypto_backed_pros_list') as $pro)
                <li>{{ $pro }}</li>
            @endforeach
        </ul>
        <h4>{{ __('wp/1/3.crypto_backed_cons_title') }}</h4>
        <ul>
            @foreach (__('wp/1/3.crypto_backed_cons_list') as $con)
                <li>{{ $con }}</li>
            @endforeach
        </ul>
    </div>

    <div class="pros-cons">
        <h3>{{ __('wp/1/3.algorithmic_title') }}</h3>
        <p>{{ __('wp/1/3.algorithmic_description') }}</p>
        <h4>{{ __('wp/1/3.algorithmic_pros_title') }}</h4>
        <ul>
            @foreach (__('wp/1/3.algorithmic_pros_list') as $pro)
                <li>{{ $pro }}</li>
            @endforeach
        </ul>
        <h4>{{ __('wp/1/3.algorithmic_cons_title') }}</h4>
        <ul>
            @foreach (__('wp/1/3.algorithmic_cons_list') as $con)
                <li>{{ $con }}</li>
            @endforeach
        </ul>
    </div>

    <p>{{ __('wp/1/3.paragraph1') }}</p>
    <p>{{ __('wp/1/3.paragraph2') }}</p>
