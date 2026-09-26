<?php
/**
 * Title: Produk kami (kartu)
 * Slug: gka/produk-cards
 * Categories: gka
 */
?>
<!-- wp:group {"tagName":"section","anchor":"produk","className":"gka-section","layout":{"type":"constrained","contentSize":"1240px"}} -->
<section id="produk" class="wp-block-group gka-section">
<!-- wp:group {"className":"gka-head","layout":{"type":"default"}} -->
<div class="wp-block-group gka-head">
<!-- wp:group {"layout":{"type":"default"}} --><div class="wp-block-group"><!-- wp:paragraph {"className":"gka-eyebrow"} --><p class="gka-eyebrow"><?php echo gka_ct( 'produk_eyebrow' ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in gka_ct ?></p><!-- /wp:paragraph --><!-- wp:heading --><h2 class="wp-block-heading"><?php echo gka_ct( 'produk_title' ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in gka_ct ?></h2><!-- /wp:heading --></div><!-- /wp:group -->
<!-- wp:group {"className":"gka-head-side","layout":{"type":"default"}} --><div class="wp-block-group gka-head-side"><!-- wp:paragraph {"className":"gka-more"} --><p class="gka-more"><a href="/produk-kami/"><?php echo esc_html( gka_c( 'produk_link' ) ); ?></a></p><!-- /wp:paragraph --></div><!-- /wp:group -->
</div><!-- /wp:group -->
<!-- wp:pattern {"slug":"gka/produk-grid"} /-->
</section>
<!-- /wp:group -->
