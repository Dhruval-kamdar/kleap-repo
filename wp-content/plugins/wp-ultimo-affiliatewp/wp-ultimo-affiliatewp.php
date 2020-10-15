<?php
/**
 * Plugin Name: WP Ultimo: AffiliateWP Integration
 * Description: Use the powerful AffiliateWP to grow the client base of your Ultimo Network!
 * Plugin URI: http://wpultimo.com
 * Text Domain: wp-ultimo
 * Version: 1.1.6
 * Author: Arindo Duque - NextPress, Aron Prins
 * Author URI: http://nextpress.co/
 * Copyright: Arindo Duque, NextPress
 * Network: true
 * License: GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 *
 * WP Ultimo: AffiliateWP Integration is distributed under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * WP Ultimo: AffiliateWP Integration is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with WP Ultimo: AffiliateWP Integration. If not, see <http://www.gnu.org/licenses/>.
 *
 * @author   Arindo Duque
 * @category Addon
 * @package  WP_Ultimo/WP_Ultimo_AffiliateWP
 * @version  1.1.4
 */

if (!defined('ABSPATH')) {

	exit; // Exit if accessed directly.

} // end if;

if (!class_exists('WP_Ultimo_AffiliateWP')) :

	/**
	 * Here starts our plugin.
	 */
	class WP_Ultimo_AffiliateWP {

		/**
		 * Version of the Plugin
		 *
		 * @var string
		 */
		public $version = '1.1.6';

		/**
		 * Makes sure we are only using one instance of the plugin
		 *
		 * @var object WU_Ultimo_AffiliateWP
		 */
		public static $instance;

		/**
		 * Returns the instance of WU_Ultimo_AffiliateWP
		 *
		 * @return object A WU_Ultimo_AffiliateWP instance
		 */
		public static function get_instance() {

			if (null === self::$instance) {
				self::$instance = new self();
			} // end if;

			return self::$instance;

		} // end get_instance;

		/**
		 * Construct.
		 */
		public function __construct() {

			load_plugin_textdomain('wp-ultimo-affiliatewp', false, dirname(plugin_basename(__FILE__)) . '/lang');

			// Bail if no WP
			if (!function_exists('WP_Ultimo')) {

				return;

			} // end if;

			// Updater
			require_once plugin_dir_path(__FILE__) . '/inc/class-wu-addon-updater.php';

			/**
			 * @since 1.2.0 Creates the updater
			 * @var WU_Addon_Updater
			 */
			$updater = new WU_Addon_Updater('wp-ultimo-affiliatewp', __('WP Ultimo: AffiliateWP Integration', 'wp-ultimo-affiliatewp'), __FILE__);

			$this->hooks();

		}  // end __construct;

		/**
		 * Add all the hooks we need on WP Ultimo to correctly track referrals
		 */
		public function hooks() {

			add_filter('affwp_extended_integrations', array($this, 'add_wpultimo_to_integrations'));

			add_filter('affwp_settings_integrations', array($this, 'add_wp_ultimo_on_settings'), 10, 1);

			// Hooks that only get loaded if the WP Ultimo integration is activated
			if (in_array('wp-ultimo', array_keys(affiliate_wp()->settings->get('integrations', array())))) {

				/**
				 * Loads support to Recurring Referrals
			   *
				 * @since 1.2.0
				 */
				require_once plugin_dir_path(__FILE__) . '/inc/class-wu-recurring.php';

				/**
				 * Create the referral tracking for the user after the signup
				 */
				add_action('wp_ultimo_registration', array($this, 'create_tracking'), 10, 3);

				/**
				 * Change the referrals when a payment is received
				 */
				add_action('wp_ultimo_payment_completed', array($this, 'update_on_payment'), 10, 4);

				/**
				 * Change the referrals when a payment is refunded
				 */
				// add_action('wp_ultimo_payment_refunded', array($this, 'update_on_refund'), 10, 3);

				/**
				 * @since  1.0.1 Adds coupon code support to AffiliateWP
				 */
				add_action('wp_ultimo_coupon_advanced_options', array($this, 'coupon_affiliate'));

				add_action('wp_ultimo_coupon_after_save', array($this, 'coupon_affiliate_save'));

				add_action('wp_ultimo_apply_coupon_code', array($this, 'create_tracking_coupon'), 10, 2);

				/**
				 * @since 1.1.1 Adds the tracking scripts to the register screen as well, so we can make sure we registered the visit
				 */
				add_action('wu_before_signup_header', array($this, 'load_tracking_scripts'));

			} // end if;

		} // end hooks;

		/**
		 * Filter settings affiliate integrations adding new option
		 *
		 * @param array $array Settings.
		 *
		 * @return array
		 */
		public function add_wp_ultimo_on_settings($array) {

			$settings_wp = array('wp_ultimo_setup_fee_affwp' => array(
			'name'        => __('WP Ultimo Integration Options', 'wp-ultimo'),
			'title'       => __('Allow commissions to be applied to the Setup Fees', 'wp-ultimo'),
			'desc'        => __('Allow commissions to be applied to the Setup Fees', 'wp-ultimo'),
			'tooltip'     => __('If you enable this option, WP Ultimo commissions will include the Setup Fees as well.', 'wp-ultimo'),
			'type'        => 'checkbox',
			'placeholder' => '',
			'default'     => 0,
			));

			return array_merge($array, $settings_wp);

		} // end add_wp_ultimo_on_settings;

		/**
		 * Adds tracking scripts to the sign-up flow
		 *
		 * @since 1.1.1
		 * @return void
		 */
		public function load_tracking_scripts() {

			if (!WU_Signup()->is_register_page()) {
				return;
			} // end if;

			// Load the scripts for tracking =)
			affiliate_wp()->tracking->header_scripts();
			affiliate_wp()->tracking->load_scripts();

		} // end load_tracking_scripts;

		/**
		 * Check if AffiliateWP is being used as recurring
		 *
		 * @since 1.2.0
		 * @return boolean
		 */
		public function is_recurring() {

			return affiliate_wp()->settings->get('recurring');

		} // end is_recurring;

		/**
		 * Adds the coupon integration form
		 *
		 * @param  WU_Coupon $coupon
		 */
		public function coupon_affiliate($coupon) { ?>

      <div class="options_group">
        <p class="form-field cycles_field">
          <label for="cycles">
            <?php _e('AffiliateWP Affiliate', 'wp-ultimo'); ?>
            <?php echo WU_Util::tooltip(__('Link this coupon to one of your affiliates.', 'wp-ultimo')); ?>
          </label>
          
          <select class="short" name="affiliate" id="affiliate">

            <option><?php _e('Select one Affiliate', 'wp-ultimo-affiliatewp'); ?></option>

            <?php
            foreach (affiliate_wp()->affiliates->get_affiliates() as $aff) :

				$user = get_user_by('id', $aff->user_id);

				if (!$user) {
					continue;
				} // end if;

				?>

              <option value="<?php echo $aff->affiliate_id; ?>" <?php selected($aff->affiliate_id, $coupon->affiliate); ?>>

				<?php printf('%s (User ID: %s)', $user->user_login, $user->ID); ?>

              </option>

            <?php endforeach; ?>

          </select>

        </p>
      </div>

			<?php

		} // end coupon_affiliate;

		/**
		 * Save the Coupon
		 *
		 * @param  WU_Coupon $coupon
		 */
		public function coupon_affiliate_save($coupon) {

			$coupon->meta_fields[] = 'affiliate';

			$coupon->affiliate = $_POST['affiliate'];

			$coupon->save();

		} // end coupon_affiliate_save;

		/**
		 * Add the integration option of WP Ultimo to AffiliateWP List
		 *
		 * @param array $integrations
		 */
		public function add_wpultimo_to_integrations($integrations) {

			/**
			*   @type string $name    Required. The integration display name.
			* 	@type string $class   Required. The integration class name.
			*   @type string $file    Required. The path to the file that contains this integration class.
			*   @type bool   $enabled Optional. True forces this integration to always be enabled.
			*                             False forces it to always be disabled. Defaults to user settings.
			*   @type array $supports Optional. List of features this integration supports. Default empty array.
			*/
			$integrations['wp-ultimo'] = array(
				'name'   => 'WP Ultimo',
				'class'  => plugin_dir_path(__FILE__) . '/wp-ultimo-affiliatewp.php',
				'status' => array( 'enabled', 'disabled' ),
				'fields' => 'WP_Ultimo_AffiliateWP'
			);

			//$integrations['wp-ultimo'] = 'WP Ultimo';

			return $integrations;

		} // end add_wpultimo_to_integrations;

		/**
		 * Create tracking from coupon code
		 *
		 * @since  1.0.1
		 * @param  WU_Coupon       $coupon
		 * @param  WU_Subscription $subscription
		 */
		public function create_tracking_coupon($coupon, $subscription) {

			$user_id = $subscription ? $subscription->user_id : false;

			// Check if this user was tracked already
			$tracked = get_user_meta($user_id, 'wp-ultimo-affiliatewp-tracked', true);

			if ($tracked == false && $subscription && $coupon->affiliate) {

				/**
				 * First of all, we need to track that visit
				 */
				$visit_id = $this->save_visit($coupon->affiliate);

				if (is_wp_error($visit_id)) {
					
					return;
					
				} // end if;

				WU_Logger::add('affiliatewp', "Visit: $visit_id");

				$user = get_user_by('id', $subscription->user_id);

				$affiliate = $coupon->affiliate;
				$desc      = sprintf(__('Referred user %1$s via coupon code %2$s.', 'wp-ultimo-affiliatewp'), $user->display_name, $coupon->title);

				/** Saves the Tracking */
				$result = $this->save_tracking($affiliate, $visit_id, $desc, $subscription);

				WU_Logger::add('affiliatewp', "Tracking: $result");

			} // end if;

			// die;
		} // end create_tracking_coupon;

		/**
		 * Create the conversion with a pending status
		 * The comission will be confirmed once the payment is received
		 *
		 * @param  interger $site_id   The site id created for the new user
		 * @param  interger $user_id   The user id
		 * @param  array    $transient Array contianing the singup information
		 */
		public function create_tracking($site_id, $user_id, $transient) {

			// Check if this user was tracked already
			$tracked = get_user_meta($user_id, 'wp-ultimo-affiliatewp-tracked', true);

			wp_cache_delete($user_id, 'wu_subscription'); // We need to clear the cache

			$subscription = wu_get_site($site_id)->get_subscription();

			if ($tracked == false && $subscription && isset($_COOKIE['affwp_ref'])) {

				$user = get_user_by('id', $user_id);

				$affiliate = $_COOKIE['affwp_ref'];
				$visit     = $_COOKIE['affwp_ref_visit_id'];
				$desc      = sprintf(__('Referred user %s.', 'wp-ultimo-affiliatewp'), $user->display_name);

				/** Saves the Tracking */
				$this->save_tracking($affiliate, $visit, $desc, $subscription);

			} // end if;

		} // end create_tracking;

		/**
		 * Creates the visit on the database, so we can link that to out referral
		 *
		 * @param  interger $affiliate
		 * @return
		 */
		public function save_visit($affiliate) {

			$post_array = array(
				'affiliate' => $affiliate,
				'campaign'  => '',
				'url'       => add_query_arg(array()),
				'referrer'  => add_query_arg(array(), $_SERVER['HTTP_REFERER']),
			);

			$request = wp_remote_post(get_admin_url(1, 'admin-ajax.php?action=affwp_track_visit'), array(
				'method'    => 'POST',
				'sslverify' => false,
				'body'      => $post_array,
			));

			return is_wp_error($request) ? $request : $request['body'];

		} // end save_visit;


		/**
		 * Adds the tracking to the AffiliateWP tracking
		 *
		 * @since  1.0.1
		 * @param  string          $affiliate
		 * @param  string          $visit
		 * @param  WU_Subscription $subscription
		 * @param  WP_User         $user
		 * @return
		 */
		public function save_tracking($affiliate, $visit, $desc, $subscription) {

			// Check for Coupon Codes
			$coupon_code = $subscription->get_coupon_code();

			// Set the correct price
			$price = $coupon_code ? $subscription->get_price_after_coupon_code() : $subscription->price;

			$plan = $subscription->get_plan();

			$setup_fee_value = 0;

			if (affiliate_wp()->settings->get('wp_ultimo_setup_fee_affwp') && $plan && $plan->has_setup_fee() && !$subscription->has_paid_setup_fee()) {

				$setup_fee_value = $plan->get_setup_fee();

			} // end if;

			/**
			 * Set Variables
			 */
			$amount    = (string) ((float) $price) + ((float) $setup_fee_value);
			$reference = (string) $subscription->user_id;
			$status    = 'pending';
			$context   = __('wp-ultimo', 'wp-ultimo-affiliatewp');
			$campaign  = __('WP Ultimo Signup', 'wp-ultimo-affiliatewp');

			$md5 = md5($amount . $desc . $reference . $context . $status . $campaign);

			$post_array = array(
				'affiliate'   => $affiliate,
				'visit_id'    => $visit,
				'amount'      => $amount,
				'status'      => $status,
				'description' => $desc,
				'context'     => $context,
				'reference'   => $reference,
				'campaign'    => $campaign,
				'md5'         => $md5,
			);

			$url = get_admin_url(1, 'admin-ajax.php?action=affwp_track_conversion');

			$request = wp_remote_post($url, array(
				'method'    => 'POST',
				'body'      => $post_array,
				'timeout'   => 300,
				'sslverify' => false,
				'cookies'   => array(
					'affwp_ref_visit_id' => $visit,
				),
			));

			if (!is_wp_error($request)) {

				update_user_meta($subscription->user_id, 'wp-ultimo-affiliatewp-tracked', true);

			} // end if;

			return is_wp_error($request) ? $request : $request['body'];

		} // end save_tracking;

		/**
		 * Update the status of a referral when the payment is received
		 *
		 * @param  integer $user_id User ID
		 * @param  string  $gateway String identifying the gateway being used
		 * @param  string  $amount  Value of the payment received
		 * @param  string  $setup_fee_value  Value of the setup fee received
		 */
		public function update_on_payment($user_id, $gateway, $amount, $setup_fee_value = 0) {

			// Get the referral
			$referral = affiliate_wp()->referrals->get_by('reference', $user_id);

			if (!$referral) {

				affiliate_wp()->utils->log(__('Referral not found', 'wp-ultimo-affiliatewp'));

				return;

			} // end if;

			$referral = affwp_get_referral($referral->referral_id);

			// Check if the status is still pending
			if ($referral->status == 'pending') {

				/**
				 * Set the value to the paid value, to account for upgrades beig paid
				 */

				/**
				 * TODO: Do not update the value of the referral
				 */
				if (affiliate_wp()->settings->get('wp_ultimo_setup_fee_affwp')) {

					$amount = $amount + $setup_fee_value;

				} // end if;

				$ref_amount = affwp_calc_referral_amount($amount, $referral->affiliate_id, $user_id);
				$referral->set('amount', $ref_amount, true);

				$success = affwp_set_referral_status($referral->referral_id, 'unpaid');

				if ($success) {

					affiliate_wp()->utils->log(sprintf(__('Referral status of #%s changed to "Unpaid".', 'wp-ultimo-affiliatewp'), $referral->referral_id));

				} // end if;

			} // end if;

		} // end update_on_payment;

		/**
		 * Update the status of a referral when the payment is received
		 *
		 * @param  interget $user_id User ID
		 * @param  string   $gateway String indentifying the gateway being used
		 * @param  string   $amount  Value of the payment received
		 */
		public function update_on_refund($user_id, $gateway, $amount) {

			if ($amount == 0) {
				return;
			} // end if;

			// Get the referral
			$referral = affiliate_wp()->referrals->get_by('reference', $user_id);

			if (!$referral) {

				affiliate_wp()->utils->log(__('Referral not found', 'wp-ultimo-affiliatewp'));

				return;

			} // end if;

			$referral = affwp_get_referral($referral->referral_id);

			// Check if the status is still pending
			if ($referral->status == 'unpaid' || $referral->status == 'pending') {

				$referral->set('status', 'rejected', true);

				affiliate_wp()->utils->log(sprintf(__('Referral status of #%s changed to "Rejected".', 'wp-ultimo-affiliatewp'), $referral->referral_id));

			} // end if;

		} // end update_on_refund;

	}  // end class WP_Ultimo_AffiliateWP;

	/**
	 * Return a unique instance of our add-on
     *
	 * @return WP_Ultimo_AffiliateWP
	 */
	function wpultimo_affiliatewp() {

		if (!class_exists('WP_Ultimo')) {
			return; // We require WP Ultimo, baby
		} // end if;

		if (class_exists('Affiliate_WP')) {

			return WP_Ultimo_AffiliateWP::get_instance();

		} else {

			WP_Ultimo()->add_message(__('WP Ultimo: AffiliateWP Integration requires <a target="_blank" href="https://affiliatewp.com">AffiliateWP</a> to be activated at least on your main site.', 'wp-ultimo-affiliatewp'), 'warning', true);

		} // end if;

	} // end wpultimo_affiliatewp;

	add_action('plugins_loaded', 'wpultimo_affiliatewp', 200);

endif;
