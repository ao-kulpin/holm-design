<?php if (!defined('ABSPATH')) {exit;}
/**
* Traits for simple products
*
* @author		Maxim Glazunov
* @link			https://icopydoc.ru/
* @since		1.0.0
*
* @return 		$result_xml (string)
*
* @depends		class:	Get_Paired_Tag
*				methods: add_skip_reason
*				functions: 
*/

trait YFYM_T_Simple_Get_Currencyid {
	public function get_currencyid($tag_name = 'currencyId', $result_xml = '') {
		$product = $this->get_product();
		$tag_value = '';

		/* общие данные для вариативных и обычных товаров */
		$res = get_woocommerce_currency(); // получаем валюта магазина
		switch ($res) { /* RUR, USD, UAH, KZT, BYN */
			case "RUB":	$currencyId_yml = "RUR"; break;
			case "USD":	$currencyId_yml = "USD"; break;
			case "EUR":	$currencyId_yml = "EUR"; break;
			case "UAH":	$currencyId_yml = "UAH"; break;
			case "KZT":	$currencyId_yml = "KZT"; break;
			case "BYN":	$currencyId_yml = "BYN"; break;
			case "BYR": $currencyId_yml = "BYN"; break;
			case "ABC": $currencyId_yml = "BYN"; break;
			default: $currencyId_yml = "RUR";
		}
 		$currencyId_yml = apply_filters('yfym_currency_id', $currencyId_yml, $this->get_feed_id()); /* с версии 3.3.15 */

		$tag_value = $currencyId_yml;

		$tag_value = apply_filters('y4ym_f_simple_tag_value_currencyid', $tag_value, array('product' => $product), $this->get_feed_id());
		if (!empty($tag_value)) {	
			$tag_name = apply_filters('y4ym_f_simple_tag_name_currencyid', $tag_name, array('product' => $product), $this->get_feed_id());
			$result_xml = new Get_Paired_Tag($tag_name, $tag_value);
		}

		$result_xml = apply_filters('y4ym_f_simple_tag_currencyid', $result_xml, array('product' => $product), $this->get_feed_id());
		return $result_xml;
	}
}
?>