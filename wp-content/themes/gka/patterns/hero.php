<?php
/**
 * Title: Hero beranda dengan statistik
 * Slug: gka/hero
 * Categories: gka
 * Inserter: no
 */
$img = gka_photo( 'hero', 'hero-close-house.webp' );
?>
<!-- wp:cover {"url":"<?php echo esc_url( $img ); ?>","dimRatio":0,"minHeight":660,"isDark":true,"align":"full","className":"gka-hero","layout":{"type":"default"}} -->
<div class="wp-block-cover alignfull is-dark gka-hero" style="min-height:660px"><img class="wp-block-cover__image-background" alt="" src="<?php echo esc_url( $img ); ?>" data-object-fit="cover" fetchpriority="high"/><span aria-hidden="true" class="wp-block-cover__background has-background-dim-0 has-background-dim"></span><div class="wp-block-cover__inner-container">
<!-- wp:template-part {"slug":"header","tagName":"div","className":"gka-header-wrap is-on-hero"} /-->
<!-- wp:group {"className":"gka-hero-body","layout":{"type":"default"}} -->
<div class="wp-block-group gka-hero-body">
<!-- wp:heading {"level":1,"className":"gka-rise"} --><h1 class="wp-block-heading gka-rise">Ayam potong sehat dari kandang <em class="is-accent">close house</em> di Serang</h1><!-- /wp:heading -->
<!-- wp:group {"className":"gka-hero-side gka-rise gka-d2","layout":{"type":"default"}} -->
<div class="wp-block-group gka-hero-side gka-rise gka-d2">
<!-- wp:paragraph {"className":"gka-hero-lede"} --><p class="gka-hero-lede">Kami membesarkan broiler di kandang tertutup bertingkat dengan suhu dan sanitasi terjaga, lalu menimbangnya bersama pembeli di lokasi.</p><!-- /wp:paragraph -->
<!-- wp:buttons --><div class="wp-block-buttons">
<!-- wp:button {"className":"is-style-arrow"} --><div class="wp-block-button is-style-arrow"><a class="wp-block-button__link wp-element-button" href="/bisnis-kami/kemitraan-peternak/">Ajukan kemitraan</a></div><!-- /wp:button -->
<!-- wp:button {"className":"is-style-outline gka-btn-ghost"} --><div class="wp-block-button is-style-outline gka-btn-ghost"><a class="wp-block-button__link wp-element-button" href="/tentang-kami/">Tentang perusahaan</a></div><!-- /wp:button -->
</div><!-- /wp:buttons -->
</div><!-- /wp:group -->
</div><!-- /wp:group -->
<!-- wp:html -->
<button type="button" class="gka-scroll-cue" aria-label="Gulir ke konten"><span>Gulir</span><i aria-hidden="true"></i></button>
<!-- /wp:html -->
<!-- wp:group {"className":"gka-statbar","layout":{"type":"default"}} -->
<div class="wp-block-group gka-statbar">
<!-- wp:paragraph --><p><strong>150.000+</strong>ekor kapasitas produksi</p><!-- /wp:paragraph -->
<!-- wp:paragraph --><p><strong>25.000</strong>ekor per lantai kandang</p><!-- /wp:paragraph -->
<!-- wp:paragraph --><p><strong>2</strong>kandang close house</p><!-- /wp:paragraph -->
<!-- wp:paragraph --><p><strong>2016</strong>mulai beroperasi</p><!-- /wp:paragraph -->
</div><!-- /wp:group -->
</div></div>
<!-- /wp:cover -->
