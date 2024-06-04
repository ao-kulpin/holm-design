<?php
/**
 * Traits Price_Rrp for simple products
 *
 * @package                 YML for Yandex Market
 * @subpackage              
 * @since                   0.1.0
 * 
 * @version                 4.0.0 (29-08-2023)
 * @author                  Maxim Glazunov
 * @link                    https://icopydoc.ru/
 * @see                     
 *
 * @depends					classes:    Get_Paired_Tag
 *                          traits:     
 *                          methods:    get_product
 *                                      get_feed_id
 *                          functions:  
 *                          constants:  
 */
defined( 'ABSPATH' ) || exit;

trait YFYM_T_Simple_Get_Price_Rrp {
	/**
	 * Summary of get_price_rrp
	 * 
	 * @param string $tag_name - Optional
	 * @param string $result_xml - Optional
	 * 
	 * @return string
	 */
	public function get_price_rrp( $tag_name = 'price_rrp', $result_xml = '' ) {
		if ( get_post_meta( $this->get_product()->get_id(), '_yfym_price_rrp', true ) !== '' ) {
			$tag_value = get_post_meta( $this->get_product()->get_id(), '_yfym_price_rrp', true );
			$result_xml .= new Get_Paired_Tag( $tag_name, $tag_value );
		}

		$result_xml = apply_filters(
			'y4ym_f_simple_tag_price_rrp',
			$result_xml,
			[ 'product' => $this->get_product() ],
			$this->get_feed_id()
		);
		return $result_xml;
	}
}