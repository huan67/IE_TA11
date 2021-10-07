<?php
/**
 * @package Creatures Lite
 */

$creaturetophead = get_theme_mod('creature_tophide','1');
if( $creaturetophead == '' ){

	$creaturewlcmtxt = esc_html(get_theme_mod('creature-wlcm-txt',true));
	$creaturemailtxt = esc_url(get_theme_mod('creature-email-txt',true));

	$creaturefbicn = get_theme_mod('facebook',true);
	$creaturetwicn = get_theme_mod('twitter',true);
	$creatureigicn = get_theme_mod('instagram',true);
	$creatureliicn = get_theme_mod('linkedin',true);
	$creaturegpicn = get_theme_mod('google',true);
	$creatureyticn = get_theme_mod('youtube',true);
?>
	<div class="top-header-bar">
		<div class="aligner">
			<div class="flex ac">
				<?php if( !empty( $creaturewlcmtxt || $creaturemailtxt ) ){ ?>
				<div class="top-head-left">
					<ul>
						<li><?php echo $creaturewlcmtxt; ?></li>
						<li><a href="mailto:<?php echo $creaturemailtxt; ?>"><?php echo $creaturemailtxt; ?></a></li>
					</ul>
				</div><!-- top-head-left -->
				<?php } if( !empty( $creaturefbicn || $creaturetwicn || $creatureigicn || $creatureliicn || $creaturegpicn || $creatureyticn ) ){ ?>
				<div class="top-head-right">
					<div class="top-head-scl">
						<?php
							if( !empty( $creaturefbicn ) ){
								echo '<a href="'.esc_url($creaturefbicn).'" target="_blank" title="facebook-f"><i class="fa fa-facebook" aria-hidden="true"></i></a>';
							}
							if( !empty( $creaturetwicn ) ){
								echo '<a href="'.esc_url($creaturetwicn).'" target="_blank" title="twitter"><i class="fa fa-twitter" aria-hidden="true"></i></a>';
							}
							if( !empty( $creatureigicn ) ){
								echo '<a href="'.esc_url($creatureigicn).'" target="_blank" title="instagram"><i class="fa fa-instagram" aria-hidden="true"></i></a>';
							}
							if( !empty( $creatureliicn ) ){
								echo '<a href="'.esc_url($creatureliicn).'" target="_blank" title="linkedin-in"><i class="fa fa-linkedin" aria-hidden="true"></i></a>';
							}
							if( !empty( $creaturegpicn ) ){
								echo '<a href="'.esc_url($creaturegpicn).'" target="_blank" title="google-plus-g"><i class="fa fa-google-plus" aria-hidden="true"></i></a>';
							}
							if( !empty( $creatureyticn ) ){
								echo '<a href="'.esc_url($creatureyticn).'" target="_blank" title="youtube"><i class="fa fa-youtube-play" aria-hidden="true"></i></a>';
							}
						?>
					</div><!-- top-head-scl -->
				</div><!-- top-head-right -->
				<?php } ?>
			</div><!-- flex -->
		</div><!-- aligner -->
	</div><!-- top header bar -->
<?php
	}
?>