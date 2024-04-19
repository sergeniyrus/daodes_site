@extends('template')

@section('title_page')
<?php
if ($post == "offers") {
    $category_name = "category_offers";
    $comments_tab = 'comments_offers';
        $column = 'offer_id';
echo "Темы предложений";
}
if ($post == "news"){
    $category_name = "category_news";
    $comments_tab = 'comments_news';
        $column = 'new_id';
echo "Темы новостей";
}
?>
@endsection

@section('main')
{{-- тело вывода --}}
@if ($texts->count())
@foreach ($texts as $text)
@php
if ($post == "offers") {
$link="/page/offers/{$text->id}";
}
if ($post == "news"){
$link="/page/news/{$text->id}";
}
@endphp

<br>
<div class="posts">
    <table>
        <tr>
            <td class="td_column">
                <div class="img_post"><img src="/img/post_img/{{ $text->img }}" /></div>
            </td>
            <td class="td_column_c">
                <table>
                    <tr>
                        <td>
                            <div class="title_post">
                                <h5>
                                    <a href="<?php echo $link; ?>">{{ $text->title }}</a>
                                    <hr class="hr_title">
                                </h5>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="text_post">
                                <p>
                                    {!! Str::limit(html_entity_decode($text->text), 250) !!}
                                    <a class="all_post" href="{{ $link }}">Дальше</a>
                                </p>
                            </div>
                        </td>
                    </tr>
                </table>
            </td>

            {{-- //темы категгории --}}
            
            @php  
            $category_id = $text->category_id;
            @endphp
            @include('partials.block_category')
        </tr>
        <tr>
            <td></td>
            <td align="center">
                <div class="footer-post">
                    <div class="infa_post">
                        <div class="viewes_post">
                            <span class="rown"><img src="/img/icons_post/views.png" />&nbsp;</span>
                            <span class="rown">
                                @if (($text->views)===NULL) 0
                                @else {{ $text->views }}                           
                                @endif
                            </span>
                        </div>
                        <div class="comment_post">
                            <span class="rown"><img src="/img/icons_post/comments.png" />&nbsp;</span>
                            <span class="rown">
                                <?php
                                    $commentCount = DB::table($comments_tab)
                                        ->where($column, $text->id)
                                        ->count();
                                    ?>
                                {{ $commentCount }}
                            </span>
                        </div>
                        <div class="data_post">
                            <span class="rown"><img src="/img/icons_post/data.png" />&nbsp;</span>
                            <span class="rown">
                                {{ $text->created_at }}
                            </span>
                        </div>
                        <div class="author_post">
                            <span class="rown"><img src="/img/icons_post/author.png" />&nbsp;</span>
                            <span class="rown">
                                {{ $text->author }}
                            </span>
                        </div>
                    </div>
            </td>
            <td></td>
        </tr>
    </table>
</div>
<br>
@endforeach
@endif
@endsection