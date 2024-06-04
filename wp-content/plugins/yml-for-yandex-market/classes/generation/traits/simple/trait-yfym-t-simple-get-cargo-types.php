<?php if (!defined('ABSPATH')) {exit;}
/**
* Traits Cargo_Types for simple products
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
*				functions:	 
*/

trait YFYM_T_Simple_Get_Cargo_Types {
	public function get_cargo_types($tag_name = 'cargo-types', $result_xml = '') {
		$product = $this->get_product();

		$cargo_types = yfym_optionGET('yfym_cargo_types', $this->get_feed_id(), 'set_arr');
		if ($cargo_types === 'enabled') { 
			if (get_post_meta($product->get_id(), '_yfym_cargo_types', true) !== '') {
				$yfym_cargo_types = get_post_meta($product->get_id(), '_yfym_cargo_types', true);
				if ($yfym_cargo_types === 'yes') {
					$yfym_cargo_types_yml = '<cargo-types>CIS_REQUIRED</cargo-types>'.PHP_EOL;
					$result_xml = new Get_Paired_Tag($tag_name, 'CIS_REQUIRED');
				}			
			}
		}

		$result_xml = apply_filters('y4ym_f_simple_tag_cargo_types', $result_xml, array('product' => $product), $this->get_feed_id());
		return $result_xml;
	}
}
?>