<?php
/**
 * Title: Pernyataan tentang kami + angka
 * Slug: gka/statement
 * Categories: gka
 */
$stats = [ [ '150000', '+', 'ekor kapasitas produksi' ], [ '25000', '', 'ekor per lantai kandang' ], [ '2', '', 'kandang close house bertingkat' ], [ '2016', '', 'mulai beroperasi' ] ];
?>
<!-- wp:group {"tagName":"section","anchor":"tentang","className":"gka-section gka-dark gka-intro-sec","layout":{"type":"constrained","contentSize":"1240px"}} -->
<section id="tentang" class="wp-block-group gka-section gka-dark gka-intro-sec">
<!-- wp:paragraph {"className":"gka-eyebrow"} --><p class="gka-eyebrow">Tentang kami</p><!-- /wp:paragraph -->
<!-- wp:paragraph {"className":"gka-statement gka-scrub"} --><p class="gka-statement gka-scrub">Sejak 2016, anak perusahaan PT Gemilang Karya Mandiri ini membesarkan ayam potong <img class="gka-pill" src="<?php echo esc_url( gka_photo( 'pill', 'pill-broiler.webp' ) ); ?>" alt="" width="84" height="40"> di kandang modern bertingkat, dengan satu tujuan: menjadi peternakan ayam yang <em class="is-accent">produktif dan dipercaya pembeli.</em></p><!-- /wp:paragraph -->
<!-- wp:html -->
<dl class="gka-counters">
<?php foreach ( $stats as [ $n, $suffix, $label ] ) : ?>
<div><dt><?php echo esc_html( $label ); ?></dt><dd><span class="gka-count" data-to="<?php echo esc_attr( $n ); ?>"><?php echo esc_html( '2016' === $n || '2' === $n ? $n : number_format( (int) $n, 0, ',', '.' ) ); ?></span><?php echo esc_html( $suffix ); ?></dd></div>
<?php endforeach; ?>
</dl>
<!-- /wp:html -->
<!-- wp:group {"className":"gka-meta","layout":{"type":"flex","flexWrap":"wrap"}} -->
<div class="wp-block-group gka-meta"><!-- wp:paragraph --><p><strong>Lokasi</strong> · Kramatwatu, Serang, Banten</p><!-- /wp:paragraph --><!-- wp:paragraph --><p><strong>Sistem</strong> · Close house, 3 lantai per kandang</p><!-- /wp:paragraph --><!-- wp:paragraph {"className":"gka-more"} --><p class="gka-more"><a href="/tentang-kami/">Baca profil perusahaan</a></p><!-- /wp:paragraph --></div>
<!-- /wp:group -->
</section>
<!-- /wp:group -->
