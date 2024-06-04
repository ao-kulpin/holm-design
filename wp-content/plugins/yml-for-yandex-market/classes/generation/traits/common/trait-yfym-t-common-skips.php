<?php
/**
 * Traits for different classes
 *
 * @package                 YML for Yandex Market
 * @subpackage              
 * @since                   0.1.0
 * 
 * @version                 4.0.3 (08-09-2023)
 * @author                  Maxim Glazunov
 * @link                    https://icopydoc.ru/
 * @see                     
 *
 * @depends					classes:    Get_Paired_Tag
 *                          traits:     
 *                          methods:    get_product
 *                                      get_offer
 *                                      get_feed_id
 *                          functions:  common_option_get
 *                          constants:  
 *                          variable:   feed_category_id (set it)
 */
defined( 'ABSPATH' ) || exit;

trait YFYM_T_Common_Skips {
	/**
	 * Summary of get_skips
	 * 
	 * @return void
	 */
	public function get_skips() {
		$skip_flag = false;

		if ( null == $this->get_product() ) {
			$this->add_skip_reason( [ 
				'reason' => __( 'There is no product with this ID', 'yml-for-yandex-market' ),
				'post_id' => $this->get_product()->get_id(),
				'file' => 'trait-yfym-t-common-skips.php',
				'line' => __LINE__
			] );
			return;
		}

		if ( $this->get_product()->is_type( 'grouped' ) ) {
			$this->add_skip_reason( [ 
				'reason' => __( 'Product is grouped', 'yml-for-yandex-market' ),
				'post_id' => $this->get_product()->get_id(),
				'file' => 'trait-yfym-t-common-skips.php',
				'line' => __LINE__
			] );
			return;
		}

		if ( $this->get_product()->is_type( 'external' ) ) {
			$this->add_skip_reason( [ 
				'reason' => __( 'Product is External/Affiliate product', 'yml-for-yandex-market' ),
				'post_id' => $this->get_product()->get_id(),
				'file' => 'trait-yfym-t-common-skips.php',
				'line' => __LINE__
			] );
			return;
		}

		if ( $this->get_product()->get_status() !== 'publish' ) {
			$this->add_skip_reason( [ 
				'reason' => sprintf( '%s "%s"',
					__( 'The product status/visibility is', 'yml-for-yandex-market' ),
					$this->get_product()->get_status()
				),
				'post_id' => $this->get_product()->get_id(),
				'file' => 'trait-yfym-t-common-skips.php',
				'line' => __LINE__
			] );
			return;
		}

		// что выгружать
		$whot_export = common_option_get( 'yfym_whot_export', false, $this->get_feed_id(), 'yfym' );
		if ( $this->get_product()->is_type( 'variable' ) ) {
			if ( $whot_export === 'simple' ) {
				$this->add_skip_reason( [ 
					'reason' => __( 'Product is variable', 'yml-for-yandex-market' ),
					'post_id' => $this->get_product()->get_id(),
					'file' => 'trait-yfym-t-common-skips.php',
					'line' => __LINE__
				] );
				return;
			}
		}
		if ( $this->get_product()->is_type( 'simple' ) ) {
			if ( $whot_export === 'variable' ) {
				$this->add_skip_reason( [ 
					'reason' => __( 'Product is simple', 'yml-for-yandex-market' ),
					'post_id' => $this->get_product()->get_id(),
					'file' => 'trait-yfym-t-common-skips.php',
					'line' => __LINE__
				] );
				return;
			}
		}

		if ( get_post_meta( $this->get_product()->get_id(), 'yfymp_removefromyml', true ) === 'on' ) {
			$this->add_skip_reason( [ 
				'reason' => __( 'The "Remove product from feed" condition worked', 'yml-for-yandex-market' ),
				'post_id' => $this->get_product()->get_id(),
				'file' => 'trait-yfym-t-common-skips.php',
				'line' => __LINE__
			] );
			return;
		}

		// на удаление в след версиях
		$skip_flag = apply_filters(
			'yfym_skip_flag',
			$skip_flag,
			$this->get_product()->get_id(),
			$this->get_product(),
			$this->get_feed_id()
		);
		if ( true === $skip_flag ) {
			$this->add_skip_reason( [ 
				'reason' => __( 'Flag', 'yml-for-yandex-market' ),
				'post_id' => $this->get_product()->get_id(),
				'file' => 'trait-yfym-t-common-skips.php',
				'line' => __LINE__
			] );
			return;
		}
		// TODO: на удаление в след версиях
		/* С версии 3.7.13 */
		$skip_flag = apply_filters(
			'y4ym_f_skip_flag',
			$skip_flag,
			[ 
				'product' => $this->get_product(),
				'catid' => $this->get_feed_category_id()
			],
			$this->get_feed_id()
		);
		if ( $skip_flag !== false ) {
			$this->add_skip_reason( [ 
				'reason' => $skip_flag,
				'post_id' => $this->get_product()->get_id(),
				'file' => 'trait-yfym-t-common-skips.php',
				'line' => __LINE__
			] );
			return;
		}

		// пропуск товаров, которых нет в наличии
		$skip_missing_products = common_option_get( 'yfym_skip_missing_products', false, $this->get_feed_id(), 'yfym' );
		if ( $skip_missing_products == 'on' ) {
			if ( false == $this->get_product()->is_in_stock() ) {
				$this->add_skip_reason( [ 
					'reason' => __( 'Skip missing products', 'yml-for-yandex-market' ),
					'post_id' => $this->get_product()->get_id(),
					'file' => 'trait-yfym-t-common-skips.php',
					'line' => __LINE__
				] );
				return;
			}
		}

		// пропускаем товары на предзаказ
		$skip_backorders_products = common_option_get( 'skip_backorders_products', false, $this->get_feed_id(), 'yfym' );
		if ( $skip_backorders_products == 'on' ) {
			if ( $this->get_product()->get_manage_stock() == true ) { // включено управление запасом  
				if ( ( $this->get_product()->get_stock_quantity() < 1 )
					&& ( $this->get_product()->get_backorders() !== 'no' ) ) {
					$this->add_skip_reason( [ 
						'reason' => __( 'Skip backorders products', 'yml-for-yandex-market' ),
						'post_id' => $this->get_product()->get_id(),
						'file' => 'trait-yfym-t-common-skips.php',
						'line' => __LINE__
					] );
					return;
				}
			} else {
				if ( $this->get_product()->get_stock_status() !== 'instock' ) {
					$this->add_skip_reason( [ 
						'reason' => __( 'Skip backorders products', 'yml-for-yandex-market' ),
						'post_id' => $this->get_product()->get_id(),
						'file' => 'trait-yfym-t-common-skips.php',
						'line' => __LINE__
					] );
					return;
				}
			}
		}

		if ( $this->get_product()->is_type( 'variable' ) ) {
			// ? нужно ли это... 
			// $this->get_offer() = $this->offer; // TODO: на удаление в след версиях 

			// пропуск вариаций, которых нет в наличии
			$skip_missing_products = common_option_get( 'yfym_skip_missing_products', false, $this->get_feed_id(), 'yfym' );
			if ( $skip_missing_products == 'on' ) {
				if ( false == $this->get_offer()->is_in_stock() ) {
					$this->add_skip_reason( [ 
						'offer_id' => $this->get_offer()->get_id(),
						'reason' => __( 'Skip missing products', 'yml-for-yandex-market' ),
						'post_id' => $this->get_product()->get_id(),
						'file' => 'traits-yfym-variable.php',
						'line' => __LINE__
					] );
					return;
				}
			}

			// пропускаем вариации на предзаказ
			$skip_backorders_products = common_option_get( 'skip_backorders_products', false, $this->get_feed_id(), 'yfym' );
			if ( $skip_backorders_products == 'on' ) {
				if ( true == $this->get_offer()->get_manage_stock() ) { // включено управление запасом			  
					if ( ( $this->get_offer()->get_stock_quantity() < 1 )
						&& ( $this->get_offer()->get_backorders() !== 'no' ) ) {
						$this->add_skip_reason( [ 
							'offer_id' => $this->get_offer()->get_id(),
							'reason' => __( 'Skip backorders products', 'yml-for-yandex-market' ),
							'post_id' => $this->get_product()->get_id(),
							'file' => 'traits-yfym-variable.php',
							'line' => __LINE__
						] );
						return;
					}
				}
			}
			// TODO: на удаление в след версиях
			$skip_flag = apply_filters(
				'yfym_skip_flag_variable',
				$skip_flag,
				$this->get_product()->get_id(),
				$this->get_product(),
				$this->get_offer(),
				$this->get_feed_id()
			);
			if ( $skip_flag === true ) {
				$this->add_skip_reason( [ 
					'offer_id' => $this->get_offer()->get_id(),
					'reason' => __( 'Flag', 'yml-for-yandex-market' ),
					'post_id' => $this->get_product()->get_id(),
					'file' => 'traits-yfym-variable.php',
					'line' => __LINE__
				] );
				return;
			}
			if ( $skip_flag === 'continue' ) {
				$this->add_skip_reason( [ 
					'offer_id' => $this->get_offer()->get_id(),
					'reason' => __( 'Flag', 'yml-for-yandex-market' ),
					'post_id' => $this->get_product()->get_id(),
					'file' => 'traits-yfym-variable.php',
					'line' => __LINE__
				] );
				return; // return 'continue';		
			}
			// TODO: на удаление в след версиях
			/* С версии 3.7.13 */
			$skip_flag = apply_filters(
				'y4ym_f_skip_flag_variable',
				$skip_flag,
				[ 
					'product' => $this->get_product(),
					'offer' => $this->get_offer(),
					'catid' => $this->get_feed_category_id()
				],
				$this->get_feed_id()
			);
			if ( $skip_flag !== false ) {
				$this->add_skip_reason( [ 
					'offer_id' => $this->get_offer()->get_id(),
					'reason' => $skip_flag,
					'post_id' => $this->get_product()->get_id(),
					'file' => 'trait-yfym-t-common-skips.php',
					'line' => __LINE__
				] );
				return;
			}
		}
	}

}