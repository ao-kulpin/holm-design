<?php if (!defined('ABSPATH')) {exit;}
/**
* Traits Delivery for simple products
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

trait YFYM_T_Simple_Get_Delivery {
	public function get_delivery($tag_name = 'delivery', $result_xml = '') {
		$product = $this->get_product();

		if (get_post_meta($product->get_id(), 'yfym_individual_delivery', true) !== '') {	
			$delivery = get_post_meta($product->get_id(), 'yfym_individual_delivery', true);
			if ($delivery === 'off' || $delivery === 'disabled') {
				$delivery = yfym_optionGET('yfym_delivery', $this->get_feed_id(), 'set_arr');
			}
		} else {
			$delivery = yfym_optionGET('yfym_delivery', $this->get_feed_id(), 'set_arr');
		}
		if ($delivery === false || $delivery == '') {			
			$result_xml = '';
		} else {
			$result_xml = new Get_Paired_Tag($tag_name, $delivery);
		}

		$result_xml = apply_filters('y4ym_f_simple_tag_delivery', $result_xml, array('product' => $product), $this->get_feed_id());
		return $result_xml;
	}
}
?>