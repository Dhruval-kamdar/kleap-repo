<?php
/**
 * Plugin Name: WP Ultimo: Multiple Accounts
 * Text Domain: wu-multiple-accounts
 * Description: Allows for users to create accounts on the main network (WP Ultimo), even if the user has used that email to create an account on one of the subsites before. BETA. Supports WooCommerce accounts for now.
 * Version: 1.1.2
 * Author: Arindo Duque - NextPress
 * Author URI: http://nextpress.co/
 * Copyright: Arindo Duque, NextPress
 * Network: true
 */

class WU_Multiple_Logins {

  /** @var string Save the original email for later reuse */
  public $original_email = '';

  /** @var string Save the fake email to later comparisons */
  public $fake_email     = '';

  /**
   * Initiate the plugin
   */
  public function __construct() {

    // Add post action to allow
    add_action('init', array($this, 'check_post_for_register'));

    // Change the email back
    add_filter('woocommerce_new_customer_data', array($this, 'new_costumer_original_email'));

    // check for register
    add_filter('wpmu_validate_user_signup', array($this, 'skip_email_exist'));

    // For single site as well
    add_filter('pre_user_email', array($this, 'skip_email_exist_single'));

    // Action in the login to debug the login info
    add_filter('authenticate', array($this, 'fix_login'), 50000, 3);

    // Now we handle the password thing
    add_action('init', array($this, 'handle_reset_password'), 2000);

    // Now we add a custom column in that table to allow the admin to control them
    add_filter('wpmu_users_columns', array($this, 'add_multiple_account_column'));
    add_filter('manage_users_custom_column', array($this, 'add_column_content'), 10, 3);

    // Fix WooCommerce Email
    add_filter('woocommerce_checkout_update_order_meta', array($this, 'fix_billing_email_in_wc_order'), 10, 2);

    // Updater
    require_once plugin_dir_path(__FILE__) . '/inc/class-wu-addon-updater-free.php';

    /**
     * @since 1.2.0 Creates the updater
     * @var WU_Addon_Updater
     */
    $updater = new WU_Addon_Updater_Free('wp-ultimo-multiple-accounts', __('WP Ultimo: Miltiple Accounts', 'wp-ultimo-multiple-accounts'), __FILE__);

  } // end construct;

  function fix_billing_email_in_wc_order($order_id, $posted) {
    // var_dump($posted); var_dump($this->fake_email); die;
    if ($posted['billing_email'] == $this->fake_email) {
      update_post_meta($order_id, '_billing_email', $this->original_email);
    }
  }

  function add_multiple_account_column($columns) {
    $columns['multiple_accounts'] = __('Multiple Accounts', 'multiple-logins');
    return $columns;
  } // end add_multiple_account_column;

  function add_column_content($null, $column, $user_id) {

    if ($column == 'multiple_accounts') {

      // Get user email
      $user = get_user_by('ID', $user_id);

      // Get all the accounts with the same email
      $users = new WP_User_Query(array(
        'blog_id' => 0,
        'search'  => $user->user_email,
        'fields'  => array('ID', 'user_login')
      ));

      $html  = sprintf(__('<strong>%s</strong> accounts using this email.', 'multiemails'), $users->total_users);
      $html .= sprintf("<br><a href='%s' class=''>". __('See all', 'multiemails') ." &raquo;</a>", network_admin_url('users.php?s='.$user->user_email));
      echo $html;

    } // end if;

  } // end add_column_content;

  /**
   * Handles the reset password
   * @return [type] [description]
   */
  function handle_reset_password() {

    // Only run in the right case
    if ( (isset($_REQUEST['action']) && $_REQUEST['action'] == 'retrievepassword')
    || (isset($_REQUEST['wc_reset_password']) && $_REQUEST['wc_reset_password'])) {

      // Only do thing if is login by email
      if (is_email($_REQUEST['user_login'])) {

        $user = $this->get_right_user($_REQUEST['user_login']);
        // Reset the username
        $_REQUEST['user_login'] = $user->user_login;
        $_POST['user_login'] = $user->user_login;
        // var_dump($_REQUEST); die;

      } // end if;

    } // end if;

  } // end handle_reset_password;

  /**
   * Get the right user to a given site based on the email and password
   * @param  [type]  $email    [description]
   * @param  boolean $password [description]
   * @return [type]            [description]
   */
  function get_right_user($email, $password = false) {

    // Sets the right user to be returned;
    $right_user = null;

    // $hash = wp_hash_password($password);

    // Now we search for the correct user based on the password and the blog information
    $users = new WP_User_Query(array('search' => $email));
    // var_dump($users);

    // Loop the results and check which one is in this group
    foreach ($users->results as $user_with_email) {

      // var_dump(wp_check_password($password, $user_with_email->user_pass, $user_with_email->ID));
      // var_dump($this->user_can_for_blog($user_with_email, get_current_blog_id(), "read")); die;

      $conditions = $password == false ? true : wp_check_password($password, $user_with_email->user_pass, $user_with_email->ID);


      // Check for the pertinence of that user in this site
      if ($conditions && $this->user_can_for_blog($user_with_email, get_current_blog_id(), "read")) {

        // Set right user
        $right_user = $user_with_email;

      } // end if;

    } // end foreach;

    // Return right user
    return $right_user;

  } // end get_right_user;

  /**
   * Checks if a given user is a member in the site
   * @return [type] [description]
   */
  function check_for_user_in_site($email) {

    // Sets the right user to be returned;
    $has_user = false;

    // $hash = wp_hash_password($password);

    // Now we search for the correct user based on the password and the blog information
    $users = new WP_User_Query(array('search' => $email));
    // var_dump($users);

    // Loop the results and check which one is in this group
    foreach ($users->results as $user_with_email) {

      // Check for the pertinence of that user in this site
      if ($this->user_can_for_blog($user_with_email, get_current_blog_id(), "read")) {
        $has_user = true;
      } // end if;

    } // end foreach;

    // If nothing was found return false;
    return $has_user;

  } // end check_for_user_in_site;

  /**
   * Get the right user after logging in
   * @param  [type] $user     [description]
   * @param  [type] $username [description]
   * @param  [type] $password [description]
   * @return [type]           [description]
   */
  function fix_login($user, $username, $password) {

    // var_dump($this->user_can_for_blog($user, get_current_blog_id(), "read"));
    // var_dump($username); 
    // var_dump($password);

    if (isset($_POST['username'])) {

      // Get the email
      $email = $_POST['username'];
      // var_dump($email);

      // Only do thing if is login by email
      if (is_email($email)) {

        // Sets the right user to be returned;
        $user = $this->get_right_user($email, $password);

      } // end if;


    } // end if;

    return $user;

  } // end debug_login;

  /**
   * Skip the email check in WordPress
   * @param  [type] $result [description]
   * @return [type]         [description]
   */
  function skip_email_exist($result){
    
    if (isset($result['errors']->errors['user_email']) && ($key = array_search(__('Sorry, that email address is already used!'), $result['errors']->errors['user_email'])) !== false) {
          unset($result['errors']->errors['user_email'][$key]);
          if (empty($result['errors']->errors['user_email'])) unset($result['errors']->errors['user_email']);
    }
    
    define('WP_IMPORTING', 'SKIP_EMAIL_EXIST');
    return $result;

  } // end skip_email_exist;

  /**
   * Skip the verification for user in single sites
   * @param  [type] $user_email [description]
   * @return [type]             [description]
   */
  function skip_email_exist_single($user_email){
    define('WP_IMPORTING', 'SKIP_EMAIL_EXIST');
    return $user_email;
  } // end skip_email_exists_single;

  /**
   * We check the POST variable to change the email, preventing the block from WordPress
   * @return [type] [description]
   */
  function check_post_for_register() {

    // Check if we need to run
    if (isset($_POST['billing_email'])) {

      // We need to check if theres a user with the same email in the same site
      if (!$this->check_for_user_in_site($_POST['billing_email'])) {

        // Copy original email
        $this->original_email = $_POST['billing_email'];
        // var_dump($this->original_email);

        // Set that to a different email
        $_POST['billing_email'] = rand(0, 1000).'_fake_nexwee@email.com';

        $this->fake_email = $_POST['billing_email'];

      } // end if;

    } // end if;

    // Check if we need to run
    if (isset($_POST['email']) && isset($_POST['woocommerce-register-nonce'])) {

      // We need to check if theres a user with the same email in the same site
      if (!$this->check_for_user_in_site($_POST['email'])) {

        // Copy original email
        $this->original_email = $_POST['email'];
        // var_dump($this->original_email);

        // Set that to a different email
        $_POST['email'] = rand(0, 1000).'_fake_nexwee@email.com';

        $this->fake_email = $_POST['email'];

      } // end if;

    } // end if;

  } // end check_post_for_register;

  /**
   * We change the email back to the original
   * @param  [type] $user_data [description]
   * @return [type]            [description]
   */
  function new_costumer_original_email($user_data) {

    // Fix email
    $user_data['user_email'] = $this->original_email;
    
    // Fix username
    $username = sanitize_user(current(explode('@', $user_data['user_email'])), true);

    // Ensure username is unique.
    $append     = 1;
    $o_username = $username;

    while (username_exists($username)) {
      $username = $o_username . $append;
      $append++;
    }

    // Fix email
    $user_data['user_login'] = $username;
    // var_dump($user_data); die;

    // Return refactored data
    return $user_data;
  
  } // end new_costumer_original_email;
  
  /**
   * We need to check the email in the order
   * @return [type] [description]
   */
  function fix_email_in_order() {
    // $order = new WC_Order(514);
  } // end fix_emails_in_order;

  /**
   * Check if user can do something in a specific blog
   * @param  [type] $user       [description]
   * @param  [type] $blog_id    [description]
   * @param  [type] $capability [description]
   * @return [type]             [description]
   */
  function user_can_for_blog($user, $blog_id, $capability) {

      $switched = is_multisite() ? switch_to_blog($blog_id) : false;

      $current_user = $user;

      if ( empty( $current_user ) ) {
        if ( $switched ) {
                restore_current_blog();
        }
        return false;
      }

      $args = array_slice( func_get_args(), 2 );
      $args = array_merge( array( $capability ), $args );

      $can = call_user_func_array( array( $current_user, 'has_cap' ), $args );

      if ( $switched ) {
              restore_current_blog();
      }

      return $can;
    
    } // end current_user_can_for_blog;

} // end class WU_Multiple_Logins;

// Call the constructor
new WU_Multiple_Logins;