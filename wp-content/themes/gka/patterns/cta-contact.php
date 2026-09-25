<?php
/**
 * Title: Ajakan hubungi kami (tipografi besar)
 * Slug: gka/cta-contact
 * Categories: gka
 */
$words = [ 'Broiler', 'Close house', 'Kemitraan', 'Penimbangan terbuka', 'Serang, Banten' ];
?>
<!-- wp:html -->
<section class="gka-cta gka-dark" aria-labelledby="gka-cta-title">
<div class="gka-marquee" aria-hidden="true"><div class="gka-marquee-track"><?php for ( $r = 0; $r < 2; $r++ ) : ?><span><?php foreach ( $words as $w ) : ?><?php echo esc_html( $w ); ?><i>✦</i><?php endforeach; ?></span><?php endfor; ?></div></div>
<div class="gka-cta-inner">
<p class="gka-eyebrow">Hubungi kami</p>
<h2 id="gka-cta-title" class="gka-cta-title">Butuh pasokan broiler atau ingin <em class="is-accent">bermitra?</em></h2>
<div class="gka-cta-row">
<p>Tim kami membalas di jam kerja. Ceritakan kebutuhan Anda, kami atur jadwal kunjungan atau panen.</p>
<div class="wp-block-buttons">
<div class="wp-block-button is-style-arrow gka-magnet"><a class="wp-block-button__link wp-element-button" href="https://wa.me/6287771491004">Chat WhatsApp</a></div>
<div class="wp-block-button is-style-outline gka-btn-ghost"><a class="wp-block-button__link wp-element-button" href="tel:+622545753355">(0254) 575 3355</a></div>
</div>
</div>
</div>
</section>
<!-- /wp:html -->
