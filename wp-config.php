<?php
# BEGIN WP Hide & Security Enhancer
define('WPH_WPCONFIG_LOADER',          TRUE);
include_once( ( defined('WP_PLUGIN_DIR')    ?     WP_PLUGIN_DIR   .   '/wp-hide-security-enhancer-pro/'    :      ( defined( 'WP_CONTENT_DIR') ? WP_CONTENT_DIR  :   dirname(__FILE__) . '/' . 'wp-content' )  . '/plugins/wp-hide-security-enhancer-pro' ) . '/include/wph.class.php');
if (class_exists('WPH')) { global $wph; $wph    =   new WPH(); ob_start( array($wph, 'ob_start_callback')); }
# END WP Hide & Security Enhancer
define( 'WP_CACHE', false ); 
 define('WP_MEMORY_LIMIT', '1024M'); 
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */
// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'qsjtrchhdu');
/** MySQL database username */
define('DB_USER', 'qsjtrchhdu');
/** MySQL database password */
define('DB_PASSWORD', 'tMfveJBjS7');
/** MySQL hostname */
define('DB_HOST', 'localhost');
/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');
/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');
/**#@+
 * Authentication Unique Keys and Salts.
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 */
require('wp-salt.php');
/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';
/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('FS_METHOD','direct');
define('WPLANG', '');
define('FS_CHMOD_DIR', (0775 & ~ umask()));
define('FS_CHMOD_FILE', (0664 & ~ umask()));
/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('FLUENTCRM_IS_DEV_FEATURES', false);
ini_set('display_errors','false');
ini_set('error_reporting', E_ALL );
define('WP_DEBUG', false);
define('WP_DEBUG_DISPLAY', false);
/* Multisite */
define( 'WP_ALLOW_MULTISITE', true );
define( 'PH_SECURE_AUTH_KEY', '4n&|i]}>ePy?1FXp1i[[>6S0Y]Flk>Wq<G0xT.I8bO:fR5=b]M$ZCHkOloJT*H=!' );
/* That's all, stop editing! Happy blogging. */
define('MULTISITE', true);
define('SUBDOMAIN_INSTALL', true);
define('DOMAIN_CURRENT_SITE', 'kleap.co');
define('PATH_CURRENT_SITE', '/');
define('SITE_ID_CURRENT_SITE', 1);
define('BLOG_ID_CURRENT_SITE', 1);
define('SUNRISE', true);
//define('DISABLE_WP_CRON', true);
/* WP Ultimo: Adding automatically domain syncing with Cloudways */
define('WU_CLOUDWAYS', true);                       // Tells WP Ultimo we should connect to Cloudways
define('WU_CLOUDWAYS_EMAIL', 'eliott.dupuy@lionscreative.ch');    // The email address you use to login on Cloudways
define('WU_CLOUDWAYS_API_KEY', 'LiFdSlgBJ6BSOSWAoIv6vXssXtxotb');     // API Key obtained on step 1
define('WU_CLOUDWAYS_SERVER_ID', '422845'); // Server ID obtained on step 2
define('WU_CLOUDWAYS_APP_ID', '1328280');       // App ID obtained on step 3
define('WU_CLOUDWAYS_EXTRA_DOMAINS', '*.lionsbuild.com');
/* end WP Ultimo */
/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
        define('ABSPATH', dirname(__FILE__) . '/');
/** Sets up WordPress vars and included files. */
//define("ADMIN_COOKIE_PATH", "/admin");
require_once(ABSPATH . 'wp-settings.php');