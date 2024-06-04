<?php if (!defined('ABSPATH')) {exit;}
/**
* Traits Supplier for variable products
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
*							add_skip_reason
*				functions:	yfym_optionGET 
*/

trait YFYM_T_Variable_Get_Supplier {
	public function get_supplier($tag_name = 'supplier', $result_xml = '') {
		$product = $this->product;
		$offer = $this->offer;

		if ((get_post_meta($product->get_id(), '_yfym_supplier', true) !== '') && (get_post_meta($product->get_id(), '_yfym_supplier', true) !== '')) {
			$yfym_supplier = get_post_meta($product->get_id(), '_yfym_supplier', true);
			$result_xml = '<supplier ogrn="'.$yfym_supplier.'"/>'.PHP_EOL;
		}	

		$result_xml = apply_filters('y4ym_f_variable_tag_supplier', $result_xml, array('product' => $product, 'offer' => $offer), $this->get_feed_id());
		return $result_xml;
	}
}
?>