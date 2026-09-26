<?php
/**
 * Title: Proses dari kandang ke pembeli (gulir horizontal)
 * Slug: gka/process
 * Categories: gka
 */
$alts  = [ 1 => 'Tempat pakan dan minum otomatis di kandang close house', 'Tim menangani ayam saat panen', 'Pemuatan keranjang ayam dari kandang', 'Truk pengangkut bermuatan keranjang panen' ];
$steps = [];
for ( $n = 1; $n <= 4; $n++ ) {
	// Copy from wp-admin → GKA Konten → Proses; a photo chosen there wins over the theme's step photo.
	$theme_img = file_exists( get_theme_file_path( "assets/photos/step{$n}.webp" ) ) ? get_theme_file_uri( "assets/photos/step{$n}.webp" ) : '';
	$steps[]   = [ gka_c( "step{$n}_title" ), gka_c( "step{$n}_text" ), gka_c_img( "step{$n}_img", $theme_img, 'large' ), $alts[ $n ] ];
}
?>
<!-- wp:group {"tagName":"section","anchor":"proses","align":"full","className":"gka-section gka-process gka-dark","layout":{"type":"constrained","contentSize":"1240px"}} -->
<section id="proses" class="wp-block-group alignfull gka-section gka-process gka-dark">
<!-- wp:group {"className":"gka-head","layout":{"type":"default"}} -->
<div class="wp-block-group gka-head">
<!-- wp:group {"layout":{"type":"default"}} --><div class="wp-block-group"><!-- wp:paragraph {"className":"gka-eyebrow"} --><p class="gka-eyebrow"><?php echo gka_ct( 'proses_eyebrow' ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in gka_ct ?></p><!-- /wp:paragraph --><!-- wp:heading --><h2 class="wp-block-heading"><?php echo gka_ct( 'proses_title' ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in gka_ct ?></h2><!-- /wp:heading --></div><!-- /wp:group -->
<!-- wp:group {"className":"gka-head-side","layout":{"type":"default"}} --><div class="wp-block-group gka-head-side"><!-- wp:paragraph --><p><?php echo gka_ct( 'proses_text' ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in gka_ct ?></p><!-- /wp:paragraph --><!-- wp:paragraph {"className":"gka-more"} --><p class="gka-more"><a href="<?php echo esc_url( gka_c( 'proses_link_url' ) ); ?>"><?php echo esc_html( gka_c( 'proses_link' ) ); ?></a></p><!-- /wp:paragraph --></div><!-- /wp:group -->
</div><!-- /wp:group -->
<!-- wp:html -->
<div class="gka-hscroll">
<ol class="gka-track">
<?php foreach ( $steps as $n => [ $title, $text, $img, $alt ] ) : $has = '' !== $img; ?>
<li class="gka-panel<?php echo $has ? '' : ' is-type'; ?>">
<?php if ( $has ) : ?><figure class="gka-panel-img"><img src="<?php echo esc_url( $img ); ?>" alt="<?php echo esc_attr( $alt ); ?>" loading="lazy" width="1200" height="900"></figure><?php else : ?><span class="gka-panel-mark" aria-hidden="true"></span><?php endif; ?>
<div class="gka-panel-text"><span class="gka-panel-no" aria-hidden="true"><?php echo esc_html( str_pad( (string) ( $n + 1 ), 2, '0', STR_PAD_LEFT ) ); ?></span><p class="gka-step-no">Tahap <?php echo (int) $n + 1; ?></p><h3><?php echo esc_html( $title ); ?></h3><p><?php echo esc_html( $text ); ?></p></div>
</li>
<?php endforeach; ?>
<li class="gka-panel is-end">
<p class="gka-readout"><?php echo esc_html( gka_c( 'proses_readout' ) ); ?><small><?php echo esc_html( gka_c( 'proses_readout_unit' ) ); ?></small></p>
<p class="gka-scale-text"><?php echo gka_ct( 'proses_scale' ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in gka_ct ?></p>
<div class="wp-block-buttons"><div class="wp-block-button is-style-arrow"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( gka_c( 'proses_btn_url' ) ); ?>"><?php echo esc_html( gka_c( 'proses_btn' ) ); ?></a></div></div>
</li>
</ol>
<div class="gka-hbar" aria-hidden="true"><i></i></div>
</div>
<!-- /wp:html -->
</section>
<!-- /wp:group -->
