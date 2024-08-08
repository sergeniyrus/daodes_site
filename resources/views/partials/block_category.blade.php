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
    
</div>
