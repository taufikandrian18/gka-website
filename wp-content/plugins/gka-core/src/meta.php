<?php
defined( 'ABSPATH' ) || exit;

function gka_core_date_id( string $ymd ): string {
	$ts = strtotime( $ymd );
	if ( ! $ts ) {
		return '';
	}
	$months = [ 1 => 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des' ];
	return gmdate( 'd', $ts ) . ' ' . $months[ (int) gmdate( 'n', $ts ) ] . ' ' . gmdate( 'Y', $ts );
}

add_action( 'init', function (): void {
	$string = [
		'type'              => 'string',
		'single'            => true,
		'show_in_rest'      => true,
		'sanitize_callback' => 'sanitize_text_field',
		'auth_callback'     => fn() => current_user_can( 'edit_posts' ),
	];
	foreach ( [ 'departemen', 'lokasi', 'tipe' ] as $key ) {
		register_post_meta( 'gka_lowongan', $key, $string );
	}
	register_post_meta( 'gka_lowongan', 'batas_lamaran', array_merge( $string, [
		'sanitize_callback' => fn( $v ) => preg_match( '/^\d{4}-\d{2}-\d{2}$/', (string) $v ) ? $v : '',
	] ) );
	register_post_meta( 'gka_tim', 'jabatan', $string );
	register_post_meta( 'gka_esg', 'keterangan', $string );

	if ( ! function_exists( 'register_block_bindings_source' ) ) {
		return;
	}
	register_block_bindings_source( 'gka/deadline', [
		'label'              => 'Batas lamaran',
		'uses_context'       => [ 'postId' ],
		'get_value_callback' => function ( array $args, $block ): string {
			$id  = (int) ( $block->context['postId'] ?? get_the_ID() );
			$raw = (string) get_post_meta( $id, 'batas_lamaran', true );
			return $raw ? 'Batas ' . gka_core_date_id( $raw ) : '';
		},
	] );
	register_block_bindings_source( 'gka/apply-mailto', [
		'label'              => 'Mailto lamaran',
		'uses_context'       => [ 'postId' ],
		'get_value_callback' => function ( array $args, $block ): string {
			$id = (int) ( $block->context['postId'] ?? get_the_ID() );
			return 'mailto:ita@pt-gka.com?subject=' . rawurlencode( 'Lamaran ' . get_the_title( $id ) );
		},
	] );
}, 20 );

/** Only show open jobs on the front end, soonest deadline first. */
add_action( 'pre_get_posts', function ( WP_Query $q ): void {
	if ( is_admin() || ( defined( 'REST_REQUEST' ) && REST_REQUEST ) ) {
		return;
	}
	if ( 'gka_lowongan' !== $q->get( 'post_type' ) || $q->is_singular() ) {
		return;
	}
	$q->set( 'meta_query', [ [ 'key' => 'batas_lamaran', 'value' => wp_date( 'Y-m-d' ), 'compare' => '>=', 'type' => 'DATE' ] ] );
	$q->set( 'meta_key', 'batas_lamaran' );
	$q->set( 'orderby', [ 'meta_value' => 'ASC', 'title' => 'ASC' ] );
} );

/** Query-loop blocks ignore pre_get_posts ordering for custom meta; enforce via query_loop_block_query_vars. */
add_filter( 'query_loop_block_query_vars', function ( array $vars ): array {
	if ( ( $vars['post_type'] ?? '' ) === 'gka_lowongan' ) {
		$vars['meta_query'] = [ [ 'key' => 'batas_lamaran', 'value' => wp_date( 'Y-m-d' ), 'compare' => '>=', 'type' => 'DATE' ] ];
		$vars['meta_key']   = 'batas_lamaran';
		$vars['orderby']    = [ 'meta_value' => 'ASC', 'title' => 'ASC' ];
	}
	return $vars;
} );
