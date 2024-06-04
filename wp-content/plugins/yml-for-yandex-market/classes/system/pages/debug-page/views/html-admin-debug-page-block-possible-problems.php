<?php
/**
 * Print Possible problems block
 * 
 * @version 4.0.0 (29-08-2023)
 * @see     
 * @package 
 */
defined( 'ABSPATH' ) || exit; ?>
<div class="postbox">
	<h2 class="hndle">
		<?php _e( 'Possible problems', 'yml-for-yandex-market' ); ?>
	</h2>
	<div class="inside">
		<?php
		$possible_problems_arr = Y4YM_Debug_Page::get_possible_problems_list();
		if ( $possible_problems_arr[1] > 0 ) {
			printf( '<ol>%s</ol>', $possible_problems_arr[0] );
		} else {
			printf( '<p>%s</p>',
				__( 'Self-diagnosis functions did not reveal potential problems', 'yml-for-yandex-market' )
			);
		}
		?>
	</div>
</div>