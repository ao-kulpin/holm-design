<?php
/**
 * Traits Dimensions for simple products
 *
 * @package                 YML for Yandex Market
 * @subpackage              
 * @since                   0.1.0
 * 
 * @version                 4.0.6 (29-09-2023)
 * @author                  Maxim Glazunov
 * @link                    https://icopydoc.ru/
 * @see                     
 *
 * @depends                 classes:    Get_Paired_Tag
 *                          traits:     
 *                          methods:    get_product
 *                                      get_feed_id
 *                          functions:  common_option_get
 *                          constants:  
 */
defined( 'ABSPATH' ) || exit;

trait YFYM_T_Simple_Get_Dimensions {
	/**
	 * Summary of get_dimensions
	 * 
	 * @param string $tag_name - Optional
	 * @param string $result_xml - Optional
	 * 
	 * @return string
	 */
	public function get_dimensions( $tag_name = 'dimensions', $result_xml = '' ) {
		// $dimensions = wc_format_dimensions( $this->get_product()->get_dimensions( false ) );
		if ( $this->get_product()->has_dimensions() ) {
			$length_yml = $this->get_product()->get_length();
			if ( ! empty( $length_yml ) ) {
				$length_yml = round( wc_get_dimension( $length_yml, 'cm' ), 3 );
			}

			$width_yml = $this->get_product()->get_width();
			if ( ! empty( $width_yml ) ) {
				$width_yml = round( wc_get_dimension( $width_yml, 'cm' ), 3 );
			}

			$height_yml = $this->get_product()->get_height();
			if ( ! empty( $height_yml ) ) {
				$height_yml = round( wc_get_dimension( $height_yml, 'cm' ), 3 );
			}

			if ( ( $length_yml > 0 ) && ( $width_yml > 0 ) && ( $height_yml > 0 ) ) {
				$result_xml = '<dimensions>' . $length_yml . '/' . $width_yml . '/' . $height_yml . '</dimensions>' . PHP_EOL;
			}
		}

		$result_xml = apply_filters(
			'y4ym_f_simple_tag_dimensions',
			$result_xml,
			[ 'product' => $this->get_product() ],
			$this->get_feed_id()
		);
		return $result_xml;
	}
}