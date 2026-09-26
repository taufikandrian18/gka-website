<?php
/**
 * Title: Strip logo mitra & sertifikasi
 * Slug: gka/logo-marquee
 * Categories: gka
 * Inserter: no
 */
// Logos chosen in wp-admin → GKA Konten → Ajakan & logo; files in assets/logos/ when none are chosen.
$items = '';
foreach ( gka_c_imgs( 'logos' ) as $att ) {
	$src = wp_get_attachment_image_url( $att, 'medium' );
	if ( $src ) {
		$alt    = get_post_meta( $att, '_wp_attachment_image_alt', true ) ?: get_the_title( $att );
		$items .= sprintf( '<li><img src="%s" alt="%s" loading="lazy" height="44"></li>', esc_url( $src ), esc_attr( $alt ) );
	}
}
$files = $items ? [] : ( glob( get_theme_file_path( 'assets/logos/*.{svg,png,webp,jpg,jpeg}' ), GLOB_BRACE ) ?: [] );
sort( $files );
foreach ( $files as $f ) {
	$name = basename( $f );
	$alt  = trim( preg_replace( '/^\d+[-_]?|[-_]+/', ' ', pathinfo( $name, PATHINFO_FILENAME ) ) );
	$items .= sprintf( '<li><img src="%s" alt="%s" loading="lazy" height="44"></li>', esc_url( get_theme_file_uri( 'assets/logos/' . $name ) ), esc_attr( $alt ) );
}
if ( ! $items ) {
	return;
}
?>
<!-- wp:html -->
<section class="gka-logos gka-dark" aria-label="Mitra dan sertifikasi">
<p class="gka-eyebrow">Mitra &amp; sertifikasi</p>
<div class="gka-logos-viewport"><ul class="gka-logos-track"><?php echo $items; // phpcs:ignore WordPress.Security.EscapeOutput -- escaped above ?></ul><ul class="gka-logos-track" aria-hidden="true"><?php echo str_replace( '<img ', '<img role="presentation" ', $items ); // phpcs:ignore ?></ul></div>
</section>
<!-- /wp:html -->
