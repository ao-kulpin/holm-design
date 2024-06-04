<?php if (!defined('ABSPATH')) {exit;}
/**
 * Traits Picture for simple products
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
 *										yfym_replace_domain
 *							constants:	
 */

trait YFYM_T_Simple_Get_Picture {
	public function get_picture($tag_name = 'picture', $result_xml = '') {
		// убираем default.png из фида
		$no_default_png_products = yfym_optionGET('yfym_no_default_png_products', $this->get_feed_id(), 'set_arr');
		if (($no_default_png_products === 'on') && (!has_post_thumbnail($this->get_product()->get_id()))) {
			$picture_yml = '';
		} else {
			$thumb_id = get_post_thumbnail_id($this->get_product()->get_id());
			$thumb_url = wp_get_attachment_image_src($thumb_id, 'full', true);
			$tag_value = $thumb_url[0]; /* урл оригинал миниатюры товара */
			$tag_value = get_from_url($tag_value);
			$picture_yml = new Get_Paired_Tag($tag_name, $tag_value);
		}
		$picture_yml = apply_filters('yfym_pic_simple_offer_filter', $picture_yml, $this->get_product(), $this->get_feed_id());

		// пропускаем товары без картинок
		$yfym_skip_products_without_pic = yfym_optionGET('yfym_skip_products_without_pic', $this->get_feed_id(), 'set_arr'); 
		if (($yfym_skip_products_without_pic === 'on') && ($picture_yml == '')) {
			$this->add_skip_reason( [ 
				'reason' => __('Product has no images', 'yml-for-yandex-market'), 
				'post_id' => $this->get_product()->get_id(), 
				'file' => 'trait-yfym-t-simple-get-picture.php', 
				'line' => __LINE__ ] 
			); 
			return '';
		}

		$result_xml = $picture_yml;

		$result_xml = yfym_replace_domain($result_xml, $this->get_feed_id());
		$result_xml = apply_filters('y4ym_f_simple_tag_picture', $result_xml, [ 'product' => $this->get_product() ], $this->get_feed_id());
		return $result_xml;
	}
}