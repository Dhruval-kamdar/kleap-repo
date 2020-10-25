<?php
use ElementorExtensions\Admin\EE_MB_Setting_Common;

$post_id = get_the_ID();
$post = get_post($post_id);

$post_meta = get_post_meta($post_id);
$property_title = $post->post_title;
$post_link = get_the_permalink();
$property_listed_date = $post->post_date;

if(!function_exists('ee_mb_enqueue_script')) {
    function ee_mb_enqueue_script() {
     
        $gmap_key = EE_MB_Setting_Common::get_settings_key('ee_mb_integration_setting', 'ee_mb_google_map_key');

        $map_url = 'https://maps.googleapis.com/maps/api/js?sensor=false&libraries=places';
        if(!empty($gmap_key)):
            $map_url = 'https://maps.googleapis.com/maps/api/js?key='.$gmap_key.'&libraries=places';
        endif;
    
        wp_enqueue_script(
            'ee-mb-googlemap-api',
            $map_url,
            [ 
                'jquery', 
            ],
            '',
            true 
        );

        wp_enqueue_script(
            'ee-mb-fancybox-jquery',
            ELEMENTOR_EXTENSIONS_URL . 'assets/lib/fancybox/jquery.fancybox.min.js',
            [ 
                'jquery', 
            ],
            '',
            true 
        );

        wp_enqueue_script(
            'ee-mb-slick',
            ELEMENTOR_EXTENSIONS_URL . 'assets/lib/slick/slick.min.js',
            [ 
                'jquery', 
            ],
            '',
            true 
        );

        wp_enqueue_script(
            'ee-mb-property-single',
            ELEMENTOR_EXTENSIONS_URL . 'assets/js/property-single-page.js',
            [ 
                'jquery', 
            ],
            '',
            true 
        );

        wp_enqueue_style(
            'ee-mb-property-single',
            ELEMENTOR_EXTENSIONS_URL . 'assets/css/property-single.min.css',
            []
        );

        wp_localize_script(
            'ee-mb-googlemap-api',
            'ElementorExtensionsFrontendConfig',
            [
                'ajaxurl' => admin_url( 'admin-ajax.php' ),
                'nonce' => wp_create_nonce( 'elementor-extensions-js' ),
                'ee_mb_path' => ELEMENTOR_EXTENSIONS_URL,
            ]
        );
     
    }
    add_action( 'wp_enqueue_scripts', 'ee_mb_enqueue_script' );
}

/*@ Add fancybox in head*/
if(!function_exists('ee_mb_property_search_custom_style')):
  function ee_mb_property_search_custom_style(){

    $agent = EE_MB_Setting_Common::get_settings_key( 'ee_mb_single_page' );

    if(!empty($agent)):

      echo '<style type="text/css">';

      if(!empty($agent->color_scheme)):
        $color_scheme = $agent->color_scheme;

        echo '.single_property_page .bg-purple,.single_property_page .slick-prev,.single_property_page .slick-next{background:'.$color_scheme.';}
        .single_property_page .price-info-wrap h2,.single_property_page .agent-info p a{color:'.$color_scheme.';}
        .single_property_page .share-links li a{color:'.$color_scheme.';border-color:'.$color_scheme.';}
        .single_property_page .share-links li a:hover{background-color: '.$color_scheme.';color:#FFF;}
        .single_property_page .button.btn-submit{background:'.$color_scheme.';border-color:'.$color_scheme.';}
        .single_property_page .button.btn-submit:focus,.single_property_page .button.btn-submit:hover,.single_property_page .button.btn-submit:active {color: '.$color_scheme.';background:#FFF;}
        .single_property_page ul.tabs li.current,.single_property_page ul.tabs li:hover{border-color:'.$color_scheme.';}
        .single_property_page .sold_stc{background-color:'.$color_scheme.';}';
      endif;

      
      if(!empty($agent->font_color_scheme)):
        $lfc_s = $agent->font_color_scheme;
        echo '.single_property_page .f-left.banner-name h4,
        .single_property_page .search-sec h4,
        .single_property_page .slideCount,
        .single_property_page .sold_stc,
        .single_property_page .share-links li a:hover,
        .single_property_page .button.btn-submit,
        .single_property_page .slick-prev::before, .single_property_page .slick-next::before
        {color:'.$lfc_s.'}';
      endif;

      echo '</style>';
    endif;
  }
  add_action('wp_head','ee_mb_property_search_custom_style');
endif;

if(!function_exists('siteset_property_search_custom_meta')):
  add_action('wp_head','siteset_property_search_custom_meta');
  function siteset_property_search_custom_meta(){
    $post_id = get_the_ID();
    $post_meta = get_post_meta($post_id);

    $property_gallery = (!empty($post_meta['gallery'][0])) ? explode(',',$post_meta['gallery'][0]) : '';

    $image = plugins_url( 'assets/img/no-imagejpg', ELEMENTOR_EXTENSIONS__FILE__ );
    if(!empty($property_gallery)):
      $image = wp_get_attachment_image_src($property_gallery[0], 'full');
      $image = $image[0];
    endif;

    $property_location_map = (!empty($post_meta['location'][0])) ? unserialize($post_meta['location'][0]) : '';
    
    echo '<meta property="og:title" content="'.get_the_title().' | '.get_bloginfo('name').'"/>';

    if(!empty($property_location_map['address'])):
      echo '<meta property="og:description" content="'.$property_location_map['address'].'"/>';
    endif;
    
    echo '<meta property="og:url" content="'.get_the_permalink($post_id).'"/>
    <meta property="og:image" content="'.$image.'"/>'; 
  }
endif;

get_header();

/*@ Check post existance */
if(!empty($post)):

  global $property_location_map,$property_agent;

  $single_page_settings = EE_MB_Setting_Common::get_settings_key('ee_mb_single_page');
  $pro_general_setting = EE_MB_Setting_Common::get_settings_key( 'ee_mb_property_setting' );
  $ee_mb_agent = EE_MB_Setting_Common::get_settings_key( 'ee_mb_agent' );
  $default_agent = (isset($ee_mb_agent->default_agent)) ? $ee_mb_agent->default_agent : '';
  $sold_stc_lbl = (isset($pro_general_setting->sold_stc_label)) ? $pro_general_setting->sold_stc_label : '';

  $currency = (isset($pro_general_setting->currency_symbol)) ? $pro_general_setting->currency_symbol : '';


  $property_price = (!empty($post_meta['price'][0])) ? $post_meta['price'][0] : 'N/A';
  $property_price_note = (!empty($post_meta['price_note'][0])) ? $post_meta['price_note'][0] : '';
  $property_bedrooms = (!empty($post_meta['bedrooms'][0])) ? $post_meta['bedrooms'][0] : '';
  $property_type = (!empty($post_meta['type'][0])) ? $post_meta['type'][0] : '';
  $property_gallery = (!empty($post_meta['gallery'][0])) ? explode(',',$post_meta['gallery'][0]) : '';

  $property_description = (!empty($post_meta['overview'][0])) ? $post_meta['overview'][0] : '';
  $settings['addresses'] = $property_location_map = (!empty($post_meta['location'][0])) ? unserialize($post_meta['location'][0]) : '';
  $property_footer_plan = (!empty($post_meta['floorplan'][0])) ? $post_meta['floorplan'][0] : '';
  $property_agent = (!empty($post_meta['agent'][0])) ? $post_meta['agent'][0] : $default_agent;

  $settings['school_checker_tab'] = $school_checker_tab = (!empty($single_page_settings->school_checker)) ? $single_page_settings->school_checker : '';

  $sold_stc = (!empty($post_meta['include_sold_subject_to_contract'][0])) ? $post_meta['include_sold_subject_to_contract'][0] : ''; 
    
  if($property_type == 'Other'):
    $property_type = $post_meta['other_type'][0];
  endif;

  $agent_meta = get_post_meta($property_agent);

  $agent_email = '';
  if(!empty($agent_meta['email'][0])):
    $settings['agent_email'] = $agent_meta['email'][0];
  endif;

  $map_settings = json_encode($settings);
  ?>

  <div class="elementor_siteset_loading_overlay property_search">
    <div class="elementor_siteset_loader"></div>
  </div>

  <div class="content single_property_page" data-settings='<?php echo $map_settings; ?>'>
   
    <?php if(!empty($property_gallery)): ?>
        <section class="slider-section">
          <div class="container slider-container slider-wrp">
            <div class="slider slider-for">
              <div class="slideCount"></div>
              <?php foreach($property_gallery as $key=>$property_image): 
               $image = wp_get_attachment_image_src($property_image, 'full');
               $image = $image[0];

               $image_metadata = get_post($property_image);
               $image_caption = '';
               if(!empty($image_metadata)):
                $image_caption = (!empty($image_metadata->post_excerpt)) ? $image_metadata->post_excerpt : $image_metadata->post_title ;
              endif;
              ?>
              <div class="slider_item" data-image-caption="<?php echo $image_caption; ?>">
                <img src="<?php echo esc_url($image); ?>" alt="<?php echo basename($image); ?>"/>
              </div>
            <?php endforeach; ?>
          </div>
          
          <div class="bg-purple info-section">
            <div class="f-left banner-name">
              <h4 id="slider_caption_text"></h4>
            </div>
            <div class="f-right search-sec btn_enlarge" id="btn_enlarge">
              <h4><i class="fa fa-search"></i> Enlarge </h4>
            </div>
          </div> 

          <div class="slider slider-nav">
            <?php 
            foreach($property_gallery as $key=>$property_image): 
              $image = wp_get_attachment_image_src($property_image, 'full');
              $image = $image[0];
              ?>
              <div>
                <img src="<?php echo esc_url($image); ?>" alt="<?php echo basename($image); ?>"/>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      </section>
    <?php  endif; ?>

    <section class="section ">
      <div class="container">
        <div class="row boxwidth border-bottom ">
          <div class="col-half backtopage-wrap">
            <h4>
              <a href="javascript:void(0);" onclick="window.history.go(-1); return false;" title="Back to search results"><i class="fa fa-chevron-left"></i> Back to search results</a>
            </h4>
          </div>
          <div class="col-half">
            <div class="share-links">
              <ul>
                <li>
                  <h4>Share this page</h4>
                </li>
                <li><a data-elementor-open-lightbox="no" target="_blank" href="mailto:sender@mail.com?subject=Shared a post from <?php bloginfo('name'); ?>&body=<?php the_title('','',true); ?>&#32;&#32;<?php the_permalink(); ?>" title="Email"><i class="fa fa-envelope" aria-hidden="true"></i></a></li>
                <li><a data-elementor-open-lightbox="no" target="_blank" href="<?php echo 'https://twitter.com/intent/tweet?text='.$property_title.'&amp;url='.$post_link; ?>" title="Twitter"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
                <li><a data-elementor-open-lightbox="no" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode($post_link); ?>" title="Facebook"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="section ">
      <div class="container">
        <div class="boxwidth ">
          <!-- Main Content -->
          <div class="w-73 f-left pad-r-22 res-w-100 main-cont">

            <div class="head-title-wrap">
              <div class="blog-info-wrap f-left text-left">
                <?php
                  if(!empty($property_title)):
                    echo '<h2>'.$property_title.'</h2>';
                  endif;

                  if(!empty($property_bedrooms)):
                    $property_type = (empty($property_type)) ? ' - '.$property_type : '';
                    echo '<h3>'.$property_bedrooms.' bedroom'.$property_type.'</h3>';
                  endif;

                  if(!empty($property_listed_date)):
                    echo '<h5>Listed on '.date('dS F Y',strtotime($property_listed_date)).'</h5>';
                  endif;
                ?>
              </div>

              <?php if(!empty($property_price)): ?>
                <div class="price-info-wrap f-right text-right">
                  <h2><?php echo $property_price_note; ?>
                    <span><?php echo $currency.number_format_i18n($property_price); ?></span>
                  </h2>

                  <?php 
                    if(!empty($sold_stc)):
                      echo '<span class="sold_stc">'.((!empty($sold_stc_lbl))? $sold_stc_lbl : "Sold STC").'</span>';
                    endif; 
                  ?>
                </div>
              <?php endif; ?>

            </div>

            <div class="tab-wrap">
              <div class="tab-container">
                <ul class="tabs">

                  <?php if(!empty($property_description)): ?>
                    <li class="tab-link current" data-tab="tab-1">Description</li>
                  <?php endif; 
                  
                  if(!empty($property_location_map['lat']) && !empty($property_location_map['lng'])): ?>
                    <li class="tab-link" data-tab="tab-2">Location Map</li>
                  <?php endif; 
                
                  if(!empty($property_footer_plan)): ?>
                    <li class="tab-link" data-tab="tab-3">Floorplan</li>
                  <?php endif; ?>

                  <?php                
                  if(empty($school_checker_tab)): ?>
                    <li class="tab-link school_checker_tab" data-tab="tab-4">School Checker</li>
                  <?php endif; ?>
                </ul>

                <?php if(!empty($property_description)): ?>
                  <div id="tab-1" class="tab-content current">
                    <?php echo wpautop($property_description); ?>
                  </div>
                <?php endif;

                if(!empty($property_location_map['lat']) && !empty($property_location_map['lng'])): 
                    $frame_url = 'https://maps.google.com/maps?q='.$property_location_map['lat'].','.$property_location_map['lng'].'&hl=es;z=14&amp;output=embed';
                  ?>
                  <div id="tab-2" class="tab-content map-cont">
                    <iframe src="<?php echo $frame_url; ?>" frameborder="0" style="border:0;" allowfullscreen></iframe>
                  </div>
                  <?php 
                endif;

                if(!empty($property_footer_plan)): 
                  $image = wp_get_attachment_image_src($property_footer_plan, 'full');
                  $property_footer_plan = $image[0];
                  ?>
                  <div id="tab-3" class="tab-content floorplan-cont">
                    <img src="<?php echo $property_footer_plan; ?>" alt="<?php basename($property_footer_plan); ?>"/> 
                  </div>
                <?php endif; 
            
                if(empty($school_checker_tab)):
                  ?>
                  <div id="tab-4" class="tab-content">
                    <div id="map" style="width:900px; height:600px;"></div>
                  </div>
                <?php  endif; ?>
              </div>
            </div>
          </div>
          <!-- Main Content Ends -->

          <?php 
          $agent_meta = get_post_meta($property_agent);

          $agent_name = $agent_email = $agent_phone = $agent_profile = '';
          
          if(!empty($agent_meta['email'][0])):
            $agent_email = $agent_meta['email'][0];
          endif;
          ?>
          <div class="w-25 f-left bg-gray res-w-100">
            <?php  if(!empty($agent_meta['profile_picture'][0])):
              $agent_profile =  $agent_meta['profile_picture'][0];
              $pro_pic_arr = wp_get_attachment_image_src($agent_profile, 'full');
              $agent_pro_pic = $pro_pic_arr[0];
              ?>
              <div class="profile-wrap">
                <img src="<?php echo $agent_pro_pic; ?>" alt="<?php echo basename($agent_pro_pic); ?>"/>
              </div>
            <?php endif; ?>
            <div class="agent-info">
              <?php if(!empty($agent_meta['name'][0])):
                $agent_name = $agent_meta['name'][0];
                ?>
                <p>Your agent for this property is:
                  <a href="<?php echo (!empty($agent_meta['email'][0])) ? 'mailto:'.$agent_meta['email'][0] : 'javascript:void(0);' ?>"><?php echo $agent_name; ?></a>
                </p>
              <?php endif; 
              if(!empty($agent_meta['phone_number'][0])):
                $agent_phone = $agent_meta['phone_number'][0];
                ?>
                <p>For more information on this property please call:
                  <a href="tel:<?php echo $agent_phone; ?>"><?php echo $agent_phone; ?></a>
                </p>
              <?php endif; ?>
              <p>Or fill in the form below and  the agent will contact you:
              </p>
            </div>

            <div class="contact-from-wrap">
              <fieldset>
                <form action="" method="post" id="agent_contact_form">
                  <div class="form-group">
                    <input type="text" id="name" name="txt_name" value="" placeholder="Name" class="input-field form-control ccm-input-text" required=""/>
                  </div>
                  <div class="form-group">
                    <input type="email" id="email" placeholder="Email" name="txt_email" value="" class="input-field form-control ccm-input-email" required=""/>
                  </div>
                  <div class="form-group">
                    <input type="tel" id="telephone" placeholder="Phone Number" name="txt_tel" value="" class="input-field form-control ccm-input-tel" required=""/>
                  </div>
                  <div class="form-group">
                    <textarea id="message" name="txt_message" placeholder="Message" rows="5" class="input-field input-field--textarea form-control" required=""></textarea>
                  </div>
                  <input type="hidden" id="property_link" name="property_link" value="<?php echo get_the_permalink(); ?>"/>
                  <div class="form-actions">
                    <button type="submit" name="Submit" id="btn_send_agent" class="button button--full btn-submit">Send</button>
                  </div>
                </form>
              </fieldset>
            </div>
          </div>

        </div>
      </div>
    </section>
  </div>

<?php

else:
  echo 'Nothing found';
endif;

get_footer();
