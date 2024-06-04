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
 * @depends                 classes:    
 *                          traits:     
 *                          methods:    
 *                          functions:  
 *                          constants:  
 */
defined( 'ABSPATH' ) || exit;

trait YFYM_T_Get_Product {
	/**
	 * Summary of product
	 * @var object
	 */
	protected $product;

	/**
	 * Get product
	 * 
	 * @return object
	 */
	protected function get_product() {
		return $this->product;
	}
}

trait YFYM_T_Get_Feed_Id {
	/**
	 * Summary of feed_id
	 * @var string
	 */
	protected $feed_id;

	/**
	 * Get feed ID
	 * 
	 * @return string
	 */
	protected function get_feed_id() {
		return $this->feed_id;
	}
}

trait YFYM_T_Get_Post_Id {
	/**
	 * Summary of post_id
	 * @var int
	 */
	protected $post_id;

	/**
	 * Summary of get_post_id
	 * @return int
	 */
	protected function get_post_id() {
		return $this->post_id;
	}
}

trait YFYM_T_Get_Skip_Reasons_Arr {
	/**
	 * Summary of skip_reasons_arr
	 * @var array
	 */
	protected $skip_reasons_arr = [];

	/**
	 * Summary of set_skip_reasons_arr
	 * 
	 * @param string $v
	 * 
	 * @return void
	 */
	public function set_skip_reasons_arr( $v ) {
		$this->skip_reasons_arr[] = $v;
	}

	/**
	 * Get product skip reasons
	 * 
	 * @return array
	 */
	public function get_skip_reasons_arr() {
		return $this->skip_reasons_arr;
	}
}