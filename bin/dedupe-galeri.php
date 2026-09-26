<?php
/**
 * Removes the gallery copies that older setup.sh runs created (seed.php re-created every item
 * fetch-assets.php had renamed). Keeps the oldest post of each title, which is the one carrying
 * the real pt-gka.com photo; images are shared and are not deleted.
 *
 * Preview:  docker compose run --rm -T cli wp eval-file /opt/gka-bin/dedupe-galeri.php
 * Delete:   docker compose run --rm -T cli wp eval-file /opt/gka-bin/dedupe-galeri.php apply
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit( "Run through WP-CLI.\n" );
}
$apply = in_array( 'apply', $args ?? [], true );
$posts = get_posts( [ 'post_type' => 'gka_galeri', 'post_status' => 'any', 'numberposts' => -1, 'orderby' => 'ID', 'order' => 'ASC' ] );

// Seed slug -> the title fetch-assets.php gives that item. A copy still under a seed slug belongs to that title.
$renames = [
	'sistem-kandang-modern-close-house' => 'Kandang close house dan menara tandon air', 'ayam-potong-sehat-produktif' => 'Deretan kandang di Kramatwatu',
	'pemantauan-air-nutrisi' => 'Tangga akses kandang bertingkat', 'area-pemuatan-pengiriman' => 'Lorong antar kandang',
	'disinfeksi-kendaraan-pengangkut' => 'Dinding kipas exhaust kandang tertutup', 'perawatan-kandang-harian' => 'Truk pengangkut di area kandang',
	'penimbangan-bersama-pembeli' => 'Pemuatan dari kandang lantai atas', 'pemberian-pakan-terjadwal' => 'Pemindahan keranjang ayam',
	'proses-panen-tepat-waktu' => 'Truk bermuatan keranjang panen', 'penerimaan-doc-day-old-chick' => 'Tempat pakan dan minum otomatis',
	'tim-operasional-peternakan' => 'Tim menangani ayam saat panen', 'kebersihan-area-kandang' => 'Ayam broiler di dalam kandang',
	'komitmen-higienitas-mutu' => 'Keranjang panen siap dikirim',
];
$seen = [];
$drop = [];
foreach ( $posts as $p ) { // oldest first, so the first post of each title is the original
	$base = preg_replace( '/-\d+$/', '', $p->post_name );
	$key  = strtolower( trim( $renames[ $base ] ?? $p->post_title ) );
	if ( isset( $seen[ $key ] ) ) {
		$drop[] = $p;
	} else {
		$seen[ $key ] = $p->ID;
	}
}
if ( ! $seen ) {
	WP_CLI::error( 'No gallery items found. Nothing deleted.' );
}
foreach ( $drop as $p ) {
	WP_CLI::log( sprintf( '%s #%d  %s  (%s)', $apply ? 'deleted' : 'would delete', $p->ID, $p->post_title, $p->post_name ) );
	if ( $apply ) {
		wp_delete_post( $p->ID, true );
	}
}
WP_CLI::success( sprintf( '%d gallery items, %d duplicates %s, %d kept.', count( $posts ), count( $drop ), $apply ? 'deleted' : 'found (run with "apply" to delete)', count( $posts ) - count( $drop ) ) );
