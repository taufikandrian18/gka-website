<?php
/**
 * Title: Hero beranda (slideshow sinematik)
 * Slug: gka/hero
 * Categories: gka
 * Inserter: no
 *
 * Copy and photos come from wp-admin → GKA Konten → Pembuka.
 */
$defaults = [ 1 => gka_photo( 'hero', 'hero-close-house.webp' ), gka_photo( 'feat-c', 'galeri-02.webp' ), gka_photo( 'cta', 'lanskap-kandang.webp' ), gka_photo( 'feat-a', 'galeri-03.webp' ), '' ];
$slides   = [];
foreach ( $defaults as $n => $fallback ) {
	$src = gka_c_img( "hero_slide{$n}_img", $fallback );
	if ( $src ) {
		$slides[] = [ $src, gka_c( "hero_slide{$n}_cap" ) ];
	}
}
?>
<!-- wp:html -->
<section class="gka-hero" aria-label="Pembuka">
<div class="gka-hero-media" aria-hidden="true">
<?php foreach ( $slides as $i => [ $src, $cap ] ) : ?>
<img class="gka-hero-slide<?php echo 0 === $i ? ' is-active' : ''; ?>" src="<?php echo esc_url( $src ); ?>" alt="" <?php echo 0 === $i ? 'fetchpriority="high"' : 'loading="lazy"'; ?> data-caption="<?php echo esc_attr( $cap ); ?>" width="1920" height="1280">
<?php endforeach; ?>
<span class="gka-hero-shade"></span><span class="gka-grain"></span>
</div>
<div class="gka-hero-body">
<p class="gka-eyebrow gka-hero-eyebrow"><?php echo gka_ct( 'hero_eyebrow' ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in gka_ct ?></p>
<h1 class="gka-hero-title"><?php echo gka_ct( 'hero_title' ); // phpcs:ignore ?></h1>
<div class="gka-hero-side">
<p class="gka-hero-lede"><?php echo gka_ct( 'hero_lede' ); // phpcs:ignore ?></p>
<div class="wp-block-buttons">
<div class="wp-block-button is-style-arrow"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( gka_c( 'hero_btn1_url' ) ); ?>"><?php echo esc_html( gka_c( 'hero_btn1' ) ); ?></a></div>
<div class="wp-block-button is-style-outline gka-btn-ghost"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( gka_c( 'hero_btn2_url' ) ); ?>"><?php echo esc_html( gka_c( 'hero_btn2' ) ); ?></a></div>
</div>
</div>
</div>
<div class="gka-hero-foot">
<div class="gka-hero-count" aria-hidden="true"><b class="gka-hero-idx">01</b><span>/ <?php echo esc_html( str_pad( (string) count( $slides ), 2, '0', STR_PAD_LEFT ) ); ?></span></div>
<div class="gka-hero-bars" aria-hidden="true"><?php foreach ( $slides as $i => $s ) : ?><i class="<?php echo 0 === $i ? 'is-active' : ''; ?>"></i><?php endforeach; ?></div>
<p class="gka-hero-cap" aria-hidden="true"><?php echo esc_html( $slides[0][1] ?? '' ); ?></p>
<button type="button" class="gka-scroll-cue" aria-label="Gulir ke konten"><span>Gulir</span><i aria-hidden="true"></i></button>
</div>
</section>
<!-- /wp:html -->
