<?php
/**
 * View for document tab id product edit page in admin
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'DT_Simple_Product_Downloads_Tab' ) ) :

/**
 * DT_Simple_Product_Downloads_Tab class
 */
class DT_Simple_Product_Downloads_Tab {
	
	/**
	 * Constructor.
	 */
	public function __construct() {
		add_filter( 'woocommerce_product_data_tabs', array( $this, 'add_documents_tab' ) );
		add_action( 'woocommerce_product_data_panels', array( $this, 'add_documents_tab_content' ) );
		add_action( 'woocommerce_process_product_meta', array( $this, 'save_documents' ), 10, 2 );
	}
	
	/**
	 * Add document tab into product edit page
	 */
	public function add_documents_tab( $tabs ) {
		$tabs['dt_document'] = array(
			'label'  => __( 'Documents', 'documents-tab' ),
			'target' => 'dt_document_data',
			'class'	 => array()
		);
		
		return $tabs;
	}
	
	/**
	 * Show content for document tab
	 */
	public function add_documents_tab_content() {
		global $post, $thepostid; ?>
		<div id="dt_document_data" class="panel woocommerce_options_panel">
			<div class="options_group">
				<div class="form-field dt_documents">
					<h3><?php _e('Downloadable documents', 'downloads-tab'); ?></h3>
					<table class="widefat">
						<thead>
							<tr>
								<th class="sort">&nbsp;</th>
								<th><?php _e( 'Name', 'documents-tab' ); ?> <?php echo wc_help_tip( __( 'This is the name of the download shown to the customer.', 'documents-tab' ) ); ?></th>
								<th colspan="2"><?php _e( 'File URL', 'documents-tab' ); ?> <?php echo wc_help_tip( __( 'This is the URL or absolute path to the file which customers will get access to. URLs entered here should already be encoded.', 'documents-tab' ) ); ?></th>
								<th>&nbsp;</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$documents = get_post_meta( $post->ID, '_dt_documents', true );

							if ( $documents ) {
								foreach ( $documents as $key => $file ) {
									include( 'views/html-product-document.php' );
								}
							}
							?>
						</tbody>
						<tfoot>
							<tr>
								<th colspan="5">
									<a href="#" class="button insert" data-row="<?php
										$file = array(
											'file' => '',
											'name' => ''
										);
										ob_start();
										include( 'views/html-product-document.php' );
										echo esc_attr( ob_get_clean() );
									?>"><?php _e( 'Add File', 'documents-tab' ); ?></a>
								</th>
							</tr>
						</tfoot>
					</table>
				</div>
			</div>
		</div><?php
	}
	
	/**
	 * Save document's data
	 */
	public function save_documents( $post_id, $post ) {

		// file paths will be stored in an array keyed off md5(file path)
		$files = array();

		if ( isset( $_POST['_dt_file_urls'] ) ) {
			$file_names         = isset( $_POST['_dt_file_names'] ) ? $_POST['_dt_file_names'] : array();
			$file_urls          = isset( $_POST['_dt_file_urls'] )  ? wp_unslash( array_map( 'trim', $_POST['_dt_file_urls'] ) ) : array();
			$file_url_size      = sizeof( $file_urls );
			$allowed_file_types = apply_filters( 'dt_downloadable_file_allowed_mime_types', get_allowed_mime_types() );

			for ( $i = 0; $i < $file_url_size; $i ++ ) {
				if ( ! empty( $file_urls[ $i ] ) ) {
					// Find type and file URL
					if ( 0 === strpos( $file_urls[ $i ], 'http' ) ) {
						$file_is  = 'absolute';
						$file_url = esc_url_raw( $file_urls[ $i ] );
					} else {
						$file_is = 'relative';
						$file_url = wc_clean( $file_urls[ $i ] );
					}

					$file_name = wc_clean( $file_names[ $i ] );
					$file_hash = md5( $file_url );

					// Validate the file extension
					if ( in_array( $file_is, array( 'absolute', 'relative' ) ) ) {
						$file_type  = wp_check_filetype( strtok( $file_url, '?' ), $allowed_file_types );
						$parsed_url = parse_url( $file_url, PHP_URL_PATH );
						$extension  = pathinfo( $parsed_url, PATHINFO_EXTENSION );

						if ( ! empty( $extension ) && ! in_array( $file_type['type'], $allowed_file_types ) ) {
							WC_Admin_Meta_Boxes::add_error( sprintf( __( 'The downloadable file %s cannot be used as it does not have an allowed file type. Allowed types include: %s', 'documents-tab' ), '<code>' . basename( $file_url ) . '</code>', '<code>' . implode( ', ', array_keys( $allowed_file_types ) ) . '</code>' ) );
							continue;
						}
					}

					// Validate the file exists
					if ( 'relative' === $file_is ) {
						$_file_url = $file_url;
						if ( '..' === substr( $file_url, 0, 2 ) || '/' !== substr( $file_url, 0, 1 ) ) {
							$_file_url = realpath( ABSPATH . $file_url );
						}

						if ( ! apply_filters( 'woocommerce_downloadable_file_exists', file_exists( $_file_url ), $file_url ) ) {
							WC_Admin_Meta_Boxes::add_error( sprintf( __( 'The downloadable file %s cannot be used as it does not exist on the server.', 'documents-tab' ), '<code>' . $file_url . '</code>' ) );
							continue;
						}
					}

					$files[ $file_hash ] = array(
						'name' => $file_name,
						'file' => $file_url
					);
				}
			}
		}

		update_post_meta( $post_id, '_dt_documents', $files );
	}
	
}
	
endif;

new DT_Simple_Product_Downloads_Tab();