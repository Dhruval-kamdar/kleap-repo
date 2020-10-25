<?php
/**
 * Right sidebar check.
 *
 * @package buildico
 */

$sidebar_pos = wt_get_option('sidebar_position');
?>

<?php if ( 'right' === $sidebar_pos ) : ?>

  <?php get_sidebar( 'right' ); ?>

<?php endif; ?>
