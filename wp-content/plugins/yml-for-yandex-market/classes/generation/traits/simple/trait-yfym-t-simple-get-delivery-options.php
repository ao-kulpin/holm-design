<?php if (!defined('ABSPATH')) {exit;}
/**
* Traits for simple products
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

trait YFYM_T_Simple_Get_Delivery_Options {
	public function get_delivery_options($tag_name = 'delivery-options', $result_xml = '', $rules = '') {
		$product = $this->get_product();
		$tag_value = '';

		if ((get_post_meta($product->get_id(), 'yfym_cost', true) !== '') && (get_post_meta($product->get_id(), 'yfym_days', true) !== '')) {
			$yfym_cost = get_post_meta($product->get_id(), 'yfym_cost', true);
			$yfym_days = get_post_meta($product->get_id(), 'yfym_days', true);	
			if (get_post_meta($product->get_id(), 'yfym_order_before', true) !== '') {
				$yfym_order_before = get_post_meta($product->get_id(), 'yfym_order_before', true);
				$yfym_order_before_yml = ' order-before="'.$yfym_order_before.'"';
			} else {
				$yfym_order_before_yml = '';
			}	

			if ($rules === 'sbermegamarket') {
				$result_xml = new Get_Open_Tag('shipment-options');
				$result_xml .= '<option days="'.$yfym_days.'"'.$yfym_order_before_yml.'/>'.PHP_EOL;
				$result_xml .= new Get_Closed_Tag('shipment-options');
			} else {
				$result_xml = new Get_Open_Tag($tag_name);
				$result_xml .= '<option cost="'.$yfym_cost.'" days="'.$yfym_days.'"'.$yfym_order_before_yml.'/>'.PHP_EOL;
				$result_xml .= new Get_Closed_Tag($tag_name);
			}
		}

		$result_xml = apply_filters('y4ym_f_simple_tag_delivery_options', $result_xml, array('product' => $product), $this->get_feed_id());
		return $result_xml;
	}
}
?>