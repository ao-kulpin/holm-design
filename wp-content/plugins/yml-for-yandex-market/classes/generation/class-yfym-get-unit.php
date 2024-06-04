<?php 
/**
 * The main class for getting the XML-code of the product 
 * 
 * @package                 YML for Yandex Market
 * @subpackage              
 * @since                   1.0.0
 * 
 * @version                 4.0.0 (29-08-2023)
 * @author                  Maxim Glazunov
 * @link                    https://icopydoc.ru/
 * @see                     
 * 
 * @param       string      $post_id - Required
 * @param       string      $feed_id - Required
 * 
 * @depends                 classes:    WC_Product_Variation
 *                                      YFYM_Get_Unit_Offer
 *                                      (YFYM_Get_Unit_Offer_Simple)
 *                                      (YFYM_Get_Unit_Offer_Varible)
 *                          traits:     YFYM_T_Get_Post_Id
 *                                      YFYM_T_Get_Feed_Id;
 *                                      YFYM_T_Get_Product
 *                                      YFYM_T_Get_Skip_Reasons_Arr
 *                          methods:    
 *                          functions:  yfym_optionGET
 *                          constants:  
 *                          options:    
 */
defined( 'ABSPATH' ) || exit;

class YFYM_Get_Unit {
	use YFYM_T_Get_Post_Id;
	use YFYM_T_Get_Feed_Id;
	use YFYM_T_Get_Product;
	use YFYM_T_Get_Skip_Reasons_Arr;

	/**
	 * Summary of result_xml
	 * @var string
	 */
	protected $result_xml;
	/**
	 * Summary of ids_in_xml
	* @var string
	*/
	protected $ids_in_xml = '';

	/**
	 * Summary of __construct
	 * @param string|int $post_id
	 * @param string|int $feed_id
	 */
	public function __construct( $post_id, $feed_id ) {
		$this->post_id = $post_id;
		$this->feed_id = (string) $feed_id;

		$args_arr = [ 'post_id' => $post_id, 'feed_id' => $feed_id ];

		do_action( 'before_wc_get_product', $args_arr );

		$product = wc_get_product( $post_id );

		do_action( 'after_wc_get_product', $args_arr, $product );
		$this->product = $product;
		do_action( 'after_wc_get_product_this_product', $args_arr, $product );

		$this->create_code(); // создаём код одного простого или вариативного товара и заносим в $result_xml
	}

	/**
	 * Summary of get_result
	 * 
	 * @return string
	 */
	public function get_result() {
		return $this->result_xml;
	}

	/**
	 * Summary of get_ids_in_xml
	 * 
	 * @return string
	 */
	public function get_ids_in_xml() {
		return $this->ids_in_xml;
	}

	/**
	 * Summary of create_code
	 * 
	 * @return string
	 */
	protected function create_code() {
		$product = $this->get_product();

		if ( null == $product ) {
			$this->result_xml = '';
			array_push( $this->skip_reasons_arr, __( 'There is no product with this ID', 'yml-for-yandex-market' ) );
			return $this->get_result();
		}

		if ( $product->is_type( 'variable' ) ) {
			$variations_arr = $product->get_available_variations();
			$variations_arr = apply_filters(
				'y4ym_f_variations_arr',
				$variations_arr,
				[ 
					'product' => $product
				],
				$this->get_feed_id()
			);

			$variation_count = count( $variations_arr );
			for ( $i = 0; $i < $variation_count; $i++ ) {
				$offer_id = $variations_arr[ $i ]['variation_id'];
				$offer = new WC_Product_Variation( $offer_id ); // получим вариацию

				$args_arr = [ 
					'feed_id' => $this->get_feed_id(),
					'product' => $product,
					'offer' => $offer,
					'variation_count' => $variation_count,
				];

				$offer_variable_obj = new YFYM_Get_Unit_Offer_Variable( $args_arr );
				$r = $this->set_result( $offer_variable_obj );
				if ( true === $r ) {
					$this->ids_in_xml .= sprintf( '%s;%s;%s;%s%s',
						$product->get_id(),
						$offer->get_id(),
						$offer_variable_obj->get_feed_price(),
						$offer_variable_obj->get_feed_category_id(),
						PHP_EOL
					);
				}

				$stop_flag = false;
				$stop_flag = apply_filters( // TODO: Удалить в следующих версиях этот фильтр
					'yfym_after_variable_offer_stop_flag',
					$stop_flag, $i, $variation_count, $offer->get_id(), $offer, $this->get_feed_id()
				);
				$stop_flag = apply_filters(
					'y4ym_f_after_variable_offer_stop_flag',
					$stop_flag,
					[ 
						'i' => $i,
						'variation_count' => $variation_count,
						'product' => $product,
						'offer' => $offer
					],
					$this->get_feed_id()
				);
				if ( true === $stop_flag ) {
					break;
				}
			}
		} else {
			$args_arr = [ 
				'feed_id' => $this->get_feed_id(),
				'product' => $product,
			];
			$offer_simple_obj = new YFYM_Get_Unit_Offer_Simple( $args_arr );
			$r = $this->set_result( $offer_simple_obj );
			if ( true === $r ) {
				$this->ids_in_xml .= sprintf( '%s;%s;%s;%s%s',
					$product->get_id(),
					$product->get_id(),
					$offer_simple_obj->get_feed_price(),
					$offer_simple_obj->get_feed_category_id(),
					PHP_EOL
				);
			}
		}

		return $this->get_result();
	}

	
	/**
	 * Summary of set_result
	 * 
	 * @param YFYM_Get_Unit_Offer $offer_obj // ожидается потомок класса YFYM_Get_Unit_Offer
	 * 
	 * @return bool
	 */
	protected function set_result( YFYM_Get_Unit_Offer $offer_obj ) {
		if ( ! empty( $offer_obj->get_skip_reasons_arr() ) ) {
			foreach ( $offer_obj->get_skip_reasons_arr() as $value ) {
				array_push( $this->skip_reasons_arr, $value );
			}
		}
		if ( true === $offer_obj->get_do_empty_product_xml() ) {
			$this->result_xml = '';
			return false;
		} else { // если нет причин пропускать товар
			$this->result_xml .= $offer_obj->get_product_xml();
			return true;
		}
	}
}