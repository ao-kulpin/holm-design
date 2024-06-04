<?php if (!defined('ABSPATH')) {exit;}
/**
 * Traits Amount for variable products
 *
 * @package					YML for Yandex Market
 * @subpackage				
 * @since					1.0.0
 * 
 * @version					1.0.0
 * @author					Maxim Glazunov
 * @link					https://icopydoc.ru/
 * @see						
 * 
 * @param	string			$tag_name (not require)
 * @param	string			$result_xml (not require)
 *
 * @return 					$result_xml (string)
 *
 * @depends					classes:	Get_Paired_Tag
 *							traits:		
 *							methods:	get_product
 *										get_offer
 *										get_feed_id
 *							functions:	yfym_optionGET
 *							constants:	
 */

trait YFYM_T_Variable_Get_Amount {
	public function get_amount($tag_name = 'amount', $result_xml = '') {
		$tag_value = '';

		$yfym_amount = yfym_optionGET('yfym_amount', $this->get_feed_id(), 'set_arr');
		if ($yfym_amount === 'enabled') {
			if ($this->get_offer()->get_manage_stock() == true) { // включено управление запасом на уровне вариации
				$stock_quantity = $this->get_offer()->get_stock_quantity();		
				if ($stock_quantity > -1) {$tag_value = $stock_quantity;}
			} else {
				if ($this->get_product()->get_manage_stock() == true) { // включено управление запасом
					$stock_quantity = $this->get_product()->get_stock_quantity();
					if ($stock_quantity > -1) {$tag_value = $stock_quantity;}
				} 
			}		
		}

		$tag_value = apply_filters('y4ym_f_variable_tag_value_amount', $tag_value, [ 'product' => $this->get_product(), 'offer' => $this->get_offer() ], $this->get_feed_id());
		if ($tag_value !== '') {
			$tag_name = apply_filters('y4ym_f_variable_tag_name_amount', $tag_name, [ 'product' => $this->get_product(), 'offer' => $this->get_offer() ], $this->get_feed_id());
			$result_xml = new Get_Paired_Tag($tag_name, $tag_value);
		}

		$result_xml = apply_filters('y4ym_f_variable_tag_amount', $result_xml, [ 'product' => $this->get_product(), 'offer' => $this->get_offer() ], $this->get_feed_id());
		return $result_xml;
	}
}