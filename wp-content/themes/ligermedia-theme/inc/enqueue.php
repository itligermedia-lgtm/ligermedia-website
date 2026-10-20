<?php
/**
 * Enqueue scripts and styles
 *
 * @package LigerMedia
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue Frontend Assets
 */
function ligermedia_enqueue_assets() {
	// Self-Hosted Local Fonts (Plus Jakarta Sans & IBM Plex Sans Thai) - Zero FOUT / Zero External Latency
	wp_enqueue_style(
		'ligermedia-fonts',
		LIGERMEDIA_URI . '/assets/fonts/fonts.css',
		array(),
		LIGERMEDIA_VERSION
	);

	// Main Theme Stylesheet
	wp_enqueue_style(
		'ligermedia-main-style',
		LIGERMEDIA_URI . '/assets/css/main.css',
		array( 'ligermedia-fonts' ),
		LIGERMEDIA_VERSION
	);

	// PDF.js Engine (Self-Hosted for 3D Flipbook Viewer)
	wp_enqueue_script(
		'pdfjs',
		LIGERMEDIA_URI . '/assets/js/vendor/pdf.min.js',
		array(),
		'3.11.174',
		array(
			'strategy'  => 'defer',
			'in_footer' => true,
		)
	);

	// Main Theme JavaScript (Deferred, Vanilla ES6)
	wp_enqueue_script(
		'ligermedia-main-script',
		LIGERMEDIA_URI . '/assets/js/main.js',
		array( 'pdfjs' ),
		LIGERMEDIA_VERSION,
		array(
			'strategy'  => 'defer',
			'in_footer' => true,
		)
	);

	// Pass dynamic data to script if needed
	wp_localize_script(
		'ligermedia-main-script',
		'LigerData',
		array(
			'ajaxUrl'   => admin_url( 'admin-ajax.php' ),
			'nonce'     => wp_create_nonce( 'liger_frontend_nonce' ),
			'pdfWorker' => LIGERMEDIA_URI . '/assets/js/vendor/pdf.worker.min.js',
			'themeUri'  => LIGERMEDIA_URI,
		)
	);
}
add_action( 'wp_enqueue_scripts', 'ligermedia_enqueue_assets' );

/**
 * Preload Local WOFF2 Font Files & LCP Hero Image for Zero Font Flash (Zero FOUT)
 */
function ligermedia_preload_lcp_assets() {
	$preload_fonts = array(
		'm8JPje1VVIzcq1HzJq2AEdo2Tj_qvLqMHdYgVcM.woff2',    // IBM Plex Sans Thai 400
		'm8JMje1VVIzcq1HzJq2AEdo2Tj_qvLqE-vU1fuJHa74.woff2', // IBM Plex Sans Thai 500
		'm8JMje1VVIzcq1HzJq2AEdo2Tj_qvLqE1vI1fuJHa74.woff2', // IBM Plex Sans Thai 600
		'm8JMje1VVIzcq1HzJq2AEdo2Tj_qvLqEsvM1fuJHa74.woff2', // IBM Plex Sans Thai 700
		'LDIoaomQNQcsA88c7O9yZ4KMCoOg4Ko20yw.woff2',         // Plus Jakarta Sans (Latin 400-800)
	);
	foreach ( $preload_fonts as $font_file ) {
		echo '<link rel="preload" as="font" type="font/woff2" href="' . esc_url( LIGERMEDIA_URI . '/assets/fonts/' . $font_file ) . '" crossorigin="anonymous">' . "\n";
	}
	if ( is_front_page() ) {
		echo '<link rel="preload" as="image" href="' . esc_url( LIGERMEDIA_URI . '/assets/images/hero-facade.webp' ) . '" fetchpriority="high">' . "\n";
	}
}
add_action( 'wp_head', 'ligermedia_preload_lcp_assets', 1 );

/**
 * Speed Optimization: Remove WordPress Emojis, Embeds, and Unnecessary Head Bloat
 */
function ligermedia_optimize_performance() {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	remove_action( 'wp_head', 'wp_generator' );
	remove_action( 'wp_head', 'wlwmanifest_link' );
	remove_action( 'wp_head', 'rsd_link' );
	remove_action( 'wp_head', 'wp_shortlink_wp_head' );
}
add_action( 'init', 'ligermedia_optimize_performance' );

function ligermedia_dequeue_bloat() {
	if ( ! is_user_logged_in() ) {
		wp_deregister_style( 'dashicons' );
	}
}
add_action( 'wp_enqueue_scripts', 'ligermedia_dequeue_bloat', 100 );

/**
 * Enqueue Block Editor Assets
 */
function ligermedia_block_editor_assets() {
	wp_enqueue_style(
		'ligermedia-editor-fonts',
		LIGERMEDIA_URI . '/assets/fonts/fonts.css',
		array(),
		LIGERMEDIA_VERSION
	);
	wp_enqueue_style(
		'ligermedia-editor-style',
		LIGERMEDIA_URI . '/assets/css/main.css',
		array( 'ligermedia-editor-fonts' ),
		LIGERMEDIA_VERSION
	);
}
add_action( 'enqueue_block_editor_assets', 'ligermedia_block_editor_assets' );

/**
 * Instant Page Navigation (1/2): Speculation Rules API
 * Tells modern browsers (Chrome/Edge) to prerender/prefetch internal links on hover or pointerdown
 * so clicking to /products/ or any internal page switches in 0ms.
 */
function ligermedia_speculation_rules() {
	if ( is_admin() || is_user_logged_in() ) {
		return;
	}
	?>
	<script type="speculationrules">
	{
		"prerender": [
			{
				"source": "document",
				"where": {
					"and": [
						{ "href_matches": "/*" },
						{ "not": { "href_matches": "/ligermedia/wp-admin/*" } },
						{ "not": { "href_matches": "/ligermedia/wp-login.php*" } },
						{ "not": { "href_matches": "/*\\?*" } },
						{ "not": { "selector_matches": "[target=_blank], [data-open-quote-modal], .trigger-quote-modal, [href^='#']" } }
					]
				},
				"eagerness": "moderate"
			}
		],
		"prefetch": [
			{
				"source": "document",
				"where": {
					"and": [
						{ "href_matches": "/*" },
						{ "not": { "href_matches": "/ligermedia/wp-admin/*" } },
						{ "not": { "href_matches": "/ligermedia/wp-login.php*" } },
						{ "not": { "href_matches": "/*\\?*" } }
					]
				},
				"eagerness": "eager"
			}
		]
	}
	</script>
	<?php
}
add_action( 'wp_head', 'ligermedia_speculation_rules', 2 );

/**
 * Instant Page Navigation (2/2): Ultra-Fast HTML Page Cache Writer & Auto-Invalidator
 * Stores rendered HTML in wp-content/cache/liger-pages/ so repeat & prefetched visits load in < 5ms.
 */
function ligermedia_get_cache_dir() {
	return ABSPATH . 'wp-content/cache/liger-pages/';
}

function ligermedia_flush_page_cache() {
	$dir = ligermedia_get_cache_dir();
	if ( ! is_dir( $dir ) ) {
		return;
	}
	$files = glob( $dir . '*.html' );
	if ( is_array( $files ) ) {
		foreach ( $files as $file ) {
			if ( is_file( $file ) ) {
				@unlink( $file );
			}
		}
	}
}

// Auto-flush cache whenever theme version changes or any post/product/category is updated
function ligermedia_check_cache_version() {
	$dir = ligermedia_get_cache_dir();
	if ( ! is_dir( $dir ) ) {
		@mkdir( $dir, 0755, true );
	}
	$ver_file = $dir . 'version.txt';
	$curr_ver = defined( 'LIGERMEDIA_VERSION' ) ? LIGERMEDIA_VERSION : '1.0';
	$saved    = file_exists( $ver_file ) ? trim( (string) @file_get_contents( $ver_file ) ) : '';
	if ( $saved !== $curr_ver ) {
		ligermedia_flush_page_cache();
		@file_put_contents( $ver_file, $curr_ver );
	}
}
add_action( 'init', 'ligermedia_check_cache_version', 1 );
add_action( 'save_post', 'ligermedia_flush_page_cache' );
add_action( 'deleted_post', 'ligermedia_flush_page_cache' );
add_action( 'created_term', 'ligermedia_flush_page_cache' );
add_action( 'edited_term', 'ligermedia_flush_page_cache' );
add_action( 'delete_term', 'ligermedia_flush_page_cache' );
add_action( 'switch_theme', 'ligermedia_flush_page_cache' );

function ligermedia_start_page_cache_buffer() {
	if (
		is_admin() ||
		is_user_logged_in() ||
		is_404() ||
		is_search() ||
		! isset( $_SERVER['REQUEST_METHOD'] ) ||
		'GET' !== $_SERVER['REQUEST_METHOD'] ||
		! empty( $_GET )
	) {
		return;
	}

	ob_start( function( $html ) {
		// Only cache complete, valid HTML documents
		if ( strlen( $html ) < 500 || false === stripos( $html, '</html>' ) ) {
			return $html;
		}
		$dir = ligermedia_get_cache_dir();
		if ( ! is_dir( $dir ) ) {
			@mkdir( $dir, 0755, true );
		}
		$req_uri   = isset( $_SERVER['REQUEST_URI'] ) ? $_SERVER['REQUEST_URI'] : '/';
		$host      = isset( $_SERVER['HTTP_HOST'] ) ? strtolower( preg_replace( '/[^a-zA-Z0-9.:_-]/', '', $_SERVER['HTTP_HOST'] ) ) : 'localhost';
		$norm_uri  = rtrim( strtok( $req_uri, '?' ), '/' ) . '/';
		$curr_ver  = defined( 'LIGERMEDIA_VERSION' ) ? LIGERMEDIA_VERSION : '4.1.0';
		$cache_key = md5( $curr_ver . '|' . $host . '|' . $norm_uri );
		@file_put_contents( $dir . $cache_key . '.html', $html );
		return $html;
	} );
}
add_action( 'template_redirect', 'ligermedia_start_page_cache_buffer', 0 );


