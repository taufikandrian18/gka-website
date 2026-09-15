<?php
/**
 * Title: Galeri dan publikasi terbaru
 * Slug: gka/galeri-publikasi
 * Categories: gka
 */
?>
<!-- wp:group {"tagName":"section","anchor":"publikasi","className":"gka-section gka-pt0","layout":{"type":"constrained","contentSize":"1240px"}} -->
<section id="publikasi" class="wp-block-group gka-section gka-pt0">
<!-- wp:columns {"className":"gka-split"} -->
<div class="wp-block-columns gka-split">
<!-- wp:column {"width":"55%"} --><div class="wp-block-column" style="flex-basis:55%">
<!-- wp:group {"className":"gka-mini-head","layout":{"type":"flex","justifyContent":"space-between","verticalAlignment":"bottom","flexWrap":"wrap"}} -->
<div class="wp-block-group gka-mini-head"><!-- wp:group {"layout":{"type":"default"}} --><div class="wp-block-group"><!-- wp:paragraph {"className":"gka-eyebrow"} --><p class="gka-eyebrow">Galeri</p><!-- /wp:paragraph --><!-- wp:heading {"fontSize":"2xl"} --><h2 class="wp-block-heading has-2-xl-font-size">Perjalanan kami <em class="is-accent">dalam gambar</em></h2><!-- /wp:heading --></div><!-- /wp:group --><!-- wp:paragraph {"className":"gka-more"} --><p class="gka-more"><a href="/galeri/">Semua foto</a></p><!-- /wp:paragraph --></div>
<!-- /wp:group -->
<!-- wp:query {"queryId":13,"query":{"postType":"gka_galeri","perPage":5,"order":"asc","orderBy":"menu_order","inherit":false},"className":"gka-gal"} -->
<div class="wp-block-query gka-gal"><!-- wp:post-template -->
<!-- wp:post-featured-image {"aspectRatio":"auto"} /-->
<!-- /wp:post-template --></div>
<!-- /wp:query -->
</div><!-- /wp:column -->
<!-- wp:column --><div class="wp-block-column">
<!-- wp:group {"className":"gka-mini-head","layout":{"type":"flex","justifyContent":"space-between","verticalAlignment":"bottom","flexWrap":"wrap"}} -->
<div class="wp-block-group gka-mini-head"><!-- wp:group {"layout":{"type":"default"}} --><div class="wp-block-group"><!-- wp:paragraph {"className":"gka-eyebrow"} --><p class="gka-eyebrow">Publikasi</p><!-- /wp:paragraph --><!-- wp:heading {"fontSize":"2xl"} --><h2 class="wp-block-heading has-2-xl-font-size">Kabar terbaru</h2><!-- /wp:heading --></div><!-- /wp:group --><!-- wp:paragraph {"className":"gka-more"} --><p class="gka-more"><a href="/publikasi/">Semua publikasi</a></p><!-- /wp:paragraph --></div>
<!-- /wp:group -->
<!-- wp:query {"queryId":14,"query":{"postType":"gka_publikasi","perPage":3,"order":"desc","orderBy":"date","inherit":false},"className":"gka-news"} -->
<div class="wp-block-query gka-news"><!-- wp:post-template -->
<!-- wp:post-date {"format":"d/m/Y"} /-->
<!-- wp:post-title {"level":3,"isLink":true} /-->
<!-- /wp:post-template -->
<!-- wp:query-no-results --><!-- wp:paragraph --><p>Belum ada publikasi.</p><!-- /wp:paragraph --><!-- /wp:query-no-results --></div>
<!-- /wp:query -->
</div><!-- /wp:column -->
</div>
<!-- /wp:columns -->
</section>
<!-- /wp:group -->
