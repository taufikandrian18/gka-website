<?php
/**
 * Title: Bisnis kami (daftar indeks dengan pratinjau foto)
 * Slug: gka/bisnis-accordion
 * Categories: gka
 */
?>
<!-- wp:group {"tagName":"section","anchor":"bisnis","className":"gka-section gka-topo","layout":{"type":"constrained","contentSize":"1240px"}} -->
<section id="bisnis" class="wp-block-group gka-section gka-topo">
<!-- wp:group {"className":"gka-head","layout":{"type":"default"}} -->
<div class="wp-block-group gka-head">
<!-- wp:group {"layout":{"type":"default"}} --><div class="wp-block-group"><!-- wp:paragraph {"className":"gka-eyebrow"} --><p class="gka-eyebrow"><?php echo gka_ct( 'bisnis_eyebrow' ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in gka_ct ?></p><!-- /wp:paragraph --><!-- wp:heading --><h2 class="wp-block-heading"><?php echo gka_ct( 'bisnis_title' ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in gka_ct ?></h2><!-- /wp:heading --></div><!-- /wp:group -->
<!-- wp:group {"className":"gka-head-side","layout":{"type":"default"}} --><div class="wp-block-group gka-head-side"><!-- wp:paragraph --><p><?php echo gka_ct( 'bisnis_text' ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in gka_ct ?></p><!-- /wp:paragraph --><!-- wp:paragraph {"className":"gka-more"} --><p class="gka-more"><a href="/bisnis-kami/"><?php echo esc_html( gka_c( 'bisnis_link' ) ); ?></a></p><!-- /wp:paragraph --></div><!-- /wp:group -->
</div><!-- /wp:group -->
<!-- wp:query {"queryId":11,"query":{"postType":"gka_bisnis","perPage":8,"order":"asc","orderBy":"menu_order","inherit":false},"className":"gka-index"} -->
<div class="wp-block-query gka-index"><!-- wp:post-template -->
<!-- wp:post-featured-image {"aspectRatio":"4/3","sizeSlug":"large"} /-->
<!-- wp:post-title {"level":3,"isLink":true} /-->
<!-- wp:post-excerpt {"excerptLength":14} /-->
<!-- /wp:post-template --></div>
<!-- /wp:query -->
</section>
<!-- /wp:group -->
