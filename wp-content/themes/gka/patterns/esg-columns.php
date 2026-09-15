<?php
/**
 * Title: ESG tiga kolom
 * Slug: gka/esg-columns
 * Categories: gka
 */
$cols = [
	[ 'kebijakan', 'Kebijakan', 'Dokumentasi kegiatan internal dan eksternal perusahaan.' ],
	[ 'piagam', 'Piagam', 'Penghargaan yang pernah diraih, beserta dokumentasi dan surat pendukung.' ],
	[ 'sertifikasi', 'Sertifikasi', 'Sertifikat yang dimiliki, dengan logo dan dokumen yang bisa diunduh.' ],
];
?>
<!-- wp:group {"tagName":"section","anchor":"esg","className":"gka-section gka-pt0","layout":{"type":"constrained","contentSize":"1240px"}} -->
<section id="esg" class="wp-block-group gka-section gka-pt0">
<!-- wp:group {"className":"gka-head","layout":{"type":"default"}} -->
<div class="wp-block-group gka-head">
<!-- wp:group {"layout":{"type":"default"}} --><div class="wp-block-group"><!-- wp:paragraph {"className":"gka-eyebrow"} --><p class="gka-eyebrow">ESG</p><!-- /wp:paragraph --><!-- wp:heading --><h2 class="wp-block-heading">Tanggung jawab yang <em class="is-accent">bisa diperiksa</em></h2><!-- /wp:heading --></div><!-- /wp:group -->
<!-- wp:group {"className":"gka-head-side","layout":{"type":"default"}} --><div class="wp-block-group gka-head-side"><!-- wp:paragraph --><p>Environmental, Social &amp; Governance: kebijakan, penghargaan, dan sertifikat yang dimiliki, lengkap dengan dokumennya.</p><!-- /wp:paragraph --><!-- wp:paragraph {"className":"gka-more"} --><p class="gka-more"><a href="/esg/">Buka halaman ESG</a></p><!-- /wp:paragraph --></div><!-- /wp:group -->
</div><!-- /wp:group -->
<!-- wp:html -->
<div class="gka-esg">
<?php foreach ( $cols as [ $slug, $title, $text ] ) : ?>
<a href="/esg/<?php echo esc_attr( $slug ); ?>/"><h3><?php echo esc_html( $title ); ?></h3><p><?php echo esc_html( $text ); ?></p></a>
<?php endforeach; ?>
</div>
<!-- /wp:html -->
</section>
<!-- /wp:group -->
