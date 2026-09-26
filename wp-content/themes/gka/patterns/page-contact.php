<?php
/**
 * Title: Halaman hubungi kami
 * Slug: gka/page-contact
 * Categories: gka
 */
?>
<!-- wp:columns {"className":"gka-contact"} -->
<div class="wp-block-columns gka-contact">
<!-- wp:column {"className":"gka-card"} --><div class="wp-block-column gka-card">
<!-- wp:heading {"fontSize":"xl"} --><h2 class="wp-block-heading has-xl-font-size">Kirim pesan</h2><!-- /wp:heading -->
<!-- wp:shortcode -->[gka_contact_form]<!-- /wp:shortcode -->
</div><!-- /wp:column -->
<!-- wp:column --><div class="wp-block-column">
<!-- wp:html -->
<div class="gka-info"><dl>
<div><dt>Alamat</dt><dd><?php echo nl2br( esc_html( gka_c( 'address' ) ) ); ?></dd></div>
<div><dt>Telepon</dt><dd><a href="tel:<?php echo esc_attr( gka_c_tel( gka_c( 'phone' ) ) ); ?>"><?php echo esc_html( gka_c( 'phone' ) ); ?></a></dd></div>
<?php if ( gka_c( 'fax' ) ) : ?><div><dt>Fax</dt><dd><?php echo esc_html( gka_c( 'fax' ) ); ?></dd></div><?php endif; ?>
<div><dt>Email</dt><dd><a href="mailto:<?php echo esc_attr( gka_c( 'email' ) ); ?>"><?php echo esc_html( gka_c( 'email' ) ); ?></a></dd></div>
<div><dt>WhatsApp</dt><dd><a href="<?php echo esc_url( gka_c_wa() ); ?>"><?php echo esc_html( gka_c( 'whatsapp' ) ); ?></a></dd></div>
</dl></div>
<iframe class="gka-map" title="Peta lokasi <?php echo esc_attr( gka_c( 'company' ) ); ?>" loading="lazy" referrerpolicy="no-referrer-when-downgrade" src="<?php echo esc_url( 'https://maps.google.com/maps?q=' . rawurlencode( gka_c( 'map' ) ) . '&z=15&output=embed' ); ?>"></iframe>
<!-- /wp:html -->
</div><!-- /wp:column -->
</div>
<!-- /wp:columns -->
