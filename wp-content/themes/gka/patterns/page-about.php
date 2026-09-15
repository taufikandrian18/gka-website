<?php
/**
 * Title: Halaman About Us
 * Slug: gka/page-about
 * Categories: gka
 */
$misi = [ 'Meningkatkan kualitas SDM dan teknologi peternakan modern', 'Meningkatkan produksi dengan menambah fasilitas kandang modern', 'Memperluas jangkauan pemasaran', 'Memberikan harga yang kompetitif bagi pelanggan' ];
$pilar = [ [ 'Kandang sehat & higienis', 'Suhu dan sanitasi terjaga dari kandang sampai ayam tiba di pembeli.' ], [ 'Panen lebih cepat', 'Sistem modern menghasilkan jumlah besar dengan proses panen yang cepat.' ], [ 'Penimbangan transparan', 'Pembeli ikut menyaksikan penimbangan, tidak ada selisih tersembunyi.' ], [ 'Lokasi strategis', 'Dekat jalur distribusi utama menuju Jawa Barat.' ] ];
?>
<!-- wp:image {"sizeSlug":"full","align":"full","className":"gka-band"} --><figure class="wp-block-image alignfull size-full gka-band"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/galeri-01.webp' ) ); ?>" alt="Kompleks kandang close house PT Gemilang Karya Agri"/></figure><!-- /wp:image -->
<!-- wp:group {"className":"gka-section","layout":{"type":"default"}} -->
<div class="wp-block-group gka-section">
<!-- wp:paragraph {"className":"gka-eyebrow"} --><p class="gka-eyebrow">Sejarah</p><!-- /wp:paragraph -->
<!-- wp:heading --><h2 class="wp-block-heading">Dari satu kandang ke <em class="is-accent">150.000 ekor</em></h2><!-- /wp:heading -->
<!-- wp:html -->
<ol class="gka-timeline"><li><b>2016</b><h3>Kandang pertama</h3><p>Operasi dimulai dengan satu kandang close house tiga lantai, masing-masing 25.000 ekor.</p></li><li><b>2017</b><h3>Kandang kedua</h3><p>Kandang kedua dibangun untuk menambah kapasitas produksi.</p></li><li><b>Kini</b><h3>150.000+ ekor</h3><p>Dua kandang modern melayani pembeli dan mitra di Banten dan Jawa Barat.</p></li></ol>
<!-- /wp:html -->
</div>
<!-- /wp:group -->
<!-- wp:columns {"className":"gka-vm"} -->
<div class="wp-block-columns gka-vm">
<!-- wp:column {"className":"gka-card is-dark"} --><div class="wp-block-column gka-card is-dark"><!-- wp:paragraph {"className":"gka-eyebrow"} --><p class="gka-eyebrow">Visi</p><!-- /wp:paragraph --><!-- wp:heading --><h2 class="wp-block-heading">Menjadi perusahaan peternakan ayam yang <em class="is-accent">unggul, produktif, dan terpercaya</em>.</h2><!-- /wp:heading --></div><!-- /wp:column -->
<!-- wp:column {"className":"gka-card"} --><div class="wp-block-column gka-card"><!-- wp:paragraph {"className":"gka-eyebrow"} --><p class="gka-eyebrow">Misi</p><!-- /wp:paragraph -->
<!-- wp:list {"className":"is-style-checks"} --><ul class="wp-block-list is-style-checks"><?php foreach ( $misi as $m ) : ?><!-- wp:list-item --><li><?php echo esc_html( $m ); ?></li><!-- /wp:list-item --><?php endforeach; ?></ul><!-- /wp:list --></div><!-- /wp:column -->
</div>
<!-- /wp:columns -->
<!-- wp:group {"className":"gka-section","layout":{"type":"default"}} -->
<div class="wp-block-group gka-section">
<!-- wp:paragraph {"className":"gka-eyebrow"} --><p class="gka-eyebrow">Keunggulan</p><!-- /wp:paragraph -->
<!-- wp:heading --><h2 class="wp-block-heading">Mengapa pembeli <em class="is-accent">kembali</em></h2><!-- /wp:heading -->
<!-- wp:html -->
<div class="gka-pillars"><?php foreach ( $pilar as [ $t, $d ] ) : ?><div><h3><?php echo esc_html( $t ); ?></h3><p><?php echo esc_html( $d ); ?></p></div><?php endforeach; ?></div>
<!-- /wp:html -->
<!-- wp:buttons --><div class="wp-block-buttons"><!-- wp:button {"className":"is-style-arrow gka-btn-dark"} --><div class="wp-block-button is-style-arrow gka-btn-dark"><a class="wp-block-button__link wp-element-button" href="/tentang-kami/tim-dan-direksi/">Kenali tim dan direksi</a></div><!-- /wp:button --></div><!-- /wp:buttons -->
</div>
<!-- /wp:group -->
<!-- wp:pattern {"slug":"gka/cta-contact"} /-->
