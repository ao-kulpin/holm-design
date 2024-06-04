<?php
/**
 * Traits Offer for simple products
 *
 * @package                 YML for Yandex Market
 * @subpackage              
 * @since                   0.1.0
 * 
 * @version                 4.0.4 (15-09-2023)
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
 *                          constants:
 */
defined( 'ABSPATH' ) || exit;

trait YFYM_T_Simple_Get_Offer_Tag {
	/**
	 * Get product url
	 * 
	 * @param string $tag_name - Optional
	 * @param string $result_xml - Optional
	 * 
	 * @return string
	 */
	public function get_offer_tag( $tag_name = 'offer', $result_xml = '' ) {
		$offer_type = '';
		$offer_type = apply_filters( 'yfym_offer_type_filter', $offer_type, $this->get_feed_category_id(), $this->get_product()->get_id(), $this->get_product(), $this->get_feed_id() ); /* изменён с версии 3.3.3 */

		$append_offer_tag = '';
		if ( get_post_meta( $this->get_product()->get_id(), 'yfym_bid', true ) !== '' ) {
			$yfym_bid = get_post_meta( $this->get_product()->get_id(), 'yfym_bid', true );
			$append_offer_tag = 'bid="' . $yfym_bid . '"';
		}

		$yfym_on_demand = common_option_get( 'yfym_on_demand', false, $this->get_feed_id(), 'yfym' );
		if ( $yfym_on_demand === 'enabled' && $this->get_product()->get_stock_status() === 'onbackorder' ) {
			$append_offer_tag .= ' type="on.demand"';
		}

		/* с версии 2.1.2 */
		$append_offer_tag = apply_filters( 'yfym_append_offer_tag_filter', $append_offer_tag, $this->get_product(), $this->get_feed_id() );

		$offer_id_value = $this->get_product()->get_id();
		$res_id_value = '';
		$yfym_source_id = common_option_get( 'yfym_source_id', false, $this->get_feed_id(), 'yfym' );
		switch ( $yfym_source_id ) {
			case "sku":
				$res_id_value = $this->get_product()->get_sku();
				break;
			case "post_meta":
				$yfym_source_id_post_meta = common_option_get( 'yfym_source_id_post_meta', false, $this->get_feed_id(), 'yfym' );
				$yfym_source_id_post_meta = trim( $yfym_source_id_post_meta );
				if ( get_post_meta( $this->get_product()->get_id(), $yfym_source_id_post_meta, true ) !== '' ) {
					$res_id_value = get_post_meta( $this->get_product()->get_id(), $yfym_source_id_post_meta, true );
				}
				break;
			case "germanized":
				if ( class_exists( 'WooCommerce_Germanized' ) ) {
					if ( get_post_meta( $this->get_product()->get_id(), '_ts_gtin', true ) !== '' ) {
						$res_id_value = get_post_meta( $this->get_product()->get_id(), '_ts_gtin', true );
					}
				}
				break;
			default:
				$res_id_value = $offer_id_value;
		}
		if ( ! empty( $res_id_value ) ) {
			$offer_id_value = $res_id_value;
		}

		$offer_id_yml = 'id="' . $offer_id_value . '"';

		$offer_id_yml = apply_filters(
			'y4ym_f_simple_offer_id_yml',
			$offer_id_yml,
			[ 
				'product' => $this->get_product()
			],
			$this->get_feed_id()
		);

		if ( $this->get_product()->get_manage_stock() == true ) { // включено управление запасом
			if ( $this->get_product()->get_stock_quantity() > 0 ) {
				$available = 'true';
			} else {
				if ( $this->get_product()->get_backorders() === 'no' ) { // предзаказ запрещен
					$available = 'false';
				} else {
					$yfym_behavior_onbackorder = common_option_get( 'yfym_behavior_onbackorder', false, $this->get_feed_id(), 'yfym' );
					if ( $yfym_behavior_onbackorder === 'false' ) {
						$available = 'false';
					} else {
						$available = 'true';
					}
				}
			}
		} else { // отключено управление запасом
			if ( $this->get_product()->get_stock_status() === 'instock' ) {
				$available = 'true';
			} else if ( $this->get_product()->get_stock_status() === 'outofstock' ) {
				$available = 'false';
			} else {
				$yfym_behavior_onbackorder = common_option_get( 'yfym_behavior_onbackorder', false, $this->get_feed_id(), 'yfym' );
				if ( $yfym_behavior_onbackorder === 'false' ) {
					$available = 'false';
				} else {
					$available = 'true';
				}
			}
		}
		$available = apply_filters( 'yfym_available_filter', $available, $this->get_product(), $this->get_product()->get_id(), $this->get_feed_id() ); /* С версии 3.5.3 */

		$available_yml = ' available="' . $available . '" ';
		$available_yml = apply_filters( 'yfym_simple_available_yml_filter', $available_yml, $this->get_product(), $this->get_feed_id() );
		$result_xml = '<offer ' . $offer_id_yml . $available_yml . $append_offer_tag . ' ' . $offer_type . '>' . PHP_EOL;

		$result_xml = apply_filters(
			'y4ym_f_simple_tag_offer',
			$result_xml,
			[ 
				'product' => $this->get_product()
			],
			$this->get_feed_id()
		);
		return $result_xml;
	}
}