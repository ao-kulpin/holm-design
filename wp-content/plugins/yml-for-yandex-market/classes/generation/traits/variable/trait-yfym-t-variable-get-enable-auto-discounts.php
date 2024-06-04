<?php if (!defined('ABSPATH')) {exit;}
/**
* Traits Enable_Auto_Discounts for variable products
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

trait YFYM_T_Variable_Get_Enable_Auto_Discounts {
	public function get_enable_auto_discounts($tag_name = 'enable_auto_discounts', $result_xml = '') {
		$product = $this->product;
		$offer = $this->offer;

		$yfym_enable_auto_discounts = yfym_optionGET('yfym_enable_auto_discounts', $this->get_feed_id(), 'set_arr');
		if ($yfym_enable_auto_discounts === 'on') {
			$result_xml = new Get_Paired_Tag($tag_name, 'yes');
		}

		$result_xml = apply_filters('y4ym_f_variable_tag_enable_auto_discounts', $result_xml, array('product' => $product, 'offer' => $offer), $this->get_feed_id());
		return $result_xml;
	}
}
?>