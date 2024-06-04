<?php
/**
 * Traits Store for simple products
 *
 * @package                 YML for Yandex Market
 * @subpackage              
 * @since                   0.1.0
 * 
 * @version                 4.0.7 (02-10-2023)
 * @author                  Maxim Glazunov
 * @link                    https://icopydoc.ru/
 * @see                     
 *
 * @depends					classes:    Get_Paired_Tag
 *                          traits:     
 *                          methods:    get_product
 *                                      get_feed_id
 *                          functions:  common_option_get
 *                          constants:  
 */
defined( 'ABSPATH' ) || exit;

trait YFYM_T_Simple_Get_Store {
	/**
	 * Summary of get_store
	 * 
	 * @param string $tag_name - Optional
	 * @param string $result_xml - Optional
	 * 
	 * @return string
	 */
	public function get_store( $tag_name = 'store', $result_xml = '' ) {
		// Возможность купить товар в розничном магазине. // true или false
		$store = yfym_optionGET( 'yfym_store', $this->get_feed_id(), 'set_arr' );
		if ( $store === false || $store == '' ) {
		} else {
			$result_xml = new Get_Paired_Tag( $tag_name, $store );
		}

		$result_xml = apply_filters(
			'y4ym_f_simple_tag_store',
			$result_xml,
			[ 'product' => $this->get_product() ],
			$this->get_feed_id()
		);
		return $result_xml;
	}
}