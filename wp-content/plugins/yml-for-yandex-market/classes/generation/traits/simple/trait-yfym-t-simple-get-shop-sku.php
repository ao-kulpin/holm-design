<?php if (!defined('ABSPATH')) {exit;}
/**
* Traits Shop_Sku for simple products
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

trait YFYM_T_simple_Get_Shop_Sku {
	public function get_shop_sku($tag_name = 'shop-sku', $result_xml = '') {
		$product = $this->get_product();
		$tag_value = '';
		 
		$yfym_shop_sku = yfym_optionGET('yfym_shop_sku', $this->get_feed_id(), 'set_arr');
		switch ($yfym_shop_sku) { 
			case "disabled": // выгружать штрихкод нет нужды		
				break; 
			case "sku": // выгружать из артикула
				$tag_value = $product->get_sku();
				break;
			case "products_id": // выгружать из id вариации
				$tag_value = $product->get_id();
				break;
			default:
				$tag_value = apply_filters('y4ym_f_simple_tag_value_switch_shop_sku', $tag_value, array('product' => $product, 'switch_value' => $yfym_shop_sku), $this->get_feed_id());			
				if ($tag_value == '') {
					$yfym_shop_sku = (int)$yfym_shop_sku;
					$tag_value = $product->get_attribute(wc_attribute_taxonomy_name_by_id($yfym_shop_sku));			
				}
		}

		$tag_value = apply_filters('y4ym_f_simple_tag_value_shop_sku', $tag_value, array('product' => $product), $this->get_feed_id());
		if (!empty($tag_value)) {
			$tag_name = apply_filters('y4ym_f_simple_tag_name_shop_sku', $tag_name, array('product' => $product), $this->get_feed_id());
			$result_xml = new Get_Paired_Tag($tag_name, $tag_value);
		}

		$result_xml = apply_filters('y4ym_f_simple_tag_shop_sku', $result_xml, array('product' => $product), $this->get_feed_id());
		return $result_xml;
	}
}
?>