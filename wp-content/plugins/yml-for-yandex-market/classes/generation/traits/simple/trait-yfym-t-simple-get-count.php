<?php
/**
 * Traits Count for simple products
 *
 * @package                 YML for Yandex Market
 * @subpackage              
 * @since                   0.1.0
 * 
 * @version                 4.0.5 (20-09-2023)
 * @author                  Maxim Glazunov
 * @link                    https://icopydoc.ru/
 * @see                     
 *
 * @depends                 classes:    Get_Paired_Tag
 *                          traits:     
 *                          methods:    get_product
 *                                      get_feed_id
 *                          functions:  common_option_get
 *                          constants:  
 */
defined( 'ABSPATH' ) || exit;

trait YFYM_T_Simple_Get_Count {
	/**
	 * Summary of get_count
	 * 
	 * @param string $tag_name - Optional
	 * @param string $result_xml - Optional
	 * 
	 * @return string
	 */
	public function get_count( $tag_name = 'count', $result_xml = '' ) {
		$tag_value = '';

		$yfym_count = common_option_get( 'yfym_count', false, $this->get_feed_id(), 'yfym' );
		if ( $yfym_count === 'enabled' ) {
			if ( true == $this->get_product()->get_manage_stock() ) { // включено управление запасом
				$stock_quantity = $this->get_product()->get_stock_quantity();
				if ( $stock_quantity > -1 ) {
					$tag_value = $stock_quantity;
				} else {
					$tag_value = (int) 0;
				}
			}
		}

		$tag_value = apply_filters(
			'y4ym_f_simple_tag_value_count',
			$tag_value,
			[ 'product' => $this->get_product() ],
			$this->get_feed_id()
		);
		if ( $tag_value !== '' ) {
			$tag_name = apply_filters(
				'y4ym_f_simple_tag_name_count',
				$tag_name,
				[ 'product' => $this->get_product() ],
				$this->get_feed_id()
			);
			$result_xml = new Get_Paired_Tag( $tag_name, $tag_value );
		}

		$result_xml = apply_filters(
			'y4ym_f_simple_tag_count',
			$result_xml,
			[ 'product' => $this->get_product() ],
			$this->get_feed_id()
		);
		return $result_xml;
	}
}