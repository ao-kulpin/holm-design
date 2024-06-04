<?php 
/**
 *  Traits Name for simple products
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
 * @depends					classes:    Get_Paired_Tag
 *                          traits:     
 *                          methods:    get_product
 *                                      get_feed_id
 *                          functions:  
 *                          constants:  
 */
defined( 'ABSPATH' ) || exit;

trait YFYM_T_Simple_Get_Name {
	/**
	 * Summary of get_name
	 * 
	 * @param string $tag_name - Optional
	 * @param string $result_xml - Optional
	 * 
	 * @return string
	 */
	public function get_name( $tag_name = 'name', $result_xml = '' ) {
		$result_yml_name = $this->get_product()->get_title(); // название товара
		$result_yml_name = apply_filters(
			'y4ym_f_simple_tag_value_name',
			$result_yml_name,
			[ 'product' => $this->get_product() ],
			$this->get_feed_id()
		);

		// TODO: Удалить в след.версиях
		$result_yml_name = apply_filters( 'yfym_change_name', $result_yml_name, $this->get_product()->get_id(), $this->get_product(), $this->get_feed_id() );

		$result_xml = new Get_Paired_Tag( $tag_name, htmlspecialchars( $result_yml_name, ENT_NOQUOTES ) );

		$result_xml = apply_filters(
			'y4ym_f_simple_tag_name',
			$result_xml,
			[ 'product' => $this->get_product() ],
			$this->get_feed_id()
		);
		return $result_xml;
	}
}