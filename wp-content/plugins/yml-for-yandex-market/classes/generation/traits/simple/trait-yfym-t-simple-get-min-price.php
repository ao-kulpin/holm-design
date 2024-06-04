<?php if (!defined('ABSPATH')) {exit;}
/**
 * Traits Min_Price for simple products
 *
 * @package					YML for Yandex Market
 * @subpackage				
 * @since					3.9.3
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
 *										get_feed_id
 *							functions:	
 *							constants:	
 */

trait YFYM_T_Simple_Get_Min_Price {
	public function get_min_price($tag_name = 'min_price', $result_xml = '') {
		if (get_post_meta($this->get_product()->get_id(), '_yfym_min_price', true) !== '') {
			$tag_value = get_post_meta($this->get_product()->get_id(), '_yfym_min_price', true);
			$result_xml .= new Get_Paired_Tag($tag_name, $tag_value);
		}

		$result_xml = apply_filters('y4ym_f_simple_tag_min_price', $result_xml, [ 'product' => $this->get_product() ], $this->get_feed_id());
		return $result_xml;
	}
}