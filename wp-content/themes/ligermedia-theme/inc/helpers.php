<?php
/**
 * Theme Helper Functions and Utilities
 *
 * @package LigerMedia
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Render inline SVG Icons for performance and crisp rendering
 *
 * @param string $icon_name Name of the icon
 * @param string $class Additional CSS classes
 * @return string SVG markup
 */
function ligermedia_get_icon( $icon_name, $class = '' ) {
	$icons = array(
		'arrow-up-right' => '<svg class="' . esc_attr( $class ) . '" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17L17 7"/><path d="M7 7h10v10"/></svg>',
		'arrow-right'    => '<svg class="' . esc_attr( $class ) . '" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>',
		'check'          => '<svg class="' . esc_attr( $class ) . '" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>',
		'download'       => '<svg class="' . esc_attr( $class ) . '" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>',
		'pdf'            => '<svg class="' . esc_attr( $class ) . '" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>',
		'shield-check'   => '<svg class="' . esc_attr( $class ) . '" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><polyline points="9 12 11 14 15 10"/></svg>',
		'phone'          => '<svg class="' . esc_attr( $class ) . '" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>',
		'mail'           => '<svg class="' . esc_attr( $class ) . '" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>',
		'map-pin'        => '<svg class="' . esc_attr( $class ) . '" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>',
	);

	return isset( $icons[ $icon_name ] ) ? $icons[ $icon_name ] : '';
}

/**
 * Output breadcrumb navigation
 */
function ligermedia_breadcrumbs() {
	if ( is_front_page() ) {
		return;
	}

	echo '<nav class="liger-breadcrumb" aria-label="Breadcrumb">';
	echo '<a href="' . esc_url( home_url( '/' ) ) . '">หน้าแรก</a>';
	echo '<span class="liger-breadcrumb__sep">/</span>';

	if ( is_singular( 'product' ) ) {
		$post_type_archive = get_post_type_archive_link( 'product' );
		if ( $post_type_archive ) {
			echo '<a href="' . esc_url( $post_type_archive ) . '">สินค้าและวัสดุ</a>';
			echo '<span class="liger-breadcrumb__sep">/</span>';
		}
		$terms = get_the_terms( get_the_ID(), 'product_cat' );
		if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
			$term = array_shift( $terms );
			echo '<a href="' . esc_url( get_term_link( $term ) ) . '">' . esc_html( $term->name ) . '</a>';
			echo '<span class="liger-breadcrumb__sep">/</span>';
		}
		echo '<span class="liger-breadcrumb__current">' . esc_html( get_the_title() ) . '</span>';
	} elseif ( is_post_type_archive( 'product' ) || is_tax( 'product_cat' ) ) {
		if ( is_tax( 'product_cat' ) ) {
			$post_type_archive = get_post_type_archive_link( 'product' );
			echo '<a href="' . esc_url( $post_type_archive ) . '">สินค้าและวัสดุ</a>';
			echo '<span class="liger-breadcrumb__sep">/</span>';
			echo '<span class="liger-breadcrumb__current">' . esc_html( single_term_title( '', false ) ) . '</span>';
		} else {
			echo '<span class="liger-breadcrumb__current">สินค้าและวัสดุทั้งหมด</span>';
		}
	} elseif ( is_singular( 'project' ) ) {
		$post_type_archive = get_post_type_archive_link( 'project' );
		if ( $post_type_archive ) {
			echo '<a href="' . esc_url( $post_type_archive ) . '">โครงการและผลงาน</a>';
			echo '<span class="liger-breadcrumb__sep">/</span>';
		}
		echo '<span class="liger-breadcrumb__current">' . esc_html( get_the_title() ) . '</span>';
	} elseif ( is_post_type_archive( 'project' ) ) {
		echo '<span class="liger-breadcrumb__current">โครงการและผลงานทั้งหมด</span>';
	} elseif ( is_page() ) {
		echo '<span class="liger-breadcrumb__current">' . esc_html( get_the_title() ) . '</span>';
	}

	echo '</nav>';
}

/**
 * Shortcode to render dynamic product specifications table
 */
function ligermedia_product_specs_shortcode() {
	if ( ! is_singular( 'product' ) ) {
		return '';
	}

	$post_id         = get_the_ID();
	$sku             = get_post_meta( $post_id, '_liger_sku', true );
	$badge           = get_post_meta( $post_id, '_liger_highlight_badge', true );
	$thickness       = get_post_meta( $post_id, '_liger_thickness', true );
	$dimensions      = get_post_meta( $post_id, '_liger_dimensions', true );
	$coating         = get_post_meta( $post_id, '_liger_coating', true );
	$fire_rating     = get_post_meta( $post_id, '_liger_fire_rating', true );
	$warranty        = get_post_meta( $post_id, '_liger_warranty', true );
	$spec_pdf_url    = get_post_meta( $post_id, '_liger_spec_pdf_url', true );

	ob_start();
	?>
	<div class="liger-product-specs-section" style="margin-top:2.5rem;padding-top:2rem;border-top:1px solid #E1E4E8;">
		<div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;margin-bottom:1.5rem;">
			<div>
				<h3 style="font-size:1.4rem;font-weight:700;color:#0D1117;margin:0 0 4px 0;">ข้อมูลจำเพาะทางวิศวกรรม (Technical Specifications)</h3>
				<span style="font-size:0.875rem;color:#484F58;">ผ่านการทดสอบและได้มาตรฐานอุตสาหกรรม</span>
			</div>
			<?php if ( ! empty( $spec_pdf_url ) ) : ?>
				<a href="<?php echo esc_url( $spec_pdf_url ); ?>" target="_blank" rel="noopener noreferrer" class="liger-btn liger-btn--primary liger-btn--sm">
					<?php echo ligermedia_get_icon( 'pdf' ); ?>
					<span>ดาวน์โหลด TDS (PDF)</span>
				</a>
			<?php endif; ?>
		</div>

		<div class="liger-spec-table-wrap">
			<table class="liger-spec-table">
				<tbody>
					<?php if ( ! empty( $sku ) ) : ?>
						<tr>
							<th>รหัสสินค้า / รุ่น</th>
							<td><strong><?php echo esc_html( $sku ); ?></strong></td>
						</tr>
					<?php endif; ?>

					<?php if ( ! empty( $thickness ) ) : ?>
						<tr>
							<th>ความหนาวัสดุ (Thickness)</th>
							<td><?php echo esc_html( $thickness ); ?></td>
						</tr>
					<?php endif; ?>

					<?php if ( ! empty( $dimensions ) ) : ?>
						<tr>
							<th>ขนาดแผ่นมาตรฐาน</th>
							<td><?php echo esc_html( $dimensions ); ?></td>
						</tr>
					<?php endif; ?>

					<?php if ( ! empty( $coating ) ) : ?>
						<tr>
							<th>การเคลือบผิว / ผิวสัมผัส</th>
							<td><?php echo esc_html( $coating ); ?></td>
						</tr>
					<?php endif; ?>

					<?php if ( ! empty( $fire_rating ) ) : ?>
						<tr>
							<th>มาตรฐานการกันไฟ</th>
							<td><strong><?php echo esc_html( $fire_rating ); ?></strong></td>
						</tr>
					<?php endif; ?>

					<?php if ( ! empty( $warranty ) ) : ?>
						<tr>
							<th>การรับประกันสภาพอากาศ</th>
							<td><?php echo esc_html( $warranty ); ?></td>
						</tr>
					<?php endif; ?>
				</tbody>
			</table>
		</div>

		<!-- Action bar for quotation -->
		<div class="liger-spec-quote-bar">
			<div>
				<strong style="color:#0D1117;font-size:1.05rem;display:block;margin-bottom:2px;">ต้องการใบเสนอราคาสำหรับวัสดุนี้?</strong>
				<span style="color:#57606A;font-size:0.9rem;">ระบุจำนวนตารางเมตรหรือแบบก่อสร้าง ทีมฝ่ายขายจะติดต่อกลับภายใน 24 ชม.</span>
			</div>
			<a href="/ligermedia/contact/?product=<?php echo urlencode( get_the_title() ); ?>" class="liger-btn liger-btn--primary" data-open-quote-modal="true" data-product-name="<?php echo esc_attr( get_the_title() ); ?>">
				<span>ขอใบเสนอราคาสำหรับสินค้านี้</span>
				<?php echo ligermedia_get_icon( 'arrow-right' ); ?>
			</a>
		</div>
	</div>
	<?php
	return ob_get_clean();
}
add_shortcode( 'liger_product_specs', 'ligermedia_product_specs_shortcode' );

/**
 * Shortcode to render dynamic project installation details
 */
function ligermedia_project_specs_shortcode() {
	if ( ! is_singular( 'project' ) ) {
		return '';
	}

	$post_id   = get_the_ID();
	$location  = get_post_meta( $post_id, '_liger_project_location', true );
	$materials = get_post_meta( $post_id, '_liger_project_materials', true );
	$year      = get_post_meta( $post_id, '_liger_project_year', true );
	$area_sqm  = get_post_meta( $post_id, '_liger_project_area_sqm', true );

	ob_start();
	?>
	<div class="liger-project-specs-box" style="background:#161B22;border:1px solid #30363D;border-radius:6px;padding:1.5rem;margin:2rem 0;color:#FFFFFF;">
		<h4 style="color:#FF5500;font-size:1.1rem;font-weight:700;margin:0 0 1rem 0;">ข้อมูลโครงการและการติดตั้ง (Installation Profile)</h4>
		<div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(200px, 1fr));gap:16px;">
			<?php if ( ! empty( $location ) ) : ?>
				<div>
					<span style="font-size:0.75rem;color:#8B949E;display:block;">สถานที่ตั้งโครงการ</span>
					<strong style="font-size:0.95rem;color:#E6EDF3;"><?php echo esc_html( $location ); ?></strong>
				</div>
			<?php endif; ?>

			<?php if ( ! empty( $year ) ) : ?>
				<div>
					<span style="font-size:0.75rem;color:#8B949E;display:block;">ปีที่ติดตั้งแล้วเสร็จ</span>
					<strong style="font-size:0.95rem;color:#E6EDF3;"><?php echo esc_html( $year ); ?></strong>
				</div>
			<?php endif; ?>

			<?php if ( ! empty( $area_sqm ) ) : ?>
				<div>
					<span style="font-size:0.75rem;color:#8B949E;display:block;">พื้นที่ติดตั้งทั้งหมด</span>
					<strong style="font-size:0.95rem;color:#E6EDF3;"><?php echo esc_html( $area_sqm ); ?></strong>
				</div>
			<?php endif; ?>

			<?php if ( ! empty( $materials ) ) : ?>
				<div>
					<span style="font-size:0.75rem;color:#8B949E;display:block;">วัสดุ LIGER MEDIA ที่ใช้</span>
					<strong style="font-size:0.95rem;color:#FF5500;"><?php echo esc_html( $materials ); ?></strong>
				</div>
			<?php endif; ?>
		</div>
	</div>
	<?php
	return ob_get_clean();
}
add_shortcode( 'liger_project_specs', 'ligermedia_project_specs_shortcode' );

/**
 * ==========================================================================
 * Automated WebP Image Conversion on Upload
 * ==========================================================================
 */

/**
 * Automatically convert uploaded JPG/PNG images to WebP format
 */
add_filter( 'wp_handle_upload', 'ligermedia_convert_upload_to_webp' );
function ligermedia_convert_upload_to_webp( $upload ) {
	if ( ! empty( $upload['file'] ) && ! empty( $upload['type'] ) ) {
		$mime_type = $upload['type'];
		if ( in_array( $mime_type, array( 'image/jpeg', 'image/png' ), true ) ) {
			$file_path = $upload['file'];

			if ( function_exists( 'imagewebp' ) ) {
				$editor = wp_get_image_editor( $file_path );
				if ( ! is_wp_error( $editor ) ) {
					$editor->set_quality( 88 );
					$webp_path = preg_replace( '/\.(jpe?g|png)$/i', '.webp', $file_path );
					$saved     = $editor->save( $webp_path, 'image/webp' );

					if ( ! is_wp_error( $saved ) && file_exists( $saved['path'] ) ) {
						// Delete old non-webp original file
						if ( $saved['path'] !== $file_path && file_exists( $file_path ) ) {
							@unlink( $file_path );
						}

						$upload['file'] = $saved['path'];
						$upload['url']  = preg_replace( '/\.(jpe?g|png)$/i', '.webp', $upload['url'] );
						$upload['type'] = 'image/webp';
					}
				}
			}
		}
	}
	return $upload;
}

/**
 * Force WordPress to generate WebP sub-sizes/thumbnails for all media
 */
add_filter( 'image_editor_output_format', 'ligermedia_image_editor_output_format' );
function ligermedia_image_editor_output_format( $formats ) {
	$formats['image/jpeg'] = 'image/webp';
	$formats['image/png']  = 'image/webp';
	return $formats;
}

/**
 * Set optimal WebP image compression quality
 */
add_filter( 'wp_editor_set_quality', 'ligermedia_webp_quality', 10, 2 );
function ligermedia_webp_quality( $quality, $mime_type ) {
	if ( 'image/webp' === $mime_type ) {
		return 88;
	}
	return $quality;
}

/**
 * Ensure WebP mime type is permitted in WordPress uploads
 */
add_filter( 'upload_mimes', 'ligermedia_mime_types' );
function ligermedia_mime_types( $mimes ) {
	$mimes['webp'] = 'image/webp';
	return $mimes;
}

/**
 * Shortcode to render Nike-style multi-angle product gallery
 */
add_shortcode( 'liger_nike_gallery', 'ligermedia_render_nike_gallery_shortcode' );
function ligermedia_render_nike_gallery_shortcode() {
	global $post;
	if ( ! $post ) {
		return '';
	}

	$post_id = $post->ID;
	$thumb_id = get_post_thumbnail_id( $post_id );
	$gallery_meta = get_post_meta( $post_id, '_product_image_gallery', true );
	$gallery_ids = ! empty( $gallery_meta ) ? explode( ',', $gallery_meta ) : array();

	$all_ids = array();
	if ( $thumb_id ) {
		$all_ids[] = (int) $thumb_id;
	}
	foreach ( $gallery_ids as $gid ) {
		$gid = (int) trim( $gid );
		if ( $gid && ! in_array( $gid, $all_ids, true ) ) {
			$all_ids[] = $gid;
		}
	}

	if ( empty( $all_ids ) ) {
		return '<div class="nike-gallery-empty">ไม่มีภาพสินค้า</div>';
	}

	// Angle labels
	$angle_labels = array(
		'ด้านหน้า (Front View)',
		'มิติเอียง 3D (Perspective)',
		'ตัดขวางแกนกลาง (Cross-Section)',
		'ด้านหลังแผ่น (Back View)',
		'พับฉาก 90° (V-Groove Fold)',
		'ซูมผิวสัมผัส (Texture Macro 10x)'
	);

	ob_start();
	?>
	<div class="nike-gallery-wrapper" id="nike-material-gallery">
		
		<!-- LEFT: Vertical Thumbnails Strip -->
		<div class="nike-thumbnails-col">
			<div class="nike-thumbnails-scroller">
				<?php foreach ( $all_ids as $idx => $att_id ) : 
					$thumb_url = wp_get_attachment_image_url( $att_id, 'medium' ) ?: wp_get_attachment_url( $att_id );
					$full_url  = wp_get_attachment_image_url( $att_id, 'full' ) ?: wp_get_attachment_url( $att_id );
					$label     = isset( $angle_labels[ $idx ] ) ? $angle_labels[ $idx ] : 'มุมมองที่ ' . ( $idx + 1 );
					$is_active = ( 0 === $idx ) ? 'is-active' : '';
				?>
					<button type="button" 
							class="nike-thumb-btn <?php echo esc_attr( $is_active ); ?>" 
							data-full-src="<?php echo esc_url( $full_url ); ?>"
							data-angle-title="<?php echo esc_attr( $label ); ?>"
							aria-label="<?php echo esc_attr( $label ); ?>">
						<img src="<?php echo esc_url( $thumb_url ); ?>" alt="<?php echo esc_attr( $label ); ?>" loading="lazy" />
						<span class="nike-thumb-tooltip"><?php echo esc_html( $label ); ?></span>
					</button>
				<?php endforeach; ?>
			</div>
		</div>

		<!-- CENTER: Main Showcase Viewer Stage -->
		<div class="nike-main-stage">
			<div class="nike-main-stage-inner" id="nike-stage-inner">

				<!-- Main Display Image with Smooth Transition & Hover Zoom -->
				<?php 
				$main_src = wp_get_attachment_image_url( $all_ids[0], 'full' ) ?: wp_get_attachment_url( $all_ids[0] );
				?>
				<div class="nike-image-container" id="nike-zoom-target">
					<img src="<?php echo esc_url( $main_src ); ?>" 
						 id="nike-main-img" 
						 alt="<?php echo esc_attr( get_the_title( $post_id ) ); ?>" 
						 class="nike-main-img"
						 loading="eager"
						 fetchpriority="high"
						 decoding="sync" />
				</div>

				<!-- Navigation Arrows -->
				<button type="button" class="nike-arrow-btn nike-arrow-prev" id="nike-arrow-prev" aria-label="Previous view">
					<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"></polyline></svg>
				</button>
				<button type="button" class="nike-arrow-btn nike-arrow-next" id="nike-arrow-next" aria-label="Next view">
					<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"></polyline></svg>
				</button>

			</div>

			<!-- Underneath the Card: Social Share Bar -->
			<?php 
			$share_url   = get_permalink( $post_id );
			$share_title = get_the_title( $post_id );
			?>
			<div class="nike-share-bar">
				<span class="nike-share-label">แชร์</span>
				
				<!-- Facebook (Official Brand Vector) -->
				<a href="<?php echo esc_url( 'https://www.facebook.com/sharer/sharer.php?u=' . rawurlencode( $share_url ) ); ?>" 
				   target="_blank" 
				   rel="noopener noreferrer" 
				   class="nike-share-btn nike-share-fb" 
				   aria-label="แชร์ไปยัง Facebook" 
				   title="แชร์ไปยัง Facebook">
					<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/icons/facebook.svg' ); ?>" alt="Facebook" width="36" height="36" />
				</a>

				<!-- LINE (Official Brand Vector) -->
				<a href="<?php echo esc_url( 'https://social-plugins.line.me/lineit/share?url=' . rawurlencode( $share_url ) ); ?>" 
				   target="_blank" 
				   rel="noopener noreferrer" 
				   class="nike-share-btn nike-share-line" 
				   aria-label="แชร์ไปยัง LINE" 
				   title="แชร์ไปยัง LINE">
					<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/icons/line.svg' ); ?>" alt="LINE" width="36" height="36" />
				</a>

				<!-- Copy Link Button -->
				<button type="button" 
				        class="nike-share-btn nike-share-copy" 
				        id="nike-copy-link-btn" 
				        data-share-url="<?php echo esc_url( $share_url ); ?>" 
				        aria-label="คัดลอกลิงก์" 
				        title="คัดลอกลิงก์">
					<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path></svg>
					<span class="nike-copy-toast" id="nike-copy-toast">คัดลอกลิงก์แล้ว!</span>
				</button>
			</div>
		</div>

	</div>
	<?php
	$html = ob_get_clean();
	// Strip superfluous newlines so wpautop does not inject unwanted <br /> tags
	$html = preg_replace( '/>\s+</', '><', trim( $html ) );
	return $html;
}

/**
 * Render Related Products on Single Product Page (Nike-Style Clean Grid)
 */
function ligermedia_related_products_shortcode() {
	$current_id = get_the_ID();
	if ( ! $current_id ) {
		return '';
	}

	$terms     = get_the_terms( $current_id, 'product_cat' );
	$term_ids  = array();
	$cat_name  = 'หมวดหมู่วัสดุนี้';
	$cat_link  = '/ligermedia/products/';

	if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
		$first_term = reset( $terms );
		$cat_name   = $first_term->name;
		$term_link  = get_term_link( $first_term );
		if ( ! is_wp_error( $term_link ) ) {
			$cat_link = $term_link;
		}
		$term_ids = wp_list_pluck( $terms, 'term_id' );
	}

	$args = array(
		'post_type'              => 'product',
		'posts_per_page'         => 10,
		'post__not_in'           => array( $current_id ),
		'orderby'                => 'date',
		'order'                  => 'DESC',
		'no_found_rows'          => true,
		'update_post_term_cache' => true,
		'update_post_meta_cache' => false,
	);

	if ( ! empty( $term_ids ) ) {
		$args['tax_query'] = array(
			array(
				'taxonomy' => 'product_cat',
				'field'    => 'term_id',
				'terms'    => $term_ids,
			),
		);
	}

	$query = new WP_Query( $args );

	// If fewer than 10 products in same category, fill with other latest products
	if ( $query->post_count < 10 ) {
		$exclude_ids = array_merge( array( $current_id ), wp_list_pluck( $query->posts, 'ID' ) );
		$fallback_args = array(
			'post_type'              => 'product',
			'posts_per_page'         => 10 - $query->post_count,
			'post__not_in'           => $exclude_ids,
			'orderby'                => 'date',
			'order'                  => 'DESC',
			'no_found_rows'          => true,
			'update_post_meta_cache' => false,
		);
		$fallback_query = new WP_Query( $fallback_args );
		if ( $fallback_query->have_posts() ) {
			$query->posts = array_merge( $query->posts, $fallback_query->posts );
		}
	}

	if ( empty( $query->posts ) ) {
		return '';
	}

	// Fetch up to 10 related products for smooth carousel scrolling
	$query->posts      = array_slice( $query->posts, 0, 10 );
	$query->post_count = count( $query->posts );

	ob_start();
	?>
	<section class="nike-related-section nike-carousel-module" data-carousel-id="related-prods">
		<div class="nike-related-header">
			<div>
				<h2 class="nike-related-title">สินค้าที่คุณอาจสนใจ<span style="color:#FF5500;">.</span></h2>
				<p class="nike-related-subtitle">วัสดุวิศวกรรมและอุปกรณ์ในกลุ่มใกล้เคียง เพื่อการระบุสเปกที่ครบวงจร</p>
			</div>
			<div class="nike-carousel-nav-group">
				<a href="<?php echo esc_url( $cat_link ); ?>" class="nike-related-see-all">
					<span>ดูสินค้าทั้งหมดในหมวด <?php echo esc_html( $cat_name ); ?></span>
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

		<div class="nike-carousel-track-wrapper">
			<div class="nike-related-grid nike-carousel-track">
				<?php
				while ( $query->have_posts() ) :
					$query->the_post();
					echo ligermedia_render_product_card( get_the_ID(), 'nike-carousel-slide' );
				endwhile;
				wp_reset_postdata();
				?>
			</div>
		</div>

		<!-- Nike-Style Bottom Scrollbar Progress Indicator -->
		<div class="nike-carousel-progress-wrap">
			<div class="nike-carousel-progress-bar">
				<div class="nike-carousel-progress-thumb"></div>
			</div>
		</div>
	</section>
	<?php
	$html = ob_get_clean();
	return preg_replace( '/\s+/', ' ', preg_replace( '/>\s+</', '><', trim( $html ) ) );
}
add_shortcode( 'liger_related_products', 'ligermedia_related_products_shortcode' );

/**
 * Shortcode to render relevant latest articles on All Products and Category pages with Nike Carousel
 * Usage: [liger_archive_related_articles]
 */
function ligermedia_archive_related_articles_shortcode() {
	$current_cat_slug = '';
	$current_cat_name = '';

	if ( is_tax( 'product_cat' ) ) {
		$term = get_queried_object();
		if ( $term && ! is_wp_error( $term ) ) {
			$current_cat_slug = $term->slug;
			$current_cat_name = $term->name;
		}
	}

	// 1. Build Query for Posts (Fetch up to 10 for rich carousel browsing)
	$args = array(
		'post_type'              => 'post',
		'posts_per_page'         => 10,
		'orderby'                => 'date',
		'order'                  => 'DESC',
		'no_found_rows'          => true,
		'update_post_term_cache' => true,
		'update_post_meta_cache' => false,
	);

	$matched_posts = array();

	if ( ! empty( $current_cat_slug ) ) {
		$cat_term = get_term_by( 'slug', $current_cat_slug, 'category' );
		if ( $cat_term ) {
			$cat_args = $args;
			$cat_args['cat'] = $cat_term->term_id;
			$cat_query = new WP_Query( $cat_args );
			if ( $cat_query->have_posts() ) {
				$matched_posts = $cat_query->posts;
			}
		}

		if ( count( $matched_posts ) < 10 ) {
			$search_keywords = array(
				'acp'         => 'คอมโพสิต',
				'pvc-sheet'   => 'พลาสวูด',
				'interior'    => 'ฟิล์ม',
				'led'         => 'LED',
				'sticker'     => 'สติกเกอร์',
				'vinyl'       => 'ไวนิล',
				'inkjet'      => 'พิมพ์',
				'accessories' => 'ติดตั้ง',
			);
			$kw = isset( $search_keywords[ $current_cat_slug ] ) ? $search_keywords[ $current_cat_slug ] : '';
			if ( $kw ) {
				$kw_args = $args;
				$kw_args['s'] = $kw;
				$kw_args['post__not_in'] = wp_list_pluck( $matched_posts, 'ID' );
				$kw_args['posts_per_page'] = 10 - count( $matched_posts );
				$kw_query = new WP_Query( $kw_args );
				if ( $kw_query->have_posts() ) {
					$matched_posts = array_merge( $matched_posts, $kw_query->posts );
				}
			}
		}
	}

	if ( count( $matched_posts ) < 10 ) {
		$fallback_args = $args;
		if ( ! empty( $matched_posts ) ) {
			$fallback_args['post__not_in'] = wp_list_pluck( $matched_posts, 'ID' );
		}
		$fallback_args['posts_per_page'] = 10 - count( $matched_posts );
		$fallback_query = new WP_Query( $fallback_args );
		if ( $fallback_query->have_posts() ) {
			$matched_posts = array_merge( $matched_posts, $fallback_query->posts );
		}
	}

	if ( empty( $matched_posts ) ) {
		return '';
	}

	// Cap at maximum 10 articles
	$matched_posts = array_slice( $matched_posts, 0, 10 );

	$section_title = ! empty( $current_cat_name ) ? 'บทความและคู่มือเทคนิค: ' . esc_html( $current_cat_name ) : 'บทความและสาระวิศวกรรมวัสดุล่าสุด';
	$section_sub   = ! empty( $current_cat_name ) ? 'คำแนะนำการระบุสเปก มาตรฐานการติดตั้ง และข้อกำหนดทางวิศวกรรมเฉพาะกลุ่ม' : 'อัปเดตสาระน่ารู้ คู่มือมาตรฐานสากล และแนวทางตรวจรับวัสดุสำหรับงานโครงการ';

	$cat_img_map = array(
		'acp'         => 'hero-facade.webp',
		'pvc-sheet'   => 'cat-signage-acrylic.webp',
		'interior'    => 'card-clpl-wood.webp',
		'led'         => 'project-signage.webp',
		'sticker'     => 'hero-slide-vinyl.webp',
		'vinyl'       => 'hero-slide-vinyl.webp',
		'inkjet'      => 'cat-print-vinyl.webp',
		'accessories' => 'card-plastwood-sheet.webp',
	);
	$fallback_img = isset( $cat_img_map[ $current_cat_slug ] ) ? $cat_img_map[ $current_cat_slug ] : 'hero-facade.webp';

	ob_start();
	?>
	<section class="liger-archive-articles-section nike-carousel-module" data-carousel-id="archive-articles">
		<div class="alignwide">
			<div class="liger-archive-articles-header">
				<div>
					<span class="liger-archive-articles-eyebrow">ENGINEERING INSIGHTS & SPEC GUIDES</span>
					<h2 class="liger-archive-articles-title"><?php echo esc_html( $section_title ); ?><span style="color:#FF5500;">.</span></h2>
					<p class="liger-archive-articles-subtitle"><?php echo esc_html( $section_sub ); ?></p>
				</div>
				<div class="nike-carousel-nav-group">
					<a href="/ligermedia/articles/" class="liger-archive-articles-all">
						<span>ดูบทความทั้งหมด</span>
						<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5l7 7-7 7"/></svg>
					</a>
					<div class="nike-carousel-arrows">
						<button type="button" class="nike-carousel-arrow-btn nike-carousel-prev" aria-label="ดูบทความก่อนหน้า">
							<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
						</button>
						<button type="button" class="nike-carousel-arrow-btn nike-carousel-next" aria-label="ดูบทความถัดไป">
							<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
						</button>
					</div>
				</div>
			</div>

			<div class="nike-carousel-track-wrapper">
				<div class="liger-archive-articles-grid nike-carousel-track">
					<?php
					$card_idx = 1;
					foreach ( $matched_posts as $post_obj ) :
						echo ligermedia_render_article_card( $post_obj->ID, $card_idx++, 'nike-carousel-slide' );
					endforeach;
					?>
				</div>
			</div>

			<!-- Nike-Style Bottom Scrollbar Progress Indicator -->
			<div class="nike-carousel-progress-wrap">
				<div class="nike-carousel-progress-bar">
					<div class="nike-carousel-progress-thumb"></div>
				</div>
			</div>
		</div>
	</section>
	<?php
	$html = ob_get_clean();
	return preg_replace( '/\s+/', ' ', preg_replace( '/>\s+</', '><', trim( $html ) ) );
}
add_shortcode( 'liger_archive_related_articles', 'ligermedia_archive_related_articles_shortcode' );

/**
 * Automatically enrich .nike-prod-card in Gutenberg query loop with Category Tag
 * and [ดูสเปก & สั่งตัวอย่าง] footer button
 */
function ligermedia_enrich_product_card( $block_content, $block ) {
	if ( ! empty( $block['attrs']['className'] ) && strpos( $block['attrs']['className'], 'nike-prod-card' ) !== false ) {
		$post_id = get_the_ID();
		if ( ! $post_id ) {
			return $block_content;
		}

		$prod_url = get_permalink( $post_id );
		$terms    = get_the_terms( $post_id, 'product_cat' );
		$cat_name = ( ! empty( $terms ) && ! is_wp_error( $terms ) ) ? $terms[0]->name : '';

		// 1. Inject Category Tag above title if not already present
		if ( $cat_name && strpos( $block_content, 'featured-category-tag' ) === false ) {
			$cat_html      = '<span class="featured-category-tag nike-prod-cat-tag">' . esc_html( $cat_name ) . '</span>';
			$block_content = preg_replace( '/(<div class="[^"]*nike-prod-body[^"]*">)/', '$1' . $cat_html, $block_content, 1 );
		}

		// 2. Inject [ดูสเปก & สั่งตัวอย่าง] footer button
		if ( strpos( $block_content, 'featured-card-footer' ) === false ) {
			$footer_html = '
			<div class="featured-card-footer nike-prod-footer" style="margin-top:auto;">
				<a href="' . esc_url( $prod_url ) . '" class="featured-cta-label" style="text-decoration:none;">ดูสเปก &amp; สั่งตัวอย่าง</a>
				<a href="' . esc_url( $prod_url ) . '" class="featured-cta-icon" aria-label="ดูสเปก ' . esc_attr( get_the_title( $post_id ) ) . '">
					<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14"/><path d="M12 5l7 7-7 7"/></svg>
				</a>
			</div>';

			$block_content = preg_replace( '/(<\/div>\s*<\/div>\s*)$/', $footer_html . '</div></div>', $block_content, 1 );
		}
	}

	return $block_content;
}
add_filter( 'render_block', 'ligermedia_enrich_product_card', 10, 2 );

/**
 * ==========================================================================
 * Project Portfolio & Modern Architectural Gallery Helpers (v4.5.0)
 * ==========================================================================
 */

/**
 * Shortcode to render Projects Hub dynamically
 */
function ligermedia_projects_hub_shortcode() {
	ob_start();
	include get_template_directory() . '/patterns/projects-hub.php';
	$html = ob_get_clean();
	$html = preg_replace( '/<!--(.|\s)*?-->/', '', $html );
	$html = preg_replace( '/>\s+</', '><', $html );
	return str_replace( array( '<p></p>', '<p> </p>', '<br />', '<br>' ), '', $html );
}
add_shortcode( 'liger_projects_hub', 'ligermedia_projects_hub_shortcode' );

/**
 * Shortcode to render Single Project dynamic layout
 */
function ligermedia_project_single_shortcode() {
	ob_start();
	include get_template_directory() . '/patterns/project-single.php';
	$html = ob_get_clean();
	$html = preg_replace( '/<!--(.|\s)*?-->/', '', $html );
	$html = preg_replace( '/>\s+</', '><', $html );
	return str_replace( array( '<p></p>', '<p> </p>', '<br />', '<br>' ), '', $html );
}
add_shortcode( 'liger_project_single', 'ligermedia_project_single_shortcode' );

/**
 * Strip unwanted wpautop breaks from Project templates
 */
add_filter( 'render_block', function( $block_content, $block ) {
	if ( isset( $block['blockName'] ) && 'core/shortcode' === $block['blockName'] ) {
		if ( false !== strpos( $block_content, 'nike-project-' ) || false !== strpos( $block_content, 'nike-material-' ) || false !== strpos( $block_content, 'nike-products-cat-nav' ) ) {
			$block_content = str_replace( array( '<p></p>', '<p> </p>', '<p><br />', '<p><br>', '</p></p>' ), '', $block_content );
			$block_content = preg_replace( '/<\/p>\s*<div/i', '<div', $block_content );
			$block_content = preg_replace( '/<\/div>\s*<\/p>/i', '</div>', $block_content );
			$block_content = preg_replace( '/<\/a>\s*<\/p>/i', '</a>', $block_content );
			$block_content = preg_replace( '/<p>\s*<a/i', '<a', $block_content );
		}
	}
	return $block_content;
}, 999, 2 );

add_filter( 'the_content', function( $content ) {
	if ( is_singular( 'project' ) || is_post_type_archive( 'project' ) ) {
		$content = preg_replace( '/<br\s*\/?>\s*(<\/?(div|span|h[1-6]|section|article|button|figure|a)\b)/i', '$1', $content );
		$content = preg_replace( '/(<\/?(div|span|h[1-6]|section|article|button|figure|a)\b[^>]*>)\s*<br\s*\/?>/i', '$1', $content );
	}
	return $content;
}, 99 );

/**
 * Categorize a project post into one of 4 core architectural material groups
 */
function ligermedia_get_project_category( $post ) {
	if ( is_numeric( $post ) ) {
		$post = get_post( $post );
	}
	if ( ! $post ) {
		return array(
			'slug'          => 'acp',
			'name'          => 'แผ่นอลูมิเนียมคอมโพสิต (ACP)',
			'short_name'    => 'แผ่นคอมโพสิต ACP',
			'badge_bg'      => '#FF5500',
			'default_cover' => 'cat-architectural-acp.webp',
		);
	}

	$slug  = $post->post_name;
	$title = mb_strtolower( $post->post_title );

	// 1. Aluminium Composite Panels (ACP)
	if ( in_array( $slug, array( 'acp', 'sky-high', 'pe-fr', '2025', 'laminate-tigon', 'building-decoration' ), true ) ||
		false !== strpos( $title, 'acp' ) || false !== strpos( $title, 'คอมโพสิต' ) || false !== strpos( $title, 'sky-high' ) || false !== strpos( $title, 'ลามิเนต' ) || false !== strpos( $title, 'ฟาซาด' ) ) {
		return array(
			'slug'          => 'acp',
			'name'          => 'แผ่นอลูมิเนียมคอมโพสิต (ACP)',
			'short_name'    => 'แผ่นคอมโพสิต ACP',
			'badge_bg'      => '#FF5500',
			'default_cover' => 'project-facade.webp',
		);
	}

	// 2. Plastwood & Acrylic
	if ( in_array( $slug, array( 'plastwood', 'greenwood', 'supreme-acrylic-abs', 'acrylic-sheet', 'polycarbonate', 'supremo-clpl' ), true ) ||
		false !== strpos( $title, 'plastwood' ) || false !== strpos( $title, 'พลาสวูด' ) || false !== strpos( $title, 'acrylic' ) || false !== strpos( $title, 'อะคริลิค' ) || false !== strpos( $title, 'clpl' ) || false !== strpos( $title, 'polycarbonate' ) ) {
		return array(
			'slug'          => 'plastwood-acrylic',
			'name'          => 'พลาสวูด & อะคริลิค (Plastwood & Acrylic)',
			'short_name'    => 'พลาสวูด & อะคริลิค',
			'badge_bg'      => '#0284C7',
			'default_cover' => 'cat-signage-acrylic.webp',
		);
	}

	// 3. Signage & LED Systems
	if ( in_array( $slug, array( 'signage-led-lighting-liponix', 'building-led-lighting-liponix', 'signage-advertising', 'led-display-liponix', 'led-kiosk-liponix', 'signate-banner-smart-jet', 'mactac-signage', 'avery-signage' ), true ) ||
		false !== strpos( $title, 'led' ) || false !== strpos( $title, 'liponix' ) || false !== strpos( $title, 'signage' ) || false !== strpos( $title, 'ป้าย' ) || false !== strpos( $title, 'lighting' ) ) {
		return array(
			'slug'          => 'signage-led',
			'name'          => 'งานป้ายอาคาร & ระบบไฟ LED (Signage & LED)',
			'short_name'    => 'ป้ายอาคาร & ไฟ LED',
			'badge_bg'      => '#D97706',
			'default_cover' => 'project-signage.webp',
		);
	}

	// 4. Wrap, Vinyl & Large Format Print
	return array(
		'slug'          => 'wrap-vinyl',
		'name'          => 'มีเดียงานพิมพ์ & สติกเกอร์ (Wrap & Vinyl)',
		'short_name'    => 'สติกเกอร์ & งานพิมพ์',
		'badge_bg'      => '#10B981',
		'default_cover' => 'cat-print-vinyl.webp',
	);
}

/**
 * Retrieve curated gallery photos for a project (with high-res fallbacks)
 */
function ligermedia_get_project_gallery_data( $post_id ) {
	$post = get_post( $post_id );
	if ( ! $post ) {
		return array();
	}

	$cat        = ligermedia_get_project_category( $post );
	$theme_uri  = get_template_directory_uri() . '/assets/images/';
	$upload_dir = wp_upload_dir();

	$gallery = array();

	// 1. Featured Image
	if ( has_post_thumbnail( $post_id ) ) {
		$thumb_url = get_the_post_thumbnail_url( $post_id, 'full' );
		if ( $thumb_url ) {
			$gallery[] = array(
				'url'     => $thumb_url,
				'alt'     => $post->post_title . ' - ภาพรวมโครงการ',
				'caption' => 'ภาพมุมมองหลักโครงการ ' . $post->post_title,
				'aspect'  => 'landscape-wide',
			);
		}
	}

	// 2. Curated library photos from uploads directory
	$cat_files = glob( $upload_dir['basedir'] . '/curated-library/' . $cat['slug'] . '/*.jpg' );
	if ( ! empty( $cat_files ) ) {
		// Offset using post ID so each project displays unique images in its bento grid
		$offset  = (int) $post_id % count( $cat_files );
		$rotated = array_merge( array_slice( $cat_files, $offset ), array_slice( $cat_files, 0, $offset ) );

		$captions = array(
			'acp'               => array(
				'มุมมองฟาซาดอาคารและการตกแต่งภายนอกด้วยแผ่นคอมโพสิต',
				'ดีเทลการเข้ามุมและการติดตั้งโครงสร้างอลูมิเนียมมาตรฐานวิศวกรรม',
				'การเก็บรอยต่อและซีลแนวซิลิโคนโครงสร้างทนรังสี UV',
				'ความเรียบเนียนของพื้นผิวและแสงตกกระทบยามเย็น',
				'ความคงทนและมาตรฐานการทนความร้อนระดับสากล',
				'ภาพรวมมุมกว้างความประณีตของงานติดตั้งระดับพรีเมียม',
			),
			'plastwood-acrylic' => array(
				'งานฉลุลาย CNC พลาสวูดความหนาแน่นสูง ลวดลายประณีต',
				'งานตกแต่งผิวสถาปัตยกรรมลายไม้ธรรมชาติ Supremo CLPL',
				'แผ่นอะคริลิคหล่อใส ขอบเลเซอร์คัตเงางามดุจคริสตัล',
				'การติดตั้งระแนงและผนังตกแต่งภายใน ทนน้ำ 100% ไม่บวมพอง',
				'งานฉากกั้นและพาร์ติชันกั้นห้องสไตล์โมเดิร์น',
				'ภาพรวมงานตกแต่งสถานที่ระดับพรีเมียม',
			),
			'signage-led'       => array(
				'ป้ายตัวอักษรโลหะ 3D Channel Letters ส่องสว่างยามค่ำคืน',
				'ระบบไฟโมดูล Liponix LED เลนส์มุมกว้าง 160 องศา ไร้จุดมืด',
				'ความคมชัดและการกระจายแสงสว่างสม่ำเสมอทั่วทั้งหน้าป้าย',
				'หม้อแปลง Switching กันฝนมาตรฐาน IP67 ปลอดภัยสูงสุด',
				'งานป้ายทาวเวอร์และป้ายสัญลักษณ์อาคารพาณิชย์',
				'ความสวยงามโดดเด่นของงานระบบไฟตกแต่งอาคาร',
			),
			'wrap-vinyl'        => array(
				'งานติดตั้งฟิล์มเปลี่ยนสีรถยนต์ Mactac Car Wrap เรียบเนียนไร้ฟองอากาศ',
				'งานพิมพ์ดิจิทัลอิงค์เจ็ทความละเอียดสูง สีสด คมชัดระดับโฟโต้',
				'งานขึงผ้าไวนิล Frontlit ป้ายบิลบอร์ดขนาดใหญ่ ทนลมพายุ',
				'งานติดตั้งสติกเกอร์กราฟิกตกแต่งอาคารและกระจก',
				'ฟิล์มพิมพ์ติดพื้นกันลื่นมาตรฐานความปลอดภัย R10',
				'ความเรียบเนียนและความทนทานต่อสภาพอากาศภายนอก',
			),
		);

		$cat_caps = isset( $captions[ $cat['slug'] ] ) ? $captions[ $cat['slug'] ] : $captions['acp'];

		$added = 0;
		foreach ( $rotated as $idx => $filepath ) {
			$url = $upload_dir['baseurl'] . '/curated-library/' . $cat['slug'] . '/' . basename( $filepath );
			if ( ! empty( $gallery ) && $gallery[0]['url'] === $url ) {
				continue;
			}

			$cap       = $cat_caps[ $added % count( $cat_caps ) ];
			$gallery[] = array(
				'url'     => $url,
				'alt'     => $post->post_title . ' - ' . $cap,
				'caption' => $cap,
				'aspect'  => ( $added % 3 === 0 ) ? 'landscape-wide' : 'square',
			);
			$added++;
			if ( $added >= 6 ) {
				break;
			}
		}
	}

	// 3. Fallback theme pool if gallery is still sparse
	if ( count( $gallery ) < 4 ) {
		$fallback_pools = array(
			'acp'               => array(
				array( 'url' => $theme_uri . 'project-facade.webp', 'alt' => 'ภาพรวมงานติดตั้งแผ่นอลูมิเนียมคอมโพสิต', 'caption' => 'ภาพมุมกว้างเปลือกอาคารภายนอก (Exterior Cladding Facade)' ),
				array( 'url' => $theme_uri . 'hero-facade.webp', 'alt' => 'งานฟาซาดอาคารเชิงพาณิชย์', 'caption' => 'มุมมองยามเย็นและแสงตกกระทบผิวแผ่นคอมโพสิต' ),
			),
			'plastwood-acrylic' => array(
				array( 'url' => $theme_uri . 'banner-cat-plastwood-acrylic.webp', 'alt' => 'งานฉลุพลาสวูดและอะคริลิค', 'caption' => 'งานฉลุลาย CNC พลาสวูดความหนาแน่นสูง' ),
				array( 'url' => $theme_uri . 'cat-signage-acrylic.webp', 'alt' => 'แผ่นอะคริลิคขึ้นรูป', 'caption' => 'งานตัดเลเซอร์ขอบเงาคริสตัลใส' ),
			),
			'signage-led'       => array(
				array( 'url' => $theme_uri . 'project-signage.webp', 'alt' => 'ป้ายอาคารและตัวอักษร 3D', 'caption' => 'ป้ายตัวอักษรโลหะ 3D Channel Letters' ),
				array( 'url' => $theme_uri . 'hero-slide-led.webp', 'alt' => 'แสงสว่างตัวอาคารยามค่ำคืน', 'caption' => 'ความคมชัดและการส่องสว่างระดับพรีเมียม' ),
			),
			'wrap-vinyl'        => array(
				array( 'url' => $theme_uri . 'hero-slide-vinyl.webp', 'alt' => 'งานห่อหุ้มยานยนต์และฟิล์มสติกเกอร์', 'caption' => 'งานติดตั้งฟิล์มเปลี่ยนสี Mactac Car Wrap' ),
				array( 'url' => $theme_uri . 'project-billboard.webp', 'alt' => 'ป้ายบิลบอร์ดและโครงสร้างขนาดใหญ่', 'caption' => 'งานขึงผ้าไวนิล Frontlit ขนาดใหญ่' ),
			),
		);

		$fb = isset( $fallback_pools[ $cat['slug'] ] ) ? $fallback_pools[ $cat['slug'] ] : $fallback_pools['acp'];
		foreach ( $fb as $f_item ) {
			$gallery[] = array(
				'url'     => $f_item['url'],
				'alt'     => $f_item['alt'],
				'caption' => $f_item['caption'],
				'aspect'  => 'square',
			);
		}
	}

	return array(
		'id'          => $post->ID,
		'title'       => $post->post_title,
		'slug'        => $post->post_name,
		'category'    => $cat,
		'cover'       => ! empty( $gallery ) ? $gallery[0]['url'] : ( $theme_uri . $cat['default_cover'] ),
		'gallery'     => $gallery,
		'photo_count' => count( $gallery ),
	);
}

/**
 * Retrieve real WooCommerce products linked to a project's material group
 */
function ligermedia_get_project_related_products( $cat_slug, $limit = 4 ) {
	$slug_map = array(
		'acp'               => array( 'sky-high-acp-pvdf', 'dow-corning-structural-silicone', 'sky-high-acp-pe', 'backing-rod-foam' ),
		'plastwood-acrylic' => array( 'siam-plastwood-sheet', 'supreme-acrylic-opal-color', 'supremo-louver-wood', 'tigon-surface-film' ),
		'signage-led'       => array( 'liponix-commercial-flood-light', 'rainproof-power-supply-12v', 'avery-5500-qm-translucent', 'supreme-acrylic-opal-color' ),
		'wrap-vinyl'        => array( 'mactac-8300-pro', 'mactac-jt-5828-pr', 'smart-jet-frontlit-5m', 'heytex-sign-backlit' ),
	);

	$slugs    = isset( $slug_map[ $cat_slug ] ) ? $slug_map[ $cat_slug ] : $slug_map['acp'];
	$products = array();

	foreach ( array_slice( $slugs, 0, $limit ) as $s ) {
		$posts = get_posts( array(
			'name'        => $s,
			'post_type'   => 'product',
			'post_status' => 'publish',
			'numberposts' => 1,
		) );

		if ( ! empty( $posts ) ) {
			$p = $posts[0];
			$products[] = array(
				'id'        => $p->ID,
				'title'     => $p->post_title,
				'url'       => get_permalink( $p->ID ),
				'thumb'     => get_the_post_thumbnail_url( $p->ID, 'medium' ) ?: ( get_template_directory_uri() . '/assets/images/cat-architectural-acp.webp' ),
				'cat_name'  => ( 'acp' === $cat_slug ) ? 'อลูมิเนียมคอมโพสิต' : ( ( 'plastwood-acrylic' === $cat_slug ) ? 'พลาสวูด & อะคริลิค' : ( ( 'signage-led' === $cat_slug ) ? 'ระบบไฟ LED & ป้าย' : 'สติกเกอร์ & มีเดีย' ) ),
			);
		}
	}

	return $products;
}

/**
 * ==========================================================================
 * Central Unified Design System Card Templates (Products & Articles)
 * ==========================================================================
 */

/**
 * Render Branded Placeholder Image SVG when post thumbnail is missing
 */
function ligermedia_get_placeholder_image( $type = 'product' ) {
	$label = ( 'article' === $type ) ? 'LIGER MEDIA KNOWLEDGE' : 'LIGER MEDIA MATERIAL';
	return 'data:image/svg+xml;charset=UTF-8,' . rawurlencode(
		'<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 600 450" width="100%" height="100%">' .
		'<rect width="600" height="450" fill="#F3F4F6"/>' .
		'<pattern id="grid" width="30" height="30" patternUnits="userSpaceOnUse">' .
		'<path d="M 30 0 L 0 0 0 30" fill="none" stroke="#E5E7EB" stroke-width="1"/>' .
		'</pattern>' .
		'<rect width="600" height="450" fill="url(#grid)"/>' .
		'<circle cx="300" cy="205" r="45" fill="#FFEFE6"/>' .
		'<path d="M285 195 L315 195 L300 220 Z" fill="#FF5500"/>' .
		'<text x="300" y="275" font-family="system-ui, -apple-system, sans-serif" font-size="14" font-weight="700" fill="#6B7280" text-anchor="middle" letter-spacing="1">' . $label . '</text>' .
		'</svg>'
	);
}

/**
 * Standard Product Card Template (Unified across Homepage, Catalog, and Archives)
 *
 * @param int|WP_Post|null $post_id
 * @param string           $extra_classes
 * @return string HTML markup
 */
function ligermedia_render_product_card( $post_id = null, $extra_classes = '' ) {
	$post = get_post( $post_id );
	if ( ! $post ) {
		return '';
	}

	$pid       = $post->ID;
	$url       = get_permalink( $pid );
	$title     = get_the_title( $pid );
	$thumb_url = get_the_post_thumbnail_url( $pid, 'medium_large' );
	if ( ! $thumb_url ) {
		$thumb_url = ligermedia_get_placeholder_image( 'product' );
	}

	// Categories & Badge
	$badge     = get_post_meta( $pid, '_liger_highlight_badge', true );
	$terms     = get_the_terms( $pid, 'product_cat' );
	$cat_name  = ( ! empty( $terms ) && ! is_wp_error( $terms ) ) ? $terms[0]->name : 'วัสดุวิศวกรรม';
	$tag_text  = ! empty( $badge ) ? $badge : $cat_name;

	// Excerpt
	$excerpt = get_the_excerpt( $pid );
	if ( empty( $excerpt ) || false !== strpos( $excerpt, '???' ) ) {
		$excerpt = wp_trim_words( wp_strip_all_tags( $post->post_content ), 14, '...' );
		if ( empty( $excerpt ) ) {
			$excerpt = 'วัสดุคุณภาพสูงมาตรฐานสากล คัดสรรโดย LIGER MEDIA พร้อมจัดส่งและให้คำปรึกษาทางวิศวกรรม';
		}
	} else {
		$excerpt = wp_trim_words( $excerpt, 14, '...' );
	}

	ob_start();
	?>
	<article class="featured-card-item ds-card <?php echo esc_attr( $extra_classes ); ?>" style="display:flex;flex-direction:column;height:100%;background:#FFFFFF;border-radius:16px;overflow:hidden;border:1px solid #E5E5E5;transition:all 0.25s cubic-bezier(0.16,1,0.3,1);">
		<a href="<?php echo esc_url( $url ); ?>" class="featured-card-media" aria-label="<?php echo esc_attr( $title ); ?>" style="display:block;aspect-ratio:4/3;overflow:hidden;background:#F5F5F5;position:relative;">
			<img src="<?php echo esc_url( $thumb_url ); ?>" alt="<?php echo esc_attr( $title ); ?>" loading="lazy" decoding="async" style="width:100%;height:100%;object-fit:cover;transition:transform 0.35s ease;" />
		</a>
		<div class="featured-card-body" style="padding:1.25rem;display:flex;flex-direction:column;flex-grow:1;">
			<span class="featured-category-tag ds-badge" style="align-self:flex-start;margin-bottom:0.6rem;font-size:0.75rem;font-weight:700;color:#FF5500;background:rgba(255,85,0,0.08);padding:3px 10px;border-radius:9999px;">
				<?php echo esc_html( $tag_text ); ?>
			</span>
			<h3 class="featured-card-title ds-card-title" style="margin:0 0 0.5rem 0;font-size:1.05rem;font-weight:700;line-height:1.4;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;text-overflow:ellipsis;min-height:2.8em;">
				<a href="<?php echo esc_url( $url ); ?>" style="color:#111111;text-decoration:none;transition:color 0.15s ease;">
					<?php echo esc_html( $title ); ?>
				</a>
			</h3>
			<p class="featured-card-desc" style="color:#707072;font-size:0.875rem;line-height:1.5;margin:0 0 1rem 0;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;text-overflow:ellipsis;">
				<?php echo esc_html( $excerpt ); ?>
			</p>
			<div class="featured-card-footer" style="margin-top:auto;display:flex;align-items:center;justify-content:space-between;padding-top:0.75rem;border-top:1px solid #E5E5E5;">
				<a href="<?php echo esc_url( $url ); ?>" class="featured-cta-label" style="text-decoration:none;font-size:0.875rem;font-weight:700;color:#111111;">ดูสเปก &amp; สั่งตัวอย่าง</a>
				<a href="<?php echo esc_url( $url ); ?>" class="featured-cta-icon" aria-label="ดูสเปก <?php echo esc_attr( $title ); ?>" style="width:30px;height:30px;border-radius:50%;background:#F5F5F5;display:flex;align-items:center;justify-content:center;color:#111111;text-decoration:none;transition:all 0.2s ease;">
					<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14"/><path d="M12 5l7 7-7 7"/></svg>
				</a>
			</div>
		</div>
	</article>
	<?php
	return ob_get_clean();
}

/**
 * Standard Article Card Template (Unified across Homepage, Knowledge Hub, and Archives)
 *
 * @param int|WP_Post|null $post_id
 * @param int              $card_idx
 * @param string           $extra_classes
 * @return string HTML markup
 */
function ligermedia_render_article_card( $post_id = null, $card_idx = 0, $extra_classes = '', $extra_attrs = array() ) {
	$post = get_post( $post_id );
	if ( ! $post ) {
		return '';
	}

	$pid       = $post->ID;
	$url       = get_permalink( $pid );
	$title     = get_the_title( $pid );
	$thumb_url = get_the_post_thumbnail_url( $pid, 'medium_large' );
	if ( ! $thumb_url ) {
		$thumb_url = ligermedia_get_placeholder_image( 'article' );
	}

	$post_cats = get_the_category( $pid );
	$badge_cat = ! empty( $post_cats ) ? $post_cats[0]->name : 'สาระวิศวกรรม';
	$date_str  = get_the_date( 'j M Y', $pid );

	// Excerpt
	$excerpt = get_the_excerpt( $pid );
	if ( empty( $excerpt ) || false !== strpos( $excerpt, '???' ) ) {
		$excerpt = wp_trim_words( wp_strip_all_tags( $post->post_content ), 16, '...' );
		if ( empty( $excerpt ) ) {
			$excerpt = 'เจาะลึกองค์ความรู้และมาตรฐานวิศวกรรมวัสดุ โดยทีมงานผู้เชี่ยวชาญ LIGER MEDIA';
		}
	} else {
		$excerpt = wp_trim_words( $excerpt, 16, '...' );
	}

	$attr_str = '';
	if ( is_array( $extra_attrs ) ) {
		foreach ( $extra_attrs as $k => $v ) {
			$attr_str .= ' ' . esc_attr( $k ) . '="' . esc_attr( $v ) . '"';
		}
	}

	ob_start();
	?>
	<article class="liger-article-card ds-card <?php echo esc_attr( $extra_classes ); ?>"<?php echo $attr_str; ?> style="display:flex;flex-direction:column;height:100%;background:#FFFFFF;border-radius:16px;overflow:hidden;border:1px solid #E5E5E5;transition:all 0.25s cubic-bezier(0.16,1,0.3,1);">
		<a href="<?php echo esc_url( $url ); ?>" class="liger-article-img-link" aria-label="<?php echo esc_attr( $title ); ?>" style="display:block;aspect-ratio:16/10;overflow:hidden;background:#F5F5F5;position:relative;">
			<div class="liger-article-img-wrap" style="position:relative;width:100%;height:100%;overflow:hidden;">
				<img src="<?php echo esc_url( $thumb_url ); ?>" alt="<?php echo esc_attr( $title ); ?>" loading="lazy" decoding="async" style="width:100%;height:100%;object-fit:cover;transition:transform 0.35s ease;" />
				<?php if ( $card_idx > 0 ) : ?>
					<span class="liger-archive-article-order" style="position:absolute;top:10px;left:10px;background:rgba(17,17,17,0.85);color:#FFF;font-size:0.75rem;font-weight:700;padding:2px 8px;border-radius:9999px;backdrop-filter:blur(4px);">
						<?php echo sprintf( '%02d', $card_idx ); ?>
					</span>
				<?php endif; ?>
			</div>
		</a>
		<div class="liger-article-body" style="padding:1.25rem;display:flex;flex-direction:column;flex-grow:1;">
			<div class="liger-article-meta" style="display:flex;align-items:center;gap:6px;font-size:0.78rem;color:#707072;margin-bottom:0.6rem;flex-wrap:wrap;">
				<span class="liger-article-date"><?php echo esc_html( $date_str ); ?></span>
				<span class="liger-article-dot">•</span>
				<span class="liger-article-read">อ่าน 4-6 นาที</span>
				<span class="liger-article-level ds-badge" style="margin-left:auto;font-size:0.7rem;font-weight:600;padding:2px 8px;background:#F5F5F5;color:#707072;border-radius:9999px;">
					<?php echo esc_html( $badge_cat ); ?>
				</span>
			</div>
			<h3 class="liger-article-title ds-card-title" style="margin:0 0 0.5rem 0;font-size:1.05rem;font-weight:700;line-height:1.4;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;text-overflow:ellipsis;min-height:2.8em;">
				<a href="<?php echo esc_url( $url ); ?>" style="color:#111111;text-decoration:none;transition:color 0.15s ease;">
					<?php echo esc_html( $title ); ?>
				</a>
			</h3>
			<p class="liger-article-desc" style="color:#707072;font-size:0.875rem;line-height:1.5;margin:0 0 1rem 0;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;text-overflow:ellipsis;">
				<?php echo esc_html( $excerpt ); ?>
			</p>
			<div class="liger-article-footer" style="margin-top:auto;display:flex;align-items:center;justify-content:space-between;padding-top:0.75rem;border-top:1px solid #E5E5E5;">
				<a href="<?php echo esc_url( $url ); ?>" style="font-size:0.875rem;font-weight:700;color:#FF5500;text-decoration:none;display:inline-flex;align-items:center;gap:4px;">
					<span>อ่านบทความฉบับเต็ม</span>
					<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M5 12h14"/><path d="M12 5l7 7-7 7"/></svg>
				</a>
			</div>
		</div>
	</article>
	<?php
	return ob_get_clean();
}

/**
 * Render Nike-Style Product Category Sidebar Navigation with dynamic post counts
 */
function ligermedia_product_sidebar_nav_shortcode() {
	$current_slug = '';
	if ( is_tax( 'product_cat' ) ) {
		$queried = get_queried_object();
		if ( $queried && isset( $queried->slug ) ) {
			$current_slug = $queried->slug;
		}
	}

	$core_categories = array(
		'acp'         => array( 'name' => 'แผ่นอลูมิเนียมคอมโพสิต', 'slug' => 'acp' ),
		'interior'    => array( 'name' => 'สติกเกอร์อินทีเรียร์ฟิล์ม', 'slug' => 'interior' ),
		'sticker'     => array( 'name' => 'สติกเกอร์งานป้าย & ยานยนต์', 'slug' => 'sticker' ),
		'vinyl'       => array( 'name' => 'ผ้าไวนิลตู้ไฟ & บิลบอร์ด', 'slug' => 'vinyl' ),
		'pvc-sheet'   => array( 'name' => 'แผ่นพลาสวูด & อะคริลิก', 'slug' => 'pvc-sheet' ),
		'inkjet'      => array( 'name' => 'มีเดียงานพิมพ์ดิจิทัล', 'slug' => 'inkjet' ),
		'led'         => array( 'name' => 'โมดูลไฟ Liponix LED', 'slug' => 'led' ),
		'accessories' => array( 'name' => 'อุปกรณ์ติดตั้ง & เทปยก', 'slug' => 'accessories' ),
	);

	$total_count = 0;
	$items = array();

	foreach ( $core_categories as $key => $cat ) {
		$term = get_term_by( 'slug', $cat['slug'], 'product_cat' );
		$count = $term ? (int) $term->count : 0;
		$total_count += $count;
		$items[ $key ] = array(
			'name'  => $cat['name'],
			'slug'  => $cat['slug'],
			'count' => $count,
			'url'   => home_url( '/product-category/' . $cat['slug'] . '/' ),
		);
	}

	// Active status for "All Products"
	$all_active = empty( $current_slug ) ? ' is-active' : '';

	ob_start();
	?>
	<ul class="nike-cat-nav-list" id="nike-products-cat-nav">
		<li>
			<a href="<?php echo esc_url( home_url( '/products/' ) ); ?>" class="nike-cat-link<?php echo esc_attr( $all_active ); ?>">
				<span>ทั้งหมด (All Products)</span>
				<span class="nike-cat-count"><?php echo esc_html( $total_count ); ?></span>
			</a>
		</li>
		<?php foreach ( $items as $it ) : ?>
			<?php $is_active = ( $current_slug === $it['slug'] ) ? ' is-active' : ''; ?>
			<li>
				<a href="<?php echo esc_url( $it['url'] ); ?>" class="nike-cat-link<?php echo esc_attr( $is_active ); ?>" data-cat-slug="<?php echo esc_attr( $it['slug'] ); ?>">
					<span><?php echo esc_html( $it['name'] ); ?></span>
					<span class="nike-cat-count"><?php echo esc_html( $it['count'] ); ?></span>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>
	<?php
	$output = ob_get_clean();
	return str_replace( array( '<br />', '<br>', '<p>', '</p>' ), '', $output );
}
add_shortcode( 'liger_product_sidebar_nav', 'ligermedia_product_sidebar_nav_shortcode' );

/**
 * 3D Interactive Catalog Flipbook Viewer Modal
 * 100% Self-Hosted, Matching composite/index.html (StPageFlip Engine)
 */
function ligermedia_flipbook_modal_shortcode() {
	ob_start();
	?>
	<!-- ========================================================
	     LIGER 3D INTERACTIVE FLIPBOOK VIEWER MODAL
	     ======================================================== -->
	<div class="liger-flip-modal" id="ligerFlipModal" aria-hidden="true" role="dialog" aria-modal="true" aria-label="3D Interactive Catalog Flipbook">
		<div class="liger-flip-backdrop" id="ligerFlipBackdrop"></div>
		
		<div class="liger-flip-window">
			<iframe id="ligerFlipIframe" class="liger-flip-iframe" src="" frameborder="0" allowfullscreen="true" allow="clipboard-write"></iframe>
		</div>
	</div>
	<?php
	$html = ob_get_clean();
	return str_replace( array( '<p></p>', '<p><br />', '<br>' ), '', $html );
}
add_shortcode( 'liger_flipbook_modal', 'ligermedia_flipbook_modal_shortcode' );

/**
 * 100% Self-Hosted Standalone Inline 3D Flipbook Showcase Shortcode
 * Zero external iframes, zero third-party dependencies, 100% local assets
 */
function ligermedia_flipbook_showcase_shortcode( $atts = array() ) {
	$atts = shortcode_atts(
		array(
			'pdf'      => '/ligermedia/wp-content/uploads/catalogs/liger-master-catalog-2026.pdf',
			'title'    => 'แคตตาล็อกแผ่นอลูมิเนียมคอมโพสิต Sky-High ACP 2026',
			'subtitle' => 'เปิดอ่านออนไลน์เสมือนเล่มจริง พลิกหน้าแบบ 3D ซูมดูชาร์ตสีและตารางสเปกวิศวกรรมสากล หรือดาวน์โหลดไฟล์ PDF ต้นฉบับ',
			'size'     => '7.7 MB',
		),
		$atts,
		'liger_flipbook_showcase'
	);

	ob_start();
	?>
	<section class="liger-self-showcase" id="ligerSelfShowcase" data-pdf-url="<?php echo esc_url( $atts['pdf'] ); ?>" data-reveal="up">
		<!-- Header Control Bar -->
		<div class="liger-anyflip-header-bar">
			<div class="liger-anyflip-title-wrap">
				<div class="liger-anyflip-badge-pill">
					<span class="liger-pulse-dot"></span>
					<span>SELF-HOSTED 3D E-CATALOG</span>
				</div>
				<h2 class="liger-anyflip-heading">
					<?php echo esc_html( $atts['title'] ); ?>
				</h2>
				<p class="liger-anyflip-desc">
					<?php echo esc_html( $atts['subtitle'] ); ?>
				</p>
			</div>
			<div class="liger-anyflip-actions-bar">
				<button type="button" class="liger-btn liger-btn--outline liger-self-fs-btn" id="ligerInlineFsBtn" title="เปิดอ่านเต็มจอ (Fullscreen)">
					<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><polyline points="15 3 21 3 21 9"/><polyline points="9 21 3 21 3 15"/><line x1="21" y1="3" x2="14" y2="10"/><line x1="3" y1="21" x2="10" y2="14"/></svg>
					<span>เปิดเต็มจอ</span>
				</button>
				<a href="<?php echo esc_url( $atts['pdf'] ); ?>" download class="liger-btn liger-btn--primary" title="ดาวน์โหลดไฟล์ PDF ต้นฉบับ">
					<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
					<span>ดาวน์โหลดไฟล์ PDF (<?php echo esc_html( $atts['size'] ); ?>)</span>
				</a>
			</div>
		</div>

		<!-- 3D Book Stage (100% Self-Hosted Canvas Rendering) -->
		<div class="liger-inline-stage" id="ligerInlineStage">
			<!-- Loading State -->
			<div class="liger-flip-loading" id="ligerInlineLoading">
				<div class="liger-flip-spinner"></div>
				<p>กำลังเตรียมเปิดเล่มแคตตาล็อก...</p>
			</div>

			<!-- 3D Book Wrap -->
			<div class="liger-3d-book-wrap" id="ligerInlineBookWrap">
				<div class="liger-3d-book" id="ligerInlineBook">
					<!-- Center Spine Ridge -->
					<div class="liger-book-spine"></div>

					<!-- Left Page Area -->
					<div class="liger-book-page liger-book-page--left" id="ligerInlinePageLeft">
						<div class="liger-page-inner">
							<canvas id="ligerInlineCanvasLeft"></canvas>
							<div class="liger-page-num" id="ligerInlinePageNumLeft">1</div>
						</div>
						<div class="liger-page-fold-shadow"></div>
					</div>

					<!-- Right Page Area -->
					<div class="liger-book-page liger-book-page--right" id="ligerInlinePageRight">
						<div class="liger-page-inner">
							<canvas id="ligerInlineCanvasRight"></canvas>
							<div class="liger-page-num" id="ligerInlinePageNumRight">2</div>
						</div>
						<div class="liger-page-curl-corner" title="คลิกเพื่อพลิกหน้าถัดไป"></div>
					</div>

					<!-- Animated 3D Flipping Sheet -->
					<div class="liger-flip-sheet" id="ligerInlineFlipSheet">
						<div class="liger-sheet-face liger-sheet-front">
							<canvas id="ligerInlineCanvasFront"></canvas>
						</div>
						<div class="liger-sheet-face liger-sheet-back">
							<canvas id="ligerInlineCanvasBack"></canvas>
						</div>
					</div>
				</div>
			</div>

			<!-- Floating Stage Navigation Chevrons -->
			<button type="button" class="liger-stage-nav liger-stage-nav--prev" id="ligerInlineStagePrev" aria-label="Previous Page">
				<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
			</button>
			<button type="button" class="liger-stage-nav liger-stage-nav--next" id="ligerInlineStageNext" aria-label="Next Page">
				<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
			</button>
		</div>

		<!-- Collapsible Thumbnails Drawer -->
		<div class="liger-flip-thumbs-drawer" id="ligerInlineThumbsDrawer">
			<div class="liger-thumbs-header">
				<span>ภาพย่อทุกหน้า (Thumbnails Overview)</span>
				<button type="button" class="liger-thumbs-close" id="ligerInlineThumbsClose">✕</button>
			</div>
			<div class="liger-thumbs-track" id="ligerInlineThumbsTrack"></div>
		</div>

		<!-- Bottom Interactive Toolbar -->
		<footer class="liger-flip-toolbar" id="ligerInlineToolbar">
			<div class="liger-tb-group liger-tb-left">
				<button type="button" class="liger-tb-btn" id="ligerInlineThumbToggle" title="แสดงภาพย่อหน้า (Thumbnails)">
					<svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
					<span class="liger-tb-label">ภาพย่อหน้า</span>
				</button>
			</div>

			<div class="liger-tb-group liger-tb-center">
				<button type="button" class="liger-tb-btn" id="ligerInlineFirstBtn" title="ไปหน้าแรก (First Page)">
					<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="11 17 6 12 11 7"/><polyline points="18 17 13 12 18 7"/></svg>
				</button>
				<button type="button" class="liger-tb-btn" id="ligerInlinePrevBtn" title="หน้าก่อนหน้า (Previous)">
					<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
				</button>

				<div class="liger-tb-page-jump">
					<input type="number" id="ligerInlinePageInput" class="liger-tb-page-input" value="1" min="1" max="99" aria-label="Page number">
					<span class="liger-tb-slash">/</span>
					<span id="ligerInlineTotalPages">8</span>
				</div>

				<button type="button" class="liger-tb-btn" id="ligerInlineNextBtn" title="หน้าถัดไป (Next)">
					<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
				</button>
				<button type="button" class="liger-tb-btn" id="ligerInlineLastBtn" title="ไปหน้าสุดท้าย (Last Page)">
					<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="13 17 18 12 13 7"/><polyline points="6 17 11 12 6 7"/></svg>
				</button>
			</div>

			<div class="liger-tb-group liger-tb-right">
				<button type="button" class="liger-tb-btn" id="ligerInlineAutoBtn" title="เล่นสไลด์พลิกหน้าอัตโนมัติ (Auto Flip)">
					<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="5 3 19 12 5 21 5 3"/></svg>
					<span class="liger-tb-label">Auto</span>
				</button>
				<button type="button" class="liger-tb-btn is-active" id="ligerInlineSoundBtn" title="เปิด/ปิดเสียงพลิกกระดาษ">
					<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"/><path d="M15.54 8.46a5 5 0 0 1 0 7.07"/><path d="M19.07 4.93a10 10 0 0 1 0 14.14"/></svg>
				</button>
			</div>
		</footer>
	</section>
	<?php
	$html = ob_get_clean();
	return str_replace( array( '<p></p>', '<p><br />', '<br>' ), '', $html );
}
add_shortcode( 'liger_flipbook_showcase', 'ligermedia_flipbook_showcase_shortcode' );










