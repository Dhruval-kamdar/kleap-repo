<?php

/**
 * Comment Data
 *
 * @package     ProjectHuddle
 * @copyright   Copyright (c) 2015, Andre Gagnon
 * @since       2.6.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
	exit;
}

/**
 * Website Comment Class
 *
 * This class handles all comment data
 *
 * @since 2.6.0
 */
class PH_Comment extends PH_REST_Comments_Controller
{
	/**
	 * Model to use for getting model data
	 *
	 * @var string
	 */
	protected $model = '\PH\Models\Comment';

	/**
	 * PH_Website_Comment_New constructor.
	 *
	 * @param array $args
	 */
	public function __construct(array $args = array())
	{
		// parent constructor
		parent::__construct($args);

		// set rest base
		$this->rest_base = 'comments';

		// register this comment route on construct
		add_action('rest_api_init', array($this, 'register_routes'));

		// comment actions for all registered ph comments
		add_action('wp_insert_comment', array($this, 'new_comment'), 10, 2);
		add_action('edit_comment', array($this, 'edit_comment'), 10, 2);
		add_action('trashed_comment', array($this, 'trash_comment'), 10, 2);

		// set item id if it exists in schema
		add_action('ph_website_publish_comment', array($this, 'set_parent_ids'), 10, 2);
		add_action('ph_website_publish_approval', array($this, 'set_parent_ids'), 10, 2);
		add_action('ph_mockup_publish_comment', array($this, 'set_parent_ids'), 10, 2);
		add_action('ph_mockup_publish_approval', array($this, 'set_parent_ids'), 10, 2);

		// if a comment or action is performed, add as a thread member
		add_action('ph_website_publish_comment', array($this, 'add_thread_member'), 10, 2);
		add_action('ph_website_publish_approval', array($this, 'add_thread_member'), 10, 2);
		add_action('ph_mockup_publish_comment', array($this, 'add_thread_member'), 10, 2);
		add_action('ph_mockup_publish_approval', array($this, 'add_thread_member'), 10, 2);

		add_action('ph_website_publish_comment', array($this, 'maybe_mention_members'), 10, 2);
		add_action('ph_mockup_publish_comment', array($this, 'maybe_mention_members'), 10, 2);

		add_action('rest_api_init', array($this, 'project_id'));
		add_action('rest_api_init', array($this, 'item_id'));
		add_action('rest_api_init', array($this, 'post_type'));
		add_action('rest_api_init', array($this, 'approval_data'));

		$this->rest = new PH_Rest_Request($this->rest_base);

		add_filter('map_meta_cap', [$this, 'read_caps'], 10, 4);
	}

	/**
	 * Filter on the current_user_can() function.
	 * This function is used to explicitly allow users to edit their own comments
	 * Regardless of their capabilities or roles.
	 *
	 * @param string[] $caps    Array of the user's capabilities.
	 * @param string   $cap     Capability name.
	 * @param int      $user_id The user ID.
	 * @param array    $args    Adds the context to the cap. Typically the object ID.
	 */
	public function read_caps($caps, $cap, $user_id, $args)
	{
		// Bail out if we're not asking about a post:
		if ('read_comment' !== $cap) {
			return $caps;
		}

		// bail if no post
		if (!$comment = get_comment($args[0])) {
			return $caps;
		}

		// bail if not our comment type
		if (!in_array($comment->comment_type, ph_get_comment_types())) {
			return $caps;
		}

		if (!$comment->comment_post_ID) {
			return $caps;
		}

		if (!user_can($user_id, $cap, $comment->comment_post_ID)) {
			$caps[] = 'do_not_allow';
		}

		return $caps;
	}

	/**
	 * Get model by id
	 */
	public function get($id = 0, $autoload_comment = true, $autoload_comment_meta = true)
	{
		return new $this->model($id, $autoload_comment, $autoload_comment_meta);
	}

	/**
	 * Maybe trigger a user mention on a comment
	 *
	 * @param integer $id
	 * @param WP_Comment $comment
	 * @return void
	 */
	public function maybe_mention_members($id, $comment)
	{
		$mentioned = [];
		// must have DOMDocument
		if (class_exists('DOMDocument')) {
			$dom = new DOMDocument;
			$dom->loadHTML($comment->comment_content);

			// get all data-mention-id tags
			foreach ($dom->getElementsByTagName('span') as $tag) {
				foreach ($tag->attributes as $attribName => $attribNode) {
					if ('data-mention-id' === $attribName) {
						// must be a valid user
						if (!$user = get_user_by('ID', $attribNode->value)) {
							continue;
						}
						$mentioned[] = $user->ID;
						// do action
						do_action('ph_mention_user', $user->ID, $id, $comment);
					}
				}
			}
		}

		// only send mentioned comments to mentioned users
		if (!empty($mentioned)) {
			if (!apply_filters('ph_send_mentioned_comments_to_all_users', false)) {
				return;
			}
		}

		$comment_type = ph_get_comment_project_type($id);

		do_action("ph_{$comment_type}_publish_comment_after_mentions", $id, $comment, $mentioned);
		do_action("ph_project_publish_comment_after_mentions", $id, $comment, $mentioned);
	}

	/**
	 * Add a user as a thread "member" when they add a comment
	 *
	 * @param integer $id
	 * @param WP_Comment $comment
	 * @return void
	 */
	public function add_thread_member($id, $comment)
	{
		// get parent ids
		$parents = ph_get_parents_ids($comment, 'comment');
		// set item id
		if ($parents['thread'] && $comment->user_id) {
			if (false === get_userdata($comment->user_id)) {
				return;
			}

			ph_add_member_to_thread(
				array(
					'user_id' => $comment->user_id,
					'post_id' => $parents['thread'],
				)
			);
		}
	}

	/**
	 * Always store item id for easier querying
	 * An Item is a generic name for either a website
	 * page or mockup image
	 *
	 * @param $comment WP_Comment
	 * @param $id Comment ID
	 */
	public function set_parent_ids($id, $comment)
	{
		// get parent ids
		$parents = ph_get_parents_ids($comment, 'comment');

		// set item id
		if ($parents['item']) {
			// update meta
			update_comment_meta($comment->comment_ID, 'item_id', (int) $parents['item']);
		}

		// set project id
		if ($parents['project']) {
			// update meta
			update_comment_meta($comment->comment_ID, 'project_id', (int) $parents['project']);
		}
	}

	public function approval_data()
	{
		register_rest_field(
			'comment',
			'approval',
			array(
				'update_callback' => null,
				'get_callback'    => function ($post, $attr, $request, $object_type) {
					return (bool) get_comment_meta($post['id'], $attr, true);
				},
				'schema'          => array(
					'description' => esc_html__('Whether the approval comment was for approval or not.', 'project-huddle'),
					'type'        => 'integer',
					'default'     => 0,
					'readonly'    => true,
				),
			)
		);
	}

	public function project_id()
	{
		register_rest_field(
			'comment',
			'project_id',
			array(
				'update_callback' => null,
				'get_callback'    => function ($post, $attr, $request, $object_type) {
					return (int) get_comment_meta($post['id'], $attr, true);
				},
				'schema'          => array(
					'description' => esc_html__('ID of the project.', 'project-huddle'),
					'type'        => 'integer',
					'default'     => 0,
					'readonly'    => true,
				),
			)
		);
	}

	public function item_id()
	{
		register_rest_field(
			'comment',
			'item_id',
			array(
				'update_callback' => null,
				'get_callback'    => function ($post, $attr, $request, $object_type) {
					return (int) get_comment_meta($post['id'], $attr, true);
				},
				'schema'          => array(
					'description' => esc_html__('ID of the project.', 'project-huddle'),
					'type'        => 'integer',
					'default'     => 0,
					'readonly'    => true,
				),
			)
		);
	}

	public function post_type()
	{
		register_rest_field(
			'comment',
			'comment_post_type',
			array(
				'update_callback' => null,
				'get_callback'    => function ($post, $attr, $request, $object_type) {
					$type = get_post_type($post['post']);

					switch ($type):
						case 'phw_comment_loc':
						case 'ph_comment_location':
							return 'thread';
							break;
						case 'project_image':
							return 'image';
							break;
						case 'website_page':
							return 'page';
							break;
						case 'ph-project':
						case 'ph-website':
							return 'project';
							break;
						default:
							return $type;
							break;
					endswitch;
				},
				'schema'          => array(
					'description' => esc_html__('Post type of the parent post.', 'project-huddle'),
					'type'        => 'string',
					'default'     => '',
					'readonly'    => true,
				),
			)
		);
	}

	/**
	 * Trigger the correct action
	 *
	 * @param $action  string Action to trigger
	 * @param $id      integer Comment ID
	 * @param $comment WP_Comment Comment Object
	 */
	function do_action($action, $id, $comment)
	{
		if (in_array($comment->comment_type, ph_get_comment_types())) {
			// get endpoint type based on parent
			$project_type = ph_get_comment_project_type($id);
			// comment type name
			$comment_type = ph_comment_type_name($comment->comment_type);
			// run action
			do_action("ph_{$project_type}_{$action}_{$comment_type}", $id, $comment);
		}
	}

	/**
	 * New comment notification
	 *
	 * @param            $id
	 * @param WP_Comment $comment
	 */
	function new_comment($id, $comment)
	{
		$this->do_action('publish', $id, $comment);
	}

	/**
	 * New comment notification
	 *
	 * @param            $id
	 * @param array $data Comment data
	 */
	function edit_comment($id, $data)
	{
		$comment = get_comment($id);
		$this->do_action('edit', $id, $comment);
	}

	/**
	 * New comment notification
	 *
	 * @param            $id
	 * @param WP_Comment $comment
	 */
	function trash_comment($id, $comment)
	{
		$this->do_action('delete', $id, $comment);
	}
}
