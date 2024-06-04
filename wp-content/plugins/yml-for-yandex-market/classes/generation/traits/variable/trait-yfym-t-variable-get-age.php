<?php
/**
 * Traits Age for variable products
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
 *                                      get_offer
 *                                      get_feed_id
 *                          functions:  common_option_get
 *                          constants:  
 */
defined( 'ABSPATH' ) || exit;

trait YFYM_T_Variable_Get_Age {
	/**
	 * Summary of get_age
	 * 
	 * @param string $tag_name - Optional
	 * @param string $result_xml - Optional
	 * 
	 * @return string
	 */
	public function get_age( $tag_name = 'age', $result_xml = '' ) {
		$tag_value = '';

		$age = common_option_get( 'yfym_age', false, $this->get_feed_id(), 'yfym' );
		if ( empty( $age ) || $age === 'disabled' ) {

		} else {
			$age = (int) $age;
			$tag_value = $this->get_offer()->get_attribute( wc_attribute_taxonomy_name_by_id( $age ) );
			if ( empty( $tag_value ) ) {
				$tag_value = $this->get_product()->get_attribute( wc_attribute_taxonomy_name_by_id( $age ) );
			}
		}

		$tag_value = apply_filters(
			'y4ym_f_variable_tag_value_age',
			$tag_value,
			[ 
				'product' => $this->get_product(),
				'offer' => $this->get_offer()
			],
			$this->get_feed_id()
		);

		if ( ! empty( $tag_value ) ) {

			$tag_name = apply_filters(
				'y4ym_f_variable_tag_name_age',
				$tag_name,
				[ 
					'product' => $this->get_product(),
					'offer' => $this->get_offer()
				],
				$this->get_feed_id()
			);

			$result_xml = new Get_Paired_Tag( $tag_name, $tag_value );
		}

		$result_xml = apply_filters(
			'y4ym_f_variable_tag_age',
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