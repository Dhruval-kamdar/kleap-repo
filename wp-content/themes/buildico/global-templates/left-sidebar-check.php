<?php
/**
 * Left sidebar check.
 *
 * @package buildico
 */

$sidebar_pos = wt_get_option('sidebar_position');
?>


<?php if ( 'left' === $sidebar_pos ) : ?>
	<?php get_sidebar( 'left' ); ?>
<?php endif; ?>

<?php 
	if ( 'right' === $sidebar_pos || 'left' === $sidebar_pos ) {
		echo '<div class="col-md-9 content-area" id="primary">';
	} else {
	    echo '<div class="col-md-12 content-area" id="primary">';
	}

