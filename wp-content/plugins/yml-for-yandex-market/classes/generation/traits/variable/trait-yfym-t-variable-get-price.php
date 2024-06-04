<?php if (!defined('ABSPATH')) {exit;}
/**
* Traits Price for variable products
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
*							get_feed_category_id
*							add_skip_reason
*				functions:	yfym_optionGET 
*/

// более осторожное удаление - yfym_variable_price_filter
trait YFYM_T_Variable_Get_Price {
	public function get_price($tag_name = 'price', $result_xml = '') {
		$product = $this->product;
		$offer = $this->offer;
		$product_category_id = $this->get_feed_category_id();

		/*
		* $offer->get_price() - актуальная цена (равна sale_price или regular_price если sale_price пуст)
		* $offer->get_regular_price() - обычная цена
		* $offer->get_sale_price() - цена скидки
		*/
		$result_yml = '';
		$price_yml = $offer->get_price(); // цена вариации
		$price_yml = apply_filters('yfym_variable_price_filter', $price_yml, $product, $offer, $offer->get_id(), $this->get_feed_id()); /* с версии 3.0.0 */ 
		$price_yml = apply_filters('y4ym_f_variable_price', $price_yml, array('product' => $product, 'offer' => $offer, 'product_category_id' => $product_category_id), $this->get_feed_id());
			
		$yfym_yml_rules = yfym_optionGET('yfym_yml_rules', $this->feed_id, 'set_arr');
		if ($yfym_yml_rules !== 'all_elements') { // если цены нет - пропускаем вариацию 
			if ($price_yml == 0 || empty($price_yml)) {$this->add_skip_reason(array('offer_id' => $offer->get_id(), 'reason' => __('The product has no price', 'yfym'), 'post_id' => $offer->get_id(), 'file' => 'trait-yfym-t-variable-get-price.php', 'line' => __LINE__)); return '';}
		}		
		
		if (class_exists('YmlforYandexMarketPro')) {
			if ((yfym_optionGET('yfymp_compare_value', $this->get_feed_id(), 'set_arr') !== false) && (yfym_optionGET('yfymp_compare_value', $this->get_feed_id(), 'set_arr') !== '')) {
				$yfymp_compare_value = yfym_optionGET('yfymp_compare_value', $this->get_feed_id(), 'set_arr');
				$yfymp_compare = yfym_optionGET('yfymp_compare', $this->get_feed_id(), 'set_arr');			 
				if ($yfymp_compare == '>=') {
					if ($price_yml < $yfymp_compare_value) {
						$this->add_skip_reason(array('offer_id' => $offer->get_id(), 'reason' => __('The product price', 'yfym').' '. $offer->get_price(). ': < ' . $yfymp_compare_value, 'post_id' => $offer->get_id(), 'file' => 'trait-yfym-t-variable-get-price.php', 'line' => __LINE__)); return '';
					}
				} else {
					if ($price_yml >= $yfymp_compare_value) {
						$this->add_skip_reason(array('offer_id' => $offer->get_id(), 'reason' => __('The product price', 'yfym').' '. $offer->get_price(). ': >= ' . $yfymp_compare_value, 'post_id' => $offer->get_id(), 'file' => 'trait-yfym-t-variable-get-price.php', 'line' => __LINE__)); return '';
					}
				}
			}
		}

		/* С версии 3.7.11 */
		$skip_price_reason = false;
		$skip_price_reason = apply_filters('y4ym_f_variable_skip_price_reason', $skip_price_reason, array('price_yml' => $price_yml, 'product' => $product, 'offer' => $offer), $this->get_feed_id());
		if ($skip_price_reason !== false) {
			$this->add_skip_reason(array('offer_id' => $offer->get_id(), 'reason' => $skip_price_reason, 'post_id' => $offer->get_id(), 'file' => 'trait-yfym-t-variable-get-price.php', 'line' => __LINE__)); return '';
		}
		
		$price_yml = apply_filters('yfym_variable_price_yml_filter', $price_yml, $product, $offer, $this->get_feed_id()); /* с версии 3.1.0 */
		$yfym_price_from = yfym_optionGET('yfym_price_from', $this->get_feed_id(), 'set_arr');
		if ($price_yml !== '') {
			if ($yfym_price_from === 'yes') { // yes - для обратной совместимости с версиями раньше 3.7.7
				$result_yml .= new Get_Paired_Tag($tag_name, $price_yml, array('from' => 'true'));
			} else {
				$result_yml .= new Get_Paired_Tag($tag_name, $price_yml);
			}
		}
		// старая цена
		$yfym_oldprice = yfym_optionGET('yfym_oldprice', $this->get_feed_id(), 'set_arr');
		if ($yfym_oldprice === 'yes' || $yfym_oldprice === 'enabled') { // yes - для обратной совместимости с версиями раньше 3.7.7
			$price_yml = (float)$price_yml;
			$sale_price = (float)$offer->get_sale_price();
			if ($sale_price > 0) {
				$oldprice_yml = $offer->get_regular_price();
				$oldprice_name_tag = 'oldprice';
				$oldprice_name_tag = apply_filters('yfym_oldprice_name_tag_filter', $oldprice_name_tag, $this->get_feed_id()); /* с версии 3.2.0 */
				if ($oldprice_yml !== '') {	
					$result_yml .= new Get_Paired_Tag($oldprice_name_tag, $oldprice_yml);
				}
			}
		}

		$result_xml = $result_yml;

		$result_xml = apply_filters('y4ym_f_variable_tag_price', $result_xml, array('product' => $product, 'offer' => $offer, 'product_category_id' => $product_category_id), $this->get_feed_id());
		return $result_xml;
	}
}
?>