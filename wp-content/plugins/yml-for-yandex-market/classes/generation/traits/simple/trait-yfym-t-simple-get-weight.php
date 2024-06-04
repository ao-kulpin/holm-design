<?php
/**
 * Traits Weight for simple products
 * 
 * @package                 YML for Yandex Market
 * @subpackage              
 * @since                   0.1.0
 * 
 * @version                 4.0.3 (08-09-2023)
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

trait YFYM_T_Simple_Get_Weight {
	/**
	 * Summary of get_weight
	 * 
	 * @param string $tag_name - Optional
	 * @param string $result_xml - Optional
	 * 
	 * @return string
	 */
	public function get_weight( $tag_name = 'weight', $result_xml = '' ) {
		$tag_value = '';

		$weight_yml = $this->get_product()->get_weight(); // вес
		if ( ! empty( $weight_yml ) ) {
			$tag_value = round( wc_get_weight( $weight_yml, 'kg' ), 3 );
		}

		$tag_value = apply_filters(
			'y4ym_f_simple_tag_value_weight',
			$tag_value,
			[ 'product' => $this->get_product() ],
			$this->get_feed_id()
		);
		if ( ! empty( $tag_value ) ) {
			$tag_name = apply_filters(
				'y4ym_f_simple_tag_name_weight',
				$tag_name,
				[ 'product' => $this->get_product() ],
				$this->get_feed_id()
			);
			$result_xml = new Get_Paired_Tag( $tag_name, $tag_value );
		}

		$result_xml = apply_filters(
			'y4ym_f_simple_tag_weight',
			$result_xml,
			[ 'product' => $this->get_product() ],
			$this->get_feed_id()
		);
		return $result_xml;
	}
}