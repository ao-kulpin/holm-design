<?php
/**
 * Traits Model for simple products
 *
 * @package                 YML for Yandex Market
 * @subpackage              
 * @since                   0.1.0
 * 
 * @version                 4.0.9 (07-10-2023)
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

trait YFYM_T_Simple_Get_Model {
	/**
	 * Summary of get_model
	 * 
	 * @param string $tag_name - Optional
	 * @param string $result_xml - Optional
	 * 
	 * @return string
	 */
	public function get_model( $tag_name = 'model', $result_xml = '' ) {
		$tag_value = '';

		$yfym_model = yfym_optionGET( 'yfym_model', $this->get_feed_id(), 'set_arr' );
		switch ( $yfym_model ) {
			case "disabled": // выгружать штрихкод нет нужды		
				break;
			case "sku": // выгружать из артикула
				$tag_value = $this->get_product()->get_sku();
				break;
			default:
				$tag_value = apply_filters(
					'y4ym_f_simple_tag_value_switch_model',
					$tag_value,
					[ 
						'product' => $this->get_product(),
						'switch_value' => $yfym_model
					],
					$this->get_feed_id()
				);
				if ( $tag_value == '' ) {
					$yfym_model = (int) $yfym_model;
					$tag_value = $this->get_product()->get_attribute( wc_attribute_taxonomy_name_by_id( $yfym_model ) );
				}
		}

		$tag_value = apply_filters(
			'y4ym_f_simple_tag_value_model',
			$tag_value,
			[ 'product' => $this->get_product() ],
			$this->get_feed_id()
		);
		if ( ! empty( $tag_value ) ) {
			$tag_name = apply_filters(
				'y4ym_f_simple_tag_name_model',
				$tag_name,
				[ 'product' => $this->get_product() ],
				$this->get_feed_id()
			);
			$result_xml = new Get_Paired_Tag( $tag_name, $tag_value );
		}

		$result_xml = apply_filters(
			'y4ym_f_simple_tag_model',
			$result_xml,
			[ 'product' => $this->get_product() ],
			$this->get_feed_id()
		);
		return $result_xml;
	}
}