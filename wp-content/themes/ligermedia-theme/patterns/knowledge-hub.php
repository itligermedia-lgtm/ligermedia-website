<?php
/**
 * Title: Knowledge Hub & Engineering Insights
 * Slug: ligermedia/knowledge-hub
 * Categories: featured, text, query
 * Description: Clean Nike/Apple-inspired Knowledge Hub section displaying the 10 latest engineering articles in an interactive carousel with scrollbar
 */

$articles_title    = function_exists( 'ligermedia_get_homepage_option' ) ? ligermedia_get_homepage_option( 'articles_title', 'คลังความรู้ & สาระวิศวกรรมวัสดุ' ) : 'คลังความรู้ & สาระวิศวกรรมวัสดุ';
$articles_subtitle = function_exists( 'ligermedia_get_homepage_option' ) ? ligermedia_get_homepage_option( 'articles_subtitle', 'คู่มือการระบุสเปก มาตรฐานการกันไฟ และเทคนิคการติดตั้งสำหรับสถาปนิก ผู้รับเหมา และช่างทำป้ายมืออาชีพ' ) : 'คู่มือการระบุสเปก มาตรฐานการกันไฟ และเทคนิคการติดตั้งสำหรับสถาปนิก ผู้รับเหมา และช่างทำป้ายมืออาชีพ';

$hub_articles = new WP_Query( array(
	'post_type'              => 'post',
	'posts_per_page'         => 10,
	'orderby'                => 'date',
	'order'                  => 'DESC',
	'post_status'            => 'publish',
	'no_found_rows'          => true,
	'update_post_term_cache' => true,
	'update_post_meta_cache' => false,
) );
?>
<!-- wp:group {"align":"full","style":{"color":{"background":"#FFFFFF"},"spacing":{"padding":{"top":"5rem","bottom":"5.5rem","left":"0","right":"0"}}},"layout":{"type":"constrained"}} -->
<section class="wp-block-group alignfull liger-knowledge-hub-section nike-carousel-module" id="knowledge-hub" data-carousel-id="knowledge-hub" style="background-color:#FFFFFF;padding-top:5rem;padding-bottom:5.5rem;border-bottom:1px solid #ECECEC;">
	<div class="alignwide">

		<!-- Section Header: Nike Aesthetic with Navigation Controls -->
		<div class="apple-section-header knowledge-section-header" style="margin-bottom:2.25rem;display:flex;justify-content:space-between;align-items:flex-end;flex-wrap:wrap;gap:1.5rem;">
			<div data-reveal="left">
				<h2 style="font-family:var(--wp--preset--font-family--display);font-size:clamp(1.85rem, 2.6vw, 2.4rem);font-weight:800;color:#111418;letter-spacing:-0.02em;margin:0 0 0.5rem 0;">
					<?php echo esc_html( $articles_title ); ?><span style="color:#FF5500;">.</span>
				</h2>
				<p style="color:#6E6E73;font-size:1rem;margin:0;max-width:640px;line-height:1.6;">
					<?php echo esc_html( $articles_subtitle ); ?>
				</p>
			</div>
			<div class="nike-carousel-nav-group" data-reveal="right">
				<a href="/ligermedia/articles/" class="apple-see-all-link">
					<span>ดูบทความและคู่มือทั้งหมด</span>
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

		<!-- 10 Latest Articles Carousel Track (Latest to Oldest) -->
		<div class="nike-carousel-track-wrapper">
			<div class="liger-articles-grid nike-carousel-track">
				<?php
				if ( $hub_articles->have_posts() ) :
					$card_idx = 0;
					while ( $hub_articles->have_posts() ) :
						$hub_articles->the_post();
						$card_idx++;
						echo ligermedia_render_article_card( get_the_ID(), $card_idx, 'nike-carousel-slide' );
					endwhile;
					wp_reset_postdata();
				endif;
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
<!-- /wp:group -->
