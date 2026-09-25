<?php
/**
 * Title: Proses dari kandang ke pembeli
 * Slug: gka/process
 * Categories: gka
 */
$steps = [
	[ 'Kandang modern', 'DOC masuk ke kandang close house dengan suhu, air, dan pakan yang dipantau harian.', 'step1', 'Tempat pakan dan minum otomatis di kandang close house' ],
	[ 'Proses panen', 'Peralatan modern mempercepat panen dan menjaga ayam tetap tenang dan bersih.', 'step2', 'Tim menangani ayam saat panen' ],
	[ 'Penimbangan', 'Ayam ditimbang di lokasi bersama pembeli, jadi angka yang tercatat adalah angka yang disaksikan.', 'step3', 'Pemuatan keranjang ayam dari kandang' ],
	[ 'Pengantaran', 'Setiap kendaraan disemprot disinfektan saat keluar-masuk, lalu dikirim tepat waktu ke mitra.', 'step4', 'Truk pengangkut bermuatan keranjang panen' ],
];
?>
<!-- wp:group {"tagName":"section","anchor":"proses","align":"full","className":"gka-section gka-process","layout":{"type":"constrained","contentSize":"1240px"}} -->
<section id="proses" class="wp-block-group alignfull gka-section gka-process">
<!-- wp:group {"className":"gka-head","layout":{"type":"default"}} -->
<div class="wp-block-group gka-head">
<!-- wp:group {"layout":{"type":"default"}} --><div class="wp-block-group"><!-- wp:paragraph {"className":"gka-eyebrow"} --><p class="gka-eyebrow">Dari kandang ke pembeli</p><!-- /wp:paragraph --><!-- wp:heading --><h2 class="wp-block-heading">Empat tahap, <em class="is-accent">satu timbangan</em> yang sama</h2><!-- /wp:heading --></div><!-- /wp:group -->
<!-- wp:group {"className":"gka-head-side","layout":{"type":"default"}} --><div class="wp-block-group gka-head-side"><!-- wp:paragraph --><p>Urutan kerja kami dari kandang sampai ayam tiba di tangan mitra. Setiap tahap terdokumentasi dan bisa disaksikan pembeli.</p><!-- /wp:paragraph --><!-- wp:paragraph {"className":"gka-more"} --><p class="gka-more"><a href="/bisnis-kami/budidaya-broiler-close-house/">Pelajari budidaya broiler</a></p><!-- /wp:paragraph --></div><!-- /wp:group -->
</div><!-- /wp:group -->
<!-- wp:html -->
<div class="gka-rail" aria-hidden="true"><span></span><span></span><span></span><span></span></div>
<ol class="gka-steps">
<?php foreach ( $steps as $n => [ $title, $text, $img, $alt ] ) : ?>
<li><img src="<?php echo esc_url( gka_photo( $img, [ 'step1' => 'galeri-06.webp', 'step2' => 'galeri-09.webp', 'step3' => 'galeri-07.webp', 'step4' => 'galeri-05.webp' ][ $img ] ) ); ?>" alt="<?php echo esc_attr( $alt ); ?>" loading="lazy" width="1200" height="900"><span class="gka-step-no">TAHAP <?php echo (int) $n + 1; ?></span><h3><?php echo esc_html( $title ); ?></h3><p><?php echo esc_html( $text ); ?></p></li>
<?php endforeach; ?>
</ol>
<!-- /wp:html -->
<!-- wp:group {"className":"gka-scale","layout":{"type":"flex","flexWrap":"wrap","verticalAlignment":"center"}} -->
<div class="wp-block-group gka-scale">
<!-- wp:paragraph {"className":"gka-readout"} --><p class="gka-readout">25.000<small>EKOR / LANTAI</small></p><!-- /wp:paragraph -->
<!-- wp:paragraph {"className":"gka-scale-text"} --><p class="gka-scale-text">Penimbangan terbuka adalah janji layanan kami: pembeli hadir, melihat angka yang sama, dan tidak ada selisih yang disembunyikan.</p><!-- /wp:paragraph -->
<!-- wp:buttons --><div class="wp-block-buttons"><!-- wp:button {"className":"is-style-arrow"} --><div class="wp-block-button is-style-arrow"><a class="wp-block-button__link wp-element-button" href="/hubungi-kami/">Jadwalkan panen</a></div><!-- /wp:button --></div><!-- /wp:buttons -->
</div><!-- /wp:group -->
</section>
<!-- /wp:group -->
