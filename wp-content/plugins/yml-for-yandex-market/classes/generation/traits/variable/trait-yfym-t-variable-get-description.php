<?php if (!defined('ABSPATH')) {exit;}
/**
 * Traits Description for variable products
 *
 * @package					YML for Yandex Market
 * @subpackage				
 * @since					1.0.0
 * 
 * @version					1.0.0 (29-03-2023)
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
 *										get_offer
 *										get_feed_id
 *							functions:	yfym_optionGET
 *							constants:	
 */

trait YFYM_T_Variable_Get_Description {
	// https://yandex.ru/support/marketplace/assortment/fields/index.html
	public function get_description($tag_name = 'description', $result_xml = '') {
		$tag_value = '';

		$yfym_yml_rules = yfym_optionGET('yfym_yml_rules', $this->get_feed_id(), 'set_arr');
		$yfym_desc = yfym_optionGET('yfym_desc', $this->get_feed_id(), 'set_arr');
		$yfym_the_content = yfym_optionGET('yfym_the_content', $this->get_feed_id(), 'set_arr');
		$yfym_enable_tags_custom = yfym_optionGET('yfym_enable_tags_custom', $this->get_feed_id(), 'set_arr');
		$yfym_enable_tags_behavior = yfym_optionGET('yfym_enable_tags_behavior', $this->get_feed_id(), 'set_arr');
		$yfym_var_desc_priority = yfym_optionGET('yfym_var_desc_priority', $this->get_feed_id(), 'set_arr');
		
		if ($yfym_enable_tags_behavior == 'default') {
			$enable_tags = '<p>,<br/>,<br>';
			$enable_tags = apply_filters('yfym_enable_tags_filter', $enable_tags, $this->get_feed_id());
		} else {
			$enable_tags = trim($yfym_enable_tags_custom);
			if ($enable_tags !== '') {
				$enable_tags = '<'.str_replace(',', '>,<', $enable_tags).'>';
			}			
		}		

		// вариации
		if ($yfym_var_desc_priority === 'on') {
			$tag_value = $this->get_offer()->get_description();		
		}

		switch ($yfym_desc) { 
			case "full": 
				// сейчас и далее проверка на случай, если описание вариации главнее
				if (empty($tag_value)) { 
					$tag_value = $this->get_product()->get_description();
				}
				break;
			case "excerpt": 
				if (empty($tag_value)) {
					$tag_value = $this->get_product()->get_short_description();
				}
				break;
			case "fullexcerpt": 
				if (empty($tag_value)) {
					$tag_value = $this->get_product()->get_description(); 
					if (empty($tag_value)) {
						$tag_value = $this->get_product()->get_short_description();
					}
				}
				break;
			case "excerptfull": 
				if (empty($tag_value)) {
					$tag_value = $this->get_product()->get_short_description();		 
					if (empty($tag_value)) {
						$tag_value = $this->get_product()->get_description();
					} 
				}
				break;
			case "fullplusexcerpt": 
				if ($yfym_var_desc_priority === 'on') {
					$tag_value = sprintf('%1$s<br/>%2$s',
						$this->get_offer()->get_description(),
						$this->get_product()->get_short_description()
					);
				} else {
					$tag_value = sprintf('%1$s<br/>%2$s',
						$this->get_product()->get_description(),
						$this->get_product()->get_short_description()
					);
				}
				break;
			case "excerptplusfull": 
				if ($yfym_var_desc_priority === 'on') {
					$tag_value = sprintf('%1$s<br/>%2$s',
						$this->get_product()->get_short_description(), 
						$this->get_offer()->get_description()
					);
				} else {
					$tag_value = sprintf('%1$s<br/>%2$s',
						$this->get_product()->get_short_description(), 
						$this->get_product()->get_description()
					);
				}
				break;	
			default: 
				if (empty($tag_value)) { // проверка на случай, если описание вариации главнее
					$tag_value = $this->get_product()->get_description(); 
					$tag_value = apply_filters('y4ym_f_variable_switchcase_default_description',
						$tag_value, 
						[
							'yfym_desc' => $yfym_desc,
							'product' => $this->get_product(),
							'offer' => $this->get_offer()
						],
						$this->get_feed_id()
					);
				}
		}	

		// схожее со строкой 50, на случай, если описание вариации имеет низкий приоритет,
		// а другие описания пусты
		if (empty($tag_value)) {
			$tag_value = $this->get_offer()->get_description();		
		}

		if (!empty($tag_value)) {
			if ($yfym_the_content === 'enabled') {
				$tag_value = html_entity_decode(apply_filters('the_content', $tag_value));
			}
			$tag_value = $this->replace_tags($tag_value, $yfym_enable_tags_behavior);
			$tag_value = strip_tags($tag_value, $enable_tags);
			$tag_value = str_replace('<br>', '<br/>', $tag_value);
			$tag_value = strip_shortcodes($tag_value);
			$tag_value = apply_filters('yfym_description_filter', $tag_value, $this->get_product()->get_id(), $this->get_product(), $this->get_feed_id());			
			$tag_value = trim($tag_value);
		}

		$tag_value = apply_filters('y4ym_f_variable_tag_value_description', $tag_value, [ 'product' => $this->get_product() ], $this->get_feed_id());
		if ($tag_value !== '') {
			$yfym_yml_rules = yfym_optionGET('yfym_yml_rules', $this->get_feed_id(), 'set_arr');
			if ($yfym_yml_rules === 'vk') {
				$tag_value = strip_tags($tag_value, '');
				$tag_value = htmlspecialchars($tag_value);
				// $tag_value = mb_strimwidth($tag_value, 0, 256);
			} else {
				$tag_value = '<![CDATA['.$tag_value.']]>';
			}
			$tag_name = apply_filters('y4ym_f_variable_tag_name_description', $tag_name, [ 'product' => $this->get_product() ], $this->get_feed_id());
			$result_xml = new Get_Paired_Tag($tag_name, $tag_value);
		}

		$result_xml = apply_filters('y4ym_f_variable_tag_description', $result_xml, [ 'product' => $this->get_product(), 'offer' => $this->get_offer() ], $this->get_feed_id());
		return $result_xml;
	}

	private function replace_tags($tag_value, $yfym_enable_tags_behavior) {
		if ($yfym_enable_tags_behavior == 'default') {
			$tag_value = str_replace('<ul>', '', $tag_value);
			$tag_value = str_replace('<li>', '', $tag_value);
			$tag_value = str_replace('</li>', '<br/>', $tag_value);
		}
		return $tag_value;
	}
}