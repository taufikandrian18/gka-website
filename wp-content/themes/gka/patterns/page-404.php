<?php
/**
 * Title: Halaman 404
 * Slug: gka/page-404
 * Categories: gka
 * Inserter: no
 *
 * Copy comes from wp-admin → GKA Konten → Halaman 404. The zero of "404" is an egg drawn like the
 * GKA oval mark; it wobbles and follows the pointer a little (motion off under reduced motion).
 */
$links = [
	[ '/bisnis-kami/', 'Bisnis Kami', 'Budidaya broiler & kemitraan' ],
	[ '/produk-kami/', 'Produk Kami', 'Broiler, daging ayam, pakan' ],
	[ '/karir/', 'Karir', 'Lowongan aktif' ],
	[ '/publikasi/', 'Publikasi', 'Kabar & dokumentasi' ],
];
?>
<!-- wp:html -->
<section class="gka-404 gka-dark" aria-labelledby="gka-404-title">
<span class="gka-404-topo" aria-hidden="true"></span><span class="gka-grain" aria-hidden="true"></span>
<div class="gka-404-inner">
<div class="gka-404-num" aria-hidden="true">
<span class="gka-404-d">4</span>
<svg class="gka-404-egg" viewBox="0 0 200 250" focusable="false">
<path d="M100 8C150 8 192 104 192 156c0 52-41 86-92 86S8 208 8 156C8 104 50 8 100 8z" fill="#0F3D2B" stroke="currentColor" stroke-width="5"/>
<path d="M100 26c40 0 74 84 74 129 0 42-33 70-74 70s-74-28-74-70c0-45 34-129 74-129z" fill="none" stroke="currentColor" stroke-width="2"/>
<text x="100" y="170" text-anchor="middle" fill="currentColor">GKA</text>
</svg>
<span class="gka-404-d">4</span>
</div>
<div class="gka-404-copy">
<p class="gka-eyebrow gka-404-rise"><?php echo gka_ct( 'e404_eyebrow' ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in gka_ct ?></p>
<h1 id="gka-404-title" class="gka-404-title gka-404-rise"><?php echo gka_ct( 'e404_title' ); // phpcs:ignore ?></h1>
<p class="gka-404-lede gka-404-rise"><?php echo gka_ct( 'e404_lede' ); // phpcs:ignore ?></p>
<p class="gka-404-path gka-404-rise" hidden><span>Alamat yang dibuka</span><code></code></p>
<div class="wp-block-buttons gka-404-rise">
<div class="wp-block-button is-style-arrow"><a class="wp-block-button__link wp-element-button" href="/"><?php echo esc_html( gka_c( 'e404_home' ) ); ?></a></div>
<div class="wp-block-button is-style-outline gka-btn-ghost"><a class="wp-block-button__link wp-element-button" href="/hubungi-kami/"><?php echo esc_html( gka_c( 'e404_contact' ) ); ?></a></div>
</div>
</div>
<nav class="gka-404-links" aria-label="Tujuan populer">
<?php foreach ( $links as $i => [ $href, $title, $sub ] ) : ?>
<a class="gka-404-rise" href="<?php echo esc_attr( $href ); ?>" style="--d:<?php echo (int) $i; ?>"><span class="gka-404-no"><?php echo esc_html( str_pad( (string) ( $i + 1 ), 2, '0', STR_PAD_LEFT ) ); ?></span><strong><?php echo esc_html( $title ); ?></strong><small><?php echo esc_html( $sub ); ?></small><i aria-hidden="true">↗</i></a>
<?php endforeach; ?>
</nav>
</div>
</section>
<!-- /wp:html -->
