<section>
  <h2 class="pros-cons">{{ __('wp/1.introduction_title') }}</h2>
  @foreach (__('wp/1.introduction_content') as $paragraph)
      @if (Str::startsWith($paragraph, '<strong>'))
          {!! $paragraph !!}
      @else
          <p>{{ $paragraph }}</p>
      @endif
  @endforeach
</section>

<section>
  <h2 class="pros-cons">{{ __('wp/1.telegram_title') }}</h2>
  <p>{{ __('wp/1.telegram_content.p1') }}</p>
  <ul>
      @foreach (__('wp/1.telegram_content.list') as $item)
          <li>{{ $item }}</li>
      @endforeach
  </ul>
  <p>{{ __('wp/1.telegram_content.p2') }}</p>
</section>

<section>
  <h2 class="pros-cons">{{ __('wp/1.mission_title') }}</h2>
  @foreach (__('wp/1.mission_content') as $key => $paragraph)
      @if ($key === 'list')
          <ol>
              @foreach ($paragraph as $item)
                  <li>{!! $item !!}</li>
              @endforeach
          </ol>
      @else
          <p>{{ $paragraph }}</p>
      @endif
  @endforeach
</section>

<section>
  <h2 class="pros-cons">{{ __('wp/1.goals_title') }}</h2>
  <p>{{ __('wp/1.goals_content.p1') }}</p>
  <ul>
      @foreach (__('wp/1.goals_content.list') as $item)
          <li>{{ $item }}</li>
      @endforeach
  </ul>
  <p>{{ __('wp/1.goals_content.p2') }}</p>
</section>

<section>
  <h2 class="pros-cons">{{ __('wp/1.ecosystem_title') }}</h2>
  <p>{{ __('wp/1.ecosystem_content.p1') }}</p>
  <ul>
      @foreach (__('wp/1.ecosystem_content.list') as $item)
          <li>{!! $item !!}</li>
      @endforeach
  </ul>
</section>

<section>
  <h2 class="pros-cons">{{ __('wp/1.token_title') }}</h2>
  <p>{{ __('wp/1.token_content.p1') }}</p>
  <ul>
      @foreach (__('wp/1.token_content.list') as $item)
          <li>{{ $item }}</li>
      @endforeach
  </ul>
</section>

<footer>
  <h2 class="pros-cons">{{ __('wp/1.footer') }}</h2>
</footer>