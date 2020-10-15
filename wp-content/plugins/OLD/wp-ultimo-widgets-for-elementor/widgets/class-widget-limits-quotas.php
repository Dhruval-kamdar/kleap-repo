<?php
/**
 * Elementor Ultimo Limits and Quotas Widget.
 *
 * Elementor widget that inserts an embbedable content into the page, from any given URL.
 *
 * @since 1.0.0
 */
namespace Wh_Elementor_Modules\Ultimo_Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Limits_Quotas extends \Elementor\Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve Gallery widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'limits-quotas';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve Gallery widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Limits And Quotas', WH_ULTIMO_TEXT_DOMAIN );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve Gallery widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'far fa-chart-bar';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the oEmbed widget belongs to.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'wp-ultimo' ];
	}

	/**
	 * Registers Controls Widget Title
	 *
	 * @return void
	 */
    protected function register_controls_widget_title() {
		$this->add_control(
			'waashero_pro_text',
			[
				'label' => __( 'Title', WH_ULTIMO_TEXT_DOMAIN ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'label_block' => true,
				'default' => __( 'Limits And Quotas', WH_ULTIMO_TEXT_DOMAIN ),
				'dynamic' => [
					'active' => true,
				],
			]
		);
		
	}

	/**
	 * Registers Controls Pro Style Title
	 *
	 * @return void
	 */
	protected function register_controls_pro_style_title() {
		
		$this->start_controls_section(
			'waashero_pro_style_text_title',
			[
				'label' => __( 'Title Text', WH_ULTIMO_TEXT_DOMAIN ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,

			]
		);

		//pro text color control
		$this->add_control(
			'waashero_pro_text_color_title',
			[
				'label' => __( 'Color', WH_ULTIMO_TEXT_DOMAIN ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#484848',
				'selectors' => [
					'{{WRAPPER}} .waashero-ultimo-title' => 'color: {{VALUE}};',
				],
				'scheme' => [
					'type' => \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				],
			]
		);

		//pro text typography
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'waashero_pro_text_typography_title',
				'selector' => '{{WRAPPER}} .waashero-ultimo-title',
				 'scheme' => \Elementor\Scheme_Typography::TYPOGRAPHY_1,
			]
		);

		//pro text alignment
		$this->add_responsive_control(
			'waashero_pro_text_align_title',
			[
				'label' => __( 'Alignment', WH_ULTIMO_TEXT_DOMAIN ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', WH_ULTIMO_TEXT_DOMAIN ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', WH_ULTIMO_TEXT_DOMAIN ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => __( 'Right', WH_ULTIMO_TEXT_DOMAIN ),
						'icon' => 'fa fa-align-right',
					],
					'justify' => [
						'title' => __( 'Justified', WH_ULTIMO_TEXT_DOMAIN ),
						'icon' => 'fa fa-align-justify',
					],
				],
				'default' =>'',
				'selectors' => [
					'{{WRAPPER}} .waashero-ultimo-title' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
	}
	
	/**
	 * Registers Controls Pro Style Text
	 *
	 * @return void
	 */
	protected function register_controls_pro_style_text() {

		$this->start_controls_section(
			'waashero_pro_style_text',
			[
				'label' => __( 'Main Text', WH_ULTIMO_TEXT_DOMAIN ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,

			]
		);

		//pro text color control
		$this->add_control(
			'waashero_pro_text_color',
			[
				'label' => __( 'Color', WH_ULTIMO_TEXT_DOMAIN ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#525252',
				'selectors' => [
					'{{WRAPPER}} .waashero-module' => 'color: {{VALUE}};',
				],
				'scheme' => [
					'type' => \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				],
			]
		);

		//pro text typography
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'waashero_pro_text_typography',
				'selector' => '#wp-ultimo-quotas-waashero .wu_status_list li p',
				 'scheme' => \Elementor\Scheme_Typography::TYPOGRAPHY_1,
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Registers Controls Widget Style
	 *
	 * @return void
	 */
	protected function register_controls_widget_style() {

		$this->start_controls_section(
			'waashero_ultimo_widget_style',
			[
				'label' => __( 'Widget Colors', WH_ULTIMO_TEXT_DOMAIN ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,

			]
		);

		//pro text color control
		$this->add_control(
			'waashero_ultimo_widget_header_color',
			[
				'label' => __( 'Header Background Color', WH_ULTIMO_TEXT_DOMAIN ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#e0dede',
				'selectors' => [
					'{{WRAPPER}} .waashero-ultimo-title' => 'background-color: {{VALUE}};',
				],
				'scheme' => [
					'type' => \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				],
			]
		);	

		//pro text color control
		$this->add_control(
			'waashero_ultimo_widget_color',
			[
				'label' => __( 'Background Color', WH_ULTIMO_TEXT_DOMAIN ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#efefef',
				'selectors' => [
					'{{WRAPPER}} .waashero-module' => 'background-color: {{VALUE}};',
				],
				'scheme' => [
					'type' => \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				],
			]
		);


		$this->end_controls_section();
	}


	

	/**
	 * Register Gallery widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function _register_controls() {

	//	start Content tab
		$this->start_controls_section(
			'content_section',
			[
				'label' => __( 'Content', WH_ULTIMO_TEXT_DOMAIN ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);


		//add gallery control
		$this->register_controls_widget_title();

		$this->end_controls_section();
		// end content tab

		//create style section-title
		$this->register_controls_pro_style_title();
		$this->register_controls_pro_style_text();
		$this->register_controls_widget_style();
	}
    
	/**
	 * Pro Renders
	 *
	 * @param [type] $settings
	 * @param [type] $plan
	 * @param [type] $post_types
	 * @param [type] $subscription
	 * @return void
	 */
	protected function pro_render($settings, $plan, $post_types,$subscription ) {
	
		?>
		<ul class="wu_status_list">
  			<li class="quotas">
    
    	<?php

    	foreach( $post_types as $post_type_slug => $post_type ) :

			/**
			 * @since 1.5.4 Check the post type
			 */
			if ( $plan->is_post_type_disabled( $post_type_slug ) ) continue;

				if ( $plan->get_quota( $post_type_slug ) !== false ) :

					$post_count = wp_count_posts( $post_type_slug );

					if (!empty( ( array ) $post_count ) ) {

					$post_count_status = apply_filters( 'wu_post_count_status', array( 'publish' ), $post_type );

					$post_count = $post_type_slug === 'attachment' ? $post_count->inherit : \WU_Plans_Limits::get_post_count($post_count, $post_type_slug);

					} else {

					$post_count = 0;

					}

					// Filter Post Count for custom added things
					$post_count = apply_filters( 'wu_post_count', $post_count, $post_type_slug );

					// Calculate width
					if ( $plan->get_quota( $post_type_slug ) == 0 ) {
						$width = 1;
					} else {
						$width = ( $post_count / $plan->get_quota( $post_type_slug ) * 100 );
					}

				?>

				<?php if ( $plan->should_display_quota( $post_type_slug ) ) : ?>

				<p class="quota">
				<?php echo $post_type->label; ?>
				<span class="bar-trail">
					<span class="bar-line" style="width: <?php echo $width; ?>%;"></span>
				</span>
				<small><?php echo $post_count; ?> / <?php echo $plan->get_quota( $post_type_slug ) == 0 ? __( 'Unlimited', 'wp-ultimo' ) : $plan->get_quota( $post_type_slug ) ; ?></small>
				</p>

			<?php endif; 
		endif; 
	endforeach; ?>

    <?php

    if ( \WU_Settings::get_setting('enable_multiple_sites')) :

      /**
       * Get things necessary to display the Sites Limit
       */
      //$subscription = wu_get_current_site()->get_subscription();
      
      $sites_count = $subscription ? $subscription->get_site_count() : 1;

      $width = (!$plan->get_quota('sites')) ? 1 : $sites_count / $plan->get_quota('sites') * 100;

    ?>

    <?php if ($plan->should_display_quota('sites')) : ?>

    <p class="quota">
      	<?php _e('Sites', 'wp-ultimo') ?>
      	<span class="bar-trail">
        	<span class="bar-line" style="width: <?php echo $width; ?>%;"></span>
      	</span>
      	<small><?php echo $sites_count; ?> / <?php echo $plan->get_quota('sites') == 0 ? __('Unlimited', 'wp-ultimo') : $plan->get_quota('sites') ; ?></small>
    </p>

    <?php  endif; endif; ?>

    <?php if ( $plan->should_display_quota( 'visits' ) ) :

		$next_reset = wu_get_current_site()->get_visit_count_reset_date();
		
		$visits_count = (int) wu_get_current_site()->get_meta('visits_count');
		$visits_width = (!$plan->get_quota('visits')) ? 1 : $visits_count / $plan->get_quota('visits') * 100;

		$reset_visits_link = current_user_can('manage_network') ? sprintf(' - <a href="%s">%s</a>', admin_url('?action=wu_reset_visit_counter'), __('Reset Visit Counter (only you see this link)', 'wp-ultimo')) : '';

    ?>

    <p class="quota">
       <?php _e('Visits (this month)', 'wp-ultimo') ?>
       <span class="bar-trail">
         <span class="bar-line" style="width: <?php echo $visits_width; ?>%;"></span>
       </span>
       <small><?php echo number_format($visits_count); ?> / <?php echo $plan->get_quota('visits') == 0 ? __('Unlimited', 'wp-ultimo') : number_format($plan->get_quota('visits')) ; ?> - <?php printf(__('Next Reset: %s', 'wp-ultimo'), $next_reset); ?><?php echo $reset_visits_link; ?></small>
    </p>

    <?php  endif; ?>

    </li>

    <?php if (
              ( \WU_Settings::get_setting('allow_template_switching') || current_user_can('manage_network') ) && 
              \WU_Settings::get_setting('allow_template') && 
              is_array( \WU_Settings::get_setting('templates')) && 
              !empty( \WU_Settings::get_setting('templates')) &&
              !empty( \WU_Site_Hooks::get_available_templates(false))
    ) : ?>
      	<li class="quotas">
        	<p style="overflow: hidden;">
          		<a href="<?php echo admin_url('admin.php?page=wu-new-template'); ?>" class="button button-primary pull-right"><?php _e('Switch Template', 'wp-ultimo'); ?> <?php echo \WU_Util::tooltip( __('Use this action to select a new starter template from the catalog. If you do decide to switch, all you data and customizations will be replaced with the data from the new template.', 'wp-ultimo') ); ?></a> 
        	</p>
      	</li>
    	<?php endif; ?>

  		</ul>
  
	</ul>
		  
	<?php 
		
	}

	
	/**
	 * Render Gallery widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	    
	protected function render() {

		$settings = $this->get_settings_for_display();
		$this->add_inline_editing_attributes( 'waashero_pro_text', 'none' );

		/**
		 * Get the subscription
		 */
		$subscription = $subscription = wu_get_current_site()->get_subscription();

		if( !$subscription ): ?>

		<div class="waashero-module waashero-account-actions" >
			<div class="waashero-module-title waashero-ultimo-title"><div <?php echo $this->get_render_attribute_string( 'waashero_pro_text' ); ?>><?php echo $settings['waashero_pro_text']; ?></div></div>
			<p class="wh-ultimo-no-sub">No Active Subscription Found.</p>
		</div>

		<?php else:

		/**
		 * Current Plan
		 */
		$plan = $subscription->get_plan();
			
		$post_types = wp_cache_get( 'wu_post_types' );
		if ( false === $post_types ) {
			$post_types = get_post_types(array('public' => true), 'objects');
			$post_types = apply_filters('wu_get_post_types', $post_types);
			wp_cache_set( 'wu_post_types', $post_types );
		} 
			
			
		?>

		<div id="wp-ultimo-quotas-waashero" class="waashero-module waashero-module-limits-quotas">

		<div class="waashero-module-title waashero-ultimo-title"><div <?php echo $this->get_render_attribute_string( 'waashero_pro_text' ); ?>><?php echo $settings['waashero_pro_text']; ?></div></div>
		
		<?php
	   
			$this->pro_render($settings, $plan, $post_types, $subscription );
		
		?>
		</div>
		<?php endif;
	}	
}
?>
