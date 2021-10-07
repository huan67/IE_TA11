<?php
if (!empty($data)) {
    $width = esc_attr(get_option('wp_flashcard_width_setting')) . 'px';
    $card_item_width = "width:$width;";

    $height = esc_attr(get_option('wp_flashcard_height_setting'));
    if (!$height) {
        $height = 300;
        if (wp_is_mobile())
            $height = 378;
    }
    $card_item_height = "height: $height" . "px;";
    ?>
    <div class="flashcard_set" style="<?php echo $card_item_width . $card_item_height; ?>">
        <div class="loader-container full-page">
            <div class="loader"></div>
        </div>
        <div class="loader-container full-screen" style="display:none;">
            <div class="loader"></div>
        </div>
        <div class="flashcard_set-container" style="display:none;">
            <div class="owl-carousel owl-theme">
                <?php include('flashcard-items-html.php'); ?>
            </div>
        </div>
    </div>
<?php } ?>

