<?php
/**
 * Elementor Ultimo Account Status Widget.
 *
 * Elementor widget that inserts an embbedable content into the page, from any given URL.
 *
 * @since 1.0.0
 */
namespace Wh_Elementor_Modules\Ultimo_Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Account_Status extends \Elementor\Widget_Base {

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
		return 'account-status';
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
		return __( 'Account Status', WH_ULTIMO_TEXT_DOMAIN );
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
		return 'fas fa-clipboard-list';
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

    protected function register_controls_widget_title(){

		
		$this->add_control(
			'waashero_pro_text',
			[
				'label' => __( 'Title', WH_ULTIMO_TEXT_DOMAIN ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'label_block' => true,
				'default' => __( 'Account Status', WH_ULTIMO_TEXT_DOMAIN ),
				'dynamic' => [
					'active' => true,
				],
			]
		);
		
	}

	protected function register_controls_pro_style_title(){
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
					'{{WRAPPER}} .waashero-ultimo-action-btn a' => 'background-color: {{VALUE}};',
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
					'{{WRAPPER}} .waashero-ultimo-action-btn > a' => 'color: {{VALUE}};',
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
					'{{WRAPPER}} .waashero-ultimo-add-account-action' => 'width: {{SIZE}}%;',
				],
			]
		);

		//pro text typography
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'waashero_ultimo_btn_typography',
				'selector' => '{{WRAPPER}} .waashero-ultimo-add-account-action',
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
					'{{WRAPPER}} .waashero-ultimo-add-account-action' => 'text-align: {{VALUE}};',
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
    

	protected function pro_render($settings, $subscription, $plan, $coupon_code, $have_coupon){
	
		?>
		<ul class="wu_status_list">
  
  			<li class="current-plan-status">
				<p>
				<?php if ($plan->free): ?>

					<strong><?php printf(__('Plan %s', 'wp-ultimo'), $plan->title); ?></strong>: <?php _e('Free!', 'wp-ultimo'); ?>

				<?php else: ?>


					<?php if ($coupon_code): ?>
					<?php if ($subscription->is_free()): ?>
						<strong><?php printf(__('Plan %s', 'wp-ultimo'), $plan->title); ?></strong>: <?php printf(__("<span style='text-decoration: line-through;'>%s every %s month(s).</span> ", 'wp-ultimo'), wu_format_currency($subscription->price), $subscription->freq) . _e('Free!', 'wp-ultimo'); ?>
					<?php else: ?>
						<strong><?php printf(__('Plan %s', 'wp-ultimo'), $plan->title); ?></strong>: <?php printf(__("<span style='text-decoration: line-through;'>%s every %s month(s).</span> ", 'wp-ultimo'), wu_format_currency($subscription->price), $subscription->freq) . printf(__('%s every %s month(s).', 'wp-ultimo'), $subscription->get_price_after_coupon_code(), $subscription->freq); ?>
					<?php endif; ?>
					<?php else: ?>
					<strong><?php printf(__('Plan %s', 'wp-ultimo'), $plan->title); ?></strong>: <?php printf(__('%s every %s month(s).', 'wp-ultimo'), wu_format_currency($subscription->price), $subscription->freq); ?>
					<?php endif; ?>

				<?php endif; ?>
    			</p>


				<p id="coupon-code-field" <?php if (!$coupon_code) echo 'style="display: none;"' ?>>
				
				<?php echo $subscription->get_coupon_code_string(); ?>

				</p>

  			</li>

  			<li class="account-status">
    
				<?php
				/**
				 * Trial Status
				 */
				$site_trial = $subscription->get_trial();
				if ( $site_trial && !$subscription->is_free() ) :
				?>
				<p>
				<strong><?php _e( 'Trial Period:', 'wp-ultimo' ); ?></strong> 
				<?php printf( _n('You still have %s day left in your trial period. It will end on %s.', 'You still have %s days left in your trial period. It will end on %s.', $site_trial, 'wp-ultimo' ), $site_trial, $subscription->get_date('trial_end') ); ?>
				</p>
				<?php endif; ?>
				
				<?php
			/**
			 * Display the integration
			 */
			if (!$plan->free) :

			if ($subscription->integration_status) :
      
    ?>
    
    <p>
      <strong><?php _e( 'Payment Method:', 'wp-ultimo' ); ?></strong> 
      
      <?php 
      /**
       * Get Gateway title
       */
      $gateway = wu_get_gateway($subscription->gateway);

      echo apply_filters( 'wu_account_integrated_method_title', $gateway ? $gateway->get_title() : ucfirst($subscription->gateway), $gateway, $subscription);
      
      /**
       * Allow plugin developers to add payment integration info
       * @since 1.7.0
       * @param WU_Gateway|false Gateway integrated
       * @param WU_SUbscription  Current user subscription
       */
      do_action( 'wu_account_integrated_method_actions_before', $gateway, $subscription );

      ?>
      
      <?php if (apply_filters('wu_account_display_cancel_integration_link', true)) : ?>
        - 
        <span class="plugins">
          <a href="<?php echo wu_get_active_gateway()->get_url('remove-integration'); ?>" class="delete"><?php _e('Cancel Payment Method', 'wp-ultimo'); ?></a>
        </span>
      <?php endif; ?>

      <?php
      
      /**
       * Allow plugin developers to add payment integration info
       * @since 1.7.0
       * @param WU_Gateway|false Gateway integrated
       * @param WU_SUbscription  Current user subscription
       */
      do_action( 'wu_account_integrated_method_actions_after', $gateway, $subscription );

      ?>

    </p>
    
    <?php else: ?>
    
      <p>
        <strong><?php _e( 'Payment Method:', 'wp-ultimo' ); ?></strong> 
        <?php _e( 'No Payment Method integrated yet.', 'wp-ultimo' ); ?>
      </p>
    
    <?php endif; endif; ?>
    
    <?php
    /**
     * Billing Starts
     */
    if ( !$subscription->is_free() ) :
    ?>
    <p>
		<strong><?php _e( 'Account valid until:', 'wp-ultimo' ); ?></strong> 
		<?php echo $subscription->created_at == $subscription->active_until ? $subscription->get_date('trial_end') : $subscription->get_date('active_until'); ?>
    </p>
    <?php endif; ?>
    
  	</li>

	<?php do_action( 'wu_account_integration_meta_box', $subscription, $plan ); ?>
	
	</ul>

	<?php if ( wu_get_current_site()->is_user_owner() && !$subscription->integration_status && !$subscription->is_free()) : ?>
	<ul class="wu-button-upgrade-account">
		<li class="upgrade-account waashero-ultimo-action-btn">

		<?php
		/**
		 * Allow plugin developers to hide the integration buttons when certain things happen
		 * @since 1.9.0
		 */
		if ( apply_filters( 'wu_display_payment_integration_buttons', true, $subscription ) ) : 
		
		$active_gateways = is_array(\WU_Settings::get_setting( 'active_gateway' ) ) ? \WU_Settings::get_setting('active_gateway') : array();

		/**
		 * @since  1.1.0 displays all possible gateways
		 */
		foreach ( wu_get_gateways() as $gateway) : $gateway = $gateway['gateway'];

			if ( !in_array( $gateway->id, array_keys( $active_gateways ) ) ) continue;

			$content = $gateway->get_button_label();

		?>

			<?php $class = !$subscription->integration_status ? 'button-primary ' : '' ?>

			<?php ob_start(); ?>
			<a class="button <?php echo $class; ?> button-streched button-gateway " href="<?php echo $gateway->get_url('process-integration'); ?>">
			 	<strong><?php echo $content; ?></strong>
			</a>
			<?php $button = ob_get_clean(); ?>
			
			<?php echo apply_filters( "wu_gateway_integration_button_$gateway->id", $button, $content ); ?>

		<?php endforeach; ?>
		
		<?php endif; // end if; ?>

		</li>
	</ul>
	<?php endif; ?>

		<script type="text/javascript">
		jQuery(document).ready(function() {

			jQuery('#wp-ultimo-account-status-waashero .account-status .delete').on('click', function (e) {

				e.preventDefault();

				var button = $(this);
				
				Swal.fire({
				titleText: '<?php printf(__('Plan %s', 'wp-ultimo'), $plan->title); ?>',
				text: <?php echo json_encode(apply_filters('wu_cancel_integration_text', __('Are you sure you want to cancel your current payment integration?', 'wp-ultimo'))); ?>,
				icon: "warning",
				showCancelButton: true,
				// confirmButtonColor: "#DD6B55",
				confirmButtonText: '<?php printf(__('%s', 'wp-ultimo'), 'Confirm'); ?>',
				cancelButtonText: '<?php printf(__('%s', 'wp-ultimo'), 'Cancel'); ?>',
				}).then((result) => {
				if (result.value) {
					window.location.href = button.attr('href');
				}
				})
		
			}); // end plan-delete;

			jQuery('#stripe-checkout-button').removeClass('button button-primary button-streched');
			jQuery('#stripe-checkout-button').addClass('waashero-ultimo-add-account-action');

		});
		</script>
			
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

		<div class="waashero-module waashero-account-actions" >
			<div class="waashero-module-title waashero-ultimo-title"><div <?php echo $this->get_render_attribute_string( 'waashero_pro_text' ); ?>><?php echo $settings['waashero_pro_text']; ?></div></div>
			<p class="wh-ultimo-no-sub">No Active Subscription Found.</p>
		</div>

		<?php else:
		/**
		 * Current Plan
		 */
		$plan = $subscription->get_plan();


		/**
		 * Coupon Codes
		 */
		$coupon_code = $subscription->get_coupon_code();
		$have_coupon = $coupon_code ?: false;

		
		?>
		<div id="wp-ultimo-account-status-waashero" class="waashero-module waashero-account-status" >

			<div class="waashero-module-title waashero-ultimo-title"><div <?php echo $this->get_render_attribute_string( 'waashero_pro_text' ); ?>><?php echo $settings['waashero_pro_text']; ?></div></div>
		
		<?php
	   
			$this->pro_render($settings, $subscription, $plan, $coupon_code, $have_coupon);
		
		?>
		</div>
		<?php endif;
	}	
}
?>
