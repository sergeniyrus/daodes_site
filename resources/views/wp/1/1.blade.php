<h1>{{ __('wp/1/1.title') }}</h1>
<p>{{ __('wp/1/1.paragraph1') }}</p>
<p>{{ __('wp/1/1.paragraph2') }}</p>
<div class="pros-cons">
    <h2>{{ __('wp/1/1.pow_title') }}</h2>
    <h3>{{ __('wp/1/1.pow_advantages_title') }}</h3>
    <ul>
        @foreach (__('wp/1/1.pow_advantages_list') as $advantage)
            <li>{{ $advantage }}</li>
        @endforeach
    </ul>
    <h3>{{ __('wp/1/1.pow_disadvantages_title') }}</h3>
    <ul>
        @foreach (__('wp/1/1.pow_disadvantages_list') as $disadvantage)
            <li>{{ $disadvantage }}</li>
        @endforeach
    </ul>
</div>
<div class="pros-cons">
    <h2>{{ __('wp/1/1.pos_title') }}</h2>
    <h3>{{ __('wp/1/1.pos_advantages_title') }}</h3>
    <ul>
        @foreach (__('wp/1/1.pos_advantages_list') as $advantage)
            <li>{{ $advantage }}</li>
        @endforeach
    </ul>
    <h3>{{ __('wp/1/1.pos_disadvantages_title') }}</h3>
    <ul>
        @foreach (__('wp/1/1.pos_disadvantages_list') as $disadvantage)
            <li>{{ $disadvantage }}</li>
        @endforeach
    </ul>
</div>
<p>{{ __('wp/1/1.paragraph3') }}</p>
<p>{{ __('wp/1/1.paragraph4') }}</p>