<?php
/**
 * Traits Period_Of_Validity_Days for simple products
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

trait YFYM_T_Simple_Get_Period_Of_Validity_Days {
	/**
	 * Summary of get_period_of_validity_days
	 * 
	 * @param string $tag_name - Optional
	 * @param string $result_xml - Optional
	 * 
	 * @return string
	 */
	public function get_period_of_validity_days( $tag_name = 'period-of-validity-days', $result_xml = '' ) {
		$tag_value = '';

		$period_of_validity_days = common_option_get( 'yfym_period_of_validity_days', false, $this->get_feed_id(), 'yfym' );
		if ( empty( $period_of_validity_days ) || $period_of_validity_days === 'disabled' ) {

		} else {
			$period_of_validity_days = (int) $period_of_validity_days;
			$tag_value = $this->get_product()->get_attribute( wc_attribute_taxonomy_name_by_id( $period_of_validity_days ) );
		}
		$tag_value = apply_filters(
			'y4ym_f_simple_tag_value_period_of_validity_days',
			$tag_value,
			[ 'product' => $this->get_product() ],
			$this->get_feed_id()
		);
		if ( ! empty( $tag_value ) ) {
			$tag_name = apply_filters(
				'y4ym_f_simple_tag_name_period_of_validity_days',
				$tag_name,
				[ 'product' => $this->get_product() ],
				$this->get_feed_id()
			);
			$result_xml = new Get_Paired_Tag( $tag_name, $tag_value );
		}

		$result_xml = apply_filters(
			'y4ym_f_simple_tag_period_of_validity_days',
			$result_xml,
			[ 'product' => $this->get_product() ],
			$this->get_feed_id()
		);
		return $result_xml;
	}
}