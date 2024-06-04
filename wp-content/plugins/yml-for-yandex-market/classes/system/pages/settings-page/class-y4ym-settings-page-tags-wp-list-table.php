<?php
/**
 * This class manages a list of tag settings
 *
 * @package                 iCopyDoc Plugins (v1, core 16-08-2023)
 * @subpackage              YML for Yandex Market
 * @since                   0.1.0
 * 
 * @version                 4.0.2 (01-09-2023)
 * @author                  Maxim Glazunov
 * @link                    https://icopydoc.ru/
 * @see                     https://2web-master.ru/wp_list_table-%E2%80%93-poshagovoe-rukovodstvo.html 
 *                          https://wp-kama.ru/function/wp_list_table
 * 
 * @param      $feed_id     $feed_id
 *
 * @depends                 classes:    WP_List_Table
 *                                      Y4YM_Data_Arr
 *                          traits:     
 *                          methods:    
 *                          functions:  common_option_get 
 *                                      yfym_optionGET
 *                          constants:  
 *                          options:    
 */
defined( 'ABSPATH' ) || exit;
class Y4YM_Settings_Page_Tags_WP_List_Table extends WP_List_Table {
	private $feed_id;
	private $rules;

	/**
	 * Summary of __construct
	 * 
	 * @param mixed $feed_id
	 */
	function __construct( $feed_id ) {
		$this->feed_id = (string) $feed_id;
		$this->rules = common_option_get( 'yfym_yml_rules', false, $feed_id, 'yfym' );

		global $status, $page;
		parent::__construct( [ 
			'plural' => '', // По умолчанию: '' ($this->screen->base);
			// Название для множественного числа, используется во всяких 
			// заголовках, например в css классах, в заметках, например 'posts', тогда 'posts' будет добавлен в 
			// класс table.

			'singular' => '', // По умолчанию: ''; 
			// Название для единственного числа, например 'post'.

			'ajax' => false, // По умолчанию: false; 
			// Должна ли поддерживать таблица AJAX. Если true, класс будет вызывать метод 
			// _js_vars() в подвале, чтобы передать нужные переменные любому скрипту обрабатывающему AJAX события.

			'screen' => null, // По умолчанию: null; 
			// Строка содержащая название хука, нужного для определения текущей страницы. 
			// Если null, то будет установлен текущий экран. 
		] );
	}

	/**
	 * Метод get_columns() необходим для маркировки столбцов внизу и вверху таблицы. 
	 * Ключи в массиве должны быть теми же, что и в массиве данных, 
	 * иначе соответствующие столбцы не будут отображены.
	 */
	function get_columns() {
		$columns = [ 
			'yfym_attr_name' => __( 'Attribute', 'yml-for-yandex-market' ),
			'yfym_attr_desc' => __( 'Attribute description', 'yml-for-yandex-market' ),
			'yfym_attr_val' => __( 'Value', 'yml-for-yandex-market' ),
			'yfym_def_val' => __( 'Default value', 'yml-for-yandex-market' ),
		];
		return $columns;
	}

	/**
	 * Summary of attr_name_mask
	 * 
	 * @param string $desc - Required
	 * @param string $tag - Required
	 * @param array $rules_arr - Optional
	 * 
	 * @return string
	 */
	private function attr_name_mask( $desc, $tag, $rules_arr = [] ) {
		$color = 'black';
		if ( ! empty( $tag ) ) {
			$tag = '[' . $tag . ']';
		}
		return sprintf( '<span class="y4ym_bold" style="color: %3$s;">%1$s</span><br/>%2$s',
			$desc,
			$tag,
			$color
		);
	}

	/**
	 * Метод вытаскивает из БД данные, которые будут лежать в таблице
	 * $this->table_data();
	 * 
	 * @return array
	 */
	private function table_data() {
		$result_arr = [];

		$data_arr_obj = new Y4YM_Data_Arr();
		$attr_arr = $data_arr_obj->get_data_for_tabs( 'wp_list_table' );

		for ( $i = 0; $i < count( $attr_arr ); $i++ ) {
			if ( $attr_arr[ $i ]['tab'] === 'wp_list_table' ) {
				$r_arr = [];
				$r_arr['yfym_attr_name'] = $this->attr_name_mask(
					$attr_arr[ $i ]['data']['label'],
					$attr_arr[ $i ]['data']['tag_name'],
					$attr_arr[ $i ]['data']['rules']
				);
				$r_arr['yfym_attr_desc'] = $attr_arr[ $i ]['data']['desc'];

				if ( $attr_arr[ $i ]['type'] === 'select' ) {
					$attr_val = $this->get_view_html_field_select( $attr_arr[ $i ] );
				} else if ( $attr_arr[ $i ]['type'] === 'text' ) {
					$attr_val = $this->get_view_html_field_input( $attr_arr[ $i ] );
				}
				$r_arr['yfym_attr_val'] = $attr_val;

				if ( true === $attr_arr[ $i ]['data']['default_value'] ) {
					$i++;
					if ( $attr_arr[ $i ]['type'] === 'text' ) {
						$r_arr['yfym_def_val'] = $this->get_view_html_field_input( $attr_arr[ $i ] );
					}
				} else {
					$r_arr['yfym_def_val'] = __( 'There are no default settings', 'yml-for-yandex-market' );
				}

				$result_arr[] = $r_arr;
				unset( $r_arr );
			}
		}

		return $result_arr;
	}

	/**
	 * Summary of get_view_html_field_input
	 * 
	 * @param array $data_arr
	 * 
	 * @return string
	 */
	private function get_view_html_field_input( $data_arr ) {
		return sprintf( '<input 
					type="text" 
					name="%1$s" 
					id="%1$s" 
					value="%2$s"
					placeholder="%3$s" /><br />',
			esc_attr( $data_arr['opt_name'] ),
			esc_attr( common_option_get( $data_arr['opt_name'], false, $this->get_feed_id(), 'yfym' ) ),
			esc_html( $data_arr['data']['placeholder'] )
		);
	}

	/**
	 * Summary of get_view_html_field_select
	 * 
	 * @param array $data_arr
	 * 
	 * @return string
	 */
	private function get_view_html_field_select( $data_arr ) {
		if ( isset( $data_arr['data']['key_value_arr'] ) ) {
			$key_value_arr = $data_arr['data']['key_value_arr'];
		} else {
			$key_value_arr = [];
		}

		// массивы храним отдельно от других параметров
		if ( isset( $data_arr['data']['multiple'] ) && true === $data_arr['data']['multiple'] ) {
			$multiple = true;
			$multiple_val = '[]" multiple';
			$value = unserialize( yfym_optionGET( $data_arr['opt_name'], $this->get_feed_id() ) );
		} else {
			$multiple = false;
			$multiple_val = '"';
			$value = common_option_get(
				$data_arr['opt_name'],
				false,
				$this->get_feed_id(),
				'yfym' );
		}

		return sprintf( '<select name="%1$s%3$s id="%1$s" />%2$s</select>',
			esc_attr( $data_arr['opt_name'] ),
			$this->print_view_html_option_for_select(
				$value,
				false,
				[ 
					'woo_attr' => $data_arr['data']['woo_attr'],
					'key_value_arr' => $key_value_arr,
					'multiple' => $multiple
				]
			),
			$multiple_val
		);
	}

	/**
	 * Summary of print_view_html_option_for_select
	 * 
	 * @param mixed $opt_value - Required
	 * @param mixed $opt_name - Optional
	 * @param array $params_arr - Optional
	 * @param mixed $res - Optional
	 * 
	 * @return string
	 */
	private function print_view_html_option_for_select( $opt_value, $opt_name = false, $params_arr = [], $res = '' ) {
		if ( ! empty( $params_arr['key_value_arr'] ) ) {
			for ( $i = 0; $i < count( $params_arr['key_value_arr'] ); $i++ ) {
				$res .= sprintf( '<option value="%1$s" %2$s>%3$s</option>' . PHP_EOL,
					esc_attr( $params_arr['key_value_arr'][ $i ]['value'] ),
					esc_attr( selected( $opt_value, $params_arr['key_value_arr'][ $i ]['value'], false ) ),
					esc_attr( $params_arr['key_value_arr'][ $i ]['text'] )
				);
			}
		}

		if ( isset( $params_arr['brands'] ) ) {
			if ( is_plugin_active( 'perfect-woocommerce-brands/perfect-woocommerce-brands.php' )
				|| is_plugin_active( 'perfect-woocommerce-brands/main.php' )
				|| class_exists( 'Perfect_Woocommerce_Brands' ) ) {
				$res .= sprintf( '<option value="sfpwb" %s>%s Perfect Woocommerce Brands</option>',
					selected( $opt_value, 'sfpwb', false ),
					__( 'Substitute from', 'yml-for-yandex-market' )
				);
			}
			if ( is_plugin_active( 'premmerce-woocommerce-brands/premmerce-brands.php' ) ) {
				$res .= sprintf( '<option value="premmercebrandsplugin" %s>%s %s</option>',
					selected( $opt_value, 'premmercebrandsplugin', false ),
					__( 'Substitute from', 'yml-for-yandex-market' ),
					'Premmerce Brands for WooCommerce'
				);
			}
			if ( is_plugin_active( 'woocommerce-brands/woocommerce-brands.php' ) ) {
				$res .= sprintf( '<option value="woocommerce_brands" %s>%s %s</option>',
					selected( $opt_value, 'woocommerce_brands', false ),
					__( 'Substitute from', 'yml-for-yandex-market' ),
					'WooCommerce Brands'
				);
			}
			if ( class_exists( 'woo_brands' ) ) {
				$res .= sprintf( '<option value="woo_brands" %s>%s %s</option>',
					selected( $opt_value, 'woo_brands', false ),
					__( 'Substitute from', 'yml-for-yandex-market' ),
					'Woocomerce Brands Pro'
				);
			}
			if ( is_plugin_active( 'yith-woocommerce-brands-add-on/init.php' )
				|| is_plugin_active( 'perfect-woocommerce-brands/main.php' )
				|| class_exists( 'Perfect_Woocommerce_Brands' ) ) {
				$res .= sprintf( '<option value="yith_woocommerce_brands_add_on" %s>%s %s</option>',
					selected( $opt_value, 'yith_woocommerce_brands_add_on', false ),
					__( 'Substitute from', 'yml-for-yandex-market' ),
					'YITH WooCommerce Brands Add-On'
				);
			}
		}

		if ( ! empty( $params_arr['woo_attr'] ) ) {
			if ( true === $params_arr['multiple'] ) {
				$woo_attributes_arr = get_woo_attributes();
				foreach ( $woo_attributes_arr as $attribute ) {
					if ( ! empty( $opt_value ) ) {
						foreach ( $opt_value as $value ) {
							if ( (string) $attribute['id'] == (string) $value ) {
								$selected = ' selected="select" ';
								break;
							} else {
								$selected = '';
							}
						}
					} else {
						$selected = '';
					}
					$res .= sprintf( '<option value="%1$s" %2$s>%3$s</option>' . PHP_EOL,
						esc_attr( $attribute['id'] ),
						$selected,
						esc_attr( $attribute['name'] )
					);
				}
				unset( $woo_attributes_arr );
			} else {
				$woo_attributes_arr = get_woo_attributes();
				for ( $i = 0; $i < count( $woo_attributes_arr ); $i++ ) {
					$res .= sprintf( '<option value="%1$s" %2$s>%3$s</option>' . PHP_EOL,
						esc_attr( $woo_attributes_arr[ $i ]['id'] ),
						esc_attr( selected( $opt_value, $woo_attributes_arr[ $i ]['id'], false ) ),
						esc_attr( $woo_attributes_arr[ $i ]['name'] )
					);
				}
				unset( $woo_attributes_arr );
			}
		}
		return $res;
	}

	/**
	 * @see	https://2web-master.ru/wp_list_table-%E2%80%93-poshagovoe-rukovodstvo.html#screen-options
	 * 
	 * prepare_items определяет два массива, управляющие работой таблицы:
	 * $hidden - определяет скрытые столбцы 
	 * $sortable - определяет, может ли таблица быть отсортирована по этому столбцу
	 *
	 * @return void
	 */
	function prepare_items() {
		$columns = $this->get_columns();
		$hidden = [];
		$sortable = $this->get_sortable_columns(); // вызов сортировки
		$this->_column_headers = [ $columns, $hidden, $sortable ];
		// блок пагинации пропущен
		$this->items = $this->table_data();
	}

	/** 
	 * Данные таблицы.
	 * Наконец, метод назначает данные из примера на переменную представления данных класса — items.
	 * Прежде чем отобразить каждый столбец, WordPress ищет методы типа column_{key_name}, например,
	 * function column_yfym_url_xml_file. 
	 * Такой метод должен быть указан для каждого столбца. Но чтобы не создавать эти методы для всех столбцов
	 * в отдельности, можно использовать column_default. Эта функция обработает все столбцы, для которых не определён
	 * специальный метод.
	 * 
	 * @param array $item - Required
	 * @param string $column_name - Required
	 * 
	 * @return mixed
	 */
	function column_default( $item, $column_name ) {
		switch ( $column_name ) {
			case 'yfym_attr_name':
			case 'yfym_attr_desc':
			case 'yfym_attr_val':
			case 'yfym_def_val':
				return $item[ $column_name ];
			default:
				return print_r( $item, true ); // Мы отображаем целый массив во избежание проблем
		}
	}

	/**
	 * Get feed ID
	 * 
	 * @return string
	 */
	private function get_feed_id() {
		return $this->feed_id;
	}
}