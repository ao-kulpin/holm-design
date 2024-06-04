<?php
/**
 * Traits Vat for simple products
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
 *                          functions:  common_option_get
 *                          constants:  
 */
defined( 'ABSPATH' ) || exit;

trait YFYM_T_Simple_Get_Vat {
	/**
	 * Summary of get_vat
	 * 
	 * @param string $tag_name - Optional
	 * @param string $result_xml - Optional
	 * 
	 * @return string
	 */
	public function get_vat( $tag_name = 'vat', $result_xml = '' ) {
		$tag_value = '';

		$tag_value = common_option_get( 'yfym_vat', false, $this->get_feed_id(), 'yfym' );
		if ( $tag_value === 'disabled' ) {
			$result_yml_vat = '';
		} else {
			if ( get_post_meta( $this->get_product()->get_id(), 'yfym_individual_vat', true ) !== '' ) {
				$individual_vat = get_post_meta( $this->get_product()->get_id(), 'yfym_individual_vat', true );
			} else {
				$individual_vat = 'global';
			}
			if ( $individual_vat === 'global' ) {
				if ( $tag_value === 'enable' ) {
					$result_yml_vat = '';
				} else {
					$result_yml_vat = new Get_Paired_Tag( $tag_name, $tag_value );
				}
			} else {
				$result_yml_vat = new Get_Paired_Tag( $tag_name, $individual_vat );
			}
		}
		$result_xml = $result_yml_vat;

		$result_xml = apply_filters(
			'y4ym_f_simple_tag_vat',
			$result_xml,
			[ 'product' => $this->get_product() ],
			$this->get_feed_id()
		);
		return $result_xml;
	}
}