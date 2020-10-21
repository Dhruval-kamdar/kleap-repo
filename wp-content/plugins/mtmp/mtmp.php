<?php
   /*
   Plugin Name: mtmp
   Plugin URI: #
   description: a plugin to customize admin functionality
   Version: 1.0
   Author: constru
   Author https://constru.ch/
   License: GPL2
   */

function my_custom_fonts() {
   $plan_id = 5776;
   $plan_id1 = 5692;
   $plan_id2 = 5817;
   $user_id = get_current_user_id();

   echo '<style type="text/css">
      div#wu-unmap-page,
      div#rank_math_metabox_link_suggestions,
      div#astra_settings_meta_box,
      body.elementororor-editor-active #elementororor-switch-mode-button,
      div#ure_content_view_restrictions_meta_box,
      #toplevel_page_wp_rankie ul li:last-child,
      .media-frame-tab-panel #menu-item-astraimages,
      li#toplevel_page_cartflows,
      #toplevel_page_rank-math ul li:last-child,
      button#menu-item-astraimages 
      {
         display: none !important;
      }
      body .plugin-install #the-list, .themes{ float:left !important; }      
      li#toplevel_page_woocommerce-marketing , 
      li#toplevel_page_woocommerce-marketing + li , 
      .rank-math-header , 
      #wp-admin-bar-rank-math, 
      .cmb2-metabox-description > a,
      li#menu-posts-elementororor_library ul li:last-child,
      ul.wu_status_list .space-used,
      #wp-admin-bar-wp-ultimo,
      ul.wu_status_list .total-users{ display: none; }
   </style>';
   echo  '<script type="text/javascript">
      jQuery(document).ready(function($) {
         $(".toplevel_page_rank-math span.ma-admin-shrink.wp-menu-name").html("SEO");
         $("#toplevel_page_wp_rankie span.ma-admin-shrink.wp-menu-name").html("Classement Google");
         $("#ure_select_other_roles").parent().parent().parent().parent().remove();
         $("#ure_select_other_roles_2").parent().parent().parent().parent().remove();
         $("#menu-pages").find("ul").append("<li><a href='.site_url().'/wp-admin/edit.php?post_type=elementororor_library&tabs_group=popup&elementororor_library_type=popup>Capture de prospect</a></li>");                  
      });
   </script>';
   if (wu_has_plan($user_id, $plan_id) || wu_has_plan($user_id, $plan_id1) || wu_has_plan($user_id, $plan_id2) && wu_is_active_subscriber($user_id)) {
      echo '<script type="text/javascript">
         jQuery(document).ready(function($) {
            console.log("M . + . B . + .");
            $(".uk-navbar-right ul.uk-navbar-nav").before(\'<div>Passer au plan supérieur <a href="'.site_url().'/wp-admin/admin.php?page=wu-my-account&action=change-plan" style="background: rgba(50,83,106,.48);padding: 6px 15px;border-radius: 15px;color: #fff;font-size: 14px;margin-left: 15px;">Booster mon site</a></div>\');
         });
        </script>';
   }

/*
   if (wu_has_plan($user_id, $plan_id) || wu_has_plan($user_id, $plan_id1) || wu_has_plan($user_id, $plan_id2) && wu_is_active_subscriber($user_id)) {
        ?>
        <style type="text/css">
            #toplevel_page_wp_rankie ul li:nth-child(2n-1){ 
               display: none;
            }
        </style>        
        <?php
   }
*/
   ?>
   <style type="text/css">
      .media-frame-tab-panel #menu-item-astraimages, button#menu-item-astraimages
      { display: none !important; }
      html{ font-size: 16px; }
   </style>
   <?php
}

function hook_css() {
   $blogs = get_blogs_of_user( get_current_user_id() );
   $tmp = '';
   foreach ($blogs as $key => $value) { $tmp = $value->siteurl; }   
   ?>
   <script type="text/javascript">
      jQuery(document).ready(function($) {
         $('body').find(".mDashboard a").attr('href', '<?php echo $tmp; ?>/wp-admin');
      });
   </script>
   <style type="text/css">
      #loginform #user_name-field #user_name,
      .woocommerce .blockUI.blockOverlay{ display: none !important; }
      .wu-content-plan .lift{ width: 30%; border:1px solid #c2c2c2; }
      .wu-content-plan .plan-tier.callout{ margin: 5%; background: #f3f3f3; padding-bottom: 30px; }
      @media screen and (max-width: 600px) {
         .wu-content-plan .lift{ width: 100%; }
         .wu-content-plan .plan-tier.callout{ margin: 0; }
      }
   </style>
   <?php if(is_main_site()){ return; } ?>
   <style>
      ul#wp-admin-bar-root-default li#wp-admin-bar-wp-logo,
      ul#wp-admin-bar-root-default li#wp-admin-bar-my-sites,
      ul#wp-admin-bar-root-default li#wp-admin-bar-edit, 
      ul#wp-admin-bar-root-default li#wp-admin-bar-rank-math,      
      ul#wp-admin-bar-top-secondary .adminbar-input{ display:none !important; } 

      body.admin-bar{ margin-top: 60px !important; }
      #wpadminbar{ height: 60px; background: #fff; padding: 0 65px; border-bottom: 1px solid; }
      #wpadminbar ul li a, #wpadminbar>#wp-toolbar span.ab-label{ font-size: 18px; line-height: 3; color: #000 !important;}
      #wpadminbar #wp-admin-bar-site-name>.ab-item:before,
      #wpadminbar>#wp-toolbar>#wp-admin-bar-root-default .ab-icon:before{ top: 12px; font-size: 26px; color: #000 !important; }
      #wpadminbar #adminbarsearch:before{ display: none; }
      #wpadminbar .ab-top-menu>li.hover>.ab-item, #wpadminbar.nojq .quicklinks .ab-top-menu>li>.ab-item:focus, #wpadminbar:not(.mobile) .ab-top-menu>li:hover>.ab-item, #wpadminbar:not(.mobile) .ab-top-menu>li>.ab-item:focus{ background: transparent !important; }
      #wpadminbar .menupop .ab-sub-wrapper, #wpadminbar .shortlink-input{ 
         background: #fff;
         top:60px;
      }         
   </style>   
   <?php
}
add_action('wp_footer', 'hook_css');

/*add_action( 'elementororor/init', function() {
   if(is_admin()){
      global $pagenow;
      if($pagenow != 'admin-ajax.php' && $pagenow != 'async-upload.php'){
      ?>
      <style type="text/css">
         .media-frame-tab-panel #menu-item-astraimages, button#menu-item-astraimages
         { display: none !important; background: <?=$pagenow ?> }
      </style>
      <?php }
   }
});*/


add_action( 'admin_init', 'nh_remove_menu_pages',1000);
function nh_remove_menu_pages() {
   global $user_ID;
   $roles = wp_get_current_user()->roles; 
   if(in_array('owner',$roles) || in_array('employee',$roles)) {
     // remove_menu_page( 'themes.php' ); //Appearance
   }
   if(is_main_site()){ return; }

   add_action('admin_head', 'my_custom_fonts');
   /*if(get_current_blog_id() != 8){
      remove_menu_page( 'edit.php?post_type=elementororor_library' );   
   }*/    
   
   $plan_id = 5776;
   $plan_id1 = 5692;
   $plan_id2 = 5817;
   $user_id = get_current_user_id();

   if (wu_has_plan($user_id, $plan_id) || wu_has_plan($user_id, $plan_id1) || wu_has_plan($user_id, $plan_id2) && wu_is_active_subscriber($user_id)) {
      remove_menu_page( 'edit.php' ); //posts      
   }
}

function se337302_fullscreen_editor()
{
    $js_code = "jQuery(document).ready(function(){" .
            "   var isFullScreenMode = wp.data.select('core/edit-post').isFeatureActive('fullscreenMode');" .
            "   if ( !isFullScreenMode )" .
            "       wp.data.dispatch('core/edit-post').toggleFeature('fullscreenMode');" .
            "});";
    wp_add_inline_script( 'wp-blocks', $js_code );

}
add_action( 'enqueue_block_editor_assets', 'se337302_fullscreen_editor' );

// Remove Administrator role from roles list
add_action( 'editable_roles' , 'hide_adminstrator_editable_roles' );
function hide_adminstrator_editable_roles( $roles ){
   $rls = wp_get_current_user()->roles; 
   if( !in_array('owner',$rls)){ return $roles; }

   //if ( isset( $roles['owner'] )){
      unset( $roles['administrator'] );   
      unset( $roles['owner'] );   
      unset( $roles['shop_manager'] );   
      unset( $roles['customer'] );   
      unset( $roles['subscriber'] );   
      unset( $roles['contributor'] );   
      unset( $roles['author'] );   
      unset( $roles['editor'] ); 
   //}
   return $roles;
}

add_filter('registration_errors', function($wp_error, $sanitized_user_login, $user_email){
    if(isset($wp_error->errors['empty_username'])){
        unset($wp_error->errors['empty_username']);
    }

    if(isset($wp_error->errors['username_exists'])){
        unset($wp_error->errors['username_exists']);
    }
    return $wp_error;
}, 10, 3);

add_action('admin_init','psp_add_role_caps',999);
function psp_add_role_caps() {
   $role = get_role( 'owner' );
   $role->add_cap( 'wp_rankie_settings' );   
}

add_action( 'wp', 'astra_remove_header' );

function astra_remove_header() {
    remove_action( 'astra_masthead', 'astra_masthead_primary_template' );
}

add_action('wp_footer','mcss');
function mcss(){
add_filter('show_admin_bar', '__return_false');
?>
<style type="text/css">
   #wpadminbar{ display: none !important; }
</style>
<?php } 

function wu_add_intercom() { 
$user_id = get_current_user_id();
$plan_id_cb = 5776;
$plan_id_cp = 5692;
$plan_id_ca = 5695;
$plan_name = "Constru Base";
if (wu_has_plan($user_id, $plan_id_cb)) {
  $plan_name = "Constru Base";
}else if (wu_has_plan($user_id, $plan_id_cp)) {
  $plan_name = "Constru Pro";
}else if (wu_has_plan($user_id, $plan_id_ca)) {
  $plan_name = "Constru Avancé";
}else{
  $plan_name = "No plan available";
}
?>
<!-- INTERCOM CODE STARTS HERE -->
<script>

  var APP_ID = "eginghgr";

 window.intercomSettings = {
    app_id: "eginghgr",
    name: "<?=wp_get_current_user()->user_login?>", // Full name
    email: "<?=wp_get_current_user()->user_email?>", // Email address 
    user_id: "<?=wp_get_current_user()->ID?>", // ID
    Plan: "<?=$plan_name?>", // ID
    FirstName: "<?=wp_get_current_user()->user_firstname?>", // ID
    LastName: "<?=wp_get_current_user()->user_lastname?>", // ID
    created_at: "<?=strtotime(wp_get_current_user()->created_at)?>" // Signup date as a Unix timestamp

  };

</script>

<script>(function(){var w=window;var ic=w.Intercom;if(typeof ic==="function"){ic('reattach_activator');ic('update',w.intercomSettings);}else{var d=document;var i=function(){i.c(arguments);};i.q=[];i.c=function(args){i.q.push(args);};w.Intercom=i;var l=function(){var s=d.createElement('script');s.type='text/javascript';s.async=true;s.src='https://widget.intercom.io/widget/' + "eginghgr";var x=d.getElementsByTagName('script')[0];x.parentNode.insertBefore(s,x);};if(w.attachEvent){w.attachEvent('onload',l);}else{w.addEventListener('load',l,false);}}})();</script>

<!-- INTERCOM CODE ENDS HERE -->

<?php } // end wu_add_intercom;

add_action( 'admin_head', 'wu_add_intercom');