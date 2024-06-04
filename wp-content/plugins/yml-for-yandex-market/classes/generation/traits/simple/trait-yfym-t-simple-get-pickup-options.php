<?php if (!defined('ABSPATH')) {exit;}
/**
* Traits Pickup_Option for simple products
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
*				functions:	yfym_optionGET
*/

trait YFYM_T_Simple_Get_Pickup_Options {
	public function get_pickup_options($tag_name = 'pickup-options', $result_xml = '') {
		$product = $this->product;
		
		if ((get_post_meta($product->get_id(), 'yfym_pickup_cost', true) !== '') && (get_post_meta($product->get_id(), 'yfym_pickup_days', true) !== '')) {
			$yfym_pickup_cost = get_post_meta($product->get_id(), 'yfym_pickup_cost', true);
			$yfym_pickup_days = get_post_meta($product->get_id(), 'yfym_pickup_days', true);	
			if (get_post_meta($product->get_id(), 'yfym_pickup_order_before', true) !== '') {
				$yfym_pickup_order_before = get_post_meta($product->get_id(), 'yfym_pickup_order_before', true);
				$yfym_pickup_order_before_yml = ' order-before="'.$yfym_pickup_order_before.'"';
			} else {
				$yfym_pickup_order_before_yml = '';
			}	
			$result_xml .= '<pickup-options>'.PHP_EOL;
			$result_xml .= '<option cost="'.$yfym_pickup_cost.'" days="'.$yfym_pickup_days.'"'.$yfym_pickup_order_before_yml.'/>'.PHP_EOL;
			$result_xml .= '</pickup-options>'.PHP_EOL;
		}

		$result_xml = apply_filters('y4ym_f_simple_tag_pickup_options', $result_xml, array('product' => $product), $this->get_feed_id());
		return $result_xml;
	}
}
?>