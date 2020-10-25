<?php

if (!class_exists('Affiliate_WP_Recurring_Base')) return;

class AffiliateWP_WP_Ultimo extends Affiliate_WP_Recurring_Base {

	/**
	 * Get things started
	 *
	 * @access  public
	 * @since   1.0
	*/
	public function init() {

		$this->context = 'wp-ultimo';

		add_action( 'wp_ultimo_payment_completed', array( $this, 'record_referral_on_payment' ), 1, 4);

    add_filter( 'affwp_referral_reference_column', array( $this, 'reference_link' ), 10, 2 );
    
	}

	/**
	 * Insert referrals on subscription payments
	 *
	 * @access  public
	 * @since   1.0
	 *
   * @param  integer $user_id User ID
   * @param  string   $gateway String identifying the gateway being used
   * @param  string   $amount  Value of the payment received
   * @param  string   $setup_fee_value  Value of the setup fee received
	*/
	public function record_referral_on_payment($user_id, $gateway, $amount, $setup_fee_value = 0) {

		$referral = affiliate_wp()->referrals->get_by('reference', $user_id);

		if (!$referral || !is_object($referral) || ('pending' == $referral->status && $referral->amount != 0)) {

			return false; // This signup wasn't referred or is the very first payment of a referred subscription

		} // end if;

		$reference = uniqid();

		if (affiliate_wp()->settings->get('wp_ultimo_setup_fee_affwp')) {
			
			$amount = $amount + $setup_fee_value;

		} // end if;

		$referral_amount = $this->calc_referral_amount($amount, $reference, $referral->referral_id, '',  $referral->affiliate_id);
		
		/**
		 * Fires when the amount of a recurring referral is calculated.
		 *
		 * @param float $referral_amount  The referral amount.
		 * @param int   $affiliate_id     The affiliate ID.
		 * @param float $amount           The full transaction amount.
		 *
		 * @since 1.1.2
		 */
		$referral_amount = (string) apply_filters( 'affwp_recurring_calc_referral_amount', $referral_amount,  $referral->affiliate_id, $amount);

		$args = array(
			'reference'    => $reference,
			'affiliate_id' => $referral->affiliate_id,
			'description'  => sprintf(__('Recurring Referral - WP Ultimo. User ID: %s', 'wp-ultimo'), $user_id),
			'amount'       => $referral_amount,
			'custom'       => $user_id,
		);

		$referral_id = $this->insert_referral($args);

		$this->complete_referral($referral_id);

	}

	/**
	 * Builds the reference link for the referrals table
	 *
	 * @access  public
	 * @since   1.0
	*/
	public function reference_link($link = '', $referral) {

		if (empty( $referral->context ) || 'wp-ultimo' != $referral->context ) {

			return $link;

		}

		if (!empty( $referral->custom)) {
			$url  = network_admin_url('admin.php?page=wu-edit-subscription&user_id=' . $referral->custom );
			$link = '<a href="' . esc_url( $url ) . '">View WP Ultimo Subscription</a>';
		}

		if (!empty($referral->reference) && is_numeric($referral->reference)) {
			$url  = network_admin_url('admin.php?page=wu-edit-subscription&user_id=' . $referral->reference );
			$link = '<a href="' . esc_url( $url ) . '">View WP Ultimo Subscription</a>';
		}

    return $link;
    
	}

}

new AffiliateWP_WP_Ultimo;