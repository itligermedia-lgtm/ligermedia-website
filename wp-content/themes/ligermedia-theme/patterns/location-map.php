<?php
/**
 * Title: Warehouse & Contact Hub (Get In Touch + Form + Wide Map)
 * Slug: ligermedia/location-map
 * Categories: featured, contact
 * Description: Clean Get In Touch layout with 2x2 info grid, interactive contact form card, and full-width Google Map
 */
?>
<!-- wp:group {"align":"full","style":{"color":{"background":"#FFFFFF"},"spacing":{"padding":{"top":"5rem","bottom":"5rem","left":"0","right":"0"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull liger-contact-home-section" style="background-color:#FFFFFF;padding-top:5rem;padding-bottom:5rem;border-top:1px solid #E1E4E8;">

	<!-- Schema.org LocalBusiness Structured Data for Google SEO & Maps -->
	<script type="application/ld+json">
	{
		"@context": "https://schema.org",
		"@type": "LocalBusiness",
		"name": "บริษัท ไลเกอร์ มีเดีย จำกัด (Liger Media Co., Ltd.)",
		"image": "https://www.ligermedia.co.th/wp-content/themes/ligermedia-theme/assets/images/logo.svg",
		"description": "ผู้นำเข้าและจัดจำหน่ายวัสดุตกแต่งอาคาร แผ่นอลูมิเนียมคอมโพสิต แผ่นอะคริลิก พลาสวูด ไวนิล และโมดูล LED มาตรฐานสากล",
		"address": {
			"@type": "PostalAddress",
			"streetAddress": "34/9 ซอยพุทธบูชา 32 ถนนพุทธบูชา",
			"addressLocality": "แขวงบางมด",
			"addressRegion": "เขตทุ่งครุ, กรุงเทพมหานคร",
			"postalCode": "10140",
			"addressCountry": "TH"
		},
		"telephone": "+6624265075",
		"openingHours": "Mo-Sa 08:30-17:30",
		"url": "https://www.ligermedia.co.th"
	}
	</script>

	<div class="alignwide">
		
		<!-- 2-Column Split: Get In Touch (Left) & Form Card (Right) -->
		<div class="liger-contact-split-grid">
			
			<!-- Left Column: Get In Touch & 2x2 Info Grid -->
			<div class="liger-contact-info-col" data-reveal="left">
				<h2 class="liger-contact-section-title">Get In Touch</h2>
				<p class="liger-contact-section-desc">
					บริษัท ไลเกอร์ มีเดีย จำกัด พร้อมให้คำปรึกษาสเปกวัสดุวิศวกรรม ประเมินราคา หรือประสานงานนำรถกระบะ/รถบรรทุกเข้ารับสินค้าด่วนที่คลังสินค้า
				</p>

				<!-- 2x2 Info Grid with Dark Squircle Icons -->
				<div class="liger-contact-2x2-grid">
					
					<!-- 1. Phone -->
					<div class="liger-c2-item">
						<div class="liger-c2-icon">
							<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#FF5500" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
						</div>
						<div class="liger-c2-text">
							<div class="liger-c2-label">Phone</div>
							<div class="liger-c2-val">
								<a href="tel:024265075">02-426-5075 ถึง 78</a><br>
								<a href="tel:0865588897" style="color:#FF5500;font-weight:700;">086-558-8897 (สายด่วน)</a>
							</div>
						</div>
					</div>

					<!-- 2. Email -->
					<div class="liger-c2-item">
						<div class="liger-c2-icon">
							<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#FF5500" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
						</div>
						<div class="liger-c2-text">
							<div class="liger-c2-label">Email</div>
							<div class="liger-c2-val">
								<a href="mailto:info@ligermedia.co.th">info@ligermedia.co.th</a><br>
								<a href="mailto:sales@ligermedia.co.th">sales@ligermedia.co.th</a>
							</div>
						</div>
					</div>

					<!-- 3. Address -->
					<div class="liger-c2-item">
						<div class="liger-c2-icon">
							<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#FF5500" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
						</div>
						<div class="liger-c2-text">
							<div class="liger-c2-label">Address</div>
							<div class="liger-c2-val">
								34/9 ซอยพุทธบูชา 32 แขวงบางมด เขตทุ่งครุ กทม. 10140
							</div>
						</div>
					</div>

					<!-- 4. LINE Official -->
					<div class="liger-c2-item">
						<div class="liger-c2-icon">
							<svg width="22" height="22" viewBox="0 0 24 24" fill="#06C755"><path d="M12 2C6.48 2 2 5.92 2 10.75c0 3.01 1.76 5.66 4.45 7.15-.2.72-.73 2.62-.84 3.03-.13.5.18.49.38.36.16-.1 2.21-1.5 3.08-2.1.92.26 1.9.41 2.93.41 5.52 0 10-3.92 10-8.75S17.52 2 12 2z"/></svg>
						</div>
						<div class="liger-c2-text">
							<div class="liger-c2-label">LINE Official</div>
							<div class="liger-c2-val">
								<a href="https://line.me/R/ti/p/@ligermedia" target="_blank" rel="noopener noreferrer" style="color:#06C755;font-weight:700;">@ligermedia</a><br>
								<span style="font-size:0.85rem;color:#6E7781;">เวลาทำการ 08:30 - 17:30 น.</span>
							</div>
						</div>
					</div>

				</div>

				<!-- Social Media Links Row -->
				<div class="liger-social-follow-row">
					<span class="liger-social-label">Social Media</span>
					<div class="liger-social-icons-group">
						<a href="https://line.me/R/ti/p/@ligermedia" target="_blank" rel="noopener noreferrer" class="liger-social-icon" aria-label="LINE Official">
							<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 5.92 2 10.75c0 3.01 1.76 5.66 4.45 7.15-.2.72-.73 2.62-.84 3.03-.13.5.18.49.38.36.16-.1 2.21-1.5 3.08-2.1.92.26 1.9.41 2.93.41 5.52 0 10-3.92 10-8.75S17.52 2 12 2z"/></svg>
						</a>
						<a href="https://facebook.com/ligermedia" target="_blank" rel="noopener noreferrer" class="liger-social-icon" aria-label="Facebook">
							<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path></svg>
						</a>
						<a href="https://youtube.com/@ligermedia" target="_blank" rel="noopener noreferrer" class="liger-social-icon" aria-label="YouTube">
							<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 0 0-1.94 2A29 29 0 0 0 1 11.75a29 29 0 0 0 .46 5.33A2.78 2.78 0 0 0 3.4 19c1.72.46 8.6.46 8.6.46s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-2 29 29 0 0 0 .46-5.25 29 29 0 0 0-.46-5.33z"/><polygon points="9.75 15.02 15.5 11.75 9.75 8.48 9.75 15.02" fill="#FFF"/></svg>
						</a>
					</div>
				</div>
			</div>

			<!-- Right Column: Clean Form Card -->
			<div class="liger-contact-form-card" data-reveal="right">
				<form class="liger-page-form" id="liger-home-contact-form">
					
					<div class="liger-form-row-2col">
						<div class="liger-input-group">
							<label for="home-contact-email">Email</label>
							<input type="email" id="home-contact-email" name="email" placeholder="example@domain.com" required />
						</div>
						<div class="liger-input-group">
							<label for="home-contact-name">Name</label>
							<input type="text" id="home-contact-name" name="name" placeholder="ชื่อผู้ติดต่อ / บริษัท" required />
						</div>
					</div>

					<div class="liger-input-group">
						<label for="home-contact-phone">Phone</label>
						<input type="tel" id="home-contact-phone" name="phone" placeholder="08X-XXX-XXXX" required />
					</div>

					<div class="liger-input-group">
						<label for="home-contact-message">Message</label>
						<textarea id="home-contact-message" name="message" rows="4" placeholder="ระบุประเภทวัสดุที่สนใจ ขนาด หรือข้อมูลที่ต้องการขอคำปรึกษา..." required></textarea>
					</div>

					<button type="submit" class="liger-contact-submit-btn">
						<span>SUBMIT BUTTON</span>
					</button>

					<div class="liger-feedback-msg" style="display:none;"></div>
				</form>
			</div>

		</div>

		<!-- Full-Width Interactive Google Maps Embed (Below 2-Column Section) -->
		<div class="liger-contact-fullmap-wrap" data-reveal="up">
			<div class="liger-fullmap-card">
				<iframe 
					title="แผนที่ตั้งคลังสินค้าและสำนักงานใหญ่ บริษัท ไลเกอร์ มีเดีย จำกัด"
					src="https://maps.google.com/maps?q=%E0%B8%9A%E0%B8%A3%E0%B8%B4%E0%B8%A9%E0%B8%B1%E0%B8%97+%E0%B9%84%E0%B8%A5%E0%B9%80%E0%B8%81%E0%B8%AD%E0%B8%A3%E0%B9%8C+%E0%B8%A1%E0%B8%B5%E0%B9%80%E0%B8%94%E0%B8%B5%E0%B8%A2+%E0%B8%88%E0%B8%B3%E0%B8%81%E0%B8%B1%E0%B8%94+34%2F9+%E0%B8%8B%E0%B8%AD%E0%B8%A2%E0%B8%9E%E0%B8%B8%E0%B8%97%E0%B8%98%E0%B8%9A%E0%B8%B9%E0%B8%8A%E0%B8%B2+32&t=&z=16&ie=UTF8&iwloc=&output=embed" 
					width="100%" 
					height="440" 
					style="border:0;display:block;" 
					allowfullscreen="" 
					loading="lazy" 
					referrerpolicy="no-referrer-when-downgrade">
				</iframe>
			</div>
			<div class="liger-fullmap-bar">
				<div class="liger-fullmap-address">
					<strong>ที่ตั้งศูนย์กระจายสินค้า & โกดัง:</strong> 34/9 ซอยพุทธบูชา 32 ถนนพุทธบูชา แขวงบางมด เขตทุ่งครุ กรุงเทพมหานคร 10140 (มีบริการโฟล์คลิฟต์ยกขึ้นรถ)
				</div>
				<a href="https://maps.google.com/?q=บริษัท+ไลเกอร์+มีเดีย+จำกัด+พุทธบูชา+32" target="_blank" rel="noopener noreferrer" class="liger-btn liger-btn--primary liger-btn--sm" style="text-decoration:none;">
					<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polygon points="3 11 22 2 13 21 11 13 3 11"></polygon></svg>
					<span>เปิด Google Maps นำทาง (GPS)</span>
				</a>
			</div>
		</div>

	</div>
</div>
<!-- /wp:group -->
