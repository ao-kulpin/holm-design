<?php if (!defined('ABSPATH')) {exit;}
/**
* Traits Enable_Auto_Discounts for simple products
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

trait YFYM_T_Simple_Get_Enable_Auto_Discounts {
	public function get_enable_auto_discounts($tag_name = 'enable_auto_discounts', $result_xml = '') {
		$product = $this->product;

		$yfym_enable_auto_discounts = yfym_optionGET('yfym_enable_auto_discounts', $this->get_feed_id(), 'set_arr');
		if ($yfym_enable_auto_discounts === 'on') {
			$result_xml = new Get_Paired_Tag($tag_name, 'yes');
		}

		$result_xml = apply_filters('y4ym_f_simple_tag_enable_auto_discounts', $result_xml, array('product' => $product), $this->get_feed_id());
		return $result_xml;
	}
}
?>