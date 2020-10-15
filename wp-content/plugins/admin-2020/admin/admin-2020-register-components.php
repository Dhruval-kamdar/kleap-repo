<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class Admin_2020_Register_Components {

  private $version;

  public function __construct( $theversion, $productid ) {

    $this->version = $theversion;
    $this->productid = $productid;

  }

  public function load(){

    $utils = new Admin2020_Util();

    $this->productkey = $utils->get_option('admin2020_pluginPage_licence_key');
    $this->run_validation($this->productkey);
    $productid = $this->productid;
    $this->productkey = $utils->get_option('admin2020_pluginPage_licence_key');

  }



  public function run_validation($k){

    if(!get_transient( 'admin2020_components')){


      $productid = $this->productid;

      $data = array();
      $data["key"] = $k;
      $data["increment_usage_count"] = true;
      $domain = get_home_url();

      $remote = wp_remote_get( 'https://admintwentytwenty.com/validate/validate.php?id='.$this->productid.'&k='.$k.'&d='.$domain, array(
  			'timeout' => 10,
  			'headers' => array(
  				'Accept' => 'application/json'
  			) )
  		);

      $settingsurl = get_admin_url().'admin.php?page=admin_2020';
      if (is_multisite()){
        $settingsurl = network_admin_url().'admin.php?page=admin_2020';
      }


      if ( ! is_wp_error( $remote ) && isset( $remote['response']['code'] ) && $remote['response']['code'] == 200 && ! empty( $remote['body'] ) ) {

  			$remote = json_decode( $remote['body'] );
        $state = $remote->state;
        $themessage = $remote->message;

        if ($state != "false"){
          set_transient( 'admin2020_components', true, 12 * HOUR_IN_SECONDS );
          return;
        } else {
          $this->new_notice($themessage." ".__("Please enter a valid product key for Admin 2020")."<a href='".$settingsurl."'> ".__("here")."</a>");
          return;
        }

  		}
    }
  }

  public function new_notice($message){

    $this->message = $message;

    add_action('admin_notices', function($message){
      echo '<div class="notice notice-warning" style="display: block !important;visibility: visible !important;"><p>';
      echo $this->message;
      echo  '</p></div>';
    });

  }


}
