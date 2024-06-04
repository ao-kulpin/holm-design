<?php if (!defined('ABSPATH')) {exit;}
/**
 * Traits Premium_Price for simple products
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
 *										get_feed_id
 *							functions:	
 *							constants:	
 */

trait YFYM_T_Simple_Get_Premium_Price {
	public function get_premium_price($tag_name = 'premium_price', $result_xml = '') {
		if (get_post_meta($this->get_product()->get_id(), '_yfym_premium_price', true) !== '') {
			$premium_price = get_post_meta($this->get_product()->get_id(), '_yfym_premium_price', true);
			$result_xml .= new Get_Paired_Tag($tag_name, $premium_price);
		}

		$result_xml = apply_filters('y4ym_f_simple_tag_premium_price', $result_xml, [ 'product' => $this->get_product() ], $this->get_feed_id());
		return $result_xml;
	}
}