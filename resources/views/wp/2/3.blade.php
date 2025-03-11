<p>{{ __('wp/2/3.paragraph1') }}</p>
<ol>
    @foreach (__('wp/2/3.advantages_list') as $item)
        <li><strong>{{ explode(':', $item)[0] }}:</strong> {{ explode(':', $item)[1] }}</li>
    @endforeach
</ol>
<h3>{{ __('wp/2/3.description_title') }}</h3>
<p>{{ __('wp/2/3.description_paragraph1') }}</p>
<p>{{ __('wp/2/3.description_paragraph2') }}</p>
<p>{{ __('wp/2/3.description_paragraph3') }}</p>
<p>{{ __('wp/2/3.description_paragraph4') }}</p>
<p>{{ __('wp/2/3.description_paragraph5') }}</p>
<h3>{{ __('wp/2/3.collateral_mechanism_title') }}</h3>
<p>{{ __('wp/2/3.collateral_mechanism_paragraph') }}</p>
<ul>
    @foreach (__('wp/2/3.collateral_mechanism_list') as $item)
        <li>{{ $item }}</li>
    @endforeach
</ul>
<h3>{{ __('wp/2/3.algorithmic_collateral_title') }}</h3>
<p>{{ __('wp/2/3.algorithmic_collateral_paragraph') }}</p>
<p>{{ __('wp/2/3.algorithmic_collateral_paragraph2') }}</p>
