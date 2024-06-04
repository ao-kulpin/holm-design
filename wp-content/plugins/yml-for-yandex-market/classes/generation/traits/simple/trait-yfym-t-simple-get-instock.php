<?php if (!defined('ABSPATH')) {exit;}
/**
* Traits Instock Count for simple products
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

trait YFYM_T_Simple_Get_Instock {
	public function get_instock($tag_name = 'instock', $result_xml = '') {
		$product = $this->get_product();
		$tag_value = '';

		$yfym_instock = yfym_optionGET('yfym_instock', $this->get_feed_id(), 'set_arr');
		if ($yfym_instock === 'enabled') {
			if ($product->get_manage_stock() == true) { // включено управление запасом
				$stock_quantity = $product->get_stock_quantity();
				if ($stock_quantity > -1) {$tag_value = $stock_quantity;} else {$tag_value = (int)0;}
			} 	
		}

		$tag_value = apply_filters('y4ym_f_simple_tag_value_instock', $tag_value, array('product' => $product), $this->get_feed_id());
		if (!empty($tag_value)) {
			$tag_name = apply_filters('y4ym_f_simple_tag_name_instock', $tag_name, array('product' => $product), $this->get_feed_id());
			$result_xml = new Get_Paired_Tag($tag_name, $tag_value);
		}

		$result_xml = apply_filters('y4ym_f_simple_tag_instock', $result_xml, array('product' => $product), $this->get_feed_id());
		return $result_xml;
	}
}
?>