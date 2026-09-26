<?php
/**
 * Title: Lowongan aktif
 * Slug: gka/karir-list
 * Categories: gka
 */
?>
<!-- wp:group {"tagName":"section","anchor":"karir","className":"gka-section gka-pt0","layout":{"type":"constrained","contentSize":"1240px"}} -->
<section id="karir" class="wp-block-group gka-section gka-pt0">
<!-- wp:group {"className":"gka-head","layout":{"type":"default"}} -->
<div class="wp-block-group gka-head">
<!-- wp:group {"layout":{"type":"default"}} --><div class="wp-block-group"><!-- wp:paragraph {"className":"gka-eyebrow"} --><p class="gka-eyebrow"><?php echo gka_ct( 'karir_eyebrow' ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in gka_ct ?></p><!-- /wp:paragraph --><!-- wp:heading --><h2 class="wp-block-heading"><?php echo gka_ct( 'karir_title' ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in gka_ct ?></h2><!-- /wp:heading --></div><!-- /wp:group -->
<!-- wp:group {"className":"gka-head-side","layout":{"type":"default"}} --><div class="wp-block-group gka-head-side"><!-- wp:paragraph --><p><?php echo gka_ct( 'karir_text' ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in gka_ct ?></p><!-- /wp:paragraph --><!-- wp:paragraph {"className":"gka-more"} --><p class="gka-more"><a href="/karir/"><?php echo esc_html( gka_c( 'karir_link' ) ); ?></a></p><!-- /wp:paragraph --></div><!-- /wp:group -->
</div><!-- /wp:group -->
<!-- wp:pattern {"slug":"gka/job-grid"} /-->
</section>
<!-- /wp:group -->
