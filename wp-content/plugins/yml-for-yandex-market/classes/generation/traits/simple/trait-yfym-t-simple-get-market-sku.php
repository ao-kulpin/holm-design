<?php if (!defined('ABSPATH')) {exit;}
/**
* Traits Market_Sku for simple products
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

trait YFYM_T_Simple_Get_Market_Sku {
	public function get_market_sku($tag_name = 'market-sku', $result_xml = '') {
		$product = $this->product;

		$yfym_market_sku_status = yfym_optionGET('yfym_market_sku_status', $this->get_feed_id(), 'set_arr');
		if ((get_post_meta($product->get_id(), '_yfym_market_sku', true) !== '') && ($yfym_market_sku_status === 'enabled')) {
			$yfym_market_sku = get_post_meta($product->get_id(), '_yfym_market_sku', true);
			$result_xml = new Get_Paired_Tag($tag_name, $yfym_market_sku);
		}

		$result_xml = apply_filters('y4ym_f_simple_tag_market_sku', $result_xml, array('product' => $product), $this->get_feed_id());
		return $result_xml;
	}
}
?>