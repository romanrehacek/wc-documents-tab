<tr>
	<td class="sort"></td>
	<td class="file_name"><input type="text" class="input_text" placeholder="<?php esc_attr_e( 'File Name', 'documents-tab' ); ?>" name="_dt_file_names[]" value="<?php echo esc_attr( $file['name'] ); ?>" /></td>
	<td class="file_url"><input type="text" class="input_text" placeholder="<?php esc_attr_e( "http://", 'documents-tab' ); ?>" name="_dt_file_urls[]" value="<?php echo esc_attr( $file['file'] ); ?>" /></td>
	<td class="file_url_choose" width="1%"><a href="#" class="button dt_upload_file_button" data-choose="<?php esc_attr_e( 'Choose file', 'documents-tab' ); ?>" data-update="<?php esc_attr_e( 'Insert file URL', 'documents-tab' ); ?>"><?php echo str_replace( ' ', '&nbsp;', __( 'Choose file', 'documents-tab' ) ); ?></a></td>
	<td width="1%"><a href="#" class="delete"><?php _e( 'Delete', 'documents-tab' ); ?></a></td>
</tr>