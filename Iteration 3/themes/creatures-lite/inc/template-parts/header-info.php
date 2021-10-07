<?php
/**
 * @package Creatures Lite
 */

$creatureheadinfo = get_theme_mod('creature_headinfohide','1');
if( $creatureheadinfo == '' ){
	$creaturelocatxt = esc_html(get_theme_mod('creature-location-txt',true));
	$creatureloca = esc_html(get_theme_mod('creature-location',true));
	$creaturewrktxt = esc_html(get_theme_mod('creature-wrkhrs-txt',true));
	$creaturewrk = esc_html(get_theme_mod('creature-wrkhrs',true));
	$creaturephntxt = esc_html(get_theme_mod('creature-phn-txt',true));
	$creaturephn = esc_html(get_theme_mod('creature-phn',true));
?>
<?php if( !empty( $creaturelocatxt || $creatureloca || $creaturewrktxt || $creaturewrk || $creaturephntxt || $creaturephn ) ){ ?>
<div class="header-right">
	<div class="flex">
		<?php if( !empty( $creaturelocatxt || $creatureloca ) ){ ?>
		<div class="box">
			<div class="headinfo">
				<div class="headinfo-icon">
					<i class="fa fa-map-marker" aria-hidden="true"></i>
				</div><!-- headinfo-icon -->
				<div class="headinfo-text">
					<h4><?php echo $creaturelocatxt; ?></h4>
					<p><?php echo $creatureloca; ?></p>
				</div><!-- headinfo-text -->
			</div><!-- headinfo -->
		</div><!-- box -->
		<?php } if( !empty( $creaturewrktxt || $creaturewrk ) ){ ?>
		<div class="box">
			<div class="headinfo">
				<div class="headinfo-icon">
					<i class="fa fa-clock-o" aria-hidden="true"></i>
				</div><!-- headinfo-icon -->
				<div class="headinfo-text">
					<h4><?php echo $creaturewrktxt; ?></h4>
					<p><?php echo $creaturewrk; ?></p>
				</div><!-- headinfo-text -->
			</div><!-- headinfo -->
		</div><!-- box -->
		<?php } if( !empty( $creaturephntxt || $creaturephn ) ){ ?>
		<div class="box">
			<div class="headinfo">
				<div class="headinfo-icon">
					<i class="fa fa-mobile" aria-hidden="true"></i>
				</div><!-- headinfo-icon -->
				<div class="headinfo-text">
					<h4><?php echo $creaturephntxt; ?></h4>
					<p><?php echo $creaturephn; ?></p>
				</div><!-- headinfo-text -->
			</div><!-- headinfo -->
		</div><!-- box -->
		<?php } ?>
	</div><!-- flex -->
</div><!-- header right -->
<?php } } ?>