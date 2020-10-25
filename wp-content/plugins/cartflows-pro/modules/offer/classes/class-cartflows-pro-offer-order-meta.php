<?php
/**
 * Offer order meta.
 *
 * @package cartflows
 */

/**
 * Meta Boxes setup
 */
class Cartflows_Pro_Offer_Order_Meta {


	/**
	 * Instance
	 *
	 * @var $instance
	 */
	private static $instance;


	/**
	 * Initiator
	 */
	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Constructor
	 */
	public function __construct() {

		if ( is_admin() ) {
			add_filter( 'woocommerce_get_formatted_order_total', array( $this, 'add_order_type' ), 10, 4 );
			add_filter( 'woocommerce_admin_order_data_after_order_details', array( $this, 'offer_linked_orders' ), 10, 1 );
		}
	}

	/**
	 * Show order type in price column.
	 *
	 * @param html   $formatted_total order total.
	 * @param object $order order object.
	 * @param string $tax_display tax.
	 * @param string $display_refunded refund.
	 * @return html $formatted_total order total.
	 */
	public function add_order_type( $formatted_total, $order, $tax_display, $display_refunded ) {

		$screen = get_current_screen();

		if ( $screen && 'edit' == $screen->base && 'shop_order' == $screen->post_type ) {

			$order_id = $order->get_id();
			$is_offer = $order->get_meta( '_cartflows_offer' );

			if ( 'yes' === $is_offer ) {

				$offer_type = $order->get_meta( '_cartflows_offer_type' );

				if ( 'upsell' === $offer_type ) {
					$formatted_total .= '<div><span>' . __( 'CartFlows Upsell', 'cartflows-pro' ) . '</span></div>';
				} elseif ( 'downsell' === $offer_type ) {
					$formatted_total .= '<div><span>' . __( 'CartFlows Downsell', 'cartflows-pro' ) . '</span></div>';
				}
			}
		}

		return $formatted_total;
	}

	/**
	 *  Display child orders in order detail page.
	 *
	 * @param object $order order object.
	 * @return void
	 */
	public function offer_linked_orders( $order ) {

		$order_id = $order->get_id();

		$is_cartflows_offer = $order->get_meta( '_cartflows_offer' );

		if ( 'yes' === $is_cartflows_offer ) {

			$parent_order_id = $order->get_meta( '_cartflows_offer_parent_id' );

			if ( ! empty( $parent_order_id ) ) {

				$parent_order       = wc_get_order( $parent_order_id );
				$order_number       = $parent_order->get_order_number();
				$parent_order_html  = '<p class="form-field form-field-wide wcf_parent_order" style= "margin-top: 20px;"><strong>' . __( 'CartFlows Parent Order', 'cartflows-pro' ) . ' : </strong>';
				$parent_order_html .= '<span style= "display: block;"><a href="' . get_edit_post_link( $parent_order_id ) . '"><strong>#' . esc_attr( $order_number ) . '</strong></a></span>';
				$parent_order_html .= '</p>';
				echo $parent_order_html;
			}
		} else {

			$child_orders = $order->get_meta( '_cartflows_offer_child_orders' );

			if ( ! empty( $child_orders ) ) {

				$child_order_html = '<p class="form-field form-field-wide wcf_child_order" style= "margin-top: 20px;"><strong>' . __( 'CartFlows Upsell&sol;Downsell Orders', 'cartflows-pro' ) . ' : </strong>';

				foreach ( $child_orders as $child_id => $data ) {

					$child_order  = wc_get_order( $child_id );
					$order_number = $child_order->get_order_number();
					$offer_type   = $child_order->get_meta( '_cartflows_offer_type' );

					if ( 'upsell' === $offer_type ) {
						$tag = __( 'Upsell', 'cartflows-pro' );
					} elseif ( 'downsell' === $offer_type ) {
						$tag = __( 'Downsell', 'cartflows-pro' );
					}

					$child_order_html .= '<span style= "display: block;"><a href="' . get_edit_post_link( $child_id ) . '"><strong>#' . esc_attr( $order_number ) . '</strong></a>&nbsp;&hyphen;&nbsp;' . $tag . '</span>';
				}

				$child_order_html .= '</p>';

				echo $child_order_html;
			}
		}
	}
}

/**
 * Kicking this off by calling 'get_instance()' method
 */
Cartflows_Pro_Offer_Order_Meta::get_instance();
