<?php
/**
 * Traits Tn Ved Codes for variable products
 *
 * @package                 YML for Yandex Market
 * @subpackage              
 * @since                   0.1.0
 * 
 * @version                 4.0.1 (31-08-2023)
 * @author                  Maxim Glazunov
 * @link                    https://icopydoc.ru/
 * @see                     
 *
 * @depends                 classes:    Get_Paired_Tag
 *                          traits:     
 *                          methods:    get_product
 *                                      get_offer
 *                                      get_feed_id
 *                          functions:  
 *                          constants:  
 */
defined( 'ABSPATH' ) || exit;

trait YFYM_T_Variable_Get_Tn_Ved_Codes {
	/**
	 * Summary of get_tn_ved_codes
	 * 
	 * @param string $tag_name - Optional
	 * @param string $result_xml - Optional
	 * 
	 * @return string
	 */
	public function get_tn_ved_codes( $tag_name = 'tn-ved-codes', $result_xml = '' ) {
		if ( get_post_meta( $this->get_product()->get_id(), '_yfym_tn_ved_code', true ) !== '' ) {
			$tag_value = get_post_meta( $this->get_product()->get_id(), '_yfym_tn_ved_code', true );
			$result_xml .= new YFYM_Get_Open_Tag( $tag_name );
			$result_xml .= new YFYM_Get_Paired_Tag( 'tn-ved-code', $tag_value );
			$result_xml .= new YFYM_Get_Closed_Tag( $tag_name );
		}

		$result_xml = apply_filters(
			'y4ym_f_variable_tag_tn_ved_code',
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