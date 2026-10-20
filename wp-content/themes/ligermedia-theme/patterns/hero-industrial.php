<?php
/**
 * Title: Nike-Style Full-Width Cinematic Hero Slider (Large Headline + Dynamic CTA)
 * Slug: ligermedia/hero-industrial
 * Categories: banner, featured
 * Description: Full-bleed visual cinematic slider with Nike-style bold headline, Thai subtext, centered shop button and round dots
 */

$default_slides = array(
	0 => array(
		'title' => 'SKY-HIGH ACP',
		'sub'   => 'นวัตกรรมแผ่นคอมโพสิตกันไฟ สำหรับฟาซาดและงานสถาปัตยกรรมอาคารสูง',
		'link'  => '/ligermedia/product-category/acp/',
		'img'   => get_template_directory_uri() . '/assets/images/hero-facade.webp',
	),
	1 => array(
		'title' => 'SUPREMO CLPL',
		'sub'   => 'ฟิล์มสถาปัตยกรรมมิติใหม่ สัมผัสเสี้ยนไม้ธรรมชาติ 3D กันน้ำ 100% ไร้รอยต่อ',
		'link'  => '/ligermedia/product-category/interior/',
		'img'   => get_template_directory_uri() . '/assets/images/hero-slide-clpl.webp',
	),
	2 => array(
		'title' => 'SUPREME ACRYLIC',
		'sub'   => 'แผ่นอะคริลิกหล่อใสพิเศษและพลาสวูดเกรดความหนาแน่นสูง มาตรฐานงานป้ายระดับลักชัวรี',
		'link'  => '/ligermedia/product-category/pvc-sheet/',
		'img'   => get_template_directory_uri() . '/assets/images/hero-slide-acrylic.webp',
	),
	3 => array(
		'title' => 'LIPONIX LED',
		'sub'   => 'โมดูลไฟเลนส์มุมกว้าง 160° แสงสว่างสม่ำเสมอ ประหยัดเม็ดไฟ 40% กันน้ำ IP68',
		'link'  => '/ligermedia/product-category/led/',
		'img'   => get_template_directory_uri() . '/assets/images/hero-slide-led.webp',
	),
	4 => array(
		'title' => 'MACTAC & SMART JET',
		'sub'   => 'สติกเกอร์เกรดเปลี่ยนสีรถยนต์และไวนิลอิงค์เจ็ทพรีเมียม เม็ดสีสดชัด ทนทานทุกสภาพอากาศ',
		'link'  => '/ligermedia/product-category/sticker/',
		'img'   => get_template_directory_uri() . '/assets/images/hero-slide-vinyl.webp',
	),
);

$slides = array();
for ( $i = 0; $i < 5; $i++ ) {
	$idx = $i + 1;
	$slides[ $i ] = array(
		'title' => function_exists( 'ligermedia_get_homepage_option' ) ? ligermedia_get_homepage_option( "slide_{$idx}_title", $default_slides[ $i ]['title'] ) : $default_slides[ $i ]['title'],
		'sub'   => function_exists( 'ligermedia_get_homepage_option' ) ? ligermedia_get_homepage_option( "slide_{$idx}_sub", $default_slides[ $i ]['sub'] ) : $default_slides[ $i ]['sub'],
		'link'  => function_exists( 'ligermedia_get_homepage_option' ) ? ligermedia_get_homepage_option( "slide_{$idx}_link", $default_slides[ $i ]['link'] ) : $default_slides[ $i ]['link'],
		'img'   => function_exists( 'ligermedia_get_homepage_option' ) ? ( ligermedia_get_homepage_option( "slide_{$idx}_img" ) ?: $default_slides[ $i ]['img'] ) : $default_slides[ $i ]['img'],
	);
}
?>
<!-- wp:group {"align":"full","className":"nike-hero-slider-wrap"} -->
<div class="wp-block-group alignfull nike-hero-slider-wrap">
	<div class="nike-slider" id="nike-hero-slider" aria-roledescription="carousel" aria-label="LIGER MEDIA Flagship Materials Showcase">
		
		<!-- Slides Track (5 Material Groups) -->
		<div class="nike-slider-track">
			<?php foreach ( $slides as $index => $s ) : 
				$active_cls = ( 0 === $index ) ? 'is-active' : '';
			?>
				<div class="nike-slide <?php echo esc_attr( $active_cls ); ?>" data-slide-index="<?php echo esc_attr( $index ); ?>" data-link="<?php echo esc_url( $s['link'] ); ?>">
					<a href="<?php echo esc_url( $s['link'] ); ?>" class="nike-slide-bg" aria-label="<?php echo esc_attr( $s['title'] ); ?>">
						<img src="<?php echo esc_url( $s['img'] ); ?>" alt="<?php echo esc_attr( $s['title'] ); ?>" <?php echo ( 0 === $index ) ? 'fetchpriority="high"' : 'loading="lazy"'; ?> decoding="async" />
						<div class="nike-slide-overlay"></div>
					</a>
					<div class="nike-slide-content">
						<h2 class="nike-hero-title"><?php echo esc_html( $s['title'] ); ?></h2>
						<p class="nike-hero-subtitle"><?php echo esc_html( $s['sub'] ); ?></p>
					</div>
				</div>
			<?php endforeach; ?>
		</div>

		<!-- Centered Floating Controls (Nike "เลือกซื้อ" Pill Button + Round Dots Remote Dock) -->
		<div class="nike-hero-center-controls">
			<a href="<?php echo esc_url( $slides[0]['link'] ); ?>" class="nike-hero-shop-btn" id="nike-hero-cta-btn" aria-label="เลือกซื้อสินค้าในหมวดหมู่นี้">
				<span>เลือกซื้อ</span>
				<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"></path><path d="M12 5l7 7-7 7"></path></svg>
			</a>

			<div class="nike-slider-bottom-controls">
				<button type="button" class="nike-ctrl-arrow" id="nike-prev-btn" aria-label="Previous Slide">
					<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
				</button>

				<div class="nike-slider-pagination" id="nike-dots-wrap">
					<?php foreach ( $slides as $index => $s ) : 
						$dot_active = ( 0 === $index ) ? 'is-active' : '';
					?>
						<button type="button" class="nike-slider-dot nike-dot <?php echo esc_attr( $dot_active ); ?>" data-index="<?php echo esc_attr( $index ); ?>" aria-label="Slide <?php echo esc_attr( $index + 1 ); ?>"></button>
					<?php endforeach; ?>
				</div>

				<button type="button" class="nike-ctrl-arrow" id="nike-next-btn" aria-label="Next Slide">
					<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
				</button>
			</div>
		</div>

	</div>
</div>
<!-- /wp:group -->
