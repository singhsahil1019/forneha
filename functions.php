<?php
/**
 * scratch functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package scratch
 */

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '1.0.0' );
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function scratch_setup() {
	/*
		* Make theme available for translation.
		* Translations can be filed in the /languages/ directory.
		* If you're building a theme based on scratch, use a find and replace
		* to change 'scratch' to the name of your theme in all the template files.
		*/
	load_theme_textdomain( 'scratch', get_template_directory() . '/languages' );

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

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus(
		array(
			'menu-1' => esc_html__( 'Primary', 'scratch' ),
		)
	);

	/*
		* Switch default core markup for search form, comment form, and comments
		* to output valid HTML5.
		*/
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	// Set up the WordPress core custom background feature.
	add_theme_support(
		'custom-background',
		apply_filters(
			'scratch_custom_background_args',
			array(
				'default-color' => 'ffffff',
				'default-image' => '',
			)
		)
	);

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);
}
add_action( 'after_setup_theme', 'scratch_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function scratch_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'scratch_content_width', 640 );
}
add_action( 'after_setup_theme', 'scratch_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function scratch_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'scratch' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'scratch' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'scratch_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function scratch_scripts() {
	wp_enqueue_style( 'scratch-style', get_stylesheet_uri(), array(), _S_VERSION );
	wp_style_add_data( 'scratch-style', 'rtl', 'replace' );

	wp_enqueue_script( 'scratch-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	wp_enqueue_script('jquery');

	if ( is_page_template( 'templates/template-tab.php' ) ) {
		wp_enqueue_script( 'custom-script', get_template_directory_uri() . '/assets/js/custom.js', array(), null, true );
		wp_localize_script('custom-script', 'ajax_object', array(
			'ajax_url' => admin_url('admin-ajax.php')
		// 'nonce' => wp_create_nonce('ajax_nonce') 
		));
	}

	if ( is_page_template( 'templates/template-nav.php' ) ) {
		wp_enqueue_script( 'custom-nav', get_template_directory_uri() . '/assets/js/custom-nav.js', array(), null, true );
		wp_localize_script('custom-nav', 'ajax_object', array(
			'ajax_url' => admin_url('admin-ajax.php')
		// 'nonce' => wp_create_nonce('ajax_nonce') 
		));
	}


	if ( is_page_template( 'templates/template-balance.php' ) ) {
		wp_enqueue_script( 'custom-balance', get_template_directory_uri() . '/assets/js/custom-balance.js', array(), null, true );
		wp_localize_script('custom-balance', 'ajax_object', array(
			'ajax_url' => admin_url('admin-ajax.php')
		// 'nonce' => wp_create_nonce('ajax_nonce') 
		));
	}
	
}
add_action( 'wp_enqueue_scripts', 'scratch_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

function QuadLayers_custom_post_type() {
	// Set UI labels for Custom Post Type
		$labels = array(
			'name'                => _x( 'Courses', 'Post Type General Name', 'storefront' ),
			'singular_name'       => _x( 'Course', 'Post Type Singular Name', 'storefront' ),
			'menu_name'           => __( 'Courses', 'storefront' ),
			'parent_item_colon'   => __( 'Parent Course', 'storefront' ),
			'all_items'           => __( 'All Courses', 'storefront' ),
			'view_item'           => __( 'View Course', 'storefront' ),
			'add_new_item'        => __( 'Add New Course', 'storefront' ),
			'add_new'             => __( 'Add New', 'storefront' ),
			'edit_item'           => __( 'Edit Course', 'storefront' ),
			'update_item'         => __( 'Update Course', 'storefront' ),
			'search_items'        => __( 'Search Course', 'storefront' ),
			'not_found'           => __( 'Not Found', 'storefront' ),
			'not_found_in_trash'  => __( 'Not found in Trash', 'storefront' ),
		);
	// Set other options for Custom Post Type
		$args = array(
			'label'               => __( 'Courses', 'storefront' ),
			'description'         => __( 'Course news and reviews', 'storefront' ),
			'labels'              => $labels,  
			'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields', ),     
			'taxonomies'          => array( 'genres' ),     
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => true,
			'menu_position'       => 5,
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'capability_type'     => 'post',
			'show_in_rest' => true, 
		);
		// Registering your Custom Post Type
		register_post_type( 'courses', $args );
	}
	add_action( 'init', 'QuadLayers_custom_post_type', 0 );

	function load_tab_content_old() {
		//check_ajax_referer('ajax_nonce', 'security'); // Verify nonce
	
		if (isset($_POST['post_id'])) {
			$post_id = intval($_POST['post_id']);
			$post = get_post($post_id);
	
			if ($post) {
				echo '<h2>' . esc_html(get_the_title($post)) . '</h2>';
				echo '<div>' . apply_filters('the_content', $post->post_content) . '</div>';
			} else {
				echo 'No content found.';
			}
		}
		wp_die();
	}
	//add_action('wp_ajax_load_tab_content', 'load_tab_content');
	//add_action('wp_ajax_nopriv_load_tab_content', 'load_tab_content');
	
	function load_tab_content() {
		//check_ajax_referer('ajax_nonce', 'security'); // Verify nonce
	
		if (isset($_POST['id'])) {
			$post_id = intval($_POST['id']);
			$post = get_post($post_id);
	
			if ($post) {
				echo '<h2>' . esc_html(get_the_title($post)) . '</h2>';
				echo '<div>' . apply_filters('the_content', $post->post_content) . '</div>';
			} else {
				echo 'No content found.';
			}
		}
		wp_die();
	}
	
	function load_term_content() {
		//check_ajax_referer('ajax_nonce', 'security'); // Verify nonce
	
		if (isset($_POST['id'])) {
			$term_name = sanitize_text_field($_POST['id']);
			$term = get_term_by('id', $term_name, 'courses_category'); // Replace with your actual taxonomy
	
			if ($term) {
				echo '<h2>' . esc_html($term->name) . '</h2>';
				// Output additional information about the term
				echo '<div>' . esc_html($term->description) . '</div>'; // You can customize what to show here
			} else {
				echo 'No content found for this term.';
			}
		}
		wp_die();
	}
	
	add_action('wp_ajax_load_tab_content', 'load_tab_content');
	add_action('wp_ajax_nopriv_load_tab_content', 'load_tab_content');
	add_action('wp_ajax_load_term_content', 'load_term_content');
	add_action('wp_ajax_nopriv_load_term_content', 'load_term_content');
	