<?php
/**
 * Title: Ajakan hubungi kami (tipografi besar)
 * Slug: gka/cta-contact
 * Categories: gka
 */
// Copy from wp-admin → GKA Konten → Ajakan & logo; phone and WhatsApp from the Kontak tab.
$words = array_filter( array_map( 'trim', explode( '/', gka_c( 'cta_words' ) ) ) );
?>
<!-- wp:html -->
<section class="gka-cta gka-dark" aria-labelledby="gka-cta-title">
<div class="gka-marquee" aria-hidden="true"><div class="gka-marquee-track"><?php for ( $r = 0; $r < 2; $r++ ) : ?><span><?php foreach ( $words as $w ) : ?><?php echo esc_html( $w ); ?><i>✦</i><?php endforeach; ?></span><?php endfor; ?></div></div>
<div class="gka-cta-inner">
<p class="gka-eyebrow"><?php echo gka_ct( 'cta_eyebrow' ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in gka_ct ?></p>
<h2 id="gka-cta-title" class="gka-cta-title"><?php echo gka_ct( 'cta_title' ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in gka_ct ?></h2>
<div class="gka-cta-row">
<p><?php echo gka_ct( 'cta_text' ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in gka_ct ?></p>
<div class="wp-block-buttons">
<div class="wp-block-button is-style-arrow gka-magnet"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( gka_c_wa() ); ?>"><?php echo esc_html( gka_c( 'cta_wa' ) ); ?></a></div>
<div class="wp-block-button is-style-outline gka-btn-ghost"><a class="wp-block-button__link wp-element-button" href="tel:<?php echo esc_attr( gka_c_tel( gka_c( 'phone' ) ) ); ?>"><?php echo esc_html( gka_c( 'phone' ) ); ?></a></div>
</div>
</div>
</div>
</section>
<!-- /wp:html -->
