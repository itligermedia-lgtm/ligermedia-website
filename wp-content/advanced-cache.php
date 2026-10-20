<?php
/**
 * LIGER MEDIA - Ultra-Fast Early HTML Page Cache Drop-in
 * Serves cached HTML pages in < 5ms before WordPress connects to MySQL.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// 1. Only cache GET requests on the frontend
if ( ! isset( $_SERVER['REQUEST_METHOD'] ) || 'GET' !== $_SERVER['REQUEST_METHOD'] ) {
	return;
}

$liger_req_uri = isset( $_SERVER['REQUEST_URI'] ) ? $_SERVER['REQUEST_URI'] : '/';

// 2. Skip admin, login, REST API, AJAX, XML-RPC, cron, or query-string filter/preview requests
if (
	false !== strpos( $liger_req_uri, '/wp-admin' ) ||
	false !== strpos( $liger_req_uri, 'wp-login.php' ) ||
	false !== strpos( $liger_req_uri, '/wp-json' ) ||
	false !== strpos( $liger_req_uri, 'admin-ajax.php' ) ||
	false !== strpos( $liger_req_uri, 'xmlrpc.php' ) ||
	false !== strpos( $liger_req_uri, 'wp-cron.php' ) ||
	! empty( $_GET )
) {
	return;
}

// 3. Skip if logged-in WordPress admin cookie is present (so admin bar isn't cached/missed)
if ( ! empty( $_COOKIE ) ) {
	foreach ( $_COOKIE as $cookie_name => $cookie_val ) {
		if ( 0 === strpos( $cookie_name, 'wordpress_logged_in_' ) || 0 === strpos( $cookie_name, 'wp-postpass_' ) ) {
			return;
		}
	}
}

$liger_cache_dir  = ABSPATH . 'wp-content/cache/liger-pages/';
$liger_ver_file   = $liger_cache_dir . 'version.txt';
$liger_theme_ver  = file_exists( $liger_ver_file ) ? trim( (string) @file_get_contents( $liger_ver_file ) ) : '4.1.0';
$liger_host       = isset( $_SERVER['HTTP_HOST'] ) ? strtolower( preg_replace( '/[^a-zA-Z0-9.:_-]/', '', $_SERVER['HTTP_HOST'] ) ) : 'localhost';
$liger_norm_uri   = rtrim( strtok( $liger_req_uri, '?' ), '/' ) . '/';
$liger_cache_key  = md5( $liger_theme_ver . '|' . $liger_host . '|' . $liger_norm_uri );
$liger_cache_file = $liger_cache_dir . $liger_cache_key . '.html';

// 4. Serve from cache if valid (TTL: 12 hours)
if ( file_exists( $liger_cache_file ) && ( time() - filemtime( $liger_cache_file ) ) < 43200 ) {
	header( 'Content-Type: text/html; charset=UTF-8' );
	header( 'X-Liger-Cache: HIT' );
	readfile( $liger_cache_file );
	exit;
}
