<?php
/**
 * Load assets
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'DT_Assets' ) ) :
	
/**
 * DT_Assets class
 */
class DT_Assets {
	
	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
	}
	
	/**
	 * Enqueue styles in admin.
	 */
	public function admin_styles() {
		$screen         = get_current_screen();
		$screen_id      = $screen ? $screen->id : '';
		
		// Enqueue only in edit product page.
		if ( $screen_id == 'product' ) {
			wp_enqueue_style( 'wc-documents-tab', DT_PLUGIN_URL . '/assets/css/style.min.css', array(), DT_VERSION );
		}
	}
	
	/**
	 * Enqueue scripts.
	 */
	public function admin_scripts() {
		$screen         = get_current_screen();
		$screen_id      = $screen ? $screen->id : '';
		
		// Enqueue only in edit product page.
		if ( $screen_id == 'product' ) {
			wp_enqueue_script( 'wc-documents-tab', DT_PLUGIN_URL . '/assets/js/scripts.min.js', array(), DT_VERSION );
		}
	}
	
}
	
endif;

new DT_Assets();