<?php
/**
 * Print the Save button
 * 
 * @version 4.0.0 (29-08-2023)
 * @see     
 * @package 
 * 
 * @param $view_arr['tab_name']
 */
defined( 'ABSPATH' ) || exit;

if ( $view_arr['tab_name'] === 'no_submit_tab' ) {
	return;
}
?>
<div class="postbox">
	<div class="inside">
		<table class="form-table">
			<tbody>
				<tr>
					<th scope="row"><label for="button-primary"></label></th>
					<td class="overalldesc">
						<?php wp_nonce_field( 'yfym_nonce_action', 'yfym_nonce_field' ); ?>
						<input id="button-primary" class="button-primary" name="yfym_submit_action" type="submit"
							value="<?php
							if ( $view_arr['tab_name'] === 'main_tab' ) {
								printf( '%s & %s',
									__( 'Save', 'yml-for-yandex-market' ),
									__( 'Create feed', 'yml-for-yandex-market' )
								);
							} else {
								_e( 'Save', 'yml-for-yandex-market' );
							}
							?>" /><br />
						<span class="description">
							<small>
								<?php _e( 'Click to save the settings', 'yml-for-yandex-market' ); ?>
							</small>
						</span>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>