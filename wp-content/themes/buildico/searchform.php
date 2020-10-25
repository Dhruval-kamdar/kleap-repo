<?php
/**
 * The template for displaying search forms
 *
 * @package buildico
 */

?>
<form method="get" id="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>" role="search">
	<div class="input-group">
		<input class="field form-control" id="s" name="s" type="text"
			placeholder="<?php esc_attr_e( 'Search &hellip;', 'buildico' ); ?>" value="<?php the_search_query(); ?>">
		<span class="input-group-btn">
			<input class="submit" id="searchsubmit" name="submit" type="submit"
			value="<?php esc_attr_e( 'Search', 'buildico' ); ?>">
	</span>
	</div>
</form>
