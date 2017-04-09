<?php
/**
 * Function describe for LayerStore 
 * 
 * @package layerstore
 */
include_once( trailingslashit( get_stylesheet_directory() ) . 'lib/layerstore-metaboxes.php' );
include_once( trailingslashit( get_stylesheet_directory() ) . 'lib/custom-config.php' );

add_action( 'wp_enqueue_scripts', 'layerstore_enqueue_styles', 999 );
function layerstore_enqueue_styles() {
  $parent_style = 'layerstore-parent-style';
    wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'layerstore-child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array( $parent_style )
    );
  wp_enqueue_script( 'layerstore-custom-script', get_stylesheet_directory_uri() . '/js/layerstore-custom.js', array('jquery'), '1' );
}

function layerstore_theme_setup() {
    
    load_child_theme_textdomain( 'layerstore', get_stylesheet_directory() . '/languages' );
    
    add_image_size( 'maxstore-category', 600, 600, true );
    
    // Add Custom logo Support.
		add_theme_support( 'custom-logo', array(
			'height'      => 100,
			'width'       => 400,
			'flex-height' => true,
			'flex-width'  => true,
		) );
		
		// Add Custom Background Support.
		$args = array(
			'default-color' => 'ffffff',
		);
		add_theme_support( 'custom-background', $args );
}
add_action( 'after_setup_theme', 'layerstore_theme_setup' );

// Remove admin options.
function layerstore_custom_remove( $wp_customize ) {
    
    $wp_customize->remove_control( 'header-logo' );
    $wp_customize->remove_section( 'site_bg_section' );
}
add_action( 'customize_register', 'layerstore_custom_remove', 100);

// Remove parent theme homepage style.
function layerstore_remove_page_templates( $templates ) {
    unset( $templates['template-home.php'] );
    return $templates;
}
add_filter( 'theme_page_templates', 'layerstore_remove_page_templates' );

// Load theme info page.
if ( is_admin() ) {
	include_once(trailingslashit( get_template_directory() ) . 'lib/welcome/welcome-screen.php');
}
