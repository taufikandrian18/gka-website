<?php
/**
 * Title: ESG di atas lanskap
 * Slug: gka/esg-columns
 * Categories: gka
 */
$cols = [
	[ 'kebijakan', 'Kebijakan', 'Dokumentasi kegiatan internal dan eksternal perusahaan.' ],
	[ 'piagam', 'Piagam', 'Penghargaan yang pernah diraih, beserta dokumentasi dan surat pendukung.' ],
	[ 'sertifikasi', 'Sertifikasi', 'Sertifikat yang dimiliki, dengan logo dan dokumen yang bisa diunduh.' ],
];
?>
<!-- wp:html -->
<section id="esg" class="gka-esg-band gka-dark" aria-labelledby="gka-esg-title">
<div class="gka-esg-media" aria-hidden="true"><img src="<?php echo esc_url( gka_photo( 'lanskap', 'lanskap-kandang.webp' ) ); ?>" alt="" loading="lazy" width="1920" height="1080"><span class="gka-grain"></span></div>
<div class="gka-esg-inner">
<div class="gka-head">
<div><p class="gka-eyebrow">ESG · Environmental, Social &amp; Governance</p><h2 id="gka-esg-title" class="wp-block-heading">Tanggung jawab yang <em class="is-accent">bisa diperiksa</em></h2></div>
<div class="gka-head-side"><p>Kebijakan, penghargaan, dan sertifikat yang dimiliki, lengkap dengan dokumennya.</p><p class="gka-more"><a href="/esg/">Buka halaman ESG</a></p></div>
</div>
<div class="gka-esg">
<?php foreach ( $cols as $i => [ $slug, $title, $text ] ) : ?>
<a href="/esg/<?php echo esc_attr( $slug ); ?>/"><span class="gka-esg-no">0<?php echo (int) $i + 1; ?></span><h3><?php echo esc_html( $title ); ?></h3><p><?php echo esc_html( $text ); ?></p><span class="gka-esg-go" aria-hidden="true">↗</span></a>
<?php endforeach; ?>
</div>
</div>
</section>
<!-- /wp:html -->
