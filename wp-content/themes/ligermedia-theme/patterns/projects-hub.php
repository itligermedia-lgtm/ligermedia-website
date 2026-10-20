<?php
/**
 * Title: Projects Hub (Modern Nike-Style Portfolio Grid)
 * Slug: ligermedia/projects-hub
 * Categories: featured, query, gallery
 * Description: Cutting-edge modern architectural projects hub with instant category switching, real-time search, photo counts, and responsive cards.
 */

// Fetch all published projects
$projects_query = new WP_Query( array(
	'post_type'              => 'project',
	'posts_per_page'         => -1,
	'orderby'                => 'date',
	'order'                  => 'DESC',
	'post_status'            => 'publish',
	'no_found_rows'          => true,
	'update_post_term_cache' => false,
	'update_post_meta_cache' => true,
) );

// Pre-calculate counts per category
$category_counts = array(
	'all'               => 0,
	'acp'               => 0,
	'plastwood-acrylic' => 0,
	'signage-led'       => 0,
	'wrap-vinyl'        => 0,
);

$projects_list = array();

if ( $projects_query->have_posts() ) {
	while ( $projects_query->have_posts() ) {
		$projects_query->the_post();
		$pid   = get_the_ID();
		$post  = get_post( $pid );
		$pdata = ligermedia_get_project_gallery_data( $pid );
		$cat   = $pdata['category'];

		$category_counts['all']++;
		if ( isset( $category_counts[ $cat['slug'] ] ) ) {
			$category_counts[ $cat['slug'] ]++;
		}

		$location  = get_post_meta( $pid, '_liger_project_location', true ) ?: 'กรุงเทพฯ และปริมณฑล';
		$materials = get_post_meta( $pid, '_liger_project_materials', true ) ?: $cat['short_name'];
		$year      = get_post_meta( $pid, '_liger_project_year', true ) ?: '2025';
		$area      = get_post_meta( $pid, '_liger_project_area_sqm', true ) ?: '1,500 ตร.ม.';

		$projects_list[] = array(
			'id'          => $pid,
			'title'       => get_the_title(),
			'url'         => get_permalink(),
			'category'    => $cat,
			'cover'       => $pdata['cover'],
			'photo_count' => $pdata['photo_count'],
			'location'    => $location,
			'materials'   => $materials,
			'year'        => $year,
			'area'        => $area,
		);
	}
	wp_reset_postdata();
}
?>

<!-- ========================================================
     1. ARCHITECTURAL HERO BANNER (Full Bleed Dark Theme)
     ======================================================== -->
<section class="nike-projects-hero nike-archive-header alignfull" style="background-image:linear-gradient(180deg, rgba(11, 14, 20, 0.72) 0%, rgba(11, 14, 20, 0.90) 100%), url('/ligermedia/wp-content/themes/ligermedia-theme/assets/images/project-facade.webp') !important;">
	<div class="nike-container alignwide nike-archive-header-inner">
		<h1 class="nike-archive-title">
			โครงการและผลงานติดตั้งจริง<span style="color:#FF5500;">.</span>
		</h1>
		<div class="nike-archive-desc">
			<p>รวมผลงานมาตรฐานสากลจากสถาปนิก ผู้รับเหมา และแบรนด์ชั้นนำทั่วไทย ที่เลือกใช้วัสดุแผ่นคอมโพสิต พลาสวูด อะคริลิค ไฟ LED และมีเดียงานพิมพ์จาก LIGER MEDIA</p>
		</div>

		<!-- Trust Badges Strip -->
		<div style="display:flex;flex-wrap:wrap;gap:1.5rem;font-size:0.875rem;color:#A0A6B1;margin-top:1.25rem;">
			<div style="display:inline-flex;align-items:center;gap:6px;">
				<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#FF5500" stroke-width="2.2"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
				<span>กว่า <strong style="color:#FFF;">500+</strong> โครงการสำเร็จทั่วประเทศ</span>
			</div>
			<div style="display:inline-flex;align-items:center;gap:6px;">
				<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#FF5500" stroke-width="2.2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
				<span>รับประกันวัสดุนานสูงสุด <strong style="color:#FFF;">15 ปี</strong></span>
			</div>
			<div style="display:inline-flex;align-items:center;gap:6px;">
				<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#FF5500" stroke-width="2.2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
				<span>มาตรฐาน มอก. / ASTM / EN Fireproof</span>
			</div>
		</div>
	</div>
</section>

<!-- ========================================================
     2. NIKE SUB-HEADER & FILTER CONTROL BAR (Sticky)
     ======================================================== -->
<div class="nike-subnav-bar" id="nike-projects-subnav-bar" style="margin-top:0!important;">
	<div class="alignwide nike-subnav-inner">
		<div class="nike-subnav-left">
			<span class="nike-subnav-breadcrumb">
				ผลงานทั้งหมด (<span id="nikeProjectsVisibleCount"><?php echo esc_html( count( $projects_list ) ); ?></span> โครงการ)
			</span>
		</div>
		<div class="nike-subnav-right">
			<!-- Desktop Hide/Show Filter Button -->
			<button type="button" class="nike-filter-toggle-btn" id="nike-projects-filter-toggle" aria-expanded="true">
				<span class="nike-filter-btn-text">ซ่อนตัวกรอง</span>
				<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="nike-filter-icon"><line x1="4" y1="21" x2="4" y2="14"></line><line x1="4" y1="10" x2="4" y2="3"></line><line x1="12" y1="21" x2="12" y2="12"></line><line x1="12" y1="8" x2="12" y2="3"></line><line x1="20" y1="21" x2="20" y2="16"></line><line x1="20" y1="12" x2="20" y2="3"></line><line x1="1" y1="14" x2="7" y2="14"></line><line x1="9" y1="8" x2="15" y2="8"></line><line x1="17" y1="16" x2="23" y2="16"></line></svg>
			</button>

			<!-- Mobile Filter Button -->
			<button type="button" class="nike-mobile-filter-btn" id="nike-projects-mobile-filter-btn">
				<span>ตัวกรอง &amp; หมวดหมู่</span>
				<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 6h16M7 12h10m-8 6h6"/></svg>
			</button>
		</div>
	</div>
</div>

<!-- ========================================================
     3. MAIN NIKE TWO-COLUMN LAYOUT (Sidebar + Projects Grid)
     ======================================================== -->
<div class="wp-block-group alignwide nike-shop-wrapper" id="nike-projects-layout">
	
	<!-- LEFT SIDEBAR: Nike-Style Material Filters & Real-Time Search -->
	<aside class="nike-shop-sidebar" id="nike-projects-sidebar">
		<div class="nike-sidebar-inner">
			
			<!-- Mobile Close Button -->
			<div class="nike-sidebar-mobile-header">
				<span style="font-weight:700;font-size:1.1rem;color:#111;">ตัวกรองผลงาน</span>
				<button type="button" class="nike-sidebar-close" id="nike-projects-sidebar-close-btn" aria-label="Close filters">✕</button>
			</div>

			<!-- Section 1: Core Material Categories -->
			<div class="nike-filter-group">
				<button type="button" class="nike-filter-group-header" aria-expanded="true">
					<span class="nike-filter-title">หมวดหมู่วัสดุที่ใช้</span>
					<svg class="nike-filter-chevron" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
				</button>
				<div class="nike-filter-group-content">
					<ul class="nike-cat-nav-list" id="nike-projects-cat-nav">
						<li>
							<button type="button" class="nike-cat-btn is-active" data-cat="all">
								<span>ทั้งหมด (All Projects)</span>
								<span class="nike-cat-count"><?php echo esc_html( $category_counts['all'] ); ?></span>
							</button>
						</li>
						<li>
							<button type="button" class="nike-cat-btn" data-cat="acp">
								<span>แผ่นอลูมิเนียมคอมโพสิต (ACP)</span>
								<span class="nike-cat-count"><?php echo esc_html( $category_counts['acp'] ); ?></span>
							</button>
						</li>
						<li>
							<button type="button" class="nike-cat-btn" data-cat="plastwood-acrylic">
								<span>พลาสวูด &amp; แผ่นอะคริลิก</span>
								<span class="nike-cat-count"><?php echo esc_html( $category_counts['plastwood-acrylic'] ); ?></span>
							</button>
						</li>
						<li>
							<button type="button" class="nike-cat-btn" data-cat="signage-led">
								<span>ป้ายทาวเวอร์ &amp; โมดูล LED</span>
								<span class="nike-cat-count"><?php echo esc_html( $category_counts['signage-led'] ); ?></span>
							</button>
						</li>
						<li>
							<button type="button" class="nike-cat-btn" data-cat="wrap-vinyl">
								<span>สติกเกอร์ &amp; งานพิมพ์ไวนิล</span>
								<span class="nike-cat-count"><?php echo esc_html( $category_counts['wrap-vinyl'] ); ?></span>
							</button>
						</li>
					</ul>
				</div>
			</div>

			<!-- Section 2: Building & Project Types -->
			<div class="nike-filter-group">
				<button type="button" class="nike-filter-group-header" aria-expanded="true">
					<span class="nike-filter-title">ประเภทงานก่อสร้าง</span>
					<svg class="nike-filter-chevron" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
				</button>
				<div class="nike-filter-group-content">
					<div class="nike-filter-options">
						<label class="nike-checkbox-label">
							<input type="checkbox" checked disabled />
							<span class="nike-custom-check"></span>
							<span class="nike-check-text">งานฟาซาดอาคาร &amp; รั้วเมทัลชีท</span>
						</label>
						<label class="nike-checkbox-label">
							<input type="checkbox" checked disabled />
							<span class="nike-custom-check"></span>
							<span class="nike-check-text">ป้ายสถานีบริการน้ำมัน &amp; โชว์รูม</span>
						</label>
						<label class="nike-checkbox-label">
							<input type="checkbox" checked disabled />
							<span class="nike-custom-check"></span>
							<span class="nike-check-text">ตกแต่งภายในห้างสรรพสินค้า &amp; โรงแรม</span>
						</label>
						<label class="nike-checkbox-label">
							<input type="checkbox" checked disabled />
							<span class="nike-custom-check"></span>
							<span class="nike-check-text">บิลบอร์ด &amp; แร็ปขบวนยานยนต์</span>
						</label>
					</div>
				</div>
			</div>

			<!-- Direct Sample Kit Box -->
			<div class="nike-sidebar-sample-box">
				<div class="nike-sample-text">ต้องการชิ้นงานตัวอย่างจริงสำหรับนำเสนองานสถาปัตย์?</div>
				<button type="button" class="nike-sidebar-sample-btn" data-open-quote-modal="true" data-product-name="ขอรับชุดตัวอย่างวัสดุ LIGER MEDIA (Sample Box สำหรับโครงการ)">
					ขอรับตัวอย่างฟรี
				</button>
			</div>

		</div>
	</aside>

	<!-- Mobile Backdrop Overlay -->
	<div class="nike-sidebar-backdrop" id="nike-projects-sidebar-backdrop"></div>

	<!-- RIGHT CONTENT AREA -->
	<main class="nike-shop-main" id="nike-projects-main">
		
		<!-- Dynamic Cards Grid -->
		<div id="nikeProjectsGrid" class="nike-projects-grid">
			<?php foreach ( $projects_list as $p ) : ?>
				<article class="nike-project-card" data-cat="<?php echo esc_attr( $p['category']['slug'] ); ?>" data-search="<?php echo esc_attr( mb_strtolower( $p['title'] . ' ' . $p['materials'] . ' ' . $p['location'] . ' ' . $p['category']['name'] ) ); ?>">
					<!-- Media Container (100% Pure Minimal Photography - No Overlays) -->
					<div class="nike-project-media">
						<a href="<?php echo esc_url( $p['url'] ); ?>" class="nike-project-media-link" aria-label="ดูรายละเอียดโครงการ <?php echo esc_attr( $p['title'] ); ?>">
							<img src="<?php echo esc_url( $p['cover'] ); ?>" alt="<?php echo esc_attr( $p['title'] ); ?>" loading="lazy" width="600" height="375" />
						</a>
					</div>

					<!-- Body Details (Minimal Architectural Layout) -->
					<div class="nike-project-body">
						<div class="nike-project-eyebrow">
							<?php echo esc_html( $p['category']['short_name'] ); ?>
						</div>

						<h3 class="nike-project-title">
							<a href="<?php echo esc_url( $p['url'] ); ?>">
								<?php echo esc_html( $p['title'] ); ?>
							</a>
						</h3>

						<div class="nike-project-meta-line">
							<span><?php echo esc_html( $p['location'] ); ?></span>
							<span class="nike-meta-dot">•</span>
							<span>ปี <?php echo esc_html( $p['year'] ); ?></span>
						</div>

						<div class="nike-project-footer">
							<a href="<?php echo esc_url( $p['url'] ); ?>" class="nike-project-explore-link">
								<span>ดูรายละเอียดผลงาน</span>
								<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14"/><path d="M12 5l7 7-7 7"/></svg>
							</a>
						</div>
					</div>
				</article>
			<?php endforeach; ?>
		</div>

		<!-- Empty State (When no results match filter) -->
		<div id="nikeProjectsEmpty" class="nike-projects-empty" style="display:none;background:#FFFFFF;border:1px dashed #D1D5DB;border-radius:12px;padding:4rem 2rem;text-align:center;margin-top:2rem;">
			<div style="width:56px;height:56px;border-radius:50%;background:#F3F4F6;display:inline-flex;align-items:center;justify-content:center;margin-bottom:1rem;color:#6B7280;">
				<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
			</div>
			<h3 style="font-size:1.25rem;font-weight:700;color:#111827;margin:0 0 0.5rem 0;">ไม่พบผลงานที่ตรงกับคำค้นหา</h3>
			<p style="color:#6B7280;font-size:0.95rem;margin:0 0 1.5rem 0;">ลองปรับคำค้นหา หรือเลือกหมวดหมู่วัสดุอื่นเพื่อดูผลงานเพิ่มเติม</p>
			<button type="button" id="nikeResetProjectFilter" style="background:#111827;color:#FFFFFF;border:none;padding:10px 22px;border-radius:999px;font-size:0.875rem;font-weight:600;cursor:pointer;transition:background-color 0.2s ease;">
				ล้างตัวกรองทั้งหมด
			</button>
		</div>

	</main>
</div>
