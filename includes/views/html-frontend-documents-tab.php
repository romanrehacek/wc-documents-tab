<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$heading = apply_filters( 'dt_frontend_tab_headline', esc_html__( 'Documents', 'documents-tab' ) );

?>

<?php if ( $heading ) : ?>
  <h2><?php echo $heading; ?></h2>
<?php endif; ?>

<?php if ( $this->documents ) : ?>
<table class="shop_attributes">
	<tbody><?php
		foreach ( $this->documents as $file ) { ?>
			<tr>
				<td class="product_weight">
					<a href="<?php echo esc_url( $file['file'] ); ?>" target="_blank" title="<?php echo esc_attr( $file['name'] ); ?>">
						<?php echo !empty( $file['name'] ) ? esc_attr( $file['name'] ) : basename( esc_url( $file['file'] ) ); ?>
					</a>
				</td>
			</tr><?php
		} ?>
	</tbody>
</table>
<?php else: ?>
<div class="no-documents">
	<?php esc_attr_e( 'No documents found', 'documents-tab' ); ?>
</div>
<?php endif; ?>
