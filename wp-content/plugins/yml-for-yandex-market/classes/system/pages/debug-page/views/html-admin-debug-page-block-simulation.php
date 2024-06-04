<?php
/**
 * Print Simulation block
 * 
 * @version 4.0.0 (29-08-2023)
 * @see     
 * @package 
 * 
 * @param $view_arr['simulated_post_id']
 * @param $view_arr['feed_id']
 * @param $view_arr['feed_assignment']
 * @param $view_arr['simulation_result_report']
 * @param $view_arr['simulation_result']
 */
defined( 'ABSPATH' ) || exit; ?>
<div class="postbox">
	<h2 class="hndle">
		<?php _e( 'Request simulation', 'yml-for-yandex-market' ); ?>
	</h2>
	<div class="inside">
		<form action="<?php echo esc_url( $_SERVER['REQUEST_URI'] ); ?>" method="post" enctype="multipart/form-data">
			<table class="form-table">
				<tbody>
					<tr>
						<th scope="row"><label for="yfym_simulated_post_id">Product ID</label></th>
						<td class="overalldesc">
							<input type="number" min="1" name="yfym_simulated_post_id"
								value="<?php echo esc_attr( $view_arr['simulated_post_id'] ); ?>">
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="yfym_feed_id">Feed ID</label></th>
						<td class="overalldesc">
							<select style="width: 100%" name="yfym_feed_id" id="yfym_feed_id">
								<?php Y4YM_Debug_Page::print_html_options(); ?>
							</select>
						</td>
					</tr>
					<tr>
						<th scope="row" colspan="2">
							<textarea style="width: 100%;" rows="4"><?php
							echo htmlspecialchars( $view_arr['simulation_result_report'] );
							?></textarea>
						</th>
					</tr>
					<tr>
						<th scope="row" colspan="2">
							<textarea rows="16" style="width: 100%;"><?php
							echo htmlspecialchars( $view_arr['simulation_result'] );
							?></textarea>
						</th>
					</tr>
				</tbody>
			</table>
			<?php wp_nonce_field( 'yfym_nonce_action_simulated', 'yfym_nonce_field_simulated' ); ?>
			<input class="button-primary" type="submit" name="yfym_submit_simulated"
				value="<?php _e( 'Simulated', 'yml-for-yandex-market' ); ?>" />
		</form>
	</div>
</div>