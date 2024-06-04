<?php
/**
 * The abstract class for getting the XML-code or skip reasons
 *
 * @package                 YML for Yandex Market
 * @subpackage              
 * @since                   0.1.0
 * 
 * @version                 4.0.9 (07-10-2023)
 * @author                  Maxim Glazunov
 * @link                    https://icopydoc.ru/
 * @see                     
 *
 * @param      array        $args_arr - Required
 * 
 * @depends                 classes:    YFYM_Error_Log
 *                          traits:     YFYM_T_Get_Post_Id
 *                                      YFYM_T_Get_Feed_Id;
 *                                      YFYM_T_Get_Product
 *                                      YFYM_T_Get_Skip_Reasons_Arr
 *                          methods:    
 *                          functions:  common_option_get
 *                          constants:  
 *                          options:    
 */
defined( 'ABSPATH' ) || exit;

abstract class YFYM_Get_Unit_Offer {
	const RULES_LIST = [ 
		'direct' => [ 
			'offer_tag', 'store', 'pickup', 'delivery', 'name', 'vendor', 'vendorcode', 'description', 'picture',
			'video', 'sales_notes', 'manufacturer_warranty', 'country_of_origin', 'age', 'url', 'categoryid'
		],
		'adv' => [ 
			'offer_tag', 'disabled', 'params', 'name', 'enable_auto_discounts', 'description', 'picture', 'url',
			'count', 'amount', 'barcode', 'weight', 'dimensions', 'expiry', 'age', 'downloadable', 'sales_notes',
			'manufacturer_warranty', 'vendor', 'model', 'vendorcode', 'store', 'pickup', 'delivery', 'categoryid',
			'vat', 'delivery_options', 'pickup_options', 'condition', 'credit_template', 'supplier', 'min_quantity',
			'step_quantity'
		],
		'single_catalog' => [ 
			'offer_tag', 'disabled', 'params', 'name', 'enable_auto_discounts', 'description', 'picture', 'url',
			'count', 'barcode', 'weight', 'dimensions', 'expiry', 'period_of_validity_days', 'age', 'downloadable',
			'country_of_origin', 'manufacturer', 'market_sku', 'tn_ved_codes', 'recommend_stock_data',
			'manufacturer_warranty', 'vendor', 'shop_sku', 'vendorcode', 'store', 'pickup', 'delivery', 'categoryid',
			'vat', 'delivery_options', 'pickup_options', 'condition', 'credit_template', 'supplier', 'min_quantity',
			'step_quantity'
		],
		'dbs' => [ 
			'offer_tag', 'disabled', 'params', 'name', 'enable_auto_discounts', 'description', 'picture', 'url',
			'count', 'amount', 'barcode', 'weight', 'dimensions', 'expiry', 'age', 'downloadable', 'sales_notes',
			'country_of_origin', 'manufacturer_warranty', 'model', 'vendor', 'vendorcode', 'store', 'pickup',
			'delivery', 'categoryid', 'vat', 'cargo_types', 'delivery_options', 'pickup_options', 'condition',
			'credit_template', 'supplier', 'min_quantity', 'step_quantity'
		],
		'sales_terms' => [ 
			'offer_tag', 'disabled', 'enable_auto_discounts', 'vat', 'delivery', 'pickup', 'delivery_options',
			'pickup_options', 'store', 'count'
		],
		'sbermegamarket' => [ 
			'offer_tag', 'disabled', 'age', 'amount', 'barcode', 'categoryid', 'condition', 'count', 'country_of_origin',
			'credit_template', 'delivery', 'description', 'dimensions', 'downloadable', 'enable_auto_discounts',
			'expiry', 'instock', 'keywords', 'manufacturer_warranty', 'manufacturer', 'market_sku', 'min_quantity',
			'model', 'name', 'params', 'period_of_validity_days', 'pickup_options', 'pickup', 'picture', 'premium_price',
			'recommend_stock_data', 'sales_notes', 'shop_sku', 'step_quantity', 'store', 'supplier', 'tn_ved_codes',
			'url', 'vat', 'vendor', 'vendorcode', 'weight'
		],
		'vk' => [ 
			'offer_tag', 'disabled', 'age', 'barcode', 'categoryid', 'condition', 'count', 'country_of_origin',
			'delivery', 'description', 'dimensions', 'downloadable', 'expiry', 'instock', 'manufacturer_warranty',
			'manufacturer', 'model', 'name', 'params', 'period_of_validity_days', 'pickup_options', 'pickup', 'picture',
			'sales_notes', 'shop_sku', 'store', 'tn_ved_codes', 'url', 'vendor', 'vendorcode', 'weight'
		],
		'all_elements' => [ 
			'offer_tag', 'disabled', 'age', 'amount', 'barcode', 'categoryid', 'condition', 'count', 'country_of_origin',
			'credit_template', 'delivery_options', 'delivery', 'description', 'dimensions', 'downloadable',
			'enable_auto_discounts', 'expiry', 'instock', 'keywords', 'manufacturer_warranty', 'manufacturer',
			'market_sku', 'min_quantity', 'model', 'name', 'outlets', 'params', 'period_of_validity_days',
			'pickup_options', 'pickup', 'picture', 'premium_price', 'recommend_stock_data', 'sales_notes',
			'shop_sku', 'step_quantity', 'store', 'supplier', 'tn_ved_codes', 'url', 'vat', 'cargo_types', 'vendor',
			'vendorcode', 'video', 'weight', 'price_rrp'
		],
		'ozon' => [ 
			'offer_tag', 'min_price', 'outlets', 'disabled', 'name', 'url', 'premium_price',
			'count', 'amount', 'categoryid', 'market_sku'
		],
		'yandex_webmaster' => [ 
			'offer_tag', 'disabled', 'barcode', 'categoryid', 'condition', 'country_of_origin', 'credit_template',
			'delivery_options', 'delivery', 'description', 'dimensions', 'instock', 'keywords', 'manufacturer',
			'market_sku', 'min_quantity', 'model', 'name', 'params', 'period_of_validity_days', 'pickup_options',
			'pickup', 'picture', 'recommend_stock_data', 'sales_notes', 'shop_sku', 'step_quantity', 'tn_ved_codes',
			'url', 'cargo_types', 'vendor', 'vendorcode', 'weight'
		],
		'products_and_offers' => [ 
			'offer_tag', 'disabled', 'barcode', 'categoryid', 'condition', 'credit_template', 'delivery_options',
			'delivery', 'description', 'dimensions', 'instock', 'keywords', 'manufacturer',
			'market_sku', 'min_quantity', 'model', 'name', 'params', 'period_of_validity_days',
			'picture', 'recommend_stock_data', 'sales_notes', 'shop_sku', 'step_quantity', 'tn_ved_codes',
			'url', 'cargo_types', 'vendor', 'vendorcode', 'weight'
		],
	];

	use YFYM_T_Get_Feed_Id;
	use YFYM_T_Get_Product;
	use YFYM_T_Get_Skip_Reasons_Arr;

	/**
	 * Summary of feed_price
	 * @var 
	 */
	public $feed_price;
	/**
	 * Summary of input_data_arr
	 * @var array
	 */
	protected $input_data_arr; // массив, который пришёл в класс. Этот массив используется в фильтрах трейтов
	/**
	 * Summary of offer
	 * @var object
	 */
	protected $offer = null;
	/**
	 * Summary of variation_count
	 * @var int
	 */
	protected $variation_count = null;
	/**
	 * Summary of variations_arr
	 * @var array
	 */
	protected $variations_arr = null;

	/**
	 * Summary of result_product_xml
	 * @var string
	 */
	protected $result_product_xml;
	/**
	 * Summary of do_empty_product_xml
	 * @var bool
	 */
	protected $do_empty_product_xml = false;

	/**
	 * @param array $args_arr [
	 *	'feed_id' 			- string - Required
	 *	'product' 			- object - Required
	 *	'offer' 			- object - Optional
	 *	'variation_count' 	- int - Optional
	 * ]
	 */
	public function __construct( $args_arr ) {
		// без этого не будет работать вне адмники is_plugin_active
		include_once ABSPATH . 'wp-admin/includes/plugin.php';

		$this->input_data_arr = $args_arr;
		$this->feed_id = (string) $args_arr['feed_id'];
		$this->product = $args_arr['product'];

		if ( isset( $args_arr['offer'] ) ) {
			$this->offer = $args_arr['offer'];
		}
		if ( isset( $args_arr['variation_count'] ) ) {
			$this->variation_count = $args_arr['variation_count'];
		} else {
			$this->variation_count = null;
		}

		$r = $this->generation_product_xml();

		// если нет нужды пропускать
		if ( empty( $this->get_skip_reasons_arr() ) ) {
			$this->result_product_xml = $r;
		} else {
			// !!! - тут нужно ещё раз подумать и проверить
			// с простыми товарами всё чётко
			$this->result_product_xml = '';
			if ( null == $this->get_offer() ) { // если прстой товар - всё чётко
				$this->set_do_empty_product_xml( true );
			} else {
				// если у нас вариативный товар, то как быть, если все вариации пропущены
				// мы то возвращаем false (см ниже), возможно надо ещё вести учёт вариций
				// также см функцию set_result() в классе class-yfym-get-unit.php
				$this->set_do_empty_product_xml( false );
			}
		}
	}

	/**
	 * Summary of generation_product_xml
	 * 
	 * @return string
	 */
	abstract public function generation_product_xml();

	/**
	 * Summary of get_product_xml
	 * 
	 * @return string
	 */
	public function get_product_xml() {
		return $this->result_product_xml;
	}

	/**
	 * Summary of set_do_empty_product_xml
	 * 
	 * @param mixed $v
	 * 
	 * @return void
	 */
	public function set_do_empty_product_xml( $v ) {
		$this->do_empty_product_xml = $v;
	}

	/**
	 * Summary of get_do_empty_product_xml
	 * 
	 * @return bool|mixed
	 */
	public function get_do_empty_product_xml() {
		return $this->do_empty_product_xml;
	}

	/**
	 * Summary of get_feed_price
	 * 
	 * @return mixed
	 */
	public function get_feed_price() {
		return $this->feed_price;
	}

	/**
	 * Summary of add_skip_reason
	 * 
	 * @param array $reason
	 * 
	 * @return void
	 */
	protected function add_skip_reason( $reason ) {
		if ( isset( $reason['offer_id'] ) ) {
			$reason_string = sprintf(
				'FEED № %1$s; Вариация товара (post_id = %2$s, offer_id = %3$s) пропущена. Причина: %4$s; Файл: %5$s; Строка: %6$s',
				$this->feed_id, $reason['post_id'], $reason['offer_id'], $reason['reason'], $reason['file'], $reason['line']
			);
		} else {
			$reason_string = sprintf(
				'FEED № %1$s; Товар с postId = %2$s пропущен. Причина: %3$s; Файл: %4$s; Строка: %5$s',
				$this->feed_id, $reason['post_id'], $reason['reason'], $reason['file'], $reason['line']
			);
		}
		$this->set_skip_reasons_arr( $reason_string );
		new YFYM_Error_Log( $reason_string );
	}

	/**
	 * Summary of get_input_data_arr
	 * 
	 * @return array
	 */
	protected function get_input_data_arr() {
		return $this->input_data_arr;
	}

	/**
	 * Summary of get_offer
	 * @return object
	 */
	protected function get_offer() {
		return $this->offer;
	}

	/**
	 * Summary of get_tags
	 * 
	 * @param string $rules
	 * @param string $result_xml
	 * 
	 * @return string
	 */
	protected function get_tags( $rules, $result_xml = '' ) {
		if ( isset( self::RULES_LIST[ $rules ] ) ) {
			for ( $i = 0; $i < count( self::RULES_LIST[ $rules ] ); $i++ ) {
				$func_name = 'get_' . self::RULES_LIST[ $rules ][ $i ];
				$result_xml .= $this->$func_name();
			}
		}
		if ( class_exists( 'WOOCS' ) ) {
			$yfym_wooc_currencies = common_option_get( 'yfym_wooc_currencies', false, $this->get_feed_id(), 'yfym' );
			if ( $yfym_wooc_currencies !== '' ) {
				global $WOOCS;
				$WOOCS->set_currency( $yfym_wooc_currencies );
			}
		}

		$p = $this->get_price();
		if ( $p !== '' ) {
			$result_xml .= $p;
			$result_xml .= $this->get_currencyid();
		}
		if ( class_exists( 'WOOCS' ) ) {
			global $WOOCS;
			$WOOCS->reset_currency();
		}
		return $result_xml;
	}
}