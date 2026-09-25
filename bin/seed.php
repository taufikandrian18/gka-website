<?php
/**
 * Idempotent demo content for PT Gemilang Karya Agri.
 * Run with WP-CLI:  wp eval-file bin/seed.php
 * Matches existing items by slug, so it is safe to run again.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit( "Run through WP-CLI: wp eval-file bin/seed.php\n" );
}
require_once ABSPATH . 'wp-admin/includes/image.php';

if ( ! defined( 'GKA_SEED_IMG' ) ) {
	define( 'GKA_SEED_IMG', get_theme_root() . '/gka/assets/images/' );
}

if ( ! function_exists( 'gka_seed_image' ) ) {
function gka_seed_image( string $file, string $alt ): int {
	$key      = $file . '|' . $alt;
	$existing = get_posts( [ 'post_type' => 'attachment', 'post_status' => 'inherit', 'meta_key' => '_gka_seed_file', 'meta_value' => $key, 'numberposts' => 1, 'fields' => 'ids' ] );
	if ( $existing ) {
		return (int) $existing[0];
	}
	$src = GKA_SEED_IMG . $file;
	if ( ! file_exists( $src ) ) {
		return 0;
	}
	$up   = wp_upload_dir();
	$dest = trailingslashit( $up['path'] ) . wp_unique_filename( $up['path'], 'gka-' . $file );
	copy( $src, $dest );
	$id = wp_insert_attachment( [ 'post_mime_type' => 'image/webp', 'post_title' => $alt, 'post_status' => 'inherit' ], $dest );
	if ( ! is_wp_error( $id ) ) {
		update_post_meta( $id, '_wp_attachment_image_alt', $alt );
		update_post_meta( $id, '_gka_seed_file', $key );
		$meta = @wp_generate_attachment_metadata( $id, $dest );
		if ( $meta ) {
			wp_update_attachment_metadata( $id, $meta );
		}
	}
	return (int) $id;
}
}

if ( ! function_exists( 'gka_seed' ) ) {
function gka_seed( string $type, string $title, array $args = [], string $image = '' ): int {
	$slug     = $args['post_name'] ?? sanitize_title( $title );
	$existing = get_page_by_path( $slug, OBJECT, $type );
	if ( $existing ) {
		$id = $existing->ID;
	} else {
		$id = (int) wp_insert_post( array_merge( [ 'post_type' => $type, 'post_title' => $title, 'post_name' => $slug, 'post_status' => 'publish' ], $args ) );
	}
	if ( $image && ! has_post_thumbnail( $id ) ) {
		$img = gka_seed_image( $image, $title );
		if ( $img ) {
			set_post_thumbnail( $id, $img );
		}
	}
	return $id;
}
}

$p  = fn( string $t ) => "<!-- wp:paragraph --><p>{$t}</p><!-- /wp:paragraph -->\n";
$h2 = fn( string $t ) => "<!-- wp:heading --><h2 class=\"wp-block-heading\">{$t}</h2><!-- /wp:heading -->\n";
$ul = fn( array $items ) => '<!-- wp:list --><ul class="wp-block-list">' . implode( '', array_map( fn( $i ) => "<!-- wp:list-item --><li>{$i}</li><!-- /wp:list-item -->", $items ) ) . "</ul><!-- /wp:list -->\n";
$note = fn( string $t ) => "<!-- wp:paragraph {\"className\":\"gka-note\"} --><p class=\"gka-note\">{$t}</p><!-- /wp:paragraph -->\n";

update_option( 'blogname', 'PT Gemilang Karya Agri' );
update_option( 'blogdescription', 'Peternakan ayam potong modern dengan sistem kandang close house' );
update_option( 'timezone_string', 'Asia/Jakarta' );
update_option( 'date_format', 'd/m/Y' );
update_option( 'permalink_structure', '/%postname%/' );
update_option( 'show_on_front', 'posts' );

/* ESG categories */
foreach ( [ 'kebijakan' => [ 'Kebijakan', 'Dokumentasi kegiatan internal dan eksternal perusahaan.' ], 'piagam' => [ 'Piagam', 'Penghargaan yang pernah diraih, termasuk dokumentasi dan surat pendukung.' ], 'sertifikasi' => [ 'Sertifikasi', 'Sertifikasi yang dimiliki, termasuk lampiran logo sertifikasi dan surat.' ] ] as $slug => [ $name, $desc ] ) {
	if ( ! term_exists( $slug, 'gka_esg_kategori' ) ) {
		wp_insert_term( $name, 'gka_esg_kategori', [ 'slug' => $slug, 'description' => $desc ] );
	}
}

/* Bisnis Kami. Breeding/Hatchery/Feeding are in the new sitemap but not on the old site: published with a visible note until the client confirms. */
gka_seed( 'gka_bisnis', 'Budidaya Broiler Close House', [ 'menu_order' => 1,
	'post_excerpt' => 'Kandang tertutup tiga lantai berkapasitas 25.000 ekor per lantai. Suhu, sirkulasi udara, pakan, dan air dipantau setiap hari.',
	'post_content' => $p( 'Kami memulai operasi pada 2016 dengan satu kandang close house tiga lantai. Setiap lantai menampung 25.000 ekor, sehingga kapasitas awal mencapai 75.000 ekor. Setahun kemudian kandang kedua dibangun dan kapasitas naik menjadi 150.000 ekor.' ) . $h2( 'Mengapa close house' ) . $p( 'Kandang tertutup membuat suhu, kelembapan, dan sirkulasi udara bisa diatur, bukan bergantung pada cuaca. Ayam tumbuh lebih seragam, risiko penyakit dari luar lebih kecil, dan panen bisa dijadwalkan dengan tepat.' ) . $ul( [ 'Suhu dan ventilasi terkontrol di setiap lantai', 'Pemberian pakan dan air minum terjadwal', 'Kebersihan area kandang dijaga harian', 'Setiap kendaraan disemprot disinfektan saat keluar-masuk' ] ),
], 'galeri-01.webp' );
gka_seed( 'gka_bisnis', 'Kemitraan Peternak', [ 'menu_order' => 2,
	'post_excerpt' => 'Program kerja sama bagi peternak yang ingin bermitra dengan standar kandang dan pendampingan tim lapangan kami.',
	'post_content' => $p( 'Peternak yang bermitra mendapat pendampingan dari Petugas Penyuluh Lapangan (PPL) kami, mulai dari persiapan kandang sampai panen dan penimbangan.' ) . $h2( 'Yang Anda dapatkan' ) . $ul( [ 'Standar kandang dan sanitasi yang sama dengan kandang kami', 'Kunjungan rutin dari tim lapangan', 'Penimbangan panen yang transparan', 'Jadwal pengambilan panen yang jelas' ] ) . $note( 'Syarat dan skema kemitraan akan diisi oleh tim GKA.' ),
], 'galeri-11.webp' );
foreach ( [ [ 'Breeding', 3, 'Pembibitan indukan untuk menjamin pasokan DOC berkualitas.', 'galeri-02.webp' ], [ 'Hatchery', 4, 'Penetasan telur menjadi DOC (day old chick) yang siap dikirim ke kandang.', 'galeri-10.webp' ], [ 'Feeding', 5, 'Pemberian pakan terjadwal dengan formulasi nutrisi sesuai umur ayam.', 'galeri-08.webp' ] ] as [ $t, $o, $ex, $img ] ) {
	gka_seed( 'gka_bisnis', $t, [ 'menu_order' => $o, 'post_excerpt' => $ex, 'post_content' => $note( 'Unit bisnis ini ada di sitemap baru. Isi detail menunggu konfirmasi dari PT Gemilang Karya Agri.' ) . $p( $ex ) ], $img );
}

/* Produk Kami */
gka_seed( 'gka_produk', 'Broiler', [ 'menu_order' => 1,
	'post_excerpt' => 'Ayam hidup siap panen dengan bobot seragam dari kandang close house.',
	'post_content' => $p( 'Broiler kami dibesarkan di kandang tertutup dengan pakan dan air terjadwal, lalu dipanen dengan peralatan modern agar prosesnya cepat dan ayam tetap bersih.' ) . $h2( 'Proses pembelian' ) . $ul( [ 'Hubungi tim kami untuk jadwal dan jumlah panen', 'Ayam ditimbang di lokasi dengan disaksikan pembeli', 'Kendaraan pengangkut disemprot disinfektan sebelum masuk dan keluar area', 'Ayam dikirim tepat waktu ke lokasi mitra' ] ) . $note( 'Rentang bobot dan ketersediaan per periode akan diisi oleh tim GKA.' ),
], 'galeri-02.webp' );
gka_seed( 'gka_produk', 'Daging Ayam', [ 'menu_order' => 2, 'post_excerpt' => 'Karkas dan potongan ayam segar.', 'post_content' => $note( 'Produk ini ada di sitemap baru. Detail menunggu konfirmasi dari PT Gemilang Karya Agri.' ) ], 'lanskap-kandang.webp' );
gka_seed( 'gka_produk', 'Pakan', [ 'menu_order' => 3, 'post_excerpt' => 'Pakan ternak dengan nutrisi terukur.', 'post_content' => $note( 'Produk ini ada di sitemap baru. Detail menunggu konfirmasi dari PT Gemilang Karya Agri.' ) ], 'galeri-08.webp' );

/* Karir – data from pt-gka.com/karir */
$jobs = [
	[ 'IT Support', 'Teknologi Informasi', '2026-10-20', [ 'Merawat perangkat komputer, jaringan, dan printer kantor', 'Membantu pengguna saat ada kendala sistem', 'Mencatat inventaris perangkat TI' ] ],
	[ 'Staff Pajak', 'Finance & Tax', '2026-10-20', [ 'Menyiapkan perhitungan dan pelaporan pajak bulanan', 'Rekonsiliasi dokumen pajak dengan pembukuan', 'Berkoordinasi dengan konsultan pajak' ] ],
	[ 'PPL (Petugas Penyuluh Lapangan)', 'Operasional & Penyuluhan', '2026-11-04', [ 'Mendampingi peternak mitra dari persiapan kandang hingga panen', 'Memantau kesehatan dan pertumbuhan ayam', 'Membuat laporan kunjungan lapangan' ] ],
];
foreach ( $jobs as [ $title, $dept, $deadline, $tasks ] ) {
	$id = gka_seed( 'gka_lowongan', $title, [ 'post_content' => $h2( 'Tanggung jawab' ) . $ul( $tasks ) . $note( 'Uraian contoh. Salin isi asli dari halaman karir di website lama.' ) ] );
	update_post_meta( $id, 'departemen', $dept );
	update_post_meta( $id, 'lokasi', 'Serang, Banten' );
	update_post_meta( $id, 'tipe', 'Full Time' );
	update_post_meta( $id, 'batas_lamaran', $deadline );
}

/* Galeri – 13 titles from pt-gka.com/galeri */
$galeri = [ 'Sistem Kandang Modern Close House', 'Ayam Potong Sehat & Produktif', 'Pemantauan Air & Nutrisi', 'Area Pemuatan & Pengiriman', 'Disinfeksi Kendaraan Pengangkut', 'Perawatan Kandang Harian', 'Penimbangan Bersama Pembeli', 'Pemberian Pakan Terjadwal', 'Proses Panen Tepat Waktu', 'Penerimaan DOC (Day Old Chick)', 'Tim Operasional Peternakan', 'Kebersihan Area Kandang', 'Komitmen Higienitas & Mutu' ];
foreach ( $galeri as $i => $title ) {
	gka_seed( 'gka_galeri', $title, [ 'menu_order' => $i + 1 ], sprintf( 'galeri-%02d.webp', $i + 1 ) );
}

/* Publikasi – clearly example posts */
$pubs = [
	[ 'Menerapkan biosekuriti ketat di setiap pintu kandang', 'Dari semprotan disinfektan kendaraan hingga kebersihan area kandang: begini kami menjaga kandang tetap aman.', 'galeri-05.webp', '2026-09-01' ],
	[ 'Kunjungan mitra peternak ke kandang close house', 'Calon mitra melihat langsung sistem kandang, pemberian pakan, dan proses penimbangan.', 'galeri-11.webp', '2026-08-12' ],
	[ 'Pelatihan rutin tim operasional peternakan', 'Penyegaran prosedur perawatan kandang harian dan pencatatan pertumbuhan ayam.', 'galeri-06.webp', '2026-07-20' ],
	[ 'Kenapa pembeli selalu hadir saat penimbangan', 'Penimbangan terbuka adalah cara paling sederhana untuk membangun kepercayaan.', 'galeri-07.webp', '2026-06-02' ],
];
foreach ( $pubs as [ $title, $ex, $img, $date ] ) {
	gka_seed( 'gka_publikasi', $title, [ 'post_excerpt' => $ex, 'post_date' => "$date 09:00:00", 'post_content' => $note( 'Artikel contoh untuk demo. Ganti dengan publikasi asli.' ) . $p( $ex ) ], $img );
}

/* ESG documents – example titles */
$docs = [
	'kebijakan'   => [ 'Kebijakan biosekuriti kandang', 'Kebijakan keselamatan dan kesehatan kerja (K3)', 'Dokumentasi kegiatan sosial masyarakat sekitar' ],
	'piagam'      => [ 'Contoh: Piagam penghargaan dinas peternakan', 'Contoh: Surat apresiasi mitra' ],
	'sertifikasi' => [ 'Contoh: Sertifikat Halal', 'Contoh: Nomor Kontrol Veteriner (NKV)' ],
];
foreach ( $docs as $cat => $titles ) {
	foreach ( $titles as $t ) {
		$id = gka_seed( 'gka_esg', $t, [ 'post_excerpt' => 'Dokumen PDF · lampiran diunggah oleh tim GKA', 'post_content' => $note( 'Judul contoh. Unggah dokumen asli sebagai lampiran PDF.' ) ] );
		wp_set_object_terms( $id, $cat, 'gka_esg_kategori' );
	}
}

/* Tim & Direksi – placeholders */
foreach ( [ [ 'Nama Direktur Utama', 'Direktur Utama' ], [ 'Nama Direktur', 'Direktur Operasional' ], [ 'Nama Komisaris', 'Komisaris' ], [ 'Nama Kepala Dokter Hewan', 'Kepala Dokter Hewan' ] ] as $i => [ $n, $r ] ) {
	$id = gka_seed( 'gka_tim', $n, [ 'post_name' => 'tim-' . ( $i + 1 ), 'menu_order' => $i + 1 ], $i % 2 ? 'lanskap-kandang.webp' : 'galeri-11.webp' );
	update_post_meta( $id, 'jabatan', $r );
}

/* Pages */
$about = gka_seed( 'page', 'About Us', [ 'post_name' => 'tentang-kami', 'post_excerpt' => 'PT Gemilang Karya Agri adalah anak perusahaan PT Gemilang Karya Mandiri yang bergerak di bidang peternakan ayam potong modern di Kramatwatu, Serang, Banten.', 'post_content' => '<!-- wp:pattern {"slug":"gka/page-about"} /-->' ] );
gka_seed( 'page', 'Our Team & Board', [ 'post_name' => 'tim-dan-direksi', 'post_parent' => $about, 'post_excerpt' => 'Jajaran direksi, manajemen, dan staf yang menjalankan operasi kandang setiap hari.', 'post_content' => '<!-- wp:pattern {"slug":"gka/page-team"} /-->' ] );
gka_seed( 'page', 'ESG', [ 'post_name' => 'esg', 'post_excerpt' => 'Kebijakan, penghargaan, dan sertifikasi kami, lengkap dengan dokumen yang bisa diunduh.', 'post_content' => '<!-- wp:pattern {"slug":"gka/page-esg"} /-->' ] );
gka_seed( 'page', 'Company Profile', [ 'post_name' => 'company-profile', 'post_excerpt' => 'Pratinjau buku company profile PT Gemilang Karya Agri — hanya bisa dilihat di halaman ini.', 'post_content' => '<!-- wp:pattern {"slug":"gka/page-book"} /-->' ] );
gka_seed( 'page', 'Hubungi Kami', [ 'post_name' => 'hubungi-kami', 'post_excerpt' => 'Untuk pembelian broiler, kemitraan peternak, atau informasi lainnya. Cara tercepat adalah WhatsApp.', 'post_content' => '<!-- wp:pattern {"slug":"gka/page-contact"} /-->' ] );

/* Contact Form 7 form, when the plugin is active */
if ( post_type_exists( 'wpcf7_contact_form' ) && ! get_page_by_path( 'formulir-kontak', OBJECT, 'wpcf7_contact_form' ) ) {
	$form_id = wp_insert_post( [ 'post_type' => 'wpcf7_contact_form', 'post_title' => 'Formulir kontak', 'post_name' => 'formulir-kontak', 'post_status' => 'publish' ] );
	update_post_meta( $form_id, '_form', "<label>Nama [text* nama autocomplete:name]</label>\n<label>Nomor WhatsApp [tel* telp autocomplete:tel]</label>\n<label>Email [email email autocomplete:email]</label>\n<label>Keperluan [select keperluan \"Pembelian broiler\" \"Kemitraan peternak\" \"Karir\" \"Lainnya\"]</label>\n<label>Pesan [textarea* pesan]</label>\n[submit \"Kirim pesan\"]" );
	update_post_meta( $form_id, '_mail', [ 'subject' => 'Pesan website: [keperluan] dari [nama]', 'sender' => '[_site_title] <wordpress@[_site_domain]>', 'recipient' => 'yanti@pt-gka.com', 'body' => "Nama: [nama]\nWhatsApp: [telp]\nEmail: [email]\nKeperluan: [keperluan]\n\n[pesan]", 'additional_headers' => 'Reply-To: [email]', 'attachments' => '', 'use_html' => 0, 'exclude_blank' => 0 ] );
	update_post_meta( $form_id, '_messages', [ 'mail_sent_ok' => 'Pesan terkirim. Tim kami akan menghubungi Anda.', 'mail_sent_ng' => 'Pesan gagal terkirim. Hubungi kami lewat WhatsApp.', 'validation_error' => 'Periksa kembali isian yang ditandai.', 'invalid_required' => 'Wajib diisi.' ] );
}

flush_rewrite_rules();
echo "seeded\n";
