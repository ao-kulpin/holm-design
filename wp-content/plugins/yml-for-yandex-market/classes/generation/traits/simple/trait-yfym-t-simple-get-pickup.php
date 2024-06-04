<?php if (!defined('ABSPATH')) {exit;}
/**
 * Traits Pickup for simple products
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
 *							functions:	yfym_optionGET
 *							constants:	
 */

trait YFYM_T_Simple_Get_Pickup {
	public function get_pickup($tag_name = 'pickup', $result_xml = '') {
		if (get_post_meta($this->get_product()->get_id(), 'yfym_individual_pickup', true) !== '') {
			$pickup = get_post_meta($this->get_product()->get_id(), 'yfym_individual_pickup', true);
			if ($pickup === 'off') {
				$pickup = yfym_optionGET('yfym_pickup', $this->get_feed_id(), 'set_arr');
			}
		} else {
			$pickup = yfym_optionGET('yfym_pickup', $this->get_feed_id(), 'set_arr');
		}

		if ($pickup == 'disabled') {
			// pickup отключён
		} else {
			$result_xml = new Get_Paired_Tag($tag_name, $pickup);
		}

		$result_xml = apply_filters('y4ym_f_simple_tag_pickup', $result_xml, [ 'product' => $this->get_product() ], $this->get_feed_id());
		return $result_xml;
	}
}