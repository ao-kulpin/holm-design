<?php if (!defined('ABSPATH')) {exit;}
/**
* Traits Expiry for variable products
*
* @author		Maxim Glazunov
* @link			https://icopydoc.ru/
* @since		1.0.0
*
* @return 		$result_xml (string)
*
* @depends		class:	Get_Paired_Tag
*				methods: add_skip_reason
*				functions: 
*/

trait YFYM_T_Variable_Get_Expiry {
	public function get_expiry($tag_name = 'expiry', $result_xml = '') {
		$product = $this->product;
		$offer = $this->offer;

		$expiry = yfym_optionGET('yfym_expiry', $this->get_feed_id(), 'set_arr');
		if (!empty($expiry) && $expiry !== 'off') {
			$expiry = (int)$expiry;
			$expiry_yml = $offer->get_attribute(wc_attribute_taxonomy_name_by_id($expiry));
			if (!empty($expiry_yml)) {	
				$result_xml .= "<expiry>".strtoupper(yfym_replace_decode($expiry_yml))."</expiry>".PHP_EOL;		
			} else {
				$expiry_yml = $product->get_attribute(wc_attribute_taxonomy_name_by_id($expiry));
				if (!empty($expiry_yml)) {	
					$result_xml .= "<expiry>".strtoupper(yfym_replace_decode($expiry_yml))."</expiry>".PHP_EOL;		
				}		
			}
		}

		$result_xml = apply_filters('y4ym_f_variable_tag_expiry', $result_xml, array('product' => $product, 'offer' => $offer), $this->get_feed_id());
		return $result_xml;
	}
}
?>