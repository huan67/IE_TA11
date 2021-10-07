<?php
/**
 * Custom template tags for this theme
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package WEN_Commerce
 */

if ( ! function_exists( 'wen_commerce_posted_on' ) ) :
	/**
	 * Prints HTML with meta information for the current post-date/time and author.
	 */
	function wen_commerce_posted_on() {
		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
			$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
		}

		$time_string = sprintf( $time_string,
			esc_attr( get_the_date( 'c' ) ),
			esc_html( get_the_date() ),
			esc_attr( get_the_modified_date( 'c' ) ),
			esc_html( get_the_modified_date() )
		);

		$posted_on = '<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>';

		echo '<span class="posted-on"><span class="screen-reader-text">' .  esc_html__( ' Posted on ', 'wen-commerce' ) . '</span>' .  $posted_on . '</span>';
	}
endif;

if ( ! function_exists( 'wen_commerce_entry_footer' ) ) :
	/**
	 * Prints HTML with meta information for the categories, tags and comments.
	 */
	function wen_commerce_entry_footer() {
		// Hide category and tag text for pages.
		if ( 'post' === get_post_type() ) {
			$categories_list = get_the_category_list( ' ' ); // Separate list by space.

			if ( $categories_list  ) {
				echo '<span class="cat-links">' . '<span class="screen-reader-text">' . __( 'Categories', 'wen-commerce' ) . '</span>' . $categories_list . '</span>';
			}

			$tags_list = get_the_tag_list( wen_commerce_get_svg( array( 'icon' => 'tag' ) ),  wen_commerce_get_svg(array( 'icon' => 'tag' ) ) ); // Separate list by icon.

			if ( $tags_list  ) {
				echo '<span class="tags-links">' . '<span class="screen-reader-text">' . __( 'Tags', 'wen-commerce' ) . ',' . '</span>' . $tags_list . '</span>';
			}
		}

		if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
			echo '<span class="comments-link">';
			comments_popup_link(
				sprintf(
					wp_kses(
						/* translators: %s: post title */
						__( 'Leave a Comment<span class="screen-reader-text"> on %s</span>', 'wen-commerce' ),
						array(
							'span' => array(
								'class' => array(),
							),
						)
					),
					get_the_title()
				)
			);
			echo '</span>';
		}

		edit_post_link(
			sprintf(
				wp_kses(
					/* translators: %s: Name of current post. Only visible to screen readers */
					__( 'Edit <span class="screen-reader-text">%s</span>', 'wen-commerce' ),
					array(
						'span' => array(
							'class' => array(),
						),
					)
				),
				get_the_title()
			),
			'<span class="edit-link">',
			'</span>'
		);
	}
endif;

if ( ! function_exists( 'wen_commerce_author_bio' ) ) :
	/**
	 * Prints HTML with meta information for the author bio.
	 */
	function wen_commerce_author_bio() {
		if ( '' !== get_the_author_meta( 'description' ) ) {
			get_template_part( 'template-parts/biography' );
		}
	}
endif;

if ( ! function_exists( 'wen_commerce_by_line' ) ) :
	/**
	 * Prints HTML with meta information for the author bio.
	 */
	function wen_commerce_by_line() {
		$post_id = get_queried_object_id();
		$post_author_id = get_post_field( 'post_author', $post_id );

		$byline = '<span class="author vcard">';

		$byline .= '<a class="url fn n" href="' . esc_url( get_author_posts_url( $post_author_id ) ) . '">' . esc_html( get_the_author_meta( 'nickname', $post_author_id ) ) . '</a></span>';

		echo '<span class="byline">' . $byline . '</span>';
	}
endif;

if ( ! function_exists( 'wen_commerce_cat_list' ) ) :
	/**
	 * Prints HTML with meta information for the categories
	 */
	function wen_commerce_cat_list() {
		// Hide category and tag text for pages.
		if ( 'post' === get_post_type() ) {
			/* translators: used between list items, there is a space after the / */
			$categories_list = get_the_category_list( esc_html__( ', ', 'wen-commerce' ) );
			if ( $categories_list ) {
				/* translators: 1: list of categories. */
				printf( '<span class="cat-links"><span class="screen-reader-text">%1$s </span>%2$s</span>', esc_html__(  'Cat Links', 'wen-commerce' ), $categories_list ); // WPCS: XSS OK.
			}
		} elseif ( 'jetpack-portfolio' == get_post_type() ) {
			/* translators: used between list items, there is a space after the / */
			$categories_list = get_the_term_list( get_the_ID(), 'jetpack-portfolio-type', '', esc_html__( ' / ', 'wen-commerce' ) );

			if ( ! is_wp_error( $categories_list ) ) {
				printf( '<span class="cat-links"><span class="screen-reader-text">%1$s </span>%2$s</span>', esc_html__(  'Cat Links', 'wen-commerce' ), $categories_list ); // WPCS: XSS OK.
			}
		} elseif ( 'featured-content' == get_post_type() ) {
			/* translators: used between list items, there is a space after the / */
			$categories_list = get_the_term_list( get_the_ID(), 'featured-content-type', '', esc_html__( ' / ', 'wen-commerce' ) );

			if ( ! is_wp_error( $categories_list ) ) {
				printf( '<span class="cat-links"><span class="screen-reader-text">%1$s </span>%2$s</span>', esc_html__(  'Cat Links', 'wen-commerce' ), $categories_list ); // WPCS: XSS OK.
			}
		}
	}
endif;

/**
 * Footer Text
 *
 * @get footer text from theme options and display them accordingly
 * @display footer_text
 * @action wen_commerce_footer
 *
 * @since WEN Commerce 1.0
 */
function wen_commerce_footer_content() {
	$theme_data = wp_get_theme();

	$search  = array( '[the-year]', '[site-link]', '[privacy-policy-link]' );
	$replace = array(
		esc_attr( date_i18n( __( 'Y', 'wen-commerce' ) ) ),
		'<a href="'. esc_url( home_url( '/' ) ) .'">'. esc_attr( get_bloginfo( 'name', 'display' ) ) . '</a>',
		get_the_privacy_policy_link(),
	);

	$copyright_text = str_replace( $search, $replace, get_theme_mod( 'wen_commerce_footer_copyright_text', sprintf( _x( 'Copyright &copy; %1$s %2$s. All Rights Reserved. %3$s ', '1: Year, 2: Site Title with home URL, 3: Privacy Policy Link', 'wen-commerce' ), '[the-year]', '[site-link]', '[privacy-policy-link]'. '<span class="sep"> | </span>') ) );

	$powered_by = esc_html( $theme_data->get( 'Name' ) ) . '&nbsp;' . esc_html__( 'by', 'wen-commerce' ) . '&nbsp;<a target="_blank" href="' . esc_url( $theme_data->get( 'AuthorURI' ) ) . '">' . esc_html( $theme_data->get( 'Author' ) ) . '</a>';

	$footer_copyright = $copyright_text . $powered_by;

	if ( $footer_copyright ) {
		$footer_copyright = '<div id="footer-content" class="copyright">' . $footer_copyright . '</div>';
	}

	$footer_content = '<div class="site-info">';
	
	if ( $footer_copyright ) {
		$footer_content .= '	
			<div class="wrapper">
			' . $footer_copyright. '
			</div><!-- .wrapper -->
		</div><!-- .site-info -->';
	}

	echo $footer_content;
}
add_action( 'wen_commerce_credits', 'wen_commerce_footer_content', 10 );

if ( ! function_exists( 'wen_commerce_single_image' ) ) :
	/**
	 * Display Single Page/Post Image
	 */
	function wen_commerce_single_image() {
		$featured_image = get_theme_mod( 'wen_commerce_single_layout', 'disabled' );

		if ( ( 'disabled' == $featured_image  || ! has_post_thumbnail() ) ) {
			return false;
		} ?>
		
		<figure class="entry-image <?php echo esc_attr( $featured_image ); ?>">
            <?php the_post_thumbnail( $featured_image ); ?>
        </figure>
	   	<?php
	}
endif; // wen_commerce_single_image.

if ( ! function_exists( 'wen_commerce_archive_image' ) ) :
	/**
	 * Display Post Archive Image
	 */
	function wen_commerce_archive_image() {
		if ( ! has_post_thumbnail() ) {
			// Bail if there is no featured image.
			return;
		}
		
		wen_commerce_post_thumbnail();
	}
endif; // wen_commerce_archive_image.

if ( ! function_exists( 'wen_commerce_comment' ) ) :
	/**
	 * Template for comments and pingbacks.
	 * Used as a callback by wp_list_comments() for displaying the comments.
	 */
	function wen_commerce_comment( $comment, $args, $depth ) {
		if ( 'pingback' == $comment->comment_type || 'trackback' == $comment->comment_type ) : ?>

		<li id="comment-<?php comment_ID(); ?>" <?php comment_class(); ?>>
			<div class="comment-body">
				<?php esc_html_e( 'Pingback:', 'wen-commerce' ); ?> <?php comment_author_link(); ?> <?php edit_comment_link( esc_html__( 'Edit', 'wen-commerce' ), '<span class="edit-link">', '</span>' ); ?>
			</div>

		<?php else : ?>

		<li id="comment-<?php comment_ID(); ?>" <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ); ?>>
			<article id="div-comment-<?php comment_ID(); ?>" class="comment-body">

				<div class="comment-author vcard">
					<?php if ( 0 != $args['avatar_size'] ) echo get_avatar( $comment, $args['avatar_size'] ); ?>
				</div><!-- .comment-author -->

				<div class="comment-container">
					<header class="comment-meta">
						<?php printf( __( '%s <span class="says screen-reader-text">says:</span>', 'wen-commerce' ), sprintf( '<cite class="fn author-name">%s</cite>', get_comment_author_link() ) ); ?>

						<a class="comment-permalink entry-meta" href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>">
						<time datetime="<?php comment_time( 'c' ); ?>"><?php printf( esc_html__( '%s ago', 'wen-commerce' ), human_time_diff( get_comment_time( 'U' ), current_time( 'timestamp' ) ) ); ?></time></a>
					<?php edit_comment_link( esc_html__( 'Edit', 'wen-commerce' ), '<span class="edit-link">', '</span>' ); ?>
					</header><!-- .comment-meta -->

					<?php if ( '0' == $comment->comment_approved ) : ?>
						<p class="comment-awaiting-moderation"><?php esc_html_e( 'Your comment is awaiting moderation.', 'wen-commerce' ); ?></p>
					<?php endif; ?>

					<div class="comment-content">
						<?php comment_text(); ?>
					</div><!-- .comment-content -->

					<?php
						comment_reply_link( array_merge( $args, array(
							'add_below' => 'div-comment',
							'depth'     => $depth,
							'max_depth' => $args['max_depth'],
							'before'    => '<span class="reply">',
							'after'     => '</span>',
						) ) );
					?>
				</div><!-- .comment-content -->

			</article><!-- .comment-body -->
		<?php /* No closing </li> is needed.  WordPress will know where to add it. */ ?>

		<?php
		endif;
	}
endif; // ends check for wen_commerce_comment()

/**
 * Used to wrap post count in Categories widget with a span tag
 */
function wen_commerce_archives_cat_count_span($links) {
	$links = str_replace('</a> (', '</a> <span>(', $links);
	$links = str_replace(')', ')</span>', $links);
	return $links;
}
add_filter('wp_list_categories', 'wen_commerce_archives_cat_count_span');

/**
 * Used to wrap post count in Archives widget with a span tag
 */
function wen_commerce_archives_count_span($links) {
	$links = str_replace('</a>&nbsp;(', '</a> <span>(', $links);
	$links = str_replace(')', ')</span>', $links);
	return $links;
}
add_filter('get_archives_link', 'wen_commerce_archives_count_span');
