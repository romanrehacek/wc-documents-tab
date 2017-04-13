<?php 
/**
 * Frontend class
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'DT_Frontend' ) ) :

/**
 * DT_Frontend class
 */
class DT_Frontend {
	
	protected $documents = array();
	
	/**
	 * Class constructor
	 */
	public function __construct() {
		add_filter( 'woocommerce_product_tabs', array( $this, 'woocommerce_product_tabs' ) );
	}
	
	/**
	 * Add new tab into product detail page.
	 */
	public function woocommerce_product_tabs( $tabs ) {
		global $post, $product;
		
		if ( $product ) {
			// Get product's documents.
			$this->documents = get_post_meta( $product->get_id(), '_dt_documents', true );
			
			// Add document's tab if documents for product exist
			if ( $this->documents ) {
				$tabs['documents'] = array(
						'title'		=> esc_attr__('Documents', 'documents-tab' ),
						'priority'	=> 30,
						'callback'	=> array( $this, 'documents_tab' )
					);
			}
		}
			
		return $tabs;
	}
	
	/**
	 * Show content of document tab
	 */
	public function documents_tab() {
		global $post;
		
		//$documents = get_post_meta( $post->ID, '_dt_documents', true );
		include_once( DT_ABSPATH . 'includes/views/html-frontend-documents-tab.php' );
	}
}

endif;

return new DT_Frontend();