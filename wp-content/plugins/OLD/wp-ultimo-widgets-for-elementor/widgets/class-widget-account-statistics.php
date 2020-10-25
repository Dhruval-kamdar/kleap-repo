<?php
/**
 * Elementor Ultimo Account Statistics Widget.
 *
 * Elementor widget that inserts an embbedable content into the page, from any given URL.
 *
 * @since 1.0.0
 */
namespace Wh_Elementor_Modules\Ultimo_Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Account_Stats extends \Elementor\Widget_Base {

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
		return 'account-stats';
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
		return __( 'Account Statistics', WH_ULTIMO_TEXT_DOMAIN );
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
		return 'fas fa-chart-pie';
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
				'default' => __( 'Account Statistics', WH_ULTIMO_TEXT_DOMAIN ),
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
	protected function register_controls_pro_style_text(){
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
				'selector' => '{{WRAPPER}} .waashero-module',
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
	 * Registers Controls Widget Button Style
	 *
	 * @return void
	 */
	protected function register_controls_widget_button_style() {

		$this->start_controls_section(
			'waashero_ultimo_widget_button_style',
			[
				'label' => __( 'Button', WH_ULTIMO_TEXT_DOMAIN ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,

			]
		);


		//pro text color control
		$this->add_control(
			'waashero_ultimo_widget_btn_color',
			[
				'label' => __( 'Button Color', WH_ULTIMO_TEXT_DOMAIN ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#677ADA',
				'selectors' => [
					'{{WRAPPER}} .waashero-ultimo-action-btn' => 'background-color: {{VALUE}};',
				],
				'scheme' => [
					'type' => \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				],
			]
		);	

		//pro text color control
		$this->add_control(
			'waashero_ultimo_widget_btn_txt_color',
			[
				'label' => __( 'Text Color', WH_ULTIMO_TEXT_DOMAIN ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#efefef',
				'selectors' => [
					'{{WRAPPER}} .waashero-ultimo-action-btn' => 'color: {{VALUE}};',
				],
				'scheme' => [
					'type' => \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				],
			]
		);

		$this->add_control(
			'waashero_ultimo_widget_btn_padding',
			[
				'label' => __( 'Width', 'plugin-name' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
		
				'selectors' => [
					'{{WRAPPER}} .waashero-ultimo-action-btn' => 'width: {{SIZE}}%;',
				],
			]
		);

		//pro text typography
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'waashero_ultimo_btn_typography',
				'selector' => '{{WRAPPER}} .waashero-ultimo-action-btn',
					'scheme' => \Elementor\Scheme_Typography::TYPOGRAPHY_1,
			]
		);

		//pro text alignment
		$this->add_control(
			'waashero_ultimo_btn_txt_align',
			[
				'label' => __( 'Text Alignment', WH_ULTIMO_TEXT_DOMAIN ),
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
				'default' =>'center',
				'selectors' => [
					'{{WRAPPER}} .waashero-ultimo-action-btn' => 'text-align: {{VALUE}};',
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
		$this->register_controls_widget_button_style();

	}
    
	/**
	 * Pro Renders
	 *
	 * @param [type] $settings
	 * @param [type] $subscription
	 * @param [type] $site_trial
	 * @return void
	 */
	protected function pro_render($settings, $subscription, $site_trial){
	
		?>
		<div id="wp-ultimo-account-stats" >

			<ul class="wu_status_list">

			<?php
			/**
			* Current Plan 
			*/
			$plan = $subscription->get_plan();
			?>
			<li class="current-plan" style="<?php echo $site_trial ? 'width: 50%' : ''; ?>">
				<div>
					<strong><?php echo $plan->title; ?></strong><box class="account-stats-field-desc"><?php _e('your current plan', 'wp-ultimo'); ?></box>			
				</div>
			</li>

			<?php
			/**
			* Trial Status
			*/
			if ( $site_trial ) :
			?>
			<li class="trial" style="<?php echo $site_trial ? 'width: 50%' : ''; ?>">
				<div>
					<strong><?php printf(_n('%s day', '%s days', $site_trial, 'wp-ultimo'), $site_trial); ?></strong><box class="account-stats-field-desc"><?php _e('remaining time in trial', 'wp-ultimo'); ?></box>
				</div>
			</li>
			<?php endif; ?>

			<?php
			/**
			* Spaced Used
			*/
			$space_used      = get_space_used();
			$space_allowed   = get_space_allowed() ?: 1;
			$percentage      = ceil($space_used / $space_allowed * 100);
			$unlimited_space = get_site_option( 'upload_space_check_disabled' ); 
			$message = $unlimited_space ? '%s' : '%s / %s (%s%s)';

			?>
			<li class="space-used">
				<div>
					<strong><?php printf($message, \WU_Util::format_megabytes($space_used), \WU_Util::format_megabytes($space_allowed), $percentage, '%'); ?></strong> <box class="account-stats-field-desc"><?php _e('space used', 'wp-ultimo'); ?></box>
				</div>
			</li>

		<?php
		/**
		* Users
		*/
		$users_quota = $plan->get_quota('users') + 1;
		$users       = wu_get_current_site()->get_user_count();
		$url         = admin_url('users.php');
		$unlimited = $plan->should_allow_unlimited_extra_users();

		?>
		<li class="total-users">
			<a href="<?php echo $url ?>">
			<strong><?php printf(__('%s / %s', 'wp-ultimo'), $users, $unlimited ? __('Unlimited', 'wp-ultimo') : $users_quota); ?></strong> <box class="account-stats-field-desc"><?php _e('users', 'wp-ultimo'); ?></a></box>
		</li>

		</ul>

		<?php if ( wu_get_current_site()->is_user_owner() ) : ?>
		<ul class="wu-button-upgrade-account">
			<li class="upgrade-account">
				<p>
					<a class="button button-primary waashero-account-stats-action waashero-ultimo-action-btn" href="<?php echo admin_url('admin.php?page=wu-my-account'); ?>">
						<strong><?php _e('See Account Summary', 'wp-ultimo'); ?></strong>
					</a>
				</p>
			</li>
		</ul>
	<?php endif; ?>
	</div>
			
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
		$subscription = wu_get_current_site()->get_subscription();

		if( !$subscription ): ?>

		<div class="waashero-module" >
		<div class="waashero-module-title waashero-ultimo-title"><div <?php echo $this->get_render_attribute_string( 'waashero_pro_text' ); ?>><?php echo $settings['waashero_pro_text']; ?></div></div>
		<p class="wh-ultimo-no-sub">No Active Subscription Found.</p>
		</div>

		<?php else:


		$site_trial = $subscription->get_trial();
		?>
		<div class="waashero-module" >
		

		<div class="waashero-module-title waashero-ultimo-title"><div <?php echo $this->get_render_attribute_string( 'waashero_pro_text' ); ?>><?php echo $settings['waashero_pro_text']; ?></div></div>
		
		<?php
	   
			$this->pro_render( $settings, $subscription, $site_trial );
		
		?>
		</div>
		<?php endif;
	}	
}
?>
