<?php
/**
 * View-only company profile book.
 * Page images live OUTSIDE the web root and are streamed by PHP after a nonce + same-origin check,
 * so there is no public image URL and no PDF on the server. A determined visitor can still
 * screenshot the screen — this stops casual saving, not a screen capture.
 */
defined( 'ABSPATH' ) || exit;

function gka_book_dir(): string {
	foreach ( [ '/opt/gka-bin/book/webp', GKA_CORE_DIR . '/book/webp', ABSPATH . 'bin/book/webp' ] as $dir ) {
		if ( is_dir( $dir ) ) {
			return $dir;
		}
	}
	return '';
}

function gka_book_count(): int {
	$dir = gka_book_dir();
	if ( ! $dir ) {
		return 0;
	}
	$count = count( glob( $dir . '/page-*.webp' ) ?: [] );
	return $count;
}

add_action( 'init', function (): void {
	add_rewrite_rule( '^gka-book/page/([0-9]{1,3})/?$', 'index.php?gka_book_page=$matches[1]', 'top' );
} );
add_filter( 'query_vars', function ( array $vars ): array {
	$vars[] = 'gka_book_page';
	return $vars;
} );

add_action( 'template_redirect', function (): void {
	$n = (int) get_query_var( 'gka_book_page' );
	if ( $n < 1 ) {
		return;
	}
	$deny = function ( string $why ) {
		status_header( 403 );
		header( 'Content-Type: text/plain; charset=utf-8' );
		echo esc_html( $why );
		exit;
	};
	if ( ! isset( $_GET['t'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['t'] ) ), 'gka_book' ) ) {
		$deny( 'Halaman buku hanya bisa dibuka dari pratinjau di website.' );
	}
	// Same-origin only. Read the raw header: wp_get_referer() hides off-site referers.
	$ref = isset( $_SERVER['HTTP_REFERER'] ) ? wp_parse_url( sanitize_text_field( wp_unslash( $_SERVER['HTTP_REFERER'] ) ), PHP_URL_HOST ) : '';
	if ( $ref && $ref !== wp_parse_url( home_url(), PHP_URL_HOST ) ) {
		$deny( 'Sumber permintaan tidak dikenal.' );
	}
	$file = sprintf( '%s/page-%02d.webp', gka_book_dir(), $n );
	if ( ! gka_book_dir() || ! file_exists( $file ) ) {
		status_header( 404 );
		exit;
	}
	nocache_headers();
	header( 'Content-Type: image/webp' );
	header( 'Content-Length: ' . filesize( $file ) );
	header( 'Content-Disposition: inline' );
	header( 'Cache-Control: private, no-store, max-age=0' );
	header( 'X-Robots-Tag: noindex, noimageindex, nofollow' );
	header( 'X-Content-Type-Options: nosniff' );
	readfile( $file );
	exit;
}, 0 );

add_shortcode( 'gka_book', function ( $atts ): string {
	$count = gka_book_count();
	if ( ! $count ) {
		return '<p class="gka-note">Pratinjau buku sedang disiapkan.</p>';
	}
	wp_enqueue_script( 'gka-book', get_theme_file_uri( 'assets/js/book.js' ), [], function_exists( 'gka_asset_ver' ) ? gka_asset_ver( 'assets/js/book.js' ) : (string) time(), [ 'strategy' => 'defer', 'in_footer' => true ] );
	$token = wp_create_nonce( 'gka_book' );
	$base  = esc_url( home_url( '/gka-book/page/' ) );
	ob_start();
	?>
	<div class="gka-book" data-count="<?php echo (int) $count; ?>" data-base="<?php echo $base; ?>" data-token="<?php echo esc_attr( $token ); ?>" data-ratio="1.4143">
		<div class="gka-book-stage">
			<div class="gka-book-spread" aria-live="polite">
				<figure class="gka-book-leaf" data-side="left"><canvas></canvas></figure>
				<figure class="gka-book-leaf" data-side="right"><canvas></canvas></figure>
			</div>
			<p class="gka-book-loading">Memuat pratinjau…</p>
			<noscript><p class="gka-note">Aktifkan JavaScript untuk melihat pratinjau buku.</p></noscript>
		</div>
		<div class="gka-book-bar">
			<button type="button" class="gka-book-btn" data-book="prev" aria-label="Halaman sebelumnya">‹</button>
			<span class="gka-book-count"><b data-book="pos">1</b> / <?php echo (int) $count; ?></span>
			<button type="button" class="gka-book-btn" data-book="next" aria-label="Halaman berikutnya">›</button>
			<button type="button" class="gka-book-btn gka-book-wide" data-book="full">Layar penuh</button>
		</div>
		<p class="gka-book-hint">Gunakan tombol panah atau geser untuk membalik halaman. Pratinjau saja — berkas cetak tidak tersedia untuk diunduh.</p>
	</div>
	<?php
	return (string) ob_get_clean();
} );
