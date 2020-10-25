<?php

namespace iThemesSecurity\Ban_Users;

use iThemesSecurity\Actor\Multi_Actor_Factory;
use iThemesSecurity\Actor\User;
use iThemesSecurity\Ban_Hosts\Creatable;
use iThemesSecurity\Ban_Hosts\Deletable;
use iThemesSecurity\Ban_Hosts\Filters;
use iThemesSecurity\Ban_Hosts\Repository;
use iThemesSecurity\Ban_Hosts\Repository_Ban;
use iThemesSecurity\Ban_Hosts\Updatable;
use iThemesSecurity\Exception\WP_Error;

final class Database_Repository implements Repository, Creatable, Updatable, Deletable {
	const CACHE_GROUP = 'itsec_database_bans';

	/** @var Multi_Actor_Factory */
	private $actor_factory;

	/** @var \wpdb */
	private $wpdb;

	/** @var string[] */
	private $ranges;

	/**
	 * Database_Repository constructor.
	 *
	 * @param Multi_Actor_Factory $actor_factory
	 * @param \wpdb               $wpdb
	 */
	public function __construct( Multi_Actor_Factory $actor_factory, \wpdb $wpdb ) {
		$this->actor_factory = $actor_factory;
		$this->wpdb          = $wpdb;
	}

	public function get_slug() {
		return 'database';
	}

	public function get_bans( Filters $filters ) {
		list( $where, $prepare ) = $this->build_where_clause( $filters );

		$query = "SELECT * FROM {$this->wpdb->base_prefix}itsec_bans";

		if ( $where ) {
			$query .= ' WHERE ' . implode( ' AND ', $where );
		}

		$query .= ' ORDER BY created_at DESC, id DESC';

		if ( $limit = $filters->get_limit() ) {
			$query .= ' LIMIT ' . absint( $limit );
		}

		$prepared = $prepare ? $this->wpdb->prepare( $query, $prepare ) : $query;
		$rows     = $this->wpdb->get_results( $prepared, ARRAY_A );

		if ( $this->wpdb->last_error ) {
			throw new WP_Error( new \WP_Error(
				'itsec_get_bans_failed',
				sprintf( __( 'Failed to get bans: %s', 'it-l10n-ithemes-security-pro' ), $this->wpdb->last_error )
			) );
		}

		return array_map( [ $this, 'hydrate' ], $rows );
	}

	public function count_bans( Filters $filters ) {
		list( $where, $prepare ) = $this->build_where_clause( $filters );

		$query = "SELECT count(*) FROM {$this->wpdb->base_prefix}itsec_bans";

		if ( $where ) {
			$query .= ' WHERE ' . implode( ' AND ', $where );
		}

		$prepared = $prepare ? $this->wpdb->prepare( $query, $prepare ) : $query;

		return (int) $this->wpdb->get_var( $prepared );
	}

	/**
	 * Builds the where clause based on the given filters.
	 *
	 * @param Filters $filters
	 *
	 * @return array
	 */
	protected function build_where_clause( Filters $filters ) {
		$where   = [];
		$prepare = [];

		if ( ( $after_cursor = $filters->get_after_cursor() ) && $after_cursor->get_last_date() ) {
			$where[]   = '(created_at, id) > (%s, %d)';
			$prepare[] = $after_cursor->get_last_date()->format( 'Y-m-d H:i:s' );
			$prepare[] = $after_cursor->get_last_id();
		}

		if ( ( $before_cursor = $filters->get_before_cursor() ) && $before_cursor->get_last_date() ) {
			$where[]   = '(created_at, id) < (%s, %d)';
			$prepare[] = $before_cursor->get_last_date()->format( 'Y-m-d H:i:s' );
			$prepare[] = $before_cursor->get_last_id();
		}

		if ( $actor_type = $filters->get_actor_type() ) {
			$where[]   = 'actor_type = %s';
			$prepare[] = $actor_type;
		}

		if ( $actor_id = $filters->get_actor_identifier() ) {
			$where[]   = 'actor_id = %s';
			$prepare[] = $actor_id;
		}

		if ( $comment = $filters->get_search() ) {
			$like      = '%' . $this->wpdb->esc_like( $comment ) . '%';
			$where[]   = '( comment LIKE %s OR host LIKE %s )';
			$prepare[] = $like;
			$prepare[] = $like;
		}

		if ( $created_after = $filters->get_created_after() ) {
			$where[]   = 'created_at > %s';
			$prepare[] = $created_after->format( 'Y-m-d H:i:s' );
		}

		if ( $created_before = $filters->get_created_before() ) {
			$where[]   = 'created_at < %s';
			$prepare[] = $created_before->format( 'Y-m-d H:i:s' );
		}

		return [ $where, $prepare ];
	}

	public function get_supported_filters() {
		return [
			Filters::ACTOR_TYPE,
			Filters::ACTOR_IDENTIFIER,
			Filters::SEARCH,
			Filters::CREATED_AFTER,
			Filters::CREATED_BEFORE,
		];
	}

	public function find_ban_for_host( $host ) {
		$row = $this->wpdb->get_row( $this->wpdb->prepare(
			"SELECT * FROM {$this->wpdb->base_prefix}itsec_bans WHERE host = %s",
			$host
		), ARRAY_A );

		if ( $row && ( $ban = $this->hydrate( $row ) ) && $ban->matches( $host ) ) {
			return $ban;
		}

		$ranges = $this->get_ranges();

		foreach ( $ranges as $id => $range ) {
			if ( \ITSEC_Lib_IP_Tools::intersect( $host, $range ) ) {
				$ban = $this->get( $id );

				if ( $ban && $ban->matches( $host ) ) {
					return $ban;
				}
			}
		}

		return null;
	}

	public function get( $id ) {
		if ( ! $row = wp_cache_get( $id, self::CACHE_GROUP ) ) {
			$row = $this->wpdb->get_row( $this->wpdb->prepare(
				"SELECT * FROM {$this->wpdb->base_prefix}itsec_bans WHERE id = %d",
				$id
			), ARRAY_A );

			if ( $row ) {
				wp_cache_set( $id, $row, self::CACHE_GROUP );
			}
		}

		if ( ! $row ) {
			return null;
		}

		return $this->hydrate( $row );
	}

	public function get_creation_schema() {
		return [
			'type'       => 'object',
			'title'      => __( 'Manually Ban', 'it-l10n-ithemes-security-pro' ),
			'required'   => [ 'host' ],
			'properties' => [
				'host'    => [
					'type'        => 'string',
					'title'       => __( 'Host', 'it-l10n-ithemes-security-pro' ),
					'description' => __( 'The IP address to ban.', 'it-l10n-ithemes-security-pro' ),
					'arg_options' => [
						'validate_callback' => 'ITSEC_Lib_REST::validate_ip',
						'sanitize_callback' => 'ITSEC_Lib_REST::sanitize_ip',
					],
				],
				'created_by'   => [
					'title'       => __( 'Created By', 'it-l10n-ithemes-security-pro' ),
					'type'        => 'object',
					'required'    => [ 'type', 'id' ],
					'properties'  => [
						'type' => [
							'type'        => 'string',
							'enum'        => \ITSEC_Lib::pluck( $this->actor_factory->get_supported_factories(), 'get_slug' ),
							'description' => __( 'The type of the actor.', 'it-l10n-ithemes-security-pro' ),
						],
						'id'   => [
							'type'        => 'string',
							'description' => __( 'The identifier for the actor.', 'it-l10n-ithemes-security-pro' ),
						],
					],
					'description' => __( 'The actor who added the ban.', 'it-l10n-ithemes-security-pro' ),
				],
				'comment' => [
					'type'        => 'string',
					'maxLength'   => 255,
					'title'       => __( 'Notes', 'it-l10n-ithemes-security-pro' ),
					'description' => __( 'A comment describing the ban.', 'it-l10n-ithemes-security-pro' ),
				]
			],
			'uiSchema'   => [
				'created_by'   => [
					'ui:widget' => 'hidden',
				],
				'comment' => [
					'ui:widget'  => 'textarea',
					'ui:options' => [
						'rows' => 4,
					],
				],
			],
		];
	}

	public function get_update_schema() {
		return [
			'type'       => 'object',
			'title'      => __( 'Update Ban', 'it-l10n-ithemes-security-pro' ),
			'properties' => [
				'comment' => [
					'type'        => 'string',
					'maxLength'   => 255,
					'title'       => __( 'Notes', 'it-l10n-ithemes-security-pro' ),
					'description' => __( 'A comment describing the ban.', 'it-l10n-ithemes-security-pro' ),
				]
			],
			'uiSchema'   => [
				'comment' => [
					'ui:widget'  => 'textarea',
					'ui:options' => [
						'rows' => 4,
					],
				],
			],
		];
	}

	public function fill( array $data, Repository_Ban $ban = null ) {
		if ( $ban ) {
			if ( ! $ban instanceof Ban ) {
				throw new \BadMethodCallException( __( 'The "database" repository only accepts Ban instances.', 'it-l10n-ithemes-security-pro' ) );
			}

			return new Ban(
				$ban->get_host(),
				$ban->get_created_by(),
				isset( $data['comment'] ) ? $data['comment'] : $ban->get_comment(),
				$ban->get_created_at(),
				$ban->get_id()
			);
		}

		if ( isset( $data['created_by'] ) ) {
			$actor = $this->actor_factory->make( $data['created_by']['type'], $data['created_by']['id'] );
		} elseif ( is_user_logged_in() ) {
			$actor = new User( wp_get_current_user() );
		} else {
			$actor = null;
		}

		$comment = isset( $data['comment'] ) ? $data['comment'] : '';

		return new Ban( $data['host'], $actor, $comment );
	}

	public function persist( Repository_Ban $ban ) {
		if ( ! $ban instanceof Ban ) {
			throw new \BadMethodCallException( __( 'The "database" repository only accepts Host instances.', 'it-l10n-ithemes-security-pro' ) );
		}

		if ( ! $ban->get_id() ) {
			if ( \ITSEC_Lib::is_ip_whitelisted( $ban->get_host() ) ) {
				throw new WP_Error( new \WP_Error(
					'itsec_cannot_ban_authorized_host',
					__( 'Cannot ban an authorized host.', 'it-l10n-ithemes-security-pro' )
				) );
			}

			$record = [
				'host'       => $ban->get_host(),
				'created_at' => $ban->get_created_at()->format( 'Y-m-d H:i:s' ),
				'comment'    => $ban->get_comment(),
				'type'       => \ITSEC_Lib_IP_Tools::is_range( $ban->get_host() ) ? 'range' : 'ip',
			];

			if ( $actor = $ban->get_created_by() ) {
				$record['actor_type'] = $actor->get_type();
				$record['actor_id']   = $actor->get_identifier();
			}

			$suppress = $this->wpdb->suppress_errors();
			$r        = $this->wpdb->insert( $this->wpdb->base_prefix . 'itsec_bans', $record );
			$id       = $this->wpdb->insert_id;
			$this->wpdb->suppress_errors( $suppress );
		} else {
			$r  = $this->wpdb->update( $this->wpdb->base_prefix . 'itsec_bans', [
				'comment' => $ban->get_comment()
			], [ 'id' => $ban->get_id() ] );
			$id = $ban->get_id();
			wp_cache_delete( $ban->get_id(), self::CACHE_GROUP );
		}

		if ( false === $r ) {
			if ( false !== stripos( $this->wpdb->last_error, 'Duplicate entry' ) ) {
				throw new WP_Error( new \WP_Error(
					'itsec_duplicate_ban',
					__( 'This IP address is already banned.', 'it-l10n-ithemes-security-pro' )
				) );
			}

			throw new WP_Error( new \WP_Error(
				'itsec_save_ban_failed',
				sprintf( __( 'Failed to save the ban: %s', 'it-l10n-ithemes-security-pro' ), $this->wpdb->last_error )
			) );
		}

		if ( ! $ban->get_id() && \ITSEC_Lib_IP_Tools::is_range( $ban->get_host() ) ) {
			wp_cache_delete( 'ranges', self::CACHE_GROUP );
			$this->ranges = null;
		}

		return $this->get( $id );
	}

	public function delete( Repository_Ban $ban ) {
		$r = $this->wpdb->delete( $this->wpdb->base_prefix . 'itsec_bans', [
			'id' => $ban->get_id(),
		] );

		if ( false === $r ) {
			throw new WP_Error( new \WP_Error(
				'itsec_delete_ban_failed',
				sprintf( __( 'Failed to delete the ban: %s', 'it-l10n-ithemes-security-pro' ), $this->wpdb->last_error )
			) );
		}

		wp_cache_delete( $ban->get_id(), self::CACHE_GROUP );
	}

	/**
	 * Gets the raw list of all banned IP addresses.
	 *
	 * @internal This should almost never be used. It exists to polyfill data for legacy APIs.
	 *
	 * @return string[]
	 */
	public function get_legacy_hosts() {
		$tn   = $this->wpdb->base_prefix . 'itsec_bans';
		$rows = $this->wpdb->get_results( "SELECT `id`, `host` FROM {$tn}" );

		return wp_list_pluck( $rows, 'host', 'id' );
	}

	/**
	 * Hydrates a row from the database to a Ban object.
	 *
	 * @param array $row
	 *
	 * @return Ban
	 */
	protected function hydrate( $row ) {
		$actor      = $this->actor_factory->make( $row['actor_type'], $row['actor_id'] );
		$created_at = null;

		if ( $row['created_at'] ) {
			try {
				$created_at = new \DateTimeImmutable( $row['created_at'], new \DateTimeZone( 'UTC' ) );
			} catch ( \Exception $e ) {
			}
		}

		return new Ban( $row['host'], $actor, $row['comment'], $created_at, (int) $row['id'] );
	}

	/**
	 * Gets the list of IP ranges that are banned.
	 *
	 * @return string[]
	 */
	protected function get_ranges() {
		if ( ! is_array( $this->ranges ) ) {
			$rows = wp_cache_get( 'ranges', self::CACHE_GROUP );

			if ( ! is_array( $rows ) ) {
				$rows = $this->wpdb->get_results( "SELECT `id`, `host` FROM {$this->wpdb->base_prefix}itsec_bans WHERE `type` = 'range'", ARRAY_A );
				if ( is_array( $rows ) ) {
					wp_cache_set( 'ranges', $rows, self::CACHE_GROUP );
				}
			}

			$this->ranges = wp_list_pluck( $rows, 'host', 'id' );
		}

		return $this->ranges;
	}
}