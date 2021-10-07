 <!-- Create a header in the default WordPress 'wrap' container -->
 <div class="wrap">
        <div id="icon-themes" class="icon32"></div>
        <h2><?php esc_html_e( 'Trip Plan Settings', 'tripplan-com' ) ?></h2>
        <?php settings_errors(); ?>
        <form method="post" action="options.php">
            <?php
                settings_fields( 'tripplan_group' );               // option group
                do_settings_sections( 'tripplan_settings' );       // the page it is on
                submit_button();
            ?>
        </form>
    </div><!-- /.wrap -->