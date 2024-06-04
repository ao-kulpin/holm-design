<?php
/**
 * Print info block
 * 
 * @version 4.0.7 (02-10-2023)
 * @see     
 * @package 
 * 
 * @param $view_arr['feed_id']
 * @param $view_arr['prefix_feed'],
 * @param $view_arr['current_blog_id']
 * @param $view_arr['extension']
 */
defined( 'ABSPATH' ) || exit;

$status_sborki = (int) yfym_optionGET( 'yfym_status_sborki', $view_arr['feed_id'] );
$feed_url = urldecode( common_option_get( 'yfym_file_url', false, $view_arr['feed_id'], 'yfym' ) );
$date_sborki = common_option_get( 'yfym_date_sborki', false, $view_arr['feed_id'], 'yfym' );
$date_sborki_end = common_option_get( 'yfym_date_sborki_end', false, $view_arr['feed_id'], 'yfym' );
$count_products_in_feed = common_option_get( 'yfym_count_products_in_feed', false, $view_arr['feed_id'], 'yfym' );
$assignment = common_option_get( 'yfym_feed_assignment', false, $view_arr['feed_id'], 'yfym' );
$utm = sprintf(
	'?utm_source=%1$s&utm_medium=organic&utm_campaign=in-plugin-%1$s&utm_content=settings&utm_term=main-instruction',
	'yml-for-yandex-market'
);
?>
<div class="postbox">
	<h2 class="hndle">
		<?php
		if ( ! empty( $assignment ) ) {
			$assignment = '(' . $assignment . ')';
		}
		printf( '%s: %sfeed-yml-%s.xml %s',
			__( 'Feed', 'yml-for-yandex-market' ),
			$view_arr['prefix_feed'],
			$view_arr['current_blog_id'],
			$assignment
		); ?>
		<?php if ( empty( $feed_url ) ) : ?>
			<?php _e( 'not created yet', 'yml-for-yandex-market' ); ?>
		<?php else : ?>
			<?php if ( $status_sborki !== -1 ) : ?>
				<?php _e( 'updating', 'yml-for-yandex-market' ); ?>
			<?php else : ?>
				<?php _e( 'created', 'yml-for-yandex-market' ); ?>
			<?php endif; ?>
		<?php endif; ?>
	</h2>
	<div class="inside">
		<p><strong style="color: green;">
				<?php _e( 'Instruction', 'yml-for-yandex-market' ); ?>:
			</strong> <a href="https://icopydoc.ru/kak-sozdat-woocommerce-yml-instruktsiya/<?php echo $utm; ?>"
				target="_blank">
				<?php _e( 'How to create a YML-feed', 'yml-for-yandex-market' ); ?>
			</a></p>
		<?php if ( empty( $feed_url ) ) : ?>
			<?php if ( $status_sborki !== -1 ) : ?>
				<p>
					<?php _e(
						'We are working on automatic file creation. YML will be developed soon',
						'yml-for-yandex-market'
					); ?>.
				</p>
			<?php else :
				printf( '<p><span class="y4ym_bold">%s "%s". %s. "%s"</span></p><p>%s</p>',
					__(
						'In order to do that, select another menu entry (which differs from "off") in the box called',
						'yml-for-yandex-market' ),
					__(
						'Automatic file creation',
						'yml-for-yandex-market'
					),
					__(
						'You can also change values in other boxes if necessary, then press',
						'yml-for-yandex-market'
					),
					__( 'Save', 'yml-for-yandex-market' ),
					__(
						'After 1-7 minutes (depending on the number of products), the feed will be generated and a link will appear instead of this message',
						'yml-for-yandex-market'
					)
				);
			endif; ?>
		<?php else : ?>
			<?php if ( $status_sborki !== -1 ) : ?>
				<p>
					<?php _e(
						'We are working on automatic file creation. YML will be developed soon',
						'yml-for-yandex-market'
					);
					?>.
				</p>
			<?php else : ?>
				<p><span class="y4ym_bold">
						<?php _e( 'Your feed here', 'yml-for-yandex-market' ); ?>:
					</span><br />
					<a target="_blank" href="<?php echo $feed_url; ?>">
						<?php echo $feed_url; ?>
					</a>
					<br />
					<?php _e( 'File size', 'yml-for-yandex-market' ); ?>:
					<?php clearstatcache();
					$feed_file_meta = new YFYM_Feed_File_Meta( $view_arr['feed_id'] );
					$filenamefeed = sprintf( '%1$s/%2$s.%3$s',
						YFYM_SITE_UPLOADS_DIR_PATH,
						$feed_file_meta->get_feed_filename(),
						$feed_file_meta->get_feed_extension()
					);
					if ( is_file( $filenamefeed ) ) {
						echo get_format_filesize( filesize( $filenamefeed ) );
					} else {
						echo '0 KB';
					} ?>
					<br />
					<?php _e( 'Start of generation', 'yml-for-yandex-market' ); ?>:
					<?php echo $date_sborki; ?>
					<br />
					<?php _e( 'Generated', 'yml-for-yandex-market' ); ?>:
					<?php echo $date_sborki_end; ?>
					<br />
					<?php _e( 'Products', 'yml-for-yandex-market' ); ?>:
					<?php echo $count_products_in_feed; ?>
				</p>
			<?php endif; ?>
		<?php endif; ?>
	</div>
</div>