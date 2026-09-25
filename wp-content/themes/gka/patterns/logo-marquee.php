<?php
/**
 * Title: Strip logo mitra & sertifikasi
 * Slug: gka/logo-marquee
 * Categories: gka
 * Inserter: no
 */
$files = glob( get_theme_file_path( 'assets/logos/*.{svg,png,webp,jpg,jpeg}' ), GLOB_BRACE ) ?: [];
sort( $files );
if ( ! $files ) {
	return;
}
$items = '';
foreach ( $files as $f ) {
	$name = basename( $f );
	$alt  = trim( preg_replace( '/^\d+[-_]?|[-_]+/', ' ', pathinfo( $name, PATHINFO_FILENAME ) ) );
	$items .= sprintf( '<li><img src="%s" alt="%s" loading="lazy" height="44"></li>', esc_url( get_theme_file_uri( 'assets/logos/' . $name ) ), esc_attr( $alt ) );
}
?>
<!-- wp:html -->
<section class="gka-logos gka-dark" aria-label="Mitra dan sertifikasi">
<p class="gka-eyebrow">Mitra &amp; sertifikasi</p>
<div class="gka-logos-viewport"><ul class="gka-logos-track"><?php echo $items; // phpcs:ignore WordPress.Security.EscapeOutput -- escaped above ?></ul><ul class="gka-logos-track" aria-hidden="true"><?php echo str_replace( '<img ', '<img role="presentation" ', $items ); // phpcs:ignore ?></ul></div>
</section>
<!-- /wp:html -->
