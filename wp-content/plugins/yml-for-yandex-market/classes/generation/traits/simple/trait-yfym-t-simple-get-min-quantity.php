<?php if (!defined('ABSPATH')) {exit;}
/**
* Traits Min_Quantity for simple products
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
*				functions:	  
*/

trait YFYM_T_Simple_Get_Min_Quantity {
	public function get_min_quantity($tag_name = 'min-quantity', $result_xml = '') {
		$product = $this->product;

		if ((get_post_meta($product->get_id(), '_yfym_min_quantity', true) !== '') && (get_post_meta($product->get_id(), '_yfym_min_quantity', true) !== '')) {
			$yfym_min_quantity = get_post_meta($product->get_id(), '_yfym_min_quantity', true);
			$result_xml = new Get_Paired_Tag($tag_name, $yfym_min_quantity);
		}

		$result_xml = apply_filters('y4ym_f_simple_tag_min_quantity', $result_xml, array('product' => $product), $this->get_feed_id());
		return $result_xml;
	}
}
?>