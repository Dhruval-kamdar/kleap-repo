<?php
/**
 * Extension Class
 *
 * Handles the extensions. Extensions can be themes or plugins depending on the context
 * You can check what type one extension is by accessing the property type
 *
 * @author      WP_Ultimo
 * @category    Admin
 * @package     WP_Ultimo/Model
 * @version     1.0.0
*/

if (!defined('ABSPATH')) {
  exit;
}

/**
 * WU_Extension; class.
 */
class WU_Extension {

  /**
   * Holds the ID of the WP_Post, to be used as the ID of each plan
   * @var integer
   */
  public $id = 0;

  /**
   * Holds the WP_Post Object of the Plan
   * @var null
   */
  public $post = null;

  /**
   * Holds the different categories this extension is part of
   * @var array
   */
  public $cats = array();

  /**
   * The status of the post
   * @var string
   */
  public $post_status = '';

  /**
   * Title of the extension
   * @var string
   */
  public $title;

  /**
   * Description
   * @var string
   */
  public $description;

  /**
   * Unique indentifier
   * @var string
   */
  public $slug;

  /**
   * Meta fields contained as attributes of each plan
   * @var array
   */
  public $meta_fields = array(
    'author',
    'type',
    'thumbnail',
    'display_version',
    'display_author',
    'display_details',
    'display_other',
    'plugin_file',
    'prices',
    'slug',
  );

  /**
   * Construct our new plan
   */
  public function __construct($extension = false) {

    if ( is_numeric( $extension ) ) {
      $this->id   = absint( $extension );
      $this->post = get_post( $extension );
      $this->get_extension( $this->id );
    } elseif ( $extension instanceof WU_Extension ) {
      $this->id   = absint( $extension->id );
      $this->post = $extension->post;
      $this->get_extension($this->id);
    } elseif ( isset( $extension->ID ) ) {
      $this->id   = absint( $extension->ID );
      $this->post = $extension;
      $this->get_extension( $this->id );
    }

  } // end construct;

  /**
   * Gets a plan from the database.
   * @param int  $id (default: 0).
   * @return bool
   */
  public function get_extension($id = 0) {

    if (!$id) {
      return false;
    }

    if ($result = get_blog_post(1, $id) ) {
      $this->populate( $result );
      return true;
    }

    return false;

  }

  /**
   * Populates an order from the loaded post data.
   * @param mixed $result
   */
  public function populate($result) {

    // Standard post data
    $this->id           = $result->ID;
    $this->post_status  = $result->post_status;

    $this->title       = $result->post_title;
    $this->description = $result->post_content;
    $this->slug        = $result->post_name;

    $file_path = WP_PLUGIN_DIR .'/'. $this->plugin_file;

    if (file_exists($file_path)) {

      $this->plugin_data = get_plugin_data($file_path);

    } // end if;

    switch_to_blog(1);

      $this->cats = wp_get_post_terms($this->id, $this->get_taxonomy(), array("fields" => "names"));

    restore_current_blog();

  } // end populate;

  /**
   * __isset function.
   * @param mixed $key
   * @return bool
   */
  public function __isset($key) {

    if (!$this->id) return false;

    // Swicth to main blog
    switch_to_blog(1);

      $value = metadata_exists('post', $this->id, 'wpu_' . $key);

    restore_current_blog();

    return $value;

  } // end __isset;

  /**
   * __get function.
   * @param mixed $key
   * @return mixed
   */
  public function __get($key) {

    // Swicth to main blog
    switch_to_blog(1);

      $value = metadata_exists('post', $this->id, 'wpu_' . $key) ? get_post_meta($this->id, 'wpu_' . $key, true) : true;

      // Thumbnails
      if ($key == 'thumbnail') {

        $value = get_post_meta($this->id, 'wpu_' . $key, true);

      } // end if;

      // Thumbnails
      if ($key == 'author') {

        $value = !is_bool($value) ? $value : '';

      } // end if;

    restore_current_blog();

    return $value;

  } // end __get;

  /**
   * Set attributes in a plan, based on a array. Useful for validation
   * @param array $atts Attributes
   */
  public function set_attributes($atts) {
    
    foreach($atts as $att => $value) {

      $this->{$att} = $value;

    } // end foreach;

    return $this;

  } // end set_attributes;

  /**
   * Return the right taxonomy for this particular type
   * @return string Right taxonomy for this type
   */
  public function get_taxonomy() {

    return "wu_extension_category_$this->type";
  
  } // end get_taxonomy;

  /**
   * Get the string containing the categories list
   * @return string
   */
  public function get_categories_string() {

    $this->cats = is_array($this->cats) ? $this->cats : array();

    if (empty($this->cats)) {

      return __('None', 'wp-ptm');

    } // end if;

    return implode(', ', $this->cats);

  } // end get_categories_string;

  /**
   * Get price based on the plan
   * @param  interger $plan_id
   * @return          
   */
  public function get_price($plan_id) {

    return 0; //isset($this->prices[$plan_id]) ? $this->prices[$plan_id] : false;

  } // end get_price;

  /**
   * Checks if it is free for that plan
   * @param  interger $plan_id
   * @return          
   */
  public function is_free($plan_id) {

    return !((boolean) $this->get_price($plan_id));

  } // end get_price;

  /**
   * Checks if this extension is unlocked for this particular site
   * @since  1.1.0
   * @param  interger $site_id
   * @return boolean
   */
  public function is_unlocked($site_id = false) {

    $site = $site_id ? wu_get_site($site_id) : wu_get_current_site();

    if (!$site) {

      return false;

    }

    $unlocked_extensions = (array) $site->get_meta('unlocked_extensions');

    return in_array($this->slug, $unlocked_extensions);

  } // end is_unlocked;

  /**
   * Unlock a extension to a specific site
   * @since  1.1.0
   * @param  interger $site_id
   * @return boolean
   */
  public function unlock($site_id = false) {

    $site = $site_id ? wu_get_site($site_id) : wu_get_current_site();

    if (!$site) {

      return false;

    }

    $unlocked_extensions = (array) $site->get_meta('unlocked_extensions');

    $unlocked_extensions[] = $this->slug;

    $unlocked_extensions = array_unique($unlocked_extensions);

    return $site->set_meta('unlocked_extensions', $unlocked_extensions);

  } // end unlock;

  /**
   * Unlock a extension to a specidif site
   * @since  1.1.0
   * @param  interger $site_id
   * @return boolean
   */
  public function lock($site_id = false) {

    $site = $site_id ? wu_get_site($site_id) : wu_get_current_site();

    if (!$site) {

      return false;

    }

    $unlocked_extensions = (array) $site->get_meta('unlocked_extensions');

    unset($unlocked_extensions[$this->slug]);

    return $site->set_meta('unlocked_extensions', $unlocked_extensions);

  } // end unlock;

  /**
   * Get the plugin title
   * @return string
   */
  public function get_title() {

    if (!$this->title) {

      return isset($this->plugin_data['Name']) ? $this->plugin_data['Name'] : '';

    } else {

      return $this->title;

    }

  } // end get_title;

  /**
   * Get the extension thumbnail
   * @param  string $size Size you want to retrieve
   * @return string       URL of the thumbnail
   */
  public function get_thumbnail($size = 'full') {
    
    $logo = $this->thumbnail;

    if (!$logo) {

      if ($this->type === 'theme') {

        $theme = wp_get_theme($this->slug);

        return $theme->get_screenshot();

      }

    }

    if (is_numeric($logo)) {

      switch_to_blog(1);

        $attach = wp_get_attachment_image_src($logo, $size);

      restore_current_blog();

      return $attach[0];

    } 

    return WP_Ultimo_PTM()->get_asset('extension-placeholder.png');

  } // end get_thumbnail;

  public function delete() {

    switch_to_blog( get_current_site()->blog_id );

      /**
       * Permanenty removes the model
       * 
       * @since 1.9.6
       */
      $is_deleted = wp_delete_post($this->id, true);
    
    restore_current_blog();

    return $is_deleted;

  } // end delete;

  /**
   * Save the current Extension
   */
  public function save() {

    // Swicth to main blog
    switch_to_blog(1);

      $this->title = wp_strip_all_tags($this->title);

      $this->description = $this->description ?: ' ';

      $extension_post = array(
        'ID'            => $this->id,
        'post_title'    => $this->title,
        'post_content'  => $this->description,
        'post_name'     => $this->slug,
        'post_type'     => 'wu_extension',
        'post_status'   => 'publish',
      );

      // Insert Post
      $this->id = wp_insert_post($extension_post, true);

      // Add the meta
      foreach ($this->meta_fields as $meta) {

        update_post_meta($this->id, 'wpu_'.$meta, $this->{$meta});

      }

      wp_set_object_terms($this->id, $this->cats, $this->get_taxonomy(), false);

    // Do something
    restore_current_blog();
    
    // Return the id of the new post
    return $this->id;

  } // end save;

} // end Class WU_Extension;

/**
 * Return the Extension, after changing to the main site
 * @param  string $slug Plugin to get
 * @return object       WU_Extension object, or false
 */
function wu_get_extension($slug) {

  // Swicth to main blog
  switch_to_blog(1);

  $posts = get_posts( array( 
      'name'           => $slug, 
      'post_type'      => 'wu_extension',
      'post_status'    => 'publish',
      'posts_per_page' => 1
  ));

  restore_current_blog();

  if ($posts) {

    $post = $posts[0];

    $extension = new WU_Extension($post);

  } else {

    $extension = new WU_Extension();

  }// end if;

  $extension->slug = $slug;

  return $extension;

} // end wu_get_extension;

/**
 * Return the Extension, after changing to the main site
 * @param  string $plugin_file Plugin to get
 * @return object              WU_Extension object, or false
 */
function wu_get_extension_by_plugin_file($plugin_file) {

  // Swicth to main blog
  switch_to_blog(1);

  $posts = get_posts( array(
      'post_type'      => 'wu_extension',
      'post_status'    => 'publish',
      'posts_per_page' => 1,
      'meta_query'     => array(
        'key'           => 'plugin_file',
        'value'         => $plugin_file,
      )
  ));

  restore_current_blog();

  if ($posts) {

    $post = $posts[0];

    $extension = new WU_Extension($post);

  } else {

    $extension = new WU_Extension();

  }// end if;

  return $extension;

} // end wu_get_extension_by_plugin_file;

/**
 * Return multiple Extensions, after changing to the main site
 *
 * @param array $multiple_slugs
 * @return array
 */
function wu_get_multiple_extensions($multiple_slugs = array()) {

   // Swicth to main blog
   switch_to_blog(1);

    $posts = get_posts( array(
      'post_name__in' => $multiple_slugs,
      'post_type'      => 'wu_extension',
      'post_status'    => 'publish',
      'posts_per_page' => -1
    ));

   restore_current_blog();

   $extensions = array();

   if ($posts) {

    foreach ($posts as $post) {

      $extensions[] = new WU_Extension($post);

    }// end if;

  }// end if;

  return $extensions;

}// end wu_get_multiple_extensions()

/**
 * Get extension by slug in array of extensions
 *
 * @param string $slug
 * @param array $extensions
 * @return WU_Extension
 */
function get_extension_by_name_in_array($slug, $extensions) {

  foreach ($extensions as $extension) {

    if ($extension->slug == $slug) {

      return $extension;

    } // end if;
  
  } // end foreach;

  // nothing
  return new WU_Extension();

} // get_extension_by_name_in_array;
