<?php 
/**
 * The Tags tab
 * 
 * @version 4.0.0 (29-08-2023)
 * @see     
 * @package 
 * 
 * @param $view_arr['tabs_arr']
 */
defined( 'ABSPATH' ) || exit;

$settings_feed_table = new Y4YM_Settings_Page_Tags_WP_List_Table( $view_arr['feed_id'] );
$settings_feed_table->prepare_items();
$settings_feed_table->display();