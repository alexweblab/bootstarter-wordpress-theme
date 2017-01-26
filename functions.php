<?php
// Let WordPress manage the document title.
add_theme_support( 'title-tag' );

// Enable support for Post Thumbnails on posts and pages.
add_theme_support( 'post-thumbnails' );

// Theme Menus
register_nav_menus( array(
	'primary' => esc_html__( 'Primary', 'bootstarter' ),
) );

// Switch default core markup for search form, comment form, and comments to output valid HTML5.
add_theme_support( 'html5', array(
	'search-form',
	'comment-form',
	'comment-list',
	'gallery',
	'caption',
) );

// Custom template tags for this theme.
require get_template_directory() . '/inc/template-tags.php';

// Register Bootstrap Navigation Walker
require get_template_directory() . '/inc/wp_bootstrap_navwalker.php';

// Disable Woocommerce Default Styling
add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );

// Add Woocommerce support
function woocommerce_support() {
    add_theme_support( 'woocommerce' );
}
add_action( 'after_setup_theme', 'woocommerce_support' );

// Update the wrappers around Woocommerce pages
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
add_action('woocommerce_before_main_content', 'my_content_wrapper_start', 10);
function my_content_wrapper_start() {
	echo '<main id="main" class="site-main" role="main">';
}
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);
add_action('woocommerce_after_main_content', 'my_content_wrapper_end', 10);
function my_content_wrapper_end() {
	echo '</main><!-- #main -->';
}

// Remove Woocommerce actions
// remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10);
// remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0 );
// remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
// remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
// remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
// remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
// remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );

// Remove review tab on Woocommerce product page
// add_filter( 'woocommerce_product_tabs', 'remove_reviews_tab', 98);
// function remove_reviews_tab($tabs) {
// 	unset($tabs['reviews']);
// 	return $tabs;
// }

// Custom CSS for WYSIWYG editor
function custom_editor_style() {
	add_editor_style(get_template_directory_uri().'/css/style-wysiwyg-editor.css');
}
add_action('after_setup_theme', 'custom_editor_style');

// Add CSS to back end
function css_to_back_end() {
	if ( !current_user_can('manage_options') ) {
		echo '<link rel="stylesheet" href="'.get_template_directory_uri().'/css/style-for-editors.css">';
	}
}
add_action('admin_head', 'css_to_back_end');

// Remove some nodes from admin top bar menu
// add_action( 'admin_bar_menu', 'remove_some_nodes_from_admin_top_bar_menu', 999 );
// function remove_some_nodes_from_admin_top_bar_menu( $wp_admin_bar ) {
//     $wp_admin_bar->remove_menu( 'customize' );
// 	$wp_admin_bar->remove_menu( 'wp-logo' );
// 	$wp_admin_bar->remove_menu( 'updates' );
// 	$wp_admin_bar->remove_menu( 'comments' );
// 	$wp_admin_bar->remove_menu( 'new-content' );
// 	$wp_admin_bar->remove_menu( 'search' );
// }

// Remove some unwanted submenu entries for editors
add_action( 'admin_menu', 'remove_entries_for_editors', 999 );
function remove_entries_for_editors() {
	if ( !current_user_can('manage_options') ) {
		remove_submenu_page( 'themes.php', 'themes.php' );
		remove_submenu_page( 'themes.php', 'widgets.php' );
		$customize_url_arr = array();
	    $customize_url_arr[] = 'customize.php'; // 3.x
	    $customize_url = add_query_arg( 'return', urlencode( wp_unslash( $_SERVER['REQUEST_URI'] ) ), 'customize.php' );
	    $customize_url_arr[] = $customize_url; // 4.0 & 4.1
	    if ( current_theme_supports( 'custom-header' ) && current_user_can( 'customize') ) {
	        $customize_url_arr[] = add_query_arg( 'autofocus[control]', 'header_image', $customize_url ); // 4.1
	        $customize_url_arr[] = 'custom-header'; // 4.0
	    }
	    if ( current_theme_supports( 'custom-background' ) && current_user_can( 'customize') ) {
	        $customize_url_arr[] = add_query_arg( 'autofocus[control]', 'background_image', $customize_url ); // 4.1
	        $customize_url_arr[] = 'custom-background'; // 4.0
	    }
	    foreach ( $customize_url_arr as $customize_url ) {
	    	remove_submenu_page( 'themes.php', $customize_url );
	    }
	}
}

// customise the login
function my_login_logo_url() {
    return home_url();
}
add_filter( 'login_headerurl', 'my_login_logo_url' );
function my_login_logo_url_title() {
    return get_bloginfo( 'name' );
}
add_filter( 'login_headertitle', 'my_login_logo_url_title' );
function my_login_stylesheet() {
    wp_enqueue_style( 'custom-login', get_template_directory_uri() . '/css/style-login.css' );
}
add_action( 'login_enqueue_scripts', 'my_login_stylesheet' );

// custom excerpt lenght
function custom_excerpt_length( $length ) {
	return 16;
}
add_filter( 'excerpt_length', 'custom_excerpt_length', 999 );
