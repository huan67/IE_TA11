<?php
if (!empty($data)) {
    foreach ($data as $card) {
        $foreground = $card['foreground'];
        $background = $card['background'];

        $height = esc_attr(get_option('wp_flashcard_height_setting')) . 'px';
        $card_item_height = "height: $height";
        ?>
        <div class="item">
            <div id="card" class="card-item" style="<?php echo $card_item_height; ?>">
                <div class="front">
                    <div class="card-text"><?php echo $foreground; ?></div>
                </div>
                <div class="back">
                    <div class="card-text"><?php echo $background; ?></div>
                </div>
            </div>
        </div>
    <?php } ?>
    <div class="item" style="<?php echo $card_item_height; ?>">
        <div id="card" class="" >
            <div class="front">
                <div class="card-result-content">
                    <strong><?php echo esc_html__('Nice work', 'wp-flashcard'); ?>!</strong>
                    <span class="card-text"><?php echo esc_html__('You just studied ', 'wp-flashcard') . count($data) . esc_html__(' terms', 'wp-flashcard') ?>!</span>
                    <span><a href="#" class="fc-btn start-over-flashcard"><?php echo esc_html__('Start over', 'wp-flashcard'); ?></a></span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
