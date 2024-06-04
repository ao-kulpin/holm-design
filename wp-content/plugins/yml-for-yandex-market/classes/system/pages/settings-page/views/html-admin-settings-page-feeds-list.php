<?php
/**
 * Print Add New Feed button
 * 
 * @version 4.0.0 (29-08-2023)
 * @see     
 * @package 
 */
defined( 'ABSPATH' ) || exit;

$feed_list_table = new Y4YM_Settings_Page_Feeds_WP_List_Table(); ?>
<form action="<?php echo esc_url( $_SERVER['REQUEST_URI'] ); ?>" method="post" enctype="multipart/form-data">
	<?php wp_nonce_field( 'yfym_nonce_action_add_new_feed', 'yfym_nonce_field_add_new_feed' ); ?>
	<input class="button" type="submit" name="yfym_submit_add_new_feed"
		value="<?php _e( 'Add New Feed', 'yml-for-yandex-market' ); ?>" />
</form>
<?php $feed_list_table->print_html_form();