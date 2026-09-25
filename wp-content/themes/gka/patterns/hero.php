<?php
/**
 * Title: Hero beranda (slideshow sinematik)
 * Slug: gka/hero
 * Categories: gka
 * Inserter: no
 */
$slides = [
	[ gka_photo( 'hero', 'hero-close-house.webp' ), 'Kandang close house · Kramatwatu' ],
	[ gka_photo( 'feat-c', 'galeri-02.webp' ), 'Pakan terukur setiap hari' ],
	[ gka_photo( 'cta', 'lanskap-kandang.webp' ), 'Sanitasi & ventilasi terjaga' ],
	[ gka_photo( 'feat-a', 'galeri-03.webp' ), '25.000 ekor per lantai' ],
];
?>
<!-- wp:html -->
<section class="gka-hero" aria-label="Pembuka">
<div class="gka-hero-media" aria-hidden="true">
<?php foreach ( $slides as $i => [ $src, $cap ] ) : ?>
<img class="gka-hero-slide<?php echo 0 === $i ? ' is-active' : ''; ?>" src="<?php echo esc_url( $src ); ?>" alt="" <?php echo 0 === $i ? 'fetchpriority="high"' : 'loading="lazy"'; ?> data-caption="<?php echo esc_attr( $cap ); ?>" width="1920" height="1280">
<?php endforeach; ?>
<span class="gka-hero-shade"></span><span class="gka-grain"></span>
</div>
<div class="gka-hero-body">
<p class="gka-eyebrow gka-hero-eyebrow">PT Gemilang Karya Agri · Serang, Banten</p>
<h1 class="gka-hero-title">Ayam potong sehat dari kandang <em class="is-accent">close house</em></h1>
<div class="gka-hero-side">
<p class="gka-hero-lede">Kami membesarkan broiler di kandang tertutup bertingkat dengan suhu dan sanitasi terjaga, lalu menimbangnya bersama pembeli di lokasi.</p>
<div class="wp-block-buttons">
<div class="wp-block-button is-style-arrow"><a class="wp-block-button__link wp-element-button" href="/bisnis-kami/kemitraan-peternak/">Ajukan kemitraan</a></div>
<div class="wp-block-button is-style-outline gka-btn-ghost"><a class="wp-block-button__link wp-element-button" href="/tentang-kami/">Tentang perusahaan</a></div>
</div>
</div>
</div>
<div class="gka-hero-foot">
<div class="gka-hero-count" aria-hidden="true"><b class="gka-hero-idx">01</b><span>/ <?php echo esc_html( str_pad( (string) count( $slides ), 2, '0', STR_PAD_LEFT ) ); ?></span></div>
<div class="gka-hero-bars" aria-hidden="true"><?php foreach ( $slides as $i => $s ) : ?><i class="<?php echo 0 === $i ? 'is-active' : ''; ?>"></i><?php endforeach; ?></div>
<p class="gka-hero-cap" aria-hidden="true"><?php echo esc_html( $slides[0][1] ); ?></p>
<button type="button" class="gka-scroll-cue" aria-label="Gulir ke konten"><span>Gulir</span><i aria-hidden="true"></i></button>
</div>
</section>
<!-- /wp:html -->
