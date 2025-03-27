    <h2>{{ __('wp/2/1.title') }}</h2>
    <p>{{ __('wp/2/1.paragraph1') }}</p>
    <p>{{ __('wp/2/1.paragraph2') }}</p>
    <p>{{ __('wp/2/1.paragraph3') }}</p>

    <h3>{{ __('wp/2/1.features_title') }}</h3>
    <ol>
        @foreach (__('wp/2/1.features_list') as $feature)
            <li>{{ $feature }}</li>
        @endforeach
    </ol>

    <h3>{{ __('wp/2/1.scalability_title') }}</h3>
    <p>{{ __('wp/2/1.scalability_paragraph') }}</p>
    <ol>
        @foreach (__('wp/2/1.scalability_list') as $item)
            <li>{{ $item }}</li>
        @endforeach
    </ol>

    <p>{{ __('wp/2/1.paragraph4') }}</p>
    <p>{{ __('wp/2/1.paragraph5') }}</p>
    <p>{{ __('wp/2/1.paragraph6') }}</p>
    <p>{{ __('wp/2/1.paragraph7') }}</p>

    <h3>{{ __('wp/2/1.commission_title') }}</h3>
    <ol>
        @foreach (__('wp/2/1.commission_list') as $item)
            <li>{{ $item }}</li>
        @endforeach
    </ol>

    <h3>{{ __('wp/2/1.ecosystem_title') }}</h3>
    <p>{{ __('wp/2/1.ecosystem_paragraph') }}</p>
    <ol>
        @foreach (__('wp/2/1.ecosystem_list') as $item)
            <li>{{ $item }}</li>
        @endforeach
    </ol>

    <h3>{{ __('wp/2/1.decentralized_section_title') }}</h3>
    <ol>
        @foreach (__('wp/2/1.decentralized_section_list') as $item)
            <li>{{ $item }}</li>
        @endforeach
    </ol>
    <p>{{ __('wp/2/1.paragraph8') }}</p>
