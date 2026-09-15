<?php
defined( 'ABSPATH' ) || exit;

function gka_core_legacy_redirects(): array {
	return [
		'tentang' => '/tentang-kami/',
		'layanan' => '/bisnis-kami/',
		'kontak'  => '/hubungi-kami/',
	];
}

add_action( 'template_redirect', function (): void {
	if ( ! is_404() ) {
		return;
	}
	$path = trim( (string) wp_parse_url( $_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH ), '/' );
	$map  = gka_core_legacy_redirects();
	if ( isset( $map[ $path ] ) ) {
		wp_safe_redirect( home_url( $map[ $path ] ), 301 );
		exit;
	}
	if ( preg_match( '#^karir-detail/\d+$#', $path ) ) {
		wp_safe_redirect( home_url( '/karir/' ), 301 );
		exit;
	}
}, 1 );

/** Demo sites must not be indexed. Set GKA_NOINDEX to false in wp-config.php on the production domain. */
add_filter( 'wp_robots', function ( array $robots ): array {
	if ( ! defined( 'GKA_NOINDEX' ) || GKA_NOINDEX ) {
		$robots['noindex']  = true;
		$robots['nofollow'] = true;
	}
	return $robots;
} );
