<?php if (!defined('ABSPATH')) {exit;}
/**
* Traits Sales_Notes for simple products
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

trait YFYM_T_Simple_Get_Sales_Notes {
	public function get_sales_notes($tag_name = 'sales_notes', $result_xml = '') {
		$product = $this->product;

		$sales_notes_cat = yfym_optionGET('yfym_sales_notes_cat', $this->get_feed_id(), 'set_arr');
		if (!empty($sales_notes_cat) && $sales_notes_cat !== 'off') {
			$sales_notes_cat = (int)$sales_notes_cat;
			$sales_notes_yml = $product->get_attribute(wc_attribute_taxonomy_name_by_id($sales_notes_cat));   
			if (!empty($sales_notes_yml)) {	
				$result_xml = "<sales_notes>".ucfirst(yfym_replace_decode($sales_notes_yml))."</sales_notes>".PHP_EOL;		
			} else {
				$sales_notes = yfym_optionGET('yfym_sales_notes', $this->get_feed_id(), 'set_arr');
				if (!empty($sales_notes)) {
					$result_xml = "<sales_notes>$sales_notes</sales_notes>".PHP_EOL;
				}
			}
		}

		$result_xml = apply_filters('y4ym_f_simple_tag_sales_notes', $result_xml, array('product' => $product), $this->get_feed_id());
		return $result_xml;
	}
}
?>