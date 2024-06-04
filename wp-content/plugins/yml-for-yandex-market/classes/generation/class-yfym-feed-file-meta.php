<?php
/**
 * Starts feed generation
 *
 * @package                 YML for Yandex Market
 * @subpackage              
 * @since                   4.0.5
 * 
 * @version                 4.0.5 (20-09-2023)
 * @author                  Maxim Glazunov
 * @link                    https://icopydoc.ru/
 * @see                     
 * 
 * @param    string|int     $feed_id - Required
 *
 * @depends                 classes:    
 *                          traits:     YFYM_T_Get_Feed_Id
 *                          methods:    
 *                          functions:  common_option_get
 *                          constants:  
 *                          options:    
 */
defined( 'ABSPATH' ) || exit;

class YFYM_Feed_File_Meta {
	use YFYM_T_Get_Feed_Id;

	/**
	 * Starts feed generation
	 * 
	 * @param string|int $feed_id - Required
	 */
	public function __construct( $feed_id ) {
		$this->feed_id = (string) $feed_id;
	}

	/**
	 * Перименовывает временный файл фида в основной
	 * 
	 * @return string
	 */
	public function get_feed_filename() {
		if ( $this->get_feed_id() == '1' ) {
			$pref_feed = '';
		} else {
			$pref_feed = $this->get_feed_id();
		}

		if ( is_multisite() ) {
			$blog_index = (string) get_current_blog_id();
		} else {
			$blog_index = '0';
		}

		$feed_name = common_option_get( 'yfym_feed_name', false, $this->get_feed_id(), 'yfym' );
		if ( empty( $feed_name ) ) {
			$file_feed_name = sprintf( '%1$sfeed-yml-%2$s', $pref_feed, $blog_index );
		} else {
			$file_feed_name = $feed_name;
		}

		return $file_feed_name;
	}

	/**
	 * Summary of get_feed_extension
	 * 
	 * @return string
	 */
	public function get_feed_extension() {
		$file_extension = common_option_get( 'yfym_file_extension', false, $this->get_feed_id(), 'yfym' );
		if ( empty( $file_extension ) ) {
			$file_extension = 'xml';
		}
		return $file_extension;
	}

	/**
	 * Summary of get_feed_full_filename
	 * 
	 * @return string
	 */
	public function get_feed_full_filename() {
		$archive_to_zip = common_option_get( 'yfym_archive_to_zip', false, $this->get_feed_id(), 'yfym' );
		if ( $archive_to_zip === 'enabled' ) {
			$file_extension = 'zip';
		} else {
			$file_extension = $this->get_feed_extension();
		}
		return sprintf( '%s.%s', $this->get_feed_filename(), $file_extension );
	}
}