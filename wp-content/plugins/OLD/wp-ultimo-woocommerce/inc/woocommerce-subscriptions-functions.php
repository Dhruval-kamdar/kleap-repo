<?php
/**
 * WooCommerce Subscription Integration Functions
 *
 * Handles Integration with WooCommerce Subscriptions
 *
 * @author      WP_Ultimo
 * @category    Admin
 * @package     WP_Ultimo/Gateways/WooCommerce
 * @since       1.2.0
*/

if (!defined('ABSPATH')) {
  exit;
}

/**
 * Retrieves the trial period we need to set for this subscription based on trials and etc
 * 
 * @since 1.2.0
 * @param WU_Subscription $subscription
 * @return integer
 */
function wu_get_days_before_next_billing($subscription) {

  if ( ! $subscription) return 0;

  $now           = WU_Subscription::get_now('timestamp');
  $billing_start = new DateTime($subscription->get_billing_start('Y-m-d h:i:s'));

  return $billing_start->diff($now)->days;

} // end wu_get_days_before_next_billing;

/**
 * Creates a WooCommerce Cart subscription based on a WP Ultimo subscription
 *
 * @since 1.2.0
 * @param WU_Subscription $subscription
 * @return void
 */
function wu_create_woocommerce_subscription($subscription) {

  $plan = $subscription->get_plan();

  $line_item = new WC_Product_Subscription();

  $line_item->set_props(array(
    'name'          => $plan->title,
    'price'         => $subscription->get_price_after_coupon_code(),
    'regular_price' => $subscription->get_price_after_coupon_code(),
  ));

  $item_id = $line_item->save();

  // Subscription Data
  update_post_meta($item_id, '_subscription_period', 'month', true);
  update_post_meta($item_id, '_subscription_period_interval', $subscription->freq, true);

  /**
   * Add setup fee
   */
  if ($plan->has_setup_fee()) {

    update_post_meta($item_id, '_subscription_sign_up_fee', $plan->get_setup_fee(), true);

  } // if;

  // Trial
  $trial_length = wu_get_days_before_next_billing( $subscription );

  if ($trial_length > 0) {

    update_post_meta($item_id, '_subscription_trial_period', 'day', true);
    update_post_meta($item_id, '_subscription_trial_length', wu_get_days_before_next_billing( $subscription ), true);

  } // end if;
  
  WC()->cart->empty_cart();

  $cart_item_key = WC()->cart->add_to_cart( $item_id, 1, 0, array(), array(
    '_wu_plan_id'      => $subscription->plan_id,
    '_wu_subscription' => 'yes'
  ) );

  return (bool) $cart_item_key;

} // end wu_create_woocommerce_subscription;
