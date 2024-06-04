<?php if (!defined('ABSPATH')) {exit;}
/**
* Traits Recommend_Stock_Data for simple products
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
*							yfym_data_from_arr
*/

trait YFYM_T_Simple_Get_Recommend_Stock_Data {
	public function get_recommend_stock_data($result_xml = '') {
		$product = $this->product;

		$result_availability_yml = '';
		$result_transport_unit_yml = '';
		$result_min_delivery_pieces_yml = '';
		$result_quantum_yml = '';
		$result_leadtime_yml = '';
		$result_box_count_yml = '';
		$yfym_delivery_weekday_yml = '';

		if (get_post_meta($product->get_id(), '_yfym_recommend_stock_data_arr', true) !== '') {
			$yfym_recommend_stock_data_arr = get_post_meta($product->get_id(), '_yfym_recommend_stock_data_arr', true);
		
			$availability = yfym_data_from_arr($yfym_recommend_stock_data_arr, 'availability', 'disabled');	
			if ($availability === '' || $availability === 'disabled') {} else {
				$result_availability_yml .= '<availability>'.$availability.'</availability>'.PHP_EOL;
			}
		
			$transport_unit = yfym_data_from_arr($yfym_recommend_stock_data_arr, 'transport_unit');
			if ($transport_unit !== '') {
				$result_transport_unit_yml .= '<transport-unit>'.$transport_unit.'</transport-unit>'.PHP_EOL;
			}
		
			$min_delivery_pieces = yfym_data_from_arr($yfym_recommend_stock_data_arr, 'min_delivery_pieces');
			if ($min_delivery_pieces !== '') {
				$result_min_delivery_pieces_yml .= '<min-delivery-pieces>'.$min_delivery_pieces.'</min-delivery-pieces>'.PHP_EOL;
			}
		
			$quantum = yfym_data_from_arr($yfym_recommend_stock_data_arr, 'quantum');
			if ($quantum !== '') {
				$result_quantum_yml .= '<quantum>'.$quantum.'</quantum>'.PHP_EOL;
			}
		
			$leadtime = yfym_data_from_arr($yfym_recommend_stock_data_arr, 'leadtime');
			if ($leadtime !== '') {
				$result_leadtime_yml .= '<leadtime>'.$leadtime.'</leadtime>'.PHP_EOL;
			}
		
			$box_count = yfym_data_from_arr($yfym_recommend_stock_data_arr, 'box_count');
			$box_count = apply_filters('yfym_box_count_filter', $box_count, $product->get_id(), $product, $this->get_feed_id());
			if ($box_count !== '') {
				$result_box_count_yml .= '<box-count>'.$box_count.'</box-count>'.PHP_EOL;
			}
		
			$delivery_weekday_arr = yfym_data_from_arr($yfym_recommend_stock_data_arr, 'delivery_weekday_arr', array());
			if (!empty($delivery_weekday_arr)) {
				$yfym_delivery_weekday_yml .= '<delivery-weekdays>'.PHP_EOL;
				for ($d = 0; $d < count($delivery_weekday_arr); $d++) {
					$yfym_delivery_weekday_yml .= '<delivery-weekday>'.$delivery_weekday_arr[$d].'</delivery-weekday>'.PHP_EOL;
				}
				$yfym_delivery_weekday_yml .= '</delivery-weekdays>'.PHP_EOL;
			}
		}

		$result_xml .= $result_availability_yml;
		$result_xml .= $result_transport_unit_yml;
		$result_xml .= $result_min_delivery_pieces_yml;
		$result_xml .= $result_quantum_yml;
		$result_xml .= $result_leadtime_yml;
		$result_xml .= $result_box_count_yml;
		$result_xml .= $yfym_delivery_weekday_yml;

//		$result_xml = apply_filters('y4ym_f_simple_tag_sales_notes', $result_xml, array('product' => $product, 'offer' => $offer), $this->get_feed_id());
		return $result_xml;
	}
}
?>