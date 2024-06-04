<?php
/**
 * Traits Url for variable products
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
 *                                      get_offer
 *                                      get_feed_id
 *                          functions:  common_option_get
 *                                      yfym_replace_domain
 *                                      get_from_url
 *                          constants:
 */
defined( 'ABSPATH' ) || exit;

trait YFYM_T_Variable_Get_Url {
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

		$tag_value = htmlspecialchars( get_permalink( $this->get_offer()->get_id() ) );
		$yfym_clear_get = common_option_get( 'yfym_clear_get', false, $this->get_feed_id(), 'yfym' );
		if ( $yfym_clear_get === 'yes' ) {
			$tag_value = get_from_url( $tag_value, 'url' );
		}
		$tag_value = apply_filters(
			'yfym_url_filter',
			$tag_value,
			$this->get_product(),
			$this->get_feed_category_id(),
			$this->get_feed_id()
		);

		// TODO: Удалить фильтр в след версиях
		$tag_value = apply_filters( 'yfym_variable_url_filter', $tag_value, $this->get_product(), $this->get_offer(),
			$this->get_feed_category_id(), $this->get_feed_id() );

		$tag_value = apply_filters(
			'y4ym_f_variable_tag_value_url',
			$tag_value,
			[ 
				'product' => $this->get_product(),
				'offer' => $this->get_offer(),
				'category_id' => $this->get_feed_category_id()
			],
			$this->get_feed_id()
		);
		if ( ! empty( $tag_value ) ) {
			$tag_name = apply_filters(
				'y4ym_f_variable_tag_name_url',
				$tag_name,
				[ 
					'product' => $this->get_product(),
					'offer' => $this->get_offer(),
					'category_id' => $this->get_feed_category_id()
				],
				$this->get_feed_id()
			);
			$result_xml = new Get_Paired_Tag( $tag_name, $tag_value );
		}

		$result_xml = yfym_replace_domain( $result_xml, $this->get_feed_id() );
		$result_xml = apply_filters(
			'y4ym_f_variable_tag_url',
			$result_xml,
			[ 
				'product' => $this->get_product(),
				'offer' => $this->get_offer(),
				'category_id' => $this->get_feed_category_id()
			],
			$this->get_feed_id()
		);
		return $result_xml;
	}
}