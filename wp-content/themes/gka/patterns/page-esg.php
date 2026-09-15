<?php
/**
 * Title: Halaman ESG
 * Slug: gka/page-esg
 * Categories: gka
 */
$cols = [
	[ 'kebijakan', 'Kebijakan', 'Dokumentasi kegiatan internal dan eksternal perusahaan.' ],
	[ 'piagam', 'Piagam', 'Penghargaan yang pernah diraih, termasuk dokumentasi dan surat pendukung.' ],
	[ 'sertifikasi', 'Sertifikasi', 'Sertifikasi yang dimiliki, termasuk lampiran logo sertifikasi dan surat.' ],
];
?>
<!-- wp:html -->
<div class="gka-esg-cards"><?php foreach ( $cols as [ $slug, $title, $text ] ) : ?><a class="gka-esg-card" href="/esg/<?php echo esc_attr( $slug ); ?>/"><h2><?php echo esc_html( $title ); ?></h2><p><?php echo esc_html( $text ); ?></p><span class="gka-more-inline">Buka</span></a><?php endforeach; ?></div>
<!-- /wp:html -->
<!-- wp:group {"className":"gka-section","layout":{"type":"default"}} -->
<div class="wp-block-group gka-section">
<!-- wp:media-text {"mediaType":"image","className":"gka-feature","verticalAlignment":"center"} -->
<div class="wp-block-media-text is-stacked-on-mobile is-vertically-aligned-center gka-feature"><figure class="wp-block-media-text__media"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/galeri-13.webp' ) ); ?>" alt="Komitmen higienitas dan mutu"/></figure><div class="wp-block-media-text__content">
<!-- wp:paragraph {"className":"gka-eyebrow"} --><p class="gka-eyebrow">Komitmen</p><!-- /wp:paragraph -->
<!-- wp:heading {"fontSize":"2xl"} --><h2 class="wp-block-heading has-2-xl-font-size">Higienis dari kandang sampai ke tangan pembeli</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>Biosekuriti kendaraan, kebersihan kandang harian, dan penimbangan terbuka adalah praktik yang kami dokumentasikan di halaman ini.</p><!-- /wp:paragraph -->
</div></div>
<!-- /wp:media-text -->
</div>
<!-- /wp:group -->
