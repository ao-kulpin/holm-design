<?php if (!defined('ABSPATH')) {exit;}
/**
* Traits Price_Rrp for variable products
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
*							add_skip_reason
*				functions:	yfym_optionGET 
*/

trait YFYM_T_Variable_Get_Price_Rrp {
	public function get_price_rrp($tag_name = 'price_rrp', $result_xml = '') {
		$product = $this->product;
		$offer = $this->offer;

		if (get_post_meta($product->get_id(), '_yfym_price_rrp', true) !== '') {
			$tag_value = get_post_meta($product->get_id(), '_yfym_price_rrp', true);
			$result_xml .= new Get_Paired_Tag($tag_name, $tag_value);
		}	

		$result_xml = apply_filters('y4ym_f_variable_tag_price_rrp', $result_xml, array('product' => $product, 'offer' => $offer), $this->get_feed_id());
		return $result_xml;
	}
}
?>