<div class="footer-post">
    <div class="infa_post">
        <div class="viewes_post">
            <span class="rown"><img src="/img/icons_post/views.png" />&nbsp;</span>
            <span class="rown">
                @if ($new->views === null)
                    0
                @else
                    {{ $new->views }}
                @endif
            </span>
        </div>
        <div class="comment_post">
            <span class="rown"><img src="/img/icons_post/comments.png" />&nbsp;</span>
            <span class="rown">
                @php
                    $commentCount = DB::table('comments_news')
                        ->where('news_id', $new->id)
                        ->count();
                @endphp
                {{ $commentCount }}
            </span>
        </div>
        <div class="data_post">
            <span class="rown"><img src="/img/icons_post/data.png" />&nbsp;</span>
            <span class="rown">
                {{ \Carbon\Carbon::parse($new->created_at)->format('d/m/y') }}
            </span>
        </div>
        <div class="author_post">
            <span class="rown"><img src="/img/icons_post/author.png" />&nbsp;</span>
            <span class="rown">
                @php
                    $authorName = DB::table('users')
                        ->where('id', $new->user_id)
                        ->value('name');
                @endphp
                {{ $authorName }}
            </span>
        </div>
    </div>
</div>
