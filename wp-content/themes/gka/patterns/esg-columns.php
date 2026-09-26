<?php
/**
 * Title: ESG di atas lanskap
 * Slug: gka/esg-columns
 * Categories: gka
 */
$cols = [];
foreach ( [ 1 => 'kebijakan', 'piagam', 'sertifikasi' ] as $n => $slug ) {
	// Copy from wp-admin → GKA Konten → ESG; the three cards always link to the three ESG categories.
	$cols[] = [ $slug, gka_c( "esg{$n}_title" ), gka_c( "esg{$n}_text" ) ];
}
?>
<!-- wp:html -->
<section id="esg" class="gka-esg-band gka-dark" aria-labelledby="gka-esg-title">
<div class="gka-esg-media" aria-hidden="true"><img src="<?php echo esc_url( gka_c_img( 'esg_bg', gka_photo( 'lanskap', 'lanskap-kandang.webp' ) ) ); ?>" alt="" loading="lazy" width="1920" height="1080"><span class="gka-grain"></span></div>
<div class="gka-esg-inner">
<div class="gka-head">
<div><p class="gka-eyebrow"><?php echo gka_ct( 'esg_eyebrow' ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in gka_ct ?></p><h2 id="gka-esg-title" class="wp-block-heading"><?php echo gka_ct( 'esg_title' ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in gka_ct ?></h2></div>
<div class="gka-head-side"><p><?php echo gka_ct( 'esg_text' ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in gka_ct ?></p><p class="gka-more"><a href="/esg/"><?php echo esc_html( gka_c( 'esg_link' ) ); ?></a></p></div>
</div>
<div class="gka-esg">
<?php foreach ( $cols as $i => [ $slug, $title, $text ] ) : ?>
<a href="/esg/<?php echo esc_attr( $slug ); ?>/"><span class="gka-esg-no">0<?php echo (int) $i + 1; ?></span><h3><?php echo esc_html( $title ); ?></h3><p><?php echo esc_html( $text ); ?></p><span class="gka-esg-go" aria-hidden="true">↗</span></a>
<?php endforeach; ?>
</div>
</div>
</section>
<!-- /wp:html -->
