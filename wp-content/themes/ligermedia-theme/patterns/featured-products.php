<?php
/**
 * Title: Featured Material Spotlight (Nike-Style Trending Products)
 * Slug: ligermedia/featured-products
 * Categories: featured, products
 * Description: Clean Nike-style 10-card spotlight showcase of top flagship materials with interactive carousel and progress scrollbar
 */

$featured_title    = function_exists( 'ligermedia_get_homepage_option' ) ? ligermedia_get_homepage_option( 'featured_title', 'สินค้าเด่นประจำซีซั่น' ) : 'สินค้าเด่นประจำซีซั่น';
$featured_subtitle = function_exists( 'ligermedia_get_homepage_option' ) ? ligermedia_get_homepage_option( 'featured_subtitle', 'วัสดุวิศวกรรมสถาปัตยกรรมและงานป้ายยอดนิยมมาตรฐานสากล ที่สถาปนิกและโครงการชั้นนำเลือกใช้' ) : 'วัสดุวิศวกรรมสถาปัตยกรรมและงานป้ายยอดนิยมมาตรฐานสากล ที่สถาปนิกและโครงการชั้นนำเลือกใช้';

// 1. First priority: Products marked with _liger_is_featured = '1'
$featured_query = new WP_Query( array(
	'post_type'              => 'product',
	'post_status'            => 'publish',
	'posts_per_page'         => 10,
	'meta_query'             => array(
		array(
			'key'     => '_liger_is_featured',
			'value'   => '1',
			'compare' => '=',
		),
	),
	'orderby'                => array(
		'menu_order' => 'ASC',
		'date'       => 'DESC',
	),
	'no_found_rows'          => true,
	'update_post_term_cache' => true,
	'update_post_meta_cache' => true,
) );

// 2. Fallback: If no products or fewer than 10 products marked, query latest published products
if ( ! $featured_query->have_posts() || $featured_query->post_count < 10 ) {
	$exclude_ids = wp_list_pluck( $featured_query->posts, 'ID' );
	$fill_count  = 10 - count( $exclude_ids );

	$fallback_query = new WP_Query( array(
		'post_type'              => 'product',
		'post_status'            => 'publish',
		'posts_per_page'         => $fill_count,
		'post__not_in'           => $exclude_ids,
		'orderby'                => array(
			'menu_order' => 'ASC',
			'date'       => 'DESC',
		),
		'no_found_rows'          => true,
		'update_post_term_cache' => true,
		'update_post_meta_cache' => true,
	) );

	// Merge posts
	if ( $fallback_query->have_posts() ) {
		$featured_query->posts = array_merge( $featured_query->posts, $fallback_query->posts );
		$featured_query->post_count = count( $featured_query->posts );
	}
}
?>
<!-- wp:group {"align":"wide","className":"liger-featured-section nike-carousel-module","layout":{"type":"constrained"}} -->
<section class="wp-block-group alignwide liger-featured-section nike-carousel-module" id="featured-spotlight" data-carousel-id="featured-spotlight">
	
	<!-- Section Header: Nike Aesthetic with Navigation Controls -->
	<div class="apple-section-header featured-section-header alignwide">
		<div>
			<span class="apple-section-eyebrow">CURATED MATERIAL SPOTLIGHT</span>
			<h2 class="apple-section-title"><?php echo esc_html( $featured_title ); ?><span style="color:var(--mi-orange);">.</span></h2>
			<p class="apple-section-subtitle"><?php echo esc_html( $featured_subtitle ); ?></p>
		</div>
		<div class="nike-carousel-nav-group">
			<a href="/ligermedia/products/" class="apple-see-all-link">
				<span>ดูสินค้าทั้งหมดทุกหมวด (All Products)</span>
				<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5l7 7-7 7"/></svg>
			</a>
			<div class="nike-carousel-arrows">
				<button type="button" class="nike-carousel-arrow-btn nike-carousel-prev" aria-label="ดูรายการก่อนหน้า">
					<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
				</button>
				<button type="button" class="nike-carousel-arrow-btn nike-carousel-next" aria-label="ดูรายการถัดไป">
					<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
				</button>
			</div>
		</div>
	</div>

	<!-- 10-Card Responsive Nike Carousel Track -->
	<div class="nike-carousel-track-wrapper alignwide">
		<div class="featured-spotlight-grid nike-carousel-track">
			<?php
			if ( $featured_query->have_posts() ) :
				while ( $featured_query->have_posts() ) :
					$featured_query->the_post();
					echo ligermedia_render_product_card( get_the_ID(), 'nike-carousel-slide' );
				endwhile;
				wp_reset_postdata();
			endif;
			?>
		</div>
	</div>

	<!-- Nike-Style Bottom Scrollbar Progress Indicator -->
	<div class="nike-carousel-progress-wrap alignwide">
		<div class="nike-carousel-progress-bar">
			<div class="nike-carousel-progress-thumb"></div>
		</div>
	</div>
</section>
<!-- /wp:group -->
