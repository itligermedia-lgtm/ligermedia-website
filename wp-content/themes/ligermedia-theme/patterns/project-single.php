<?php
/**
 * Title: Project Single (Modern Editorial Bento Gallery & Materials Integration)
 * Slug: ligermedia/project-single
 * Categories: featured, text, gallery
 * Description: Cutting-edge modern architectural single project layout with Bento grid gallery, integrated lightbox, technical installation profile, linked materials used, and related projects.
 */

$pid = get_the_ID();
if ( ! $pid ) {
	$pid = get_queried_object_id();
}

$post = get_post( $pid );
if ( ! $post ) {
	return;
}

$pdata = ligermedia_get_project_gallery_data( $pid );
$cat   = $pdata['category'];

$location = get_post_meta( $pid, '_liger_project_location', true ) ?: 'กรุงเทพฯ และปริมณฑล';
$year     = get_post_meta( $pid, '_liger_project_year', true ) ?: '2025';
$area     = get_post_meta( $pid, '_liger_project_area_sqm', true ) ?: '2,500 ตร.ม.';

// Map materials appropriately based on category to prevent outdated placeholder text
$raw_materials = get_post_meta( $pid, '_liger_project_materials', true );
if ( empty( $raw_materials ) || ( 'signage-led' === $cat['slug'] && false !== strpos( $raw_materials, 'ACP' ) ) || ( 'plastwood-acrylic' === $cat['slug'] && false !== strpos( $raw_materials, 'ACP' ) ) ) {
	$materials_map = array(
		'acp'               => 'แผ่นอลูมิเนียมคอมโพสิต SKY-HIGH FR / ซิลิโคนโครงสร้าง',
		'plastwood-acrylic' => 'แผ่นพลาสวูดฉลุ CNC / อะคริลิคหล่อใส Supreme / ระแนง CLPL',
		'signage-led'       => 'ป้ายตัวอักษร 3D / โมดูลไฟ Liponix LED / สติกเกอร์โปร่งแสง Avery',
		'wrap-vinyl'        => 'สติกเกอร์ Mactac Car Wrap / ผ้าไวนิล Smart Jet Frontlit',
	);
	$materials = isset( $materials_map[ $cat['slug'] ] ) ? $materials_map[ $cat['slug'] ] : $cat['short_name'];
} else {
	$materials = $raw_materials;
}

// Clean post content (remove raw old galleries or scripts)
$raw_content = $post->post_content;
// Strip wp:gallery and fb-root/scripts
$clean_content = preg_replace( '/<!-- wp:gallery.*?<!-- \/wp:gallery -->/s', '', $raw_content );
$clean_content = preg_replace( '/<script\b[^>]*>(.*?)<\/script>/is', '', $clean_content );
$clean_content = preg_replace( '/<!-- wp:html.*?<!-- \/wp:html -->/s', '', $clean_content );
$clean_content = trim( apply_filters( 'the_content', $clean_content ) );

if ( empty( $clean_content ) || strlen( strip_tags( $clean_content ) ) < 20 ) {
	$clean_content = '
	<p>โครงการนี้ได้รับการออกแบบและติดตั้งโดยคำนึงถึงมาตรฐานความปลอดภัยสูงสุด ความสวยงามทันสมัย และความทนทานต่อสภาพภูมิอากาศร้อนชื้นของประเทศไทย โดยเลือกใช้วัสดุเกรดพรีเมียมจาก <strong>LIGER MEDIA</strong> ที่ผ่านการทดสอบมาตรฐานระดับสากล</p>
	<p>ทีมวิศวกรและผู้เชี่ยวชาญด้านวัสดุได้ร่วมให้คำปรึกษาตั้งแต่ขั้นตอนการเลือกสเปก การคำนวณโครงสร้างรอยต่อ และเทคนิคการติดตั้งเพื่อให้งานออกมาประณีต เรียบเนียน ไร้รอยสะดุด พร้อมรับประกันคุณภาพการใช้งานยาวนาน</p>';
}

// Fetch Linked Products
$related_products = ligermedia_get_project_related_products( $cat['slug'], 4 );

// Fetch 3 Related Projects in the same category
$related_projects_query = new WP_Query( array(
	'post_type'      => 'project',
	'posts_per_page' => 3,
	'post__not_in'   => array( $pid ),
	'orderby'        => 'rand',
	'post_status'    => 'publish',
) );
?>

<div class="nike-project-single-wrap" style="background:#FFFFFF;color:#111827;padding-bottom:5rem;">
	
	<!-- Top Breadcrumb & Back Strip -->
	<div class="nike-container alignwide" style="padding-left:clamp(1.5rem, 3.5vw, 3rem);padding-right:clamp(1.5rem, 3.5vw, 3rem);padding-top:1.75rem;padding-bottom:0.75rem;">
		<nav class="nike-breadcrumbs" aria-label="Breadcrumb" style="font-size:0.85rem;color:#6B7280;display:flex;align-items:center;gap:6px;">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" style="color:#6B7280;text-decoration:none;">หน้าแรก</a>
			<span>/</span>
			<a href="<?php echo esc_url( home_url( '/projects/' ) ); ?>" style="color:#6B7280;text-decoration:none;">ผลงานและโครงการ</a>
			<span>/</span>
			<span style="color:#111827;font-weight:600;"><?php echo esc_html( $cat['short_name'] ); ?></span>
		</nav>
	</div>

	<!-- Project Hero Header -->
	<header class="nike-project-header-section" style="padding-top:1.25rem;padding-bottom:2.25rem;">
		<div class="nike-container alignwide" style="padding-left:clamp(1.5rem, 3.5vw, 3rem);padding-right:clamp(1.5rem, 3.5vw, 3rem);">
			
			<div class="nike-project-eyebrow" style="display:inline-flex;align-items:center;gap:10px;margin-bottom:0.75rem;"><span style="font-size:0.8rem;font-weight:700;letter-spacing:0.06em;text-transform:uppercase;color:#FF5500;"><?php echo esc_html( $cat['short_name'] ); ?></span><span style="color:#D1D5DB;">•</span><span style="font-size:0.85rem;color:#6B7280;font-weight:500;"><?php echo esc_html( $pdata['photo_count'] ); ?> ภาพผลงานโครงการจริง</span></div>

			<h1 style="font-family:var(--wp--preset--font-family--display, sans-serif);font-size:clamp(2rem, 3.6vw, 3.25rem);font-weight:800;letter-spacing:-0.025em;color:#111827;margin:0 0 1.5rem 0;line-height:1.2;">
				<?php echo esc_html( $pdata['title'] ); ?>
			</h1>

			<!-- Key Specs Architectural Divider Strip (Clean Typographic Editorial) -->
			<div class="nike-project-header-specs" style="display:flex;flex-wrap:wrap;align-items:flex-start;gap:2rem 3.5rem;padding:1.5rem 0;border-top:1px solid #E5E7EB;border-bottom:1px solid #E5E7EB;background:transparent;">
				<div style="display:flex;flex-direction:column;gap:6px;">
					<span style="font-size:0.75rem;color:#6B7280;text-transform:uppercase;letter-spacing:0.06em;font-weight:600;">สถานที่ตั้ง</span>
					<span style="color:#111827;font-weight:700;font-size:1.05rem;line-height:1.4;">
						<?php echo esc_html( $location ); ?>
					</span>
				</div>
				<div style="display:flex;flex-direction:column;gap:6px;">
					<span style="font-size:0.75rem;color:#6B7280;text-transform:uppercase;letter-spacing:0.06em;font-weight:600;">ปีที่แล้วเสร็จ</span>
					<span style="color:#111827;font-weight:700;font-size:1.05rem;line-height:1.4;">
						ปี <?php echo esc_html( $year ); ?>
					</span>
				</div>
				<div style="display:flex;flex-direction:column;gap:6px;">
					<span style="font-size:0.75rem;color:#6B7280;text-transform:uppercase;letter-spacing:0.06em;font-weight:600;">พื้นที่ติดตั้ง</span>
					<span style="color:#111827;font-weight:700;font-size:1.05rem;line-height:1.4;">
						<?php echo esc_html( $area ); ?>
					</span>
				</div>
				<div style="display:flex;flex-direction:column;gap:6px;flex:1;min-width:260px;">
					<span style="font-size:0.75rem;color:#6B7280;text-transform:uppercase;letter-spacing:0.06em;font-weight:600;">วัสดุหลักที่เลือกใช้</span>
					<span style="color:#FF5500;font-weight:700;font-size:1.05rem;line-height:1.4;">
						<?php echo esc_html( $materials ); ?>
					</span>
				</div>
			</div>

		</div>
	</header>

	<!-- ========================================================
	     MODERN CHIC BENTO GALLERY (Editorial Showcase)
	     ======================================================== -->
	<section class="nike-project-bento-section" style="margin-bottom:3.5rem;">
		<div class="nike-container alignwide" style="padding-left:clamp(1.5rem, 3.5vw, 3rem);padding-right:clamp(1.5rem, 3.5vw, 3rem);">
			
			<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem;">
				<h2 style="font-size:1.15rem;font-weight:700;color:#111827;margin:0;display:flex;align-items:center;gap:8px;">
					<span>ภาพถ่ายสถาปัตยกรรม &amp; รายละเอียดงานติดตั้ง</span>
				</h2>
				<span style="font-size:0.8rem;color:#6B7280;display:inline-flex;align-items:center;gap:6px;">
					<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/><line x1="11" y1="8" x2="11" y2="14"/><line x1="8" y1="11" x2="14" y2="11"/></svg>
					คลิกที่ภาพเพื่อดูขนาดใหญ่เต็มจอ (Full Screen Zoom)
				</span>
			</div>

			<div class="nike-bento-gallery-grid" id="nikeProjectGallery">
				<?php 
				$total_gallery = count( $pdata['gallery'] );
				foreach ( $pdata['gallery'] as $idx => $img ) : 
					$bento_class = 'bento-item-' . $idx;
					if ( 0 === $idx ) {
						$bento_class .= ' bento-hero';
					} elseif ( 1 === $idx || 2 === $idx ) {
						$bento_class .= ' bento-duo';
					} else {
						$bento_class .= ( $total_gallery >= 7 ) ? ' bento-quad' : ' bento-trio';
					}
				?>
					<figure class="nike-bento-item <?php echo esc_attr( $bento_class ); ?>" data-lightbox-index="<?php echo esc_attr( $idx ); ?>" data-full-url="<?php echo esc_url( $img['url'] ); ?>" data-caption="<?php echo esc_attr( $img['caption'] ); ?>">
						<img src="<?php echo esc_url( $img['url'] ); ?>" alt="<?php echo esc_attr( $img['alt'] ); ?>" loading="<?php echo ( 0 === $idx ) ? 'eager' : 'lazy'; ?>" />
						
						<!-- Hover Reveal Overlay -->
						<div class="nike-bento-overlay">
							<div class="nike-bento-top-info">
								<span class="nike-bento-idx">ภาพที่ <?php echo sprintf( '%02d', $idx + 1 ); ?> / <?php echo sprintf( '%02d', count( $pdata['gallery'] ) ); ?></span>
								<span class="nike-bento-zoom-icon">
									<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/><line x1="11" y1="8" x2="11" y2="14"/><line x1="8" y1="11" x2="14" y2="11"/></svg>
								</span>
							</div>
							<figcaption class="nike-bento-caption">
								<?php echo esc_html( $img['caption'] ); ?>
							</figcaption>
						</div>
					</figure>
				<?php endforeach; ?>
			</div>

		</div>
	</section>

	<!-- ========================================================
	     PROJECT STORY & TECHNICAL INSTALLATION PROFILE
	     ======================================================== -->
	<section class="nike-project-story-section" style="margin-bottom:4rem;">
		<div class="nike-container alignwide" style="padding-left:clamp(1.5rem, 3.5vw, 3rem);padding-right:clamp(1.5rem, 3.5vw, 3rem);">
			<div class="nike-story-grid" style="display:grid;grid-template-columns:1.5fr 1fr;gap:3rem;align-items:start;">
				
				<!-- Left Column: Story -->
				<div class="nike-story-content">
					<h2 style="font-size:1.5rem;font-weight:800;color:#111827;margin:0 0 1.25rem 0;display:flex;align-items:center;gap:10px;">
						<span style="width:4px;height:22px;background:#FF5500;border-radius:2px;display:inline-block;"></span>
						ความเป็นมาและความท้าทายของงานติดตั้ง
					</h2>
					<div class="nike-editorial-body" style="font-size:1.05rem;line-height:1.8;color:#374151;">
						<?php echo $clean_content; ?>
					</div>
				</div>

				<!-- Right Column: Profile Card -->
				<div class="nike-project-profile-card" style="background:#0D1117;border-radius:16px;border:1px solid #21262D;padding:2rem;color:#FFFFFF;position:sticky;top:80px;">
					<h3 style="font-size:1.15rem;font-weight:700;color:#FFFFFF;margin:0 0 1.25rem 0;display:flex;align-items:center;gap:8px;">
						<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#FF5500" stroke-width="2.2"><polygon points="12 2 2 7 12 12 22 7 12 2"/><polyline points="2 17 12 22 22 17"/><polyline points="2 12 12 17 22 12"/></svg>
						โปรไฟล์สเปกงานติดตั้ง
					</h3>

					<dl style="margin:0 0 1.75rem 0;display:grid;grid-template-columns:1fr;gap:1rem;font-size:0.9rem;">
						<div style="border-bottom:1px solid #21262D;padding-bottom:0.75rem;">
							<dt style="color:#8B949E;font-size:0.78rem;margin-bottom:2px;">หมวดหมู่งาน</dt>
							<dd style="margin:0;color:#FFFFFF;font-weight:600;"><?php echo esc_html( $cat['name'] ); ?></dd>
						</div>
						<div style="border-bottom:1px solid #21262D;padding-bottom:0.75rem;">
							<dt style="color:#8B949E;font-size:0.78rem;margin-bottom:2px;">วัสดุที่เลือกใช้</dt>
							<dd style="margin:0;color:#FF5500;font-weight:700;"><?php echo esc_html( $materials ); ?></dd>
						</div>
						<div style="border-bottom:1px solid #21262D;padding-bottom:0.75rem;">
							<dt style="color:#8B949E;font-size:0.78rem;margin-bottom:2px;">สถานที่ติดตั้ง</dt>
							<dd style="margin:0;color:#FFFFFF;font-weight:600;"><?php echo esc_html( $location ); ?></dd>
						</div>
						<div style="border-bottom:1px solid #21262D;padding-bottom:0.75rem;">
							<dt style="color:#8B949E;font-size:0.78rem;margin-bottom:2px;">ขนาดพื้นที่โดยประมาณ</dt>
							<dd style="margin:0;color:#FFFFFF;font-weight:600;"><?php echo esc_html( $area ); ?></dd>
						</div>
						<div>
							<dt style="color:#8B949E;font-size:0.78rem;margin-bottom:2px;">การรับประกันงานวัสดุ</dt>
							<dd style="margin:0;color:#10B981;font-weight:600;">รับประกัน 10 - 15 ปี (ตามมาตรฐานผู้ผลิต)</dd>
						</div>
					</dl>

					<div style="display:flex;flex-direction:column;gap:10px;">
						<a href="https://line.me/ti/p/~@ligermedia" target="_blank" rel="noopener" style="display:flex;align-items:center;justify-content:center;gap:8px;background:#FF5500;color:#FFFFFF;text-decoration:none;padding:12px 18px;border-radius:999px;font-weight:700;font-size:0.95rem;transition:background-color 0.2s ease;">
							<span>ปรึกษางานสเปกโครงการนี้</span>
							<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14"/><path d="M12 5l7 7-7 7"/></svg>
						</a>
						<a href="tel:021234567" style="display:flex;align-items:center;justify-content:center;gap:8px;background:rgba(255,255,255,0.08);color:#C9D1D9;text-decoration:none;padding:10px 18px;border-radius:999px;font-weight:600;font-size:0.875rem;transition:all 0.2s ease;">
							<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
							<span>ติดต่อสายด่วนฝ่ายโครงการ</span>
						</a>
					</div>
				</div>

			</div>
		</div>
	</section>

	<!-- ========================================================
	     MATERIALS USED (Real Linked Products Showcase)
	     ======================================================== -->
	<?php if ( ! empty( $related_products ) ) : ?>
		<section class="nike-materials-used-section alignfull" style="background:#F9FAFB;border-top:1px solid #E5E7EB;border-bottom:1px solid #E5E7EB;padding:4rem 0;">
			<div class="nike-container alignwide" style="padding-left:clamp(1.5rem, 3.5vw, 3rem);padding-right:clamp(1.5rem, 3.5vw, 3rem);">
				
				<div style="display:flex;align-items:flex-end;justify-content:space-between;flex-wrap:wrap;gap:1rem;margin-bottom:2rem;">
					<div>
						<div style="display:inline-flex;align-items:center;gap:6px;color:#FF5500;font-size:0.8rem;font-weight:700;letter-spacing:0.06em;text-transform:uppercase;margin-bottom:6px;">
							<span>MATERIALS &amp; PRODUCTS APPLIED</span>
						</div>
						<h2 style="font-family:var(--wp--preset--font-family--display, sans-serif);font-size:clamp(1.6rem, 2.5vw, 2.2rem);font-weight:800;color:#111827;margin:0 0 6px 0;">
							วัสดุและผลิตภัณฑ์ที่ใช้ในผลงานนี้
						</h2>
						<p style="color:#6B7280;margin:0;font-size:0.95rem;">
							สินค้าคุณภาพมาตรฐานสากลจาก LIGER MEDIA ที่ถูกนำไปใช้ในโครงการนี้ สามารถคลิกดูสเปกและขอตัวอย่างชิ้นงานได้ทันที
						</p>
					</div>

					<a href="<?php echo esc_url( home_url( '/products/' ) ); ?>" style="display:inline-flex;align-items:center;gap:6px;font-size:0.875rem;font-weight:700;color:#FF5500;text-decoration:none;">
						<span>ดูแคตตาล็อกสินค้าทั้งหมด</span>
						<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14"/><path d="M12 5l7 7-7 7"/></svg>
					</a>
				</div>

				<!-- Product Cards Row -->
				<div class="nike-materials-grid" style="display:grid;grid-template-columns:repeat(auto-fit, minmax(240px, 1fr));gap:1.5rem;">
					<?php foreach ( $related_products as $prod ) : ?>
						<div class="nike-material-card ds-card">
							<a href="<?php echo esc_url( $prod['url'] ); ?>" style="display:block;aspect-ratio:4/3;background:#F3F4F6;overflow:hidden;"><img src="<?php echo esc_url( $prod['thumb'] ); ?>" alt="<?php echo esc_attr( $prod['title'] ); ?>" loading="lazy" style="width:100%;height:100%;object-fit:cover;transition:transform 0.3s ease;" /></a>
							<div style="padding:1.25rem;display:flex;flex-direction:column;flex:1;justify-content:space-between;">
								<div>
									<div style="font-size:0.75rem;font-weight:600;color:#FF5500;text-transform:uppercase;letter-spacing:0.04em;margin-bottom:6px;"><?php echo esc_html( $prod['cat_name'] ); ?></div>
									<h3 style="font-size:0.95rem;font-weight:700;color:#111827;margin:0 0 1.25rem 0;line-height:1.4;"><a href="<?php echo esc_url( $prod['url'] ); ?>" style="color:#111827;text-decoration:none;"><?php echo esc_html( $prod['title'] ); ?></a></h3>
								</div>
								<div style="display:flex;gap:8px;align-items:center;">
									<a href="<?php echo esc_url( $prod['url'] ); ?>" class="ds-btn ds-btn--secondary ds-btn--sm" style="flex:1;">
										<span>ดูสเปกสินค้า</span>
										<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14"/><path d="M12 5l7 7-7 7"/></svg>
									</a>
									<a href="https://line.me/ti/p/~@ligermedia" target="_blank" rel="noopener" class="ds-btn-icon" title="ขอใบเสนอราคาวัสดุนี้">
										<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
									</a>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
				</div>

			</div>
		</section>
	<?php endif; ?>

	<!-- ========================================================
	     RELATED PROJECTS (More Installations from this Category)
	     ======================================================== -->
	<?php if ( $related_projects_query->have_posts() ) : ?>
		<section class="nike-related-projects-section" style="padding-top:4rem;">
			<div class="nike-container alignwide" style="padding-left:clamp(1.5rem, 3.5vw, 3rem);padding-right:clamp(1.5rem, 3.5vw, 3rem);">
				
				<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.75rem;">
					<h2 style="font-family:var(--wp--preset--font-family--display, sans-serif);font-size:1.4rem;font-weight:800;color:#111827;margin:0;">
						ผลงานและโครงการอื่นๆ ที่น่าสนใจ
					</h2>
					<a href="<?php echo esc_url( home_url( '/projects/' ) ); ?>" style="font-size:0.875rem;font-weight:700;color:#FF5500;text-decoration:none;">
						ดูโครงการทั้งหมด →
					</a>
				</div>

				<div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(280px, 1fr));gap:1.5rem;">
					<?php while ( $related_projects_query->have_posts() ) : $related_projects_query->the_post(); 
						$r_pid   = get_the_ID();
						$r_pdata = ligermedia_get_project_gallery_data( $r_pid );
						$r_loc   = get_post_meta( $r_pid, '_liger_project_location', true ) ?: 'กรุงเทพฯ';
					?>
						<article class="nike-project-card">
							<div class="nike-project-media">
								<a href="<?php the_permalink(); ?>" class="nike-project-media-link">
									<img src="<?php echo esc_url( $r_pdata['cover'] ); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy" />
								</a>
							</div>
							<div class="nike-project-body">
								<div class="nike-project-eyebrow">
									<?php echo esc_html( $r_pdata['category']['short_name'] ); ?>
								</div>
								<h3 class="nike-project-title">
									<a href="<?php the_permalink(); ?>">
										<?php the_title(); ?>
									</a>
								</h3>
								<div class="nike-project-meta-line">
									<span><?php echo esc_html( $r_loc ); ?></span>
								</div>
							</div>
						</article>
					<?php endwhile; wp_reset_postdata(); ?>
				</div>

			</div>
		</section>
	<?php endif; ?>

</div>
