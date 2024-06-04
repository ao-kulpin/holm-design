<?php if (!defined('ABSPATH')) {exit;}
/**
* Traits Step_Quantity for simple products
*
* @author		Maxim Glazunov
* @link			https://icopydoc.ru/
* @since		1.0.0
*
* @return 		$result_xml (string)
*
* @depends		class:		Get_Paired_Tag
*				methods: 	get_product
*							get_feed_id
*							add_skip_reason
*				functions:	yfym_optionGET 
*/

trait YFYM_T_Simple_Get_Step_Quantity {
	public function get_step_quantity($tag_name = 'step-quantity', $result_xml = '') {
		$product = $this->product;

		if ((get_post_meta($product->get_id(), '_yfym_step_quantity', true) !== '') && (get_post_meta($product->get_id(), '_yfym_step_quantity', true) !== '')) {
			$yfym_step_quantity = get_post_meta($product->get_id(), '_yfym_step_quantity', true);
			$result_xml = new Get_Paired_Tag($tag_name, $yfym_step_quantity);
		}

		$result_xml = apply_filters('y4ym_f_simple_tag_step_quantity', $result_xml, array('product' => $product), $this->get_feed_id());
		return $result_xml;
	}
}
?>