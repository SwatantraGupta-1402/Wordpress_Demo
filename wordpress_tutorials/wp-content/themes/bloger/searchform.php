<?php
/**
 * Search form 
 */
?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label>
		<span class="screen-reader-text"><?php esc_attr_e( 'Search for:','bloger' ); ?></span>
		<input type="search" name="s" autocomplete="off" class="search-field" placeholder="<?php esc_attr_e( 'Search &hellip;','bloger' ); ?>" value="<?php echo esc_attr(get_search_query()); ?>" title="<?php  esc_attr_e( 'Search for:','bloger' ); ?>" />
	</label>
	<input type="submit" class="search-submit" value="<?php esc_attr_e('Search','bloger'); ?>">
</form>