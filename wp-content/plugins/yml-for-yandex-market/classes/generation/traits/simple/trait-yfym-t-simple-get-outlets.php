<?php if (!defined('ABSPATH')) {exit;}
/**
* Traits Outlets for simple products
*
* @author		Maxim Glazunov
* @link			https://icopydoc.ru/
* @since		1.0.0
*
* @return 		$result_xml (string)
*
* @depends		class:		
*				methods: 	get_product
*							get_feed_id
*				functions:	yfym_optionGET
*/

trait YFYM_T_Simple_Get_Outlets {
	public function get_outlets($tag_name = 'outlets', $result_xml = '', $rules = '') {
		$product = $this->product;

		if ($product->get_manage_stock() == true) { // включено управление запасом
			$stock_quantity = $product->get_stock_quantity();
//				$yfym_count = yfym_optionGET('yfym_count', $numFeed, 'set_arr');
//				if ($yfym_count === 'enabled' && $stock_quantity > -1) {
				$result_xml .= '<instock>'.$stock_quantity.'</instock>'.PHP_EOL;
//				}

			$warehouse = yfym_optionGET('yfym_warehouse', $this->get_feed_id(), 'set_arr');
			if ($rules === 'sbermegamarket') {
				$result_xml = new Get_Open_Tag($tag_name);
				$result_xml .= '<outlet id="'.$warehouse.'" instock="'.htmlspecialchars($stock_quantity).'"></outlet>'.PHP_EOL;
				$result_xml .= new Get_Closed_Tag($tag_name);
			} else {
				$result_xml = new Get_Open_Tag($tag_name);
				$result_xml .= '<outlet instock="'.htmlspecialchars($stock_quantity).'" warehouse_name="'.$warehouse.'"></outlet>'.PHP_EOL;
				$result_xml .= new Get_Closed_Tag($tag_name);
			}
		}	

		$result_xml = apply_filters('y4ym_f_simple_tag_outlets', $result_xml, array('product' => $product), $this->get_feed_id());
		return $result_xml;	
	}
}
?>