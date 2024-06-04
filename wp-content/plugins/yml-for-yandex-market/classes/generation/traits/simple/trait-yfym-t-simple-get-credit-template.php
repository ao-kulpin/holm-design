<?php if (!defined('ABSPATH')) {exit;}
/**
* Traits Credit_Template for simple products
*
* @author		Maxim Glazunov
* @link			https://icopydoc.ru/
* @since		1.0.0
*
* @return 		$result_xml (string)
*
* @depends		class:		Get_Open_Tag
*				methods: 	get_product
*							get_feed_id
*				functions:	 
*/

trait YFYM_T_Simple_Get_Credit_Template {
	public function get_credit_template($tag_name = 'credit-template', $result_xml = '') {
		$product = $this->get_product();

		if ((get_post_meta($product->get_id(), 'yfym_credit_template', true) !== '') && (get_post_meta($product->get_id(), 'yfym_credit_template', true) !== '')) {
			$yfym_credit_template = get_post_meta($product->get_id(), 'yfym_credit_template', true);
			$result_xml = new Get_Open_Tag($tag_name, array('id' => $yfym_credit_template), true);
		}

		$result_xml = apply_filters('y4ym_f_simple_tag_credit_template', $result_xml, array('product' => $product), $this->get_feed_id());
		return $result_xml;
	}
}
?>