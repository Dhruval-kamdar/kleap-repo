<?php

	if (!defined('ABSPATH')) {
	  exit;
	}

	/**
	 * Get the trial period
	 */
	function wu_woo_get_days_before_next_billing($subscription) {
		
	  if ( ! $subscription) return 0;
	  $now           = WU_Subscription::get_now('timestamp');
	  $billing_start = new DateTime($subscription->get_billing_start('Y-m-d h:i:s'));
	  return $billing_start->diff($now)->days;

	}

	/**
	 * Creates a WooCommerce Cart subscription based on a WP Ultimo subscription
	 */
	function wu_woo_create_woocommerce_subscription($subscription) {
		
	  $plan = $subscription->get_plan();
	  
	  $default_Pricing =  WU_Settings::get_setting('default_pricing_option');  //Yearly
	  $selectedPricing = array('monthly' => 1, 'quarterly'=> 3, 'yearly' => 12);
	  $enabledPricing = array_search($default_Pricing, $selectedPricing); 
	  $currentPlanID = $plan->id; //Plan ID
	  
	  switch_to_blog(1);
	  //echo $enabledPricing.$currentPlanID;
	  $item_id = get_option($enabledPricing.$currentPlanID);
	  restore_current_blog();

	  // Product Data
	  update_post_meta($item_id, '_subscription_period', 'month', true);
	  update_post_meta($item_id, '_subscription_period_interval', $subscription->freq, true);
	  update_post_meta($item_id, '_subscription_price', $subscription->price, true);

	  // Trial
	  $trial_length = wu_woo_get_days_before_next_billing( $subscription );
	  
	  if ($trial_length > 0) {

		update_post_meta($item_id, '_subscription_trial_period', 'day', true);
		update_post_meta($item_id, '_subscription_trial_length', wu_woo_get_days_before_next_billing( $subscription ), true);

	  } // end if;
		
	  switch_to_blog(1);
	  global $woocommerce; 
	  $woocommerce->cart->empty_cart();

	  $cart_item_key = WC()->cart->add_to_cart( $item_id, 1, 0, array(), array(
		'_wu_plan_id'      => $subscription->plan_id,
		'_wu_subscription' => 'yes'
	  ) );
	  restore_current_blog();
	  

	  return (bool) $cart_item_key;

	}
