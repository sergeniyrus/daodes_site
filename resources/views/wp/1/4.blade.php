    <p>{{ __('wp/1/4.paragraph1') }}</p>
    <p>{{ __('wp/1/4.paragraph2') }}</p>

    <div class="pros-cons">
        <h3>{{ __('wp/1/4.pros_title') }}</h3>
        <ul>
            @foreach (__('wp/1/4.pros_list') as $pro)
                <li>{{ $pro }}</li>
            @endforeach
        </ul>
    </div>

    <div class="pros-cons">
        <h3>{{ __('wp/1/4.cons_title') }}</h3>
        <ul>
            @foreach (__('wp/1/4.cons_list') as $con)
                <li>{{ $con }}</li>
            @endforeach
        </ul>
    </div>

    <p>{{ __('wp/1/4.paragraph3') }}</p>
