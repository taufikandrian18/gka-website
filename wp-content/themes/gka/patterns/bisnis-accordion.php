<?php
/**
 * Title: Bisnis kami (akordeon)
 * Slug: gka/bisnis-accordion
 * Categories: gka
 */
?>
<!-- wp:group {"tagName":"section","anchor":"bisnis","className":"gka-section gka-pt0","layout":{"type":"constrained","contentSize":"1240px"}} -->
<section id="bisnis" class="wp-block-group gka-section gka-pt0">
<!-- wp:group {"className":"gka-head","layout":{"type":"default"}} -->
<div class="wp-block-group gka-head">
<!-- wp:group {"layout":{"type":"default"}} --><div class="wp-block-group"><!-- wp:paragraph {"className":"gka-eyebrow"} --><p class="gka-eyebrow">Bisnis kami</p><!-- /wp:paragraph --><!-- wp:heading --><h2 class="wp-block-heading">Rantai usaha <em class="is-accent">unggas</em> yang saling terhubung</h2><!-- /wp:heading --></div><!-- /wp:group -->
<!-- wp:group {"className":"gka-head-side","layout":{"type":"default"}} --><div class="wp-block-group gka-head-side"><!-- wp:paragraph --><p>Dari kandang close house hingga kemitraan dengan peternak di sekitar Serang.</p><!-- /wp:paragraph --><!-- wp:paragraph {"className":"gka-more"} --><p class="gka-more"><a href="/bisnis-kami/">Lihat semua bisnis</a></p><!-- /wp:paragraph --></div><!-- /wp:group -->
</div><!-- /wp:group -->
<!-- wp:columns {"className":"gka-biz"} -->
<div class="wp-block-columns gka-biz">
<!-- wp:column --><div class="wp-block-column">
<!-- wp:query {"queryId":11,"query":{"postType":"gka_bisnis","perPage":8,"order":"asc","orderBy":"menu_order","inherit":false},"className":"gka-accordion"} -->
<div class="wp-block-query gka-accordion"><!-- wp:post-template -->
<!-- wp:post-title {"level":3,"className":"gka-acc-title"} /-->
<!-- wp:post-excerpt {"moreText":"Buka halaman","showMoreOnNewLine":true} /-->
<!-- /wp:post-template --></div>
<!-- /wp:query -->
</div><!-- /wp:column -->
<!-- wp:column --><div class="wp-block-column">
<!-- wp:image {"sizeSlug":"full","className":"gka-sticky-photo"} --><figure class="wp-block-image size-full gka-sticky-photo"><img src="<?php echo esc_url( gka_photo( 'bisnis', 'galeri-01.webp' ) ); ?>" alt="Kandang close house PT Gemilang Karya Agri" loading="lazy"/></figure><!-- /wp:image -->
</div><!-- /wp:column -->
</div>
<!-- /wp:columns -->
</section>
<!-- /wp:group -->
