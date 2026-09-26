<?php
/**
 * wp-admin screen "GKA Konten": one tab per group of fields from gka_content_schema().
 * Saving a tab only touches that tab's keys; an emptied field is removed so the default returns.
 */
defined( 'ABSPATH' ) || exit;

const GKA_CONTENT_CAP = 'edit_pages';

add_action( 'admin_menu', function (): void {
	$hook = add_menu_page( 'GKA Konten', 'GKA Konten', GKA_CONTENT_CAP, 'gka-konten', 'gka_content_screen', 'dashicons-edit-page', 3 );
	add_action( "admin_print_scripts-{$hook}", function (): void {
		wp_enqueue_media();
		wp_enqueue_script( 'gka-content-admin', plugins_url( 'assets/content-admin.js', GKA_CORE_DIR . '/gka-core.php' ), [ 'jquery' ], (string) filemtime( GKA_CORE_DIR . '/assets/content-admin.js' ), true );
	} );
} );

function gka_content_screen(): void {
	if ( ! current_user_can( GKA_CONTENT_CAP ) ) {
		return;
	}
	$schema = gka_content_schema();
	$tab    = sanitize_key( $_GET['tab'] ?? '' );
	$tab    = isset( $schema[ $tab ] ) ? $tab : array_key_first( $schema );
	$saved  = (array) get_option( 'gka_content', [] );
	[ $title, $fields ] = $schema[ $tab ];
	?>
	<div class="wrap gka-content">
		<h1>GKA Konten</h1>
		<p class="description">Teks, angka, tautan, dan foto yang tampil di situs. Desain tetap diatur tema; isian di sini tidak tertimpa saat situs diperbarui. <strong>Kosongkan isian untuk kembali ke teks bawaan</strong> (terlihat samar di dalam kolom).</p>
		<?php if ( isset( $_GET['updated'] ) ) : ?>
			<div class="notice notice-success is-dismissible"><p>Tersimpan. <a href="<?php echo esc_url( home_url( '/' ) ); ?>" target="_blank" rel="noopener">Lihat situs ↗</a></p></div>
		<?php endif; ?>
		<nav class="nav-tab-wrapper">
			<?php foreach ( $schema as $slug => [ $label ] ) : ?>
				<a class="nav-tab<?php echo $slug === $tab ? ' nav-tab-active' : ''; ?>" href="<?php echo esc_url( admin_url( 'admin.php?page=gka-konten&tab=' . $slug ) ); ?>"><?php echo esc_html( $label ); ?></a>
			<?php endforeach; ?>
		</nav>
		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<input type="hidden" name="action" value="gka_content_save">
			<input type="hidden" name="tab" value="<?php echo esc_attr( $tab ); ?>">
			<?php wp_nonce_field( 'gka_content_save_' . $tab ); ?>
			<table class="form-table" role="presentation"><tbody>
			<?php foreach ( $fields as $key => $field ) :
				$value = (string) ( $saved[ $key ] ?? '' );
				$id    = 'gka-' . $key;
				?>
				<tr>
					<th scope="row"><label for="<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $field['label'] ); ?></label></th>
					<td>
						<?php gka_content_input( $key, $field, $value, $id ); ?>
						<?php if ( $field['help'] ) : ?><p class="description"><?php echo esc_html( $field['help'] ); ?></p><?php endif; ?>
					</td>
				</tr>
			<?php endforeach; ?>
			</tbody></table>
			<?php submit_button( 'Simpan ' . $title ); ?>
		</form>
	</div>
	<style>
		.gka-content .form-table th{width:220px}
		.gka-content textarea{width:100%;max-width:640px}
		.gka-content input.regular-text{width:100%;max-width:640px}
		.gka-pick-preview{display:flex;flex-wrap:wrap;gap:8px;margin:0 0 8px}
		.gka-pick-preview img{height:72px;width:auto;border-radius:6px;border:1px solid #dcdcde;background:#f6f7f7}
	</style>
	<?php
}

function gka_content_input( string $key, array $field, string $value, string $id ): void {
	$name = 'gka[' . $key . ']';
	switch ( $field['type'] ) {
		case 'textarea':
			printf( '<textarea id="%s" name="%s" rows="3" placeholder="%s">%s</textarea>', esc_attr( $id ), esc_attr( $name ), esc_attr( $field['default'] ), esc_textarea( $value ) );
			return;
		case 'image':
		case 'images':
			$multiple = 'images' === $field['type'];
			$ids      = array_filter( array_map( 'absint', explode( ',', $value ) ) );
			echo '<div class="gka-pick" data-multiple="' . ( $multiple ? '1' : '0' ) . '">';
			echo '<div class="gka-pick-preview">';
			foreach ( $ids as $att ) {
				echo wp_get_attachment_image( $att, 'thumbnail' );
			}
			echo '</div>';
			printf( '<input type="hidden" id="%s" name="%s" value="%s">', esc_attr( $id ), esc_attr( $name ), esc_attr( implode( ',', $ids ) ) );
			printf( '<button type="button" class="button gka-pick-open">%s</button> ', $multiple ? 'Pilih logo' : 'Pilih foto' );
			printf( '<button type="button" class="button-link gka-pick-clear"%s>%s</button>', $ids ? '' : ' hidden', $multiple ? 'Hapus semua' : 'Pakai foto bawaan' );
			echo '</div>';
			return;
		default:
			printf( '<input type="text" class="regular-text" id="%s" name="%s" value="%s" placeholder="%s">', esc_attr( $id ), esc_attr( $name ), esc_attr( $value ), esc_attr( $field['default'] ) );
	}
}

add_action( 'admin_post_gka_content_save', function (): void {
	$schema = gka_content_schema();
	$tab    = sanitize_key( $_POST['tab'] ?? '' );
	if ( ! isset( $schema[ $tab ] ) || ! current_user_can( GKA_CONTENT_CAP ) ) {
		wp_die( 'Tidak diizinkan.', 403 );
	}
	check_admin_referer( 'gka_content_save_' . $tab );

	$input = wp_unslash( (array) ( $_POST['gka'] ?? [] ) );
	$saved = (array) get_option( 'gka_content', [] );
	foreach ( $schema[ $tab ][1] as $key => $field ) {
		$raw = (string) ( $input[ $key ] ?? '' );
		switch ( $field['type'] ) {
			case 'textarea':
				$clean = sanitize_textarea_field( $raw );
				break;
			case 'url':
				$clean = str_starts_with( trim( $raw ), '/' ) ? '/' . ltrim( sanitize_text_field( $raw ), '/' ) : esc_url_raw( trim( $raw ) );
				break;
			case 'image':
				$clean = (string) ( absint( $raw ) ?: '' );
				break;
			case 'images':
				$clean = implode( ',', array_filter( array_map( 'absint', explode( ',', $raw ) ) ) );
				break;
			default:
				$clean = sanitize_text_field( $raw );
		}
		// Unchanged defaults are not stored, so improving a default in code still reaches the site.
		if ( '' === $clean || $clean === $field['default'] ) {
			unset( $saved[ $key ] );
		} else {
			$saved[ $key ] = $clean;
		}
	}
	update_option( 'gka_content', $saved, true );
	wp_safe_redirect( admin_url( 'admin.php?page=gka-konten&tab=' . $tab . '&updated=1' ) );
	exit;
} );

/** Shortcut from the admin bar while viewing the site. */
add_action( 'admin_bar_menu', function ( WP_Admin_Bar $bar ): void {
	if ( ! is_admin() && current_user_can( GKA_CONTENT_CAP ) ) {
		$bar->add_node( [ 'id' => 'gka-konten', 'title' => 'Edit konten GKA', 'href' => admin_url( 'admin.php?page=gka-konten' ) ] );
	}
}, 80 );
