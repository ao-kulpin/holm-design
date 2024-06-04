<?php if (!defined('ABSPATH')) {exit;}
/**
 * Traits Disabled for simple products
 *
 * @package					YML for Yandex Market
 * @subpackage				
 * @since					0.1.0
 * 
 * @version					1.0.0 (25-05-2023)
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
 *							functions:	common_option_get
 *							constants:	
 */

trait YFYM_T_Simple_Get_Disabled {
	public function get_disabled($tag_name = 'disabled', $result_xml = '') {
		$tag_value = '';

		$disabled = common_option_get('yfym_auto_disabled', false, $this->get_feed_id(), 'yfym');
		if ($disabled === 'yes') {
			// если товар не доступен к покупке
			if (false === $this->get_product()->is_in_stock()) {
				$tag_value = 'true';
			} else {
				$tag_value = 'false';
			}
		}

		$tag_value = apply_filters(
			'y4ym_f_simple_tag_value_disabled',
			$tag_value,
			[
				'product' => $this->get_product()
			],
			$this->get_feed_id()
		);

		if (!empty($tag_value)) {
			$tag_name = apply_filters(
				'y4ym_f_simple_tag_name_disabled',
				$tag_name,
				[
					'product' => $this->get_product()
				],
				$this->get_feed_id()
			);
			$result_xml = new Get_Paired_Tag($tag_name, $tag_value);
		}

		$result_xml = apply_filters(
			'y4ym_f_simple_tag_disabled',
			$result_xml,
			[
				'product' => $this->get_product()
			], 
			$this->get_feed_id()
		);
		return $result_xml;
	}
}