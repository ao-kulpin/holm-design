<?php
/**
 * Set and Get the Plugin Data
 *
 * @package                 iCopyDoc Plugins (v1, core 16-08-2023)
 * @subpackage              YML for Yandex Market
 * @since                   0.1.0
 * 
 * @version                 4.0.5 (20-09-2023)
 * @author                  Maxim Glazunov
 * @link                    https://icopydoc.ru/
 * @see                     
 * 
 * @param       array       $data_arr - Optional
 *
 * @depends                 classes:    
 *                          traits:     
 *                          methods:    
 *                          functions:  
 *                          constants:  
 *                          options:    
 */
defined( 'ABSPATH' ) || exit;

class Y4YM_Data_Arr {
	/**
	 * The plugin data array
	 *
	 * @var array
	 */
	private $data_arr = [];

	/**
	 * Summary of __construct
	 * 
	 * @param array $data_arr - Optional
	 */
	public function __construct( $data_arr = [] ) {
		if ( empty( $data_arr ) ) {
			$this->data_arr = [ 
				[ 0 => 'yfym_status_sborki', 1 => '-1', 2 => 'private', // TODO: Удалить потом эту строку
					'opt_name' => 'yfym_status_sborki',
					'def_val' => '-1',
					'mark' => 'private',
					'required' => true,
					'type' => 'auto',
					'tab' => 'none'
				],
				[ 0 => 'yfym_date_sborki', 1 => '0000000001', 2 => 'private', // TODO: Удалить потом эту строку // дата начала сборки
					'opt_name' => 'yfym_date_sborki',
					'def_val' => '0000000001',
					'mark' => 'private',
					'required' => true,
					'type' => 'auto',
					'tab' => 'none'
				],
				[ 0 => 'yfym_date_sborki_end', 1 => '0000000001', 2 => 'private', // TODO: Удалить потом эту строку // дата завершения сборки
					'opt_name' => 'yfym_date_sborki_end',
					'def_val' => '0000000001',
					'mark' => 'private',
					'required' => true,
					'type' => 'auto',
					'tab' => 'none'
				],
				[ 0 => 'yfym_date_save_set', 1 => '0000000001', 2 => 'private', // TODO: Удалить потом эту строку // дата сохранения настроек плагина
					'opt_name' => 'yfym_date_save_set',
					'def_val' => '0000000001',
					'mark' => 'private',
					'required' => true,
					'type' => 'auto',
					'tab' => 'none'
				],
				[ 0 => 'yfym_count_products_in_feed', 1 => '-1', 2 => 'private', // TODO: Удалить потом эту строку // число товаров, попавших в выгрузку
					'opt_name' => 'yfym_count_products_in_feed',
					'def_val' => '-1',
					'mark' => 'private',
					'required' => true,
					'type' => 'auto',
					'tab' => 'none'
				],
				[ 0 => 'yfym_file_url', 1 => '', 2 => 'private', // TODO: Удалить потом эту строку 
					'opt_name' => 'yfym_file_url',
					'def_val' => '',
					'mark' => 'private',
					'required' => true,
					'type' => 'auto',
					'tab' => 'none'
				],
				[ 0 => 'yfym_file_file', 1 => '', 2 => 'private', // TODO: Удалить потом эту строку 
					'opt_name' => 'yfym_file_file',
					'def_val' => '',
					'mark' => 'private',
					'required' => true,
					'type' => 'auto',
					'tab' => 'none'
				],
				[ 0 => 'yfym_errors', 1 => '', 2 => 'private', // TODO: Удалить потом эту строку
					'opt_name' => 'yfym_errors',
					'def_val' => '',
					'mark' => 'private',
					'required' => true,
					'type' => 'auto',
					'tab' => 'none'
				],
				[ 0 => 'yfym_status_cron', 1 => 'off', 2 => 'private', // TODO: Удалить потом эту строку
					'opt_name' => 'yfym_status_cron',
					'def_val' => 'off',
					'mark' => 'private',
					'required' => true,
					'type' => 'auto',
					'tab' => 'none'
				],
				// ------------------- ОСНОВНЫЕ НАСТРОЙКИ -------------------
				[ 0 => 'yfym_run_cron', 1 => 'disabled', 2 => 'public', // TODO: Удалить потом эту строку
					'opt_name' => 'yfym_run_cron',
					'def_val' => 'disabled',
					'mark' => 'public',
					'required' => true,
					'type' => 'select',
					'tab' => 'main_tab',
					'data' => [ 
						'label' => __( 'Automatic file creation', 'yml-for-yandex-market' ),
						'desc' => __( 'The refresh interval on your feed', 'yml-for-yandex-market' ),
						'woo_attr' => false,
						'key_value_arr' => [ 
							[ 'value' => 'disabled', 'text' => __( 'Disabled', 'yml-for-yandex-market' ) ],
							[ 'value' => 'hourly', 'text' => __( 'Hourly', 'yml-for-yandex-market' ) ],
							[ 'value' => 'six_hours', 'text' => __( 'Every six hours', 'yml-for-yandex-market' ) ],
							[ 'value' => 'twicedaily', 'text' => __( 'Twice a day', 'yml-for-yandex-market' ) ],
							[ 'value' => 'daily', 'text' => __( 'Daily', 'yml-for-yandex-market' ) ],
							[ 'value' => 'week', 'text' => __( 'Once a week', 'yml-for-yandex-market' ) ]
						]
					]
				],
				[ 0 => 'yfym_ufup', 1 => '0', 2 => 'public', // TODO: Удалить потом эту строку
					'opt_name' => 'yfym_ufup',
					'def_val' => 'disabled',
					'mark' => 'public',
					'required' => true,
					'type' => 'select',
					'tab' => 'main_tab',
					'data' => [ 
						'label' => __( 'Update feed when updating products', 'yml-for-yandex-market' ),
						'desc' => '',
						'woo_attr' => false,
						'key_value_arr' => [ 
							[ 'value' => 'disabled', 'text' => __( 'Disabled', 'yml-for-yandex-market' ) ],
							[ 'value' => 'on', 'text' => __( 'Enabled', 'yml-for-yandex-market' ) ]
						]
					]
				],
				[ 0 => 'yfym_feed_assignment', 1 => '', 2 => 'public', // TODO: Удалить потом эту строку
					'opt_name' => 'yfym_feed_assignment',
					'def_val' => '',
					'mark' => 'public',
					'required' => true,
					'type' => 'text',
					'tab' => 'main_tab',
					'data' => [ 
						'label' => __( 'Feed assignment', 'yml-for-yandex-market' ),
						'desc' => __( 'Not used in feed. Inner note for your convenience', 'yml-for-yandex-market' ),
						'placeholder' => __( 'For Yandex Market', 'yml-for-yandex-market' ),
						'tr_class' => 'y4ym_tr'
					]
				],
				[ 0 => 'yfym_file_extension', 1 => 'xml', 2 => 'public', // TODO: Удалить потом эту строку
					'opt_name' => 'yfym_file_extension',
					'def_val' => 'xml',
					'mark' => 'public',
					'required' => true,
					'type' => 'select',
					'tab' => 'main_tab',
					'data' => [ 
						'label' => __( 'Feed file extension', 'yml-for-yandex-market' ),
						'desc' => '',
						'woo_attr' => false,
						'key_value_arr' => [ 
							[ 'value' => 'xml', 'text' => 'XML (' . __( 'recommend', 'yml-for-yandex-market' ) . ')' ],
							[ 'value' => 'yml', 'text' => 'YML' ]
						]
					]
				],
				[ 0 => 'yfym_feed_name', 1 => '', 2 => 'public', // TODO: Удалить потом эту строку
					'opt_name' => 'yfym_feed_name',
					'def_val' => '',
					'mark' => 'public',
					'required' => true,
					'type' => 'text',
					'tab' => 'main_tab',
					'data' => [ 
						'label' => __( 'Name of the feed file', 'yml-for-yandex-market' ),
						'desc' => __(
							'If you leave the field empty, the default value will be used',
							'yml-for-yandex-market'
						),
						'placeholder' => 'feed-yml-0',
						'tr_class' => ''
					]
				],
				[ 0 => 'yfym_archive_to_zip', 1 => 'disabled', 2 => 'public', // TODO: Удалить потом эту строку
					'opt_name' => 'yfym_archive_to_zip',
					'def_val' => 'disabled',
					'mark' => 'public',
					'required' => true,
					'type' => 'select',
					'tab' => 'main_tab',
					'data' => [ 
						'label' => __( 'Archive to ZIP', 'yml-for-yandex-market' ),
						'desc' => sprintf( '%s: %s',
							__( 'Default', 'yml-for-yandex-market' ),
							__( 'Disabled', 'yml-for-yandex-market' )
						),
						'woo_attr' => false,
						'key_value_arr' => [ 
							[ 'value' => 'disabled', 'text' => __( 'Disabled', 'yml-for-yandex-market' ) ],
							[ 'value' => 'enabled', 'text' => __( 'Enabled', 'yml-for-yandex-market' ) ]
						]
					]
				],
				[ 0 => 'yfym_format_date', 1 => 'rfc_short', 2 => 'public', // TODO: Удалить потом эту строку
					'opt_name' => 'yfym_format_date',
					'def_val' => 'rfc_short',
					'mark' => 'public',
					'required' => true,
					'type' => 'select',
					'tab' => 'main_tab',
					'data' => [ 
						'label' => __( 'Format date', 'yml-for-yandex-market' ),
						'desc' => sprintf( '%s: %s',
							__( 'Default', 'yml-for-yandex-market' ),
							'RFC 3339 short'
						),
						'woo_attr' => false,
						'key_value_arr' => [ 
							[ 
								'value' => 'rfc_short',
								'text' => sprintf( '%s (%s)',
									'RFC 3339 short (2022-03-21T17:47)',
									__( 'recommend', 'yml-for-yandex-market' )
								)
							],
							[ 'value' => 'rfc', 'text' => 'RFC 3339 full (2022-03-21T17:47:19+03:00)' ],
							[ 'value' => 'unixtime', 'text' => 'Unix time (2022-03-21 17:47)' ]
						],
						'tr_class' => 'y4ym_tr'
					]
				],
				[ 0 => 'yfym_yml_rules', 1 => 'yandex_market', 2 => 'public', // TODO: Удалить потом эту строку
					'opt_name' => 'yfym_yml_rules',
					'def_val' => 'yandex_market',
					'mark' => 'public',
					'required' => true,
					'type' => 'select',
					'tab' => 'main_tab',
					'data' => [ 
						'label' => __( 'To follow the rules', 'yml-for-yandex-market' ),
						'desc' => sprintf( '%s <i>(%s)</i>. %s. %s',
							__( 'Exclude products that do not meet the requirements', 'yml-for-yandex-market' ),
							__( 'missing required elements/data', 'yml-for-yandex-market' ),
							__(
								'The plugin will try to automatically remove products from the YML-feed for which the required fields for the feed are not filled',
								'yml-for-yandex-market'
							),
							__( 'Also, this item affects the structure of the file', 'yml-for-yandex-market' )
						),
						'woo_attr' => false,
						'key_value_arr' => [ 
							[ 
								'value' => 'yandex_market',
								'text' => sprintf( '%s ADV (%s)',
									__( 'Yandex Market', 'yml-for-yandex-market' ),
									__( 'Simplified type', 'yml-for-yandex-market' )
								)
							],
							[ 
								'value' => 'yandex_direct',
								'text' => sprintf( '%s (%s)',
									__( 'Yandex Direct', 'yml-for-yandex-market' ),
									__( 'Simplified type', 'yml-for-yandex-market' )
								)
							],
							[ 
								'value' => 'dbs',
								'text' => sprintf( '%s DBS (%s)',
									__( 'Yandex Market', 'yml-for-yandex-market' ),
									__( 'Simplified type', 'yml-for-yandex-market' )
								)
							],
							[ 
								'value' => 'single_catalog',
								'text' => sprintf( 'FBY, FBY+, FBS (%s)',
									__( 'in a single catalog', 'yml-for-yandex-market' )
								)
							],
							[ 
								'value' => 'sales_terms',
								'text' => sprintf( '%s (%s)',
									__( 'To manage the placement', 'yml-for-yandex-market' ),
									__( 'Yandex Market', 'yml-for-yandex-market' )
								)
							],
							[ 'value' => 'sbermegamarket', 'text' => __( 'SberMegaMarket', 'yml-for-yandex-market' ) ],
							[ 'value' => 'beru', 'text' => __( 'Former Beru', 'yml-for-yandex-market' ) ],
							[ 
								'value' => 'products_and_offers',
								'text' => sprintf( '%s (%s)',
									__( 'Yandex Webmaster', 'yml-for-yandex-market' ),
									__( 'Products and offers', 'yml-for-yandex-market' )
								)
							],
							[ 
								'value' => 'yandex_webmaster',
								'text' => sprintf( '%s (turbo) (%s)',
									__( 'Yandex Webmaster', 'yml-for-yandex-market' ),
									__( 'abolished by Yandex', 'yml-for-yandex-market' )
								)
							],
							[ 
								'value' => 'all_elements',
								'text' => sprintf( '%s (%s)',
									__( 'No rules', 'yml-for-yandex-market' ),
									__( 'Not recommended', 'yml-for-yandex-market' )
								)
							],
							[ 'value' => 'ozon', 'text' => 'OZON' ],
							[ 'value' => 'vk', 'text' => 'ВКонтакте (vk.com)' ],
						],
						'tr_class' => 'y4ym_tr'
					]
				],
				[ 0 => 'yfym_step_export', 1 => '500', 2 => 'public', // TODO: Удалить потом эту строку
					'opt_name' => 'yfym_step_export',
					'def_val' => '500',
					'mark' => 'public',
					'required' => true,
					'type' => 'select',
					'tab' => 'main_tab',
					'data' => [ 
						'label' => __( 'Step export', 'yml-for-yandex-market' ),
						'desc' =>
							sprintf( '%s. %s. %s',
								__( 'The value affects the speed of file creation', 'yml-for-yandex-market' ),
								__(
									'If you have any problems with the generation of the file - try to reduce the value in this field',
									'yml-for-yandex-market'
								),
								__( 'More than 500 can only be installed on powerful servers', 'yml-for-yandex-market' )
							),
						'woo_attr' => false,
						'key_value_arr' => [ 
							[ 'value' => '80', 'text' => '80' ],
							[ 'value' => '200', 'text' => '200' ],
							[ 'value' => '300', 'text' => '300' ],
							[ 'value' => '450', 'text' => '450' ],
							[ 'value' => '500', 'text' => '500' ],
							[ 'value' => '800', 'text' => '800' ],
							[ 'value' => '1000', 'text' => '1000' ]
						],
						'tr_class' => 'y4ym_tr'
					]
				],
				[ 0 => 'yfym_cache', 1 => 'disabled', 2 => 'public', // TODO: Удалить потом эту строку
					'opt_name' => 'yfym_cache',
					'def_val' => 'disabled',
					'mark' => 'public',
					'required' => true,
					'type' => 'select',
					'tab' => 'main_tab',
					'data' => [ 
						'label' => __( 'Ignore plugin cache', 'yml-for-yandex-market' ),
						'desc' => sprintf( '%s: <a 
						href="https://icopydoc.ru/pochemu-ne-obnovilis-tseny-v-fide-para-slov-o-tihih-pravkah/%s">%s</a>',
							__(
								"Changing this option can be useful if your feed prices don't change after syncing",
								'yml-for-yandex-market'
							),
							'?utm_source=yml-for-yandex-market&utm_medium=organic&utm_campaign=in-plugin-yml-for-yandex-market&utm_content=settings&utm_term=about-cache',
							__( 'Learn More', 'yml-for-yandex-market' )
						),
						'woo_attr' => false,
						'key_value_arr' => [ 
							[ 'value' => 'disabled', 'text' => __( 'Disabled', 'yml-for-yandex-market' ) ],
							[ 'value' => 'enabled', 'text' => __( 'Enabled', 'yml-for-yandex-market' ) ]
						],
						'tr_class' => 'y4ym_tr'
					]
				],
				[ 0 => 'yfym_do_cash_file', 1 => 'disabled', 2 => 'public', // TODO: Удалить потом эту строку
					'opt_name' => 'yfym_do_cash_file',
					'def_val' => 'disabled',
					'mark' => 'public',
					'required' => true,
					'type' => 'select',
					'tab' => 'main_tab',
					'data' => [ 
						'label' => __(
							'Disable the creation of cache files when saving products',
							'yml-for-yandex-market'
						),
						'desc' => sprintf( '%s. %s',
							__(
								'This option allows you to reduce the load on the site at the time of saving the product card',
								'yml-for-yandex-market'
							),
							__(
								'However, disabling this option leads to a significant increase in the feed creation time',
								'yml-for-yandex-market'
							)
						),
						'woo_attr' => false,
						'key_value_arr' => [ 
							[ 'value' => 'disabled', 'text' => __( 'Disabled', 'yml-for-yandex-market' ) ],
							[ 'value' => 'enabled', 'text' => __( 'Enabled', 'yml-for-yandex-market' ) ]
						],
						'tr_class' => ''
					]
				],
				[ //  ? удалить целиком
					0 => 'yfym_file_ids_in_yml', 1 => '', 2 => 'private', // TODO: Удалить потом эту строку
					'opt_name' => 'yfym_file_ids_in_yml',
					'def_val' => '',
					'mark' => 'private',
					'required' => false,
					'type' => 'text',
					'tab' => 'none',
					'data' => []
				],
				[ //  ? удалить целиком
					0 => 'yfym_file_ids_in_xml', 1 => '', 2 => 'private', // TODO: Удалить потом эту строку
					'opt_name' => 'yfym_file_ids_in_xml',
					'def_val' => '',
					'mark' => 'private',
					'required' => false,
					'type' => 'text',
					'tab' => 'none',
					'data' => []
				],
				[ //  ? удалить целиком
					0 => 'yfym_main_product', 1 => 'other', 2 => 'private', // TODO: Удалить потом эту строку
					'opt_name' => 'yfym_main_product',
					'def_val' => '',
					'mark' => 'private',
					'required' => false,
					'type' => 'text',
					'tab' => 'none',
					'data' => []
				],
				// ------------------- ДАННЫЕ МАГАЗИНА -------------------
				[ 0 => 'yfym_shop_name', 1 => 'Название магазина', 2 => 'public', // TODO: Удалить потом эту строку
					'opt_name' => 'yfym_shop_name',
					'def_val' => '',
					'mark' => 'public',
					'required' => true,
					'type' => 'text',
					'tab' => 'shop_data_tab',
					'data' => [ 
						'label' => __( 'Shop name', 'yml-for-yandex-market' ),
						'desc' => __(
							'The short name of the store should not exceed 20 characters',
							'yml-for-yandex-market'
						),
						'placeholder' => __( 'For Yandex Market', 'yml-for-yandex-market' ),
						'rules' => [ 
							'yandex_market', 'dbs', 'single_catalog', 'sales_terms', 'sbermegamarket', 'beru',
							'products_and_offers', 'yandex_webmaster', 'all_elements', 'ozon', 'vk'
						],
						'tag_name' => 'name'
					]
				],
				[ 0 => 'yfym_company_name', 1 => 'Наименование юрлица', 'public', // TODO: Удалить потом эту строку
					'opt_name' => 'yfym_company_name',
					'def_val' => '',
					'mark' => 'public',
					'required' => true,
					'type' => 'text',
					'tab' => 'shop_data_tab',
					'data' => [ 
						'label' => __( 'Company name', 'yml-for-yandex-market' ),
						'desc' => __( 'Full name of the company that owns the store', 'yml-for-yandex-market' ),
						'placeholder' => __( 'For Yandex Market', 'yml-for-yandex-market' ),
						'rules' => [ 
							'yandex_market', 'dbs', 'single_catalog', 'sales_terms', 'sbermegamarket', 'beru',
							'products_and_offers', 'yandex_webmaster', 'all_elements', 'ozon', 'vk'
						],
						'tag_name' => 'company'
					]
				],
				[ 0 => 'yfym_warehouse', 1 => 'Основной склад', 2 => 'public', // TODO: Удалить потом эту строку
					'opt_name' => 'yfym_warehouse',
					'def_val' => '',
					'mark' => 'public',
					'required' => true,
					'type' => 'text',
					'tab' => 'shop_data_tab',
					'data' => [ 
						'label' => __( 'Warehouse', 'yml-for-yandex-market' ) . ' Name/ID',
						'desc' => sprintf( '%s (OZON) %s (%s)',
							__( 'Warehouse name', 'yml-for-yandex-market' ),
							__( 'or ID', 'yml-for-yandex-market' ),
							__( 'SberMegaMarket', 'yml-for-yandex-market' )
						),
						'placeholder' => __( 'For Yandex Market', 'yml-for-yandex-market' )
					]
				],
				[ 0 => 'yfym_currencies', 1 => 'enabled', 2 => 'public', // TODO: Удалить потом эту строку
					'opt_name' => 'yfym_currencies',
					'def_val' => 'enabled',
					'mark' => 'public',
					'required' => true,
					'type' => 'select',
					'tab' => 'shop_data_tab',
					'data' => [ 
						'label' => __( 'Element "currencies"', 'yml-for-yandex-market' ),
						'desc' => '',
						'woo_attr' => false,
						'key_value_arr' => [ 
							[ 'value' => 'enabled', 'text' => __( 'Enabled', 'yml-for-yandex-market' ) ],
							[ 'value' => 'disabled', 'text' => __( 'Disabled', 'yml-for-yandex-market' ) ]
						],
						'rules' => [ 
							'yandex_market', 'dbs', 'single_catalog', 'sales_terms', 'sbermegamarket', 'beru',
							'products_and_offers', 'yandex_webmaster', 'all_elements', 'ozon', 'vk'
						],
						'tag_name' => 'currencies'
					]
				],
				[ 0 => 'yfym_adult', 1 => 'no', 2 => 'public', // TODO: Удалить потом эту строку
					'opt_name' => 'yfym_adult',
					'def_val' => 'no',
					'mark' => 'public',
					'required' => true,
					'type' => 'select',
					'tab' => 'shop_data_tab',
					'data' => [ 
						'label' => __( 'Adult Market', 'yml-for-yandex-market' ),
						'desc' => '',
						'woo_attr' => false,
						'key_value_arr' => [ 
							[ 'value' => 'no', 'text' => __( 'No', 'yml-for-yandex-market' ) ],
							[ 'value' => 'yes', 'text' => __( 'Yes', 'yml-for-yandex-market' ) ]
						],
						'rules' => [ 
							'yandex_market', 'dbs', 'single_catalog', 'sales_terms', 'sbermegamarket', 'beru',
							'products_and_offers', 'yandex_webmaster', 'all_elements', 'ozon', 'vk'
						],
						'tag_name' => 'adult'
					]
				],
				// ------------------- НАСТРОЙКИ АТРИБУТОВ -------------------
				[ 0 => 'yfym_amount', 1 => 'disabled', 2 => 'public', // TODO: Удалить потом эту строку
					'opt_name' => 'yfym_amount',
					'def_val' => 'disabled',
					'mark' => 'public',
					'required' => false,
					'type' => 'select',
					'tab' => 'wp_list_table',
					'data' => [ 
						'label' => __( 'Quantity of products', 'yml-for-yandex-market' ) . ' (СДЭК)',
						'desc' => __(
							'To make it work you must enable "Manage stock" and indicate "Stock quantity"',
							'yml-for-yandex-market'
						),
						'woo_attr' => false,
						'default_value' => false,
						'key_value_arr' => [ 
							[ 'value' => 'disabled', 'text' => __( 'Disabled', 'yml-for-yandex-market' ) ],
							[ 'value' => 'enabled', 'text' => __( 'Enabled', 'yml-for-yandex-market' ) ]
						],
						'rules' => [ 
							'yandex_market', 'dbs', 'single_catalog', 'sales_terms', 'sbermegamarket', 'beru',
							'products_and_offers', 'yandex_webmaster', 'all_elements', 'ozon', 'vk'
						],
						'tag_name' => 'amount'
					]
				],
				[ 0 => 'yfym_shop_sku', 1 => 'disabled', 2 => 'public', // TODO: Удалить потом эту строку
					'opt_name' => 'yfym_shop_sku',
					'def_val' => 'disabled',
					'mark' => 'public',
					'required' => false,
					'type' => 'select',
					'tab' => 'wp_list_table',
					'data' => [ 
						'label' => 'Shop sku',
						'desc' => 'Shop sku',
						'woo_attr' => true,
						'default_value' => false,
						'key_value_arr' => [ 
							[ 'value' => 'disabled', 'text' => __( 'Disabled', 'yml-for-yandex-market' ) ],
							[ 'value' => 'products_id', 'text' => __( 'Add from products ID', 'yml-for-yandex-market' ) ],
							[ 'value' => 'sku', 'text' => __( 'Substitute from SKU', 'yml-for-yandex-market' ) ]
						],
						'rules' => [ 
							'yandex_market', 'dbs', 'single_catalog', 'sales_terms', 'sbermegamarket', 'beru',
							'products_and_offers', 'yandex_webmaster', 'all_elements', 'ozon', 'vk'
						],
						'tag_name' => 'shop-sku'
					]
				],
				[ 0 => 'yfym_count', 1 => 'disabled', 2 => 'public', // TODO: Удалить потом эту строку
					'opt_name' => 'yfym_count',
					'def_val' => 'disabled',
					'mark' => 'public',
					'required' => false,
					'type' => 'select',
					'tab' => 'wp_list_table',
					'data' => [ 
						'label' => __( 'Quantity of products', 'yml-for-yandex-market' ),
						'desc' => __(
							'To make it work you must enable "Manage stock" and indicate "Stock quantity"',
							'yml-for-yandex-market'
						),
						'woo_attr' => false,
						'default_value' => false,
						'key_value_arr' => [ 
							[ 'value' => 'disabled', 'text' => __( 'Disabled', 'yml-for-yandex-market' ) ],
							[ 'value' => 'enabled', 'text' => __( 'Enabled', 'yml-for-yandex-market' ) ]
						],
						'rules' => [ 
							'yandex_market', 'dbs', 'single_catalog', 'sales_terms', 'sbermegamarket', 'beru',
							'products_and_offers', 'yandex_webmaster', 'all_elements', 'ozon', 'vk'
						],
						'tag_name' => 'count'
					]
				],
				[ 0 => 'yfym_auto_disabled', 1 => 'disabled', 2 => 'public', // TODO: Удалить потом эту строку
					'opt_name' => 'yfym_auto_disabled',
					'def_val' => 'disabled',
					'mark' => 'public',
					'required' => false,
					'type' => 'select',
					'tab' => 'wp_list_table',
					'data' => [ 
						'label' => __( 'Automatically remove products from sale', 'yml-for-yandex-market' ),
						'desc' => __( 'Automatically remove products from sale', 'yml-for-yandex-market' ),
						'woo_attr' => false,
						'default_value' => false,
						'key_value_arr' => [ 
							[ 'value' => 'disabled', 'text' => __( 'Disabled', 'yml-for-yandex-market' ) ],
							[ 'value' => 'yes', 'text' => __( 'Yes', 'yml-for-yandex-market' ) ]
						],
						'rules' => [ 
							'yandex_market', 'dbs', 'single_catalog', 'sales_terms', 'sbermegamarket', 'beru',
							'products_and_offers', 'yandex_webmaster', 'all_elements', 'ozon', 'vk'
						],
						'tag_name' => 'disabled'
					]
				],
				[ 0 => 'yfym_market_sku_status', 1 => 'disabled', 2 => 'public', // TODO: Удалить потом эту строку
					'opt_name' => 'yfym_market_sku_status',
					'def_val' => 'disabled',
					'mark' => 'public',
					'required' => false,
					'type' => 'select',
					'tab' => 'wp_list_table',
					'data' => [ 
						'label' => __( 'Add market-sku to feed', 'yml-for-yandex-market' ),
						'desc' => __(
							'Optional when creating a catalog. A must for price recommendations',
							'yml-for-yandex-market'
						),
						'woo_attr' => false,
						'default_value' => false,
						'key_value_arr' => [ 
							[ 'value' => 'disabled', 'text' => __( 'Disabled', 'yml-for-yandex-market' ) ],
							[ 'value' => 'enabled', 'text' => __( 'Enabled', 'yml-for-yandex-market' ) ]
						],
						'rules' => [ 
							'yandex_market', 'dbs', 'single_catalog', 'sales_terms', 'sbermegamarket', 'beru',
							'products_and_offers', 'yandex_webmaster', 'all_elements', 'ozon', 'vk'
						],
						'tag_name' => 'market-sku'
					]
				],
				[ 0 => 'yfym_manufacturer', 1 => 'disabled', 2 => 'public', // TODO: Удалить потом эту строку
					'opt_name' => 'yfym_manufacturer',
					'def_val' => 'disabled',
					'mark' => 'public',
					'required' => false,
					'type' => 'select',
					'tab' => 'wp_list_table',
					'data' => [ 
						'label' => __( 'Manufacturer company', 'yml-for-yandex-market' ),
						'desc' => __( 'Manufacturer company', 'yml-for-yandex-market' ),
						'woo_attr' => true,
						'default_value' => false,
						'key_value_arr' => [ 
							[ 'value' => 'disabled', 'text' => __( 'Disabled', 'yml-for-yandex-market' ) ]
						],
						'rules' => [ 
							'yandex_market', 'dbs', 'single_catalog', 'sales_terms', 'sbermegamarket', 'beru',
							'products_and_offers', 'yandex_webmaster', 'all_elements', 'ozon', 'vk'
						],
						'tag_name' => 'manufacturer'
					]
				],
				[ 0 => 'yfym_vendor', 1 => 'disabled', 2 => 'public', // TODO: Удалить потом эту строку
					'opt_name' => 'yfym_vendor',
					'def_val' => 'disabled',
					'mark' => 'public',
					'required' => false,
					'type' => 'select',
					'tab' => 'wp_list_table',
					'data' => [ 
						'label' => __( 'Vendor', 'yml-for-yandex-market' ),
						'desc' => __( 'Vendor', 'yml-for-yandex-market' ),
						'woo_attr' => true,
						'default_value' => true,
						'brands' => true,
						'key_value_arr' => [ 
							[ 'value' => 'disabled', 'text' => __( 'Disabled', 'yml-for-yandex-market' ) ],
							[ 'value' => 'post_meta', 'text' => __( 'Substitute from post meta', 'yml-for-yandex-market' ) ],
							[ 
								'value' => 'default_value',
								'text' => sprintf( '%s "%s"',
									__( 'Default value from field', 'yml-for-yandex-market' ),
									__( 'Default value', 'yml-for-yandex-market' )
								)
							]
						],
						'rules' => [ 
							'yandex_market', 'dbs', 'single_catalog', 'sales_terms', 'sbermegamarket', 'beru',
							'products_and_offers', 'yandex_webmaster', 'all_elements', 'ozon', 'vk'
						],
						'tag_name' => 'vendor'
					]
				],
				[ 0 => 'yfym_vendor_post_meta', 1 => '', 2 => 'public', // TODO: Удалить потом эту строку
					'opt_name' => 'yfym_vendor_post_meta',
					'def_val' => '',
					'mark' => 'public',
					'required' => true,
					'type' => 'text',
					'tab' => 'wp_list_table',
					'data' => [ 
						'label' => '',
						'desc' => '',
						'placeholder' => sprintf( '%s / %s',
							__( 'Value', 'yml-for-yandex-market' ),
							__( 'Name post_meta', 'yml-for-yandex-market' )
						)
					]
				],
				[ 0 => 'yfym_country_of_origin', 1 => 'off', 2 => 'public', // TODO: Удалить потом эту строку
					'opt_name' => 'yfym_country_of_origin',
					'def_val' => 'disabled',
					'mark' => 'public',
					'required' => false,
					'type' => 'select',
					'tab' => 'wp_list_table',
					'data' => [ 
						'label' => __( 'Country of origin', 'yml-for-yandex-market' ),
						'desc' => __(
							'This element indicates the country where the product was manufactured',
							'yml-for-yandex-market'
						),
						'woo_attr' => true,
						'default_value' => false,
						'key_value_arr' => [ 
							[ 'value' => 'disabled', 'text' => __( 'Disabled', 'yml-for-yandex-market' ) ]
						],
						'rules' => [ 
							'yandex_market', 'dbs', 'single_catalog', 'sales_terms', 'sbermegamarket', 'beru',
							'products_and_offers', 'yandex_webmaster', 'all_elements', 'ozon', 'vk'
						],
						'tag_name' => 'country_of_origin'
					]
				],
				[ 0 => 'yfym_source_id', 1 => 'disabled', 2 => 'public', // TODO: Удалить потом эту строку
					'opt_name' => 'yfym_source_id',
					'def_val' => 'disabled',
					'mark' => 'public',
					'required' => false,
					'type' => 'select',
					'tab' => 'wp_list_table',
					'data' => [ 
						'label' => __( 'Source ID of the product', 'yml-for-yandex-market' ),
						'desc' => '',
						'woo_attr' => true,
						'default_value' => true,
						'key_value_arr' => [ 
							[ 'value' => 'disabled', 'text' => __( 'Disabled', 'yml-for-yandex-market' ) ],
							[ 'value' => 'sku', 'text' => __( 'Substitute from SKU', 'yml-for-yandex-market' ) ],
							[ 'value' => 'post_meta', 'text' => __( 'Substitute from post meta', 'yml-for-yandex-market' ) ],
							[ 
								'value' => 'germanized',
								'text' => __( 'Substitute from', 'yml-for-yandex-market' ) . 'WooCommerce Germanized'
							]
						],
						'rules' => [ 
							'yandex_market', 'dbs', 'single_catalog', 'sales_terms', 'sbermegamarket', 'beru',
							'products_and_offers', 'yandex_webmaster', 'all_elements', 'ozon', 'vk'
						],
						'tag_name' => ''
					]
				],
				[ 0 => 'yfym_source_id_post_meta', 1 => '', 2 => 'public', // TODO: Удалить потом эту строку
					'opt_name' => 'yfym_source_id_post_meta',
					'def_val' => '',
					'mark' => 'public',
					'required' => true,
					'type' => 'text',
					'tab' => 'wp_list_table',
					'data' => [ 
						'label' => '',
						'desc' => '',
						'placeholder' => __( 'Name post_meta', 'yml-for-yandex-market' )
					]
				],
				[ 0 => 'yfym_on_demand', 1 => 'disabled', 2 => 'public', // TODO: Удалить потом эту строку
					'opt_name' => 'yfym_on_demand',
					'def_val' => 'disabled',
					'mark' => 'public',
					'required' => false,
					'type' => 'select',
					'tab' => 'wp_list_table',
					'data' => [ 
						'label' => __( 'Mark products under the order', 'yml-for-yandex-market' ),
						'desc' => __( 'Product under the order', 'yml-for-yandex-market' ),
						'woo_attr' => false,
						'default_value' => false,
						'key_value_arr' => [ 
							[ 'value' => 'disabled', 'text' => __( 'Disabled', 'yml-for-yandex-market' ) ],
							[ 'value' => 'enabled', 'text' => __( 'Enabled', 'yml-for-yandex-market' ) ]
						],
						'rules' => [ 
							'yandex_market', 'dbs', 'single_catalog', 'sales_terms', 'sbermegamarket', 'beru',
							'products_and_offers', 'yandex_webmaster', 'all_elements', 'ozon', 'vk'
						],
						'tag_name' => 'type="on.demand"'
					]
				],
				[ 0 => 'yfym_pickup', 1 => 'true', 2 => 'public', // TODO: Удалить потом эту строку
					'opt_name' => 'yfym_pickup',
					'def_val' => 'disabled',
					'mark' => 'public',
					'required' => false,
					'type' => 'select',
					'tab' => 'wp_list_table',
					'data' => [ 
						'label' => __( 'Pickup', 'yml-for-yandex-market' ),
						'desc' => __( 'Option to get order from pickup point', 'yml-for-yandex-market' ),
						'woo_attr' => false,
						'default_value' => false,
						'key_value_arr' => [ 
							[ 'value' => 'disabled', 'text' => __( 'Disabled', 'yml-for-yandex-market' ) ],
							[ 'value' => 'true', 'text' => __( 'True', 'yml-for-yandex-market' ) ],
							[ 'value' => 'false', 'text' => __( 'False', 'yml-for-yandex-market' ) ]
						],
						'rules' => [ 
							'yandex_market', 'dbs', 'single_catalog', 'sales_terms', 'sbermegamarket', 'beru',
							'products_and_offers', 'yandex_webmaster', 'all_elements', 'ozon', 'vk'
						],
						'tag_name' => 'pickup'
					]
				],
				[ 0 => 'yfym_price_from', 1 => 'no', 2 => 'public', // TODO: Удалить потом эту строку
					'opt_name' => 'yfym_price_from',
					'def_val' => 'no',
					'mark' => 'public',
					'required' => false,
					'type' => 'select',
					'tab' => 'wp_list_table',
					'data' => [ 
						'label' => __( 'Price from', 'yml-for-yandex-market' ),
						'desc' => sprintf( '%s <strong>from="true"</strong> %s <strong>price</strong><br />
						<strong>%s:</strong><br /><code>&lt;price from=&quot;true&quot;&gt;2000&lt;/price&gt;</code>',
							__( 'Apply the setting Price from', 'yml-for-yandex-market' ),
							__( 'attribute of', 'yml-for-yandex-market' ),
							__( 'Example', 'yml-for-yandex-market' )
						),
						'woo_attr' => false,
						'default_value' => false,
						'key_value_arr' => [ 
							[ 'value' => 'true', 'text' => __( 'Yes', 'yml-for-yandex-market' ) ],
							[ 'value' => 'false', 'text' => __( 'No', 'yml-for-yandex-market' ) ]
						],
						'rules' => [ 
							'yandex_market', 'dbs', 'single_catalog', 'sales_terms', 'sbermegamarket', 'beru',
							'products_and_offers', 'yandex_webmaster', 'all_elements', 'ozon', 'vk'
						],
						'tag_name' => '...from="true"...'
					]
				],
				[ 0 => 'yfym_oldprice', 1 => 'no', 2 => 'public', // TODO: Удалить потом эту строку
					'opt_name' => 'yfym_oldprice',
					'def_val' => 'no',
					'mark' => 'public',
					'required' => false,
					'type' => 'select',
					'tab' => 'wp_list_table',
					'data' => [ 
						'label' => __( 'Old price', 'yml-for-yandex-market' ),
						'desc' => __(
							'In oldprice indicates the old price of the goods, which must necessarily be higher than the new price (price)',
							'yml-for-yandex-market'
						),
						'woo_attr' => false,
						'default_value' => false,
						'key_value_arr' => [ 
							[ 'value' => 'yes', 'text' => __( 'Yes', 'yml-for-yandex-market' ) ],
							[ 'value' => 'no', 'text' => __( 'No', 'yml-for-yandex-market' ) ]
						],
						'rules' => [ 
							'yandex_market', 'dbs', 'single_catalog', 'sales_terms', 'sbermegamarket', 'beru',
							'products_and_offers', 'yandex_webmaster', 'all_elements', 'ozon', 'vk'
						],
						'tag_name' => 'oldprice'
					]
				],
				[ 0 => 'yfym_delivery', 1 => 'false', 2 => 'public', // TODO: Удалить потом эту строку
					'opt_name' => 'yfym_delivery',
					'def_val' => 'false',
					'mark' => 'public',
					'required' => false,
					'type' => 'select',
					'tab' => 'wp_list_table',
					'data' => [ 
						'label' => __( 'Delivery', 'yml-for-yandex-market' ),
						'desc' => __(
							'The delivery item must be set to false if the item is prohibited to sell remotely (jewelry, medicines)',
							'yml-for-yandex-market'
						),
						'woo_attr' => false,
						'default_value' => false,
						'key_value_arr' => [ 
							[ 'value' => 'true', 'text' => __( 'True', 'yml-for-yandex-market' ) ],
							[ 'value' => 'false', 'text' => __( 'False', 'yml-for-yandex-market' ) ]
						],
						'rules' => [ 
							'yandex_market', 'dbs', 'single_catalog', 'sales_terms', 'sbermegamarket', 'beru',
							'products_and_offers', 'yandex_webmaster', 'all_elements', 'ozon', 'vk'
						],
						'tag_name' => 'delivery'
					]
				],
				[ 0 => 'yfym_vat', 1 => 'disabled', 2 => 'public', // TODO: Удалить потом эту строку
					'opt_name' => 'yfym_vat',
					'def_val' => 'no',
					'mark' => 'public',
					'required' => false,
					'type' => 'select',
					'tab' => 'wp_list_table',
					'data' => [ 
						'label' => __( 'VAT rate', 'yml-for-yandex-market' ),
						'desc' => __(
							'This element is used when creating an YML feed for Yandex.Delivery',
							'yml-for-yandex-market'
						),
						'woo_attr' => false,
						'default_value' => false,
						'key_value_arr' => [ 
							[ 'value' => 'disabled', 'text' => __( 'Disabled', 'yml-for-yandex-market' ) ],
							[ 'value' => 'enabled', 'text' => __( 'Enable. No default value', 'yml-for-yandex-market' ) ],
							[ 'value' => 'NO_VAT', 'text' => __( 'No VAT', 'yml-for-yandex-market' ) . ' (NO_VAT)' ],
							[ 'value' => 'VAT_0', 'text' => '0% (VAT_0)' ],
							[ 'value' => 'VAT_10', 'text' => '10% (VAT_10)' ],
							[ 'value' => 'VAT_10_110', 'text' => 'VAT_10_110' ],
							[ 'value' => 'VAT_18', 'text' => '18% (VAT_18)' ],
							[ 'value' => 'VAT_18_118', 'text' => '18/118 (VAT_18_118)' ],
							[ 'value' => 'VAT_20', 'text' => '20% (VAT_20)' ],
							[ 'value' => 'VAT_20_120', 'text' => '20/120 (VAT_20_120)' ],
						],
						'rules' => [ 
							'yandex_market', 'dbs', 'single_catalog', 'sales_terms', 'sbermegamarket', 'beru',
							'products_and_offers', 'yandex_webmaster', 'all_elements', 'ozon', 'vk'
						],
						'tag_name' => 'vat'
					]
				],
				[ 0 => 'yfym_video', 1 => 'disabled', 2 => 'public', // TODO: Удалить потом эту строку
					'opt_name' => 'yfym_video',
					'def_val' => 'disabled',
					'mark' => 'public',
					'required' => false,
					'type' => 'select',
					'tab' => 'wp_list_table',
					'data' => [ 
						'label' => __( 'Video', 'yml-for-yandex-market' ),
						'desc' => __(
							'This element is used when creating an YML feed for Yandex Direct',
							'yml-for-yandex-market'
						),
						'woo_attr' => false,
						'default_value' => false,
						'key_value_arr' => [ 
							[ 'value' => 'disabled', 'text' => __( 'Disabled', 'yml-for-yandex-market' ) ],
							[ 'value' => 'enabled', 'text' => __( 'Enabled', 'yml-for-yandex-market' ) ]
						],
						'rules' => [ 
							'direct', 'all_elements'
						],
						'tag_name' => 'video'
					]
				],
				[ 0 => 'yfym_barcode', 1 => 'disabled', 2 => 'public', // TODO: Удалить потом эту строку
					'opt_name' => 'yfym_barcode',
					'def_val' => 'no',
					'mark' => 'public',
					'required' => false,
					'type' => 'select',
					'tab' => 'wp_list_table',
					'data' => [ 
						'label' => __( 'Barcode', 'yml-for-yandex-market' ),
						'desc' => '',
						'woo_attr' => true,
						'default_value' => true,
						'key_value_arr' => [ 
							[ 'value' => 'disabled', 'text' => __( 'Disabled', 'yml-for-yandex-market' ) ],
							[ 'value' => 'sku', 'text' => __( 'Substitute from SKU', 'yml-for-yandex-market' ) ],
							[ 'value' => 'post_meta', 'text' => __( 'Substitute from post meta', 'yml-for-yandex-market' ) ],
							[ 
								'value' => 'ean-for-woocommerce',
								'text' => __( 'Substitute from', 'yml-for-yandex-market' ) . ' EAN for WooCommerce'
							],
							[ 
								'value' => 'germanized',
								'text' => __( 'Substitute from', 'yml-for-yandex-market' ) . ' WooCommerce Germanized'
							]
						],
						'rules' => [ 
							'yandex_market', 'dbs', 'single_catalog', 'sales_terms', 'sbermegamarket', 'beru',
							'products_and_offers', 'yandex_webmaster', 'all_elements', 'ozon', 'vk'
						],
						'tag_name' => 'barcode'
					]
				],
				[ 0 => 'yfym_barcode_post_meta', 1 => '', 2 => 'public', // TODO: Удалить потом эту строку
					'opt_name' => 'yfym_barcode_post_meta',
					'def_val' => '',
					'mark' => 'public',
					'required' => true,
					'type' => 'text',
					'tab' => 'wp_list_table',
					'data' => [ 
						'label' => '',
						'desc' => '',
						'placeholder' => __( 'Name post_meta', 'yml-for-yandex-market' )
					]
				],
				[ 0 => 'yfym_vendorcode', 1 => 'disabled', 2 => 'public', // TODO: Удалить потом эту строку
					'opt_name' => 'yfym_vendorcode',
					'def_val' => 'disabled',
					'mark' => 'public',
					'required' => false,
					'type' => 'select',
					'tab' => 'wp_list_table',
					'data' => [ 
						'label' => __( 'Vendor Code', 'yml-for-yandex-market' ),
						'desc' => __( 'Vendor Code', 'yml-for-yandex-market' ),
						'woo_attr' => true,
						'default_value' => false,
						'key_value_arr' => [ 
							[ 'value' => 'disabled', 'text' => __( 'Disabled', 'yml-for-yandex-market' ) ],
							[ 'value' => 'sku', 'text' => __( 'Substitute from SKU', 'yml-for-yandex-market' ) ]
						],
						'rules' => [ 
							'yandex_market', 'dbs', 'single_catalog', 'sales_terms', 'sbermegamarket', 'beru',
							'products_and_offers', 'yandex_webmaster', 'all_elements', 'ozon', 'vk'
						],
						'tag_name' => 'vendorcode'
					]
				],
				[ 0 => 'yfym_cargo_types', 1 => 'disabled', 2 => 'public', // TODO: Удалить потом эту строку
					'opt_name' => 'yfym_cargo_types',
					'def_val' => 'disabled',
					'mark' => 'public',
					'required' => false,
					'type' => 'select',
					'tab' => 'wp_list_table',
					'data' => [ 
						'label' => '«Честный ЗНАК»',
						'desc' => '',
						'woo_attr' => false,
						'default_value' => false,
						'key_value_arr' => [ 
							[ 'value' => 'disabled', 'text' => __( 'Disabled', 'yml-for-yandex-market' ) ],
							[ 'value' => 'enabled', 'text' => __( 'Enabled', 'yml-for-yandex-market' ) ]
						],
						'rules' => [ 
							'yandex_market', 'dbs', 'single_catalog', 'sales_terms', 'sbermegamarket', 'beru',
							'products_and_offers', 'yandex_webmaster', 'all_elements', 'ozon', 'vk'
						],
						'tag_name' => 'cargo-types'
					]
				],
				[ 0 => 'yfym_expiry', 1 => 'off', 2 => 'public', // TODO: Удалить потом эту строку
					'opt_name' => 'yfym_expiry',
					'def_val' => 'disabled',
					'mark' => 'public',
					'required' => false,
					'type' => 'select',
					'tab' => 'wp_list_table',
					'data' => [ 
						'label' => __( 'Shelf life / service life', 'yml-for-yandex-market' ),
						'desc' => __( 'Shelf life / service life. expiry date / service life', 'yml-for-yandex-market' ),
						'woo_attr' => true,
						'default_value' => false,
						'key_value_arr' => [ 
							[ 'value' => 'disabled', 'text' => __( 'Disabled', 'yml-for-yandex-market' ) ]
						],
						'rules' => [ 
							'yandex_market', 'dbs', 'single_catalog', 'sales_terms', 'sbermegamarket', 'beru',
							'products_and_offers', 'yandex_webmaster', 'all_elements', 'ozon', 'vk'
						],
						'tag_name' => 'expiry'
					]
				],
				[ 0 => 'yfym_period_of_validity_days', 1 => 'disabled', 2 => 'public', // TODO: Удалить потом эту строку
					'opt_name' => 'yfym_period_of_validity_days',
					'def_val' => 'disabled',
					'mark' => 'public',
					'required' => false,
					'type' => 'select',
					'tab' => 'wp_list_table',
					'data' => [ 
						'label' => __( 'Shelf life', 'yml-for-yandex-market' ),
						'desc' => __( 'Shelf life', 'yml-for-yandex-market' ),
						'woo_attr' => true,
						'default_value' => false,
						'key_value_arr' => [ 
							[ 'value' => 'disabled', 'text' => __( 'Disabled', 'yml-for-yandex-market' ) ]
						],
						'rules' => [ 
							'yandex_market', 'dbs', 'single_catalog', 'sales_terms', 'sbermegamarket', 'beru',
							'products_and_offers', 'yandex_webmaster', 'all_elements', 'ozon', 'vk'
						],
						'tag_name' => 'period-of-validity-days'
					]
				],
				[ 0 => 'yfym_downloadable', 1 => 'off', 2 => 'public', // TODO: Удалить потом эту строку
					'opt_name' => 'yfym_downloadable',
					'def_val' => 'off',
					'mark' => 'public',
					'required' => false,
					'type' => 'select',
					'tab' => 'wp_list_table',
					'data' => [ 
						'label' => __( 'Mark downloadable products', 'yml-for-yandex-market' ),
						'desc' => '',
						'woo_attr' => false,
						'default_value' => false,
						'key_value_arr' => [ 
							[ 'value' => 'off', 'text' => __( 'Disabled', 'yml-for-yandex-market' ) ],
							[ 'value' => 'on', 'text' => __( 'On', 'yml-for-yandex-market' ) ]
						],
						'rules' => [ 
							'yandex_market', 'dbs', 'single_catalog', 'sales_terms', 'sbermegamarket', 'beru',
							'products_and_offers', 'yandex_webmaster', 'all_elements', 'ozon', 'vk'
						],
						'tag_name' => 'downloadable'
					]
				],
				[ 
					'opt_name' => 'yfym_age',
					'def_val' => 'disabled',
					'mark' => 'public',
					'required' => false,
					'type' => 'select',
					'tab' => 'wp_list_table',
					'data' => [ 
						'label' => __( 'Age', 'yml-for-yandex-market' ),
						'desc' => __( 'Age', 'yml-for-yandex-market' ),
						'woo_attr' => true,
						'default_value' => false,
						'key_value_arr' => [ 
							[ 'value' => 'disabled', 'text' => __( 'Disabled', 'yml-for-yandex-market' ) ]
						],
						'rules' => [ 
							'yandex_market', 'dbs', 'single_catalog', 'sales_terms', 'sbermegamarket', 'beru',
							'products_and_offers', 'yandex_webmaster', 'all_elements', 'ozon', 'vk'
						],
						'tag_name' => 'age'
					]
				],
				[ 0 => 'yfym_model', 1 => 'disabled', 2 => 'public', // TODO: Удалить потом эту строку
					'opt_name' => 'yfym_model',
					'def_val' => 'disabled',
					'mark' => 'public',
					'required' => false,
					'type' => 'select',
					'tab' => 'wp_list_table',
					'data' => [ 
						'label' => __( 'Model', 'yml-for-yandex-market' ),
						'desc' => __( 'Model', 'yml-for-yandex-market' ),
						'woo_attr' => true,
						'default_value' => false,
						'key_value_arr' => [ 
							[ 'value' => 'disabled', 'text' => __( 'Disabled', 'yml-for-yandex-market' ) ],
							[ 'value' => 'sku', 'text' => __( 'Substitute from SKU', 'yml-for-yandex-market' ) ]
						],
						'rules' => [ 
							'yandex_market', 'dbs', 'single_catalog', 'sales_terms', 'sbermegamarket', 'beru',
							'products_and_offers', 'yandex_webmaster', 'all_elements', 'ozon', 'vk'
						],
						'tag_name' => 'model'
					]
				],
				[ 0 => 'yfym_manufacturer_warranty', 1 => 'off', 2 => 'public', // TODO: Удалить потом эту строку
					'opt_name' => 'yfym_manufacturer_warranty',
					'def_val' => 'disabled',
					'mark' => 'public',
					'required' => false,
					'type' => 'select',
					'tab' => 'wp_list_table',
					'data' => [ 
						'label' => __( 'Manufacturer warrant', 'yml-for-yandex-market' ),
						'desc' => __( "This element is used for products that have an official manufacturer's warranty", 'yfym' ) . '.<ul><li>false — ' . __( 'Product does not have an official warranty', 'yml-for-yandex-market' ) . '</li><li>true — ' . __( 'Product has an official warranty', 'yml-for-yandex-market' ) . '</li></ul>',
						'woo_attr' => true,
						'default_value' => false,
						'key_value_arr' => [ 
							[ 'value' => 'disabled', 'text' => __( 'Disabled', 'yml-for-yandex-market' ) ],
							[ 'value' => 'alltrue', 'text' => __( 'Add to all', 'yml-for-yandex-market' ) . ' true' ],
							[ 'value' => 'allfalse', 'text' => __( 'Add to all', 'yml-for-yandex-market' ) . ' false' ],
							[ 'value' => 'sku', 'text' => __( 'Substitute from SKU', 'yml-for-yandex-market' ) ]
						],
						'rules' => [ 
							'yandex_market', 'dbs', 'single_catalog', 'sales_terms', 'sbermegamarket', 'beru',
							'products_and_offers', 'yandex_webmaster', 'all_elements', 'ozon', 'vk'
						],
						'tag_name' => 'manufacturer_warranty'
					]
				],
				[ 0 => 'yfym_sales_notes_cat', 1 => 'off', 2 => 'public', // TODO: Удалить потом эту строку
					'opt_name' => 'yfym_sales_notes_cat',
					'def_val' => 'disabled',
					'mark' => 'public',
					'required' => false,
					'type' => 'select',
					'tab' => 'wp_list_table',
					'data' => [ 
						'label' => __( 'Sales notes', 'yml-for-yandex-market' ),
						'desc' => __(
							'The text may be up to 50 characters in length. Also in the item is forbidden to specify the terms of delivery and price reduction (discount on merchandise)',
							'yml-for-yandex-market'
						),
						'woo_attr' => true,
						'default_value' => true,
						'key_value_arr' => [ 
							[ 'value' => 'disabled', 'text' => __( 'Disabled', 'yml-for-yandex-market' ) ],
							[ 
								'value' => 'default_value',
								'text' => sprintf( '%s "%s"',
									__( 'Default value from field', 'yml-for-yandex-market' ),
									__( 'Default value', 'yml-for-yandex-market' )
								)
							]
						],
						'rules' => [ 
							'yandex_market', 'dbs', 'single_catalog', 'sales_terms', 'sbermegamarket', 'beru',
							'products_and_offers', 'yandex_webmaster', 'all_elements', 'ozon', 'vk'
						],
						'tag_name' => 'sales_notes'
					]
				],
				[ 0 => 'yfym_sales_notes', 1 => '', 2 => 'public', // TODO: Удалить потом эту строку
					'opt_name' => 'yfym_sales_notes',
					'def_val' => '',
					'mark' => 'public',
					'required' => true,
					'type' => 'text',
					'tab' => 'wp_list_table',
					'data' => [ 
						'label' => '',
						'desc' => '',
						'placeholder' => __( 'Default value', 'yml-for-yandex-market' )
					]
				],
				[ 0 => 'yfym_store', 1 => 'false', 2 => 'public', // TODO: Удалить потом эту строку
					'opt_name' => 'yfym_store',
					'def_val' => 'true',
					'mark' => 'public',
					'required' => false,
					'type' => 'select',
					'tab' => 'wp_list_table',
					'data' => [ 
						'label' => __( 'Store', 'yml-for-yandex-market' ),
						'desc' => sprintf( '<ul><li>%s — %s</li><li>%s — %s</li></ul>',
							__( 'true', 'yml-for-yandex-market' ),
							__( 'The product can be purchased in retail stores', 'yml-for-yandex-market' ),
							__( 'false', 'yml-for-yandex-market' ),
							__( 'the product cannot be purchased in retail stores', 'yml-for-yandex-market' )
						),
						'woo_attr' => false,
						'default_value' => false,
						'key_value_arr' => [ 
							[ 'value' => 'true', 'text' => __( 'True', 'yml-for-yandex-market' ) ],
							[ 'value' => 'false', 'text' => __( 'False', 'yml-for-yandex-market' ) ]
						],
						'rules' => [ 
							'yandex_market', 'dbs', 'single_catalog', 'sales_terms', 'sbermegamarket', 'beru',
							'products_and_offers', 'yandex_webmaster', 'all_elements', 'ozon', 'vk'
						],
						'tag_name' => 'store'
					]
				],
				[ 0 => 'yfym_condition', 1 => 'disabled', 2 => 'public', // TODO: Удалить потом эту строку
					'opt_name' => 'yfym_condition',
					'def_val' => 'true',
					'mark' => 'public',
					'required' => false,
					'type' => 'select',
					'tab' => 'wp_list_table',
					'data' => [ 
						'label' => __( 'Condition', 'yml-for-yandex-market' ),
						'desc' => sprintf( '%s %s:<br/>%s',
							__( 'Default value', 'yml-for-yandex-market' ),
							__( 'for', 'yml-for-yandex-market' ),
							'(...condition type="X"...)'
						),
						'woo_attr' => false,
						'default_value' => false,
						'key_value_arr' => [ 
							[ 'value' => 'disabled', 'text' => __( 'Disabled', 'yml-for-yandex-market' ) ],
							[ 
								'value' => 'showcasesample',
								'text' => __( 'Showcase sample', 'yml-for-yandex-market' ) . ' (showcasesample)'
							],
							[ 
								'value' => 'reduction',
								'text' => __( 'Reduction', 'yml-for-yandex-market' ) . ' (reduction)'
							],
							[ 
								'value' => 'fashionpreowned',
								'text' => __( 'Fashionpreowned', 'yml-for-yandex-market' ) . ' (fashionpreowned)'
							],
							[ 
								'value' => 'preowned',
								'text' => __( 'Fashionpreowned', 'yml-for-yandex-market' ) . ' (preowned)'
							],
							[ 
								'value' => 'likenew',
								'text' => __( 'Like New', 'yml-for-yandex-market' ) . ' (likenew)'
							]
						],
						'rules' => [ 
							'yandex_market', 'dbs', 'single_catalog', 'sales_terms', 'sbermegamarket', 'beru',
							'products_and_offers', 'yandex_webmaster', 'all_elements', 'ozon', 'vk'
						],
						'tag_name' => 'condition'
					]
				],
				[ 0 => 'yfym_reason', 1 => '', 2 => 'public', // TODO: Удалить потом эту строку
					'opt_name' => 'yfym_reason',
					'def_val' => '',
					'mark' => 'public',
					'required' => true,
					'type' => 'text',
					'tab' => 'wp_list_table',
					'data' => [ 
						'default_value' => false,
						'label' => '',
						'desc' => sprintf( '%s %s:<br/>%s [reason]',
							__( 'Default value', 'yml-for-yandex-market' ),
							__( 'for', 'yml-for-yandex-market' ),
							__( 'Reason', 'yml-for-yandex-market' )
						),
						'placeholder' => __( 'Default value', 'yml-for-yandex-market' ),
						'rules' => [ 
							'yandex_market', 'dbs', 'single_catalog', 'sales_terms', 'sbermegamarket', 'beru',
							'products_and_offers', 'yandex_webmaster', 'all_elements', 'ozon', 'vk'
						],
						'tag_name' => ''
					]
				],
				[ 0 => 'yfym_quality', 1 => 'perfect', 2 => 'public', // TODO: Удалить потом эту строку
					'opt_name' => 'yfym_quality',
					'def_val' => 'true',
					'mark' => 'public',
					'required' => false,
					'type' => 'select',
					'tab' => 'wp_list_table',
					'data' => [ 
						'label' => '',
						'desc' => sprintf( '%s %s:<br/>%s [quality]',
							__( 'Default value', 'yml-for-yandex-market' ),
							__( 'for', 'yml-for-yandex-market' ),
							__( 'Quality', 'yml-for-yandex-market' )
						),
						'woo_attr' => false,
						'default_value' => false,
						'key_value_arr' => [ 
							[ 'value' => 'perfect', 'text' => __( 'Perfect', 'yml-for-yandex-market' ) ],
							[ 'value' => 'excellent', 'text' => __( 'Excellent', 'yml-for-yandex-market' ) ],
							[ 'value' => 'good', 'text' => __( 'Good', 'yml-for-yandex-market' ) ]
						],
						'rules' => [ 
							'yandex_market', 'dbs', 'single_catalog', 'sales_terms', 'sbermegamarket', 'beru',
							'products_and_offers', 'yandex_webmaster', 'all_elements', 'ozon', 'vk'
						],
						'tag_name' => ''
					]
				],

				[ 0 => 'yfym_pickup_options', 1 => '0', 2 => 'public', // TODO: Удалить потом эту строку
					'opt_name' => 'yfym_pickup_options',
					'def_val' => 'true',
					'mark' => 'public',
					'required' => false,
					'type' => 'select',
					'tab' => 'wp_list_table',
					'data' => [ 
						'label' => __( 'Pickup of products', 'yml-for-yandex-market' ),
						'desc' => sprintf( '%s pickup-options.<br/><a 
						target="_blank" 
						href="//yandex.ru/support/partnermarket/elements/pickup-options.html#structure">%s</a>',
							__( 'Optional element', 'yml-for-yandex-market' ),
							__( 'Read more on Yandex', 'yml-for-yandex-market' )
						),
						'woo_attr' => false,
						'default_value' => false,
						'key_value_arr' => [ 
							[ 'value' => 'disabled', 'text' => __( 'Disabled', 'yml-for-yandex-market' ) ],
							[ 'value' => 'on', 'text' => __( 'Enabled', 'yml-for-yandex-market' ) ]
						],
						'rules' => [ 
							'yandex_market', 'dbs', 'single_catalog', 'sales_terms', 'sbermegamarket', 'beru',
							'products_and_offers', 'yandex_webmaster', 'all_elements', 'ozon', 'vk'
						],
						'tag_name' => 'pickup-options'
					]
				],
				[ 0 => 'yfym_pickup_cost', 1 => '0', 2 => 'public', // TODO: Удалить потом эту строку
					'opt_name' => 'yfym_pickup_cost',
					'def_val' => '',
					'mark' => 'public',
					'required' => true,
					'type' => 'text',
					'tab' => 'wp_list_table',
					'data' => [ 
						'default_value' => false,
						'label' => '',
						'desc' => sprintf( '%s:<br/><i>%s [order-before] %s pickup-options</i>',
							__( 'Pickup cost', 'yml-for-yandex-market' ),
							__( 'Required element', 'yml-for-yandex-market' ),
							__( 'of attribute', 'yml-for-yandex-market' )
						),
						'placeholder' => '',
						'rules' => [ 
							'yandex_market', 'dbs', 'single_catalog', 'sales_terms', 'sbermegamarket', 'beru',
							'products_and_offers', 'yandex_webmaster', 'all_elements', 'ozon', 'vk'
						],
						'tag_name' => ''
					]
				],
				[ 0 => 'yfym_pickup_days', 1 => '32', 2 => 'public', // TODO: Удалить потом эту строку
					'opt_name' => 'yfym_pickup_days',
					'def_val' => '',
					'mark' => 'public',
					'required' => true,
					'type' => 'text',
					'tab' => 'wp_list_table',
					'data' => [ 
						'default_value' => false,
						'label' => '',
						'desc' => sprintf( '%s:<br/><i>%s [order-before] %s pickup-options</i>',
							__( 'Pickup days', 'yml-for-yandex-market' ),
							__( 'Required element', 'yml-for-yandex-market' ),
							__( 'of attribute', 'yml-for-yandex-market' )
						),
						'placeholder' => __( 'Default value', 'yml-for-yandex-market' ),
						'rules' => [ 
							'yandex_market', 'dbs', 'single_catalog', 'sales_terms', 'sbermegamarket', 'beru',
							'products_and_offers', 'yandex_webmaster', 'all_elements', 'ozon', 'vk'
						],
						'tag_name' => ''
					]
				],
				[ 0 => 'yfym_pickup_order_before', 1 => '', 2 => 'public', // TODO: Удалить потом эту строку
					'opt_name' => 'yfym_pickup_order_before',
					'def_val' => '',
					'mark' => 'public',
					'required' => true,
					'type' => 'text',
					'tab' => 'wp_list_table',
					'data' => [ 
						'default_value' => false,
						'label' => '',
						'desc' => sprintf( '%s:<br/><i>%s [order-before] %s pickup-options</i>',
							__( 'The time in which you need to place an order to get it at this time', 'yml-for-yandex-market' ),
							__( 'Optional element', 'yml-for-yandex-market' ),
							__( 'of attribute', 'yml-for-yandex-market' )
						),
						'placeholder' => '',
						'rules' => [ 
							'yandex_market', 'dbs', 'single_catalog', 'sales_terms', 'sbermegamarket', 'beru',
							'products_and_offers', 'yandex_webmaster', 'all_elements', 'ozon', 'vk'
						],
						'tag_name' => ''
					]
				],

				[ 0 => 'yfym_delivery_options', 1 => '0', 2 => 'public', // TODO: Удалить потом эту строку
					'opt_name' => 'yfym_delivery_options',
					'def_val' => '',
					'mark' => 'public',
					'required' => false,
					'type' => 'select',
					'tab' => 'wp_list_table',
					'data' => [ 
						'label' => __( 'Use delivery-options', 'yml-for-yandex-market' ),
						'desc' => sprintf( '%s delivery-option.<br/><a 
						target="_blank" 
						href="//yandex.ru/support/partnermarket/elements/delivery-options.html#structure">%s</a>',
							__( 'Optional element', 'yml-for-yandex-market' ),
							__( 'Read more on Yandex', 'yml-for-yandex-market' )
						),
						'woo_attr' => false,
						'default_value' => false,
						'key_value_arr' => [ 
							[ 'value' => 'disabled', 'text' => __( 'Disabled', 'yml-for-yandex-market' ) ],
							[ 'value' => 'on', 'text' => __( 'Enabled', 'yml-for-yandex-market' ) ]
						],
						'rules' => [ 
							'yandex_market', 'dbs', 'single_catalog', 'sales_terms', 'sbermegamarket', 'beru',
							'products_and_offers', 'yandex_webmaster', 'all_elements', 'ozon', 'vk'
						],
						'tag_name' => 'delivery-option'
					]
				],
				[ 0 => 'yfym_delivery_cost', 1 => '0', 2 => 'public', // TODO: Удалить потом эту строку
					'opt_name' => 'yfym_delivery_cost',
					'def_val' => '',
					'mark' => 'public',
					'required' => true,
					'type' => 'text',
					'tab' => 'wp_list_table',
					'data' => [ 
						'default_value' => false,
						'label' => '',
						'desc' => sprintf( '%s:<br/><i>%s [cost] %s delivery-option</i>',
							__( 'Delivery cost', 'yml-for-yandex-market' ),
							__( 'Required element', 'yml-for-yandex-market' ),
							__( 'of attribute', 'yml-for-yandex-market' )
						),
						'placeholder' => '',
						'rules' => [ 
							'yandex_market', 'dbs', 'single_catalog', 'sales_terms', 'sbermegamarket', 'beru',
							'products_and_offers', 'yandex_webmaster', 'all_elements', 'ozon', 'vk'
						],
						'tag_name' => ''
					]
				],
				[ 0 => 'yfym_delivery_days', 1 => '32', 2 => 'public', // TODO: Удалить потом эту строку
					'opt_name' => 'yfym_delivery_days',
					'def_val' => '',
					'mark' => 'public',
					'required' => true,
					'type' => 'text',
					'tab' => 'wp_list_table',
					'data' => [ 
						'default_value' => false,
						'label' => '',
						'desc' => sprintf( '%s:<br/><i>%s [days] %s delivery-option</i>',
							__( 'Delivery days', 'yml-for-yandex-market' ),
							__( 'Required element', 'yml-for-yandex-market' ),
							__( 'of attribute', 'yml-for-yandex-market' )
						),
						'placeholder' => __( 'Default value', 'yml-for-yandex-market' ),
						'rules' => [ 
							'yandex_market', 'dbs', 'single_catalog', 'sales_terms', 'sbermegamarket', 'beru',
							'products_and_offers', 'yandex_webmaster', 'all_elements', 'ozon', 'vk'
						],
						'tag_name' => ''
					]
				],
				[ 0 => 'yfym_order_before', 1 => '', 2 => 'public', // TODO: Удалить потом эту строку
					'opt_name' => 'yfym_order_before',
					'def_val' => '',
					'mark' => 'public',
					'required' => true,
					'type' => 'text',
					'tab' => 'wp_list_table',
					'data' => [ 
						'default_value' => false,
						'label' => '',
						'desc' => sprintf( '%s:<br/><i>%s [order-before] %s delivery-option</i>',
							__( 'The time in which you need to place an order to get it at this time', 'yml-for-yandex-market' ),
							__( 'Optional element', 'yml-for-yandex-market' ),
							__( 'of attribute', 'yml-for-yandex-market' )
						),
						'placeholder' => '',
						'rules' => [ 
							'yandex_market', 'dbs', 'single_catalog', 'sales_terms', 'sbermegamarket', 'beru',
							'products_and_offers', 'yandex_webmaster', 'all_elements', 'ozon', 'vk'
						],
						'tag_name' => ''
					]
				],

				[ 0 => 'yfym_delivery_options2', 1 => '0', 2 => 'public', // TODO: Удалить потом эту строку
					'opt_name' => 'yfym_delivery_options2',
					'def_val' => '',
					'mark' => 'public',
					'required' => false,
					'type' => 'select',
					'tab' => 'wp_list_table',
					'data' => [ 
						'label' => sprintf( '%s<br/><small><i>(%s)</i></small>',
							__( 'Use delivery-options', 'yml-for-yandex-market' ),
							__( 'Add a second delivery methods', 'yml-for-yandex-market' )
						),
						'desc' => sprintf( '%s delivery-option.<br/><a 
						target="_blank" 
						href="//yandex.ru/support/partnermarket/elements/delivery-options.html#structure">%s</a>',
							__( 'Optional element', 'yml-for-yandex-market' ),
							__( 'Read more on Yandex', 'yml-for-yandex-market' )
						),
						'woo_attr' => false,
						'default_value' => false,
						'key_value_arr' => [ 
							[ 'value' => 'disabled', 'text' => __( 'Disabled', 'yml-for-yandex-market' ) ],
							[ 'value' => 'on', 'text' => __( 'Enabled', 'yml-for-yandex-market' ) ]
						],
						'rules' => [ 
							'yandex_market', 'dbs', 'single_catalog', 'sales_terms', 'sbermegamarket', 'beru',
							'products_and_offers', 'yandex_webmaster', 'all_elements', 'ozon', 'vk'
						],
						'tag_name' => 'delivery-option'
					]
				],
				[ 0 => 'yfym_delivery_cost2', 1 => '0', 2 => 'public', // TODO: Удалить потом эту строку
					'opt_name' => 'yfym_delivery_cost2',
					'def_val' => '',
					'mark' => 'public',
					'required' => true,
					'type' => 'text',
					'tab' => 'wp_list_table',
					'data' => [ 
						'default_value' => false,
						'label' => '',
						'desc' => sprintf( '%s:<br/><i>%s [cost] %s delivery-option</i>',
							__( 'Delivery cost', 'yml-for-yandex-market' ),
							__( 'Required element', 'yml-for-yandex-market' ),
							__( 'of attribute', 'yml-for-yandex-market' )
						),
						'placeholder' => '',
						'rules' => [ 
							'yandex_market', 'dbs', 'single_catalog', 'sales_terms', 'sbermegamarket', 'beru',
							'products_and_offers', 'yandex_webmaster', 'all_elements', 'ozon', 'vk'
						],
						'tag_name' => ''
					]
				],
				[ 0 => 'yfym_delivery_days2', 1 => '32', 2 => 'public', // TODO: Удалить потом эту строку
					'opt_name' => 'yfym_delivery_days2',
					'def_val' => '',
					'mark' => 'public',
					'required' => true,
					'type' => 'text',
					'tab' => 'wp_list_table',
					'data' => [ 
						'default_value' => false,
						'label' => '',
						'desc' => sprintf( '%s:<br/><i>%s [days] %s delivery-option</i>',
							__( 'Delivery days', 'yml-for-yandex-market' ),
							__( 'Required element', 'yml-for-yandex-market' ),
							__( 'of attribute', 'yml-for-yandex-market' )
						),
						'placeholder' => __( 'Default value', 'yml-for-yandex-market' ),
						'rules' => [ 
							'yandex_market', 'dbs', 'single_catalog', 'sales_terms', 'sbermegamarket', 'beru',
							'products_and_offers', 'yandex_webmaster', 'all_elements', 'ozon', 'vk'
						],
						'tag_name' => ''
					]
				],
				[ 0 => 'yfym_order_before2', 1 => '', 2 => 'public', // TODO: Удалить потом эту строку
					'opt_name' => 'yfym_order_before2',
					'def_val' => '',
					'mark' => 'public',
					'required' => true,
					'type' => 'text',
					'tab' => 'wp_list_table',
					'data' => [ 
						'default_value' => false,
						'label' => '',
						'desc' => sprintf( '%s:<br/><i>%s [order-before] %s delivery-option</i>',
							__( 'The time in which you need to place an order to get it at this time', 'yml-for-yandex-market' ),
							__( 'Optional element', 'yml-for-yandex-market' ),
							__( 'of attribute', 'yml-for-yandex-market' )
						),
						'placeholder' => '',
						'rules' => [ 
							'yandex_market', 'dbs', 'single_catalog', 'sales_terms', 'sbermegamarket', 'beru',
							'products_and_offers', 'yandex_webmaster', 'all_elements', 'ozon', 'vk'
						],
						'tag_name' => ''
					]
				],
				[ 0 => 'yfym_params_arr', 1 => '', 2 => 'public', // TODO: Удалить потом эту строку
					'opt_name' => 'yfym_params_arr',
					'def_val' => '',
					'mark' => 'public',
					'required' => true,
					'type' => 'select',
					'tab' => 'wp_list_table',
					'data' => [ 
						'label' => __( 'Include these attributes in the values Param', 'yml-for-yandex-market' ),
						'desc' => sprintf( '%s: %s',
							__( 'Hint', 'yml-for-yandex-market' ),
							__(
								'To select multiple values, hold down the (ctrl) button on Windows or (cmd) on a Mac. To deselect, press and hold (ctrl) or (cmd), click on the marked items',
								'yml-for-yandex-market'
							)
						),
						'woo_attr' => true,
						'default_value' => false,
						'key_value_arr' => [],
						'multiple' => true,
						'size' => '8',
						'rules' => [ 
							'yandex_market', 'dbs', 'single_catalog', 'sales_terms', 'sbermegamarket', 'beru',
							'products_and_offers', 'yandex_webmaster', 'all_elements', 'ozon', 'vk'
						],
						'tag_name' => esc_attr( '<param name="ATTR_NAME">ATTR_VAL</param>' )
					]
				],
				[ 0 => 'yfym_behavior_stip_symbol', 1 => 'default', 2 => 'public', // TODO: Удалить потом эту строку
					'opt_name' => 'yfym_behavior_of_params',
					'def_val' => 'default',
					'mark' => 'public',
					'required' => true,
					'type' => 'select',
					'tab' => 'wp_list_table',
					'data' => [ 
						'label' => '',
						'desc' => __( 'If the attribute has multiple values', 'yml-for-yandex-market' ),
						'woo_attr' => false,
						'default_value' => false,
						'key_value_arr' => [ 
							[ 
								'value' => 'default',
								'text' => sprintf( '%s (%s)',
									__( 'Default', 'yml-for-yandex-market' ),
									__( 'No split', 'yml-for-yandex-market' )
								)
							],
							[ 
								'value' => 'split',
								'text' => __( 'Split', 'yml-for-yandex-market' )
							]
						],
						'rules' => [ 
							'yandex_market', 'dbs', 'single_catalog', 'sales_terms', 'sbermegamarket', 'beru',
							'products_and_offers', 'yandex_webmaster', 'all_elements', 'ozon', 'vk'
						],
						'tag_name' => ''
					]
				],
				[ 0 => 'yfym_ebay_stock', 1 => '0', 2 => 'public', // TODO: Удалить потом эту строку
					'opt_name' => 'yfym_ebay_stock',
					'def_val' => 'disabled',
					'mark' => 'public',
					'required' => false,
					'type' => 'select',
					'tab' => 'wp_list_table',
					'data' => [ 
						'label' => __( 'Add information about stock to feed for EBay', 'yml-for-yandex-market' ),
						'desc' => '',
						'woo_attr' => false,
						'default_value' => false,
						'key_value_arr' => [ 
							[ 'value' => 'disabled', 'text' => __( 'Disabled', 'yml-for-yandex-market' ) ],
							[ 'value' => 'enabled', 'text' => __( 'Enabled', 'yml-for-yandex-market' ) ]
						],
						'rules' => [ 
							'yandex_market', 'dbs', 'single_catalog', 'sales_terms', 'sbermegamarket', 'beru',
							'products_and_offers', 'yandex_webmaster', 'all_elements', 'ozon', 'vk'
						],
						'tag_name' => esc_attr( '<param name="stock">X</param>' )
					]
				],
				// ------------------- ФИЛЬТРАЦИЯ -------------------
				[ 0 => 'yfym_whot_export', 1 => 'all', 2 => 'public', // TODO: Удалить потом эту строку
					'opt_name' => 'yfym_whot_export',
					'def_val' => 'all',
					'mark' => 'public',
					'required' => true,
					'type' => 'select',
					'tab' => 'filtration_tab',
					'data' => [ 
						'label' => __( 'Whot export', 'yml-for-yandex-market' ),
						'desc' => '',
						'woo_attr' => false,
						'key_value_arr' => [ 
							[ 
								'value' => 'all',
								'text' => __( 'Simple & Variable products', 'yml-for-yandex-market' )
							],
							[ 
								'value' => 'simple',
								'text' => __( 'Only simple products', 'yml-for-yandex-market' )
							],
							[ 
								'value' => 'variable',
								'text' => __( 'Only variable products', 'yml-for-yandex-market' )
							]
						]
					]
				],
				[ 0 => 'yfym_desc', 1 => 'fullexcerpt', 2 => 'public', // TODO: Удалить потом эту строку
					'opt_name' => 'yfym_desc',
					'def_val' => 'fullexcerpt',
					'mark' => 'public',
					'required' => true,
					'type' => 'select',
					'tab' => 'filtration_tab',
					'data' => [ 
						'label' => __( 'Description of the product', 'yml-for-yandex-market' ),
						'desc' => sprintf( '[description] - %s',
							__( 'The source of the description', 'yml-for-yandex-market' )
						),
						'woo_attr' => false,
						'key_value_arr' => [ 
							[ 
								'value' => 'excerpt',
								'text' => __( 'Only Excerpt description', 'yml-for-yandex-market' )
							],
							[ 
								'value' => 'full',
								'text' => __( 'Only Full description', 'yml-for-yandex-market' )
							],
							[ 
								'value' => 'excerptfull',
								'text' => __( 'Excerpt or Full description', 'yml-for-yandex-market' )
							],
							[ 
								'value' => 'fullexcerpt',
								'text' => __( 'Full or Excerpt description', 'yml-for-yandex-market' )
							],
							[ 
								'value' => 'excerptplusfull',
								'text' => __( 'Excerpt plus Full description', 'yml-for-yandex-market' )
							],
							[ 
								'value' => 'fullplusexcerpt',
								'text' => __( 'Full plus Excerpt description', 'yml-for-yandex-market' )
							]
						],
						'tr_class' => 'y4ym_tr'
					]
				],
				[ 0 => 'yfym_enable_tags_behavior', 1 => 'default', 2 => 'public', // TODO: Удалить потом эту строку
					'opt_name' => 'yfym_enable_tags_behavior',
					'def_val' => '',
					'mark' => 'public',
					'required' => false,
					'type' => 'select',
					'tab' => 'filtration_tab',
					'data' => [ 
						'label' => __( 'List of allowed tags', 'yml-for-yandex-market' ),
						'desc' => '',
						'woo_attr' => false,
						'default_value' => false,
						'key_value_arr' => [ 
							[ 'value' => 'default', 'text' => __( 'Default', 'yml-for-yandex-market' ) ],
							[ 'value' => 'custom', 'text' => __( 'From the field below', 'yml-for-yandex-market' ) ]
						]
					]
				],
				[ 0 => 'yfym_enable_tags_custom', 1 => '', 2 => 'public', // TODO: Удалить потом эту строк
					'opt_name' => 'yfym_enable_tags_custom',
					'def_val' => '',
					'mark' => 'public',
					'required' => true,
					'type' => 'text',
					'tab' => 'filtration_tab',
					'data' => [ 
						'default_value' => false,
						'label' => '',
						'desc' => sprintf( '%s <code>p,br,h3</code>',
							__( 'For example', 'yml-for-yandex-market' )
						),
						'placeholder' => 'p,br,h3'
					]
				],
				[ 0 => 'yfym_the_content', 1 => 'enabled', 2 => 'public', // TODO: Удалить потом эту строку
					'opt_name' => 'yfym_the_content',
					'def_val' => 'enabled',
					'mark' => 'public',
					'required' => false,
					'type' => 'select',
					'tab' => 'filtration_tab',
					'data' => [ 
						'label' => __( 'Use the filter', 'yml-for-yandex-market' ) . ' the_content',
						'desc' => sprintf( '%s: %s',
							__( 'Default', 'yml-for-yandex-market' ),
							__( 'Enabled', 'yml-for-yandex-market' )
						),
						'woo_attr' => false,
						'default_value' => false,
						'key_value_arr' => [ 
							[ 'value' => 'disabled', 'text' => __( 'Disabled', 'yml-for-yandex-market' ) ],
							[ 'value' => 'enabled', 'text' => __( 'Enabled', 'yml-for-yandex-market' ) ]
						]
					]
				],
				[ 0 => 'yfym_replace_domain', 1 => '', 2 => 'public', // TODO: Удалить потом эту строку
					'opt_name' => 'yfym_replace_domain',
					'def_val' => '',
					'mark' => 'public',
					'required' => true,
					'type' => 'text',
					'tab' => 'filtration_tab',
					'data' => [ 
						'default_value' => false,
						'label' => __( 'Change the domain to', 'yml-for-yandex-market' ),
						'desc' => __(
							'The option allows you to change the domain of your site in the feed to any other',
							'yml-for-yandex-market'
						),
						'placeholder' => 'https://site.ru',
						'tr_class' => 'y4ym_tr'
					]
				],
				[ 0 => 'yfym_clear_get', 1 => 'disabled', 2 => 'public', // TODO: Удалить потом эту строку
					'opt_name' => 'yfym_clear_get',
					'def_val' => 'disabled',
					'mark' => 'public',
					'required' => false,
					'type' => 'select',
					'tab' => 'filtration_tab',
					'data' => [ 
						'label' => __(
							'Clear URL from GET-paramrs',
							'yml-for-yandex-market'
						),
						'desc' => sprintf( '%s: <a target="_blank" href="https://icopydoc.ru/vklyuchaem-turbo-stranitsy-dlya-magazina-woocommerce-instruktsiya/?utm_source=yml-for-yandex-market&utm_medium=organic&utm_campaign=in-plugin-yml-for-yandex-market&utm_content=settings&utm_term=yandex-turbo-instruction">%s</a>',
							__( 'This option may be useful when setting up Turbo pages', 'yml-for-yandex-market' ),
							__( 'Tips for configuring Turbo pages', 'yml-for-yandex-market' )
						),
						'woo_attr' => false,
						'default_value' => false,
						'key_value_arr' => [ 
							[ 'value' => 'disabled', 'text' => __( 'Disabled', 'yml-for-yandex-market' ) ],
							[ 'value' => 'on', 'text' => __( 'Enabled', 'yml-for-yandex-market' ) ]
						]
					]
				],
				[ 0 => 'yfym_behavior_stip_symbol', 1 => 'default', 2 => 'public', // TODO: Удалить потом эту строку	
					'opt_name' => 'yfym_behavior_stip_symbol',
					'def_val' => 'disabled',
					'mark' => 'public',
					'required' => false,
					'type' => 'select',
					'tab' => 'filtration_tab',
					'data' => [ 
						'label' => sprintf( '%s vendorCode %s shop-sku %s',
							__( 'In attributes', 'yml-for-yandex-market' ),
							__( 'and', 'yml-for-yandex-market' ),
							__( 'ampersand', 'yml-for-yandex-market' )
						),
						'desc' => '',
						'woo_attr' => false,
						'default_value' => false,
						'key_value_arr' => [ 
							[ 'value' => 'default', 'text' => __( 'Default', 'yml-for-yandex-market' ) ],
							[ 'value' => 'del', 'text' => __( 'Delete', 'yml-for-yandex-market' ) ],
							[ 
								'value' => 'slash',
								'text' => __( 'Replace with', 'yml-for-yandex-market' ) . ' /'
							],
							[ 
								'value' => 'amp',
								'text' => __( 'Replace with', 'yml-for-yandex-market' ) . ' amp;'
							]
						]
					]
				],
				[ 0 => 'yfym_var_desc_priority', 1 => 'on', 2 => 'public', // TODO: Удалить потом эту строку
					'opt_name' => 'yfym_var_desc_priority',
					'def_val' => 'disabled',
					'mark' => 'public',
					'required' => false,
					'type' => 'select',
					'tab' => 'filtration_tab',
					'data' => [ 
						'label' => __(
							'The varition description takes precedence over others',
							'yml-for-yandex-market'
						),
						'desc' => sprintf( '%s: %s',
							__( 'Default', 'yml-for-yandex-market' ),
							__( 'Enabled', 'yml-for-yandex-market' )
						),
						'woo_attr' => false,
						'default_value' => false,
						'key_value_arr' => [ 
							[ 'value' => 'disabled', 'text' => __( 'Disabled', 'yml-for-yandex-market' ) ],
							[ 'value' => 'on', 'text' => __( 'Enabled', 'yml-for-yandex-market' ) ]
						]
					]
				],
				[ 0 => 'yfym_no_group_id_arr', 1 => '', 2 => 'public', // TODO: Удалить потом эту строку
					'opt_name' => 'yfym_no_group_id_arr',
					'def_val' => '',
					'mark' => 'public',
					'required' => true,
					'type' => 'select',
					'tab' => 'filtration_tab',
					'data' => [ 
						'label' => __(
							'Categories of variable products for which group_id is not allowed',
							'yml-for-yandex-market'
						),
						'desc' => __(
							'According to Yandex Market rules in this field you need to mark ALL categories of products not related to "Clothes, Shoes and Accessories", "Furniture", "Cosmetics, perfumes and care", "Baby products", "Accessories for portable electronics". Ie categories for which it is forbidden to use the attribute group_id',
							'yml-for-yandex-market'
						),
						'woo_attr' => false,
						'default_value' => false,
						'key_value_arr' => [],
						'multiple' => true,
						'size' => '8',
						'tr_class' => 'y4ym_tr'
					]
				],
				[ 0 => 'yfym_add_in_name_arr', 1 => '', 2 => 'public', // TODO: Удалить потом эту строку
					'opt_name' => 'yfym_add_in_name_arr',
					'def_val' => '',
					'mark' => 'public',
					'required' => true,
					'type' => 'select',
					'tab' => 'filtration_tab',
					'data' => [ 
						'label' => __( 'Add attributes to the variable products name', 'yml-for-yandex-market' ),
						'desc' => sprintf( '%s. %s',
							__( 'You can only add attributes that are used for variations and that cannot be grouped using', 'yml-for-yandex-market' ),
							__(
								'It works only for variable products that are not in the category "Clothes, Shoes and Accessories", "Furniture", "Cosmetics, perfumes and care", "Baby products", "Accessories for portable electronics"',
								'yml-for-yandex-market'
							)
						),
						'woo_attr' => true,
						'default_value' => false,
						'key_value_arr' => [],
						'multiple' => true,
						'size' => '8'
					]
				],
				[ 0 => 'yfym_separator_type', 1 => 'type1', 2 => 'public', // TODO: Удалить потом эту строку
					'opt_name' => 'yfym_separator_type',
					'def_val' => 'type1',
					'mark' => 'public',
					'required' => true,
					'type' => 'select',
					'tab' => 'filtration_tab',
					'data' => [ 
						'label' => __( 'Separator options', 'yml-for-yandex-market' ),
						'desc' => __( 'Separator options', 'yml-for-yandex-market' ),
						'woo_attr' => false,
						'key_value_arr' => [ 
							[ 
								'value' => 'type1',
								'text' => sprintf( '%s 1. (В1:З1, В2:З2, ... Вn:Зn)',
									__( 'Type', 'yml-for-yandex-market' )
								)
							],
							[ 
								'value' => 'type2',
								'text' => sprintf( '%s 2. (В1-З1, В2-З2, ... Вn:Зn)',
									__( 'Type', 'yml-for-yandex-market' )
								)
							],
							[ 
								'value' => 'type3',
								'text' => sprintf( '%s 3. В1:З1, В2:З2, ... Вn:Зn',
									__( 'Type', 'yml-for-yandex-market' )
								)
							],
							[ 
								'value' => 'type4',
								'text' => sprintf( '%s 4. З1 З2 ... Зn',
									__( 'Type', 'yml-for-yandex-market' )
								)
							]
						]
					]
				],
				[ 0 => 'yfym_behavior_of_params', 1 => 'default', 2 => 'public', // TODO: Удалить потом эту строку
					'opt_name' => 'yfym_behavior_onbackorder',
					'def_val' => 'disabled',
					'mark' => 'public',
					'required' => false,
					'type' => 'select',
					'tab' => 'filtration_tab',
					'data' => [ 
						'label' => __(
							'For pre-order products, establish availability equal to',
							'yml-for-yandex-market'
						),
						'desc' => '',
						'woo_attr' => false,
						'default_value' => false,
						'key_value_arr' => [ 
							[ 'value' => 'true', 'text' => 'True' ],
							[ 'value' => 'false', 'text' => 'False' ]
						],
						'tr_class' => 'y4ym_tr'
					]
				],
				[ 0 => 'yfym_no_default_png_products', 1 => '0', 2 => 'public', // TODO: Удалить потом эту строку
					'opt_name' => 'yfym_no_default_png_products',
					'def_val' => 'disabled',
					'mark' => 'public',
					'required' => false,
					'type' => 'select',
					'tab' => 'filtration_tab',
					'data' => [ 
						'label' => __( 'Remove default.png from YML', 'yml-for-yandex-market' ),
						'desc' => '',
						'woo_attr' => false,
						'default_value' => false,
						'key_value_arr' => [ 
							[ 'value' => 'disabled', 'text' => __( 'Disabled', 'yml-for-yandex-market' ) ],
							[ 'value' => 'on', 'text' => __( 'Enabled', 'yml-for-yandex-market' ) ]
						]
					]
				],
				[ 0 => 'yfym_skip_missing_products', 1 => '0', 2 => 'public', // TODO: Удалить потом эту строку
					'opt_name' => 'yfym_skip_missing_products',
					'def_val' => 'disabled',
					'mark' => 'public',
					'required' => false,
					'type' => 'select',
					'tab' => 'filtration_tab',
					'data' => [ 
						'label' => sprintf( '%s (%s)',
							__( 'Skip missing products', 'yml-for-yandex-market' ),
							__( 'except for products for which a pre-order is permitted', 'yml-for-yandex-market' )
						),
						'desc' => '',
						'woo_attr' => false,
						'default_value' => false,
						'key_value_arr' => [ 
							[ 'value' => 'disabled', 'text' => __( 'Disabled', 'yml-for-yandex-market' ) ],
							[ 'value' => 'on', 'text' => __( 'Enabled', 'yml-for-yandex-market' ) ]
						],
						'tr_class' => 'y4ym_tr'
					]
				],
				[ 0 => 'yfym_skip_backorders_products', 1 => '0', 2 => 'public', // TODO: Удалить потом эту строку
					'opt_name' => 'yfym_skip_backorders_products',
					'def_val' => 'disabled',
					'mark' => 'public',
					'required' => false,
					'type' => 'select',
					'tab' => 'filtration_tab',
					'data' => [ 
						'label' => __( 'Skip backorders products', 'yml-for-yandex-market' ),
						'desc' => '',
						'woo_attr' => false,
						'default_value' => false,
						'key_value_arr' => [ 
							[ 'value' => 'disabled', 'text' => __( 'Disabled', 'yml-for-yandex-market' ) ],
							[ 'value' => 'on', 'text' => __( 'Enabled', 'yml-for-yandex-market' ) ]
						]
					]
				],
				[ 0 => 'yfym_skip_products_without_pic', 1 => '0', 2 => 'public', // TODO: Удалить потом эту строку
					'opt_name' => 'yfym_skip_products_without_pic',
					'def_val' => 'disabled',
					'mark' => 'public',
					'required' => false,
					'type' => 'select',
					'tab' => 'filtration_tab',
					'data' => [ 
						'label' => __( 'Skip products without pictures', 'yml-for-yandex-market' ),
						'desc' => '',
						'woo_attr' => false,
						'default_value' => false,
						'key_value_arr' => [ 
							[ 'value' => 'disabled', 'text' => __( 'Disabled', 'yml-for-yandex-market' ) ],
							[ 'value' => 'on', 'text' => __( 'Enabled', 'yml-for-yandex-market' ) ]
						]
					]
				]
			];
		} else {
			$this->data_arr = $data_arr;
		}

		if ( class_exists( 'WOOCS' ) ) {
			global $WOOCS;
			$currencies_arr = $WOOCS->get_currencies();

			if ( is_array( $currencies_arr ) ) {
				$array_keys = array_keys( $currencies_arr );
				for ( $i = 0; $i < count( $array_keys ); $i++ ) {
					$key_value_arr[] = [ 
						'value' => $array_keys[ $i ],
						'text' => $array_keys[ $i ]
					];
				}
			}
			$this->data_arr[] = [ 0 => 'yfym_wooc_currencies', 1 => '', 2 => 'public', // TODO: Удалить потом эту строку
				'opt_name' => 'yfym_wooc_currencies',
				'def_val' => '',
				'mark' => 'public',
				'required' => false,
				'type' => 'select',
				'tab' => 'shop_data_tab',
				'data' => [ 
					'label' => __( 'Feed currency', 'yml-for-yandex-market' ),
					'desc' => sprintf( '%s %s. %s.<br/><strong>%s:</strong> %s %s %s',
						__( 'You have plugin installed', 'yml-for-yandex-market' ),
						'WooCommerce Currency Switcher by PluginUs.NET. Woo Multi Currency and Woo Multi Pay',
						__( 'Indicate in what currency the prices should be', 'yml-for-yandex-market' ),
						__( 'Please note', 'yml-for-yandex-market' ),
						__( 'Yandex Market only supports the following currencies', 'yml-for-yandex-market' ),
						'RUR, RUB, UAH, BYN, KZT, USD, EUR',
						__( 'Choosing a different currency can lead to errors', 'yml-for-yandex-market' )
					),
					'woo_attr' => false,
					'default_value' => false,
					'key_value_arr' => $key_value_arr
				]
			];
		}

		$this->data_arr = apply_filters( 'y4ym_f_set_default_feed_settings_result_arr', $this->get_data_arr() );
	}

	/**
	 * Get the plugin data array
	 * 
	 * @return array
	 */
	public function get_data_arr() {
		return $this->data_arr;
	}

	/**
	 * Get data for tabs
	 * 
	 * @param string $whot
	 * 
	 * @return array	Example: array([0] => opt_key1, [1] => opt_key2, ...)
	 */
	public function get_data_for_tabs( $whot = '' ) {
		$res_arr = [];
		if ( ! empty( $this->get_data_arr() ) ) {
			// echo get_array_as_string($this->get_data_arr(), '<br/>');
			for ( $i = 0; $i < count( $this->get_data_arr() ); $i++ ) {
				switch ( $whot ) {
					case "main_tab":
					case "shop_data_tab":
					case "tags_settings_tab":
					case "filtration_tab":
						if ( $this->get_data_arr()[ $i ]['tab'] === $whot ) {
							$arr = $this->get_data_arr()[ $i ]['data'];
							$arr['opt_name'] = $this->get_data_arr()[ $i ]['opt_name'];
							$arr['tab'] = $this->get_data_arr()[ $i ]['tab'];
							$arr['type'] = $this->get_data_arr()[ $i ]['type'];
							$res_arr[] = $arr;
						}
						break;
					case "wp_list_table":
						if ( $this->get_data_arr()[ $i ]['tab'] === $whot ) {
							$arr = $this->get_data_arr()[ $i ];
							$res_arr[] = $arr;
						}
						break;
					default:
						if ( $this->get_data_arr()[ $i ]['tab'] === $whot ) {
							$arr = $this->get_data_arr()[ $i ]['data'];
							$arr['opt_name'] = $this->get_data_arr()[ $i ]['opt_name'];
							$arr['tab'] = $this->get_data_arr()[ $i ]['tab'];
							$arr['type'] = $this->get_data_arr()[ $i ]['type'];
							$res_arr[] = $arr;
						}
				}
			}
			// echo get_array_as_string($res_arr, '<br/>');
			return $res_arr;
		} else {
			return $res_arr;
		}
	}

	/**
	 * Get plugin options name
	 * 
	 * @param string $whot
	 * 
	 * @return array	Example: array([0] => opt_key1, [1] => opt_key2, ...)
	 */
	public function get_opts_name( $whot = '' ) {
		$res_arr = [];
		if ( ! empty( $this->get_data_arr() ) ) {
			for ( $i = 0; $i < count( $this->get_data_arr() ); $i++ ) {
				switch ( $whot ) {
					case "public":
						if ( $this->get_data_arr()[ $i ]['mark'] === 'public' ) {
							$res_arr[] = $this->get_data_arr()[ $i ]['opt_name'];
						}
						break;
					case "private":
						if ( $this->get_data_arr()[ $i ]['mark'] === 'private' ) {
							$res_arr[] = $this->get_data_arr()[ $i ]['opt_name'];
						}
						break;
					default:
						$res_arr[] = $this->get_data_arr()[ $i ]['opt_name'];
				}
			}
			return $res_arr;
		} else {
			return $res_arr;
		}
	}

	/**
	 * Get plugin options name and default date (array)
	 * 
	 * @param string $whot
	 * 
	 * @return array	Example: array(opt_name1 => opt_val1, opt_name2 => opt_val2, ...)
	 */
	public function get_opts_name_and_def_date( $whot = 'all' ) {
		$res_arr = [];
		if ( ! empty( $this->get_data_arr() ) ) {
			for ( $i = 0; $i < count( $this->get_data_arr() ); $i++ ) {
				switch ( $whot ) {
					case "public":
						if ( $this->get_data_arr()[ $i ]['mark'] === 'public' ) {
							$res_arr[ $this->get_data_arr()[ $i ]['opt_name'] ] = $this->get_data_arr()[ $i ]['def_val'];
						}
						break;
					case "private":
						if ( $this->get_data_arr()[ $i ]['mark'] === 'private' ) {
							$res_arr[ $this->get_data_arr()[ $i ]['opt_name'] ] = $this->get_data_arr()[ $i ]['def_val'];
						}
						break;
					default:
						$res_arr[ $this->get_data_arr()[ $i ]['opt_name'] ] = $this->get_data_arr()[ $i ]['def_val'];
				}
			}
			return $res_arr;
		} else {
			return $res_arr;
		}
	}

	/**
	 * Get plugin options name and default date (stdClass object)
	 * 
	 * @param string $whot
	 * 
	 * @return array<stdClass>
	 */
	public function get_opts_name_and_def_date_obj( $whot = 'all' ) {
		$source_arr = $this->get_opts_name_and_def_date( $whot );

		$res_arr = [];
		foreach ( $source_arr as $key => $value ) {
			$obj = new stdClass();
			$obj->name = $key;
			$obj->opt_def_value = $value;
			$res_arr[] = $obj; // unit obj
			unset( $obj );
		}
		return $res_arr;
	}
}