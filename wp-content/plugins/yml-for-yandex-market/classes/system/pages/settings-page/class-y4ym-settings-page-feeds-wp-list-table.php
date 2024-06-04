<?php
/**
 * The this class manages the list of feeds
 *
 * @package                 iCopyDoc Plugins (v1, core 16-08-2023)
 * @subpackage              YML for Yandex Market
 * @since                   0.1.0
 * 
 * @version                 4.0.0 (29-08-2023)
 * @author                  Maxim Glazunov
 * @link                    https://icopydoc.ru/
 * @see                     https://2web-master.ru/wp_list_table-%E2%80%93-poshagovoe-rukovodstvo.html 
 *                          https://wp-kama.ru/function/wp_list_table
 * 
 * @param      	
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
class Y4YM_Settings_Page_Feeds_WP_List_Table extends WP_List_Table {
	function __construct() {
		global $status, $page;
		parent::__construct( [ 
			// По умолчанию: '' ($this->screen->base);
			// Название для множественного числа, используется во всяких заголовках, например в css классах,
			// в заметках, например 'posts', тогда 'posts' будет добавлен в класс table
			'plural' => '',

			// По умолчанию: ''; Название для единственного числа, например 'post'. 
			'singular' => '',

			// По умолчанию: false; Должна ли поддерживать таблица AJAX. Если true, класс будет вызывать метод 
			// _js_vars() в подвале, чтобы передать нужные переменные любому скрипту обрабатывающему AJAX события.
			'ajax' => false,

			// По умолчанию: null; Строка содержащая название хука, нужного для определения текущей страницы. 
			// Если null, то будет установлен текущий экран.
			'screen' => null,
		] );

		add_action( 'admin_footer', [ $this, 'print_style_footer' ] ); // меняем ширину колонок
	}

	public function print_html_form() { ?>
		<form method="get">
			<?php wp_nonce_field( 'yfym_nonce_action_f', 'yfym_nonce_field_f' ); ?>
			<input type="hidden" name="page" value="<?php echo esc_attr( $_REQUEST['page'] ); ?>" />
			<input type="hidden" name="yfym_form_id" value="yfym_wp_list_table" />
			<?php
			$this->prepare_items();
			$this->display();
			?>
		</form>
		<?php
	}

	/**	
	 * Сейчас у таблицы стандартные стили WordPress. Чтобы это исправить, вам нужно адаптировать классы CSS, которые
	 * были автоматически применены к каждому столбцу. Название класса состоит из строки «column-» и ключевого имени 
	 * массива $columns, например «column-isbn» или «column-author».
	 * В качестве примера мы переопределим ширину столбцов (для простоты, стили прописаны непосредственно 
	 * в HTML разделе head)
	 */
	public function print_style_footer() {
		print( '<style type="text/css">#yfym_feed_id, .column-yfym_feed_id {width: 7%;}</style>' );
	}

	/**	
	 * Метод get_columns() необходим для маркировки столбцов внизу и вверху таблицы. 
	 * Ключи в массиве должны быть теми же, что и в массиве данных, 
	 * иначе соответствующие столбцы не будут отображены.
	 */
	function get_columns() {
		$columns = [ 
			'cb' => '<input type="checkbox" />',
			'yfym_feed_id' => __( 'Feed ID', 'yml-for-yandex-market' ),
			'yfym_url_xml_file' => __( 'YML File', 'yml-for-yandex-market' ),
			'yfym_run_cron' => __( 'Automatic file creation', 'yml-for-yandex-market' ),
			'yfym_step_export' => __( 'Step of export', 'yml-for-yandex-market' ),
			'yfym_date_sborki_end' => __( 'Generated', 'yml-for-yandex-market' ),
			'yfym_count_products_in_feed' => __( 'Products', 'yml-for-yandex-market' )
		];
		return $columns;
	}
	/**	
	 * Метод вытаскивает из БД данные, которые будут лежать в таблице
	 * $this->table_data();
	 */
	private function table_data() {
		$yfym_settings_arr = common_option_get( 'yfym_settings_arr' );
		$result_arr = [];
		if ( $yfym_settings_arr == '' || empty( $yfym_settings_arr ) ) {
			return $result_arr;
		}
		$yfym_settings_arr_keys_arr = array_keys( $yfym_settings_arr );
		for ( $i = 0; $i < count( $yfym_settings_arr_keys_arr ); $i++ ) {
			$key = $yfym_settings_arr_keys_arr[ $i ];

			$text_column_yfym_feed_id = $key;

			if ( $yfym_settings_arr[ $key ]['yfym_file_url'] === '' ) {
				$text_column_yfym_url_xml_file = __( 'Not created yet', 'yml-for-yandex-market' );
			} else {
				$text_column_yfym_url_xml_file = sprintf( '<a target="_blank" href="%1$s">%1$s</a>',
					urldecode( $yfym_settings_arr[ $key ]['yfym_file_url'] )
				);
			}
			if ( $yfym_settings_arr[ $key ]['yfym_feed_assignment'] === '' ) {

			} else {
				$text_column_yfym_url_xml_file = sprintf( '%1$s<br/>(%2$s: %3$s)',
					$text_column_yfym_url_xml_file,
					__( 'Feed assignment', 'yml-for-yandex-market' ),
					$yfym_settings_arr[ $key ]['yfym_feed_assignment']
				);
			}

			$yfym_status_cron = $yfym_settings_arr[ $key ]['yfym_status_cron'];
			switch ( $yfym_status_cron ) {
				case 'off':
					$text_column_yfym_run_cron = __( 'Off', 'yml-for-yandex-market' );
					break;
				case 'five_min':
					$text_column_yfym_run_cron = __( 'Every five minutes', 'yml-for-yandex-market' );
					break;
				case 'hourly':
					$text_column_yfym_run_cron = __( 'Hourly', 'yml-for-yandex-market' );
					break;
				case 'six_hours':
					$text_column_yfym_run_cron = __( 'Every six hours', 'yml-for-yandex-market' );
					break;
				case 'twicedaily':
					$text_column_yfym_run_cron = __( 'Twice a day', 'yml-for-yandex-market' );
					break;
				case 'daily':
					$text_column_yfym_run_cron = __( 'Daily', 'yml-for-yandex-market' );
					break;
				case 'week':
					$text_column_yfym_run_cron = __( 'Once a week', 'yml-for-yandex-market' );
					break;
				default:
					$text_column_yfym_run_cron = __( "Don't start", "yml-for-yandex-market" );
			}

			if ( $yfym_settings_arr[ $key ]['yfym_date_sborki_end'] === '0000000001' ) {
				$text_date_sborki_end = '-';
			} else {
				$text_date_sborki_end = $yfym_settings_arr[ $key ]['yfym_date_sborki_end'];
			}

			if ( $yfym_settings_arr[ $key ]['yfym_count_products_in_feed'] === '-1' ) {
				$text_count_products_in_feed = '-';
			} else {
				$text_count_products_in_feed = $yfym_settings_arr[ $key ]['yfym_count_products_in_feed'];
			}

			$result_arr[ $i ] = [ 
				'yfym_feed_id' => $text_column_yfym_feed_id,
				'yfym_url_xml_file' => $text_column_yfym_url_xml_file,
				'yfym_run_cron' => $text_column_yfym_run_cron,
				'yfym_step_export' => $yfym_settings_arr[ $key ]['yfym_step_export'],
				'yfym_date_sborki_end' => $text_date_sborki_end,
				'yfym_count_products_in_feed' => $text_count_products_in_feed
			];
		}

		return $result_arr;
	}
	/**
	 * @see https://2web-master.ru/wp_list_table-%E2%80%93-poshagovoe-rukovodstvo.html#screen-options
	 * 
	 * prepare_items определяет два массива, управляющие работой таблицы:
	 * $hidden - определяет скрытые столбцы
	 * $sortable - определяет, может ли таблица быть отсортирована по этому столбцу.
	 *
	 */
	function prepare_items() {
		$columns = $this->get_columns();
		$hidden = [];
		$sortable = $this->get_sortable_columns(); // вызов сортировки
		$this->_column_headers = [ $columns, $hidden, $sortable ];
		// пагинация 
		$per_page = 5;
		$current_page = $this->get_pagenum();
		$total_items = count( $this->table_data() );
		$found_data = array_slice( $this->table_data(), ( ( $current_page - 1 ) * $per_page ), $per_page );
		$this->set_pagination_args( [ 
			'total_items' => $total_items, // Мы должны вычислить общее количество элементов
			'per_page' => $per_page // Мы должны определить, сколько элементов отображается на странице
		] );
		// end пагинация 
		$this->items = $found_data; // $this->items = $this->table_data() // Получаем данные для формирования таблицы
	}
	/**
	 * Данные таблицы.
	 * Наконец, метод назначает данные из примера на переменную представления данных класса — items.
	 * Прежде чем отобразить каждый столбец, WordPress ищет методы типа column_{key_name}, например, 
	 * function column_yfym_url_xml_file. Такой метод должен быть указан для каждого столбца. Но чтобы не создавать 
	 * эти методы для всех столбцов в отдельности, можно использовать column_default. Эта функция обработает все 
	 * столбцы, для которых не определён специальный метод:
	 */
	function column_default( $item, $column_name ) {
		switch ( $column_name ) {
			case 'yfym_feed_id':
			case 'yfym_url_xml_file':
			case 'yfym_run_cron':
			case 'yfym_step_export':
			case 'yfym_date_sborki_end':
			case 'yfym_count_products_in_feed':
				return $item[ $column_name ];
			default:
				return print_r( $item, true ); // Мы отображаем целый массив во избежание проблем
		}
	}
	/**
	 * Функция сортировки.
	 * Второй параметр в массиве значений $sortable_columns отвечает за порядок сортировки столбца. 
	 * Если значение true, столбец будет сортироваться в порядке возрастания, если значение false, столбец 
	 * сортируется в порядке убывания, или не упорядочивается. Это необходимо для маленького треугольника около 
	 * названия столбца, который указывает порядок сортировки, чтобы строки отображались в правильном направлении
	 */
	function get_sortable_columns() {
		$sortable_columns = [ 
			'yfym_url_xml_file' => [ 'yfym_url_xml_file', false ]
		];
		return $sortable_columns;
	}
	/**
	 * Действия.
	 * Эти действия появятся, если пользователь проведет курсор мыши над таблицей
	 * column_{key_name} - в данном случае для колонки yfym_url_xml_file - function column_yfym_url_xml_file
	 */
	function column_yfym_url_xml_file( $item ) {
		$actions = [ 
			'edit' => sprintf( '<a href="?page=%s&action=%s&feed_id=%s">%s</a>',
				$_REQUEST['page'],
				'edit',
				$item['yfym_feed_id'],
				__( 'Edit', 'yml-for-yandex-market' )
			),
			'duplicate' => sprintf( '<a href="?page=%s&action=%s&feed_id=%s&_wpnonce=%s">%s</a>',
				$_REQUEST['page'],
				'duplicate',
				$item['yfym_feed_id'],
				wp_create_nonce( 'nonce_duplicate' . $item['yfym_feed_id'] ),
				__( 'Duplicate', 'yml-for-yandex-market' )
			)
		];

		return sprintf( '%1$s %2$s', $item['yfym_url_xml_file'], $this->row_actions( $actions ) );
	}
	/**
	 * Массовые действия.
	 * Bulk action осуществляются посредством переписывания метода get_bulk_actions() и возврата связанного массива
	 * Этот код просто помещает выпадающее меню и кнопку «применить» вверху и внизу таблицы
	 * ВАЖНО! Чтобы работало нужно оборачивать вызов класса в form:
	 * <form id="events-filter" method="get"> 
	 * <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" /> 
	 * <?php $wp_list_table->display(); ?> 
	 * </form> 
	 */
	function get_bulk_actions() {
		$actions = [ 
			'delete' => __( 'Delete', 'yml-for-yandex-market' )
		];
		return $actions;
	}
	// Флажки для строк должны быть определены отдельно. Как упоминалось выше, есть метод column_{column} для 
	// отображения столбца. cb-столбец – особый случай:
	function column_cb( $item ) {
		return sprintf(
			'<input type="checkbox" name="checkbox_xml_file[]" value="%s" />', $item['yfym_feed_id']
		);
	}

	/**
	 * Нет элементов.
	 * Если в списке нет никаких элементов, отображается стандартное сообщение «No items found.». Если вы хотите 
	 * изменить это сообщение, вы можете переписать метод no_items():
	 */
	function no_items() {
		_e( 'No XML feed found', 'yml-for-yandex-market' );
	}
}