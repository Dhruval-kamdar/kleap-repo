<?php
namespace ElementorExtensions\Classes;

use ElementorExtensions\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Utils {

	public static function get_pages(){
        $all_pages = get_pages(['post_status' => 'publish', 'sort_column' => 'post_title', 'sort_oder' => 'ASC']);

        $pages = [];
        $pages[''] = __('-- Select --', 'elementor-extensions');
        if(!empty($all_pages)):
            foreach($all_pages as $key => $page):
                $pages[$page->ID] = __($page->post_title, 'elementor-extensions');
            endforeach;
        endif;
        return $pages;
    }

	public static function get_post_types( $args = [] ) {
		$post_type_args = [
			// Default is the value $public.
			'show_in_nav_menus' => true,
		];

		// Keep for backwards compatibility
		if ( ! empty( $args['post_type'] ) ) {
			$post_type_args['name'] = $args['post_type'];
			unset( $args['post_type'] );
		}

		$post_type_args = wp_parse_args( $post_type_args, $args );

		$_post_types = get_post_types( $post_type_args, 'objects' );

		$post_types = [];

		foreach ( $_post_types as $post_type => $object ) {
			$post_types[ $post_type ] = $object->label;
		}

		/**
		 * Public Post types
		 *
		 * Allow 3rd party plugins to filters the public post types elementor should work on
		 *
		 * @since 1.0.0
		 *
		 * @param array $post_types Elementor supported public post types.
		 */
		return apply_filters( 'elementor_extensions/utils/get_post_types', $post_types );
	}

	public static function get_client_ip() {
		$server_ip_keys = [
			'HTTP_CLIENT_IP',
			'HTTP_X_FORWARDED_FOR',
			'HTTP_X_FORWARDED',
			'HTTP_X_CLUSTER_CLIENT_IP',
			'HTTP_FORWARDED_FOR',
			'HTTP_FORWARDED',
			'REMOTE_ADDR',
		];

		foreach ( $server_ip_keys as $key ) {
			if ( isset( $_SERVER[ $key ] ) && filter_var( $_SERVER[ $key ], FILTER_VALIDATE_IP ) ) {
				return $_SERVER[ $key ];
			}
		}

		// Fallback local ip.
		return '127.0.0.1';
	}

	public static function get_site_domain() {
		return str_ireplace( 'www.', '', parse_url( home_url(), PHP_URL_HOST ) );
	}

	public static function get_current_post_id() {
		if ( isset( Plugin::elementor()->documents ) ) {
			return Plugin::elementor()->documents->get_current()->get_main_id();
		}

		return get_the_ID();
	}

	public static function get_the_archive_url() {
		$url = '';
		if ( is_category() || is_tag() || is_tax() ) {
			$url = get_term_link( get_queried_object() );
		} elseif ( is_author() ) {
			$url = get_author_posts_url( get_queried_object_id() );
		} elseif ( is_year() ) {
			$url = get_year_link( get_query_var( 'year' ) );
		} elseif ( is_month() ) {
			$url = get_month_link( get_query_var( 'year' ), get_query_var( 'monthnum' ) );
		} elseif ( is_day() ) {
			$url = get_day_link( get_query_var( 'year' ), get_query_var( 'monthnum' ), get_query_var( 'day' ) );
		} elseif ( is_post_type_archive() ) {
			$url = get_post_type_archive_link( get_post_type() );
		}

		return $url;
	}

	public static function get_page_title( $include_context = true ) {
		$title = '';

		if ( is_singular() ) {
			/* translators: %s: Search term. */
			$title = get_the_title();

			if ( $include_context ) {
				$post_type_obj = get_post_type_object( get_post_type() );
				$title = sprintf( '%s: %s', $post_type_obj->labels->singular_name, $title );
			}
		} elseif ( is_search() ) {
			/* translators: %s: Search term. */
			$title = sprintf( __( 'Search Results for: %s', 'elementor-extensions' ), get_search_query() );

			if ( get_query_var( 'paged' ) ) {
				/* translators: %s is the page number. */
				$title .= sprintf( __( '&nbsp;&ndash; Page %s', 'elementor-extensions' ), get_query_var( 'paged' ) );
			}
		} elseif ( is_category() ) {
			$title = single_cat_title( '', false );

			if ( $include_context ) {
				/* translators: Category archive title. 1: Category name */
				$title = sprintf( __( 'Category: %s', 'elementor-extensions' ), $title );
			}
		} elseif ( is_tag() ) {
			$title = single_tag_title( '', false );
			if ( $include_context ) {
				/* translators: Tag archive title. 1: Tag name */
				$title = sprintf( __( 'Tag: %s', 'elementor-extensions' ), $title );
			}
		} elseif ( is_author() ) {
			$title = '<span class="vcard">' . get_the_author() . '</span>';

			if ( $include_context ) {
				/* translators: Author archive title. 1: Author name */
				$title = sprintf( __( 'Author: %s', 'elementor-extensions' ), $title );
			}
		} elseif ( is_year() ) {
			$title = get_the_date( _x( 'Y', 'yearly archives date format', 'elementor-extensions' ) );

			if ( $include_context ) {
				/* translators: Yearly archive title. 1: Year */
				$title = sprintf( __( 'Year: %s', 'elementor-extensions' ), $title );
			}
		} elseif ( is_month() ) {
			$title = get_the_date( _x( 'F Y', 'monthly archives date format', 'elementor-extensions' ) );

			if ( $include_context ) {
				/* translators: Monthly archive title. 1: Month name and year */
				$title = sprintf( __( 'Month: %s', 'elementor-extensions' ), $title );
			}
		} elseif ( is_day() ) {
			$title = get_the_date( _x( 'F j, Y', 'daily archives date format', 'elementor-extensions' ) );

			if ( $include_context ) {
				/* translators: Daily archive title. 1: Date */
				$title = sprintf( __( 'Day: %s', 'elementor-extensions' ), $title );
			}
		} elseif ( is_tax( 'post_format' ) ) {
			if ( is_tax( 'post_format', 'post-format-aside' ) ) {
				$title = _x( 'Asides', 'post format archive title', 'elementor-extensions' );
			} elseif ( is_tax( 'post_format', 'post-format-gallery' ) ) {
				$title = _x( 'Galleries', 'post format archive title', 'elementor-extensions' );
			} elseif ( is_tax( 'post_format', 'post-format-image' ) ) {
				$title = _x( 'Images', 'post format archive title', 'elementor-extensions' );
			} elseif ( is_tax( 'post_format', 'post-format-video' ) ) {
				$title = _x( 'Videos', 'post format archive title', 'elementor-extensions' );
			} elseif ( is_tax( 'post_format', 'post-format-quote' ) ) {
				$title = _x( 'Quotes', 'post format archive title', 'elementor-extensions' );
			} elseif ( is_tax( 'post_format', 'post-format-link' ) ) {
				$title = _x( 'Links', 'post format archive title', 'elementor-extensions' );
			} elseif ( is_tax( 'post_format', 'post-format-status' ) ) {
				$title = _x( 'Statuses', 'post format archive title', 'elementor-extensions' );
			} elseif ( is_tax( 'post_format', 'post-format-audio' ) ) {
				$title = _x( 'Audio', 'post format archive title', 'elementor-extensions' );
			} elseif ( is_tax( 'post_format', 'post-format-chat' ) ) {
				$title = _x( 'Chats', 'post format archive title', 'elementor-extensions' );
			}
		} elseif ( is_post_type_archive() ) {
			$title = post_type_archive_title( '', false );

			if ( $include_context ) {
				/* translators: Post type archive title. 1: Post type name */
				$title = sprintf( __( 'Archives: %s', 'elementor-extensions' ), $title );
			}
		} elseif ( is_tax() ) {
			$title = single_term_title( '', false );

			if ( $include_context ) {
				$tax = '';// get_taxonomy( get_queried_object()->taxonomy );
				/* translators: Taxonomy term archive title. 1: Taxonomy singular name, 2: Current taxonomy term */
				$title = sprintf( __( '%1$s: %2$s', 'elementor-extensions' ), $tax->labels->singular_name, $title );
			}
		} elseif ( is_404() ) {
			$title = __( 'Page Not Found', 'elementor-extensions' );
		} // End if().

		/**
		 * The archive title.
		 *
		 * Filters the archive title.
		 *
		 * @since 1.0.0
		 *
		 * @param string $title Archive title to be displayed.
		 */
		$title = apply_filters( 'elementor/utils/get_the_archive_title', $title );

		return $title;
	}

	public static function get_the_archive_title( $include_context = true ) {
		return self::get_page_title();
	}

	public static function set_global_authordata() {
		global $authordata;
		if ( ! isset( $authordata->ID ) ) {
			$post = get_post();
			$authordata = get_userdata( $post->post_author );
		}
	}

	/**
	 * Used to overcome core bug when taxonomy is in more then one post type
	 *
	 * @see https://core.trac.wordpress.org/ticket/27918
	 *
	 * @global array $wp_taxonomies The registered taxonomies.
	 *
	 *
	 * @param array $args
	 * @param string $output
	 * @param string $operator
	 *
	 * @return array
	 */
	public static function get_taxonomies( $args = [], $output = 'names', $operator = 'and' ) {
		global $wp_taxonomies;

		$field = ( 'names' === $output ) ? 'name' : false;

		// Handle 'object_type' separately.
		if ( isset( $args['object_type'] ) ) {
			$object_type = (array) $args['object_type'];
			unset( $args['object_type'] );
		}

		$taxonomies = wp_filter_object_list( $wp_taxonomies, $args, $operator );

		if ( isset( $object_type ) ) {
			foreach ( $taxonomies as $tax => $tax_data ) {
				if ( ! array_intersect( $object_type, $tax_data->object_type ) ) {
					unset( $taxonomies[ $tax ] );
				}
			}
		}

		if ( $field ) {
			$taxonomies = wp_list_pluck( $taxonomies, $field );
		}

		return $taxonomies;
	}

	/**
	 * Fetches available post types
	 *
	 * @since 1.0.0
	 */
	public static function get_public_post_types_options( $singular = false, $args = [] ) {
		$post_type_args = [
			'show_in_nav_menus' => true,
		];

		$post_types = [];

		if ( ! function_exists( 'get_post_types' ) )
			return $post_types;

		$_post_types = get_post_types( $post_type_args, 'objects' );

		foreach ( $_post_types as $post_type => $object ) {
			$post_types[ $post_type ] = $singular ? $object->labels->singular_name : $object->label;
		}

		return $post_types;
	}

	/**
	 * Fetches available taxonomies
	 *
	 * @since 1.0.0
	 */
	public static function get_taxonomies_options() {

		$options = [];

		$taxonomies = get_taxonomies( array(
			'show_in_nav_menus' => true
		), 'objects' );

		if ( empty( $taxonomies ) ) {
			$options[ '' ] = __( 'No taxonomies found', 'elementor-extensions' );
			return $options;
		}

		foreach ( $taxonomies as $taxonomy ) {
			$options[ $taxonomy->name ] = $taxonomy->label;
		}

		return $options;
	}

	/**
	 * Fetches available pages
	 *
	 * @since 1.0.0
	 */
	public static function get_pages_options() {

		$options = [];

		$pages = get_pages( array(
			'hierarchical' => false,
		) );

		if ( empty( $pages ) ) {
			$options[ '' ] = __( 'No pages found', 'elementor-extensions' );
			return $options;
		}

		foreach ( $pages as $page ) {
			$options[ $page->ID ] = $page->post_title;
		}

		return $options;
	}

	/**
	 * Fetches available users
	 *
	 * @since 1.0.0
	 */
	public static function get_users_options() {

		$options = [];

		$users = get_users( array(
			'fields' => [ 'ID', 'display_name' ],
		) );

		if ( empty( $users ) ) {
			$options[ '' ] = __( 'No users found', 'elementor-extensions' );
			return $options;
		}

		foreach ( $users as $user ) {
			$options[ $user->ID ] = $user->display_name;
		}

		return $options;
	}

	/**
	 * Get category with highest number of parents
	 * from a given list
	 *
	 * @since 1.0.0
	 */
	public static function get_most_parents_category( $categories = [] ) {

		$counted_cats = [];

		if ( ! is_array( $categories ) )
			return $categories;

		foreach ( $categories as $category ) {
			$category_parents = get_category_parents( $category->term_id, false, ',' );
			$category_parents = explode( ',', $category_parents );
			$counted_cats[ $category->term_id ] = count( $category_parents );
		}

		arsort( $counted_cats );
		reset( $counted_cats );

		return key( $counted_cats );
	}

	/**
	 * Get list of terms for a specific post ID
	 * from a taxonomy with highter number of terms used
	 *
	 * @since 1.0.0
	 */
	public static function get_parent_terms_highest( $post_id ) {

		$taxonomies = get_post_taxonomies( $post_id );

		if(empty($taxonomies)):
			return [];
		endif;

		$tax 		= $taxonomies[0];
		$tax_term_c = 0;

		foreach ( $taxonomies as $taxonomy => $name ) {
			$taxonomy_terms = wp_get_post_terms( $post_id, $name );

			if ( count( $taxonomy_terms ) > $tax_term_c ) {
				$tax_term_c = count( $taxonomy_terms );
				$tax 		= $name;
			}
		}

		$terms = wp_get_post_terms( $post_id, $tax );

		return $terms;
	}

	public static function elementor() {
		return \Elementor\Plugin::$instance;
	}
}
