<?php
/**
 * Print Sandbox block
 * 
 * @version 4.0.0 (29-08-2023)
 * @see     
 * @package 
 */
defined( 'ABSPATH' ) || exit; ?>
<div class="postbox">
	<h2 class="hndle">
		<?php _e( 'Sandbox', 'yml-for-yandex-market' ); ?>
	</h2>
	<div class="inside">
		<?php
		require_once YFYM_PLUGIN_DIR_PATH . '/sandbox.php';
		try {
			y4ym_run_sandbox();
		} catch (Exception $e) {
			echo 'Exception: ', $e->getMessage(), "\n";
		}
		?>
	</div>
</div>