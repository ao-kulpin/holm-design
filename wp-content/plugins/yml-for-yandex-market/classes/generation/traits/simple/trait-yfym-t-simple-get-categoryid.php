<?php
/**
 * Traits CategoryId for simple products
 *
 * @package                 YML for Yandex Market
 * @subpackage              
 * @since                   0.1.0
 * 
 * @version                 4.0.0 (29-08-2023)
 * @author                  Maxim Glazunov
 * @link                    https://icopydoc.ru/
 * @see                     
 *
 * @depends					classes:    Get_Paired_Tag
 *                          traits:     
 *                          methods:    get_product
 *                                      get_feed_id
 *                          functions:  common_option_get
 *                          constants:  
 */
defined( 'ABSPATH' ) || exit;

trait YFYM_T_Simple_Get_CategoryId {
	/**
	 * Summary of get_categoryid
	 * 
	 * @param string $tag_name - Optional
	 * @param string $result_xml - Optional
	 * 
	 * @return string
	 */
	public function get_categoryid( $tag_name = 'categoryId', $result_xml = '' ) {
		$tag_value = '';

		$tag_value = $this->get_feed_category_id();

		$tag_value = apply_filters(
			'y4ym_f_simple_tag_value_categoryid',
			$tag_value,
			[ 'product' => $this->get_product() ],
			$this->get_feed_id()
		);
		if ( ! empty( $tag_value ) ) {
			$tag_name = apply_filters(
				'y4ym_f_simple_tag_name_categoryid',
				$tag_name,
				[ 'product' => $this->get_product() ],
				$this->get_feed_id()
			);
			$result_xml = new Get_Paired_Tag( $tag_name, $tag_value );
		}

		// TODO: удалить в след.версиях
		$result_xml = apply_filters( 'yfym_after_cat_filter', $result_xml, $this->get_product()->get_id(), $this->get_feed_id() );

		$result_xml = apply_filters(
			'y4ym_f_simple_tag_categoryid',
			$result_xml,
			[ 'product' => $this->get_product() ],
			$this->get_feed_id()
		);
		return $result_xml;
	}
}