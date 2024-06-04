<?php if (!defined('ABSPATH')) {exit;}
/**
* Traits Manufacturer for variable products
*
* @author		Maxim Glazunov
* @link			https://icopydoc.ru/
* @since		1.0.0
*
* @return 		$result_xml (string)
*
* @depends		class:		Get_Paired_Tag
*				methods: 	get_product
*							get_offer
*							get_feed_id
*				functions:	yfym_optionGET
*/

trait YFYM_T_Variable_Get_Manufacturer {
	public function get_manufacturer($tag_name = 'manufacturer', $result_xml = '') {
		$product = $this->get_product();
		$offer = $this->get_offer();
		$tag_value = '';

		$manufacturer = yfym_optionGET('yfym_manufacturer', $this->get_feed_id(), 'set_arr');
		if (empty($manufacturer) || $manufacturer === 'off' || $manufacturer === 'disabled') { } else {
			$manufacturer = (int)$manufacturer;
			$tag_value = $offer->get_attribute(wc_attribute_taxonomy_name_by_id($manufacturer));
			if (empty($tag_value)) {	
				$tag_value = $product->get_attribute(wc_attribute_taxonomy_name_by_id($manufacturer));
			}
		}
		
		$tag_value = apply_filters('y4ym_f_variable_tag_value_manufacturer', $tag_value, array('product' => $product, 'offer' => $offer), $this->get_feed_id());
		if (!empty($tag_value)) {	
			$tag_name = apply_filters('y4ym_f_variable_tag_name_manufacturer', $tag_name, array('product' => $product, 'offer' => $offer), $this->get_feed_id());
			$result_xml = new Get_Paired_Tag($tag_name, $tag_value);
		}

		$result_xml = apply_filters('y4ym_f_variable_tag_manufacturer', $result_xml, array('product' => $product, 'offer' => $offer), $this->get_feed_id());
		return $result_xml;
	}
}
?>