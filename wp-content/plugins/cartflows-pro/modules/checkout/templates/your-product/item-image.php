<?php
/**
 * Item image
 *
 * @package cartflows
 */

?>

<?php if ( 'yes' === self::$product_option_data['product_images'] ) { ?>
	<div class="wcf-item-image" style=""><?php echo $rc_product_obj->get_image(); ?></div>
<?php } ?>
