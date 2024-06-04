<?php if (!defined('ABSPATH')) {exit;}
/**
* Traits Keywords for variable products
*
* @author		Maxim Glazunov
* @link			https://icopydoc.ru/
* @since		1.0.0
*
* @return 		$result_xml (string)
*
* @depends		class:		Get_Open_Tag
*				methods: 	get_product
*							get_offer
*							get_feed_id
*				functions: 
*/

trait YFYM_T_Variable_Get_Keywords {
	public function get_keywords($tag_name = 'keywords', $result_xml = '') {
		$product = $this->get_product();
		$offer = $this->get_offer();

		$result_xml = apply_filters('y4ym_f_variable_tag_keywords', $result_xml, array('product' => $product, 'offer' => $offer), $this->get_feed_id());
		return $result_xml;
	}
}
?>