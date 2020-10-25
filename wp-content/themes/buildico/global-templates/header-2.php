<?php
/**
 *  This is the header one style
 *
 * Displays header one style
 *
 * @package buildico
 */
if( get_post_meta( get_the_ID(), "custom_page_options", true ) ){
     $buildico_page_settings = get_post_meta( get_the_ID(), "custom_page_options", true );
}else{
     $buildico_page_settings = array();
}

if( ! empty( $buildico_page_settings['header_color_select'] )){
     if( 'dark-header' === $buildico_page_settings['header_color_select'] ){
         $buildico_header_bg_color = $buildico_page_settings['header_color_select'] . ' light ';
     }else{
         $buildico_header_bg_color = '';
     }
}else{
     if( 'dark-header' ===  wt_get_option('header_bg_color')){
         $buildico_header_bg_color = wt_get_option('header_bg_color') . ' light ';
     }else{
         $buildico_header_bg_color = '';
     }
}
$buildico_top_header_info = wt_get_option('header_two_top');
$buildico_mid_header_info = wt_get_option('middle_header_content');
?>
<header id="header" class="header-section header-two <?php echo esc_attr( $buildico_header_bg_color ); ?>">
    <?php if(wt_get_option('hidetop_bar') === true) : ?>
    <div class="header-top">
        <div class="container">
            <div class="clearfix">
                <?php if( ! empty( $buildico_top_header_info ) ) : ?>
                <div class="top-left pull-left">
                    <ul class="top-h-list">
                        <?php if( ! empty( $buildico_top_header_info['b_item1_value'] ) ) : ?>
                        <li>
                            <?php if( ! empty( $buildico_top_header_info['b_item1_label'] ) ) : ?>
                            <span><?php echo esc_html($buildico_top_header_info['b_item1_label']); ?> :</span>
                            <?php endif; ?>
                            <?php echo esc_html($buildico_top_header_info['b_item1_value']); ?>
                        </li>
                        <?php endif; ?>
                        <?php if( ! empty( $buildico_top_header_info['b_item2_value'] ) ) : ?>
                        <li>
                            <?php if( ! empty( $buildico_top_header_info['b_item2_label'] ) ) : ?>
                            <span><?php echo esc_html($buildico_top_header_info['b_item2_label']); ?> :</span>
                            <?php endif; ?>
                            <?php echo esc_html($buildico_top_header_info['b_item2_value']); ?>
                        </li>
                        <?php endif; ?>
                        <?php if( ! empty( $buildico_top_header_info['b_item3_value'] ) ) : ?>
                        <li>
                            <?php if( ! empty( $buildico_top_header_info['b_item3_label'] ) ) : ?>
                            <span><?php echo esc_html($buildico_top_header_info['b_item3_label']); ?> :</span>
                            <?php endif; ?>
                            <?php echo esc_html($buildico_top_header_info['b_item3_value']); ?>
                        </li>
                        <?php endif; ?>
                    </ul>
                </div>
                <?php endif; ?>
                <?php
                $buildico_socials = wt_get_option('social_lists');
                if( ! empty( $buildico_socials ) ) : ?>
                <div class="top-right text-right pull-right">
                    <ul class="top-h-social">
                        <?php foreach ( $buildico_socials as  $buildico_social ) {
                            echo '<li><a href="'. esc_url($buildico_social['social_link']) .'"><i class="'. esc_attr($buildico_social['social_icon']) .'"></i></a></li>';
                        }
                        ?>
                    </ul>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>
    <div class="mid-header">
        <div class="container">
            <div class="clearfix">
                <div class="brand pull-left">
                    <!-- Your site title as branding in the menu -->
                    <?php if( !empty( $buildico_page_settings['custom_logo'] ) ) : ?>
                        <?php
                        $logo_url = wp_get_attachment_image_src( $buildico_page_settings['custom_logo'], '180x40-logo' ); ?>
                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="navbar-brand" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>"><img src="<?php echo esc_url( $logo_url[0] ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>"></a>
                    <?php else : ?>
                    <?php if ( ! has_custom_logo() ) { ?>

                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="navbar-brand" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>"><img src="<?php echo get_template_directory_uri(); ?>/assets/img/logo-dark.png" alt="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>"></a>

                    <?php } else {

                        the_custom_logo();

                    } ?><!-- end custom logo -->
                    <?php endif; ?>
                </div><!-- /.brand -->
                <?php if( ! empty( $buildico_mid_header_info ) ) : ?>
                <div class="pull-right header-contact-wrap">
                    <ul class="header-contact-info">
                        <li>
                            <?php if( ! empty( $buildico_mid_header_info['bm_quote_icon'] ) ){
                                echo '<i class="'. esc_attr( $buildico_mid_header_info['bm_quote_icon'] ) .'"></i>';
                            }else{
                                echo '<i class="ti-email"></i>';
                            } ?>
                            <span><?php echo esc_html($buildico_mid_header_info['bm_sub_head1']); ?></span>
                            <a href="mailto:<?php echo esc_attr( $buildico_mid_header_info['bm_heading_link1'] ); ?>"><?php echo esc_html( $buildico_mid_header_info['bm_heading1'] ); ?></a>
                        </li>
                        <li>
                            <?php if( ! empty( $buildico_mid_header_info['bm_phone_icon'] ) ){
                                echo '<i class="'. esc_attr( $buildico_mid_header_info['bm_phone_icon'] ) .'"></i>';
                            }else{
                                echo '<i class="ti-headphone-alt"></i>';
                            } ?>

                            <span><?php echo esc_html($buildico_mid_header_info['bm_phone_label']); ?></span>
                            <a href="tel:<?php echo esc_attr( $buildico_mid_header_info['bm_phone_number'] ); ?>"><?php echo esc_html( $buildico_mid_header_info['bm_phone_number'] ); ?></a>
                        </li>
                    </ul>
                </div>
                <?php else : ?>
                <div class="pull-right header-contact-wrap">
                    <ul class="header-contact-info">
                        <li>
                            <i class="ti-email"></i>
                            <span><?php echo esc_html__('Want an approximate price?', 'buildico'); ?></span>
                            <a href="mailto:<?php echo esc_attr('wowthemez@gmail.com'); ?>"><?php echo esc_html__('Ge a free quote', 'buildico'); ?></a>
                        </li>
                        <li>
                            <i class="ti-headphone-alt"></i>
                            <span><?PHP echo esc_html__('Call us now', 'buildico'); ?></span>
                            <a href="tel:<?php echo esc_attr__('+01 234 56789', 'buildico'); ?>"><?php echo esc_html__('+01 234 56789', 'buildico'); ?></a>
                        </li>
                    </ul>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php if( has_nav_menu( 'primary' ) ) : ?>
    <div class="main-header main-menu-top">
        <div class="container">
            <div class="clearfix main-header-inner">
                <div class="navigation-menu pull-left">
                    <?php
                        if( ! empty( $buildico_page_settings['custom_menu'] ) ){
                            wp_nav_menu(
                                array(
                                    'theme_location'  => 'primary',
                                    'container_class' => 'mainmenu',
                                    'container_id'    => '',
                                    'menu_class'      => 'main-menu',
                                    'fallback_cb'     => '',
                                    'menu_id'         => 'navi-menu',
                                    'menu'         => $buildico_page_settings['custom_menu'],
                                    'depth'           => 2
                                )
                            );
                        }else{
                            wp_nav_menu(
                                array(
                                    'theme_location'  => 'primary',
                                    'container_class' => 'mainmenu',
                                    'container_id'    => '',
                                    'menu_class'      => 'main-menu',
                                    'fallback_cb'     => '',
                                    'menu_id'         => 'navi-menu',
                                    'depth'           => 2
                                )
                            );
                        }
                    ?>
                </div>
                <?php if( wt_get_option('hideheader_search_icon') === true || !empty(wt_get_option('woo_cart_option'))) : ?>
                <div class="header-right pull-right d-flex align-items-center">
                <?php if( wt_get_option('hideheader_search_icon') === true ) : ?>
                <div id="search-trigger" class="header-search-btn "><i class="fa fa-search"></i></div>
                <?php endif; ?>
                <?php
					$buildico_cart = wt_get_option('woo_cart_option');
					if ( class_exists( 'WooCommerce' ) ) {
						if( 'show_all' === $buildico_cart ){
							buildico_woo_header_cart();
						}elseif( 'show_only_shop' === $buildico_cart ){
							if( is_woocommerce() || is_cart() || is_checkout() || is_account_page() ){
								buildico_woo_header_cart();
							}
						}
					}
				?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>
</header>

<?php if( has_nav_menu( 'primary' ) && wt_get_option('fixed_header') === true ) : ?>
<div id="fixed-header" class="main-header fixed-header <?php echo esc_attr( $buildico_header_bg_color ); ?>">
    <div class="container">
        <div class="clearfix fixed-header-inner">
            <div class="brand pull-left">
                <!-- Your site title as branding in the menu -->
                <?php if( ! empty( $buildico_page_settings['custom_logo'] ) || ! empty($buildico_page_settings['sticky_header_logo']) ) : ?>
                    <?php
                    if( ! empty($buildico_page_settings['sticky_header_logo']) ){
                        $logo_url = wp_get_attachment_image_src( $buildico_page_settings['sticky_header_logo'], '180x40-logo' );
                    }elseif ( ! empty($buildico_page_settings['custom_logo']) ) {
                        $logo_url = wp_get_attachment_image_src( $buildico_page_settings['custom_logo'], '180x40-logo' );
                    }else{
                        $logo_url = array();
                    }
                    ?>
                    <?php if(!empty($logo_url)): ?>
                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="navbar-brand" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>"><img src="<?php echo esc_url( $logo_url[0] ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>"></a>
                    <?php else : ?>
                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="navbar-brand" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>"><?php echo esc_html( get_bloginfo( 'name', 'display' ) ); ?></a>
                    <?php endif; ?>
                <?php else : ?>
                <?php if ( ! has_custom_logo() ) { ?>

                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="navbar-brand" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>"><img src="<?php echo get_template_directory_uri(); ?>/assets/img/logo-dark.png" alt="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>"></a>

                <?php } else {

                    the_custom_logo();

                } ?><!-- end custom logo -->
                <?php endif; ?>
            </div><!-- /.brand -->
            <div class="navigation-menu pull-right">
                <?php
                    if( ! empty( $buildico_page_settings['custom_menu'] ) ){
                        wp_nav_menu(
                            array(
                                'theme_location'  => 'primary',
                                'container_class' => 'mainmenu',
                                'container_id'    => '',
                                'menu_class'      => 'main-menu',
                                'fallback_cb'     => '',
                                'menu_id'         => 'navi-menu',
                                'menu'         => $buildico_page_settings['custom_menu'],
                                'depth'           => 2
                            )
                        );
                    }else{
                        wp_nav_menu(
                            array(
                                'theme_location'  => 'primary',
                                'container_class' => 'mainmenu',
                                'container_id'    => '',
                                'menu_class'      => 'main-menu',
                                'fallback_cb'     => '',
                                'menu_id'         => 'navi-menu',
                                'depth'           => 2
                            )
                        );
                    }
                ?>
                <?php if( wt_get_option('hideheader_search_icon') === true || !empty(wt_get_option('woo_cart_option'))) : ?>
                <div class="header-right pull-right d-flex align-items-center">
                <?php if( wt_get_option('hideheader_search_icon') === true ) : ?>
                <div id="fixed-header-search-trigger" class="header-search-btn "><i class="fa fa-search"></i></div>
                <?php endif; ?>
                <?php
					$buildico_cart = wt_get_option('woo_cart_option');
					if ( class_exists( 'WooCommerce' ) ) {
						if( 'show_all' === $buildico_cart ){
							buildico_woo_header_cart();
						}elseif( 'show_only_shop' === $buildico_cart ){
							if( is_woocommerce() || is_cart() || is_checkout() || is_account_page() ){
								buildico_woo_header_cart();
							}
						}
					}
				?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
