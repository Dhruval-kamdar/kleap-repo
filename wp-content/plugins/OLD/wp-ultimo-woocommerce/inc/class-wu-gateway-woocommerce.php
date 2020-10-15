<?php
/**
 * WooCommerce Gateway
 *
 * Handles Integration with WooCommerce Payments
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
 * WU_Gateway_WooCommerce
 */
class WU_Gateway_WooCommerce extends WU_Gateway {

  /**
   * Decides if we need to use WooCommerce Subscriptions
   * 
   * @since 1.2.0
   * @var boolean
   */
  private $should_use_woocommerce_subscriptions = false;

  /**
   * Initialize the Gateway key elements
   */
  public function init() {

    $this->should_use_woocommerce_subscriptions = WU_Settings::get_setting('enable_woocommerce_subscription_integration') && wu_is_woocommerce_subscriptions_active();

    // Change the button value
    add_filter('wu_gateway_integration_button_woocommerce', array($this, 'change_woocommerce_button'), 10, 2);

    // Add new status to the list table
    add_filter('wu_subscriptions_status', array($this, 'add_on_hold_status'));

    add_filter('wu_gateway_get_url', array($this, 'redirect_to_cart_on_integration'), 100, 2);

    // Some actions just need to run if we do not use WooCommerce Subscriptions
    if ( ! $this->should_use_woocommerce_subscriptions() ) {

      // Add Cron Job
      add_action('wu_cron', array($this, 'create_orders_for_subscriptions'));

      // When a user just registers, we need to generate the order right way
      add_action('wu_subscription_create_integration', array($this, 'create_order_if_on_hold'), 10, 3);

      // Reset Invoice Sent Status
      add_action('wu_subscription_before_save', array($this, 'reset_invoice_sent_status'));

    } // end if;

    // Add new email template
    add_action('init', array($this, 'register_new_email_template'), 20);

    // Add the action
    add_filter('wu_transaction_item_actions', array($this, 'add_action_mark_paid'), 10, 2);

    // @since 1.2.0
    add_action("wp_ajax_wu_process_marked_as_paid_$this->id", array($this, 'process_mark_as_paid'));

    // Adds the Link
    add_action('load-post.php', array($this, 'add_wp_ultimo_link'));

    // Listens for on complete orders
    add_action('woocommerce_order_status_completed', array($this, 'on_order_completed'), 10, 1);

    // Add the on hold
    add_filter('wu_subscription_on_hold_gateways', array($this, 'add_woocommerce_to_on_hold_gateways'));

    // Changes the title
    add_filter("wu_get_gateway_title_$this->id", array($this, 'get_gateway_title'));

    // Force Completed status for WP Ultimo Orders
    add_filter('woocommerce_payment_complete_order_status', array($this, 'force_completed_status'), 10, 2);

    /** Always allow dashboard access */
    add_filter('woocommerce_prevent_admin_access', array($this, 'always_allow_admin_access'));

    // Hooks for WooCommerce Subscription
    $this->woocommerce_subscription_hooks();

  } // end init;

  /**
   * Allow access to the admin access
   *
   * @since 1.2.0
   * @param bool $access
   * @return bool
   */
  public function always_allow_admin_access($access) {

    if (wp_doing_ajax() || wp_doing_cron()) return $access;

    $has_subscription = wu_get_current_site()->get_subscription();
    $is_super_admin   = current_user_can('manage_network');

    return $has_subscription || $is_super_admin ? false : true;

  } // end always_allow_admin_access;

  /**
   * Checks if we should use WooCommerce Subscriptions
   *
   * @since 1.2.0
   * @return bool
   */
  public function should_use_woocommerce_subscriptions() {

    return $this->should_use_woocommerce_subscriptions;

  } // end should_use_woocommerce_subscriptions;

  /**
   * Add the WooCommerce gateway to the on-hold list
   *
   * @param array $allowed_gateways
   * @return array
   */
  public function add_woocommerce_to_on_hold_gateways($allowed_gateways) {
      
    $allowed_gateways[] = 'woocommerce';
    
    return $allowed_gateways;

  } // end add_woocommerce_to_on_hold_gateways;

  /**
   * Get agteway title from the settings
   *
   * @param string $title
   * @return string
   */
  public function get_gateway_title() {
    
    return WU_Settings::get_setting('woocommerce_title', __('Dynamic Payments', 'wp-ultimo-woocommerce'));

  } // end get_gateway_title;

  /**
   * Force the completed status for WP Ultimo orders
   *
   * @since 1.1.2
   * @param string $status
   * @param integer $order_id
   * @return void
   */
  public function force_completed_status($status, $order_id) {

    $force_completed = get_post_meta($order_id, '_is_wp_ultimo', true);

    if ($force_completed === 'yes') {

      return 'completed';

    } // end if;

    return $status;

  } // end force_completed_status;

  /**
   * Adds the link to the edit order header page
   *
   * @return void
   */
  public function add_wp_ultimo_link() {

    add_action('admin_head', function() {

      $post_id = isset($_GET['post']) ? $_GET['post'] : false;

      if ($post_id && get_post_meta($post_id, '_is_wp_ultimo', true)) {

        printf('<a id="wp-ultimo-link" href="%s" style="display: none;" class="page-title-action" target="_blank">%s</a>', network_admin_url('admin.php?page=wu-edit-subscription&user_id='.get_post_meta($post_id, '_customer_user', true)), __('Go to the Subscription on WP Ultimo', 'wp-ultimo-woocommerce'));

        echo "<script>(function($) {
          $(document).ready(function() {
            $('#wp-ultimo-link').insertAfter('.wp-heading-inline').show();
          });
        })(jQuery);</script>";

      } // end if;

    });

  } // end add_wp_ultimo_link;

  /**
   * Extends subscription when we receive notice that the order was marked completed
   *
   * @param integer $order_id
   * @return void
   */
  public function on_order_completed($order_id) {

    global $wpdb;

    switch_to_blog(get_current_site()->blog_id);

    // Only for WP Ultimo orders
    if (get_post_meta($order_id, '_is_wp_ultimo', true)) {

      $customer_id = get_post_meta($order_id, '_customer_user', true);

      $subscription = wu_get_subscription($customer_id);

      $table_name = WU_Transactions::get_table_name();

      $query = $wpdb->prepare("SELECT * FROM $table_name WHERE user_id = %d AND reference_id = %d", $customer_id, $order_id);

      $transaction = $wpdb->get_row($query);

      if ($subscription && $transaction && $transaction->type == 'pending') {

        $result = WU_Transactions::update_transaction($transaction->id, array(
          'type' => 'payment'
        ));

        /**
         * If update was ok
         */
        if ($result) {

          $plan = $subscription->get_plan();

          /**
           * Add setup fee
           * @since 1.2
           */
          if ($plan->has_setup_fee() && ! $subscription->has_paid_setup_fee()) {

            $setup_fee_desc = sprintf(__('Setup fee for Plan %s', 'wp-ultimo-woocommerce'), $plan->title);
            
            $setup_fee_value = $plan->get_setup_fee();

            /**
             * Add setup fee line
             * @since 1.7.0
             */
            add_filter('wu_subscription_get_invoice_lines', function($lines) use ($setup_fee_desc, $setup_fee_value) {

              $lines[] = array(
                'text'  => $setup_fee_desc,
                'value' => $setup_fee_value,
              );

              return $lines;

            });

          } // end if;

          // Extend Subscription
          $subscription->paid_setup_fee = true;
          $subscription->extend_future();

          // Add transaction in our transactions database
          $message = sprintf(__('Payment for the plan %s - The account will now be active until %s', 'wp-ultimo-woocommerce'), $plan->title, $subscription->get_date('active_until'));

          // Add transaction in our transactions database
          $message = sprintf(__('Payment for the plan %s - The account will now be active until %s', 'wp-ultimo-woocommerce'), $plan->title, $subscription->get_date('active_until'));

          /**
           * @since  1.2.0 Send the invoice as an attachment
           */
          $invoice     = $this->generate_invoice($this->id, $subscription, $message, $subscription->get_price_after_coupon_code());
          $attachments = $invoice ? array($invoice) : array();

          // Send receipt Mail
          WU_Mail()->send_template('payment_receipt', $subscription->get_user_data('user_email'), array(
            'amount'           => wu_format_currency($subscription->get_price_after_coupon_code()),
            'date'             => date(get_option('date_format')),
            'gateway'          => $this->get_title(),
            'new_active_until' => $subscription->get_date('active_until'),
            'user_name'        => $subscription->get_user_data('display_name')
          ), $attachments);

          WU_Logger::add('gateway-'.$this->id, sprintf(__('User ID: %s - WooCommerce Payment: %s %s payment received, transaction ID %s', 'wp-ultimo-woocommerce'), $transaction->user_id, $transaction->reference_id, wu_format_currency( $subscription->get_price_after_coupon_code() ), $transaction->reference_id));

          /**
           * @since  1.1.2 Hooks for payments and integrations
           */
          do_action('wp_ultimo_payment_completed', $transaction->user_id, $this->id, $subscription->get_price_after_coupon_code());

        } // end if;

      } // end if;

    } // end if;

    restore_current_blog();

  } // end on_order_completed;

  /**
   * Process the action of Marking a payment as received
   * @return
   */
  public function process_mark_as_paid() {

    if (!current_user_can('manage_network')) {

      die(json_encode(array(
        'status'  => false,
        'message' => __('You don\'t have permissions to perform that action.', 'wp-ultimo-woocommerce'),
      )));

    }

    // Get Transaction ID
    if (!isset($_GET['transaction_id'])) {

       die(json_encode(array(
         'status'  => false,
         'message' => __('A valid transaction id is necessary.', 'wp-ultimo-woocommerce'),
       )));

    }

    $transaction_id = $_GET['transaction_id'];
    $transaction = WU_Transactions::get_transaction($transaction_id);

    if (!$transaction) {

       die(json_encode(array(
         'status'  => false,
         'message' => __('Transaction not found.', 'wp-ultimo-woocommerce'),
       )));

    }

    $subscription = wu_get_subscription($transaction->user_id);

    if (!$subscription) {

       die(json_encode(array(
         'status'  => false,
         'message' => __('Subscription not found.', 'wp-ultimo-woocommerce'),
       )));

    }

    /**
     * Update WooCommerce order
     */
    $order = wc_get_order($transaction->reference_id);

    if (!$order) {

      die(json_encode(array(
        'status'  => false,
        'message' => __('WooCommerce order not found.', 'wp-ultimo-woocommerce'),
      )));

    }

    // Set the new status
    $order->update_status('completed', __('Order set as completed by WP Ultimo.', 'wp-ultimo-woocommerce'), true);

    // Update the Transaction?
    $result = WU_Transactions::update_transaction($transaction_id, array(
      'type' => 'payment'
    ));

    /**
     * If update was ok
     */
    if ($result !== false) {

      $plan = $subscription->get_plan();

      /**
       * Add setup fee
       * @since 1.2
       */
      if ($plan->has_setup_fee() && ! $subscription->has_paid_setup_fee()) {

        $setup_fee_desc = sprintf(__('Setup fee for Plan %s', 'wp-ultimo-woocommerce'), $plan->title);
        
        $setup_fee_value = $plan->get_setup_fee();

        /**
         * Add setup fee line
         * @since 1.7.0
         */
        add_filter('wu_subscription_get_invoice_lines', function($lines) use ($setup_fee_desc, $setup_fee_value) {

          $lines[] = array(
            'text'  => $setup_fee_desc,
            'value' => $setup_fee_value,
          );

          return $lines;

        });

      } // end if;

      // Extend Subscription
      $subscription->paid_setup_fee = true;
      $subscription->extend_future();

      // Add transaction in our transactions database
      $message = sprintf(__('Payment for the plan %s - The account will now be active until %s', 'wp-ultimo-woocommerce'), $plan->title, $subscription->get_date('active_until'));

      /**
       * @since  1.2.0 Send the invoice as an attachment
       */
      $invoice     = $this->generate_invoice($this->id, $subscription, $message, $subscription->get_price_after_coupon_code());
      $attachments = $invoice ? array($invoice) : array();

      // Send receipt Mail
      WU_Mail()->send_template('payment_receipt', $subscription->get_user_data('user_email'), array(
        'amount'           => wu_format_currency($subscription->get_price_after_coupon_code()),
        'date'             => date(get_option('date_format')),
        'gateway'          => $this->get_title(),
        'new_active_until' => $subscription->get_date('active_until'),
        'user_name'        => $subscription->get_user_data('display_name')
      ), $attachments);

      WU_Logger::add('gateway-'.$this->id, sprintf(__('User ID: %s - Manual Payment: %s %s payment received, transaction ID %s', 'wp-ultimo-woocommerce'), $transaction->user_id, $transaction->reference_id, wu_format_currency( $subscription->get_price_after_coupon_code() ), $transaction->reference_id));

      /**
       * @since  1.1.2 Hooks for payments and integrations
       */
      do_action('wp_ultimo_payment_completed', $transaction->user_id, $this->id, $subscription->get_price_after_coupon_code());

      /**
       * Display the Results
       */

      $active_until = new DateTime($subscription->active_until);

      die(json_encode(array(
         'status'  => true,
         'message' => __('Transaction updated, sucessfully.', 'wp-ultimo-woocommerce'),
         'remaining_string' => $subscription->get_active_until_string(),
         'status'           => $subscription->get_status(),
         'status_label'     => $subscription->get_status_label(),
         'active_until'     => $subscription->get_date('active_until', get_blog_option(1, 'date_format') . ' @ H:i' ),
      )));

    } else {

      die(json_encode(array(
         'status'  => false,
         'message' => __('An error occurred trying to update this transaction.', 'wp-ultimo-woocommerce'),
      )));

    } // end if;

  } // end mark_as_paid;

  public function get_payment_link($order_id) {

    switch_to_blog(get_current_site()->blog_id);

      $this->load_woocommerce_dependencies();

      $pay_url = wc_get_endpoint_url(get_option('woocommerce_checkout_pay_endpoint', true), $order_id, wc_get_page_permalink('checkout'));

      if ('yes' == get_option('woocommerce_force_ssl_checkout') || is_ssl()) {

        $pay_url = str_replace('http:', 'https:', $pay_url);

      } // end if;

      $order_key = get_post_meta($order_id, '_order_key', true);

      $pay_url = add_query_arg(array('pay_for_order' => 'true', 'key' => $order_key), $pay_url);

    restore_current_blog();

    return apply_filters('woocommerce_get_checkout_payment_url', $pay_url, wc_get_order($order_id));

  } // end get_payment_link;

  /**
   * Mark the payment as paid
   * @param array $actions
   * @param WU_Transaction $transaction
   */
  public function add_action_mark_paid($actions, $transaction) {

    if ($transaction->type == 'pending' && $transaction->gateway == 'woocommerce' && current_user_can('manage_network')) {

      $actions['paid'] = sprintf('<a href="#" data-transaction="%s" data-gateway="%s" data-subscription="%s" class="wu-paid-trigger" data-text="%s" aria-label="%s">%s</a>', $transaction->id, $transaction->gateway, $transaction->user_id, __('If you have received this payment, use this option to confirm the payment. The user will receive a new invoice marked as paid and an email confirming the payment. Marking this as Paid here will not mark the correspondent order on WooCommerce as completed.', 'wp-ultimo-woocommerce'), __('Mark as Paid', 'wp-ultimo-woocommerce'), __('Mark as Paid', 'wp-ultimo-woocommerce'));

    } // end if;

    switch_to_blog(get_current_site()->blog_id);

    if ($transaction->gateway == 'woocommerce' && current_user_can('manage_network')) {

        if (get_post($transaction->reference_id)) {

          $actions['see-order'] = sprintf('<a href="%s" target="_blank" aria-label="%s">%s</a>', admin_url('post.php?action=edit&post=' . $transaction->reference_id) , __('See Order on WooCommerce', 'wp-ultimo-woocommerce'), __('See Order on WooCommerce', 'wp-ultimo-woocommerce'));

        }

    } else if ($transaction->type == 'pending' && $transaction->gateway == 'woocommerce') {

        if (get_post($transaction->reference_id)) {

          $order_link = $this->get_payment_link($transaction->reference_id);

          $actions['pay'] = sprintf('<a href="%s" target="_blank" aria-label="%s">%s</a>', $order_link, __('Pay', 'wp-ultimo-woocommerce'), __('Pay', 'wp-ultimo-woocommerce'));

        } // end if;

      } // end if;

    restore_current_blog();

    return $actions;

  } // end add_action_mark_paid;

  /**
   * Register the email template we are going to use
   * @return
   */
  public function register_new_email_template() {

    /**
     * Payment Invoice Sent
     */
    WU_Mail()->register_template('payment_invoice_woocommerce_sent', array(
      'admin'      => false,
      'name'       => __('Payment Invoice', 'wp-ultimo-woocommerce'),
      'subject'    => __('Invoice for Subscription on {{site_name}}', 'wp-ultimo-woocommerce'),

      'content'    => __("Hi, {{user_name}}. <br><br>
    Here is the invoice for your subscription on {{site_name}}. The invoice is due on {{due_date}}. You can use the button below to pay it. Let us know if you have any questions.<br><br>
    <a href='{{payment_link}}'>Click here to Pay</a>
    ", 'wp-ultimo-woocommerce'),

      'shortcodes' => array(
        'user_name',
        'amount',
        'date',
        'gateway',
        'due_date',
        'payment_link',
      )
    ));

  } // end register_new_email_template;

  /**
   * Generate the slug for the product plan
   *
   * @param string $plan_title
   * @param float $plan_price
   * @param integer $plan_freq
   * @return void
   */
  public function get_plan_product_slug($plan_title, $plan_price, $plan_freq) {

    return sprintf('%s-%s-%s', $plan_title, $plan_price, $plan_freq);

  } // end create_plan_product_slug;

  /**
   * Requires WooCommerce if we don't have it installed yet
   * @return void
   */
  public function load_woocommerce_dependencies() {

    $plugins_dir = WP_Ultimo_WC()->plugins_path;

    if (!class_exists('WooCommerce')) {

      require_once($plugins_dir.'/woocommerce/woocommerce.php');

      /**
       * Overloadings
       */
      WC()->countries =  new WC_Countries();

    } // end if;

    if (!class_exists('WC_Subscriptions') && $this->should_use_woocommerce_subscriptions()) {

      require_once($plugins_dir.'/woocommerce-subscriptions/woocommerce-subscriptions.php');

      WC_Subscriptions::load_dependant_classes();
      WC_Subscriptions::attach_dependant_hooks();

    } // end if;

  } // end load_woocommerce_dependencies;

  /**
   * Creates the order and returns it with the Plan product
   *
   * @param WU_Subscription $subscription
   * @return WC_Order
   */
  public function create_order($subscription) {

    /** Check for the requirements */
    $this->load_woocommerce_dependencies();

    global $woocommerce;

    if (!$subscription) {
      return;
    }

    $plan = $subscription->get_plan();

    if (!$plan) {
      return;
    }

    $user = $subscription->get_user();

    $address = array(
      'first_name' => $user->first_name,
      'last_name'  => $user->last_name,
      'email'      => $user->user_email,
    );

    /**
     * Let's generate the WooCommerce product
     */
    $product = new WC_Product();

    $product_slug  = $this->get_plan_product_slug($plan->title, $subscription->price, $subscription->freq);
    $product_price = $subscription->get_price();

    $product_name  = sprintf('%s %s', __('Plan', 'wp-ultimo-woocommerce'), $plan->title) . ' - ' . sprintf(__('%s, paid every %s month(s).', 'wp-ultimo-woocommerce'), wu_format_currency($product_price), $subscription->freq);

    $product->set_props(array(
      'name'               => $product_name,
      // 'description'        => $product_desc,
      'price'              => $product_price,
      'regular_price'      => $product_price,
      'catalog_visibility' => 'hidden',
      'wu_slug'            => $product_slug,
      'virtual'            => 'yes',
      'downloadable'       => 'yes',
    ));

    switch_to_blog(get_current_site()->blog_id);

    // Now we create the order
    $order = wc_create_order(array(
      'customer_id'   => (int) $subscription->user_id,
      'created_via'   => 'WP Ultimo',
    ));

    // Add the product
    $order->add_product($product, 1); // This is an existing SIMPLE product

    if ($subscription->get_coupon_code()) {

      $order->add_fee((object) array(
        'name'      => $subscription->get_coupon_code_string(),
        'amount'    => -($product_price - $subscription->get_price_after_coupon_code()),
        'taxable'   => true,
        'tax'       => 0,
        'tax_data'  => array(),
        'tax_class' => ''
      ));

    } // end if;

    if ($plan->has_setup_fee() && ! $subscription->has_paid_setup_fee()) {

      $setup_fee_desc = sprintf(__('Setup fee for Plan %s', 'wp-ultimo-woocommerce'), $plan->title);
      
      $setup_fee_value = $plan->get_setup_fee();

      $order->add_fee((object) array(
        'name'      => $setup_fee_desc,
        'amount'    => $setup_fee_value,
        'taxable'   => true,
        'tax'       => 0,
        'tax_data'  => array(),
        'tax_class' => ''
      ));

    } // end if;

    // Add additional fees
    $order = apply_filters('wu_woocommerce_add_fees_to_order', $order, $subscription);

    // Add a simple note to let the admin know that this is a automatic note generate by WP Ultimo
    $order->add_order_note(__('Order created by WP Ultimo', 'wp-ultimo-woocommerce'));

    // Set this as a WP Ultimo Order
    update_post_meta($order->get_id(), '_is_wp_ultimo', 'yes');

    // Set totals
    $order->calculate_totals();

    restore_current_blog();

    return $order;

  } // end create_order;

  /**
   * Create WooCommerce Discount
   */
  public function create_woocommerce_fee() { } // end create_woocommerce_fee;

  /**
   * Creates the subscription for the subscription
   *
   * @param WU_Subscription $subscription
   * @return void
   */
  public function create_order_for_subscription($subscription, $price = false, $completed = false, $reduced_amount = false) {

    switch_to_blog(get_current_site()->blog_id);

    /**
     * Log the Transactions
     */
    // Add transaction in our transactions database
    $plan = $subscription->get_plan();

    /**
     * Generate the woocommerce order
     */
    $order = $this->create_order($subscription);

    $total = $order->calculate_totals();

    if ($order) {

      // Send receipt Mail
      WU_Mail()->send_template('payment_invoice_woocommerce_sent', $subscription->get_user_data('user_email'), array(
        'amount'           => wu_format_currency( $total ),
        'date'             => date(get_option('date_format')),
        'gateway'          => $this->get_title(),
        'due_date'         => $subscription->get_date('due_date'),
        'user_name'        => $subscription->get_user_data('display_name'),
        'payment_link'     => $order->get_checkout_payment_url(),
      ));

      // Mark as sent
      $meta                = $subscription->meta;
      $meta->order_created = true;
      $subscription->meta  = $meta;

      $subscription->save();

      /**
       * Log Things
       */
      $message = sprintf(__('Payment for the plan %s using %s as the processor.', 'wp-ultimo-woocommerce'), $plan->title, $this->get_gateway_title());

      $reduced_amount = $reduced_amount ?: $total;

      // Log Transaction and the results
      WU_Transactions::add_transaction($subscription->user_id, $order->get_id(), 'pending', $total, $this->id, $message, false, $reduced_amount);

    } // end if;

    if ($completed) {

      $order->update_status('completed', __('WP Ultimo Order paid automatically due to user credit.', 'wp-ultimo-woocommerce'));

    } // end if;

    restore_current_blog();

    return $order;

  } // end create_order_for_subscription;

  /**
   * Send invoice for that particular subscription
   * @return
   */
  public function create_orders_for_subscriptions() {

    // Get List
    $subscriptions = WU_Subscription::get_subscriptions('on-hold', false, false);

    foreach($subscriptions as $sub) {

      $subscription = wu_get_subscription($sub->user_id);

      // Check if on hold
      if (!$subscription->is_on_hold()) return;

      // If we already sent it, we don't need to send it again
      if ((isset($subscription->meta->order_created) && $subscription->meta->order_created) || $subscription->gateway != $this->id) {
        
        return;

      } // end if;

      // Handles
      return $this->create_order_for_subscription($subscription);

    } // end foreach;

  } // end create_orders_for_subscriptions;

  /**
   * Checks if the order is on hold right after the user signs-up
   *
   * @param WU_Subscription $subscription
   * @return void
   */
  public function create_order_if_on_hold($subscription) {

    // Check if on hold
    if (!$subscription->is_on_hold()) return;

    // If we already sent it, we don't need to send it again
    if ( (isset($subscription->meta->order_created) && $subscription->meta->order_created) || $subscription->gateway != $this->id) return;

    // Handles
    $this->create_order_for_subscription($subscription);

  } // end create_order_if_on_hold;

  /**
   * Un mark this subscription removing the sent invoice flag from it
   * @param  array $subscription Array data to be saved
   * @return array
   */
  public function reset_invoice_sent_status($subscription) {

    $sub = wu_get_subscription($subscription['user_id']);

    if ($sub && is_a($sub, 'WU_Subscription')) {

      $sub->active_until = $subscription['active_until'];

      if (!$sub->is_on_hold()) {

        $meta                        = (object) unserialize($subscription['meta_object']);
        $meta->order_created         = false;
        $subscription['meta_object'] = serialize($meta);

      } // end if;

    } // end if;

    return $subscription;

  } // end reset_invoice_sent_status;

  /**
   * Add on hold status to subscription tables
   * @param array $status All the current status
   */
  public function add_on_hold_status($status) {

    $gateways = is_array(WU_Settings::get_setting('active_gateway')) ? WU_Settings::get_setting('active_gateway') : array();

    if (!in_array($this->id, array_keys($gateways))) return $status;

    $status['on-hold'] = __('On Hold', 'wp-ultimo-woocommerce');

    return $status;

  } // end add_on_hold_status;

  /**
   * Change the label of the button
   * @param  string $button HTML of the button
   * @return string
   */
  public function change_woocommerce_button($button, $content) {

    $label   = WU_Settings::get_setting('woocommerce_button_label') ?: __('Use Dynamic Payments', 'wp-ultimo-woocommerce');

    $tooltip = WU_Settings::get_setting('woocommerce_button_tooltip');

    if ($tooltip === false) {
      $tooltip = __('By choosing dynamic payments, you will receive an invoice every billing period with a link for a payment form. You\'ll be able to use different payment options. Once that payment is confirmed by the payment processor chosen, your subscription will be renewed.');
    }

    return str_replace($content, $label . WU_Util::tooltip($tooltip), $button);

  } // end change_woocommerce_button;

  /**
   * Process Refund
   * @return null
   */
  public function process_refund() {

    if (!current_user_can('manage_network')) {

      die(json_encode(array(
        'status'  => false,
        'message' => __('You don\'t have permissions to perform that action.', 'wp-ultimo-woocommerce'),
      )));

    }

    // Get Transaction ID
    if (!isset($_GET['transaction_id'])) {

       die(json_encode(array(
         'status'  => false,
         'message' => __('A valid transaction id is necessary.', 'wp-ultimo-woocommerce'),
       )));

    }

    if (!isset($_GET['value']) || !is_numeric($_GET['value'])) {

       die(json_encode(array(
         'status'  => false,
         'message' => __('A valid amount is necessary.', 'wp-ultimo-woocommerce'),
       )));

    }

    $transaction_id = $_GET['transaction_id'];
    $transaction = WU_Transactions::get_transaction($transaction_id);

    if (!$transaction) {

       die(json_encode(array(
         'status'  => false,
         'message' => __('Transaction not found.', 'wp-ultimo-woocommerce'),
       )));

    }

    $subscription = wu_get_subscription($transaction->user_id);

    if (!$subscription) {

       die(json_encode(array(
         'status'  => false,
         'message' => __('Subscription not found.', 'wp-ultimo-woocommerce'),
       )));

    }

    /**
     * Everything Worked
     */

     /**
      * Issue woocommerce refund
      */

    $value = $_GET['value'];
    $refund = $this->refund_woocommerce_order($transaction->reference_id, $value);

    if (!is_wp_error($refund)) {

      WU_Logger::add('gateway-'.$this->id, sprintf(__('User ID: %s - WooCommerce Payment "%s" received: You refunded the payment with %s.', 'wp-ultimo-woocommerce'), $transaction->user_id, $transaction->reference_id, wu_format_currency($value)) . $transaction->reference_id);

      $message = sprintf(__('A refund was issued to your account. Payment reference %s.', 'wp-ultimo-woocommerce'), $transaction->reference_id);

      // Log Transaction and the results
      WU_Transactions::add_transaction($transaction->user_id, $transaction->reference_id, 'refund', $value, $this->id, $message);

      // Send refund Mail
      WU_Mail()->send_template('refund_issued', $subscription->get_user_data('user_email'), array(
        'amount'           => wu_format_currency($value),
        'date'             => date(get_option('date_format')),
        'gateway'          => $this->get_title(),
        'new_active_until' => $subscription->get_date('active_until'),
        'user_name'        => $subscription->get_user_data('display_name')
      ));

      /**
       * @since  1.1.2 Hooks for payments and integrations
       */
      do_action('wp_ultimo_payment_refunded', $transaction->user_id, $this->id, $value);

      die(json_encode(array(
          'status'  => true,
          'message' => __('Refund issued successfully. It should appear on this panel shortly.', 'wp-ultimo-woocommerce'),
      )));

    } else {

      die(json_encode(array(
        'status'  => false,
        'message' => sprintf(__('We were not able to refund the WooCommerce Order: %s', 'wp-ultimo-woocommerce'), $refund->get_error_message()),
      )));

    } // end if;

  } // end process_refund;

  /**
   * Refunds a WooCommerce Order
   *
   * @return void
   */
  public function refund_woocommerce_order($order_id, $amount, $refund_reason = '') {

    $order = wc_get_order($order_id);

    // If it's something else such as a WC_Order_Refund, we don't want that.
    if (!is_a($order, 'WC_Order')) {
      return new WP_Error('wc-order', __( 'Provided ID is not a WC Order', 'wp-ultimo-woocommerce'));
    }

    if ('refunded' == $order->get_status()) {
      return new WP_Error('wc-order', __( 'Order has been already refunded', 'wp-ultimo-woocommerce'));
    }

    // Get Items
    $order_items   = $order->get_items();

    // Refund Amount
    $refund_amount = 0;

    // Prepare line items which we are refunding
    $line_items = array();

    if ($order_items) {

      foreach($order_items as $item_id => $item) {

        $tax_data = $item_meta['_line_tax_data'];

        $refund_tax = 0;

        if (is_array($tax_data[0])) {
          $refund_tax = array_map( 'wc_format_decimal', $tax_data[0] );
        }

        $refund_amount = wc_format_decimal($refund_amount) + wc_format_decimal($item_meta['_line_total'][0]);

        $line_items[$item_id] = array(
          'qty'          => $item_meta['_qty'][0],
          'refund_total' => wc_format_decimal($item_meta['_line_total'][0]),
          'refund_tax'   =>  $refund_tax
        );

      } // end foreach;

    } // end if;

    // Check for partial Refund
    if ($amount == $refund_amount) {

      $final_refund_amount = $refund_amount;
      $line_items          = $line_items;

    } else {

      $final_refund_amount = $amount;
      $line_items          = array();

    }

    $refund = wc_create_refund(array(
      'amount'         => $final_refund_amount,
      'reason'         => $refund_reason,
      'order_id'       => $order_id,
      'line_items'     => $line_items,
      'refund_payment' => true
    ));

    return $refund;

  } // end refund woocommerce_order;

  /**
   * First step of the payment flow: proccess_form
   */
  public function process_integration() {

    /**
     * Now we get the costumer ID to be saved in the integration
     */
    $this->create_integration($this->subscription, $this->plan, $this->freq, '', array());

    if ((!isset($this->subscription->meta->order_created) || !$subscription->meta->order_created)) {

      // Handles
      $this->create_order_for_subscription($this->subscription);

    } // end if;

    // Redirect and mark as success
    wp_redirect(WU_Gateway::get_url('success'));

    exit;

  } // end process_integration;

  /**
   * Do upgrade or downgrade of plans
   */
  public function change_plan() {

    // Just return in the wrong pages
    if (!isset($_POST['wu_action']) || $_POST['wu_action'] !== 'wu_change_plan') return;

    // Security check
    if (!wp_verify_nonce($_POST['_wpnonce'], 'wu-change-plan')) {
      WP_Ultimo()->add_message(__('You don\'t have permissions to perform this action.', 'wp-ultimo-woocommerce'), 'error');
      return;
    }

    if (!isset($_POST['plan_id'])) {
      WP_Ultimo()->add_message(__('You need to select a valid plan to change to.', 'wp-ultimo-woocommerce'), 'error');
      return;
    }

    // Check frequency
    if (!isset($_POST['plan_freq']) || !$this->check_frequency($_POST['plan_freq'])) {
      WP_Ultimo()->add_message(__('You need to select a valid frequency to change to.', 'wp-ultimo-woocommerce'), 'error');
      return;
    }

    // Get Plans - Current and new one
    $current_plan = $this->plan;
    $new_plan     = new WU_Plan((int) $_POST['plan_id']);

    $new_price = $new_plan->{"price_".$_POST['plan_freq']};
    $new_freq  = (int) $_POST['plan_freq'];

    if (!$new_plan->id) {
      WP_Ultimo()->add_message(__('You need to select a valid plan to change to.', 'wp-ultimo-woocommerce'), 'error');
      return;
    }

    /**
     * Allow us to hijack the process if woocommerce subscription is enabled
     * 
     * @since 1.2.0
     * @param WU_Subscription
     * @param WU_Plan
     * @param WU_Plan
     */
    do_action('wu_woocommerce_change_plan', $this->subscription, $current_plan, $new_plan, $new_freq);

    /**
     * Refund the last transaction and create a new one
     * @since 1.4.4
     */
    $credit = $this->subscription->calculate_credit();

    $this->subscription->set_credit($credit);

    // We need to take the current subscription time out
    $this->subscription->withdraw();

    /**
     * Now we have the new plan and the new frequency
     */
    // Case: new plan if free
    if ($new_plan->free) {

      // Set new plan
      $this->subscription->plan_id            = $new_plan->id;
      $this->subscription->freq               = 1;
      $this->subscription->price              = 0;
      $this->subscription->integration_status = false;

      $this->subscription->set_last_plan_change();
      $this->subscription->save();

      $this->subscription->extend();

      // Hooks, passing new plan
      do_action('wu_subscription_gateway_change_plan', $this->subscription, $new_plan, $current_plan);

      // Redirect to success page
      wp_redirect(WU_Gateway::get_url('plan-changed'));

      exit;

    } // end if free;

    // Update our subscription object now
    $this->subscription->plan_id            = $new_plan->id;
    $this->subscription->freq               = $new_freq;
    $this->subscription->price              = $new_price;
    $this->subscription->integration_status = true;

    $this->subscription->set_last_plan_change();
    $this->subscription->save();

    // Calculate
    $price_to_pay_now = $this->subscription->get_outstanding_amount();

    /**
     * Adding lines to the Order
     */
    foreach($this->subscription->get_invoice_lines() as $line) {

      /**
       * Add the discount value
       */
      add_filter('wu_woocommerce_add_fees_to_order', function($order, $subscription) use ($line) {

        $order->add_fee((object) array(
          'name'      => $line['text'],
          'amount'    => $line['value'],
          'taxable'   => true,
          'tax'       => 0,
          'tax_data'  => array(),
          'tax_class' => ''
        ));

        return $order;

      }, 10, 2);

    } // end foreach;

    /**
     * Sets the credit if the content is negative; and sets the price to zero.
     */
    if ($price_to_pay_now > 0) {

      $transaction_type = 'pending';

      $price = $price_to_pay_now;

      $paid = false;

      $this->subscription->set_credit(0);

      // Create subscription
      $order = $this->create_order_for_subscription($this->subscription);

    } else {

      $transaction_type = 'payment';

      $price = 0;

      $paid = true;

      $this->subscription->set_credit(abs($price_to_pay_now));

      // Create subscription
      $order = $this->create_order_for_subscription($this->subscription, $price, true, $this->subscription->get_price_after_coupon_code());

      // We don't need to extend, because it will happen automatically.

    } // end if;

    // Hooks, passing new plan
    do_action('wu_subscription_change_plan', $this->subscription, $new_plan, $current_plan);

    // Redirect to success page
    wp_redirect(WU_Gateway::get_url('plan-changed'));

    exit;

  } // end change_plan;

  /**
   * Remove the Stripe integration
   */
  public function remove_integration($redirect = true, $subscription = false) {

    // Get the subscription
    if (!$subscription) {

      $subscription = $this->subscription;

    } // end if;

    if ($subscription) {

      // Finally we remove the integration from our database
      $subscription->meta->subscription_id = '';
      $subscription->integration_status    = false;
      $subscription->save();

    }

    if ($redirect) {

      // Redirect and mark as success
      wp_redirect(WU_Gateway::get_url('integration-removed'));

      exit;

    } // end if;

  } // end remove_integration;

  /**
   * Handles the notifications
   */
  public function handle_notifications() {} // end handle_notifications;

  /**
   * Maybe we add the WooCommerce Subscription enable option
   *
   * @since 1.2.0
   * @return array
   */
  public function get_woocommerce_subscription_settings() {

	$blog_id = get_current_blog_id();
	
	if ( ! wu_is_woocommerce_subscriptions_active()) return array();
	
	$subscriptionsUrl = get_option( 'wc_subscriptions_siteurl' );
	
	$blog_url = get_option( 'siteurl' );
	
	if(strpos($blog_url, 'https')){
		$newUrl = str_replace('https://', 'https://_[wc_subscriptions_siteurl]_', $blog_url);
	}else{
		$newUrl = str_replace('http://', 'http://_[wc_subscriptions_siteurl]_', $blog_url);
	}
	
	if($subscriptionsUrl != $newUrl){
		update_option( 'wc_subscriptions_siteurl', $newUrl , $blog_id );
	}

    return array(
      'enable_woocommerce_subscription_integration' => array(
        'title'         => __('Enable WooCommerce Subscription Integration', 'wp-ultimo-woocommerce'),
        'desc'          => __('It seems that you have WooCommerce Subscriptions activated on your install. WP Ultimo: WooCommerce Integration can also integrate with WooCommerce Subscriptions. If you enable this option, WP Ultimo will use WooCommerce Subscriptions to handle billing instead of using plain WooCommerce Orders.', 'wp-ultimo-woocommerce'),
        'tooltip'       => '',
        'type'          => 'checkbox',
        'default'       => 0,
        'require'       => array('active_gateway[woocommerce]' => true),
      ),
    );

  } // end get_woocommerce_subscription_settings;

  /**
   * Creates the custom fields need to our Gateway
   * @return array Setting fields
   */
  public function settings() {

    $woocommerce_subscription_settings = $this->get_woocommerce_subscription_settings();

    $default_settings_requirements = array('active_gateway[woocommerce]' => true);

    // Defines this gateway settings field
    $default_settings = array(

      'woocommerce_title' => array(
        'title'                       => __('Integration Name', 'wp-ultimo-woocommerce'),
        'desc'                        => __('Select a display name to this particular Integration. The name you choose will be use when this option is presented to the user.', 'wp-ultimo-woocommerce'),
        'type'                        => 'text',
        'placeholder'                 => __('Dynamic Payments', 'wp-ultimo-woocommerce'),
        'default'                     => __('Dynamic Payments', 'wp-ultimo-woocommerce'),
        'require'                     => $default_settings_requirements,
      ),

      'woocommerce_button_label' => array(
        'title'                       => __('Button Label', 'wp-ultimo-woocommerce'),
        'desc'                        => __('Select the label to be used on the Integration button for this option.', 'wp-ultimo-woocommerce'),
        'type'                        => 'text',
        'placeholder'                 => __('Use Dynamic Payments', 'wp-ultimo-woocommerce'),
        'default'                     => __('Use Dynamic Payments', 'wp-ultimo-woocommerce'),
        'require'                     => $default_settings_requirements,
      ),

      'woocommerce_button_tooltip' => array(
        'title'                       => __('Button Tooltip', 'wp-ultimo-woocommerce'),
        'desc'                        => __('Select the tooltip to be used on the Integration button for this option. Leave blank to hide the tooltip icon.', 'wp-ultimo-woocommerce'),
        'type'                        => 'textarea',
        'placeholder'                 => __('By choosing dynamic payments, you will receive an invoice every billing period with a link for a payment form. You\'ll be able to use different payment options. Once that payment is confirmed by the payment processor chosen, your subscription will be renewed.'),
        'default'                     => __('By choosing dynamic payments, you will receive an invoice every billing period with a link for a payment form. You\'ll be able to use different payment options. Once that payment is confirmed by the payment processor chosen, your subscription will be renewed.'),
        'require'                     => $default_settings_requirements,
      ),

      'woocommerce_waiting_days' => array(
        'title'                       => __('Waiting Days', 'wp-ultimo-woocommerce'),
        'desc'                        => __('After the subscription expires, how long should the system wait for the user to pay using one of the active WooCommerce Gateways?', 'wp-ultimo-woocommerce'),
        'tooltip'                     => '',
        'type'                        => 'number',
        'placeholder'                 => 'e.g. 5',
        'default'                     => 5,
        'require'                     => $default_settings_requirements,
      ),

      'woocommerce_redirect_automatically' => array(
        'title'         => __('Auto-Redirect to Payment', 'wp-ultimo'),
        'desc'          => __('Auto-redirect to the Checkout Page after WooCommerce is selected as the Payment Method.', 'wp-ultimo'),
        'tooltip'       => '',
        'type'          => 'checkbox',
        'default'       => 0,
      ),

    );

    return array_merge($woocommerce_subscription_settings, $default_settings);

  } // end settings;

  /**
   * Add Handlers for the WooCommerce Subscription
   */
   public function woocommerce_subscription_hooks() {

    if ( ! $this->should_use_woocommerce_subscriptions()) return;

    add_action('woocommerce_checkout_subscription_created', array($this, 'process_subscription_created'), 100, 3);
    
    add_filter('wcs_renewal_order_created', array($this, 'create_transaction_on_renew'), 10, 2 );
    
    add_action('wu_cancel_woocommerce_subscription', array($this, 'cancel_woocommerce_subscription_on_integration_removal'));
    
    add_action('wu_woocommerce_change_plan', array($this, 'change_plan_woocommerce_subscription'), 10, 4);

    add_filter('woocommerce_thankyou_order_received_text', array($this, 'add_link_to_back_end'), 999, 2);
    
    // add_action('woocommerce_payment_successful_result', array($this, 'redirect_to_account_page_after_checkout'), 100, 2);

    // add_action('init', function() {

    //   if (isset($_GET['CP'])) {

    //     $this->change_plan_woocommerce_subscription( wu_get_subscription(36), wu_get_plan(51), wu_get_plan(695), 1);

    //   } // end if;

    // });

  } // end woocommerce_subscription_hooks;

  public function add_link_to_back_end($text, $order) {

    $is_wp_ultimo_order = get_post_meta($order->get_id(), '_is_wp_ultimo', true) == 'yes';

    $default_site = get_active_blog_for_user( get_current_user_id() );
 
    if ($default_site && $is_wp_ultimo_order) {

      switch_to_blog( $default_site->blog_id );

        $panel_url = $this->get_success_url();

      restore_current_blog();

      $text .= '<br><br>' . sprintf('<a class="button button-primary" href="%s">%s</a>', $panel_url, __('Go to your Site\'s Dashboard &rarr;', 'wu-wc'));

    } // end if;

    return $text;

  } // end add_link_to_back_end;

  public function get_difference_in_days($start_date, $end_date) {

    $start_date_time = new DateTime($start_date);
    $end_date_time   = new DateTime($end_date);

    return $start_date_time->diff( $end_date_time )->days;

  } // end get_difference_in_days;

  public function new_calculate_pro_rate($old_price, $old_freq, $new_price, $new_freq, $start_date) {

    $end_date_time = new DateTime($start_date);
    $end_date_time->add( date_interval_create_from_date_string("+ $new_freq months") );

    $old_end_date_time = new DateTime($start_date);
    $old_end_date_time->add( date_interval_create_from_date_string("+ $old_freq months") );

    $now = WU_Subscription::get_now();

    $days_in_the_new_subscription  = $this->get_difference_in_days( $start_date, $end_date_time->format('Y-m-d H:i:s') );
    $days_in_the_old_subscription  = $this->get_difference_in_days( $start_date, $old_end_date_time->format('Y-m-d H:i:s') );

    $days_until_the_end_of_the_new_subscription = $this->get_difference_in_days( $now->format('Y-m-d H:i:s'), $end_date_time->format('Y-m-d H:i:s') );
    
    $days_from_the_start_until_now = $this->get_difference_in_days( $start_date, $now->format('Y-m-d H:i:s') );
    $days_until_the_end_of_the_old_subscription = $this->get_difference_in_days( $start_date, $old_end_date_time->format('Y-m-d H:i:s') );
    
    $credit = $old_price - ( $old_price * ( $days_from_the_start_until_now / $days_in_the_old_subscription ) );

    return ( ( $days_until_the_end_of_the_new_subscription / $days_in_the_new_subscription ) * $new_price ) - $credit;

  }

  /**
   * Sets the name and price values for the WooCommerce Subscription 
   *
   * @since 1.2.0
   * @param WC_Subscription $wc_subscription
   * @param WC_Order_Item_Product $subscription_item
   * @param string $name
   * @param float $price
   * @return void
   */
  public function set_wc_subscription($wc_subscription, $subscription_item, $name, $price) {

    $subscription_item->set_total( $price );
    $subscription_item->set_name( $name );
    $subscription_item->save();

    $wc_subscription->calculate_totals();

  } // end set_wc_subscription;

  /**
   * Handles the plan upgrades and downgrade
   *
   * @since 1.2.0
   * @param WU_Subscription $subscription
   * @param WU_Plan $current_plan
   * @param WU_Plan $new_plan
   * @return void
   */
  public function change_plan_woocommerce_subscription($subscription, $current_plan, $new_plan, $new_freq) {

    switch_to_blog(get_current_site()->blog_id);

      $wc_subscription = wc_get_order( $subscription->get_meta('_wc_subscription_id') );

      if ($wc_subscription) {

        // Case se a subscription já existe
        // 1. Pega o item Subscription dessa subscription

        $subscription_item = array_pop($wc_subscription->get_items());

        $pro_rate = $this->new_calculate_pro_rate($subscription->get_price_after_coupon_code(), $subscription->freq, $new_plan->get_price( $new_freq ), $new_freq, $subscription->get_date('active_until', 'Y-m-d H:i:s', date_interval_create_from_date_string("- $subscription->freq months")));

        // Edits the subscription temporarely to add the pro-rate price
        if ($pro_rate > 0) {

          $name = $new_plan->title . ' - ' .__('Pro-rate (Changed Plans)', 'wp-ultimo-woocommerce');
          $price = $pro_rate;

        } else {

          $name = $new_plan->title;
          $price = $new_plan->get_price( $new_freq );

        }

        // After that, we resume the change, modifying the values for the new plan
        $this->set_wc_subscription( $wc_subscription, $subscription_item, $name, $price);
        
        // Make sure gateways are setup
        WC()->payment_gateways();

        do_action( 'woocommerce_scheduled_subscription_payment', $wc_subscription->get_id() );

      } // end if;

    restore_current_blog();

  } // end change_plan_woocommerce_subscription;

  /**
   * Cancel order when the integration is done
   *
   * @since 1.2.0
   * @param WU_Subscription $subscription
   * @return boolean
   */
  public function cancel_woocommerce_subscription_on_integration_removal($subscription) {

    switch_to_blog(get_current_site()->blog_id);

      $wc_subscription = wc_get_order( $subscription->get_meta('_wc_subscription_id') );

      if ($wc_subscription) {

        $wc_subscription->cancel_order();

      } // end if;

    restore_current_blog();

  } // end cancel_woocommerce_subscription_on_integration_removal;

   /**
    * And transactions to WP Ultimo on renewal
    *
    * @since 1.2.0
    * @param WC_Order $order
    * @param WC_Subscription $subscription
    * @return void
    */
  public function create_transaction_on_renew($order, $subscription) {

    /**
     * Create the transaction
     */
    $message = sprintf(__('Payment for the plan %s using %s as the processor.', 'wp-ultimo-woocommerce'), $plan->title, $this->get_gateway_title());

    // Log Transaction and the results
    WU_Transactions::add_transaction($order->get_user_id(), $order->get_id(), 'pending', $order->get_total(), $this->id, $message, false, $order->get_total());

    return $order;

   } // end create_transaction_on_renew;

   /**
    * Get the normal success URL
    *
    * @since 1.2.0
    * @return string
    */
   public function get_success_url() {

    // Let's generate a nonce code to later verification, just to be sure...
    $security_code = wp_create_nonce('wu_gateway_page');

    return admin_url(sprintf('admin.php?page=wu-my-account&action=%s&code=%s&gateway=%s', 'success', $security_code, $this->id));

  } // end get_url;

   /**
    * Redirect after the order finalizes
    * 
    * @since 1.2.0
    * @param integer $order_id
    * @return void
    */
   public function redirect_to_account_page_after_checkout($result, $order_id) {

    $order = wc_get_order($order_id);

    $is_wp_ultimo_order = get_post_meta($order_id, '_is_wp_ultimo', true) == 'yes';

    $default_site = get_active_blog_for_user( get_current_user_id() );
 
    if ($default_site && $is_wp_ultimo_order) {

      switch_to_blog( $default_site->blog_id );

        $result['redirect'] = $this->get_success_url();

      restore_current_blog();

    } // end if;

    return $result;

   } // end redirect_to_account_page_after_checkout;

   /**
    * Process the Subscription from WooCommerce after creation, allowing us to add our custom meta data
    *
    * @since 1.2.0
    * @param WC_Subscription $subscription
    * @param WC_Order $order
    * @param array $recurring_cart
    * @return void
    */
   public function process_subscription_created($subscription, $order, $recurring_cart) {

    $wu_subscription = wu_get_subscription( $order->get_user_id() );

    $plan = $wu_subscription->get_plan();

    /**
     * Delete the item, so we don't have a lot of bloat on the WooCommerce Products
     */
    foreach ($subscription->get_items() as $item) {

      $product = wc_get_product( $item->get_product_id() );

      if ($product) {

        $product->delete( true );

      } // end if;

    } // end foreach;

    // Add a simple note to let the admin know that this is a automatic note generate by WP Ultimo
    $subscription->add_order_note(__('Subscription created by WP Ultimo', 'wp-ultimo-woocommerce'));
    $order->add_order_note(__('Subscription created by WP Ultimo', 'wp-ultimo-woocommerce'));

    // Set this as a WP Ultimo Order
    update_post_meta($subscription->get_id(), '_is_wp_ultimo', 'yes');
    update_post_meta($order->get_id(), '_is_wp_ultimo', 'yes');

    // Add the WC Subscription to the WU Subscription
    $wu_subscription->set_meta('_wc_subscription_id', $subscription->get_id());

    /**
     * Create the transaction
     */
    $message = sprintf(__('Payment for the plan %s using %s as the processor.', 'wp-ultimo-woocommerce'), $plan->title, $this->get_gateway_title());

    // Log Transaction and the results
    WU_Transactions::add_transaction($order->get_user_id(), $order->get_id(), 'pending', $order->get_total(), $this->id, $message, false, $order->get_total());

   } // end process_subscription_created;

   /**
    * Redirect the user to the cart page directly after integration
    *
    * @since 1.2.0
    * @param string $url
    * @param string $type
    * @return string
    */
   public function redirect_to_cart_on_integration($url, $type) {

    $subscription = wu_get_current_subscription();

    if ($type == 'success' && $subscription) {

      $transactions = WU_Transactions::get_transactions($subscription->user_id, 1, 1, 'id', 'DESC');
      
      if (empty($transactions) || !WU_Settings::get_setting('woocommerce_redirect_automatically', false)) {
        
        return $url;

      } // end if;

      if ($transactions[0]->type !== 'pending') {

        return $url;

      } // end if;

      $order_link = $this->get_payment_link($transactions[0]->reference_id);

      return $order_link;

    } // end if;

    return $url;

   } // end redirect_to_cart_on_integration;

} // end class WU_Gateway_WooCommerce

/**
 * Register the gateway =D
 */
wu_register_gateway('woocommerce', __('WooCommerce Integration', 'wp-ultimo-woocommerce'), __('Extend your WP Ultimo payment options to allow your subscribers to use all of the many available methods on your WooCommerce install!', 'wp-ultimo-woocommerce'), 'WU_Gateway_WooCommerce');
