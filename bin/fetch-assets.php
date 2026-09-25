<?php
/**
 * Pulls the real photos from https://pt-gka.com (requested by PT GKA for the demo),
 * makes web-sized copies for theme slots, and swaps seed placeholders in the media library.
 * Run: docker compose run --rm cli wp eval-file /opt/gka-bin/fetch-assets.php
 * Safe to re-run. Never replaces an image someone set manually in wp-admin.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit( "Run through WP-CLI.\n" );
}
require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/media.php';
require_once ABSPATH . 'wp-admin/includes/image.php';

$src = 'https://pt-gka.com/uploads/';
$files = [
	'hero-1'   => 'carousel/hero-1-1600w.webp',
	'hero-2'   => 'carousel/hero-2-1600w.webp',
	'hero-3'   => 'carousel/hero-3-1600w.webp',
	'about'    => 'tentang/tentang-about-1024w.webp',
	'higienis' => 'layanan/service-higienis.jpg',
	'modern'   => 'layanan/service-modern.jpg',
	'goodsvc'  => 'layanan/service-goodservices.jpg',
];
for ( $i = 1; $i <= 13; $i++ ) {
	$files[ sprintf( 'g%02d', $i ) ] = sprintf( 'galeri/galeri-%02d.jpg', $i );
}

/* 1. Download originals once. */
$up   = wp_upload_dir();
$orig = $up['basedir'] . '/gka-photos/original';
wp_mkdir_p( $orig );
$local = [];
foreach ( $files as $key => $path ) {
	$ext  = pathinfo( $path, PATHINFO_EXTENSION );
	$dest = "$orig/$key.$ext";
	if ( ! file_exists( $dest ) ) {
		$tmp = download_url( $src . $path, 60 );
		if ( is_wp_error( $tmp ) ) {
			WP_CLI::warning( "download failed: $path – " . $tmp->get_error_message() );
			continue;
		}
		rename( $tmp, $dest );
	}
	$local[ $key ] = $dest;
}
WP_CLI::log( count( $local ) . ' originals ready' );

/* 2. Theme slots: resized copies used directly by the patterns. */
$slots = [
	'hero'   => [ 'g05', 2000 ], 'pill' => [ 'g12', 240 ], 'bisnis' => [ 'g01', 1200 ],
	'step1'  => [ 'g10', 900 ],  'step2' => [ 'g11', 900 ], 'step3' => [ 'g08', 900 ], 'step4' => [ 'goodsvc', 900 ],
	'cta'    => [ 'g02', 2000 ], 'about' => [ 'hero-1', 2000 ], 'team' => [ 'g11', 1200 ], 'esg' => [ 'higienis', 1200 ],
];
foreach ( $slots as $slot => [ $key, $width ] ) {
	if ( empty( $local[ $key ] ) ) {
		continue;
	}
	$ed = wp_get_image_editor( $local[ $key ] );
	if ( is_wp_error( $ed ) ) {
		WP_CLI::warning( "editor: $slot – " . $ed->get_error_message() );
		continue;
	}
	$ed->resize( $width, null, false );
	$ed->set_quality( 78 );
	$out = $ed->save( $up['basedir'] . "/gka-photos/$slot.webp", 'image/webp' );
	if ( is_wp_error( $out ) ) {
		$ed->save( $up['basedir'] . "/gka-photos/$slot.jpg", 'image/jpeg' );
	}
}
WP_CLI::log( 'theme slots written' );

/* 3. Media library: replace placeholder featured images. */
function gka_fa_attach( string $file, string $title ): int {
	$found = get_posts( [ 'post_type' => 'attachment', 'post_status' => 'inherit', 'meta_key' => '_gka_real_file', 'meta_value' => basename( $file ) . '|' . $title, 'numberposts' => 1, 'fields' => 'ids' ] );
	if ( $found ) {
		return (int) $found[0];
	}
	$tmp = wp_tempnam( basename( $file ) );
	copy( $file, $tmp );
	$id = media_handle_sideload( [ 'name' => 'gka-' . sanitize_file_name( $title ) . '.' . pathinfo( $file, PATHINFO_EXTENSION ), 'tmp_name' => $tmp ], 0, $title );
	if ( is_wp_error( $id ) ) {
		WP_CLI::warning( "sideload $title – " . $id->get_error_message() );
		return 0;
	}
	update_post_meta( $id, '_wp_attachment_image_alt', $title );
	update_post_meta( $id, '_gka_real_file', basename( $file ) . '|' . $title );
	return (int) $id;
}
function gka_fa_set( string $type, string $slug, string $file, string $alt ): void {
	$post = get_page_by_path( $slug, OBJECT, $type );
	if ( ! $post || ! $file ) {
		return;
	}
	$thumb = get_post_thumbnail_id( $post );
	if ( $thumb && ! get_post_meta( $thumb, '_gka_seed_file', true ) && ! get_post_meta( $thumb, '_gka_real_file', true ) ) {
		return; // chosen by a person in wp-admin: keep it
	}
	$id = gka_fa_attach( $file, $alt );
	if ( $id ) {
		set_post_thumbnail( $post, $id );
	}
}
$L = fn( $k ) => $local[ $k ] ?? '';

$galeri = [
	1 => 'Kandang close house dan menara tandon air', 2 => 'Deretan kandang di Kramatwatu', 3 => 'Tangga akses kandang bertingkat',
	4 => 'Lorong antar kandang', 5 => 'Dinding kipas exhaust kandang tertutup', 6 => 'Truk pengangkut di area kandang',
	7 => 'Pemuatan dari kandang lantai atas', 8 => 'Pemindahan keranjang ayam', 9 => 'Truk bermuatan keranjang panen',
	10 => 'Tempat pakan dan minum otomatis', 11 => 'Tim menangani ayam saat panen', 12 => 'Ayam broiler di dalam kandang',
	13 => 'Keranjang panen siap dikirim',
];
$old = [ 'sistem-kandang-modern-close-house', 'ayam-potong-sehat-produktif', 'pemantauan-air-nutrisi', 'area-pemuatan-pengiriman', 'disinfeksi-kendaraan-pengangkut', 'perawatan-kandang-harian', 'penimbangan-bersama-pembeli', 'pemberian-pakan-terjadwal', 'proses-panen-tepat-waktu', 'penerimaan-doc-day-old-chick', 'tim-operasional-peternakan', 'kebersihan-area-kandang', 'komitmen-higienitas-mutu' ];
foreach ( $galeri as $n => $title ) {
	$post = get_page_by_path( $old[ $n - 1 ], OBJECT, 'gka_galeri' ) ?: get_page_by_path( sanitize_title( $title ), OBJECT, 'gka_galeri' );
	if ( ! $post ) {
		continue;
	}
	if ( $post->post_name === $old[ $n - 1 ] ) {
		wp_update_post( [ 'ID' => $post->ID, 'post_title' => $title, 'post_name' => sanitize_title( $title ) ] );
	}
	gka_fa_set( 'gka_galeri', sanitize_title( $title ), $L( sprintf( 'g%02d', $n ) ), $title );
}

$map = [
	[ 'gka_bisnis', 'budidaya-broiler-close-house', 'g05', 'Kandang close house PT Gemilang Karya Agri' ],
	[ 'gka_bisnis', 'kemitraan-peternak', 'g11', 'Tim lapangan bersama peternak' ],
	[ 'gka_bisnis', 'breeding', 'g12', 'Ayam broiler di kandang' ],
	[ 'gka_bisnis', 'hatchery', 'hero-3', 'Interior kandang dengan tempat pakan' ],
	[ 'gka_bisnis', 'feeding', 'g10', 'Tempat pakan dan minum otomatis' ],
	[ 'gka_produk', 'broiler', 'g12', 'Ayam broiler siap panen' ],
	[ 'gka_produk', 'daging-ayam', 'modern', 'Ayam di dalam kandang modern' ],
	[ 'gka_produk', 'pakan', 'g10', 'Sistem pemberian pakan' ],
	[ 'gka_publikasi', 'menerapkan-biosekuriti-ketat-di-setiap-pintu-kandang', 'higienis', 'Kendaraan di area kandang' ],
	[ 'gka_publikasi', 'kunjungan-mitra-peternak-ke-kandang-close-house', 'g11', 'Kunjungan ke kandang' ],
	[ 'gka_publikasi', 'pelatihan-rutin-tim-operasional-peternakan', 'modern', 'Interior kandang' ],
	[ 'gka_publikasi', 'kenapa-pembeli-selalu-hadir-saat-penimbangan', 'g09', 'Truk bermuatan keranjang panen' ],
	[ 'gka_tim', 'tim-1', 'g11', 'Foto tim' ], [ 'gka_tim', 'tim-2', 'g08', 'Foto tim' ], [ 'gka_tim', 'tim-3', 'g07', 'Foto tim' ], [ 'gka_tim', 'tim-4', 'g13', 'Foto tim' ],
];
foreach ( $map as [ $type, $slug, $key, $alt ] ) {
	gka_fa_set( $type, $slug, $L( $key ), $alt );
}
WP_CLI::success( 'Real photos applied. Reload the site.' );
