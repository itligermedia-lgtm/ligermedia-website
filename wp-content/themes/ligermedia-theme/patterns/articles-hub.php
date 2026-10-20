<?php
/**
 * Title: Articles Hub (Nike-Style Two-Column Archive)
 * Slug: ligermedia/articles-hub
 * Categories: featured, text, query
 * Description: Nike-style two-column knowledge hub with sticky subnav, collapsible sidebar filters, instant category switching, real-time search, and 3-column responsive card grid
 */

// Fetch all published articles
$articles_query = new WP_Query( array(
	'post_type'              => 'post',
	'posts_per_page'         => -1,
	'orderby'                => 'date',
	'order'                  => 'DESC',
	'post_status'            => 'publish',
	'no_found_rows'          => true,
	'update_post_term_cache' => true,
	'update_post_meta_cache' => false,
) );

$fallback_images = array(
	'hero-slide-vinyl.webp',
	'card-clpl-wood.webp',
	'cat-architectural-acp.webp',
	'project-signage.webp',
	'cat-signage-acrylic.webp',
	'hero-facade.webp',
	'sample-plastwood-cnc-carved.webp',
	'sample-mactac-9800-carwrap.webp',
	'sample-smartjet-1000d-billboard.webp',
	'cat-print-vinyl.webp',
);

// Helper function to resolve normalized category group
function liger_get_article_group( $post_id ) {
	$cats = wp_get_post_categories( $post_id, array( 'fields' => 'all' ) );
	foreach ( $cats as $c ) {
		$slug = $c->slug;
		$name = $c->name;
		if ( 'interior' === $slug || false !== strpos( $name, 'ตกแต่งภายใน' ) || false !== strpos( $name, 'ฟิล์ม' ) ) {
			return array(
				'slug' => 'interior',
				'name' => 'ตกแต่งภายใน & ฟิล์มสถาปัตยกรรม',
			);
		}
		if ( 'acp' === $slug || false !== strpos( $name, 'สถาปัตยกรรม' ) || false !== strpos( $name, 'ฟาซาด' ) ) {
			return array(
				'slug' => 'acp',
				'name' => 'สถาปัตยกรรม & ฟาซาดอาคาร',
			);
		}
		if ( 'led' === $slug || 'pvc-sheet' === $slug || false !== strpos( $name, 'ป้าย' ) || false !== strpos( $name, 'LED' ) ) {
			$title = get_the_title( $post_id );
			if ( false !== strpos( $title, 'ไวนิล' ) || false !== strpos( $title, 'Car Wrap' ) ) {
				return array(
					'slug' => 'print-media',
					'name' => 'มีเดียงานพิมพ์ & โฆษณา',
				);
			}
			return array(
				'slug' => 'signage-led',
				'name' => 'นวัตกรรมงานป้าย & LED',
			);
		}
		if ( false !== strpos( $name, 'พิมพ์' ) || false !== strpos( $name, 'มีเดีย' ) ) {
			return array(
				'slug' => 'print-media',
				'name' => 'มีเดียงานพิมพ์ & โฆษณา',
			);
		}
	}
	return array(
		'slug' => 'acp',
		'name' => 'สาระวิศวกรรมวัสดุ',
	);
}

// Calculate post counts per category group
$group_counts = array(
	'all'         => 0,
	'acp'         => 0,
	'interior'    => 0,
	'signage-led' => 0,
	'print-media' => 0,
);

if ( $articles_query->have_posts() ) {
	foreach ( $articles_query->posts as $p ) {
		$group_counts['all']++;
		$grp = liger_get_article_group( $p->ID );
		if ( isset( $group_counts[ $grp['slug'] ] ) ) {
			$group_counts[ $grp['slug'] ]++;
		}
	}
}
?>

<!-- ========================================================
     1. NIKE-STYLE ARCHIVE HEADER BANNER (Full Bleed Dark Banner)
     ======================================================== -->
<div class="wp-block-group alignfull nike-archive-header" style="margin-top:0!important;margin-bottom:0!important;">
	<div class="alignwide nike-archive-header-inner">
		<h1 class="nike-archive-title">
			คลังความรู้ &amp; สาระวิศวกรรมวัสดุ<span style="color:#FF5500;">.</span>
		</h1>
		<div class="nike-archive-desc">
			<p>ศูนย์รวมบทความเทคนิค คู่มือการระบุสเปกในแบบก่อสร้าง (BOQ) มาตรฐานการกันไฟสากล และเทคนิคการติดตั้งสำหรับสถาปนิก ผู้รับเหมา และช่างทำป้ายมืออาชีพ</p>
		</div>
	</div>
</div>

<!-- ========================================================
     2. NIKE SUB-HEADER & FILTER CONTROL BAR (Sticky)
     ======================================================== -->
<div class="nike-subnav-bar" id="nike-articles-subnav-bar" style="margin-top:0!important;">
	<div class="alignwide nike-subnav-inner">
		<div class="nike-subnav-left">
			<span class="nike-subnav-breadcrumb">
				บทความทั้งหมด (<span id="nike-articles-live-count"><?php echo esc_html( $group_counts['all'] ); ?></span> บทความ)
			</span>
		</div>
		<div class="nike-subnav-right">
			<!-- Desktop Hide/Show Filter Button -->
			<button type="button" class="nike-filter-toggle-btn" id="nike-articles-filter-toggle" aria-expanded="true">
				<span class="nike-filter-btn-text">ซ่อนตัวกรอง</span>
				<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="nike-filter-icon"><line x1="4" y1="21" x2="4" y2="14"></line><line x1="4" y1="10" x2="4" y2="3"></line><line x1="12" y1="21" x2="12" y2="12"></line><line x1="12" y1="8" x2="12" y2="3"></line><line x1="20" y1="21" x2="20" y2="16"></line><line x1="20" y1="12" x2="20" y2="3"></line><line x1="1" y1="14" x2="7" y2="14"></line><line x1="9" y1="8" x2="15" y2="8"></line><line x1="17" y1="16" x2="23" y2="16"></line></svg>
			</button>

			<!-- Mobile Filter Button -->
			<button type="button" class="nike-mobile-filter-btn" id="nike-articles-mobile-filter-btn">
				<span>ตัวกรอง &amp; หมวดหมู่</span>
				<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 6h16M7 12h10m-8 6h6"/></svg>
			</button>
		</div>
	</div>
</div>

<!-- ========================================================
     3. MAIN NIKE TWO-COLUMN LAYOUT (Sidebar + Articles Grid)
     ======================================================== -->
<div class="wp-block-group alignwide nike-shop-wrapper" id="nike-articles-layout">
	
	<!-- LEFT SIDEBAR: Nike-Style Material Filters & Real-Time Search -->
	<aside class="nike-shop-sidebar" id="nike-articles-sidebar">
		<div class="nike-sidebar-inner">
			
			<!-- Mobile Close Button -->
			<div class="nike-sidebar-mobile-header">
				<span style="font-weight:700;font-size:1.1rem;color:#111;">ตัวกรองบทความ</span>
				<button type="button" class="nike-sidebar-close" id="nike-articles-sidebar-close-btn" aria-label="Close filters">✕</button>
			</div>

			<!-- Section 1: Core Material Categories -->
			<div class="nike-filter-group">
				<button type="button" class="nike-filter-group-header" aria-expanded="true">
					<span class="nike-filter-title">หมวดหมู่วัสดุ &amp; วิศวกรรม</span>
					<svg class="nike-filter-chevron" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
				</button>
				<div class="nike-filter-group-content">
					<ul class="nike-cat-nav-list" id="nike-article-cat-nav">
						<li>
							<button type="button" class="nike-cat-btn is-active" data-cat="all">
								<span>ทั้งหมด (All Topics)</span>
								<span class="nike-cat-count"><?php echo esc_html( $group_counts['all'] ); ?></span>
							</button>
						</li>
						<li>
							<button type="button" class="nike-cat-btn" data-cat="acp">
								<span>สถาปัตยกรรม &amp; ฟาซาดอาคาร</span>
								<span class="nike-cat-count"><?php echo esc_html( $group_counts['acp'] ); ?></span>
							</button>
						</li>
						<li>
							<button type="button" class="nike-cat-btn" data-cat="interior">
								<span>ตกแต่งภายใน &amp; ฟิล์มสถาปัตยกรรม</span>
								<span class="nike-cat-count"><?php echo esc_html( $group_counts['interior'] ); ?></span>
							</button>
						</li>
						<li>
							<button type="button" class="nike-cat-btn" data-cat="signage-led">
								<span>นวัตกรรมงานป้าย &amp; LED</span>
								<span class="nike-cat-count"><?php echo esc_html( $group_counts['signage-led'] ); ?></span>
							</button>
						</li>
						<li>
							<button type="button" class="nike-cat-btn" data-cat="print-media">
								<span>มีเดียงานพิมพ์ &amp; โฆษณา</span>
								<span class="nike-cat-count"><?php echo esc_html( $group_counts['print-media'] ); ?></span>
							</button>
						</li>
					</ul>
				</div>
			</div>

			<!-- Section 2: Popular Engineering Topics / Quick Tags -->
			<div class="nike-filter-group">
				<button type="button" class="nike-filter-group-header" aria-expanded="true">
					<span class="nike-filter-title">หัวข้อยอดนิยม (Quick Tags)</span>
					<svg class="nike-filter-chevron" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
				</button>
				<div class="nike-filter-group-content">
					<div class="nike-article-tags-wrap" id="nike-article-tags">
						<button type="button" class="nike-article-tag-btn" data-tag="กันไฟ">#มาตรฐานกันไฟ A2</button>
						<button type="button" class="nike-article-tag-btn" data-tag="เซาะร่อง">#การเซาะร่องพับฉาก</button>
						<button type="button" class="nike-article-tag-btn" data-tag="CLPL">#ฟิล์มลายไม้ CLPL</button>
						<button type="button" class="nike-article-tag-btn" data-tag="LED">#โมดูลไฟ LED 160°</button>
						<button type="button" class="nike-article-tag-btn" data-tag="พลาสวูด">#แผ่นพลาสวูด CNC</button>
						<button type="button" class="nike-article-tag-btn" data-tag="ไวนิล">#ผ้าไวนิลตู้ไฟ</button>
						<button type="button" class="nike-article-tag-btn" data-tag="Car Wrap">#ฟิล์ม Car Wrap</button>
						<button type="button" class="nike-article-tag-btn" data-tag="โครงเคร่า">#โครงสร้างรับแรงลม</button>
					</div>
				</div>
			</div>

			<!-- Section 3: Material Sample Box CTA -->
			<div class="nike-sidebar-sample-box" style="margin-top:0.5rem;">
				<div class="nike-sample-text">ต้องการชิ้นงานตัวอย่างจริงเพื่อตรวจสเปกโครงการ?</div>
				<button type="button" class="nike-sidebar-sample-btn" data-open-quote-modal="true" data-product-name="ขอรับชุดตัวอย่างวัสดุ LIGER MEDIA (Material Sample Box)">
					ขอรับตัวอย่างฟรี
				</button>
			</div>

		</div>
	</aside>

	<!-- Mobile Backdrop Overlay -->
	<div class="nike-sidebar-backdrop" id="nike-articles-sidebar-backdrop"></div>

	<!-- RIGHT MAIN ARTICLES GRID -->
	<main class="nike-shop-main" id="nike-articles-main">
		
		<div class="nike-articles-grid" id="nike-articles-grid-container">
			<?php
			if ( $articles_query->have_posts() ) :
				$card_idx = 0;
				while ( $articles_query->have_posts() ) :
					$articles_query->the_post();
					$post_id  = get_the_ID();
					$grp      = liger_get_article_group( $post_id );
					$excerpt  = get_the_excerpt();
					if ( empty( $excerpt ) || false !== strpos( $excerpt, '???' ) ) {
						$excerpt = wp_trim_words( get_the_content(), 18, '...' );
					}
					$card_idx++;
					$extra_attrs = array(
						'data-category' => $grp['slug'],
						'data-title'    => mb_strtolower( get_the_title() ),
						'data-excerpt'  => mb_strtolower( $excerpt ),
						'data-reveal'   => 'up',
					);
					echo ligermedia_render_article_card( $post_id, $card_idx, 'liger-hub-card', $extra_attrs );
				endwhile;
				wp_reset_postdata();
			endif;
			?>
		</div>

		<!-- Clean Empty Search/Filter State -->
		<div class="nike-query-empty" id="nike-articles-empty-state" style="display:none;background:#FFFFFF;border:1px dashed #E2E8F0;border-radius:18px;padding:3.5rem 2rem;text-align:center;">
			<div style="font-size:2.5rem;margin-bottom:1rem;">🔍</div>
			<h3 style="font-size:1.35rem;font-weight:800;color:#0F172A;margin:0 0 0.5rem 0;">ไม่พบบทความที่ตรงกับเงื่อนไข</h3>
			<p style="color:#64748B;font-size:0.95rem;max-width:440px;margin:0 auto 1.5rem auto;line-height:1.6;">
				ลองเปลี่ยนคำค้นหา หรือเลือกหมวดหมู่วัสดุอื่น เพื่อดูข้อมูลและคู่มือทางวิศวกรรม
			</p>
			<button type="button" id="nike-articles-reset-all-btn" class="nike-filter-toggle-btn" style="background:#0F172A;color:#FFFFFF;border-color:#0F172A;padding:0.6rem 1.4rem;font-size:0.9rem;">
				แสดงบทความทั้งหมด
			</button>
		</div>

	</main>

</div>
