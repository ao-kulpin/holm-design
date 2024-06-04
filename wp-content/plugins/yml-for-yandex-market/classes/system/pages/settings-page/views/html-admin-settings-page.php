<?php
/**
 * Settings page
 * 
 * @version 4.0.0 (29-08-2023)
 * @see     
 * @package 
 * 
 * @param $view_arr['feed_id']
 * @param $view_arr['tab_name']
 */
defined( 'ABSPATH' ) || exit;
?>
<div class="wrap">
	<h1>YML for Yandex Market</h1>
	<div id="poststuff">
		<?php include_once __DIR__ . '/html-admin-settings-page-feeds-list.php'; ?>
		<div id="post-body" class="columns-2">

			<div id="postbox-container-1" class="postbox-container">
				<div class="meta-box-sortables">
					<?php
					include_once __DIR__ . '/html-admin-settings-page-info-block.php';
					do_action( 'yfym_activation_forms' ); // TODO: depricated

					do_action( 'y4ym_activation_forms' );

					do_action( 'y4ym_feedback_block' );

					do_action( 'yfym_before_container_1', $view_arr['feed_id'] );

					do_action( 'yfym_between_container_1', $view_arr['feed_id'] );

					do_action( 'yfym_append_container_1', $view_arr['feed_id'] );
					?>
				</div>
			</div><!-- /postbox-container-1 -->

			<div id="postbox-container-2" class="postbox-container">
				<div class="meta-box-sortables">
					<?php include_once __DIR__ . '/html-admin-settings-page-tabs.php'; ?>

					<form action="<?php echo esc_url( $_SERVER['REQUEST_URI'] ); ?>" method="post"
						enctype="multipart/form-data">
						<input type="hidden" name="yfym_feed_id_for_save"
							value="<?php echo esc_attr( $view_arr['feed_id'] ); ?>">
						<?php
						switch ( $view_arr['tab_name'] ) {
							case 'tags_settings_tab':
								include_once __DIR__ . '/html-admin-settings-page-tab-tags.php';
								break;
							default:
								$html_template = __DIR__ . '/html-admin-settings-page-tab-another.php';
								$html_template = apply_filters( 'y4ym_f_html_template_tab',
									$html_template,
									[ 
										'tab_name' => $view_arr['tab_name'],
										'view_arr' => $view_arr
									]
								);
								include_once $html_template;
						}

						do_action( 'yfym_between_container_2', $view_arr['feed_id'] );

						include_once __DIR__ . '/html-admin-settings-page-save-btn.php';
						?>
					</form>
				</div>
			</div><!-- /postbox-container-2 -->

		</div>
	</div><!-- /poststuff -->
	<?php
	do_action( 'print_view_html_icp_banners', 'yfym' );
	do_action( 'print_view_html_icpd_my_plugins_list', 'yfym' );
	?>
</div>