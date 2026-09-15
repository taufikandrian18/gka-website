<?php
defined( 'ABSPATH' ) || exit;

function gka_core_post_types(): array {
	return [
		'gka_bisnis'    => [ 'singular' => 'Bisnis', 'plural' => 'Bisnis Kami', 'slug' => 'bisnis-kami', 'archive' => true, 'icon' => 'dashicons-networking', 'hier' => true, 'public' => true ],
		'gka_produk'    => [ 'singular' => 'Produk', 'plural' => 'Produk Kami', 'slug' => 'produk-kami', 'archive' => true, 'icon' => 'dashicons-products', 'hier' => true, 'public' => true ],
		'gka_esg'       => [ 'singular' => 'Dokumen ESG', 'plural' => 'Dokumen ESG', 'slug' => 'esg/dokumen', 'archive' => false, 'icon' => 'dashicons-shield', 'hier' => false, 'public' => true ],
		'gka_publikasi' => [ 'singular' => 'Publikasi', 'plural' => 'Publikasi', 'slug' => 'publikasi', 'archive' => true, 'icon' => 'dashicons-media-document', 'hier' => false, 'public' => true ],
		'gka_galeri'    => [ 'singular' => 'Foto', 'plural' => 'Galeri', 'slug' => 'galeri', 'archive' => true, 'icon' => 'dashicons-format-gallery', 'hier' => false, 'public' => true ],
		'gka_lowongan'  => [ 'singular' => 'Lowongan', 'plural' => 'Karir', 'slug' => 'karir', 'archive' => true, 'icon' => 'dashicons-id', 'hier' => false, 'public' => true ],
		'gka_tim'       => [ 'singular' => 'Anggota Tim', 'plural' => 'Tim & Direksi', 'slug' => 'tim', 'archive' => false, 'icon' => 'dashicons-groups', 'hier' => false, 'public' => false ],
	];
}

function gka_core_register_content(): void {
	foreach ( gka_core_post_types() as $key => $c ) {
		register_post_type( $key, [
			'labels'             => [
				'name'          => $c['plural'],
				'singular_name' => $c['singular'],
				'add_new_item'  => sprintf( 'Tambah %s', $c['singular'] ),
				'edit_item'     => sprintf( 'Ubah %s', $c['singular'] ),
				'all_items'     => sprintf( 'Semua %s', $c['plural'] ),
			],
			'public'             => $c['public'],
			'publicly_queryable' => $c['public'],
			'show_ui'            => true,
			'show_in_rest'       => true,
			'rest_base'          => $key,
			'menu_icon'          => $c['icon'],
			'hierarchical'       => $c['hier'],
			'has_archive'        => $c['archive'] ? $c['slug'] : false,
			'rewrite'            => $c['public'] ? [ 'slug' => $c['slug'], 'with_front' => false ] : false,
			'supports'           => [ 'title', 'editor', 'thumbnail', 'excerpt', 'page-attributes', 'custom-fields', 'revisions' ],
		] );
	}

	register_taxonomy( 'gka_esg_kategori', [ 'gka_esg' ], [
		'labels'            => [ 'name' => 'Kategori ESG', 'singular_name' => 'Kategori ESG' ],
		'hierarchical'      => true,
		'public'            => true,
		'show_in_rest'      => true,
		'show_admin_column' => true,
		'rewrite'           => [ 'slug' => 'esg', 'with_front' => false ],
	] );
}
add_action( 'init', 'gka_core_register_content', 5 );
