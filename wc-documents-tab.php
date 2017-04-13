<?php
/*
Plugin Name:	WC Documents Tab
Description:	Add documents into a product tab
Version:    	1.1
Author:     	Roman Rehacek
Contributors:	romanrehacek, wpsjb
Donate link:	https://paypal.me/romanrehacek/25
License:    	GPL2
License URI:	https://www.gnu.org/licenses/gpl-2.0.html
Text Domain:	documents-tab
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WC_Documents_Tab' ) ) :
	
/**
 * Main plugin class
 */
class WC_Documents_Tab {
	
	/**
	 * Plugin version
	 */
	public $version = '1.0';
	
	/**
	 * Constructor
	 */
	public function __construct() {
		$this->define_constants();
		$this->init_hooks();
	}
	
	/**
	 * Hook into actions and filters.
	 */
	private function init_hooks() {
		register_activation_hook( __FILE__, array( $this, 'plugin_activation' ) );
		
		add_action( 'plugins_loaded', array( $this, 'init' ) );
	}
	
	/**
	 * Define plugin constants
	 */
	private function define_constants() {
		$this->define( 'DT_VERSION',			$this->version );
		$this->define( 'DT_PLUGIN_FILE',		__FILE__ );
		$this->define( 'DT_PLUGIN_BASENAME',	plugin_basename( __FILE__ ) );
		$this->define( 'DT_PLUGIN_URL',			untrailingslashit( plugins_url( '/', __FILE__ ) ) );
		$this->define( 'DT_ABSPATH',			dirname( __FILE__ ) . '/' );
	}
	
	/**
	 * Activation check
	 */
	public function plugin_activation() {
		if ( ! $this->is_woocommerce_active() ) {
		    deactivate_plugins( DT_PLUGIN_BASENAME );
		    wp_die( __( 'Please activate WooCommerce.', 'documents-tab' ), 'Plugin dependency check', array( 'back_link' => true ) );
		}
	}
	
	/**
	 * Init when WordPress Initialises.
	 */
	public function init() {
		if ( $this->is_woocommerce_active() && WC()->version >= 2.1 ) {
			$this->includes();
		} elseif ( $this->is_woocommerce_active() ) {
			add_action('admin_notices', array( $this, 'update_woocommerce' ));
		} else {
			add_action('admin_notices', array( $this, 'need_woocommerce' ));
		}
	}
	
	/**
	 * Include required core files used in admin and on the frontend.
	 */
	private function includes() {
		
		include_once( DT_ABSPATH . 'includes/class-dt-assets.php' );
		
		if ( is_admin() ) {
			include_once( DT_ABSPATH . 'includes/class-dt-simple-product-downloads-tab.php' );
		} else {
			include_once( DT_ABSPATH . 'includes/class-dt-frontend.php' );
		}
	}
	
	/**
	 * Check if WooCommerce is active
	 */
	private function is_woocommerce_active() {
		return class_exists( 'woocommerce' );
	}
	
	/**
	 * Define constant if not already set.
	 */
	private function define( $name, $value ) {
		if ( ! defined( $name ) ) {
			define( $name, $value);
		}
	}
	
	/**
	 * Show admin notice if woocommerce plugin not found.
	 */
	public function need_woocommerce() {
		echo '<div class="error">';
		echo '<p>' . __('WooCommerce Documents Tab needs WooCommerce plugin to work.', 'documents-tab') . '</p>';
		echo '</div>';
	}

	/**
	 * Show admin notice if woocommerce plugin version is older than required version.
	 */
	public function update_woocommerce() {
		echo '<div class="error">';
		echo '<p>' . __('To use WooCommerce Documents Tab update your WooCommerce plugin.', 'documents-tab') . '</p>';
		echo '</div>';
	}
	
}
	
endif;

return new WC_Documents_Tab();