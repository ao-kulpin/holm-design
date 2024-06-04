<?php if (!defined('ABSPATH')) {exit;}
/**
* Traits Count for variable products
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

trait YFYM_T_Variable_Get_Count {
	public function get_count($tag_name = 'count', $result_xml = '') {
		$product = $this->get_product();
		$offer = $this->get_offer();
		$tag_value = '';

		$yfym_count = yfym_optionGET('yfym_count', $this->get_feed_id(), 'set_arr');
		if ($yfym_count === 'enabled') {
			if ($offer->get_manage_stock() == true) { // включено управление запасом на уровне вариации
				$stock_quantity = $offer->get_stock_quantity();		
				if ($stock_quantity > -1) {$tag_value = $stock_quantity;} else {$tag_value = (int)0;}
			} else {
				if ($product->get_manage_stock() == true) { // включено управление запасом
					$stock_quantity = $product->get_stock_quantity();
					if ($stock_quantity > -1) {$tag_value = $stock_quantity;} else {$tag_value = (int)0;}
				} 
			}		
		}

		$tag_value = apply_filters('y4ym_f_variable_tag_value_count', $tag_value, array('product' => $product, 'offer' => $offer), $this->get_feed_id());
		if ($tag_value !== '') {
			$tag_name = apply_filters('y4ym_f_variable_tag_name_count', $tag_name, array('product' => $product, 'offer' => $offer), $this->get_feed_id());
			$result_xml = new Get_Paired_Tag($tag_name, $tag_value);
		}

		$result_xml = apply_filters('y4ym_f_variable_tag_count', $result_xml, array('product' => $product, 'offer' => $offer), $this->get_feed_id());
		return $result_xml;
	}
}
?>