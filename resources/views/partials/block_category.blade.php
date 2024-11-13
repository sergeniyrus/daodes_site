<style>
    .category_post {
  width: 100%;
  font-family: Georgia, "Times New Roman", Times, serif;
  font-size: min(max(80%, 3vw), 160%);
  color: rgb(0, 68, 255);
  text-align: center;
}
.sidbar {
  width: 90%;
  height: max-content;
  background-color: #0b0c18ce;
  border: 1px solid #fff;
  margin-top: 1vh;
  padding: 5px;
  border-radius: 15px 0px 15px 5px;
  text-align: center;
}
.category_menu {
  font-family: Georgia, "Times New Roman", Times, serif;
  font-size: min(max(80%, 4vw), 160%);
  text-align: center;
  margin-left: 1vh;
  border-bottom: 1px solid #0fc51e;
  color: #0fc51e;
}

.tab_category {
  margin-top: 1vh;
  text-align: justify;
  font-size: min(max(80%, 1vw), 160%);
}

a#tab_category:active,
a#tab_category:hover {
  text-decoration: none;
  color: #629de0;
}

a#tab_category {
  text-decoration: none;
  color: #05ff19;
}
</style>
<?php
$category = DB::table($category_name)
    ->where('id', $category_id)
    ->value('name');
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
                        ->value('name');
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
