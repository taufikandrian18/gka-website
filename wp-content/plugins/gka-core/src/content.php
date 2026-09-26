<?php
/**
 * Editable site copy: one option (gka_content) holding the words, numbers, links and photos the
 * theme's homepage, contact details and archive intros print. The design stays in the theme; every
 * field falls back to its default below, so an empty field means "use the original text".
 *
 * Text conventions for editors: *kata* = italic accent word; {email}, {email_karir}, {telepon},
 * {whatsapp} = the contact details from the Kontak tab.
 */
defined( 'ABSPATH' ) || exit;

/** Field definitions per tab: key => [ type, label, default, help ]. Types: text, textarea, url, image, images. */
function gka_content_schema(): array {
	static $schema = null;
	if ( null !== $schema ) {
		return $schema;
	}
	$f = fn( string $type, string $label, string $default = '', string $help = '' ) => compact( 'type', 'label', 'default', 'help' );
	$acc = 'Tulis *kata* untuk huruf miring berwarna aksen.';
	$img = 'Kosong = foto bawaan tema.';

	$hero = [
		'hero_eyebrow' => $f( 'text', 'Label kecil di atas judul', 'PT Gemilang Karya Agri · Serang, Banten' ),
		'hero_title'   => $f( 'text', 'Judul utama', 'Ayam potong sehat dari kandang *close house*', $acc ),
		'hero_lede'    => $f( 'textarea', 'Paragraf pembuka', 'Kami membesarkan broiler di kandang tertutup bertingkat dengan suhu dan sanitasi terjaga, lalu menimbangnya bersama pembeli di lokasi.' ),
		'hero_btn1'    => $f( 'text', 'Tombol utama · teks', 'Ajukan kemitraan' ),
		'hero_btn1_url' => $f( 'url', 'Tombol utama · tautan', '/bisnis-kami/kemitraan-peternak/' ),
		'hero_btn2'    => $f( 'text', 'Tombol kedua · teks', 'Tentang perusahaan' ),
		'hero_btn2_url' => $f( 'url', 'Tombol kedua · tautan', '/tentang-kami/' ),
	];
	$caps = [ 'Kandang close house · Kramatwatu', 'Pakan terukur setiap hari', 'Sanitasi & ventilasi terjaga', '25.000 ekor per lantai', '' ];
	foreach ( $caps as $i => $cap ) {
		$n = $i + 1;
		$hero[ "hero_slide{$n}_img" ] = $f( 'image', "Slide {$n} · foto", '', '' === $cap ? 'Slide tambahan: tampil hanya jika fotonya diisi.' : $img );
		$hero[ "hero_slide{$n}_cap" ] = $f( 'text', "Slide {$n} · keterangan", $cap );
	}

	$about = [
		'about_eyebrow' => $f( 'text', 'Label', 'Tentang kami' ),
		'about_text'    => $f( 'textarea', 'Pernyataan besar', 'Sejak 2016, anak perusahaan PT Gemilang Karya Mandiri ini membesarkan ayam potong [foto] di kandang modern bertingkat, dengan satu tujuan: menjadi peternakan ayam yang *produktif dan dipercaya pembeli.*', $acc . ' [foto] = foto kecil berbentuk kapsul di tengah kalimat.' ),
	];
	foreach ( [ [ '150.000', '+', 'ekor kapasitas produksi' ], [ '25.000', '', 'ekor per lantai kandang' ], [ '2', '', 'kandang close house bertingkat' ], [ '2016', '', 'mulai beroperasi' ] ] as $i => [ $num, $suf, $lab ] ) {
		$n = $i + 1;
		$about[ "stat{$n}_num" ]   = $f( 'text', "Angka {$n}", $num, 1 === $n ? 'Angka dihitung naik saat terlihat. Pakai titik untuk ribuan.' : '' );
		$about[ "stat{$n}_suf" ]   = $f( 'text', "Angka {$n} · akhiran", $suf );
		$about[ "stat{$n}_label" ] = $f( 'text', "Angka {$n} · keterangan", $lab );
	}
	$about += [
		'about_lokasi'   => $f( 'text', 'Baris info · Lokasi', 'Kramatwatu, Serang, Banten' ),
		'about_sistem'   => $f( 'text', 'Baris info · Sistem', 'Close house, 3 lantai per kandang' ),
		'about_link'     => $f( 'text', 'Tautan · teks', 'Baca profil perusahaan' ),
		'about_link_url' => $f( 'url', 'Tautan · alamat', '/tentang-kami/' ),
	];

	$proses = [
		'proses_eyebrow'  => $f( 'text', 'Label', 'Dari kandang ke pembeli' ),
		'proses_title'    => $f( 'text', 'Judul', 'Empat tahap, *satu timbangan* yang sama', $acc ),
		'proses_text'     => $f( 'textarea', 'Paragraf samping', 'Urutan kerja kami dari kandang sampai ayam tiba di tangan mitra. Setiap tahap terdokumentasi dan bisa disaksikan pembeli.' ),
		'proses_link'     => $f( 'text', 'Tautan · teks', 'Pelajari budidaya broiler' ),
		'proses_link_url' => $f( 'url', 'Tautan · alamat', '/bisnis-kami/budidaya-broiler-close-house/' ),
	];
	$steps = [
		[ 'Kandang modern', 'DOC masuk ke kandang close house dengan suhu, air, dan pakan yang dipantau harian.' ],
		[ 'Proses panen', 'Peralatan modern mempercepat panen dan menjaga ayam tetap tenang dan bersih.' ],
		[ 'Penimbangan', 'Ayam ditimbang di lokasi bersama pembeli, jadi angka yang tercatat adalah angka yang disaksikan.' ],
		[ 'Pengantaran', 'Setiap kendaraan disemprot disinfektan saat keluar-masuk, lalu dikirim tepat waktu ke mitra.' ],
	];
	foreach ( $steps as $i => [ $t, $d ] ) {
		$n = $i + 1;
		$proses[ "step{$n}_title" ] = $f( 'text', "Tahap {$n} · judul", $t );
		$proses[ "step{$n}_text" ]  = $f( 'textarea', "Tahap {$n} · teks", $d );
		$proses[ "step{$n}_img" ]   = $f( 'image', "Tahap {$n} · foto", '', 4 === $n ? 'Kosong = panel warna aksen tanpa foto.' : $img );
	}
	$proses += [
		'proses_readout'      => $f( 'text', 'Angka penutup', '25.000' ),
		'proses_readout_unit' => $f( 'text', 'Angka penutup · satuan', 'Ekor / lantai' ),
		'proses_scale'        => $f( 'textarea', 'Teks penutup', 'Penimbangan terbuka adalah janji layanan kami: pembeli hadir, melihat angka yang sama, dan tidak ada selisih yang disembunyikan.' ),
		'proses_btn'          => $f( 'text', 'Tombol · teks', 'Jadwalkan panen' ),
		'proses_btn_url'      => $f( 'url', 'Tombol · tautan', '/hubungi-kami/' ),
	];

	$sections = [
		'bisnis'    => [ 'Bisnis kami', 'Rantai usaha *unggas* yang saling terhubung', 'Dari kandang close house hingga kemitraan dengan peternak di sekitar Serang.', 'Lihat semua bisnis' ],
		'produk'    => [ 'Produk kami', 'Produk yang lahir dari *kandang bersih*', '', 'Lihat semua produk' ],
		'galeri'    => [ 'Galeri', 'Perjalanan kami *dalam gambar*', '', 'Semua foto' ],
		'publikasi' => [ 'Publikasi', 'Kabar terbaru', '', 'Semua publikasi' ],
		'karir'     => [ 'Karir', 'Tumbuh bersama *tim kandang* kami', 'Lowongan aktif saat ini. Kirim CV umum ke {email_karir}.', 'Lihat semua lowongan' ],
	];
	$heads = [];
	foreach ( $sections as $k => [ $eb, $title, $text, $link ] ) {
		$heads[ "{$k}_eyebrow" ] = $f( 'text', ucfirst( $k ) . ' · label', $eb );
		$heads[ "{$k}_title" ]   = $f( 'text', ucfirst( $k ) . ' · judul', $title, $acc );
		if ( '' !== $text ) {
			$heads[ "{$k}_text" ] = $f( 'textarea', ucfirst( $k ) . ' · paragraf', $text, 'karir' === $k ? '{email_karir} = email karir dari tab Kontak.' : '' );
		}
		$heads[ "{$k}_link" ] = $f( 'text', ucfirst( $k ) . ' · tautan', $link );
	}

	$esg = [
		'esg_eyebrow' => $f( 'text', 'Label', 'ESG · Environmental, Social & Governance' ),
		'esg_title'   => $f( 'text', 'Judul', 'Tanggung jawab yang *bisa diperiksa*', $acc ),
		'esg_text'    => $f( 'textarea', 'Paragraf samping', 'Kebijakan, penghargaan, dan sertifikat yang dimiliki, lengkap dengan dokumennya.' ),
		'esg_link'    => $f( 'text', 'Tautan · teks', 'Buka halaman ESG' ),
		'esg_bg'      => $f( 'image', 'Foto latar', '', 'Kosong = foto lanskap bawaan.' ),
	];
	foreach ( [ [ 'Kebijakan', 'Dokumentasi kegiatan internal dan eksternal perusahaan.' ], [ 'Piagam', 'Penghargaan yang pernah diraih, beserta dokumentasi dan surat pendukung.' ], [ 'Sertifikasi', 'Sertifikat yang dimiliki, dengan logo dan dokumen yang bisa diunduh.' ] ] as $i => [ $t, $d ] ) {
		$n = $i + 1;
		$esg[ "esg{$n}_title" ] = $f( 'text', "Kartu {$n} · judul", $t );
		$esg[ "esg{$n}_text" ]  = $f( 'textarea', "Kartu {$n} · teks", $d );
	}

	$cta = [
		'cta_eyebrow' => $f( 'text', 'Label', 'Hubungi kami' ),
		'cta_title'   => $f( 'text', 'Judul', 'Butuh pasokan broiler atau ingin *bermitra?*', $acc ),
		'cta_text'    => $f( 'textarea', 'Paragraf', 'Tim kami membalas di jam kerja. Ceritakan kebutuhan Anda, kami atur jadwal kunjungan atau panen.' ),
		'cta_wa'      => $f( 'text', 'Tombol WhatsApp · teks', 'Chat WhatsApp' ),
		'cta_words'   => $f( 'text', 'Teks berjalan', 'Broiler / Close house / Kemitraan / Penimbangan terbuka / Serang, Banten', 'Pisahkan dengan garis miring ( / ).' ),
		'logos'       => $f( 'images', 'Logo mitra & sertifikasi', '', 'Tampil sebagai strip di bawah pembuka beranda. Kosong = strip disembunyikan.' ),
	];

	$kontak = [
		'company'     => $f( 'text', 'Nama perusahaan', 'PT Gemilang Karya Agri' ),
		'parent'      => $f( 'text', 'Keterangan grup', 'Anak perusahaan PT Gemilang Karya Mandiri' ),
		'address'     => $f( 'textarea', 'Alamat', 'Jl. Bojonegara No. 99, Walikukun, Terate, Kramatwatu, Serang, Banten' ),
		'phone'       => $f( 'text', 'Telepon', '(0254) 575 3355', 'Tulis seperti yang ingin ditampilkan; tautan telepon dibuat otomatis.' ),
		'fax'         => $f( 'text', 'Fax', '(0254) 849 5241' ),
		'email'       => $f( 'text', 'Email', 'yanti@pt-gka.com' ),
		'whatsapp'    => $f( 'text', 'WhatsApp', '+62 877-7149-1004', 'Dengan kode negara. Tautan wa.me dibuat otomatis.' ),
		'email_karir' => $f( 'text', 'Email lamaran kerja', 'ita@pt-gka.com' ),
		'map'         => $f( 'text', 'Peta · koordinat atau alamat', '-6.00134,106.08568', 'Contoh: -6.00134,106.08568 atau nama tempat.' ),
	];

	$arsip = [
		'lede_bisnis'    => $f( 'textarea', 'Bisnis Kami', 'Dari budidaya broiler di kandang close house hingga kemitraan dengan peternak di sekitar Serang.' ),
		'lede_produk'    => $f( 'textarea', 'Produk Kami', 'Setiap produk punya halaman dengan spesifikasi, proses pembelian, dan dokumen pendukung.' ),
		'lede_publikasi' => $f( 'textarea', 'Publikasi', 'Dokumentasi kegiatan internal dan eksternal perusahaan.' ),
		'lede_galeri'    => $f( 'textarea', 'Galeri', 'Kumpulan dokumentasi fasilitas kandang, proses produksi, dan kegiatan operasional.' ),
		'lede_lowongan'  => $f( 'textarea', 'Karir', 'Lowongan aktif di Serang, Banten. Tidak menemukan posisi yang cocok? Kirim CV ke {email_karir}.' ),
	];

	$schema = [
		'hero'    => [ 'Pembuka', $hero ],
		'about'   => [ 'Tentang & angka', $about ],
		'heads'   => [ 'Judul bagian', $heads ],
		'proses'  => [ 'Proses', $proses ],
		'esg'     => [ 'ESG', $esg ],
		'cta'     => [ 'Ajakan & logo', $cta ],
		'kontak'  => [ 'Kontak', $kontak ],
		'arsip'   => [ 'Intro halaman daftar', $arsip ],
	];
	return $schema;
}

/** Flat key => field definition. */
function gka_content_fields(): array {
	static $flat = null;
	if ( null === $flat ) {
		$flat = [];
		foreach ( gka_content_schema() as [ , $fields ] ) {
			$flat += $fields;
		}
	}
	return $flat;
}

/** Raw value: what the editor saved, or the default. */
function gka_c( string $key ): string {
	static $saved = null;
	$saved = $saved ?? (array) get_option( 'gka_content', [] );
	if ( isset( $saved[ $key ] ) && '' !== $saved[ $key ] ) {
		return (string) $saved[ $key ];
	}
	return (string) ( gka_content_fields()[ $key ]['default'] ?? '' );
}

/** Contact tokens editors may write in any text field. */
function gka_c_tokens(): array {
	return [
		'{email}'       => gka_c( 'email' ),
		'{email_karir}' => gka_c( 'email_karir' ),
		'{telepon}'     => gka_c( 'phone' ),
		'{whatsapp}'    => gka_c( 'whatsapp' ),
	];
}

/**
 * Escaped HTML for a text field: *word* becomes the accent italic, contact tokens are filled in
 * (emails become mailto links when $links is true).
 */
function gka_ct( string $key, bool $links = true ): string {
	$html = esc_html( gka_c( $key ) );
	$html = (string) preg_replace( '/\*([^*]+)\*/u', '<em class="is-accent">$1</em>', $html );
	foreach ( gka_c_tokens() as $token => $value ) {
		if ( ! str_contains( $html, $token ) ) {
			continue;
		}
		$out = esc_html( $value );
		if ( $links && is_email( $value ) ) {
			$out = '<a href="mailto:' . esc_attr( $value ) . '">' . $out . '</a>';
		}
		$html = str_replace( $token, $out, $html );
	}
	return $html;
}

/** Image URL for an image field, or $fallback when none is chosen. */
function gka_c_img( string $key, string $fallback = '', string $size = 'full' ): string {
	$id = (int) gka_c( $key );
	$url = $id ? wp_get_attachment_image_url( $id, $size ) : '';
	return $url ?: $fallback;
}

/** Attachment IDs of an images field. */
function gka_c_imgs( string $key ): array {
	return array_values( array_filter( array_map( 'absint', explode( ',', gka_c( $key ) ) ) ) );
}

/** tel: URI from a displayed Indonesian number: "(0254) 575 3355" -> "+622545753355". */
function gka_c_tel( string $display ): string {
	$d = preg_replace( '/\D+/', '', $display );
	if ( str_starts_with( $display, '+' ) ) {
		return '+' . $d;
	}
	return str_starts_with( $d, '0' ) ? '+62' . substr( $d, 1 ) : $d;
}

/** wa.me link from the WhatsApp field. */
function gka_c_wa(): string {
	return 'https://wa.me/' . ltrim( gka_c_tel( gka_c( 'whatsapp' ) ), '+' );
}

/** [gka_contact_line] – address, email and phone for the footer. */
add_shortcode( 'gka_contact_line', function (): string {
	$email = gka_c( 'email' );
	$phone = gka_c( 'phone' );
	return sprintf(
		'<p class="has-muted-color has-text-color">%s<br><a href="mailto:%s">%s</a> · <a href="tel:%s">%s</a></p>',
		nl2br( esc_html( gka_c( 'address' ) ) ),
		esc_attr( $email ),
		esc_html( $email ),
		esc_attr( gka_c_tel( $phone ) ),
		esc_html( $phone )
	);
} );

/** [gka_copyright] – "© <year> <company>" for the footer. */
add_shortcode( 'gka_copyright', fn(): string => '© ' . esc_html( wp_date( 'Y' ) ) . ' ' . esc_html( gka_c( 'company' ) ) );

/** [gka_c key="company"] – any single text field, for template parts that cannot run PHP. */
add_shortcode( 'gka_c', fn( $atts ): string => gka_ct( (string) ( $atts['key'] ?? '' ) ) );
