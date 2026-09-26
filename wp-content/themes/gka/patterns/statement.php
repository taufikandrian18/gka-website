<?php
/**
 * Title: Pernyataan tentang kami + angka
 * Slug: gka/statement
 * Categories: gka
 *
 * Copy comes from wp-admin → GKA Konten → Tentang & angka.
 */
$pill      = '<img class="gka-pill" src="' . esc_url( gka_photo( 'pill', 'pill-broiler.webp' ) ) . '" alt="" width="84" height="40">';
$statement = str_replace( '[foto]', $pill, gka_ct( 'about_text' ) );
?>
<!-- wp:html -->
<section id="tentang" class="gka-section gka-dark gka-intro-sec has-global-padding is-layout-constrained">
<p class="gka-eyebrow"><?php echo gka_ct( 'about_eyebrow' ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in gka_ct ?></p>
<p class="gka-statement gka-scrub"><?php echo $statement; // phpcs:ignore ?></p>
<dl class="gka-counters">
<?php for ( $n = 1; $n <= 4; $n++ ) :
	$num = gka_c( "stat{$n}_num" );
	$to  = preg_replace( '/\D+/', '', $num );
	?>
<div><dt><?php echo esc_html( gka_c( "stat{$n}_label" ) ); ?></dt><dd><span class="gka-count"<?php echo '' !== $to && preg_match( '/^[\d.]+$/', $num ) ? ' data-to="' . esc_attr( $to ) . '"' : ''; ?>><?php echo esc_html( $num ); ?></span><?php echo esc_html( gka_c( "stat{$n}_suf" ) ); ?></dd></div>
<?php endfor; ?>
</dl>
<div class="gka-meta is-layout-flex">
<p><strong>Lokasi</strong> · <?php echo esc_html( gka_c( 'about_lokasi' ) ); ?></p>
<p><strong>Sistem</strong> · <?php echo esc_html( gka_c( 'about_sistem' ) ); ?></p>
<p class="gka-more"><a href="<?php echo esc_url( gka_c( 'about_link_url' ) ); ?>"><?php echo esc_html( gka_c( 'about_link' ) ); ?></a></p>
</div>
</section>
<!-- /wp:html -->
