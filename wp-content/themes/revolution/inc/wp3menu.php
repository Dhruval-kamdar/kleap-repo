<?php

function thb_center_nav_menu_items( $items, $args ) {
	if ( $args->theme_location == 'nav-menu' ) {
		if ( is_array( $items ) || is_object( $items ) ) {

			ob_start();
			?>
			<?php do_action( 'thb_logo', true ); ?>
			<?php
			$logo_html  = ob_get_clean();
			$menu_items = array();
			foreach ( $items as $key => $item ) {
				if ( $item->menu_item_parent == 0 ) {
					$menu_items[] = $key; }
			}
			$new_item_array             = array();
			$new_item                   = new stdClass();
			$new_item->ID               = 0;
			$new_item->db_id            = 0;
			$new_item->menu_item_parent = 0;
			$new_item->url              = '';
			$new_item->title            = $logo_html;
			$new_item->menu_order       = 0;
			$new_item->object_id        = 0;
			$new_item->description      = '';
			$new_item->attr_title       = '';
			$new_item->button           = '';
			$new_item->megamenu         = '';
			$new_item->logo             = true;
			$new_item->classes          = 'logo-menu-item';
			$new_item_array[]           = $new_item;
			$get_position               = count( $menu_items ) % 2 == 0 ? ( count( $menu_items ) / 2 ) - 1 : floor( count( $menu_items ) / 2 );
			array_splice( $items, $menu_items[ $get_position ], 0, $new_item_array );
		}
	}

	return $items;
}
/**
 * Custom Walker - Mobile Menu
 *
 * @access      public
 * @since       1.0
 * @return      void
 */
class thb_mobileDropdown extends Walker_Nav_Menu {
	/**
	 * Start the element output.
	 *
	 * @see Walker::start_el()
	 *
	 * @since 3.0.0
	 * @since 4.4.0 'nav_menu_item_args' filter was added.
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param object $item   Menu item data object.
	 * @param int    $depth  Depth of menu item. Used for padding.
	 * @param array  $args   An array of arguments. @see wp_nav_menu()
	 * @param int    $id     Current item ID.
	 */
	function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

		$classes   = empty( $item->classes ) ? array() : (array) $item->classes;
		$classes[] = 'menu-item-' . $item->ID;
		$classes[] = ! empty( $item->menuanchor ) ? 'has-hash' : '';
		/**
		 * Filter the arguments for a single nav menu item.
		 *
		 * @since 4.4.0
		 *
		 * @param array  $args  An array of arguments.
		 * @param object $item  Menu item data object.
		 * @param int    $depth Depth of menu item. Used for padding.
		 */
		$args = apply_filters( 'nav_menu_item_args', $args, $item, $depth );

		/**
		 * Filter the CSS class(es) applied to a menu item's list item element.
		 *
		 * @since 3.0.0
		 * @since 4.1.0 The `$depth` parameter was added.
		 *
		 * @param array  $classes The CSS classes that are applied to the menu item's `<li>` element.
		 * @param object $item    The current menu item.
		 * @param array  $args    An array of wp_nav_menu() arguments.
		 * @param int    $depth   Depth of menu item. Used for padding.
		 */
		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		/**
		 * Filter the ID applied to a menu item's list item element.
		 *
		 * @since 3.0.1
		 * @since 4.1.0 The `$depth` parameter was added.
		 *
		 * @param string $menu_id The ID that is applied to the menu item's `<li>` element.
		 * @param object $item    The current menu item.
		 * @param array  $args    An array of wp_nav_menu() arguments.
		 * @param int    $depth   Depth of menu item. Used for padding.
		 */
		$id = apply_filters( 'nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args, $depth );
		$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

		$output .= $indent . '<li' . $id . $class_names . '>';

		$atts           = array();
		$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
		$atts['target'] = ! empty( $item->target ) ? $item->target : '';
		$atts['rel']    = ! empty( $item->xfn ) ? $item->xfn : '';
		$atts['href']   = ! empty( $item->url ) ? $item->url : '';

		/**
		 * Filter the HTML attributes applied to a menu item's anchor element.
		 *
		 * @since 3.6.0
		 * @since 4.1.0 The `$depth` parameter was added.
		 *
		 * @param array $atts {
		 *     The HTML attributes applied to the menu item's `<a>` element, empty strings are ignored.
		 *
		 *     @type string $title  Title attribute.
		 *     @type string $target Target attribute.
		 *     @type string $rel    The rel attribute.
		 *     @type string $href   The href attribute.
		 * }
		 * @param object $item  The current menu item.
		 * @param array  $args  An array of wp_nav_menu() arguments.
		 * @param int    $depth Depth of menu item. Used for padding.
		 */
		$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );

		$menubg      = ! empty( $item->menubg ) ? $item->menubg : '';
		$menu_anchor = ! empty( $item->menuanchor ) ? '#' . esc_attr( $item->menuanchor ) : '';

		$attributes = '';

		if ( $menubg ) {
			$atts['data-menubg'] = $menubg;
		}
		foreach ( $atts as $attr => $value ) {
			if ( ! empty( $value ) ) {
				$value       = ( 'href' === $attr ) ? esc_url( $value . $menu_anchor ) : esc_attr( $value );
				$attributes .= ' ' . $attr . '="' . $value . '"';
			}
		}

		/** This filter is documented in wp-includes/post-template.php */
		$title = apply_filters( 'the_title', $item->title, $item->ID );

		/**
		 * Filter a menu item's title.
		 *
		 * @since 4.4.0
		 *
		 * @param string $title The menu item's title.
		 * @param object $item  The current menu item.
		 * @param array  $args  An array of wp_nav_menu() arguments.
		 * @param int    $depth Depth of menu item. Used for padding.
		 */
		$title = apply_filters( 'nav_menu_item_title', $title, $item, $args, $depth );

		$item_output  = $args->before;
		$item_output .= '<a' . $attributes . '>';
		$item_output .= $args->link_before . $title . $args->link_after;
		$item_output .= ( in_array( 'menu-item-has-children', $item->classes ) ? '<div class="thb-arrow"><div></div><div></div></div>' : '' ) . '</a>';
		$item_output .= $args->after;

		/**
		 * Filter a menu item's starting output.
		 *
		 * The menu item's starting output only includes `$args->before`, the opening `<a>`,
		 * the menu item's title, the closing `</a>`, and `$args->after`. Currently, there is
		 * no filter for modifying the opening and closing `<li>` for a menu item.
		 *
		 * @since 3.0.0
		 *
		 * @param string $item_output The menu item's starting HTML output.
		 * @param object $item        Menu item data object.
		 * @param int    $depth       Depth of menu item. Used for padding.
		 * @param array  $args        An array of wp_nav_menu() arguments.
		 */
		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}
}

/* Full Menu */
class thb_fullmenu extends Walker_Nav_Menu {
	var $active_megamenu = 0;

	/**
	 * Starts the element output.
	 *
	 * @since 3.0.0
	 * @since 4.4.0 The {@see 'nav_menu_item_args'} filter was added.
	 *
	 * @see Walker::start_el()
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param object $item   Menu item data object.
	 * @param int    $depth  Depth of menu item. Used for padding.
	 * @param array  $args   An array of wp_nav_menu() arguments.
	 * @param int    $id     Current item ID.
	 */
	public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

		$classes   = empty( $item->classes ) ? array() : (array) $item->classes;
		$classes[] = 'menu-item-' . $item->ID;
		$classes[] = ! empty( $item->menuanchor ) ? 'has-hash' : '';

		if ( $depth === 1 && $this->active_megamenu ) {
			$classes[] = 'mega-menu-title';
		}
		if ( $depth === 0 ) {
			$this->active_megamenu = get_post_meta( $item->ID, '_menu_item_megamenu', true );
			if ( $this->active_megamenu ) {
				$classes[] = ' menu-item-mega-parent '; }
		} else {
				$classes[] = get_post_meta( $item->ID, '_menu_item_titleitem', true ) === 'enabled' ? ' title-item' : '';
		}
		if ( $depth === 2 ) {
			if ( $this->active_megamenu ) {
				$classes[] = ' menu-item-mega-link '; }
		}
		/**
		 * Filters the arguments for a single nav menu item.
		 *
		 * @since 4.4.0
		 *
		 * @param array  $args  An array of arguments.
		 * @param object $item  Menu item data object.
		 * @param int    $depth Depth of menu item. Used for padding.
		 */
		$args = apply_filters( 'nav_menu_item_args', $args, $item, $depth );

		/**
		 * Filters the CSS class(es) applied to a menu item's list item element.
		 *
		 * @since 3.0.0
		 * @since 4.1.0 The `$depth` parameter was added.
		 *
		 * @param array  $classes The CSS classes that are applied to the menu item's `<li>` element.
		 * @param object $item    The current menu item.
		 * @param array  $args    An array of wp_nav_menu() arguments.
		 * @param int    $depth   Depth of menu item. Used for padding.
		 */

		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		/**
		 * Filters the ID applied to a menu item's list item element.
		 *
		 * @since 3.0.1
		 * @since 4.1.0 The `$depth` parameter was added.
		 *
		 * @param string $menu_id The ID that is applied to the menu item's `<li>` element.
		 * @param object $item    The current menu item.
		 * @param array  $args    An array of wp_nav_menu() arguments.
		 * @param int    $depth   Depth of menu item. Used for padding.
		 */
		$id = apply_filters( 'nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args, $depth );
		$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

		$output .= $indent . '<li' . $id . $class_names . '>';

		$atts           = array();
		$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
		$atts['target'] = ! empty( $item->target ) ? $item->target : '';
		$atts['rel']    = ! empty( $item->xfn ) ? $item->xfn : '';
		$atts['href']   = ! empty( $item->url ) ? $item->url : '';

		/**
		 * Filters the HTML attributes applied to a menu item's anchor element.
		 *
		 * @since 3.6.0
		 * @since 4.1.0 The `$depth` parameter was added.
		 *
		 * @param array $atts {
		 *     The HTML attributes applied to the menu item's `<a>` element, empty strings are ignored.
		 *
		 *     @type string $title  Title attribute.
		 *     @type string $target Target attribute.
		 *     @type string $rel    The rel attribute.
		 *     @type string $href   The href attribute.
		 * }
		 * @param object $item  The current menu item.
		 * @param array  $args  An array of wp_nav_menu() arguments.
		 * @param int    $depth Depth of menu item. Used for padding.
		 */

		$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );

		$menu_anchor = ! empty( $item->menuanchor ) ? '#' . esc_attr( $item->menuanchor ) : '';

		$attributes = '';
		foreach ( $atts as $attr => $value ) {
			if ( ! empty( $value ) ) {
				$value       = ( 'href' === $attr ) ? esc_url( $value . $menu_anchor ) : esc_attr( $value );
				$attributes .= ' ' . $attr . '="' . $value . '"';
			}
		}

		/** This filter is documented in wp-includes/post-template.php */
		$title = apply_filters( 'the_title', $item->title, $item->ID );

		/**
		 * Filters a menu item's title.
		 *
		 * @since 4.4.0
		 *
		 * @param string $title The menu item's title.
		 * @param object $item  The current menu item.
		 * @param array  $args  An array of wp_nav_menu() arguments.
		 * @param int    $depth Depth of menu item. Used for padding.
		 */
		$title = apply_filters( 'nav_menu_item_title', $title, $item, $args, $depth );

		$item_output = $args->before;
		if ( ! isset( $item->logo ) && ! $item->logo ) {
			$item_output .= '<a' . $attributes . '>';
		}

		$item_output .= $args->link_before . $title . $args->link_after;

		if ( ! isset( $item->logo ) && ! property_exists( $item, 'logo' ) ) {
			$item_output .= '</a>';
		}
		$item_output .= $args->after;

		/**
		 * Filters a menu item's starting output.
		 *
		 * The menu item's starting output only includes `$args->before`, the opening `<a>`,
		 * the menu item's title, the closing `</a>`, and `$args->after`. Currently, there is
		 * no filter for modifying the opening and closing `<li>` for a menu item.
		 *
		 * @since 3.0.0
		 *
		 * @param string $item_output The menu item's starting HTML output.
		 * @param object $item        Menu item data object.
		 * @param int    $depth       Depth of menu item. Used for padding.
		 * @param array  $args        An array of wp_nav_menu() arguments.
		 */
		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}
}

/* Mega Background */
class thb_custom_menu {

	/*
	--------------------------------------------*
	 * Constructor
	 *--------------------------------------------*/

	/**
	 * Initializes the plugin by setting localization, filters, and administration functions.
	 */
	function __construct() {

		// add custom menu fields to menu
		add_filter( 'wp_setup_nav_menu_item', array( $this, 'thb_add_custom_nav_fields' ) );

		// save menu custom fields
		add_action( 'wp_update_nav_menu_item', array( $this, 'thb_update_custom_nav_fields' ), 10, 3 );

	} // end constructor


	/**
	 * Add custom fields to $item nav object
	 * in order to be used in custom Walker
	 *
	 * @access      public
	 * @since       1.0
	 * @return      void
	 */
	function thb_add_custom_nav_fields( $menu_item ) {

		$menu_item->menubg         = get_post_meta( $menu_item->ID, '_menu_item_menubg', true );
		$menu_item->menuanchor = get_post_meta( $menu_item->ID, '_menu_item_menuanchor', true );
		$menu_item->megamenu   = get_post_meta( $menu_item->ID, '_menu_item_megamenu', true );
		$menu_item->titleitem  = get_post_meta( $menu_item->ID, '_menu_item_titleitem', true );
		return $menu_item;

	}

	/**
	 * Save menu custom fields
	 *
	 * @access      public
	 * @since       1.0
	 * @return      void
	 */
	function thb_update_custom_nav_fields( $menu_id, $menu_item_db_id, $args ) {

		// Check if element is properly sent
		if ( ! empty( $_REQUEST['menu-item-menubg'] ) ) {
			$menubg_value = $_REQUEST['menu-item-menubg'][ $menu_item_db_id ];
			update_post_meta( $menu_item_db_id, '_menu_item_menubg', $menubg_value );
		}

		if ( ! empty( $_REQUEST['menu-item-menuanchor'] ) ) {
			$menuanchor_value = $_REQUEST['menu-item-menuanchor'][ $menu_item_db_id ];
			update_post_meta( $menu_item_db_id, '_menu_item_menuanchor', $menuanchor_value );
		}

		if ( ! isset( $_REQUEST['edit-menu-item-titleitem'][ $menu_item_db_id ] ) ) {
			$_REQUEST['edit-menu-item-titleitem'][ $menu_item_db_id ] = '';
		}
			$titleitem_enabled_value = $_REQUEST['edit-menu-item-titleitem'][ $menu_item_db_id ];
			update_post_meta( $menu_item_db_id, '_menu_item_titleitem', $titleitem_enabled_value );

		if ( ! isset( $_REQUEST['edit-menu-item-megamenu'][ $menu_item_db_id ] ) ) {
			$_REQUEST['edit-menu-item-megamenu'][ $menu_item_db_id ] = '';

		}
			$menu_mega_enabled_value = $_REQUEST['edit-menu-item-megamenu'][ $menu_item_db_id ];
			update_post_meta( $menu_item_db_id, '_menu_item_megamenu', $menu_mega_enabled_value );
	}

}

// instantiate plugin's class
$GLOBALS['thb_custom_menu'] = new thb_custom_menu();


function thb_custom_nav_fields_action( $item_id, $item, $depth, $args, $id ) {
	?>
	<div class="thb_menu_options">
		<p class="thb-field-link-mega description description-thin">
			<label for="edit-menu-item-megamenu-<?php echo esc_attr( $item_id ); ?>">
				<?php esc_html_e( 'Mega Menu', 'revolution' ); ?><br />
			<?php $value = get_post_meta( $item_id, '_menu_item_megamenu', true ); ?>
			<input type="checkbox" value="enabled" id="edit-menu-item-megamenu-<?php echo esc_attr( $item_id ); ?>" name="edit-menu-item-megamenu[<?php echo esc_attr( $item_id ); ?>]" <?php checked( $value, 'enabled' ); ?> />
			<?php esc_html_e( 'Enable', 'revolution' ); ?>
			</label>
		</p>
		<p class="thb-field-link-title description description-thin">
			<label for="edit-menu-item-titleitem-<?php echo esc_attr( $item_id ); ?>">
				<?php esc_html_e( 'Title', 'revolution' ); ?><br />
			<?php $value = get_post_meta( $item_id, '_menu_item_titleitem', true ); ?>
			<input type="checkbox" value="enabled" id="edit-menu-item-titleitem-<?php echo esc_attr( $item_id ); ?>" name="edit-menu-item-titleitem[<?php echo esc_attr( $item_id ); ?>]" <?php checked( $value, 'enabled' ); ?> />
			<?php esc_html_e( 'Enable', 'revolution' ); ?>
			</label>
		</p>
		<p class="field-menuanchor description-wide">
			<label for="edit-menu-item-menuanchor-<?php echo esc_attr( $item_id ); ?>">
				<strong><?php esc_html_e( 'Menu Anchor', 'revolution' ); ?></strong><br />
				<input type="text" id="edit-menu-item-menuanchor-<?php echo esc_attr( $item_id ); ?>" class="widefat edit-menu-item-menuanchor" name="menu-item-menuanchor[<?php echo esc_attr( $item_id ); ?>]" value="<?php echo esc_attr( $item->menuanchor ); ?>" />
				<span class="description"><?php _e( 'Add your row ID without the hashtag.', 'revolution' ); ?></span>
			</label>
		</p>
		<p class="field-menubg description-wide thb_menu_options">
			<label for="edit-menu-item-menubg-<?php echo esc_attr( $item_id ); ?>">
				<strong><?php esc_html_e( 'Menu Image', 'revolution' ); ?></strong><br />
				<input type="text" id="edit-menu-item-menubg-<?php echo esc_attr( $item_id ); ?>" class="widefat edit-menu-item-menubg" name="menu-item-menubg[<?php echo esc_attr( $item_id ); ?>]" value="<?php echo esc_attr( $item->menubg ); ?>" />
				<span class="description"><?php esc_html_e( 'The menu background will be used when possible. Enter an image url here.', 'revolution' ); ?></span>
			</label>
		</p>
	</div>
	<?php
}
add_action( 'wp_nav_menu_item_custom_fields', 'thb_custom_nav_fields_action', 10, 5 );
