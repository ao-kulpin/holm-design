<?php if (!defined('ABSPATH')) {exit;}
/**
* Traits Vendor for simple products
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

trait YFYM_T_Simple_Get_Vendor {
	public function get_vendor($tag_name = 'vendor', $result_xml = '') {
		$product = $this->product;

		$vendor_name = '';
		// $result_yml_vendor = '';
		$vendor = yfym_optionGET('yfym_vendor', $this->get_feed_id(), 'set_arr');
		if ((is_plugin_active('perfect-woocommerce-brands/perfect-woocommerce-brands.php') || is_plugin_active('perfect-woocommerce-brands/main.php') || class_exists('Perfect_Woocommerce_Brands')) && $vendor === 'sfpwb') {
			$barnd_terms = get_the_terms($product->get_id(), 'pwb-brand');
			if ($barnd_terms !== false) {
				foreach($barnd_terms as $barnd_term) {
					$vendor_name = $barnd_term->name;
					// $result_yml_vendor = '<vendor>'. $barnd_term->name .'</vendor>'.PHP_EOL;
					break;
				}
			}
		} else if ((is_plugin_active('premmerce-woocommerce-brands/premmerce-brands.php')) && ($vendor === 'premmercebrandsplugin')) {
			$barnd_terms = get_the_terms($product->get_id(), 'product_brand');
			if ($barnd_terms !== false) {
				foreach($barnd_terms as $barnd_term) {
					$vendor_name = $barnd_term->name;
					// $result_yml_vendor = '<vendor>'. $barnd_term->name .'</vendor>'.PHP_EOL;
					break;
				}
			}
		} else if ((is_plugin_active('woocommerce-brands/woocommerce-brands.php')) && ($vendor === 'woocommerce_brands')) {
			$barnd_terms = get_the_terms($product->get_id(), 'product_brand');
			if ($barnd_terms !== false) {
				foreach($barnd_terms as $barnd_term) {
					$vendor_name = $barnd_term->name;
					// $result_xml .= '<vendor>'. $barnd_term->name .'</vendor>'.PHP_EOL;
					break;
				}
			}
		} else if (class_exists('woo_brands') && $vendor === 'woo_brands') {
			$barnd_terms = get_the_terms($product->get_id(), 'product_brand');
			if ($barnd_terms !== false) {
				foreach($barnd_terms as $barnd_term) {
					$vendor_name = $barnd_term->name;
					// $result_yml_vendor = '<vendor>'. $barnd_term->name .'</vendor>'.PHP_EOL;
					break;
				}
			}
		} else if ((is_plugin_active('yith-woocommerce-brands-add-on/init.php')) && ($vendor === 'yith_woocommerce_brands_add_on')) {
			$barnd_terms = get_the_terms($product->get_id(), 'yith_product_brand');
			if ($barnd_terms !== false) {
				foreach($barnd_terms as $barnd_term) {
					$vendor_name = $barnd_term->name;
					// $result_yml_vendor = '<vendor>'. $barnd_term->name .'</vendor>'.PHP_EOL;
					break;
				}
			}
		} else if ($vendor == 'post_meta') {
			$vendor_post_meta_id = yfym_optionGET('yfym_vendor_post_meta', $this->get_feed_id(), 'set_arr');
			if (get_post_meta($product->get_id(), $vendor_post_meta_id, true) !== '') {					
				$vendor_yml = get_post_meta($product->get_id(), $vendor_post_meta_id, true);
				$vendor_name = ucfirst(yfym_replace_decode($vendor_yml));
				// $result_yml_vendor = "<vendor>".$vendor_yml."</vendor>".PHP_EOL;
			}
		} else if ($vendor == 'default_value') {
			$vendor_yml = yfym_optionGET('yfym_vendor_post_meta', $this->get_feed_id(), 'set_arr');
			if ($vendor_yml !== '') {
				$vendor_name = ucfirst(yfym_replace_decode($vendor_yml));
				// $result_yml_vendor = "<vendor>".$vendor_yml."</vendor>".PHP_EOL;
			}
		} else { 
			if ($vendor !== 'disabled') {
				$vendor = (int)$vendor;
				$vendor_yml = $product->get_attribute(wc_attribute_taxonomy_name_by_id($vendor));
				if (!empty($vendor_yml)) {
					$vendor_name = ucfirst(yfym_replace_decode($vendor_yml));
					// $result_yml_vendor = '<vendor>'.$vendor_yml.'</vendor>'.PHP_EOL;
				}
			}	
		}

		$skip_vendor_reason = false;
		$skip_vendor_reason = apply_filters('y4ym_f_simple_skip_vendor_reason', $skip_vendor_reason, array('product' => $product, 'vendor_name' => $vendor_name), $this->get_feed_id());
		if ($skip_vendor_reason !== false) {
			$this->add_skip_reason(array('reason' => $skip_vendor_reason, 'post_id' => $product->get_id(), 'file' => 'trait-yfym-t-simple-get-vendor.php', 'line' => __LINE__)); return '';
		}

		// $result_xml = $result_yml_vendor;

		$vendor_name = apply_filters('y4ym_f_simple_tag_value_vendor', $vendor_name, array('product' => $product), $this->get_feed_id());
		if (!empty($vendor_name)) {	
			$tag_name = apply_filters('y4ym_f_simple_tag_name_vendor', $tag_name, array('product' => $product), $this->get_feed_id());
			$result_xml = new Get_Paired_Tag($tag_name, $vendor_name);
		}

		$result_xml = apply_filters('y4ym_f_simple_tag_vendor', $result_xml, array('product' => $product, 'vendor_name' => $vendor_name), $this->get_feed_id());
		return $result_xml;
	}
}
?>