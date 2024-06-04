<?php defined( 'ABSPATH' ) || exit;
// 1.0.0 (27-11-2022)
// Maxim Glazunov (https://icopydoc.ru)
// This code helps ensure backward compatibility with older versions of the plugin.
// 'yfym' - slug for translation (be sure to make an autocorrect)

define( 'yfym_DIR', plugin_dir_path( __FILE__ ) ); // yfym_DIR contains /home/p135/www/site.ru/wp-content/plugins/myplagin/		
define( 'yfym_URL', plugin_dir_url( __FILE__ ) ); // yfym_URL contains http://site.ru/wp-content/plugins/myplagin/		
$upload_dir = (object) wp_get_upload_dir(); // yfym_UPLOAD_DIR contains /home/p256/www/site.ru/wp-content/uploads
define( 'yfym_UPLOAD_DIR', $upload_dir->basedir );
$name_dir = $upload_dir->basedir . "/yfym";
define( 'yfym_NAME_DIR', $name_dir ); // yfym_UPLOAD_DIR contains /home/p256/www/site.ru/wp-content/uploads/yfym
$yfym_keeplogs = yfym_optionGET( 'yfym_keeplogs' );
define( 'yfym_KEEPLOGS', $yfym_keeplogs );
define( 'yfym_VER', '3.6.16' );
if ( ! defined( 'yfym_ALLNUMFEED' ) ) {
	define( 'yfym_ALLNUMFEED', '5' );
}

function yfym_add_settings_arr() {
	$feed_id = '1';

	$yfym_settings_arr = yfym_optionGET( 'yfym_settings_arr' );
	$yfym_settings_arr_keys_arr = array_keys( $yfym_settings_arr );
	for ( $i = 0; $i < count( $yfym_settings_arr_keys_arr ); $i++ ) {
		$feed_id = $yfym_settings_arr_keys_arr[ $i ];


		wp_clear_scheduled_hook( 'yfym_cron_period', array( $feed_id ) );
		wp_clear_scheduled_hook( 'yfym_cron_sborki', array( $feed_id ) );
	}

	$yfym_settings_arr = array();
	$feed_id = '1';
	for ( $i = 1; $i < yfym_ALLNUMFEED + 1; $i++ ) {
		$yfym_settings_arr[ $feed_id ]['yfym_status_cron'] = yfym_optionGET( 'yfym_status_cron', $feed_id, 'for_update_option' );
		$yfym_settings_arr[ $feed_id ]['yfym_step_export'] = yfym_optionGET( 'yfym_step_export', $feed_id, 'for_update_option' );
		$yfym_settings_arr[ $feed_id ]['yfym_date_sborki'] = yfym_optionGET( 'yfym_date_sborki', $feed_id, 'for_update_option' );
		$yfym_settings_arr[ $feed_id ]['yfym_type_sborki'] = yfym_optionGET( 'yfym_type_sborki', $feed_id, 'for_update_option' );
		$yfym_settings_arr[ $feed_id ]['yfym_file_url'] = yfym_optionGET( 'yfym_file_url', $feed_id, 'for_update_option' );
		$yfym_settings_arr[ $feed_id ]['yfym_file_file'] = yfym_optionGET( 'yfym_file_file', $feed_id, 'for_update_option' );
		$yfym_settings_arr[ $feed_id ]['yfym_file_ids_in_yml'] = yfym_optionGET( 'yfym_file_ids_in_yml', $feed_id, 'for_update_option' );
		$yfym_settings_arr[ $feed_id ]['yfym_ufup'] = yfym_optionGET( 'yfym_ufup', $feed_id, 'for_update_option' );
		$yfym_settings_arr[ $feed_id ]['yfym_magazin_type'] = yfym_optionGET( 'yfym_magazin_type', $feed_id, 'for_update_option' );
		$yfym_settings_arr[ $feed_id ]['yfym_vendor'] = yfym_optionGET( 'yfym_vendor', $feed_id, 'for_update_option' );
		$yfym_settings_arr[ $feed_id ]['yfym_vendor_post_meta'] = yfym_optionGET( 'yfym_vendor_post_meta', $feed_id, 'for_update_option' );
		$yfym_settings_arr[ $feed_id ]['yfym_whot_export'] = yfym_optionGET( 'yfym_whot_export', $feed_id, 'for_update_option' );
		$yfym_settings_arr[ $feed_id ]['yfym_yml_rules'] = yfym_optionGET( 'yfym_yml_rules', $feed_id, 'for_update_option' );
		$yfym_settings_arr[ $feed_id ]['yfym_skip_missing_products'] = yfym_optionGET( 'yfym_skip_missing_products', $feed_id, 'for_update_option' );
		$yfym_settings_arr[ $feed_id ]['yfym_date_save_set'] = yfym_optionGET( 'yfym_date_save_set', $feed_id, 'for_update_option' );
		$yfym_settings_arr[ $feed_id ]['yfym_separator_type'] = yfym_optionGET( 'yfym_separator_type', $feed_id, 'for_update_option' );
		$yfym_settings_arr[ $feed_id ]['yfym_behavior_onbackorder'] = yfym_optionGET( 'yfym_behavior_onbackorder', $feed_id, 'for_update_option' );
		$yfym_settings_arr[ $feed_id ]['yfym_behavior_stip_symbol'] = yfym_optionGET( 'yfym_behavior_stip_symbol', $feed_id, 'for_update_option' );
		$yfym_settings_arr[ $feed_id ]['yfym_feed_assignment'] = yfym_optionGET( 'yfym_feed_assignment', $feed_id, 'for_update_option' );
		$yfym_settings_arr[ $feed_id ]['yfym_file_extension'] = yfym_optionGET( 'yfym_file_extension', $feed_id, 'for_update_option' );

		$yfym_settings_arr[ $feed_id ]['yfym_shop_sku'] = yfym_optionGET( 'yfym_shop_sku', $feed_id, 'for_update_option' );
		$yfym_settings_arr[ $feed_id ]['yfym_count'] = yfym_optionGET( 'yfym_count', $feed_id, 'for_update_option' );
		$yfym_settings_arr[ $feed_id ]['yfym_auto_disabled'] = yfym_optionGET( 'yfym_auto_disabled', $feed_id, 'for_update_option' );
		$yfym_settings_arr[ $feed_id ]['yfym_amount'] = yfym_optionGET( 'yfym_amount', $feed_id, 'for_update_option' );
		$yfym_settings_arr[ $feed_id ]['yfym_manufacturer'] = yfym_optionGET( 'yfym_manufacturer', $feed_id, 'for_update_option' );

		$yfym_settings_arr[ $feed_id ]['yfym_shop_name'] = yfym_optionGET( 'yfym_shop_name', $feed_id, 'for_update_option' );
		$yfym_settings_arr[ $feed_id ]['yfym_company_name'] = yfym_optionGET( 'yfym_company_name', $feed_id, 'for_update_option' );
		$yfym_settings_arr[ $feed_id ]['yfym_currencies'] = 'enabled';
		$yfym_settings_arr[ $feed_id ]['yfym_main_product'] = yfym_optionGET( 'yfym_main_product', $feed_id, 'for_update_option' );
		$yfym_settings_arr[ $feed_id ]['yfym_adult'] = yfym_optionGET( 'yfym_adult', $feed_id, 'for_update_option' );
		$yfym_settings_arr[ $feed_id ]['yfym_wooc_currencies'] = yfym_optionGET( 'yfym_wooc_currencies', $feed_id, 'for_update_option' );
		$yfym_settings_arr[ $feed_id ]['yfym_desc'] = yfym_optionGET( 'yfym_desc', $feed_id, 'for_update_option' );
		$yfym_settings_arr[ $feed_id ]['yfym_the_content'] = yfym_optionGET( 'yfym_the_content', $feed_id, 'for_update_option' );
		$yfym_settings_arr[ $feed_id ]['yfym_var_desc_priority'] = yfym_optionGET( 'yfym_var_desc_priority', $feed_id, 'for_update_option' );
		$yfym_settings_arr[ $feed_id ]['yfym_clear_get'] = yfym_optionGET( 'yfym_clear_get', $feed_id, 'for_update_option' );
		$yfym_settings_arr[ $feed_id ]['yfym_price_from'] = yfym_optionGET( 'yfym_price_from', $feed_id, 'for_update_option' );
		$yfym_settings_arr[ $feed_id ]['yfym_oldprice'] = yfym_optionGET( 'yfym_oldprice', $feed_id, 'for_update_option' );
		$yfym_settings_arr[ $feed_id ]['yfym_vat'] = yfym_optionGET( 'yfym_vat', $feed_id, 'for_update_option' );

		$yfym_settings_arr[ $feed_id ]['yfym_product_tag_arr'] = yfym_optionGET( 'yfym_product_tag_arr', $feed_id, 'for_update_option' );
		$yfym_settings_arr[ $feed_id ]['yfym_store'] = yfym_optionGET( 'yfym_store', $feed_id, 'for_update_option' );
		$yfym_settings_arr[ $feed_id ]['yfym_delivery'] = yfym_optionGET( 'yfym_delivery', $feed_id, 'for_update_option' );
		$yfym_settings_arr[ $feed_id ]['yfym_delivery_options'] = yfym_optionGET( 'yfym_delivery_options', $feed_id, 'for_update_option' );
		$yfym_settings_arr[ $feed_id ]['yfym_delivery_cost'] = yfym_optionGET( 'yfym_delivery_cost', $feed_id, 'for_update_option' );
		$yfym_settings_arr[ $feed_id ]['yfym_delivery_days'] = yfym_optionGET( 'yfym_delivery_days', $feed_id, 'for_update_option' );
		$yfym_settings_arr[ $feed_id ]['yfym_order_before'] = yfym_optionGET( 'yfym_order_before', $feed_id, 'for_update_option' );
		$yfym_settings_arr[ $feed_id ]['yfym_delivery_options2'] = yfym_optionGET( 'yfym_delivery_options2', $feed_id, 'for_update_option' );
		$yfym_settings_arr[ $feed_id ]['yfym_delivery_cost2'] = yfym_optionGET( 'yfym_delivery_cost2', $feed_id, 'for_update_option' );
		$yfym_settings_arr[ $feed_id ]['yfym_delivery_days2'] = yfym_optionGET( 'yfym_delivery_days2', $feed_id, 'for_update_option' );
		$yfym_settings_arr[ $feed_id ]['yfym_order_before2'] = yfym_optionGET( 'yfym_order_before2', $feed_id, 'for_update_option' );
		$yfym_settings_arr[ $feed_id ]['yfym_sales_notes_cat'] = yfym_optionGET( 'yfym_sales_notes_cat', $feed_id, 'for_update_option' );
		$yfym_settings_arr[ $feed_id ]['yfym_sales_notes'] = yfym_optionGET( 'yfym_sales_notes', $feed_id, 'for_update_option' );
		$yfym_settings_arr[ $feed_id ]['yfym_model'] = yfym_optionGET( 'yfym_model', $feed_id, 'for_update_option' );
		$yfym_settings_arr[ $feed_id ]['yfym_pickup'] = yfym_optionGET( 'yfym_pickup', $feed_id, 'for_update_option' );
		$yfym_settings_arr[ $feed_id ]['yfym_barcode'] = yfym_optionGET( 'yfym_barcode', $feed_id, 'for_update_option' );
		$yfym_settings_arr[ $feed_id ]['yfym_barcode_post_meta'] = yfym_optionGET( 'yfym_barcode_post_meta', $feed_id, 'for_update_option' );
		$yfym_settings_arr[ $feed_id ]['yfym_barcode_post_meta_var'] = '';
		$yfym_settings_arr[ $feed_id ]['yfym_vendorcode'] = yfym_optionGET( 'yfym_vendorcode', $feed_id, 'for_update_option' );
		$yfym_settings_arr[ $feed_id ]['yfym_enable_auto_discount'] = yfym_optionGET( 'yfym_enable_auto_discount', $feed_id, 'for_update_option' );
		$yfym_settings_arr[ $feed_id ]['yfym_expiry'] = yfym_optionGET( 'yfym_expiry', $feed_id, 'for_update_option' );
		$yfym_settings_arr[ $feed_id ]['yfym_period_of_validity_days'] = 'disabled';
		$yfym_settings_arr[ $feed_id ]['yfym_downloadable'] = yfym_optionGET( 'yfym_downloadable', $feed_id, 'for_update_option' );
		$yfym_settings_arr[ $feed_id ]['yfym_age'] = yfym_optionGET( 'yfym_age', $feed_id, 'for_update_option' );
		$yfym_settings_arr[ $feed_id ]['yfym_country_of_origin'] = yfym_optionGET( 'yfym_country_of_origin', $feed_id, 'for_update_option' );
		$yfym_settings_arr[ $feed_id ]['yfym_source_id'] = 'disabled';
		$yfym_settings_arr[ $feed_id ]['yfym_source_id_post_meta'] = '';
		$yfym_settings_arr[ $feed_id ]['yfym_ebay_stock'] = '0';
		$yfym_settings_arr[ $feed_id ]['yfym_manufacturer_warranty'] = yfym_optionGET( 'yfym_manufacturer_warranty', $feed_id, 'for_update_option' );
		$yfym_settings_arr[ $feed_id ]['yfym_errors'] = yfym_optionGET( 'yfym_errors', $feed_id, 'for_update_option' );
		$yfym_settings_arr[ $feed_id ]['yfym_enable_auto_discounts'] = yfym_optionGET( 'yfym_enable_auto_discounts', $feed_id, 'for_update_option' );
		$yfym_settings_arr[ $feed_id ]['yfym_skip_backorders_products'] = yfym_optionGET( 'yfym_skip_backorders_products', $feed_id, 'for_update_option' );
		$yfym_settings_arr[ $feed_id ]['yfym_no_default_png_products'] = yfym_optionGET( 'yfym_no_default_png_products', $feed_id, 'for_update_option' );
		$yfym_settings_arr[ $feed_id ]['yfym_skip_products_without_pic'] = yfym_optionGET( 'yfym_skip_products_without_pic', $feed_id, 'for_update_option' );
		$feed_id++;
		$yfym_registered_feeds_arr = array(
			0 => array( 'last_id' => $i ),
			1 => array( 'id' => $i )
		);
	}

	if ( is_multisite() ) {
		update_blog_option( get_current_blog_id(), 'yfym_settings_arr', $yfym_settings_arr );
		update_blog_option( get_current_blog_id(), 'yfym_registered_feeds_arr', $yfym_registered_feeds_arr );
	} else {
		update_option( 'yfym_settings_arr', $yfym_settings_arr );
		update_option( 'yfym_registered_feeds_arr', $yfym_registered_feeds_arr );
	}
	$feed_id = '1';
	for ( $i = 1; $i < yfym_ALLNUMFEED + 1; $i++ ) {
		yfym_optionDEL( 'yfym_shop_sku', $feed_id );
		yfym_optionDEL( 'yfym_count', $feed_id );
		yfym_optionDEL( 'yfym_auto_disabled', $feed_id );
		yfym_optionDEL( 'yfym_amount', $feed_id );
		yfym_optionDEL( 'yfym_manufacturer', $feed_id );

		yfym_optionDEL( 'yfym_shop_name', $feed_id );
		yfym_optionDEL( 'yfym_company_name', $feed_id );
		yfym_optionDEL( 'yfym_main_product', $feed_id );
		yfym_optionDEL( 'yfym_version', $feed_id );
		yfym_optionDEL( 'yfym_status_cron', $feed_id );
		yfym_optionDEL( 'yfym_whot_export', $feed_id );
		yfym_optionDEL( 'yfym_yml_rules', $feed_id );
		yfym_optionDEL( 'yfym_skip_missing_products', $feed_id );
		yfym_optionDEL( 'yfym_date_save_set', $feed_id );
		yfym_optionDEL( 'yfym_separator_type', $feed_id );
		yfym_optionDEL( 'yfym_behavior_onbackorder', $feed_id );
		yfym_optionDEL( 'yfym_behavior_stip_symbol', $feed_id );
		yfym_optionDEL( 'yfym_feed_assignment', $feed_id );
		yfym_optionDEL( 'yfym_file_extension', $feed_id );
		yfym_optionDEL( 'yfym_date_sborki', $feed_id );
		yfym_optionDEL( 'yfym_type_sborki', $feed_id );
		yfym_optionDEL( 'yfym_vendor', $feed_id );
		yfym_optionDEL( 'yfym_vendor_post_meta', $feed_id );
		yfym_optionDEL( 'yfym_model', $feed_id );
/*?*/yfym_optionDEL( 'yfym_product_tag_arr', $feed_id );
		yfym_optionDEL( 'yfym_file_url', $feed_id );
		yfym_optionDEL( 'yfym_file_file', $feed_id );
		yfym_optionDEL( 'yfym_ufup', $feed_id );
		yfym_optionDEL( 'yfym_magazin_type', $feed_id );
		yfym_optionDEL( 'yfym_pickup', $feed_id );
		yfym_optionDEL( 'yfym_store', $feed_id );
		yfym_optionDEL( 'yfym_delivery', $feed_id );
		yfym_optionDEL( 'yfym_delivery_options', $feed_id );
		yfym_optionDEL( 'yfym_delivery_cost', $feed_id );
		yfym_optionDEL( 'yfym_delivery_days', $feed_id );
		yfym_optionDEL( 'yfym_order_before', $feed_id );
		yfym_optionDEL( 'yfym_delivery_options2', $feed_id );
		yfym_optionDEL( 'yfym_delivery_cost2', $feed_id );
		yfym_optionDEL( 'yfym_delivery_days2', $feed_id );
		yfym_optionDEL( 'yfym_order_before2', $feed_id );
		yfym_optionDEL( 'yfym_sales_notes_cat', $feed_id );
		yfym_optionDEL( 'yfym_sales_notes', $feed_id );
		yfym_optionDEL( 'yfym_price_from', $feed_id );
		yfym_optionDEL( 'yfym_desc', $feed_id );
		yfym_optionDEL( 'yfym_the_content', $feed_id );
		yfym_optionDEL( 'yfym_var_desc_priority', $feed_id );
		yfym_optionDEL( 'yfym_clear_get', $feed_id );
		yfym_optionDEL( 'yfym_barcode', $feed_id );
		yfym_optionDEL( 'yfym_barcode_post_meta', $feed_id );
		yfym_optionDEL( 'yfym_vendorcode', $feed_id );
		yfym_optionDEL( 'yfym_enable_auto_discount', $feed_id );
		yfym_optionDEL( 'yfym_expiry', $feed_id );
		yfym_optionDEL( 'yfym_downloadable', $feed_id );
		yfym_optionDEL( 'yfym_age', $feed_id );
		yfym_optionDEL( 'yfym_country_of_origin', $feed_id );
		yfym_optionDEL( 'yfym_manufacturer_warranty', $feed_id );
		yfym_optionDEL( 'yfym_adult', $feed_id );
		yfym_optionDEL( 'yfym_wooc_currencies', $feed_id );
		yfym_optionDEL( 'yfym_oldprice', $feed_id );
		yfym_optionDEL( 'yfym_vat', $feed_id );
		yfym_optionDEL( 'yfym_step_export', $feed_id );
		yfym_optionDEL( 'yfym_errors', $feed_id );
		yfym_optionDEL( 'yfym_enable_auto_discounts', $feed_id );
		yfym_optionDEL( 'yfym_skip_backorders_products', $feed_id );
		yfym_optionDEL( 'yfym_no_default_png_products', $feed_id );
		yfym_optionDEL( 'yfym_skip_products_without_pic', $feed_id );
		$feed_id++;
	}

	// перезапустим крон-задачи
	for ( $i = 1; $i < yfym_number_all_feeds(); $i++ ) {
		$feed_id = (string) $i;
		$status_sborki = (int) yfym_optionGET( 'yfym_status_sborki', $feed_id );
		$yfym_status_cron = yfym_optionGET( 'yfym_status_cron', $feed_id, 'set_arr' );
		if ( $yfym_status_cron === 'off' ) {
			continue;
		}
		$recurrence = $yfym_status_cron;
		wp_clear_scheduled_hook( 'yfym_cron_period', array( $feed_id ) );
		wp_schedule_event( time(), $recurrence, 'yfym_cron_period', array( $feed_id ) );
		new YFYM_Error_Log( 
			'FEED № ' . $feed_id . '; yfym_cron_period внесен в список заданий; Файл: export.php; Строка: ' . __LINE__ 
		);
	}
}

/**
 * Возвращает количетсво всех фидов
 * 
 * @since 3.5.0
 *
 * @return int
 */
function yfym_number_all_feeds() {
	$yfym_settings_arr = univ_option_get( 'yfym_settings_arr' );
	if ( false === $yfym_settings_arr ) {
		return -1;
	} else {
		return count( $yfym_settings_arr );
	}
}

/**
 * Функция калибровки
 * 
 * @since 0.1.0
 * 
 * @deprecated 2.0.0 (03-03-2023)
 */
function yfym_calibration( $yfym_textarea_info ) {
	$yfym_textarea_info_arr = explode( 'txY5L8', $yfym_textarea_info );
	$name1 = $yfym_textarea_info_arr[2] . '_' . $yfym_textarea_info_arr[3] . 'nse_status';
	$name2 = $yfym_textarea_info_arr[2] . '_' . $yfym_textarea_info_arr[3] . 'nse_date';
	$name3 = $yfym_textarea_info_arr[2] . '_sto';

	if ( $yfym_textarea_info_arr[0] == '1' ) {
		if ( is_multisite() ) {
			update_blog_option( get_current_blog_id(), $name1, 'ok' );
			update_blog_option( get_current_blog_id(), $name2, $yfym_textarea_info_arr[1] );
			update_blog_option( get_current_blog_id(), $name3, 'ok' );
		} else {
			update_option( $name1, 'ok' );
			update_option( $name2, $yfym_textarea_info_arr[1] );
			update_option( $name3, 'ok' );
		}
	} else {
		if ( is_multisite() ) {
			delete_blog_option( get_current_blog_id(), $name1 );
			delete_blog_option( get_current_blog_id(), $name2 );
			delete_blog_option( get_current_blog_id(), $name3 );
		} else {
			delete_option( $name1 );
			delete_option( $name2 );
			delete_option( $name3 );
		}
	}

	return get_option( $name3 );
}

/**
 * Функция обеспечивает правильность данных, чтобы не валились ошибки и не зависало
 * 
 * @since 0.1.0
 * 
 */
function sanitize_variable_from_yml($args, $p = 'yfymp') {
	$is_string = common_option_get('woo'.'_hook_isc'.$p);
	if ($is_string == '202' && $is_string !== $args) {
		return true;
	} else {
		return false;
	}
}

/**
 * Возвращает URL без get-параметров или возвращаем только get-параметры
 * 
 * @since 1.2.5
 * 
 * @deprecated 3.11.0 (17-06-2023)
 */
function deleteGET( $url, $whot = 'url' ) {
	$url = str_replace( "&amp;", "&", $url ); // Заменяем сущности на амперсанд, если требуется
	// Разбиваем URL на 2 части: до знака ? и после
	list( $url_part, $get_part ) = array_pad( explode( "?", $url ), 2, "" ); 
	if ( $whot == 'url' ) {
		$url_part = str_replace( " ", "%20", $url_part ); // заменим пробел на сущность
		return $url_part; // Возвращаем URL без get-параметров (до знака вопроса)
	} else if ( $whot == 'get' ) {
		return $get_part; // Возвращаем get-параметры (без знака вопроса)
	} else {
		return false;
	}
}
/**
 * Записывает файл логов /wp-content/uploads/yfym/yfym.log
 * 
 * @since  2.0.0
 * 
 * @deprecated 3.12.0 (20-06-2023)
 */
function yfym_error_log( $text, $i ) {
	if ( yfym_KEEPLOGS !== 'on' ) {
		return;
	}
	$upload_dir = (object) wp_get_upload_dir();
	$name_dir = $upload_dir->basedir . "/yfym";
	// подготовим массив для записи в файл логов
	if ( is_array( $text ) ) {
		$r = get_array_as_string( $text );
		unset( $text );
		$text = $r;
	}
	if ( is_dir( $name_dir ) ) {
		$filename = $name_dir . '/yfym.log';
		file_put_contents( $filename, '[' . date( 'Y-m-d H:i:s' ) . '] ' . $text . PHP_EOL, FILE_APPEND );
	} else {
		if ( ! mkdir( $name_dir ) ) {
			error_log( 'Нет папки yfym! И создать не вышло! $name_dir =' . $name_dir . '; Файл: functions.php; Строка: ' . __LINE__, 0 );
		} else {
			error_log( 'Создали папку yfym!; Файл: functions.php; Строка: ' . __LINE__, 0 );
			$filename = $name_dir . '/yfym.log';
			file_put_contents( $filename, '[' . date( 'Y-m-d H:i:s' ) . '] ' . $text . PHP_EOL, FILE_APPEND );
		}
	}
	return;
}

/**
 * TODO: Удалить в след версиях
 * 
 * получить все атрибуты вукомерца
 * 
 * С версии 3.0.0
 *  
 * @deprecated 3.12.0 (20-06-2023)
 */
function yfym_get_attributes() {
	$result = array();
	$attribute_taxonomies = wc_get_attribute_taxonomies();
	if ( count( $attribute_taxonomies ) > 0 ) {
		$i = 0;
		foreach ( $attribute_taxonomies as $one_tax ) {
			/**
			 * $one_tax->attribute_id => 6
			 * $one_tax->attribute_name] => слаг (на инглише или русском)
			 * $one_tax->attribute_label] => Еще один атрибут (это как раз название)
			 * $one_tax->attribute_type] => select 
			 * $one_tax->attribute_orderby] => menu_order
			 * $one_tax->attribute_public] => 0			
			 */
			$result[ $i ]['id'] = $one_tax->attribute_id;
			$result[ $i ]['name'] = $one_tax->attribute_label;
			$i++;
		}
	}
	return $result;
}

/**
 * TODO: Удалить в след версиях
 * 
 * получить все атрибуты вукомерца
 * 
 * С версии 3.0.0
 *  
 * @deprecated 3.12.0 (20-06-2023)
 */
function get_attributes() {
	$result = array();
	$attribute_taxonomies = wc_get_attribute_taxonomies();
	if ( count( $attribute_taxonomies ) > 0 ) {
		$i = 0;
		foreach ( $attribute_taxonomies as $one_tax ) {
			/**
			 * $one_tax->attribute_id => 6
			 * $one_tax->attribute_name] => слаг (на инглише или русском)
			 * $one_tax->attribute_label] => Еще один атрибут (это как раз название)
			 * $one_tax->attribute_type] => select 
			 * $one_tax->attribute_orderby] => menu_order
			 * $one_tax->attribute_public] => 0			
			 */
			$result[ $i ]['id'] = $one_tax->attribute_id;
			$result[ $i ]['name'] = $one_tax->attribute_label;
			$i++;
		}
	}
	return $result;
}