<?php
/**
 * Elementor Ultimo CHoose PLan Widget.
 *
 * Elementor widget that inserts an embbedable content into the page, from any given URL.
 *
 * @since 1.0.0
 */
namespace Wh_Elementor_Modules\Ultimo_Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Custom_Domain extends \Elementor\Widget_Base {

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
		return 'custom-domain';
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
		return __( 'Custom Domain', WH_ULTIMO_TEXT_DOMAIN );
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
		return 'fas fa-globe';
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
	 * Registers Titles For Conrtols Widget Titles
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
				'default' => __( 'Custom Domain', WH_ULTIMO_TEXT_DOMAIN ),
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'custom_description',
			[
				'label' => __( 'Description/Information', WH_ULTIMO_TEXT_DOMAIN ),
				'type' => \Elementor\Controls_Manager::WYSIWYG,
				'label_block' => true,
				'default' => __( 'You can use a custom domain with your website.', WH_ULTIMO_TEXT_DOMAIN ),
				'dynamic' => [
					'active' => true,
				],
			]
		);
		
		$this->add_control(
			'custom_domain_description',
			[
				'label' => __( 'Domain Description', WH_ULTIMO_TEXT_DOMAIN ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'label_block' => true,
				'default' => __( 'Point an A Record to the following IP Address ', WH_ULTIMO_TEXT_DOMAIN ),
				'dynamic' => [
					'active' => true,
				],
			]
		);
	}

	/**
	 * Registers Controls Widgets Content
	 *
	 * @return void
	 */
	protected function register_controls_widget_content() {
		$this->add_control(
			'custom_description_2',
			[
				'label' => __( 'Description/Information', WH_ULTIMO_TEXT_DOMAIN ),
				'type' => \Elementor\Controls_Manager::WYSIWYG,
				'label_block' => true,
				'dynamic' => [
					'active' => true,
				],
			]
		);
	}

	/**
	 * Registers Controls Pro Style Titles
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
	 * Registers Controls Pro Style Titles
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
	 * Registers Controls Widget Button Styles
	 *
	 * @return void
	 */
	protected function register_controls_widget_button_style(){
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
	 * Registers Controls PopUp Confirm Style
	 *
	 * @return void
	 */
	protected function register_controls_popup_confirm_style() {

		$this->start_controls_section(
			'waashero_ultimo_widget_popup_style',
			[
				'label' => __( 'Confirmation Popup', WH_ULTIMO_TEXT_DOMAIN ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,

			]
		);

		//pro text color control
		$this->add_control(
			'waashero_ultimo_widget_popup_bg_color',
			[
				'label' => __( 'Background Color', WH_ULTIMO_TEXT_DOMAIN ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#efefef',
				'selectors' => [
					'.wh-ultimo-notification-wrapper' => 'background-color: {{VALUE}} !important;',
				],
				'scheme' => [
					'type' => \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				],
			]
		);	

		$this->add_control(
			'waashero_ultimo_widget_popup_text_color',
			[
				'label' => __( 'Text Color', WH_ULTIMO_TEXT_DOMAIN ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#282828',
				'selectors' => [
					'.wh-ultimo-notification-title' => 'color: {{VALUE}} !important;',
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
	 * Register Custom Domain widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function _register_controls() {

		//start Content tab
		$this->start_controls_section(
			'content_section',
			[
				'label' => __( 'Content', WH_ULTIMO_TEXT_DOMAIN ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
		$this->register_controls_widget_title();
		$this->end_controls_section();

		//start Content tab
		$this->start_controls_section(
			'content_section_2',
			[
				'label' => __( 'Lower Content', WH_ULTIMO_TEXT_DOMAIN ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
		$this->register_controls_widget_content();
		$this->end_controls_section();

		//create style section-title
		$this->register_controls_pro_style_title();
		$this->register_controls_pro_style_text();
		$this->register_controls_widget_style();
		$this->register_controls_widget_button_style();
		$this->register_controls_popup_confirm_style();
	}
	
	/**
	 * Render widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	    
	protected function render() {
		$settings = $this->get_settings_for_display();
		$this->add_inline_editing_attributes( 'waashero_pro_text', 'none' );
		$messages = '';

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

		//TODO:: This way requires PHP 7.3. Maybe find better way to improve backwards comp?
		if( is_admin() ):
			global $blog_id;
			$blog_id = preg_replace('/[^0-9]/', '', \array_key_first($_COOKIE));
		endif;


		/**
		 * Current Plan
		 */
		$plan          = $subscription->get_plan();
		
		$enabled       = $plan->custom_domain;
		$custom_domain = wu_get_current_site()->get_meta('custom-domain');

		$domain = str_replace('http://', '', network_home_url());
		$domain = str_replace('https://', '', $domain);
		$domain = trim($domain, '/');

		?>

		<div class="waashero-module waashero-module-custom-domain">
				
			<div class="waashero-module-title waashero-ultimo-title"><div <?php echo $this->get_render_attribute_string( 'waashero_pro_text' ); ?>><?php echo $settings['waashero_pro_text']; ?></div></div>

		<form id="wp-ultimo-custom-domain-waashero" method="post">

		<ul class="wu_status_list">

		<div class="full">

			<?php !empty($settings['custom_description']) ? $desc = $settings['custom_description'] : $desc = __('You can use a custom domain with your website.', 'wp-ultimo'); ?>

			<p><?php echo $enabled ? '<div>'.$desc.'</div>'
			: __('Your plan does not support custom domains. You can upgrade your plan to have access to this feature.', 'wp-ultimo'); ?></p>

		</div> 

		<li class="full">
			<p>
			<input type="text" <?php disabled(!$enabled); ?> value="<?php echo $custom_domain; ?>" class="regular-text" name="custom-domain" placeholder="yourcustomdomain.com">
			</p>
		</li>

		<?php if ($enabled) : ?>
			<li class="full">
			<?php
			if( !empty($settings['custom_description_2']) ){ echo $settings['custom_description_2']; };
			?>
			</li>

		<li class="full">

			<p><?php if(!empty($settings['custom_domain_description'])):echo $settings['custom_domain_description'];
			printf(__(' <code>%s</code>.', 'wp-ultimo'), \WU_Settings::get_setting('network_ip') ? \WU_Settings::get_setting('network_ip') : $_SERVER['SERVER_ADDR']);  
			?>
			<br>

			<?php endif;

			
			?>

			</p>
		</li>   
		<li class="full">
			<p>
			<?php
			/**
			 * Add extra elements
			 * @since 1.7.3
			 */
			do_action('wu_custom_domain_after', $custom_domain);

			?>
			</p>
			</li>
		<?php endif; ?> 

		<li class="full">
			<p class="sub">

			<button id="wu-custom-domain-submit" data-target="#wu-custom-domain" data-title="<?php _e('Are you sure?', 'wp-ultimo'); ?>" data-text="<?php echo esc_html( \WU_Settings::get_setting('domain-mapping-alert-message')); ?>" data-form="true" <?php disabled(!$enabled); ?> name="wu-action-save-custom-domain" class="waashero-ultimo-action-btn waashero-custom-domain-action wu-confirm button <?php echo $enabled ? " waashero-custom-domain-action-style" : ''; ?>" type="submit" style="display:none;">
			</button>

			</p>
		</li>

		</ul>

		<?php $enabled ? wp_nonce_field('wu-save-custom-domain') : ''; ?>

		</form>
		<button id="wu-custom-domain" data-target="#wu-custom-domain" data-title="<?php _e('Are you sure?', 'wp-ultimo'); ?>" data-text="<?php echo esc_html( \WU_Settings::get_setting('domain-mapping-alert-message')); ?>" data-form="true" <?php disabled(!$enabled); ?> name="wu-action-save-custom-domain" class="waashero-ultimo-action-btn waashero-custom-domain-action <?php echo $enabled ? " waashero-custom-domain-action-style" : ''; ?>" type="submit" >
				<?php _e('Set Custom Domain', 'wp-ultimo'); ?>
			</button>
		</div>
		<?php endif; ?>

		<script type="text/javascript">

			jQuery('#wu-custom-domain').on('click', function (e) {
				e.preventDefault();
				var n = jQuery(this);
				Swal.fire({
					titleText: n.data("title"),
					text: n.data("text"),
					icon: "warning",
					showCancelButton: true,
					confirmButtonColor: "#DD6B55",
					confirmButtonText: 'Confirm',
					cancelButtonText: 'Cancel',
				}).then((result) => {
					if (result.value) {
						jQuery('#wu-custom-domain-submit').click();
					}
				})
			});
		</script>
	<?php		
		if ( !is_admin() ):
			$messages = WP_Ultimo()->get_messages( is_network_admin() );
			foreach ( $messages as $message ):

				if( !empty( $message ) && $message['message'] ): 

					if( $message['type'] == 'error' ):
						$icon = "warning";
					else:
						$icon = "info";
					endif;
					?>
					<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
					<script type="text/javascript">

					const Toast = Swal.mixin({
						toast: true,
						position: 'top-end',
						showConfirmButton: false,
						timer: 6000,
						timerProgressBar: true,
						customClass: {
							container: 'wh-ultimo-notification-wrapper',
							title: 'wh-ultimo-notification-title',
						},
					onOpen: (toast) => {
						toast.addEventListener('mouseenter', Swal.stopTimer)
						toast.addEventListener('mouseleave', Swal.resumeTimer)
						}
					})

					Toast.fire({
						icon: '<?php echo $icon; ?>',
						title: '<?php echo $message['message']; ?>'
					})

				</script> 
				<?php endif;
			endforeach;
		endif;
	}	
}
?>
