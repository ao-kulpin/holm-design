<?php if (!defined('ABSPATH')) {exit;}
/**
* Traits Manufacturer_Warranty for simple products
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
*							yfym_replace_decode
*/

trait YFYM_T_Simple_Get_Manufacturer_Warranty {
	public function get_manufacturer_warranty($tag_name = 'manufacturer_warranty', $result_xml = '') {
		$product = $this->product;

		$manufacturer_warranty = yfym_optionGET('yfym_manufacturer_warranty', $this->get_feed_id(), 'set_arr');
		if (!empty($manufacturer_warranty) && $manufacturer_warranty !== 'off') {			
			if ($manufacturer_warranty === 'alltrue') {
				$result_xml .= "<manufacturer_warranty>true</manufacturer_warranty>".PHP_EOL;
			} else if ($manufacturer_warranty === 'allfalse') {
				$result_xml .= "<manufacturer_warranty>false</manufacturer_warranty>".PHP_EOL;
			} else {
				$manufacturer_warranty = (int)$manufacturer_warranty;		
				$manufacturer_warranty_yml = $product->get_attribute(wc_attribute_taxonomy_name_by_id($manufacturer_warranty));
				if (!empty($manufacturer_warranty_yml)) {
					$result_xml .= "<manufacturer_warranty>".yfym_replace_decode($manufacturer_warranty_yml)."</manufacturer_warranty>".PHP_EOL;
				}
			}			
		}

		$result_xml = apply_filters('y4ym_f_simple_tag_manufacturer_warranty', $result_xml, array('product' => $product), $this->get_feed_id());
		return $result_xml;
	}
}
?>