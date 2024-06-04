<?php
/**
 * Interface Hoocked
 *
 * @package                 YML for Yandex Market
 * @subpackage              
 * @since                   0.1.0
 * 
 * @version                 4.0.5 (20-09-2023)
 * @author                  Maxim Glazunov
 * @link                    https://icopydoc.ru/
 * @see                     
 * 
 * @param         
 *
 * @depends                 classes:    YFYM_Error_Log
 *                          traits:     
 *                          methods:    
 *                          functions:  common_option_get
 *                                      common_option_upd
 *                          constants:  
 *                          options:    
 */
defined( 'ABSPATH' ) || exit;

final class Y4YM_Interface_Hoocked {
	/**
	 * Summary of __construct
	 */
	public function __construct() {
		$this->init_hooks();
		$this->init_classes();
	}

	/**
	 * Initialization hooks
	 * 
	 * @return void
	 */
	public function init_hooks() {
		add_action( 'woocommerce_product_data_panels', [ $this, 'yfym_art_added_tabs_panel' ], 10, 1 );
		add_action( 'woocommerce_product_options_general_product_data',
			[ $this, 'yfym_woocommerce_product_options_general_product_data' ],
			10,
			1
		);
		// https://wpruse.ru/woocommerce/custom-fields-in-products/
		// https://wpruse.ru/woocommerce/custom-fields-in-variations/
		add_filter( 'woocommerce_product_data_tabs', [ $this, 'yfym_added_wc_tabs' ], 10, 1 );
		add_action( 'admin_footer', [ $this, 'yfym_art_added_tabs_icon' ], 10, 1 );
		// индивидуальные опции доставки товара
		add_action( 'save_post', [ $this, 'save_post_product' ], 50, 3 );
		// пришлось юзать save_post вместо save_post_product ибо wc блочит обновы

		add_filter( 'yfym_f_save_if_empty', [ $this, 'flag_save_if_empty' ], 10, 2 );
	}

	/**
	 * Initialization classes
	 * 
	 * @return void
	 */
	public function init_classes() {
		return;
	}

	/**
	 * Сохраняем данные блока, когда пост сохраняется
	 * 
	 * @param int $post_id
	 * @param object $post
	 * @param bool $update (true — это обновление записи; false — это добавление новой записи)
	 * 
	 * @return void
	 */
	public function save_post_product( $post_id, $post, $update ) {
		new YFYM_Error_Log( 'Стартовала функция save_post_product. Файл: class-y4ym-interface-hocked.php; Строка: ' . __LINE__ );

		if ( $post->post_type !== 'product' ) {
			return; // если это не товар вукомерц
		}
		if ( wp_is_post_revision( $post_id ) ) {
			return; // если это ревизия
		}
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return; // если это автосохранение ничего не делаем
		}
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return; // проверяем права юзера
		}

		$post_meta_arr = [ 
			'yfym_individual_delivery',
			'yfym_cost',
			'yfym_days',
			'yfym_order_before',
			'yfym_individual_pickup',
			'yfym_pickup_cost',
			'yfym_pickup_days',
			'yfym_pickup_order_before',
			'yfym_bid',
			'yfym_individual_vat',
			// 'yfym_condition',
			'_yfym_condition',
			'yfym_reason',
			'_yfym_quality',
			'_yfym_market_sku',
			'_yfym_tn_ved_code',
			'yfym_credit_template',
			'_yfym_cargo_types',
			'_yfym_supplier',
			'_yfym_min_quantity',
			'_yfym_step_quantity',
			'_yfym_premium_price',
			'_yfym_price_rrp',
			'_yfym_min_price',
			'_yfym_video_url'
		];
		$this->save_post_meta( $post_meta_arr, $post_id );

		// Убедимся что поле установлено.
		if ( isset( $_POST['yfym_cost'] ) ) {
			$yfym_recommend_stock_data_arr = [];
			$yfym_recommend_stock_data_arr['availability'] = sanitize_text_field( $_POST['_yfym_availability'] );
			$yfym_recommend_stock_data_arr['transport_unit'] = sanitize_text_field( $_POST['_yfym_transport_unit'] );
			$yfym_recommend_stock_data_arr['min_delivery_pieces'] = sanitize_text_field( $_POST['_yfym_min_delivery_pieces'] );
			$yfym_recommend_stock_data_arr['quantum'] = sanitize_text_field( $_POST['_yfym_quantum'] );
			$yfym_recommend_stock_data_arr['leadtime'] = sanitize_text_field( $_POST['_yfym_leadtime'] );
			$yfym_recommend_stock_data_arr['box_count'] = sanitize_text_field( $_POST['_yfym_box_count'] );
			if ( isset( $_POST['_delivery_weekday_arr'] ) && ! empty( $_POST['_delivery_weekday_arr'] ) ) {
				$yfym_recommend_stock_data_arr['delivery_weekday_arr'] = $_POST['_delivery_weekday_arr'];
			} else {
				$yfym_recommend_stock_data_arr['delivery_weekday_arr'] = [];
			}
			// Обновляем данные в базе данных
			update_post_meta( $post_id, '_yfym_recommend_stock_data_arr', $yfym_recommend_stock_data_arr );
		}

		// нужно ли запускать обновление фида при перезаписи файла
		$yfym_settings_arr = yfym_optionGET( 'yfym_settings_arr' );
		$yfym_settings_arr_keys_arr = array_keys( $yfym_settings_arr );
		for ( $i = 0; $i < count( $yfym_settings_arr_keys_arr ); $i++ ) {
			$feed_id = $yfym_settings_arr_keys_arr[ $i ];

			new YFYM_Error_Log(
				sprintf( 'FEED № %1$s; Шаг $i = %2$s цикла по формированию кэша файлов; Файл: %3$s; Строка: %4$s',
					$feed_id,
					$i,
					'class-y4ym-interface-hocked.php',
					__LINE__
				)
			);

			// если в настройках включено создание кэш-файлов в момент сохранения товара
			$do_cash_file = common_option_get( 'yfym_do_cash_file', false, $feed_id, 'yfym' );
			if ( $do_cash_file !== 'enabled' ) {
				$result_get_unit_obj = new YFYM_Get_Unit( $post_id, $feed_id );
				$result_xml = $result_get_unit_obj->get_result();
				$ids_in_xml = $result_get_unit_obj->get_ids_in_xml();
				yfym_wf( $result_xml, $post_id, $feed_id, $ids_in_xml );
			}

			// нужно ли запускать обновление фида при перезаписи файла
			$yfym_ufup = common_option_get( 'yfym_ufup', false, $feed_id, 'yfym' );
			if ( $yfym_ufup == 'on' ) {
				new YFYM_Error_Log(
					sprintf( 'FEED № %1$s; Шаг $yfym_ufup = %2$s Пересборка фида требуется; Файл: %3$s; Строка: %4$s',
						$feed_id,
						$yfym_ufup,
						'class-y4ym-interface-hocked.php',
						__LINE__
					)
				);
			} else {
				new YFYM_Error_Log(
					sprintf( 'FEED № %1$s; Шаг $yfym_ufup = %2$s Пересборка фида не требуется; Файл: %3$s; Строка: %4$s',
						$feed_id,
						$yfym_ufup,
						'class-y4ym-interface-hocked.php',
						__LINE__
					)
				);
				continue;
			}
			$status_sborki = (int) yfym_optionGET( 'yfym_status_sborki', $feed_id );
			if ( $status_sborki > -1 ) {
				continue; // если идет сборка фида - пропуск
			}

			new YFYM_Error_Log(
				sprintf( 'FEED № %1$s; Пересборка запускается; Файл: %2$s; Строка: %3$s',
					$feed_id,
					'class-y4ym-interface-hocked.php',
					__LINE__
				)
			);

			$yfym_date_save_set = common_option_get( 'yfym_date_save_set', false, $feed_id, 'yfym' );

			$feed_file_meta = new YFYM_Feed_File_Meta( $feed_id );
			$filenamefeed = sprintf( '%1$s/%2$s.%3$s',
				YFYM_SITE_UPLOADS_DIR_PATH,
				$feed_file_meta->get_feed_filename(),
				$feed_file_meta->get_feed_extension()
			);
			if ( ! file_exists( $filenamefeed ) ) { // файла с фидом нет
				new YFYM_Error_Log(
					sprintf( 'FEED № %1$s; WARNING: Файла %2$s не существует! Пропускаем быструю сборку; Файл: %3$s; Строка: %4$s',
						$feed_id,
						$filenamefeed,
						'class-y4ym-interface-hocked.php',
						__LINE__
					)
				);
				continue;
			}

			clearstatcache(); // очищаем кэш дат файлов
			$last_upd_file = filemtime( $filenamefeed );
			new YFYM_Error_Log(
				sprintf( 'FEED № %1$s; %2$s: $yfym_date_save_set = %3$s; $filenamefeed = %4$s; Файл: %5$s; Строка: %6$s',
					$feed_id,
					'Начинаем сравнивать даты',
					$yfym_date_save_set,
					$filenamefeed,
					'class-y4ym-interface-hocked.php',
					__LINE__
				)
			);
			if ( $yfym_date_save_set > $last_upd_file ) {
				// настройки фида сохранялись позже, чем создан фид. Нужно полностью пересобрать фид
				new YFYM_Error_Log(
					sprintf( 'FEED № %1$s; NOTICE: %2$s; Файл: %3$s; Строка: %4$s',
						$feed_id,
						'Настройки фида сохранялись позже, чем создан фид',
						'class-y4ym-interface-hocked.php',
						__LINE__
					)
				);

				$yfym_run_cron = common_option_get( 'yfym_status_cron', false, $feed_id, 'yfym' );
				if ( $yfym_run_cron !== 'off' ) {
					$feedid = (string) $feed_id; // ! для правильности работы важен тип string
					$recurrence = $yfym_run_cron;
					wp_clear_scheduled_hook( 'yfym_cron_period', [ $feedid ] );
					wp_schedule_event( time(), $recurrence, 'yfym_cron_period', [ $feedid ] );
					new YFYM_Error_Log(
						sprintf( 'FEED № %1$s; %2$s; Файл: %3$s; Строка: %4$s',
							$feed_id,
							'Для полной пересборки после быстрого сохранения yfym_cron_period внесен в список заданий',
							'class-y4ym-interface-hocked.php',
							__LINE__
						)
					);
				}
			} else { // нужно лишь обновить цены
				$feed_id = (string) $feed_id;
				new YFYM_Error_Log(
					sprintf( 'FEED № %1$s; NOTICE: %2$s; Файл: %3$s; Строка: %4$s',
						$feed_id,
						'Настройки фида сохранялись раньше, чем создан фид. Нужно лишь обновить цены',
						'class-y4ym-interface-hocked.php',
						__LINE__
					)
				);
				$generation = new YFYM_Generation_XML( $feed_id );
				$generation->clear_file_ids_in_xml( $feed_id );
				$generation->onlygluing();
			}
		}
		return;
	}

	/**
	 * Summary of save_post_meta
	 * 
	 * @param array $post_meta_arr
	 * @param int $post_id
	 * 
	 * @return void
	 */
	private function save_post_meta( $post_meta_arr, $post_id ) {
		for ( $i = 0; $i < count( $post_meta_arr ); $i++ ) {
			$meta_name = $post_meta_arr[ $i ];
			if ( isset( $_POST[ $meta_name ] ) ) {
				update_post_meta( $post_id, $meta_name, sanitize_text_field( $_POST[ $meta_name ] ) );
			}
		}
	}

	public static function yfym_woocommerce_product_options_general_product_data() {
		global $product, $post;
		echo '<div class="options_group">'; // Группировка полей 
		woocommerce_wp_text_input( [ 
			'id' => '_yfym_premium_price',
			'label' => 'premium_price',
			'placeholder' => '0',
			'description' => __( 'Price for Ozon Premium customers. Used only in the OZONE feed', 'yml-for-yandex-market' ),
			'type' => 'number',
			'custom_attributes' => [ 
				'step' => '0.01',
				'min' => '0'
			]
		] );
		woocommerce_wp_text_input( [ 
			'id' => '_yfym_price_rrp',
			'label' => 'price_rrp',
			'placeholder' => '0',
			'description' => __( 'Recommended retail price, type of price for suppliers', 'yml-for-yandex-market' ),
			'type' => 'number',
			'custom_attributes' => [ 
				'step' => '0.01',
				'min' => '0'
			]
		] );
		woocommerce_wp_text_input( [ 
			'id' => '_yfym_min_price',
			'label' => 'min_price',
			'placeholder' => '0',
			'description' => __( 'Minimum price', 'yml-for-yandex-market' ),
			'type' => 'number',
			'custom_attributes' => [ 
				'step' => '0.01',
				'min' => '0'
			]
		] );
		echo '</div>';
	}

	/**
	 * Summary of yfym_added_wc_tabs
	 * 
	 * @param array $tabs
	 * 
	 * @return array
	 */
	public static function yfym_added_wc_tabs( $tabs ) {
		$tabs['yfym_special_panel'] = [ 
			'label' => __( 'YML for Yandex Market', 'yml-for-yandex-market' ), // название вкладки
			'target' => 'yfym_added_wc_tabs', // идентификатор вкладки
			'class' => [ 'hide_if_grouped' ], // классы управления видимостью вкладки в зависимости от типа товара
			'priority' => 70, // приоритет вывода
		];
		return $tabs;
	}

	/**
	 * Summary of yfym_art_added_tabs_icon
	 * 
	 * @return void
	 */
	public static function yfym_art_added_tabs_icon() {
		// https://rawgit.com/woothemes/woocommerce-icons/master/demo.html 
		?>
		<style>
			#woocommerce-coupon-data ul.wc-tabs li.yfym_special_panel_options a::before,
			#woocommerce-product-data ul.wc-tabs li.yfym_special_panel_options a::before,
			.woocommerce ul.wc-tabs li.yfym_special_panel_options a::before {
				content: "\f172";
			}
		</style>
		<?php
	}

	/**
	 * Summary of yfym_art_added_tabs_panel
	 * 
	 * @return void
	 */
	public static function yfym_art_added_tabs_panel() {
		global $post; ?>
		<div id="yfym_added_wc_tabs" class="panel woocommerce_options_panel">
			<?php do_action( 'yfym_prepend_options_panel', $post ); ?>
			<div class="options_group">
				<h2>
					<strong>
						<?php _e( 'Individual product settings for YML-feed', 'yml-for-yandex-market' ); ?>
					</strong>
				</h2>
				<p>
					<?php _e( 'Here you can set up individual options terms for this product', 'yml-for-yandex-market' ); ?>. <a
						target="_blank" href="//yandex.ru/support/partnermarket/elements/delivery-options.html#structure">
						<?php _e( 'Read more on Yandex', 'yml-for-yandex-market' ); ?>
					</a>
				</p>
				<?php do_action( 'yfym_prepend_options_group_1', $post ); ?>
				<?php
				woocommerce_wp_select( [ 
					'id' => 'yfym_individual_delivery',
					'label' => __( 'Delivery', 'yml-for-yandex-market' ),
					'options' => [ 
						'off' => __( 'Disabled', 'yml-for-yandex-market' ),
						'false' => 'False',
						'true' => 'True'
					],
					'description' => __( 'Optional element', 'yml-for-yandex-market' ) . ' <strong>delivery</strong>'
				] );

				// цифровое поле
				woocommerce_wp_text_input( [ 
					'id' => 'yfym_cost',
					'label' => __( 'Delivery cost', 'yml-for-yandex-market' ),
					//	'placeholder' => '1',
					'description' => __( 'Required element', 'yml-for-yandex-market' ) . ' <strong>cost</strong> ' . __( 'of attribute', 'yml-for-yandex-market' ) . ' <strong>delivery-option</strong>',
					'type' => 'number',
					'custom_attributes' => [ 
						'step' => 'any',
						'min' => '0'
					]
				] );

				// текстовое поле
				woocommerce_wp_text_input( [ 
					'id' => 'yfym_days',
					'label' => __( 'Delivery days', 'yml-for-yandex-market' ),
					'description' => __( 'Required element', 'yml-for-yandex-market' ) . ' <strong>days</strong> ' . __( 'of attribute', 'yml-for-yandex-market' ) . ' <strong>delivery-option</strong>',
					'type' => 'text'
				] );

				// текстовое поле
				woocommerce_wp_text_input( [ 
					'id' => 'yfym_order_before',
					'label' => __( 'The time', 'yml-for-yandex-market' ),
					'description' => __( 'Optional element', 'yml-for-yandex-market' ) . ' <strong>order-before</strong> ' . __( 'of attribute', 'yml-for-yandex-market' ) . ' <strong>delivery-option</strong>. ' . __( 'The time in which you need to place an order to get it at this time', 'yml-for-yandex-market' ),
					//	'desc_tip' => 'true',
					'type' => 'text'
				] );

				?>
				<?php do_action( 'yfym_append_options_group_1', $post ); ?>
			</div>
			<?php do_action( 'yfym_append_options_between_group_1_2', $post ); ?>
			<div class="options_group">
				<h2>
					<?php _e( 'Here you can configure the pickup conditions for this product', 'yml-for-yandex-market' ); ?>
				</h2>
				<?php do_action( 'yfym_prepend_options_group_2', $post ); ?>
				<?php
				woocommerce_wp_select( [ 
					'id' => 'yfym_individual_pickup',
					'label' => __( 'Pickup', 'yml-for-yandex-market' ),
					'options' => [ 
						'off' => __( 'Disabled', 'yml-for-yandex-market' ),
						'false' => 'False',
						'true' => 'True'
					],
					'description' => __( 'Optional element', 'yml-for-yandex-market' ) . ' <strong>pickup</strong>'
				] );

				// цифровое поле
				woocommerce_wp_text_input( [ 
					'id' => 'yfym_pickup_cost',
					'label' => __( 'Pickup cost', 'yml-for-yandex-market' ),
					'description' => sprintf( '%s <strong>cost</strong> %s <strong>pickup-options</strong>',
						__( 'Required element', 'yml-for-yandex-market' ),
						__( 'of attribute', 'yml-for-yandex-market' )
					),
					'type' => 'number',
					'custom_attributes' => [ 
						'step' => 'any',
						'min' => '0'
					],
				] );

				// текстовое поле
				woocommerce_wp_text_input( [ 
					'id' => 'yfym_pickup_days',
					'label' => __( 'Pickup days', 'yml-for-yandex-market' ),
					'description' => sprintf( '%s <strong>days</strong> %s <strong>pickup-options</strong>',
						__( 'Required element', 'yml-for-yandex-market' ),
						__( 'of attribute', 'yml-for-yandex-market' )
					),
					'type' => 'text'
				] );

				// текстовое поле
				woocommerce_wp_text_input( [ 
					'id' => 'yfym_pickup_order_before',
					'label' => __( 'The time', 'yml-for-yandex-market' ),
					'description' => sprintf( '%s <strong>order-before</strong> %s <strong>pickup-options</strong> %s',
						__( 'Required element', 'yml-for-yandex-market' ),
						__( 'of attribute', 'yml-for-yandex-market' ),
						__(
							'The time in which you need to place an order to get it at this time',
							'yml-for-yandex-market'
						)
					),
					'type' => 'text'
				] );

				?>
				<?php do_action( 'yfym_append_options_group_2', $post ); ?>
			</div>
			<?php do_action( 'yfym_append_options_between_group_2_3', $post ); ?>
			<div class="options_group">
				<h2>
					<?php _e( 'Bid values', 'yml-for-yandex-market' ); ?> &
					<?php _e( 'Сondition', 'yml-for-yandex-market' ); ?>
				</h2>
				<?php do_action( 'yfym_prepend_options_group_3', $post ); ?>
				<?php
				woocommerce_wp_text_input( [ 
					'id' => 'yfym_bid',
					'label' => __( 'Bid values', 'yml-for-yandex-market' ),
					'description' => __( 'Optional element', 'yml-for-yandex-market' ) . ' <strong>bid</strong>. ' . __( 'Bid values in your price list. Specify the bid amount in Yandex cents: for example, the value 80 corresponds to the bid of 0.8 Yandex units. The values must be positive integers', 'yml-for-yandex-market' ) . ' <a target="_blank" href="//yandex.ru/support/partnermarket/elements/bid-cbid.html">' . __( 'Read more on Yandex', 'yml-for-yandex-market' ) . '</a>',
					'type' => 'text'
				] );

				woocommerce_wp_select( [ 
					'id' => 'yfym_individual_vat',
					'label' => __( 'VAT rate', 'yml-for-yandex-market' ),
					'options' => [ 
						'global' => __( 'Use global settings', 'yml-for-yandex-market' ),
						'NO_VAT' => __( 'No VAT', 'yml-for-yandex-market' ) . ' (NO_VAT)',
						'VAT_0' => '0% (VAT_0)',
						'VAT_10' => '10% (VAT_10)',
						'VAT_10_110' => '10/110 (VAT_10_110)',
						'VAT_18' => '18% (VAT_18)',
						'VAT_18_118' => '18/118 (VAT_18_118)',
						'VAT_20' => '20% (VAT_20)',
						'VAT_20_120' => '20/120 VAT_20_120)'
					],
					'description' => __( 'Optional element', 'yml-for-yandex-market' ) . ' <strong>vat</strong> ' . __( 'This element is used when creating an YML feed for Yandex.Delivery', 'yml-for-yandex-market' ) . ' <a target="_blank" href="//yandex.ru/support/delivery/settings/vat.html">' . __( 'Read more on Yandex', 'yml-for-yandex-market' ) . '</a>'
				] );

				// woocommerce_wp_select( [ 
				//	'id' => 'yfym_condition',
				//	'label' => __( 'Сondition', 'yml-for-yandex-market' ),
				//	'options' => [ 
				//		'off' => __( 'None', 'yml-for-yandex-market' ),
				//		'likenew' => __( 'Like New', 'yml-for-yandex-market' ),
				//		'used' => __( 'Used', 'yml-for-yandex-market' ),
				//	],
				//	'description' => __( 'Optional element', 'yml-for-yandex-market' ) . ' <strong>condition</strong>'
				// ] );
		
				woocommerce_wp_select( [ 
					'id' => '_yfym_condition',
					'label' => __( 'Сondition', 'yml-for-yandex-market' ),
					'options' => [ 
						'default' => __( 'Default', 'yml-for-yandex-market' ),
						'disabled' => __( 'Disabled', 'yml-for-yandex-market' ),
						'showcasesample' => __( 'Showcase sample', 'yml-for-yandex-market' ) . ' (showcasesample)',
						'reduction' => __( 'Reduction', 'yml-for-yandex-market' ) . ' (reduction)',
						'fashionpreowned' => __( 'Fashionpreowned', 'yml-for-yandex-market' ) . ' (fashionpreowned)',
						'preowned' => __( 'Fashionpreowned', 'yml-for-yandex-market' ) . ' (preowned)',
						'likenew' => __( 'Like New', 'yml-for-yandex-market' ) . ' (likenew)'
					],
					'description' => __( 'Optional element', 'yml-for-yandex-market' ) . ' <strong>condition</strong>'
				] );

				woocommerce_wp_select( [ 
					'id' => '_yfym_quality',
					'label' => __( 'Quality', 'yml-for-yandex-market' ),
					'options' => [ 
						'default' => __( 'Default', 'yml-for-yandex-market' ),
						'perfect' => __( 'Perfect', 'yml-for-yandex-market' ),
						'excellent' => __( 'Excellent', 'yml-for-yandex-market' ),
						'good' => __( 'Good', 'yml-for-yandex-market' ),
					],
					'description' => __( 'Required element', 'yml-for-yandex-market' ) . ' <strong>quality</strong> ' . __( 'of attribute', 'yml-for-yandex-market' ) . ' <strong>condition</strong>',
					'type' => 'text'
				] );

				woocommerce_wp_text_input( [ 
					'id' => 'yfym_reason',
					'label' => __( 'Reason', 'yml-for-yandex-market' ),
					'placeholder' => '',
					'description' => __( 'Required element', 'yml-for-yandex-market' ) . ' <strong>reason</strong> ' . __( 'of attribute', 'yml-for-yandex-market' ) . ' <strong>condition</strong>',
					'type' => 'text'
				] );
				?>
				<?php do_action( 'yfym_append_options_group_3', $post ); ?>
			</div>
			<div class="options_group">
				<h2>Маркетплейс Яндекс.Маркета</h2>
				<p>
					<?php _e( 'This data is used only when creating a feed for', 'yml-for-yandex-market' ); ?> Маркетплейс
					Яндекс.Маркета
				</p>
				<?php do_action( 'yfym_prepend_options_group_other', $post ); ?>
				<?php
				woocommerce_wp_text_input( [ 
					'id' => '_yfym_market_sku',
					'label' => __( 'Product ID on Yandex', 'yml-for-yandex-market' ),
					'placeholder' => '',
					'description' => __( 'Optional element', 'yml-for-yandex-market' ) . ' <strong>market-sku</strong> (' . __( 'Forbidden in Yandex Market', 'yml-for-yandex-market' ) . ') ' . __( 'Product ID on Yandex. You can get it after downloading the file in your personal account', 'yml-for-yandex-market' ) . '. <a target="_blank" href="//yandex.ru/support/marketplace/catalog/yml-simple.html">' . __( 'Read more on Yandex', 'yml-for-yandex-market' ) . '</a>',
					'type' => 'text'
				] );
				woocommerce_wp_text_input( [ 
					'id' => '_yfym_tn_ved_code',
					'label' => __( 'Code ТН ВЭД', 'yml-for-yandex-market' ),
					'placeholder' => '',
					'description' => __( 'Optional element', 'yml-for-yandex-market' ) . ' <strong>tn-ved-code</strong> (' . __( 'Forbidden in Yandex Market', 'yml-for-yandex-market' ) . ') <a target="_blank" href="//yandex.ru/support/marketplace/catalog/yml-simple.html">' . __( 'Read more on Yandex', 'yml-for-yandex-market' ) . '</a>',
					'type' => 'text'
				] );

				if ( get_post_meta( $post->ID, '_yfym_recommend_stock_data_arr', true ) == '' ) {
					$yfym_recommend_stock_data_arr = [];
				} else {
					$yfym_recommend_stock_data_arr = get_post_meta( $post->ID, '_yfym_recommend_stock_data_arr', true );
				}
				$availability = yfym_data_from_arr( $yfym_recommend_stock_data_arr, 'availability', 'disabled' );
				$transport_unit = yfym_data_from_arr( $yfym_recommend_stock_data_arr, 'transport_unit' );
				$min_delivery_pieces = yfym_data_from_arr( $yfym_recommend_stock_data_arr, 'min_delivery_pieces' );
				$quantum = yfym_data_from_arr( $yfym_recommend_stock_data_arr, 'quantum' );
				$leadtime = yfym_data_from_arr( $yfym_recommend_stock_data_arr, 'leadtime' );
				$box_count = yfym_data_from_arr( $yfym_recommend_stock_data_arr, 'box_count' );
				$delivery_weekday_arr = yfym_data_from_arr( $yfym_recommend_stock_data_arr, 'delivery_weekday_arr', [] );

				woocommerce_wp_select( [ 
					'id' => '_yfym_availability',
					'label' => __( 'Supply plans', 'yml-for-yandex-market' ),
					'value' => $availability,
					'options' => array(
						'disabled' => __( 'Disabled', 'yml-for-yandex-market' ),
						'ACTIVE' => __( 'Supplies will', 'yml-for-yandex-market' ),
						'INACTIVE' => __( 'There will be no supplies', 'yml-for-yandex-market' ),
						'DELISTED' => __( 'Product in the archive', 'yml-for-yandex-market' ),
					),
					'description' => __( 'Optional element', 'yml-for-yandex-market' ) . ' <strong>availability</strong> (' . __( 'Forbidden in Yandex Market', 'yml-for-yandex-market' ) . ') <a target="_blank" href="//yandex.ru/support/marketplace/catalog/yml-simple.html">' . __( 'Read more on Yandex', 'yml-for-yandex-market' ) . '</a>'
				] );
				woocommerce_wp_text_input( [ 
					'id' => '_yfym_transport_unit',
					'label' => __( 'The number of products in the package (multiplicity of the box)', 'yml-for-yandex-market' ),
					'value' => $transport_unit,
					'placeholder' => '',
					'description' => __( 'Optional element', 'yml-for-yandex-market' ) . ' <strong>transport-unit</strong> (' . __( 'Forbidden in Yandex Market', 'yml-for-yandex-market' ) . ') <a target="_blank" href="//yandex.ru/support/marketplace/catalog/yml-simple.html">' . __( 'Read more on Yandex', 'yml-for-yandex-market' ) . '</a>',
					'type' => 'text'
				] );
				woocommerce_wp_text_input( [ 
					'id' => '_yfym_min_delivery_pieces',
					'label' => __( 'Minimum delivery pieces', 'yml-for-yandex-market' ),
					'value' => $min_delivery_pieces,
					'placeholder' => '',
					'description' => __( 'Optional element', 'yml-for-yandex-market' ) . ' <strong>min-delivery-pieces</strong> (' . __( 'Forbidden in Yandex Market', 'yml-for-yandex-market' ) . ') <a target="_blank" href="//yandex.ru/support/marketplace/catalog/yml-simple.html">' . __( 'Read more on Yandex', 'yml-for-yandex-market' ) . '</a>',
					'type' => 'text'
				] );
				woocommerce_wp_text_input( [ 
					'id' => '_yfym_quantum',
					'label' => __( 'Additional batch (quantum of delivery)', 'yml-for-yandex-market' ),
					'value' => $quantum,
					'placeholder' => '',
					'description' => __( 'Optional element', 'yml-for-yandex-market' ) . ' <strong>quantum</strong> (' . __( 'Forbidden in Yandex Market', 'yml-for-yandex-market' ) . ') <a target="_blank" href="//yandex.ru/support/marketplace/catalog/yml-simple.html">' . __( 'Read more on Yandex', 'yml-for-yandex-market' ) . '</a>',
					'type' => 'text'
				] );
				woocommerce_wp_text_input( [ 
					'id' => '_yfym_leadtime',
					'label' => __( 'Lead time', 'yml-for-yandex-market' ),
					'value' => $leadtime,
					'placeholder' => '',
					'description' => __( 'Optional element', 'yml-for-yandex-market' ) . ' <strong>leadtime</strong> (' . __( 'Forbidden in Yandex Market', 'yml-for-yandex-market' ) . ') <a target="_blank" href="//yandex.ru/support/marketplace/catalog/yml-simple.html">' . __( 'Read more on Yandex', 'yml-for-yandex-market' ) . '</a>',
					'type' => 'text'
				] );
				woocommerce_wp_text_input( [ 
					'id' => '_yfym_box_count',
					'label' => __( 'Box count', 'yml-for-yandex-market' ),
					'value' => $box_count,
					'placeholder' => '',
					'description' => __( 'Optional element', 'yml-for-yandex-market' ) . ' <strong>box-count</strong> (' . __( 'Forbidden in Yandex Market', 'yml-for-yandex-market' ) . ') <a target="_blank" href="//yandex.ru/support/marketplace/catalog/yml-simple.html">' . __( 'Read more on Yandex', 'yml-for-yandex-market' ) . '</a>',
					'type' => 'text'
				] );
				yfym_woocommerce_wp_select_multiple( [ 
					'id' => '_delivery_weekday_arr',
					//	'wrapper_class' => 'show_if_simple', 
					'label' => __( 'Days of the week when you are ready to deliver the goods to the warehouse of the marketplace', 'yml-for-yandex-market' ),
					'value' => $delivery_weekday_arr,
					'options' => [ 
						'MONDAY' => __( 'Monday', 'yml-for-yandex-market' ),
						'TUESDAY' => __( 'Tuesday', 'yml-for-yandex-market' ),
						'WEDNESDAY' => __( 'Wednesday', 'yml-for-yandex-market' ),
						'THURSDAYy' => __( 'Thursday', 'yml-for-yandex-market' ),
						'FRIDAY' => __( 'Friday', 'yml-for-yandex-market' ),
						'SATURDAY' => __( 'Saturday', 'yml-for-yandex-market' ),
						'SUNDAY' => __( 'Sunday', 'yml-for-yandex-market' )
					]
				] );
				?>
				<?php do_action( 'yfym_append_options_group_4', $post ); ?>
			</div>
			<div class="options_group">
				<h2>
					<?php _e( 'Other', 'yml-for-yandex-market' ); ?>
				</h2>
				<?php do_action( 'yfym_prepend_options_group_other', $post ); ?>
				<?php
				woocommerce_wp_text_input( [ 
					'id' => 'yfym_credit_template',
					'label' => __( 'Credit program identifier', 'yml-for-yandex-market' ),
					'placeholder' => '',
					'description' => __( 'Optional element', 'yml-for-yandex-market' ) . ' <strong>credit-template</strong> <a target="_blank" href="//yandex.ru/support/partnermarket/efficiency/credit.html">' . __( 'Read more on Yandex', 'yml-for-yandex-market' ) . '</a>',
					'type' => 'text'
				] );
				woocommerce_wp_select( [ 
					'id' => '_yfym_cargo_types',
					'label' => 'Cargo types',
					'options' => [ 
						'default' => __( 'Default', 'yml-for-yandex-market' ),
						'disabled' => __( 'Disabled', 'yml-for-yandex-market' ),
						'yes' => 'CIS_REQUIRED'
					],
					'description' => __( 'Optional element', 'yml-for-yandex-market' ) . ' <strong>cargo-types</strong> <a target="_blank" href="//yandex.ru/support/partnermarket-dsbs/orders/cis.html">' . __( 'Read more on Yandex', 'yml-for-yandex-market' ) . '</a>',
				] );
				woocommerce_wp_text_input( [ 
					'id' => '_yfym_supplier',
					'label' => 'ОГРН/ОГРНИП ' . __( 'of a third-party seller', 'yml-for-yandex-market' ),
					'description' => __( 'Optional element', 'yml-for-yandex-market' ) . ' <strong>supplier</strong>. <a target="_blank" href="//yandex.ru/support/partnermarket/registration/marketplace.html">' . __( 'Read more on Yandex', 'yml-for-yandex-market' ) . '</a>',
					'type' => 'text'
				] );
				woocommerce_wp_text_input( [ 
					'id' => '_yfym_min_quantity',
					'label' => __( 'Minimum number of products per order', 'yml-for-yandex-market' ),
					'description' => __( 'For these categories only', 'yml-for-yandex-market' ) . ': "Автошины", "Грузовые шины", "Мотошины", "Диски" <strong>min-quantity</strong>. <a target="_blank" href="//yandex.ru/support/partnermarket/elements/min-quantity.html">' . __( 'Read more on Yandex', 'yml-for-yandex-market' ) . '</a>',
					'type' => 'text'
				] );
				woocommerce_wp_text_input( [ 
					'id' => '_yfym_step_quantity',
					'label' => 'step-quantity',
					'description' => __( 'For these categories only', 'yml-for-yandex-market' ) . ': "Автошины", "Грузовые шины", "Мотошины", "Диски" <strong>step-quantity</strong>',
					'type' => 'text'
				] );

				woocommerce_wp_text_input( [ 
					'id' => '_yfym_video_url',
					'label' => 'video',
					'description' => __( 'Video URL', 'yml-for-yandex-market' ) . ': <strong>video</strong>',
					'type' => 'text'
				] );
				?>
				<?php do_action( 'yfym_append_options_group_5', $post ); ?>
			</div>
			<?php do_action( 'yfym_append_options_panel', $post ); ?>
		</div>
		<?php
	}

	/**
	 * Флаг для того, чтобы работало сохранение настроек если мультиселект пуст
	 * 
	 * @param string $save_if_empty
	 * @param array $args_arr
	 * 
	 * @return string
	 */
	public function flag_save_if_empty( $save_if_empty, $args_arr ) {
		if ( ! empty( $_GET ) && isset( $_GET['tab'] ) && $_GET['tab'] === 'tags_settings_tab' ) {
			if ( $args_arr['opt_name'] === 'yfym_params_arr' ) {
				$save_if_empty = 'empty_arr';
			}
		}
		if ( ! empty( $_GET ) && isset( $_GET['tab'] ) && $_GET['tab'] === 'filtration_tab' ) {
			if ( $args_arr['opt_name'] === 'yfym_no_group_id_arr'
				|| $args_arr['opt_name'] === 'yfym_add_in_name_arr'
			) {
				$save_if_empty = 'empty_arr';
			}
		}
		return $save_if_empty;
	}
} // end class Y4YM_Interface_Hoocked