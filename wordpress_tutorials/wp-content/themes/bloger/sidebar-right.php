<?php
/**
 * The sidebar containing the main widget area.
 *
 * @package bloger Lite
 */

if ( ! is_active_sidebar( 'bloger_right_sidebar' ) ) {
	return;
}
?>

<div id="secondary" class="widget-area" role="complementary">
	<?php dynamic_sidebar( 'bloger_right_sidebar' ); ?>
</div><!-- #secondary -->
