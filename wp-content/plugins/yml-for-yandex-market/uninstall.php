<?php defined( 'WP_UNINSTALL_PLUGIN' ) || exit;
if ( is_multisite() ) {
	$yfym_registered_feeds_arr = get_blog_option( get_current_blog_id(), 'yfym_registered_feeds_arr' );
	if ( is_array( $yfym_registered_feeds_arr ) ) {
		// с единицы, т.к инфа по конкретным фидам там
		for ( $i = 1; $i < count( $yfym_registered_feeds_arr ); $i++ ) {
			$feed_id = $yfym_registered_feeds_arr[ $i ]['id'];
			delete_blog_option( get_current_blog_id(), 'yfym_status_sborki' . $feed_id );
			delete_blog_option( get_current_blog_id(), 'yfym_last_element' . $feed_id );
		}
	}
	delete_blog_option( get_current_blog_id(), 'yfym_version' );
	delete_blog_option( get_current_blog_id(), 'yfym_keeplogs' );
	delete_blog_option( get_current_blog_id(), 'yfym_disable_notices' );
	delete_blog_option( get_current_blog_id(), 'yfym_feed_content' );

	delete_blog_option( get_current_blog_id(), 'yfym_settings_arr' );
	delete_blog_option( get_current_blog_id(), 'yfym_registered_feeds_arr' );
} else {
	$yfym_registered_feeds_arr = get_option( 'yfym_registered_feeds_arr' );
	if ( is_array( $yfym_registered_feeds_arr ) ) {
		// с единицы, т.к инфа по конкретным фидам там
		for ( $i = 1; $i < count( $yfym_registered_feeds_arr ); $i++ ) {
			$feed_id = $yfym_registered_feeds_arr[ $i ]['id'];
			delete_option( 'yfym_status_sborki' . $feed_id );
			delete_option( 'yfym_last_element' . $feed_id );
		}
	}
	delete_option( 'yfym_version' );
	delete_option( 'yfym_keeplogs' );
	delete_option( 'yfym_disable_notices' );
	delete_option( 'yfym_feed_content' );

	delete_option( 'yfym_settings_arr' );
	delete_option( 'yfym_registered_feeds_arr' );
}
wp_cache_flush();