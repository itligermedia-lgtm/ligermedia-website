<?php
/**
 * Register Custom Post Types for LIGER MEDIA
 *
 * @package LigerMedia
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function ligermedia_register_post_types() {

	/**
	 * Post Type: Products (สินค้าและวัสดุ)
	 */
	$product_labels = array(
		'name'                  => 'สินค้าและวัสดุ',
		'singular_name'         => 'สินค้า',
		'menu_name'             => 'สินค้า (Products)',
		'name_admin_bar'        => 'สินค้า',
		'add_new'               => 'เพิ่มสินค้าใหม่',
		'add_new_item'          => 'เพิ่มสินค้าใหม่',
		'new_item'              => 'สินค้าใหม่',
		'edit_item'             => 'แก้ไขสินค้า',
		'view_item'             => 'ดูสินค้า',
		'all_items'             => 'สินค้าทั้งหมด',
		'search_items'          => 'ค้นหาสินค้า',
		'not_found'             => 'ไม่พบสินค้า',
		'not_found_in_trash'    => 'ไม่พบสินค้าในถังขยะ',
	);

	$product_args = array(
		'labels'             => $product_labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'products', 'with_front' => false ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => 5,
		'menu_icon'          => 'dashicons-grid-view',
		'show_in_rest'       => true,
		'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'page-attributes' ),
	);

	register_post_type( 'product', $product_args );

	/**
	 * Post Type: Projects (โครงการและผลงานติดตั้ง)
	 */
	$project_labels = array(
		'name'                  => 'โครงการและผลงาน',
		'singular_name'         => 'โครงการ',
		'menu_name'             => 'โครงการ (Projects)',
		'name_admin_bar'        => 'โครงการ',
		'add_new'               => 'เพิ่มโครงการใหม่',
		'add_new_item'          => 'เพิ่มโครงการใหม่',
		'new_item'              => 'โครงการใหม่',
		'edit_item'             => 'แก้ไขโครงการ',
		'view_item'             => 'ดูโครงการ',
		'all_items'             => 'โครงการทั้งหมด',
		'search_items'          => 'ค้นหาโครงการ',
		'not_found'             => 'ไม่พบโครงการ',
		'not_found_in_trash'    => 'ไม่พบโครงการในถังขยะ',
	);

	$project_args = array(
		'labels'             => $project_labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'projects', 'with_front' => false ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => 6,
		'menu_icon'          => 'dashicons-building',
		'show_in_rest'       => true,
		'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
	);

	register_post_type( 'project', $project_args );

	/**
	 * Post Type: Catalogs & Resources (ดาวน์โหลดเอกสาร / แคตตาล็อก)
	 */
	$catalog_labels = array(
		'name'                  => 'เอกสารและแคตตาล็อก',
		'singular_name'         => 'แคตตาล็อก',
		'menu_name'             => 'เอกสารดาวน์โหลด',
		'name_admin_bar'        => 'เอกสารดาวน์โหลด',
		'add_new'               => 'เพิ่มเอกสารใหม่',
		'add_new_item'          => 'เพิ่มเอกสารใหม่',
		'new_item'              => 'เอกสารใหม่',
		'edit_item'             => 'แก้ไขเอกสาร',
		'view_item'             => 'ดูเอกสาร',
		'all_items'             => 'เอกสารทั้งหมด',
		'search_items'          => 'ค้นหาเอกสาร',
	);

	$catalog_args = array(
		'labels'             => $catalog_labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'catalogs', 'with_front' => false ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => 7,
		'menu_icon'          => 'dashicons-media-document',
		'show_in_rest'       => true,
		'supports'           => array( 'title', 'thumbnail' ),
	);

	register_post_type( 'catalog', $catalog_args );
}
add_action( 'init', 'ligermedia_register_post_types' );
