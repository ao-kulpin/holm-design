<?php
/**
 * Traits Country_Of_Orgin for simple products
 *
 * @package                 YML for Yandex Market
 * @subpackage              
 * @since                   0.1.0
 * 
 * @version                 4.0.8 (04-10-2023)
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

trait YFYM_T_Simple_Get_Country_Of_Orgin {
	/**
	 * Summary of get_country_of_origin
	 * 
	 * @param string $tag_name - Optional
	 * @param string $result_xml - Optional
	 * 
	 * @return string
	 */
	public function get_country_of_origin( $tag_name = 'country_of_origin', $result_xml = '' ) {
		$tag_value = '';

		$country_of_origin = common_option_get( 'yfym_country_of_origin', false, $this->get_feed_id(), 'yfym' );
		if ( empty( $country_of_origin ) || $country_of_origin === 'disabled' ) {

		} else {
			$country_of_origin = (int) $country_of_origin;
			$tag_value = $this->get_product()->get_attribute( wc_attribute_taxonomy_name_by_id( $country_of_origin ) );
		}
		$tag_value = apply_filters(
			'y4ym_f_simple_tag_value_country_of_origin',
			$tag_value,
			[ 'product' => $this->get_product() ],
			$this->get_feed_id()
		);
		if ( ! empty( $tag_value ) ) {
			$tag_name = apply_filters(
				'y4ym_f_simple_tag_name_country_of_origin',
				$tag_name,
				[ 'product' => $this->get_product() ],
				$this->get_feed_id()
			);
			$result_xml = new Get_Paired_Tag( $tag_name, $tag_value );
		}

		$result_xml = apply_filters(
			'y4ym_f_simple_tag_country_of_origin',
			$result_xml,
			[ 'product' => $this->get_product() ],
			$this->get_feed_id()
		);
		return $result_xml;
	}
}