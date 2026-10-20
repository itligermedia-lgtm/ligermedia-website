<?php
/**
 * Register Taxonomies for LIGER MEDIA
 *
 * @package LigerMedia
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function ligermedia_register_taxonomies() {

	/**
	 * Taxonomy: Product Category (หมวดหมู่วัสดุ)
	 */
	$cat_labels = array(
		'name'              => 'หมวดหมู่สินค้า',
		'singular_name'     => 'หมวดหมู่สินค้า',
		'search_items'      => 'ค้นหาหมวดหมู่',
		'all_items'         => 'หมวดหมู่ทั้งหมด',
		'parent_item'       => 'หมวดหมู่หลัก',
		'parent_item_colon' => 'หมวดหมู่หลัก:',
		'edit_item'         => 'แก้ไขหมวดหมู่',
		'update_item'       => 'อัปเดตหมวดหมู่',
		'add_new_item'      => 'เพิ่มหมวดหมู่ใหม่',
		'new_item_name'     => 'ชื่อหมวดหมู่ใหม่',
		'menu_name'         => 'หมวดหมู่สินค้า',
	);

	register_taxonomy(
		'product_cat',
		array( 'product' ),
		array(
			'hierarchical'      => true,
			'labels'            => $cat_labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array( 'slug' => 'product-category' ),
			'show_in_rest'      => true,
		)
	);

	/**
	 * Taxonomy: Application / Usage (การใช้งาน เช่น ภายนอก / ภายในอาคาร)
	 */
	$app_labels = array(
		'name'          => 'ประเภทการใช้งาน',
		'singular_name' => 'การใช้งาน',
		'search_items'  => 'ค้นหาประเภทการใช้งาน',
		'all_items'     => 'ประเภทการใช้งานทั้งหมด',
		'edit_item'     => 'แก้ไขการใช้งาน',
		'update_item'   => 'อัปเดตการใช้งาน',
		'add_new_item'  => 'เพิ่มการใช้งานใหม่',
		'menu_name'     => 'ประเภทการใช้งาน',
	);

	register_taxonomy(
		'product_application',
		array( 'product' ),
		array(
			'hierarchical'      => false,
			'labels'            => $app_labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array( 'slug' => 'product-application' ),
			'show_in_rest'      => true,
		)
	);

	/**
	 * Taxonomy: Project Sector (ประเภทโครงการ)
	 */
	$sector_labels = array(
		'name'          => 'ประเภทโครงการ',
		'singular_name' => 'ประเภทโครงการ',
		'search_items'  => 'ค้นหาประเภทโครงการ',
		'all_items'     => 'ประเภทโครงการทั้งหมด',
		'edit_item'     => 'แก้ไขประเภทโครงการ',
		'menu_name'     => 'ประเภทโครงการ',
	);

	register_taxonomy(
		'project_sector',
		array( 'project' ),
		array(
			'hierarchical'      => true,
			'labels'            => $sector_labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array( 'slug' => 'project-sector' ),
			'show_in_rest'      => true,
		)
	);
}
add_action( 'init', 'ligermedia_register_taxonomies' );
