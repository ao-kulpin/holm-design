<?php
/**
 * Traits Store for variable products
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
 *                                      get_offer
 *                                      get_feed_id
 *                          functions:  common_option_get
 *                          constants:  
 */
defined( 'ABSPATH' ) || exit;

trait YFYM_T_Variable_Get_Store {
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
		if ( false === $store || $store == '' ) {
		} else {
			$result_xml = new Get_Paired_Tag( $tag_name, $store );
		}

		$result_xml = apply_filters(
			'y4ym_f_variable_tag_store',
			$result_xml,
			[ 
				'product' => $this->get_product(),
				'offer' => $this->get_offer()
			],
			$this->get_feed_id()
		);
		return $result_xml;
	}
}