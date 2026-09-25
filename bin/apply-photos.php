<?php
/**
 * Sets featured images for marketing content from the curated photos shipped in the theme
 * (assets/photos/feat-*.webp). Galeri keeps the real pt-gka.com documentation photos.
 * Run: docker compose run --rm -T cli wp eval-file /opt/gka-bin/apply-photos.php
 * Safe to re-run; never replaces an image chosen manually in wp-admin.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit( "Run through WP-CLI.\n" );
}
require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/media.php';
require_once ABSPATH . 'wp-admin/includes/image.php';

$dir = get_theme_file_path( 'assets/photos/' );

$map = [
	[ 'gka_bisnis', 'budidaya-broiler-close-house', 'feat-a', 'Broiler di kandang close house dengan tempat pakan otomatis' ],
	[ 'gka_bisnis', 'kemitraan-peternak', 'feat-d', 'Peternak di dalam kandang' ],
	[ 'gka_bisnis', 'breeding', 'feat-e', 'Ayam indukan' ],
	[ 'gka_bisnis', 'hatchery', 'feat-a', 'Kandang broiler modern' ],
	[ 'gka_bisnis', 'feeding', 'feat-c', 'Ayam makan dari tempat pakan' ],
	[ 'gka_produk', 'broiler', 'feat-b', 'Ayam broiler siap panen' ],
	[ 'gka_produk', 'daging-ayam', 'feat-a', 'Broiler di kandang' ],
	[ 'gka_produk', 'pakan', 'feat-c', 'Pakan ternak' ],
	[ 'gka_publikasi', 'menerapkan-biosekuriti-ketat-di-setiap-pintu-kandang', 'feat-a', 'Interior kandang yang bersih' ],
	[ 'gka_publikasi', 'kunjungan-mitra-peternak-ke-kandang-close-house', 'feat-d', 'Kunjungan ke kandang' ],
	[ 'gka_publikasi', 'pelatihan-rutin-tim-operasional-peternakan', 'feat-b', 'Tim operasional' ],
];

$cache = [];
foreach ( $map as [ $type, $slug, $file, $alt ] ) {
	$post = get_page_by_path( $slug, OBJECT, $type );
	$src  = $dir . $file . '.webp';
	if ( ! $post || ! file_exists( $src ) ) {
		continue;
	}
	$thumb  = get_post_thumbnail_id( $post );
	$is_ours = ! $thumb || get_post_meta( $thumb, '_gka_seed_file', true ) || get_post_meta( $thumb, '_gka_real_file', true ) || get_post_meta( $thumb, '_gka_curated', true );
	if ( ! $is_ours ) {
		continue;
	}
	$key = $file . '|' . $alt;
	if ( ! isset( $cache[ $key ] ) ) {
		$found = get_posts( [ 'post_type' => 'attachment', 'post_status' => 'inherit', 'meta_key' => '_gka_curated', 'meta_value' => $key, 'numberposts' => 1, 'fields' => 'ids' ] );
		if ( $found ) {
			$cache[ $key ] = (int) $found[0];
		} else {
			$tmp = wp_tempnam( $file . '.webp' );
			copy( $src, $tmp );
			$id = media_handle_sideload( [ 'name' => 'gka-' . $file . '.webp', 'tmp_name' => $tmp ], 0, $alt );
			if ( is_wp_error( $id ) ) {
				WP_CLI::warning( "$slug: " . $id->get_error_message() );
				continue;
			}
			update_post_meta( $id, '_wp_attachment_image_alt', $alt );
			update_post_meta( $id, '_gka_curated', $key );
			$cache[ $key ] = (int) $id;
		}
	}
	set_post_thumbnail( $post, $cache[ $key ] );
}
WP_CLI::success( 'Curated photos applied.' );
