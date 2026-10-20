<?php
/**
 * LIGER MEDIA Theme Functions and Definitions
 *
 * @package LigerMedia
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'LIGERMEDIA_VERSION', '5.6.0' );
define( 'LIGERMEDIA_DIR', get_template_directory() );
define( 'LIGERMEDIA_URI', get_template_directory_uri() );

/**
 * Setup Theme Supports
 */
function ligermedia_setup() {
	// Let WordPress manage the document title
	add_theme_support( 'title-tag' );

	// Enable featured images for posts, pages, and custom post types
	add_theme_support( 'post-thumbnails' );

	// HTML5 support for search form, comment form, gallery, etc.
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

	// Responsive embedded content (YouTube, Vimeo, etc.)
	add_theme_support( 'responsive-embeds' );

	// Add block editor styles support
	add_theme_support( 'editor-styles' );
	add_editor_style( 'assets/css/main.css' );
}
add_action( 'after_setup_theme', 'ligermedia_setup' );

/**
 * Modular Includes
 */
require_once LIGERMEDIA_DIR . '/inc/enqueue.php';
require_once LIGERMEDIA_DIR . '/inc/post-types.php';
require_once LIGERMEDIA_DIR . '/inc/taxonomies.php';
require_once LIGERMEDIA_DIR . '/inc/meta-boxes.php';
require_once LIGERMEDIA_DIR . '/inc/homepage-settings.php';
require_once LIGERMEDIA_DIR . '/inc/helpers.php';

/**
 * Register Custom Block Patterns (Only if not already auto-registered by WP Core)
 */
function ligermedia_register_patterns() {
	if ( ! function_exists( 'register_block_pattern' ) || ! class_exists( 'WP_Block_Patterns_Registry' ) ) {
		return;
	}

	$registry = WP_Block_Patterns_Registry::get_instance();
	$patterns = array(
		'ligermedia/client-marquee' => array(
			'title'      => 'Trusted Clients & Enterprise Brands Marquee',
			'file'       => LIGERMEDIA_DIR . '/patterns/client-marquee.php',
			'categories' => array( 'featured', 'text' ),
		),
		'ligermedia/knowledge-hub' => array(
			'title'      => 'Knowledge Hub & Engineering Insights',
			'file'       => LIGERMEDIA_DIR . '/patterns/knowledge-hub.php',
			'categories' => array( 'featured', 'text' ),
		),
		'ligermedia/articles-hub' => array(
			'title'      => 'Articles Hub (Nike-Style Two-Column Archive)',
			'file'       => LIGERMEDIA_DIR . '/patterns/articles-hub.php',
			'categories' => array( 'featured', 'text', 'query' ),
		),
		'ligermedia/projects-hub' => array(
			'title'      => 'Projects Hub (Modern Nike-Style Portfolio Grid)',
			'file'       => LIGERMEDIA_DIR . '/patterns/projects-hub.php',
			'categories' => array( 'featured', 'query', 'gallery' ),
		),
		'ligermedia/project-single' => array(
			'title'      => 'Project Single (Modern Editorial Bento Gallery & Materials Integration)',
			'file'       => LIGERMEDIA_DIR . '/patterns/project-single.php',
			'categories' => array( 'featured', 'text', 'gallery' ),
		),
		'ligermedia/location-map'  => array(
			'title'      => 'Warehouse & GPS Location Hub',
			'file'       => LIGERMEDIA_DIR . '/patterns/location-map.php',
			'categories' => array( 'featured', 'contact' ),
		),
	);

	foreach ( $patterns as $slug => $data ) {
		if ( ! $registry->is_registered( $slug ) && file_exists( $data['file'] ) ) {
			ob_start();
			include $data['file'];
			$content = ob_get_clean();

			register_block_pattern(
				$slug,
				array(
					'title'      => $data['title'],
					'categories' => $data['categories'],
					'content'    => $content,
				)
			);
		}
	}
}
add_action( 'init', 'ligermedia_register_patterns', 20 );

/**
 * Remove default WordPress archive title prefixes like "หมวดหมู่สินค้า: " or "Category: "
 */
add_filter( 'get_the_archive_title_prefix', '__return_empty_string' );
add_filter( 'get_the_archive_title', function( $title ) {
	if ( is_category() || is_tax() || is_tag() ) {
		return single_term_title( '', false );
	}
	return $title;
} );


