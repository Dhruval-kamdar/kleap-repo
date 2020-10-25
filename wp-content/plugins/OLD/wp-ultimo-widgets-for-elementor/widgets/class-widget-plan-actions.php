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

class Plan_Actions extends \Elementor\Widget_Base {

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
		return 'plan-actions';
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
		return __( 'Account Actions', WH_ULTIMO_TEXT_DOMAIN );
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
		return 'fas fa-exclamation-triangle';
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
				'default' => __( 'Account Actions', WH_ULTIMO_TEXT_DOMAIN ),
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'waashero_pro_text_remove_site',
			[
				'label' => __( 'Remove Site Button', WH_ULTIMO_TEXT_DOMAIN ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'label_block' => true,
				'default' => __( 'Remove Site', WH_ULTIMO_TEXT_DOMAIN ),
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'waashero_pro_text_delete_plan',
			[
				'label' => __( 'Delete Plan Button', WH_ULTIMO_TEXT_DOMAIN ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'label_block' => true,
				'default' => __( 'Delete Account', WH_ULTIMO_TEXT_DOMAIN ),
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
	protected function register_controls_widget_style(){
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
				'label' => __( 'Delete Account Button', WH_ULTIMO_TEXT_DOMAIN ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,

			]
		);

		//pro text color control
		$this->add_control(
			'waashero_ultimo_widget_btn_color',
			[
				'label' => __( 'Button Color', WH_ULTIMO_TEXT_DOMAIN ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#CE5B5B',
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
	 * Registers Controls Widget Button Style Remove
	 *
	 * @return void
	 */
	protected function register_controls_widget_button_style_remove() {

		$this->start_controls_section(
			'waashero_ultimo_widget_button_style_remove',
			[
				'label' => __( 'Remove Site Button', WH_ULTIMO_TEXT_DOMAIN ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,

			]
		);


		//pro text color control
		$this->add_control(
			'waashero_ultimo_widget_btn_color_remove',
			[
				'label' => __( 'Button Color', WH_ULTIMO_TEXT_DOMAIN ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#CE5B5B',
				'selectors' => [
					'{{WRAPPER}} .waashero-ultimo-action-btn-remove' => 'background-color: {{VALUE}};',
				],
				'scheme' => [
					'type' => \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				],
			]
		);	

		//pro text color control
		$this->add_control(
			'waashero_ultimo_widget_btn_txt_color_remove',
			[
				'label' => __( 'Text Color', WH_ULTIMO_TEXT_DOMAIN ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#efefef',
				'selectors' => [
					'{{WRAPPER}} .waashero-ultimo-action-btn-remove' => 'color: {{VALUE}};',
				],
				'scheme' => [
					'type' => \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				],
			]
		);

		$this->add_control(
			'waashero_ultimo_widget_btn_padding_remove',
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
					'{{WRAPPER}} .waashero-ultimo-action-btn-remove' => 'width: {{SIZE}}%;',
				],
			]
		);

		//pro text typography
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'waashero_ultimo_btn_typography_remove',
				'selector' => '{{WRAPPER}} .waashero-ultimo-action-btn-remove',
					'scheme' => \Elementor\Scheme_Typography::TYPOGRAPHY_1,
			]
		);

		//pro text alignment
		$this->add_control(
			'waashero_ultimo_btn_txt_align_remove',
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
					'{{WRAPPER}} .waashero-ultimo-action-btn-remove' => 'text-align: {{VALUE}};',
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

		//start Content tab
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

		//create style section-text
		$this->register_controls_pro_style_text();

		//create style section-main-style
		$this->register_controls_widget_style();

		//create style section-button-style
		$this->register_controls_widget_button_style();

		//create style section-button-style
		$this->register_controls_widget_button_style_remove();
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
			$current_plan = $subscription->get_plan();
			/**
			 * Ultimo Network Plans
			 */
			$plans = wp_cache_get( 'wu_network_plans' );
			if ( false === $plans && $subscription !== false ) {
				$plans = \WU_Plans::get_plans( true, $current_plan->id );
				wp_cache_set( 'wu_network_plans', $plans );
			} 
			$coupon_code = $subscription->get_coupon_code();
			
			?>
			<div class="waashero-module waashero-account-actions" >
			

			<div class="waashero-module-title waashero-ultimo-title"><div <?php echo $this->get_render_attribute_string( 'waashero_pro_text' ); ?>><?php echo $settings['waashero_pro_text']; ?></div></div>
			

			

			<ul class="ultimo-account-actions-ul">
			
				<?php do_action('wu_button_subscription_on_site'); ?>
			
				<?php if ($subscription && $subscription->get_site_count() > 1) : ?>
				<li>
					<a href="<?php echo admin_url('ms-delete-site.php'); ?>" class="waashero-ultimo-action-btn-remove">
						<?php echo $settings['waashero_pro_text_remove_site']; ?>
					</a>
				</li>
    	<?php endif; ?>
  
				<li>
					<a href="<?php echo admin_url('admin.php?page=wu-remove-account'); ?>" class="waashero-ultimo-action-btn">
						<?php echo $settings['waashero_pro_text_delete_plan']; ?>
					</a>
				</li>
  			</ul>
		</div>
		<?php endif;
	}	
}
?>
