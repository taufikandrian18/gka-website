<?php
/**
 * Plugin Name: GKA Core
 * Description: Content types, meta, block bindings and legacy redirects for PT Gemilang Karya Agri.
 * Version: 0.1.0
 * Requires at least: 6.6
 * Requires PHP: 8.1
 * Text Domain: gka-core
 */
defined( 'ABSPATH' ) || exit;

define( 'GKA_CORE_DIR', __DIR__ );

require_once GKA_CORE_DIR . '/src/post-types.php';
require_once GKA_CORE_DIR . '/src/meta.php';
require_once GKA_CORE_DIR . '/src/redirects.php';

add_action( 'init', fn() => add_post_type_support( 'page', 'excerpt' ) );

register_activation_hook( __FILE__, function (): void {
	gka_core_register_content();
	flush_rewrite_rules();
} );
