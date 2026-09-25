<?php
defined( 'ABSPATH' ) || exit;

add_action( 'after_setup_theme', function (): void {
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'editor-styles' );
	add_editor_style( 'assets/css/blocks.css' );
} );

/**
 * Cache-busting version for a theme asset: file modification time, so every deploy
 * that actually changes a file changes its ?ver= and browsers refetch it.
 */
function gka_asset_ver( string $rel ): string {
	$path = get_theme_file_path( $rel );
	$mtime = file_exists( $path ) ? filemtime( $path ) : 0;
	return $mtime ? (string) $mtime : (string) wp_get_theme()->get( 'Version' );
}

add_action( 'wp_enqueue_scripts', function (): void {
	wp_enqueue_style( 'gka-blocks', get_theme_file_uri( 'assets/css/blocks.css' ), [], gka_asset_ver( 'assets/css/blocks.css' ) );
	wp_enqueue_script( 'gka-view', get_theme_file_uri( 'assets/js/view.js' ), [], gka_asset_ver( 'assets/js/view.js' ), [ 'strategy' => 'defer', 'in_footer' => true ] );
	wp_add_inline_script( 'gka-view', 'window.GKA=' . wp_json_encode( [ 'home' => gka_base_path() . '/', 'theme' => trailingslashit( get_theme_file_uri() ) ] ) . ';', 'before' );
	// Motion layer: GSAP 3 + ScrollTrigger (standard no-charge licence) and Lenis smooth scroll, vendored.
	$defer = [ 'strategy' => 'defer', 'in_footer' => true ];
	wp_enqueue_script( 'gka-gsap', get_theme_file_uri( 'assets/vendor/gsap.min.js' ), [], '3.15.0', $defer );
	wp_enqueue_script( 'gka-scrolltrigger', get_theme_file_uri( 'assets/vendor/ScrollTrigger.min.js' ), [ 'gka-gsap' ], '3.15.0', $defer );
	wp_enqueue_script( 'gka-lenis', get_theme_file_uri( 'assets/vendor/lenis.min.js' ), [], '1.3.26', $defer );
	wp_enqueue_script( 'gka-motion', get_theme_file_uri( 'assets/js/motion.js' ), [ 'gka-scrolltrigger', 'gka-lenis', 'gka-view' ], gka_asset_ver( 'assets/js/motion.js' ), $defer );
} );

/**
 * Intro curtain on the homepage, once per browser session. The flag is set in <head> before first
 * paint so the page never flashes underneath; motion.js plays it out, and CSS hides it after 3.2s
 * if scripts fail.
 */
add_action( 'wp_head', function (): void {
	if ( ! is_front_page() ) {
		return;
	}
	echo "<script>try{if(!sessionStorage.getItem('gka-intro')&&!matchMedia('(prefers-reduced-motion: reduce)').matches)document.documentElement.classList.add('gka-preload')}catch(e){}</script>\n";
}, 0 );
add_action( 'wp_body_open', function (): void {
	if ( ! is_front_page() ) {
		return;
	}
	printf( '<div class="gka-loader" aria-hidden="true"><div class="gka-loader-in"><img src="%s" alt="" width="84" height="54"><b>000</b></div></div>', esc_url( get_theme_file_uri( 'assets/brand/gka-mark.svg' ) ) );
} );

add_action( 'wp_head', function (): void {
	printf( '<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>' . "\n", esc_url( get_theme_file_uri( 'assets/fonts/plus-jakarta-sans-600.woff2' ) ) );
}, 1 );

add_action( 'init', function (): void {
	register_block_pattern_category( 'gka', [ 'label' => __( 'GKA', 'gka' ) ] );
	register_block_style( 'core/heading', [ 'name' => 'on-forest', 'label' => __( 'Di atas hijau', 'gka' ) ] );
	register_block_style( 'core/list', [ 'name' => 'plain', 'label' => __( 'Tanpa bullet', 'gka' ) ] );
	register_block_style( 'core/list', [ 'name' => 'checks', 'label' => __( 'Centang', 'gka' ) ] );
	register_block_style( 'core/button', [ 'name' => 'arrow', 'label' => __( 'Dengan panah', 'gka' ) ] );
} );

/**
 * Path WordPress is served under: '' at a domain root, '/gka' at website.taufikandrian.my.id/gka/.
 */
function gka_base_path(): string {
	return rtrim( (string) wp_parse_url( home_url(), PHP_URL_PATH ), '/' );
}

/**
 * Templates, patterns, menus and seeded content link with root-relative paths ("/karir/",
 * "/wp-content/themes/..."). When the site lives in a subdirectory, prefix them at render time so
 * the same markup works at a domain root and under /gka/. Links that already carry the base path,
 * protocol-relative URLs ("//cdn") and absolute URLs are left alone.
 */
add_filter( 'render_block', function ( string $html ): string {
	$base = gka_base_path();
	if ( '' === $base || ! str_contains( $html, '="/' ) ) {
		return $html;
	}
	return (string) preg_replace_callback(
		'#\b(href|src|action)=(["\'])(/(?!/)[^"\']*)#',
		static function ( array $m ) use ( $base ): string {
			$path = $m[3];
			if ( $path === $base || str_starts_with( $path, $base . '/' ) || str_starts_with( $path, $base . '?' ) ) {
				return $m[0];
			}
			return $m[1] . '=' . $m[2] . $base . $path;
		},
		$html
	);
} );

/** Indonesian labels for the core mobile menu buttons (site has no translation packs installed). */
add_filter( 'render_block_core/navigation', function ( string $html ): string {
	return strtr( $html, [
		'aria-label="Open menu"'  => 'aria-label="Buka menu"',
		'aria-label="Close menu"' => 'aria-label="Tutup menu"',
		'aria-label="Menu"'       => 'aria-label="Menu utama"',
	] );
} );



/** [gka_breadcrumbs] – dynamic trail for page heads. */
add_shortcode( 'gka_breadcrumbs', function (): string {
	$trail = [ [ 'Beranda', home_url( '/' ) ] ];
	$labels = [ 'gka_bisnis' => 'Bisnis Kami', 'gka_produk' => 'Produk Kami', 'gka_publikasi' => 'Publikasi', 'gka_galeri' => 'Galeri', 'gka_lowongan' => 'Karir', 'gka_esg' => 'ESG' ];
	if ( is_singular() ) {
		$post = get_queried_object();
		if ( 'page' === $post->post_type ) {
			foreach ( array_reverse( get_post_ancestors( $post ) ) as $anc ) {
				$trail[] = [ get_the_title( $anc ), get_permalink( $anc ) ];
			}
		} elseif ( 'gka_esg' === $post->post_type ) {
			$trail[] = [ 'ESG', home_url( '/esg/' ) ];
			$terms = get_the_terms( $post, 'gka_esg_kategori' );
			if ( $terms && ! is_wp_error( $terms ) ) {
				$trail[] = [ $terms[0]->name, get_term_link( $terms[0] ) ];
			}
		} elseif ( isset( $labels[ $post->post_type ] ) ) {
			$trail[] = [ $labels[ $post->post_type ], get_post_type_archive_link( $post->post_type ) ];
		}
		$trail[] = [ get_the_title( $post ), '' ];
	} elseif ( is_post_type_archive() ) {
		$pt = get_query_var( 'post_type' );
		$pt = is_array( $pt ) ? reset( $pt ) : $pt;
		if ( 'gka_galeri' === $pt ) {
			$trail[] = [ 'Publikasi', home_url( '/publikasi/' ) ];
		}
		$trail[] = [ $labels[ $pt ] ?? post_type_archive_title( '', false ), '' ];
	} elseif ( is_tax( 'gka_esg_kategori' ) ) {
		$trail[] = [ 'ESG', home_url( '/esg/' ) ];
		$trail[] = [ single_term_title( '', false ), '' ];
	} elseif ( is_404() ) {
		$trail[] = [ '404', '' ];
	}
	$items = '';
	foreach ( $trail as $i => [ $label, $url ] ) {
		$items .= ( $url && $i < count( $trail ) - 1 )
			? sprintf( '<li><a href="%s">%s</a></li>', esc_url( $url ), esc_html( $label ) )
			: sprintf( '<li aria-current="page">%s</li>', esc_html( $label ) );
	}
	return '<nav class="gka-crumbs" aria-label="Breadcrumb"><ol>' . $items . '</ol></nav>';
} );

/** [gka_page_intro] – the page excerpt as lede, when a page has one. */
add_shortcode( 'gka_lede', function (): string {
	if ( is_post_type_archive() ) {
		$map = [
			'gka_bisnis'    => 'Dari budidaya broiler di kandang close house hingga kemitraan dengan peternak di sekitar Serang.',
			'gka_produk'    => 'Setiap produk punya halaman dengan spesifikasi, proses pembelian, dan dokumen pendukung.',
			'gka_publikasi' => 'Dokumentasi kegiatan internal dan eksternal perusahaan.',
			'gka_galeri'    => 'Kumpulan dokumentasi fasilitas kandang, proses produksi, dan kegiatan operasional.',
			'gka_lowongan'  => 'Lowongan aktif di Serang, Banten. Tidak menemukan posisi yang cocok? Kirim CV ke ita@pt-gka.com.',
		];
		$pt = get_query_var( 'post_type' );
		$pt = is_array( $pt ) ? reset( $pt ) : $pt;
		return isset( $map[ $pt ] ) ? '<p class="gka-lede">' . esc_html( $map[ $pt ] ) . '</p>' : '';
	}
	if ( is_tax() ) {
		$d = term_description();
		return $d ? '<div class="gka-lede">' . wp_kses_post( $d ) . '</div>' : '';
	}
	if ( is_singular() && has_excerpt() ) {
		return '<p class="gka-lede">' . esc_html( get_the_excerpt() ) . '</p>';
	}
	return '';
} );

/** Eyebrow label for single/archive heads. */
add_shortcode( 'gka_eyebrow', function ( $atts ): string {
	$atts = shortcode_atts( [ 'text' => '' ], $atts );
	$text = $atts['text'];
	if ( ! $text && is_singular( 'gka_lowongan' ) ) {
		$text = (string) get_post_meta( get_the_ID(), 'departemen', true );
	}
	return $text ? '<p class="gka-eyebrow">' . esc_html( $text ) . '</p>' : '';
} );

/** [gka_esg_tabs] – category switcher on ESG pages. */
add_shortcode( 'gka_esg_tabs', function (): string {
	$terms = get_terms( [ 'taxonomy' => 'gka_esg_kategori', 'hide_empty' => false, 'orderby' => 'term_order' ] );
	if ( ! $terms || is_wp_error( $terms ) ) {
		return '';
	}
	$order = [ 'kebijakan' => 1, 'piagam' => 2, 'sertifikasi' => 3 ];
	usort( $terms, fn( $a, $b ) => ( $order[ $a->slug ] ?? 9 ) <=> ( $order[ $b->slug ] ?? 9 ) );
	$current = is_tax( 'gka_esg_kategori' ) ? get_queried_object_id() : 0;
	$out = '';
	foreach ( $terms as $t ) {
		$out .= sprintf( '<a href="%s"%s>%s</a>', esc_url( get_term_link( $t ) ), $t->term_id === $current ? ' aria-current="page"' : '', esc_html( $t->name ) );
	}
	return '<nav class="gka-tabs" aria-label="Kategori ESG">' . $out . '</nav>';
} );

/** [gka_contact_form] – Contact Form 7 form when installed, otherwise direct contact buttons. */
add_shortcode( 'gka_contact_form', function (): string {
	$form = get_page_by_path( 'formulir-kontak', OBJECT, 'wpcf7_contact_form' );
	if ( shortcode_exists( 'contact-form-7' ) && $form ) {
		return do_shortcode( sprintf( '[contact-form-7 id="%d" html_class="gka-cf7"]', $form->ID ) );
	}
	return '<p>Formulir sedang disiapkan. Hubungi kami langsung lewat <a href="https://wa.me/6287771491004">WhatsApp</a> atau <a href="mailto:yanti@pt-gka.com">email</a>.</p>';
} );

/** Real photos fetched by bin/fetch-assets.php live in uploads/gka-photos; fall back to theme placeholders. */
function gka_photo( string $slot, string $fallback ): string {
	// 1. Curated demo photos shipped with the theme. 2. Photos pulled from pt-gka.com. 3. Placeholder.
	if ( file_exists( get_theme_file_path( "assets/photos/{$slot}.webp" ) ) ) {
		return get_theme_file_uri( "assets/photos/{$slot}.webp" );
	}
	static $up = null;
	$up = $up ?? wp_upload_dir();
	foreach ( [ 'webp', 'jpg' ] as $ext ) {
		if ( file_exists( "{$up['basedir']}/gka-photos/{$slot}.{$ext}" ) ) {
			return "{$up['baseurl']}/gka-photos/{$slot}.{$ext}";
		}
	}
	return get_theme_file_uri( 'assets/images/' . $fallback );
}

add_action( 'wp_head', function (): void {
	printf( '<link rel="icon" type="image/svg+xml" href="%s">' . "\n", esc_url( get_theme_file_uri( 'assets/brand/gka-mark.svg' ) ) );
	echo '<meta name="theme-color" content="#08140F">' . "\n";
}, 2 );
