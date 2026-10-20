<?php
/**
 * Custom Meta Boxes for Product Specs, Gallery, Homepage Display & Project Details
 *
 * @package LigerMedia
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue Media Scripts in Admin for Product/Project Post Types
 */
add_action( 'admin_enqueue_scripts', 'ligermedia_admin_enqueue_media' );
function ligermedia_admin_enqueue_media( $hook ) {
	global $post;
	if ( 'post.php' === $hook || 'post-new.php' === $hook ) {
		if ( isset( $post ) && in_array( $post->post_type, array( 'product', 'project' ), true ) ) {
			wp_enqueue_media();
		}
	}
}

/**
 * Register Meta Boxes
 */
function ligermedia_add_meta_boxes() {
	// 1. Product Homepage Display & Order Settings
	add_meta_box(
		'ligermedia_product_display_settings',
		'การแสดงผลและลำดับ (Display & Homepage Settings)',
		'ligermedia_render_product_display_meta_box',
		'product',
		'side',
		'high'
	);

	// 2. Product Additional Image Gallery
	add_meta_box(
		'ligermedia_product_gallery',
		'รูปภาพเพิ่มเติมของสินค้า (Product Image Gallery)',
		'ligermedia_render_product_gallery_meta_box',
		'product',
		'normal',
		'high'
	);

	// 3. Product Specifications Meta Box
	add_meta_box(
		'ligermedia_product_specs',
		'ข้อมูลจำเพาะทางวิศวกรรมของวัสดุ (Technical Specifications)',
		'ligermedia_render_product_specs_meta_box',
		'product',
		'normal',
		'high'
	);

	// 4. Project Details Meta Box
	add_meta_box(
		'ligermedia_project_details',
		'รายละเอียดโครงการติดตั้ง (Project Details)',
		'ligermedia_render_project_details_meta_box',
		'project',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'ligermedia_add_meta_boxes' );

/**
 * Render Product Display Settings (Featured on Homepage + Display Order)
 */
function ligermedia_render_product_display_meta_box( $post ) {
	wp_nonce_field( 'ligermedia_product_display_nonce_action', 'ligermedia_product_display_nonce' );

	$is_featured   = get_post_meta( $post->ID, '_liger_is_featured', true );
	$display_order = get_post_meta( $post->ID, '_liger_display_order', true );
	if ( '' === $display_order ) {
		$display_order = $post->menu_order ?: 0;
	}
	?>
	<div style="padding:4px 0;">
		<p style="margin-bottom:12px;">
			<label style="display:flex;align-items:center;gap:8px;font-weight:600;color:#111827;cursor:pointer;">
				<input type="checkbox" name="liger_is_featured" value="1" <?php checked( $is_featured, '1' ); ?> style="width:18px;height:18px;" />
				<span>แสดงในส่วนสินค้าเด่นหน้าแรก (Featured on Homepage)</span>
			</label>
			<small style="color:#6B7280;display:block;margin-top:4px;line-height:1.4;">
				หากเลือกไว้ สินค้านี้จะถูกนำไปแสดงในแถบสไลด์สินค้าเด่นบนหน้าแรก
			</small>
		</p>
		<hr style="border:none;border-top:1px solid #E5E7EB;margin:12px 0;" />
		<p style="margin-bottom:0;">
			<label for="liger_display_order" style="display:block;font-weight:600;color:#111827;margin-bottom:4px;">
				ลำดับการแสดงผล (Display Order):
			</label>
			<input type="number" id="liger_display_order" name="liger_display_order" value="<?php echo esc_attr( $display_order ); ?>" style="width:100%;padding:6px 10px;border:1px solid #c3c4c7;border-radius:4px;" min="0" step="1" />
			<small style="color:#6B7280;display:block;margin-top:4px;line-height:1.4;">
				ตัวเลขน้อยจะแสดงก่อน เช่น 1, 2, 3... (ค่าเริ่มต้นคือ 0)
			</small>
		</p>
	</div>
	<?php
}

/**
 * Render Product Image Gallery Meta Box with WP Media Library
 */
function ligermedia_render_product_gallery_meta_box( $post ) {
	wp_nonce_field( 'ligermedia_product_gallery_nonce_action', 'ligermedia_product_gallery_nonce' );

	$gallery_meta = get_post_meta( $post->ID, '_product_image_gallery', true );
	$image_ids    = array_filter( array_map( 'absint', explode( ',', $gallery_meta ) ) );
	?>
	<div id="liger-product-gallery-container">
		<input type="hidden" id="liger_product_image_gallery" name="liger_product_image_gallery" value="<?php echo esc_attr( $gallery_meta ); ?>" />
		
		<p style="color:#4B5563;font-size:0.9rem;margin-bottom:12px;">
			เลือกรูปภาพมุมมองเพิ่มเติมของสินค้า (เช่น ภาพตัดขวาง, ภาพซูมผิวสัมผัส, ภาพมุมมอง 3D) เพื่อนำไปแสดงในแถบแกลเลอรีหน้ารายละเอียดสินค้า
		</p>

		<div id="liger-gallery-thumbs" style="display:flex;flex-wrap:wrap;gap:12px;margin-bottom:14px;">
			<?php
			if ( ! empty( $image_ids ) ) :
				foreach ( $image_ids as $img_id ) :
					$thumb_src = wp_get_attachment_image_url( $img_id, 'thumbnail' );
					if ( ! $thumb_src ) continue;
					?>
					<div class="liger-gallery-thumb-item" data-id="<?php echo esc_attr( $img_id ); ?>" style="position:relative;width:90px;height:90px;border-radius:8px;border:1px solid #D1D5DB;overflow:hidden;background:#F9FAFB;">
						<img src="<?php echo esc_url( $thumb_src ); ?>" style="width:100%;height:100%;object-fit:cover;" alt="" />
						<button type="button" class="liger-remove-gallery-img" style="position:absolute;top:4px;right:4px;width:22px;height:22px;background:rgba(0,0,0,0.65);color:#FFF;border:none;border-radius:50%;cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:12px;line-height:1;" title="ลบรูปภาพนี้">✕</button>
					</div>
					<?php
				endforeach;
			endif;
			?>
		</div>

		<div>
			<button type="button" id="liger-add-gallery-btn" class="button button-secondary" style="padding:4px 14px;font-size:0.9rem;">
				+ เพิ่ม / จัดการรูปภาพเพิ่มเติมจากคลังสื่อ
			</button>
		</div>
	</div>

	<script>
	jQuery(document).ready(function($) {
		var frame;
		var $galleryInput = $('#liger_product_image_gallery');
		var $thumbsWrap = $('#liger-gallery-thumbs');

		function updateGalleryInput() {
			var ids = [];
			$thumbsWrap.find('.liger-gallery-thumb-item').each(function() {
				ids.push($(this).data('id'));
			});
			$galleryInput.val(ids.join(','));
		}

		$('#liger-add-gallery-btn').on('click', function(e) {
			e.preventDefault();

			if (frame) {
				frame.open();
				return;
			}

			frame = wp.media({
				title: 'เลือกรูปภาพเพิ่มเติมสำหรับสินค้า',
				button: { text: 'เพิ่มรูปภาพที่เลือก' },
				multiple: true,
				library: { type: 'image' }
			});

			frame.on('select', function() {
				var selection = frame.state().get('selection');
				selection.each(function(attachment) {
					var att = attachment.toJSON();
					var imgId = att.id;
					var thumbUrl = att.sizes && att.sizes.thumbnail ? att.sizes.thumbnail.url : att.url;

					if ($thumbsWrap.find('.liger-gallery-thumb-item[data-id="' + imgId + '"]').length === 0) {
						var itemHtml = '<div class="liger-gallery-thumb-item" data-id="' + imgId + '" style="position:relative;width:90px;height:90px;border-radius:8px;border:1px solid #D1D5DB;overflow:hidden;background:#F9FAFB;">' +
							'<img src="' + thumbUrl + '" style="width:100%;height:100%;object-fit:cover;" alt="" />' +
							'<button type="button" class="liger-remove-gallery-img" style="position:absolute;top:4px;right:4px;width:22px;height:22px;background:rgba(0,0,0,0.65);color:#FFF;border:none;border-radius:50%;cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:12px;line-height:1;" title="ลบรูปภาพนี้">✕</button>' +
							'</div>';
						$thumbsWrap.append(itemHtml);
					}
				});
				updateGalleryInput();
			});

			frame.open();
		});

		$thumbsWrap.on('click', '.liger-remove-gallery-img', function(e) {
			e.preventDefault();
			$(this).closest('.liger-gallery-thumb-item').remove();
			updateGalleryInput();
		});
	});
	</script>
	<?php
}

/**
 * Render Product Specs Meta Box with Thai Labels and PDF Upload
 */
function ligermedia_render_product_specs_meta_box( $post ) {
	wp_nonce_field( 'ligermedia_product_specs_nonce_action', 'ligermedia_product_specs_nonce' );

	$sku             = get_post_meta( $post->ID, '_liger_sku', true );
	$thickness       = get_post_meta( $post->ID, '_liger_thickness', true );
	$dimensions      = get_post_meta( $post->ID, '_liger_dimensions', true );
	$coating         = get_post_meta( $post->ID, '_liger_coating', true );
	$fire_rating     = get_post_meta( $post->ID, '_liger_fire_rating', true );
	$warranty        = get_post_meta( $post->ID, '_liger_warranty', true );
	$spec_pdf_url    = get_post_meta( $post->ID, '_liger_spec_pdf_url', true );
	$highlight_badge = get_post_meta( $post->ID, '_liger_highlight_badge', true );
	?>
	<style>
		.liger-meta-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 16px; margin-top: 10px; }
		.liger-meta-field label { display: block; font-weight: 600; margin-bottom: 5px; color: #161b22; }
		.liger-meta-field input { width: 100%; padding: 8px 12px; border: 1px solid #c3c4c7; border-radius: 4px; box-sizing: border-box; }
		.liger-meta-field small { color: #646970; display: block; margin-top: 4px; }
	</style>

	<div class="liger-meta-grid">
		<div class="liger-meta-field">
			<label for="liger_sku">รหัสสินค้า / รุ่น (Model / SKU):</label>
			<input type="text" id="liger_sku" name="liger_sku" value="<?php echo esc_attr( $sku ); ?>" placeholder="เช่น LM-ACP-405, LM-ACR-01" />
		</div>

		<div class="liger-meta-field">
			<label for="liger_highlight_badge">ป้ายไฮไลต์สินค้า (Badge):</label>
			<input type="text" id="liger_highlight_badge" name="liger_highlight_badge" value="<?php echo esc_attr( $highlight_badge ); ?>" placeholder="เช่น นำเข้าเกรดพรีเมียม / กันลามไฟ Class A2" />
		</div>

		<div class="liger-meta-field">
			<label for="liger_thickness">ความหนาของวัสดุ (Thickness):</label>
			<input type="text" id="liger_thickness" name="liger_thickness" value="<?php echo esc_attr( $thickness ); ?>" placeholder="เช่น 3 มม. / 4 มม. (ผิวอลูมิเนียม 0.50 มม.)" />
		</div>

		<div class="liger-meta-field">
			<label for="liger_dimensions">ขนาดมาตรฐาน (Standard Dimensions):</label>
			<input type="text" id="liger_dimensions" name="liger_dimensions" value="<?php echo esc_attr( $dimensions ); ?>" placeholder="เช่น 1,220 x 2,440 มม. / 1,250 x 3,050 มม." />
		</div>

		<div class="liger-meta-field">
			<label for="liger_coating">การเคลือบผิว / วัสดุผิวหน้า (Surface Coating):</label>
			<input type="text" id="liger_coating" name="liger_coating" value="<?php echo esc_attr( $coating ); ?>" placeholder="เช่น PVDF Kynar 500 / FEVE / PE Gloss" />
		</div>

		<div class="liger-meta-field">
			<label for="liger_fire_rating">มาตรฐานกันไฟ (Fire Resistance Class):</label>
			<input type="text" id="liger_fire_rating" name="liger_fire_rating" value="<?php echo esc_attr( $fire_rating ); ?>" placeholder="เช่น ASTM E84 Class A, EN 13501-1 Class A2" />
		</div>

		<div class="liger-meta-field">
			<label for="liger_warranty">ระยะเวลารับประกัน (Warranty):</label>
			<input type="text" id="liger_warranty" name="liger_warranty" value="<?php echo esc_attr( $warranty ); ?>" placeholder="เช่น 10 ปี / 15 ปี สภาพอากาศภายนอก" />
		</div>

		<div class="liger-meta-field" style="grid-column: 1 / -1;">
			<label for="liger_spec_pdf_url">ลิงก์เอกสารสเปก PDF (TDS Download URL):</label>
			<div style="display:flex;gap:8px;">
				<input type="url" id="liger_spec_pdf_url" name="liger_spec_pdf_url" value="<?php echo esc_url( $spec_pdf_url ); ?>" placeholder="https://..." style="flex:1;" />
				<button type="button" id="liger_upload_pdf_btn" class="button button-secondary">เลือกไฟล์ PDF จากคลังสื่อ</button>
			</div>
			<small>สามารถคลิกปุ่มด้านขวาเพื่อเลือกไฟล์ PDF จาก Media Library หรือวาง URL โดยตรงได้</small>
		</div>
	</div>

	<script>
	jQuery(document).ready(function($) {
		$('#liger_upload_pdf_btn').on('click', function(e) {
			e.preventDefault();
			var pdfFrame = wp.media({
				title: 'เลือกไฟล์เอกสารสเปก (PDF)',
				button: { text: 'ใช้ไฟล์นี้' },
				multiple: false
			});
			pdfFrame.on('select', function() {
				var attachment = pdfFrame.state().get('selection').first().toJSON();
				$('#liger_spec_pdf_url').val(attachment.url);
			});
			pdfFrame.open();
		});
	});
	</script>
	<?php
}

/**
 * Render Project Details Meta Box
 */
function ligermedia_render_project_details_meta_box( $post ) {
	wp_nonce_field( 'ligermedia_project_details_nonce_action', 'ligermedia_project_details_nonce' );

	$location      = get_post_meta( $post->ID, '_liger_project_location', true );
	$materials     = get_post_meta( $post->ID, '_liger_project_materials', true );
	$year          = get_post_meta( $post->ID, '_liger_project_year', true );
	$area_sqm      = get_post_meta( $post->ID, '_liger_project_area_sqm', true );
	?>
	<div class="liger-meta-grid">
		<div class="liger-meta-field">
			<label for="liger_project_location">สถานที่ตั้งโครงการ (Location):</label>
			<input type="text" id="liger_project_location" name="liger_project_location" value="<?php echo esc_attr( $location ); ?>" placeholder="เช่น ถนนพระราม 9, กรุงเทพมหานคร" />
		</div>

		<div class="liger-meta-field">
			<label for="liger_project_materials">วัสดุ LIGER MEDIA ที่ใช้ (Materials Applied):</label>
			<input type="text" id="liger_project_materials" name="liger_project_materials" value="<?php echo esc_attr( $materials ); ?>" placeholder="เช่น แผ่นคอมโพสิตลายหินอ่อน Fireproof A2" />
		</div>

		<div class="liger-meta-field">
			<label for="liger_project_year">ปีที่ติดตั้งแล้วเสร็จ (Completion Year):</label>
			<input type="text" id="liger_project_year" name="liger_project_year" value="<?php echo esc_attr( $year ); ?>" placeholder="เช่น 2025" />
		</div>

		<div class="liger-meta-field">
			<label for="liger_project_area_sqm">ขนาดพื้นที่ติดตั้ง (Installation Area):</label>
			<input type="text" id="liger_project_area_sqm" name="liger_project_area_sqm" value="<?php echo esc_attr( $area_sqm ); ?>" placeholder="เช่น 3,200 ตร.ม." />
		</div>
	</div>
	<?php
}

/**
 * Save Meta Box Data Securely
 */
function ligermedia_save_meta_boxes_data( $post_id ) {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	if ( ! current_user_can( 'edit_post', $post_id ) ) return;

	// 1. Product Display & Order Save
	if ( isset( $_POST['ligermedia_product_display_nonce'] ) && wp_verify_nonce( sanitize_key( $_POST['ligermedia_product_display_nonce'] ), 'ligermedia_product_display_nonce_action' ) ) {
		$is_featured = isset( $_POST['liger_is_featured'] ) ? '1' : '0';
		update_post_meta( $post_id, '_liger_is_featured', $is_featured );

		if ( isset( $_POST['liger_display_order'] ) ) {
			$order = absint( $_POST['liger_display_order'] );
			update_post_meta( $post_id, '_liger_display_order', $order );

			// Also update wp_posts menu_order seamlessly
			remove_action( 'save_post', 'ligermedia_save_meta_boxes_data' );
			wp_update_post( array(
				'ID'         => $post_id,
				'menu_order' => $order,
			) );
			add_action( 'save_post', 'ligermedia_save_meta_boxes_data' );
		}
	}

	// 2. Product Gallery Save
	if ( isset( $_POST['ligermedia_product_gallery_nonce'] ) && wp_verify_nonce( sanitize_key( $_POST['ligermedia_product_gallery_nonce'] ), 'ligermedia_product_gallery_nonce_action' ) ) {
		if ( isset( $_POST['liger_product_image_gallery'] ) ) {
			$gallery_raw = sanitize_text_field( wp_unslash( $_POST['liger_product_image_gallery'] ) );
			$clean_ids   = array_filter( array_map( 'absint', explode( ',', $gallery_raw ) ) );
			update_post_meta( $post_id, '_product_image_gallery', implode( ',', $clean_ids ) );
		}
	}

	// 3. Product Specs Save
	if ( isset( $_POST['ligermedia_product_specs_nonce'] ) && wp_verify_nonce( sanitize_key( $_POST['ligermedia_product_specs_nonce'] ), 'ligermedia_product_specs_nonce_action' ) ) {
		$fields = array(
			'liger_sku'             => '_liger_sku',
			'liger_highlight_badge' => '_liger_highlight_badge',
			'liger_thickness'       => '_liger_thickness',
			'liger_dimensions'      => '_liger_dimensions',
			'liger_coating'         => '_liger_coating',
			'liger_fire_rating'     => '_liger_fire_rating',
			'liger_warranty'        => '_liger_warranty',
		);

		foreach ( $fields as $post_key => $meta_key ) {
			if ( isset( $_POST[ $post_key ] ) ) {
				update_post_meta( $post_id, $meta_key, sanitize_text_field( wp_unslash( $_POST[ $post_key ] ) ) );
			}
		}

		if ( isset( $_POST['liger_spec_pdf_url'] ) ) {
			update_post_meta( $post_id, '_liger_spec_pdf_url', esc_url_raw( wp_unslash( $_POST['liger_spec_pdf_url'] ) ) );
		}
	}

	// 4. Project Details Save
	if ( isset( $_POST['ligermedia_project_details_nonce'] ) && wp_verify_nonce( sanitize_key( $_POST['ligermedia_project_details_nonce'] ), 'ligermedia_project_details_nonce_action' ) ) {
		$fields = array(
			'liger_project_location'  => '_liger_project_location',
			'liger_project_materials' => '_liger_project_materials',
			'liger_project_year'      => '_liger_project_year',
			'liger_project_area_sqm'  => '_liger_project_area_sqm',
		);

		foreach ( $fields as $post_key => $meta_key ) {
			if ( isset( $_POST[ $post_key ] ) ) {
				update_post_meta( $post_id, $meta_key, sanitize_text_field( wp_unslash( $_POST[ $post_key ] ) ) );
			}
		}
	}
}
add_action( 'save_post', 'ligermedia_save_meta_boxes_data' );
