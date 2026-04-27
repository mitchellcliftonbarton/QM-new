<?php

require_once( __DIR__ . '/vendor/autoload.php' );
$timber = new Timber\Timber();

/*
----------------
INCLUDES
----------------
*/

require('register-acf-blocks.php');
require('custom-post-types.php');
require('editorial-updates.php');
require('admin-template-overrides.php');
require('weather-check.php');
require('download_ics.php');
require('time_helpers.php');
require('routes.php');

// date_default_timezone_set("America/New_York");


/*
----------------
TIMBER STUFF
----------------
*/

if ( ! class_exists( 'Timber' ) ) {
	add_action( 'admin_notices', function() {
		echo '<div class="error"><p>Timber not activated. Make sure you activate the plugin in <a href="' . esc_url( admin_url( 'plugins.php#timber' ) ) . '">' . esc_url( admin_url( 'plugins.php' ) ) . '</a></p></div>';
	});

	add_filter('template_include', function( $template ) {
		return get_stylesheet_directory() . '/static/no-timber.html';
	});

	return;
}

/**
 * Sets the directories (inside your theme) to find .twig files
 */
Timber::$dirname = array( 'templates', 'views' );

/**
 * By default, Timber does NOT autoescape values. Want to enable Twig's autoescape?
 * No prob! Just set this value to true
 */
Timber::$autoescape = false;


/**
 * We're going to configure our theme inside of a subclass of Timber\Site
 * You can move this to its own file and include here via php's include("MySite.php")
 */
class StarterSite extends Timber\Site {
	/** Add timber support. */
	public function __construct() {
		add_action( 'after_setup_theme', array( $this, 'theme_supports' ) );
		add_filter( 'timber/context', array( $this, 'add_to_context' ) );
		add_filter( 'timber/twig', array( $this, 'add_to_twig' ) );
		add_action( 'init', array( $this, 'register_post_types' ) );
		add_action( 'init', array( $this, 'register_taxonomies' ) );
		parent::__construct();
	}
	/** This is where you can register custom post types. */
	public function register_post_types() {

	}
	/** This is where you can register custom taxonomies. */
	public function register_taxonomies() {

	}

	/** This is where you add some context
	 *
	 * @param string $context context['this'] Being the Twig's {{ this }}.
	 */
	public function add_to_context( $context ) {
		global $wp;

		$main_menu = new Timber\Menu('main-menu');
		$mobile_main_menu = new Timber\Menu('mobile-main-menu');
		$social_links_menu = new Timber\Menu('social-links');
		$secondary_footer_menu = new Timber\Menu('secondary-footer-links');
		$site = $this;
		$context['site'] = $this;
		$context['main_menu'] = $main_menu;
		$context['mobile_main_menu'] = $mobile_main_menu;
		$context['social_links_menu'] = $social_links_menu;
		$context['secondary_footer_menu'] = $secondary_footer_menu;
		$context['isHome'] = is_home();
		$context['full_url'] = home_url( $wp->request );

		// TIME STUFF
		$openTimes = getOpenTimes();

		// $context['is_open'] = $openTimes['is_open'];
		// print_r($openTimes);
		$context['open_time'] = $openTimes ? $openTimes['open_time'] : false;
		$context['close_time'] = $openTimes ? $openTimes['close_time'] : false;
		$is_homepage = false;
		
		if (is_front_page()) {
			$is_homepage = true;
		}
		
		$context['is_homepage'] = $is_homepage;

		return $context;
	}

	public function theme_supports() {
		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5', array(
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
			)
		);

		/*
		 * Enable support for Post Formats.
		 *
		 * See: https://codex.wordpress.org/Post_Formats
		 */
		add_theme_support(
			'post-formats', array(
				'aside',
				'image',
				'video',
				'quote',
				'link',
				'gallery',
				'audio',
			)
		);

		add_theme_support( 'menus' );
	}

	/** This Would return 'foo bar!'.
	 *
	 * @param string $text being 'foo', then returned 'foo bar!'.
	 */
	public function myfoo( $text ) {
		$text .= ' bar!';
		return $text;
	}

	/** This is where you can add your own functions to twig.
	 *
	 * @param string $twig get extension.
	 */
	public function add_to_twig( $twig ) {
		$twig->addExtension( new Twig_Extension_StringLoader() );
		$twig->addFilter( new Twig_SimpleFilter( 'myfoo', array( $this, 'myfoo' ) ) );
		return $twig;
	}

}

/*
----------------
ROUTES
----------------
*/

createRoutes();

/*
----------------
EDITORIAL UPDATES
----------------
*/

// change 'posts' to 'editorial' in wp admin sidebar. It still is the 'post' type, but just changing labels
add_action( 'init', 'change_post_object_label' );
add_action( 'admin_menu', 'change_post_menu_label' );


/*
----------------
REMOVE COMMENTS FROM SITE
----------------
*/

add_action( 'admin_menu', 'pk_remove_admin_menus' ); // Removes from admin menu
add_action('init', 'pk_remove_comment_support', 100); // Removes from post and pages
add_action( 'wp_before_admin_bar_render', 'pk_remove_comments_admin_bar' ); // Removes from admin bar

/*
----------------
ADMIN TEMPLATE UPDATES
----------------
*/

add_action('admin_init', 'remove_textarea');
add_action('init', 'remove_featured_image');
add_action('init', 'remove_editor');

// hide admin bar
add_filter('show_admin_bar', '__return_false');


/*
----------------
CUSTOM POST TYPES
----------------
*/

add_action( 'init', 'create_exhibitions' ); // add exhibitions
add_action( 'init', 'create_events' ); // add events
add_action( 'init', 'create_events_taxonomy', 0 ); // event categories
add_action( 'init', 'create_staff_members' ); // add staff members
add_action( 'init', 'create_staff_taxonomy', 0 ); // add staff categories
add_action( 'init', 'create_programs' ); // add programs
add_action( 'init', 'create_program_taxonomy', 0 ); // add program categories
add_action( 'init', 'create_videos' ); // add videos
add_action( 'init', 'create_video_taxonomy', 0 ); // add video categories





/*
----------------
ADD SITEWIDE DATA OPTIONS PAGE
----------------
*/

if( function_exists('acf_add_options_page') ) {
	acf_add_options_page(array(
		'page_title' => 'Sitewide Data',
		'menu_title' => 'Sitewide Data',
		'position'   => '60'
	));
}


/*
----------------
REGISTER ACF BLOCKS
----------------
*/

add_action( 'acf/init', 'my_acf_init' );
add_filter( 'allowed_block_types_all', 'my_plugin_allowed_block_types', 10, 2 );


/*
----------------
WEATHER CHECK
----------------
*/

add_action('wp_ajax_nopriv_weather_check', 'weather_check');
add_action('wp_ajax_weather_check', 'weather_check');


/*
----------------
ICS FUNCTIONALITY
----------------
*/

// echo date('F j, Y g:i a', strtotime('20220405T160000Z'));

$args = [
	'id' => isset($_POST['id']) ? $_POST['id'] : false,
	'date' => isset($_POST['date']) ? $_POST['date'] : false
];

add_action('wp_ajax_nopriv_download_ics', function() use ( $args ) { download_ics( $args ); });
add_action('wp_ajax_download_ics', function() use ( $args ) { download_ics( $args ); });


/*
----------------
CUSTOM FUNCTIONS
----------------
*/

function trunc_func( $value, $chars ) {
  $length = strlen($value);

	if ($length >= $chars) {
		return rtrim(substr($value, 0, $chars)) . '...';
	} else {
		return $value;
	}
}

add_filter( 'timber/twig', 'add_to_twig' );

function add_to_twig ($twig) {
	$twig->addFunction( new Timber\Twig_Function( 'checkOpenStatus', 'checkOpenStatus' ) );
	$twig->addFilter( new Twig_SimpleFilter( 'trunc', 'trunc_func' ) );
	return $twig;
}

/*
----------------
FINAL TIMBER STUFF
----------------
*/

add_filter( 'timber_context', 'mytheme_timber_context'  );

function mytheme_timber_context( $context ) {
    $context['options'] = get_fields('option');
    return $context;
}

new StarterSite();
