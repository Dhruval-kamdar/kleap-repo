<?php
/**
 * Cartflows Functions.
 *
 * @package CARTFLOWS
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


/**
 * Is custom checkout?
 *
 * @param int $checkout_id checkout ID.
 * @since 1.0.0
 */
function _is_wcf_optin_custom_fields( $checkout_id ) {

	$is_custom = wcf()->options->get_optin_meta_value( $checkout_id, 'wcf-optin-enable-custom-fields' );

	if ( 'yes' === $is_custom ) {

		return true;
	}

	return false;
}

/**
 * Get get step object.
 *
 * @param int $step_id current step ID.
 * @since 1.5.9
 */
function wcf_pro_get_step( $step_id ) {

	if ( ! isset( wcf_pro()->wcf_step_objs[ $step_id ] ) ) {

		wcf_pro()->wcf_step_objs[ $step_id ] = new Cartflows_Pro_Step_Factory( $step_id );
	}

	return wcf_pro()->wcf_step_objs[ $step_id ];
}

/**
 * Get ab test
 *
 * @param int $step_id current step ID.
 * @since 1.0.0
 */
function wcf_get_ab_test( $step_id ) {

	return new Cartflows_Pro_Ab_Test_Factory( $step_id );

}
