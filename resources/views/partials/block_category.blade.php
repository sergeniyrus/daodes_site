<?php
$category = DB::table($category_name)
    ->where('id', $category_id)
    ->value('category_name');
?>
<div class="item-3">
    <div class="category_post">
        <p>Тема:
            <?php echo $category; ?>
        </p>
    </div>    
        <div class="sidbar">
            <div class="category_menu">Темы:</div>
            <?php
            // получаем категории
            $categories = DB::table($category_name)->get();
            foreach ($categories as $category) :
                $num_p = 0;
                $single_cat = DB::table($post)->where('category_id', $category->id)->get();
                foreach ($single_cat as $single_news) :
                    $num_p++;
                endforeach;

                if ($num_p >= 1) :
                    $categories = DB::table($category_name)
                        ->where('id', $single_news->category_id)
                        ->value('category_name');
            ?>
            <div class="tab_category">
                <a id="tab_category" href="/category/<?php echo $post . '/' . $category->id; ?>">
                    <p><?php echo $categories . ' - ' . $num_p; ?></p>
                </a>
            </div>
            <?php
                endif;
            endforeach;
            ?>
        </div>
    

{{-- @auth
            <?php
            $rol = DB::table('users')
                ->where('name', Auth::user()->name)
                ->select('rang_access')
                ->first();
            ?>
            @if ($rol->rang_access >= 3)
                <div class="admin_menu my-4">
                    <div>
                        <a href="/add_cat" title="Создать">
                            <img src="/img/icons_post/add.png" alt="Создать">
                        </a>
                    </div>
                    <div>
                        <a href="/edit_cat">
                            <img src="/img/icons_post/edit.png" title="Редактировать" alt="Редактировать"></a>
                    </div>
                </div>
                @endif
            @endauth 
</div>--}}