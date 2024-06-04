<?php
/**
 * Traits Url for simple products
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
 * @depends                 classes:    Get_Paired_Tag
 *                          traits:     
 *                          methods:    get_product
 *                                      get_feed_id
 *                          functions:  common_option_get
 *                                      yfym_replace_domain
 *                                      get_from_url
 *                          constants:
 */
defined( 'ABSPATH' ) || exit;

trait YFYM_T_Simple_Get_Url {
	/**
	 * Get product url
	 * 
	 * @param string $tag_name - Optional
	 * @param string $result_xml - Optional
	 * 
	 * @return string
	 */
	public function get_url( $tag_name = 'url', $result_xml = '' ) {
		$tag_value = '';

		$tag_value = htmlspecialchars( get_permalink( $this->get_product()->get_id() ) ); // урл товара
		$yfym_clear_get = yfym_optionGET( 'yfym_clear_get', $this->get_feed_id(), 'set_arr' );
		if ( $yfym_clear_get === 'yes' ) {
			$tag_value = get_from_url( $tag_value, 'url' ); // удаляем get-параметры
		}
		$tag_value = apply_filters(
			'yfym_url_filter',
			$tag_value,
			$this->get_product(),
			$this->get_feed_category_id(),
			$this->get_feed_id()
		);

		// TODO: Удалить фильтр в след версиях
		$tag_value = apply_filters( 'yfym_simple_url_filter', $tag_value, $this->get_product(),
			$this->get_feed_category_id(), $this->get_feed_id() );

		$tag_value = apply_filters(
			'y4ym_f_simple_tag_value_url',
			$tag_value,
			[ 
				'product' => $this->get_product(),
				'category_id' => $this->get_feed_category_id()
			],
			$this->get_feed_id()
		);
		if ( ! empty( $tag_value ) ) {
			$tag_name = apply_filters(
				'y4ym_f_simple_tag_name_url',
				$tag_name,
				[ 
					'product' => $this->get_product(),
					'category_id' => $this->get_feed_category_id()
				],
				$this->get_feed_id()
			);
			$result_xml = new Get_Paired_Tag( $tag_name, $tag_value );
		}

		$result_xml = yfym_replace_domain( $result_xml, $this->get_feed_id() );
		$result_xml = apply_filters(
			'y4ym_f_simple_tag_url',
			$result_xml,
			[ 
				'product' => $this->get_product(),
				'category_id' => $this->get_feed_category_id()
			],
			$this->get_feed_id()
		);
		return $result_xml;
	}
}