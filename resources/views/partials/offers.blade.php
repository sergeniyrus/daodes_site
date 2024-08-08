<!-- предложение для главной DAO -->
<div class="windows-new">
    <div class="windows-new-images">
        <img src="<?php echo $offer->img; ?>" alt="" />
    </div>
    <div class="windows-string">
        <a href="/page/offers/<?php echo $offer->id; ?>">
            <div class="windows-new-title">
                <?php echo $offer->title; ?>
            </div>
        </a>
        <div class="windows-new-text">
            <p>
                <?php echo html_entity_decode(substr($offer->content, 0, 500), ENT_QUOTES, 'utf-8'); ?>
            </p>
        </div>
    </div>
</div>
