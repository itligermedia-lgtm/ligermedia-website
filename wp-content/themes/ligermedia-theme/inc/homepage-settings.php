<?php
/**
 * Homepage Content Settings for LIGER MEDIA
 * Allows Administrators to edit homepage texts, hero slider slides, and section headings directly from WP Admin.
 *
 * @package LigerMedia
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register Homepage Settings Menu in WP Admin
 */
function ligermedia_register_homepage_settings_menu() {
	add_menu_page(
		'ตั้งค่าเนื้อหาหน้าแรก',
		'ตั้งค่าหน้าแรก',
		'manage_options',
		'ligermedia-homepage-settings',
		'ligermedia_render_homepage_settings_page',
		'dashicons-admin-home',
		25
	);
}
add_action( 'admin_menu', 'ligermedia_register_homepage_settings_menu' );

/**
 * Register Setting
 */
function ligermedia_register_homepage_settings() {
	register_setting( 'ligermedia_homepage_group', 'ligermedia_homepage_options', 'ligermedia_sanitize_homepage_options' );
}
add_action( 'admin_init', 'ligermedia_register_homepage_settings' );

/**
 * Sanitize Settings
 */
function ligermedia_sanitize_homepage_options( $input ) {
	$output = array();
	if ( ! is_array( $input ) ) {
		return $output;
	}

	// Section Titles
	$output['featured_title']    = sanitize_text_field( $input['featured_title'] ?? '' );
	$output['featured_subtitle'] = sanitize_text_field( $input['featured_subtitle'] ?? '' );
	$output['articles_title']    = sanitize_text_field( $input['articles_title'] ?? '' );
	$output['articles_subtitle'] = sanitize_text_field( $input['articles_subtitle'] ?? '' );

	// Slides (1 to 5)
	for ( $i = 1; $i <= 5; $i++ ) {
		$output[ "slide_{$i}_title" ] = sanitize_text_field( $input[ "slide_{$i}_title" ] ?? '' );
		$output[ "slide_{$i}_sub" ]   = sanitize_text_field( $input[ "slide_{$i}_sub" ] ?? '' );
		$output[ "slide_{$i}_link" ]  = esc_url_raw( $input[ "slide_{$i}_link" ] ?? '' );
		$output[ "slide_{$i}_img" ]   = esc_url_raw( $input[ "slide_{$i}_img" ] ?? '' );
	}

	return $output;
}

/**
 * Helper to get homepage option with fallback
 */
function ligermedia_get_homepage_option( $key, $fallback = '' ) {
	$options = get_option( 'ligermedia_homepage_options', array() );
	if ( ! empty( $options[ $key ] ) ) {
		return $options[ $key ];
	}
	return $fallback;
}

/**
 * Render Homepage Settings Page
 */
function ligermedia_render_homepage_settings_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	?>
	<div class="wrap" style="max-width:960px;">
		<h1 style="display:flex;align-items:center;gap:10px;margin-bottom:1.5rem;">
			<span class="dashicons dashicons-admin-home" style="font-size:30px;width:30px;height:30px;color:#FF5500;"></span>
			<span>จัดการเนื้อหาหน้าแรก (Homepage Content Settings)</span>
		</h1>

		<?php settings_errors(); ?>

		<form method="post" action="options.php" style="background:#FFF;padding:24px 30px;border-radius:12px;border:1px solid #D1D5DB;box-shadow:0 4px 12px rgba(0,0,0,0.03);">
			<?php
			settings_fields( 'ligermedia_homepage_group' );
			$opts = get_option( 'ligermedia_homepage_options', array() );
			?>

			<!-- Tab 1: Section Headings -->
			<h2 style="font-size:1.2rem;font-weight:700;color:#111827;border-bottom:2px solid #FF5500;padding-bottom:8px;margin-bottom:18px;">
				1. หัวข้อและคำอธิบายส่วนต่างๆ บนหน้าแรก
			</h2>

			<table class="form-table" role="presentation" style="margin-bottom:24px;">
				<tr>
					<th scope="row"><label for="featured_title">หัวข้อส่วน "สินค้าเด่น":</label></th>
					<td>
						<input type="text" id="featured_title" name="ligermedia_homepage_options[featured_title]" value="<?php echo esc_attr( $opts['featured_title'] ?? 'สินค้าเด่นประจำซีซั่น' ); ?>" class="regular-text" style="width:100%;max-width:480px;" />
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="featured_subtitle">คำอธิบายส่วน "สินค้าเด่น":</label></th>
					<td>
						<input type="text" id="featured_subtitle" name="ligermedia_homepage_options[featured_subtitle]" value="<?php echo esc_attr( $opts['featured_subtitle'] ?? 'วัสดุวิศวกรรมสถาปัตยกรรมและงานป้ายยอดนิยมมาตรฐานสากล ที่สถาปนิกและโครงการชั้นนำเลือกใช้' ); ?>" class="large-text" />
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="articles_title">หัวข้อส่วน "คลังความรู้":</label></th>
					<td>
						<input type="text" id="articles_title" name="ligermedia_homepage_options[articles_title]" value="<?php echo esc_attr( $opts['articles_title'] ?? 'คลังความรู้ & สาระวิศวกรรมวัสดุ' ); ?>" class="regular-text" style="width:100%;max-width:480px;" />
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="articles_subtitle">คำอธิบายส่วน "คลังความรู้":</label></th>
					<td>
						<input type="text" id="articles_subtitle" name="ligermedia_homepage_options[articles_subtitle]" value="<?php echo esc_attr( $opts['articles_subtitle'] ?? 'คู่มือการระบุสเปก มาตรฐานการกันไฟ และเทคนิคการติดตั้งสำหรับสถาปนิก ผู้รับเหมา และช่างทำป้ายมืออาชีพ' ); ?>" class="large-text" />
					</td>
				</tr>
			</table>

			<!-- Tab 2: Hero Slider Slides -->
			<h2 style="font-size:1.2rem;font-weight:700;color:#111827;border-bottom:2px solid #FF5500;padding-bottom:8px;margin-bottom:18px;">
				2. แบนเนอร์สไลด์หน้าแรก (Hero Slider 5 สไลด์)
			</h2>

			<?php
			$default_slides = array(
				1 => array( 'title' => 'SKY-HIGH ACP', 'sub' => 'นวัตกรรมแผ่นคอมโพสิตกันไฟ สำหรับฟาซาดและงานสถาปัตยกรรมอาคารสูง', 'link' => '/ligermedia/product-category/acp/' ),
				2 => array( 'title' => 'SUPREMO CLPL', 'sub' => 'ฟิล์มสถาปัตยกรรมมิติใหม่ สัมผัสเสี้ยนไม้ธรรมชาติ 3D กันน้ำ 100% ไร้รอยต่อ', 'link' => '/ligermedia/product-category/interior/' ),
				3 => array( 'title' => 'SUPREME ACRYLIC', 'sub' => 'แผ่นอะคริลิกหล่อใสพิเศษและพลาสวูดเกรดความหนาแน่นสูง มาตรฐานงานป้ายระดับลักชัวรี', 'link' => '/ligermedia/product-category/pvc-sheet/' ),
				4 => array( 'title' => 'LIPONIX LED', 'sub' => 'โมดูลไฟเลนส์มุมกว้าง 160° แสงสว่างสม่ำเสมอ ประหยัดเม็ดไฟ 40% กันน้ำ IP68', 'link' => '/ligermedia/product-category/led/' ),
				5 => array( 'title' => 'MACTAC & AVERY', 'sub' => 'สติกเกอร์เกรดยานยนต์และงานป้ายโฆษณาระดับโลก ทนทานสภาพอากาศเมืองไทย 7 ปี+', 'link' => '/ligermedia/product-category/sticker/' ),
			);

			for ( $i = 1; $i <= 5; $i++ ) :
				$s_title = $opts[ "slide_{$i}_title" ] ?? $default_slides[ $i ]['title'];
				$s_sub   = $opts[ "slide_{$i}_sub" ] ?? $default_slides[ $i ]['sub'];
				$s_link  = $opts[ "slide_{$i}_link" ] ?? $default_slides[ $i ]['link'];
				$s_img   = $opts[ "slide_{$i}_img" ] ?? '';
			?>
				<div style="background:#F9FAFB;padding:16px 20px;border-radius:8px;border:1px solid #E5E7EB;margin-bottom:16px;">
					<h3 style="margin:0 0 12px 0;font-size:1.05rem;color:#FF5500;font-weight:700;">
						สไลด์ที่ <?php echo $i; ?>:
					</h3>
					<div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:10px;">
						<div>
							<label style="display:block;font-weight:600;margin-bottom:4px;">หัวข้อใหญ่ (Slide Title):</label>
							<input type="text" name="ligermedia_homepage_options[slide_<?php echo $i; ?>_title]" value="<?php echo esc_attr( $s_title ); ?>" style="width:100%;padding:6px 10px;border-radius:4px;border:1px solid #c3c4c7;" />
						</div>
						<div>
							<label style="display:block;font-weight:600;margin-bottom:4px;">ลิงก์ปลายทาง (Link URL):</label>
							<input type="text" name="ligermedia_homepage_options[slide_<?php echo $i; ?>_link]" value="<?php echo esc_attr( $s_link ); ?>" style="width:100%;padding:6px 10px;border-radius:4px;border:1px solid #c3c4c7;" />
						</div>
					</div>
					<div style="margin-bottom:10px;">
						<label style="display:block;font-weight:600;margin-bottom:4px;">ข้อความบรรยาย (Subtitle):</label>
						<input type="text" name="ligermedia_homepage_options[slide_<?php echo $i; ?>_sub]" value="<?php echo esc_attr( $s_sub ); ?>" style="width:100%;padding:6px 10px;border-radius:4px;border:1px solid #c3c4c7;" />
					</div>
					<div>
						<label style="display:block;font-weight:600;margin-bottom:4px;">รูปภาพพื้นหลัง (Background Image URL):</label>
						<div style="display:flex;gap:8px;">
							<input type="text" id="slide_<?php echo $i; ?>_img" name="ligermedia_homepage_options[slide_<?php echo $i; ?>_img]" value="<?php echo esc_attr( $s_img ); ?>" placeholder="เว้นว่างไว้เพื่อใช้รูปภาพมาตรฐาน หรือคลิกเลือกรูปภาพใหม่" style="flex:1;padding:6px 10px;border-radius:4px;border:1px solid #c3c4c7;" />
							<button type="button" class="button button-secondary liger-slide-upload-btn" data-target="#slide_<?php echo $i; ?>_img">เลือกรูปภาพ</button>
						</div>
					</div>
				</div>
			<?php endfor; ?>

			<?php submit_button( 'บันทึกการเปลี่ยนแปลงทั้งหมด', 'primary', 'submit', true, array( 'style' => 'background:#FF5500;border-color:#E04B00;font-size:1rem;padding:6px 24px;' ) ); ?>
		</form>
	</div>

	<script>
	jQuery(document).ready(function($) {
		$('.liger-slide-upload-btn').on('click', function(e) {
			e.preventDefault();
			var targetInput = $(this).data('target');
			var frame = wp.media({
				title: 'เลือกรูปภาพสไลด์หน้าแรก',
				button: { text: 'ใช้รูปภาพนี้' },
				multiple: false,
				library: { type: 'image' }
			});
			frame.on('select', function() {
				var attachment = frame.state().get('selection').first().toJSON();
				$(targetInput).val(attachment.url);
			});
			frame.open();
		});
	});
	</script>
	<?php
}
