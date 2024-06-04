<?php
/**
 * Traits Expiry for simple products
 *
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

trait YFYM_T_Simple_Get_Expiry {
	/**
	 * Summary of get_expiry
	 * 
	 * @param string $tag_name - Optional
	 * @param string $result_xml - Optional
	 * 
	 * @return string
	 */
	public function get_expiry( $tag_name = 'expiry', $result_xml = '' ) {
		$expiry = common_option_get( 'yfym_expiry', false, $this->get_feed_id(), 'yfym' );
		if ( ! empty( $expiry ) && $expiry !== 'off' ) {
			$expiry = (int) $expiry;
			$expiry_yml = $this->get_product()->get_attribute( wc_attribute_taxonomy_name_by_id( $expiry ) );
			if ( ! empty( $expiry_yml ) ) {
				$result_xml = new Get_Paired_Tag( $tag_name, strtoupper( yfym_replace_decode( $expiry_yml ) ) );
			}
		}

		$result_xml = apply_filters(
			'y4ym_f_simple_tag_expiry',
			$result_xml,
			[ 'product' => $this->get_product() ],
			$this->get_feed_id()

		);
		return $result_xml;
	}
}