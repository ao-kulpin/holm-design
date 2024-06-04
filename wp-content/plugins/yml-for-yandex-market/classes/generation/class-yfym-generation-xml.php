<?php
/**
 * Starts feed generation
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
 * @param    string|int     $feed_id - Required
 *
 * @depends                 classes:    YFYM_Get_Unit
 *                                      Get_Paired_Tag
 *                                      WP_Query
 *                                      ZipArchive
 *                          traits:     
 *                          methods:    
 *                          functions:  common_option_get
 *                                      common_option_upd
 *                                      yfym_optionGET
 *                                      yfym_optionUPD
 *                          constants:  YFYM_SITE_UPLOADS_DIR_PATH
 *                                      YFYM_SITE_UPLOADS_URL
 *                          options:    
 */
defined( 'ABSPATH' ) || exit;

class YFYM_Generation_XML {
	/**
	 * Prefix
	 * @var string
	 */
	private $pref = 'yfym';
	/**
	 * Feed ID
	 * @var string
	 */
	protected $feed_id;
	/**
	 * XML code
	 * @var string
	 */
	protected $result_xml = '';

	/**
	 * Starts feed generation
	 * 
	 * @param string|int $feed_id - Required
	 */
	public function __construct( $feed_id ) {
		$this->feed_id = (string) $feed_id;
	}

	/**
	 * Write file
	 * 
	 * @param string $result_xml
	 * @param string $cc
	 * 
	 * @return bool
	 */
	public function write_file( $result_xml, $cc ) {
		$filename = urldecode( common_option_get( 'yfym_file_file', false, $this->get_feed_id(), 'yfym' ) );

		if ( $this->get_feed_id() === '1' ) {
			$pref_feed = '';
		} else {
			$pref_feed = $this->get_feed_id();
		}

		if ( empty( $filename ) ) {
			$filename = YFYM_SITE_UPLOADS_DIR_PATH . "/" . $pref_feed . "feed-yml-0-tmp.xml";
		}
		if ( file_exists( $filename ) ) { // файл есть
			if ( ! $handle = fopen( $filename, $cc ) ) {
				new YFYM_Error_Log( sprintf(
					'FEED № %1$s; Не могу открыть файл %2$s; Файл: %3$s; Строка: %4$s',
					$this->get_feed_id(),
					$filename,
					'class-generation-xml.php',
					__LINE__
				) );
			}
			if ( false === fwrite( $handle, $result_xml ) ) {
				new YFYM_Error_Log( sprintf(
					'FEED № %1$s; Не могу произвести запись в файл %2$s; Файл: %3$s; Строка: %4$s',
					$this->get_feed_id(),
					$filename,
					'class-generation-xml.php',
					__LINE__
				) );
			} else {
				new YFYM_Error_Log( sprintf(
					'FEED № %1$s; Ура! Записали в файл %2$s; Файл: %3$s; Строка: %4$s',
					$this->get_feed_id(),
					$filename,
					'class-generation-xml.php',
					__LINE__
				) );
				return true;
			}
			fclose( $handle );
		} else {
			new YFYM_Error_Log( sprintf(
				'FEED № %1$s; Ура! Файла $filename = %2$s еще нет; Файл: %3$s; Строка: %4$s',
				$this->get_feed_id(),
				$filename,
				'class-generation-xml.php',
				__LINE__
			) );
			// файла еще нет. попытаемся создать
			if ( is_multisite() ) {
				$tmp_filename = $pref_feed . 'feed-yml-' . get_current_blog_id() . '-tmp.xml';
			} else {
				$tmp_filename = $pref_feed . 'feed-yml-0-tmp.xml';
			}
			// загружаем временный файл в папку загрузок
			$upload = wp_upload_bits( $tmp_filename, null, $result_xml );
			/**
			 *	для работы с csv или xml требуется в плагине разрешить загрузку таких файлов
			 *	$upload['file'] => '/var/www/wordpress/wp-content/uploads/2010/03/feed-xml.xml', // путь
			 *	$upload['url'] => 'http://site.ru/wp-content/uploads/2010/03/feed-xml.xml', // урл
			 *	$upload['error'] => false, // сюда записывается сообщение об ошибке в случае ошибки
			 */
			// проверим получилась ли запись
			if ( $upload['error'] ) {
				new YFYM_Error_Log( sprintf(
					'FEED № %1$s; Ура! Запись вызвала ошибку: %2$s; Файл: %3$s; Строка: %4$s',
					$this->get_feed_id(),
					$upload['error'],
					'class-generation-xml.php',
					__LINE__
				) );
			} else {
				new YFYM_Error_Log( sprintf(
					'FEED № %1$s; Запись удалась! Путь файла: %2$s, УРЛ файла: %3$s; Файл: %4$s; Строка: %5$s',
					$this->get_feed_id(),
					$upload['file'],
					$upload['url'],
					'class-generation-xml.php',
					__LINE__
				) );
				yfym_optionUPD( 'yfym_file_file', urlencode( $upload['file'] ), $this->get_feed_id(), 'yes', 'set_arr' );
				return true;
			}
		}
		return false;
	}

	/**
	 * Gluing cache files into a single feed
	 * 
	 * @param array $id_arr
	 * 
	 * @return void
	 */
	public function gluing( $id_arr ) {
		/**
		 * $id_arr[$i]['ID'] - ID товара
		 * $id_arr[$i]['post_modified_gmt'] - Время обновления карточки товара
		 * global $wpdb;
		 * $res = $wpdb->get_results(
		 *	"SELECT ID, post_modified_gmt FROM $wpdb->posts WHERE post_type = 'product' AND post_status = 'publish'"
		 * );	
		 */
		if ( $this->get_feed_id() === '1' ) {
			$pref_feed = '';
		} else {
			$pref_feed = $this->get_feed_id();
		}
		$name_dir = YFYM_SITE_UPLOADS_DIR_PATH . '/yfym/feed' . $this->get_feed_id();
		if ( ! is_dir( $name_dir ) ) {
			if ( ! mkdir( $name_dir ) ) {
				error_log(
					sprintf(
						'FEED № %s; ERROR: Нет папки yfym! И создать не вышло! $name_dir = %s; Файл: %s; Строка: %s',
						$this->get_feed_id(),
						$name_dir,
						'class-yfym-generation-xml.php',
						__LINE__
						, 0 )
				);
			} else {
				error_log(
					sprintf(
						'FEED № %s; Создали папку $name_dir = %s; Файл: %s; Строка: %s',
						$this->get_feed_id(),
						$name_dir,
						'class-yfym-generation-xml.php',
						__LINE__
						, 0 )
				);
			}
		}

		/** 
		 *	этот блок исправляет потенциальную проблему изменения относительных путей типа:
		 *	/home/c/canpro4d/canpro4d.beget.tech/public_html/wp-content/uploads/yfym/feed2/ids_in_xml.tmp 
		 *	/home/c/canpro4d/canpower.ru/public_html/wp-content/uploads/yfym/feed2/ids_in_xml.tmp
		 **/
		$yfym_file_ids_in_xml = urldecode( yfym_optionGET( 'yfym_file_ids_in_xml', $this->get_feed_id(), 'set_arr' ) );
		$yfym_file_ids_in_yml = urldecode( yfym_optionGET( 'yfym_file_ids_in_yml', $this->get_feed_id(), 'set_arr' ) );
		if ( empty( $yfym_file_ids_in_xml ) ||
			$yfym_file_ids_in_xml !== YFYM_PLUGIN_UPLOADS_DIR_PATH . '/feed' . $this->get_feed_id() . '/ids_in_xml.tmp'
		) { // если не указан адрес файла с id-шниками или они не равны
			$yfym_file_ids_in_xml = YFYM_PLUGIN_UPLOADS_DIR_PATH . '/feed' . $this->get_feed_id() . '/ids_in_xml.tmp';
			yfym_optionUPD( 'yfym_file_ids_in_xml', urlencode( $yfym_file_ids_in_xml ), $this->get_feed_id(), 'yes', 'set_arr' );
		}
		if ( empty( $yfym_file_ids_in_yml ) ||
			$yfym_file_ids_in_yml !== YFYM_PLUGIN_UPLOADS_DIR_PATH . '/feed' . $this->get_feed_id() . '/ids_in_yml.tmp'
		) { // если не указан адрес файла с id-шниками или они не равны
			$yfym_file_ids_in_yml = YFYM_PLUGIN_UPLOADS_DIR_PATH . '/feed' . $this->get_feed_id() . '/ids_in_yml.tmp';
			yfym_optionUPD( 'yfym_file_ids_in_yml', urlencode( $yfym_file_ids_in_yml ), $this->get_feed_id(), 'yes', 'set_arr' );
		}

		$yfym_date_save_set = yfym_optionGET( 'yfym_date_save_set', $this->get_feed_id(), 'set_arr' );
		clearstatcache(); // очищаем кэш дат файлов

		foreach ( $id_arr as $product ) {
			$filename = $name_dir . '/' . $product['ID'] . '.tmp';
			$filenameIn = $name_dir . '/' . $product['ID'] . '-in.tmp'; /* с версии 2.0.0 */
			new YFYM_Error_Log( 'FEED № ' . $this->get_feed_id() . '; RAM ' . round( memory_get_usage() / 1024, 1 ) . ' Кб. ID товара/файл = ' . $product['ID'] . '.tmp; Файл: class-yfym-generation-xml.php; Строка: ' . __LINE__ );
			if ( is_file( $filename ) && is_file( $filenameIn ) ) { // if (file_exists($filename)) {
				$last_upd_file = filemtime( $filename ); // 1318189167			
				if ( ( $last_upd_file < strtotime( $product['post_modified_gmt'] ) ) || ( $yfym_date_save_set > $last_upd_file ) ) {
					// Файл кэша обновлен раньше чем время модификации товара
					// или файл обновлен раньше чем время обновления настроек фида
					new YFYM_Error_Log( 'FEED № ' . $this->get_feed_id() . '; NOTICE: Файл кэша ' . $filename . ' обновлен РАНЬШЕ чем время модификации товара или время сохранения настроек фида! Файл: class-yfym-generation-xml.php; Строка: ' . __LINE__ );
					$result_get_unit_obj = new YFYM_Get_Unit( $product['ID'], $this->get_feed_id() );
					$result_xml = $result_get_unit_obj->get_result();
					$ids_in_xml = $result_get_unit_obj->get_ids_in_xml();

					yfym_wf( $result_xml, $product['ID'], $this->get_feed_id(), $ids_in_xml );
					new YFYM_Error_Log( 'FEED № ' . $this->get_feed_id() . '; Обновили кэш товара. Файл: class-generation-xml.php; Строка: ' . __LINE__ );
					file_put_contents( $yfym_file_ids_in_xml, $ids_in_xml, FILE_APPEND );
				} else {
					// Файл кэша обновлен позже чем время модификации товара
					// или файл обновлен позже чем время обновления настроек фида
					new YFYM_Error_Log( 'FEED № ' . $this->get_feed_id() . '; NOTICE: Файл кэша ' . $filename . ' обновлен ПОЗЖЕ чем время модификации товара или время сохранения настроек фида; Файл: class-yfym-generation-xml.php; Строка: ' . __LINE__ );
					new YFYM_Error_Log( 'FEED № ' . $this->get_feed_id() . '; Пристыковываем файл кэша без изменений; Файл: class-yfym-generation-xml.php; Строка: ' . __LINE__ );
					$result_xml = file_get_contents( $filename );
					$ids_in_xml = file_get_contents( $filenameIn );
					file_put_contents( $yfym_file_ids_in_xml, $ids_in_xml, FILE_APPEND );
				}
			} else { // Файла нет
				new YFYM_Error_Log( 'FEED № ' . $this->get_feed_id() . '; NOTICE: Файла кэша товара ' . $filename . ' ещё нет! Создаем... Файл: class-yfym-generation-xml.php; Строка: ' . __LINE__ );
				$result_get_unit_obj = new YFYM_Get_Unit( $product['ID'], $this->get_feed_id() );
				$result_xml = $result_get_unit_obj->get_result();
				$ids_in_xml = $result_get_unit_obj->get_ids_in_xml();

				yfym_wf( $result_xml, $product['ID'], $this->get_feed_id(), $ids_in_xml );
				new YFYM_Error_Log( 'FEED № ' . $this->get_feed_id() . '; Создали кэш товара. Файл: class-generation-xml.php; Строка: ' . __LINE__ );
				file_put_contents( $yfym_file_ids_in_xml, $ids_in_xml, FILE_APPEND );
			}
		}
	} // end function gluing()

	/**
	 * Summary of clear_file_ids_in_xml
	 * 
	 * @param string $feed_id
	 * 
	 * @return void
	 */
	public function clear_file_ids_in_xml( $feed_id ) {
		$yfym_file_ids_in_xml = urldecode( yfym_optionGET( 'yfym_file_ids_in_xml', $feed_id, 'set_arr' ) );
		if ( is_file( $yfym_file_ids_in_xml ) ) {
			new YFYM_Error_Log( 'FEED № ' . $feed_id . '; NOTICE: Обнуляем файл $yfym_file_ids_in_xml = ' . $yfym_file_ids_in_xml . '; Файл: class-generation-xml.php; Строка: ' . __LINE__ );
			file_put_contents( $yfym_file_ids_in_xml, '' );
		} else {
			new YFYM_Error_Log( 'FEED № ' . $feed_id . '; WARNING: Файла c idшниками $yfym_file_ids_in_xml = ' . $yfym_file_ids_in_xml . ' нет! Создадим пустой; Файл: class-generation-xml.php; Строка: ' . __LINE__ );
			$yfym_file_ids_in_xml = YFYM_PLUGIN_UPLOADS_DIR_PATH . '/feed' . $feed_id . '/ids_in_xml.tmp';
			$res = file_put_contents( $yfym_file_ids_in_xml, '' );
			if ( $res !== false ) {
				new YFYM_Error_Log( 'FEED № ' . $feed_id . '; NOTICE: Файл c idшниками $yfym_file_ids_in_xml = ' . $yfym_file_ids_in_xml . ' успешно создан; Файл: class-generation-xml.php; Строка: ' . __LINE__ );
				yfym_optionUPD( 'yfym_file_ids_in_xml', urlencode( $yfym_file_ids_in_xml ), $feed_id, 'yes', 'set_arr' );
			} else {
				new YFYM_Error_Log( 'FEED № ' . $feed_id . '; ERROR: Ошибка создания файла $yfym_file_ids_in_xml = ' . $yfym_file_ids_in_xml . '; Файл: class-generation-xml.php; Строка: ' . __LINE__ );
			}
		}
	}

	/**
	 * Summary of run
	 * 
	 * @return void
	 */
	public function run() {
		$result_xml = '';

		$step_export = (int) yfym_optionGET( 'yfym_step_export', $this->get_feed_id(), 'set_arr' );
		$status_sborki = (int) yfym_optionGET( 'yfym_status_sborki', $this->get_feed_id() ); // файл уже собран. На всякий случай отключим крон сборки

		new YFYM_Error_Log( 'FEED № ' . $this->get_feed_id() . '; $status_sborki = ' . $status_sborki . '; Файл: class-generation-xml.php; Строка: ' . __LINE__ );

		switch ( $status_sborki ) {
			case -1: // сборка завершена
				new YFYM_Error_Log( 'FEED № ' . $this->get_feed_id() . '; case -1; Файл: class-generation-xml.php; Строка: ' . __LINE__ );

				wp_clear_scheduled_hook( 'yfym_cron_sborki', [ $this->get_feed_id() ] );
				break;
			case 1: // сборка начата		
				new YFYM_Error_Log( 'FEED № ' . $this->get_feed_id() . '; case 1; Файл: class-generation-xml.php; Строка: ' . __LINE__ );

				$result_xml = $this->get_feed_header();
				$result = $this->write_file( $result_xml, 'w+' );
				if ( $result !== true ) {
					new YFYM_Error_Log( 'FEED № ' . $this->get_feed_id() . '; $this->write_file вернула ошибку! $result =' . $result . '; Файл: class-generation-xml.php; Строка: ' . __LINE__ );
					$this->stop();
					return;
				} else {
					new YFYM_Error_Log( 'FEED № ' . $this->get_feed_id() . '; $this->write_file отработала успешно; Файл: class-generation-xml.php; Строка: ' . __LINE__ );
				}
				$this->clear_file_ids_in_xml( $this->get_feed_id() ); /* С версии 2.0.0 */
				$status_sborki = 2;
				new YFYM_Error_Log( 'FEED № ' . $this->get_feed_id() . '; status_sborki увеличен на ' . $step_export . ' и равен ' . $status_sborki . '; Файл: class-generation-xml.php; Строка: ' . __LINE__ );
				yfym_optionUPD( 'yfym_status_sborki', $status_sborki, $this->get_feed_id() );
				break;
			default:
				new YFYM_Error_Log( 'FEED № ' . $this->get_feed_id() . '; case default; Файл: class-generation-xml.php; Строка: ' . __LINE__ );

				$offset = ( ( $status_sborki - 1 ) * $step_export ) - $step_export; // $status_sborki - $step_export;
				$args = [ 
					'post_type' => 'product',
					'post_status' => 'publish',
					'posts_per_page' => $step_export,
					'offset' => $offset,
					'relation' => 'AND',
					'orderby' => 'ID'
				];
				$whot_export = yfym_optionGET( 'yfym_whot_export', $this->get_feed_id(), 'set_arr' );
				switch ( $whot_export ) {
					case "vygruzhat":
						$args['meta_query'] = [ 
							[ 
								'key' => 'vygruzhat',
								'value' => 'on'
							]
						];
						break;
					case "xmlset":
						$yfym_xmlset_number = '1';
						$yfym_xmlset_number = apply_filters( 'yfym_xmlset_number_filter', $yfym_xmlset_number, $this->get_feed_id() );
						$yfym_xmlset_key = '_yfym_xmlset' . $yfym_xmlset_number;
						$args['meta_query'] = [ 
							[ 
								'key' => $yfym_xmlset_key,
								'value' => 'on'
							]
						];
						break;
				} // end switch($whot_export)
				$args = apply_filters( 'yfym_query_arg_filter', $args, $this->get_feed_id() );

				new YFYM_Error_Log( 'FEED № ' . $this->get_feed_id() . '; Полная сборка. $whot_export = ' . $whot_export . '; Файл: class-generation-xml.php; Строка: ' . __LINE__ );

				new YFYM_Error_Log( 'FEED № ' . $this->get_feed_id() . '; $args =>; Файл: class-generation-xml.php; Строка: ' . __LINE__ );
				new YFYM_Error_Log( $args );

				$featured_query = new \WP_Query( $args );
				$prod_id_arr = [];
				if ( $featured_query->have_posts() ) {
					new YFYM_Error_Log( 'FEED № ' . $this->get_feed_id() . '; Вернулось записей = ' . count( $featured_query->posts ) . '; Файл: class-generation-xml.php; Строка: ' . __LINE__ );
					for ( $i = 0; $i < count( $featured_query->posts ); $i++ ) {
						$prod_id_arr[ $i ]['ID'] = $featured_query->posts[ $i ]->ID;
						$prod_id_arr[ $i ]['post_modified_gmt'] = $featured_query->posts[ $i ]->post_modified_gmt;
					}
					wp_reset_query(); /* Remember to reset */
					unset( $featured_query ); // чутка освободим память
					$this->gluing( $prod_id_arr );
					$status_sborki++; // = $status_sborki + $step_export;
					new YFYM_Error_Log( 'FEED № ' . $this->get_feed_id() . '; status_sborki увеличен на ' . $step_export . ' и равен ' . $status_sborki . '; Файл: class-generation-xml.php; Строка: ' . __LINE__ );
					yfym_optionUPD( 'yfym_status_sborki', $status_sborki, $this->get_feed_id() );
				} else { // если постов нет, пишем концовку файла
					$result_xml = $this->get_feed_footer();
					$result = $this->write_file( $result_xml, 'a' );
					new YFYM_Error_Log( 'FEED № ' . $this->get_feed_id() . '; Файл фида готов. Осталось только переименовать временный файл в основной; Файл: class-yfym-generation-xml.php; Строка: ' . __LINE__ );
					$res_rename = $this->rename_feed_file();
					$this->archiving( $res_rename );

					$this->stop();
				}
			// end default
		} // end switch($status_sborki)
		return; // final return from public function phase()
	}

	/**
	 * Summary of stop
	 * 
	 * @return void
	 */
	public function stop() {
		$status_sborki = -1;
		yfym_optionUPD( 'yfym_status_sborki', $status_sborki, $this->get_feed_id() );
		wp_clear_scheduled_hook( 'yfym_cron_sborki', [ $this->get_feed_id() ] );
		do_action( 'yfym_after_construct', $this->get_feed_id(), 'full' ); // сборка закончена
	}

	/**
	 * Проверим, нужна ли пересборка фида при обновлении поста
	 * 
	 * @param mixed $post_id
	 * 
	 * @return bool
	 */
	public function check_ufup( $post_id ) {
		$yfym_ufup = yfym_optionGET( 'yfym_ufup', $this->get_feed_id(), 'set_arr' );
		if ( $yfym_ufup === 'on' ) {
			$status_sborki = (int) yfym_optionGET( 'yfym_status_sborki', $this->get_feed_id() );
			if ( $status_sborki > -1 ) { // если идет сборка фида - пропуск
				return false;
			} else {
				return true;
			}
		} else {
			return false;
		}
	}

	/**
	 * Summary of get_feed_header
	 * 
	 * @param string $result_xml
	 * 
	 * @return string
	 */
	protected function get_feed_header( $result_xml = '' ) {
		$yfym_cache = common_option_get( 'yfym_cache', false, $this->get_feed_id(), 'yfym' );
		if ( $yfym_cache === 'enabled' ) {
			$unixtime = (string) current_time( 'timestamp', 1 ); // 1335808087 - временная зона GMT (Unix формат)
			yfym_optionUPD( 'yfym_date_save_set', $unixtime, $this->get_feed_id(), 'yes', 'set_arr' );
		}

		$unixtime = (string) current_time( 'Y-m-d H:i' ); // время в unix формате 2022-03-21 17:47
		$rfc_3339_time = (string) current_time( 'c' ); // 2022-07-17T17:47:19+03:00
		$rfc_3339_short_time = (string) current_time( 'Y-m-d\TH:i' ); // 2022-07-17T17:47
		yfym_optionUPD( 'yfym_date_sborki', $unixtime, $this->get_feed_id(), 'yes', 'set_arr' );
		$shop_name = stripslashes( yfym_optionGET( 'yfym_shop_name', $this->get_feed_id(), 'set_arr' ) );
		$company_name = stripslashes( yfym_optionGET( 'yfym_company_name', $this->get_feed_id(), 'set_arr' ) );
		$result_xml .= '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
		$yfym_format_date = yfym_optionGET( 'yfym_format_date', $this->get_feed_id(), 'set_arr' );
		if ( $yfym_format_date === 'unixtime' ) {
			$catalog_date = $unixtime;
		} else if ( $yfym_format_date === 'rfc_short' ) {
			$catalog_date = $rfc_3339_short_time;
		} else {
			$catalog_date = $rfc_3339_time;
		}
		$result_xml .= new Get_Open_Tag( 'yml_catalog', [ 'date' => $catalog_date ] );
		$result_xml .= new Get_Open_Tag( 'shop' );
		$result_xml .= new Get_Paired_Tag( 'name', esc_html( $shop_name ) );
		$result_xml .= new Get_Paired_Tag( 'company', esc_html( $company_name ) );
		$res_home_url = home_url( '/' );
		$res_home_url = apply_filters( 'yfym_home_url', $res_home_url, $this->get_feed_id() );
		$result_xml .= new Get_Paired_Tag( 'url', yfym_replace_domain( $res_home_url, $this->get_feed_id() ) );
		$result_xml .= new Get_Paired_Tag( 'platform', 'WordPress - YML for Yandex Market' );
		$result_xml .= new Get_Paired_Tag( 'version', get_bloginfo( 'version' ) );

		if ( class_exists( 'WOOCS' ) ) {
			$yfym_wooc_currencies = common_option_get( 'yfym_wooc_currencies', false, $this->get_feed_id(), 'yfym' );
			if ( $yfym_wooc_currencies !== '' ) {
				global $WOOCS;
				$WOOCS->set_currency( $yfym_wooc_currencies );
			}
		}

		/* общие параметры */
		$yfym_currencies = common_option_get( 'yfym_currencies', false, $this->get_feed_id(), 'yfym' );
		if ( $yfym_currencies !== 'disabled' ) {
			$res = get_woocommerce_currency(); // получаем валюта магазина
			$rate_cb = '';
			switch ( $res ) { /* RUR, USD, EUR, UAH, KZT, BYN */
				case "RUB":
					$currencyId_yml = "RUR";
					break;
				case "USD":
					$currencyId_yml = "USD";
					$rate_cb = "CB";
					break;
				case "EUR":
					$currencyId_yml = "EUR";
					$rate_cb = "CB";
					break;
				case "UAH":
					$currencyId_yml = "UAH";
					break;
				case "KZT":
					$currencyId_yml = "KZT";
					break;
				case "BYN":
					$currencyId_yml = "BYN";
					break;
				case "BYR":
					$currencyId_yml = "BYN";
					break;
				case "ABC":
					$currencyId_yml = "BYN";
					break;
				default:
					$currencyId_yml = "RUR";
			}
			$rate_cb = apply_filters( 'y4ym_f_rate_cb', $rate_cb, $this->get_feed_id() );
			$currencyId_yml = apply_filters( 'yfym_currency_id', $currencyId_yml, $this->get_feed_id() );
			if ( $rate_cb == '' ) {
				$result_xml .= new Get_Open_Tag( 'currencies' );
				$result_xml .= new Get_Open_Tag( 'currency', [ 'id' => $currencyId_yml, 'rate' => '1' ], true );
				$result_xml .= new Get_Closed_Tag( 'currencies' );
			} else {
				$result_xml .= new Get_Open_Tag( 'currencies' );
				$result_xml .= new Get_Open_Tag( 'currency', [ 'id' => 'RUR', 'rate' => '1' ], true );
				$result_xml .= new Get_Open_Tag( 'currency', [ 'id' => $currencyId_yml, 'rate' => $rate_cb ], true );
				$result_xml .= new Get_Closed_Tag( 'currencies' );
			}
		}

		$yfym_yml_rules = common_option_get( 'yfym_yml_rules', false, $this->get_feed_id(), 'yfym' );
		if ( $yfym_yml_rules !== 'sales_terms' && $yfym_yml_rules !== 'sets' ) {
			$result_xml .= $this->get_categories();
		}

		$yfym_pickup_options = common_option_get( 'yfym_pickup_options', false, $this->get_feed_id(), 'yfym' );
		if ( $yfym_pickup_options === 'on' ) {
			$pickup_cost = yfym_optionGET( 'yfym_pickup_cost', $this->get_feed_id(), 'set_arr' );
			$pickup_days = yfym_optionGET( 'yfym_pickup_days', $this->get_feed_id(), 'set_arr' );
			$attr_arr = [ 
				'cost' => $pickup_cost,
				'days' => $pickup_days
			];
			$pickup_order_before = common_option_get( 'yfym_pickup_order_before', false, $this->get_feed_id(), 'yfym' );
			if ( $pickup_order_before !== '' ) {
				$attr_arr['order-before'] = $pickup_order_before;
			}
			$result_xml .= new Get_Open_Tag( 'pickup-options' );
			$result_xml .= new Get_Open_Tag( 'option', $attr_arr, true );
			$result_xml .= new Get_Closed_Tag( 'pickup-options' );
		}

		if ( $yfym_yml_rules === 'sbermegamarket' ) {
			$tag_name = 'shipment-options';
		} else {
			$tag_name = 'delivery-options';
		}

		$yfym_delivery_options = common_option_get( 'yfym_delivery_options', false, $this->get_feed_id(), 'yfym' );
		if ( $yfym_delivery_options === 'on' ) {
			$delivery_cost = common_option_get( 'yfym_delivery_cost', false, $this->get_feed_id(), 'yfym' );
			$delivery_days = common_option_get( 'yfym_delivery_days', false, $this->get_feed_id(), 'yfym' );
			$order_before = common_option_get( 'yfym_order_before', false, $this->get_feed_id(), 'yfym' );
			if ( $order_before == '' ) {
				$order_before_yml = '';
			} else {
				$order_before_yml = ' order-before="' . $order_before . '"';
			}
			$result_xml .= '<' . $tag_name . '>' . PHP_EOL;
			$result_xml .= '<option cost="' . $delivery_cost . '" days="' . $delivery_days . '"' . $order_before_yml . '/>' . PHP_EOL;
			$yfym_delivery_options2 = yfym_optionGET( 'yfym_delivery_options2', $this->get_feed_id(), 'set_arr' );
			if ( $yfym_delivery_options2 === 'on' ) {
				$delivery_cost2 = common_option_get( 'yfym_delivery_cost2', false, $this->get_feed_id(), 'yfym' );
				$delivery_days2 = common_option_get( 'yfym_delivery_days2', false, $this->get_feed_id(), 'yfym' );
				$order_before2 = common_option_get( 'yfym_order_before2', false, $this->get_feed_id(), 'yfym' );
				if ( $order_before2 == '' ) {
					$order_before_yml2 = '';
				} else {
					$order_before_yml2 = ' order-before="' . $order_before2 . '"';
				}
				$result_xml .= '<option cost="' . $delivery_cost2 . '" days="' . $delivery_days2 . '"' . $order_before_yml2 . '/>' . PHP_EOL;
			}
			$result_xml .= '</' . $tag_name . '>' . PHP_EOL;
		}

		// магазин 18+
		$adult = common_option_get( 'yfym_adult', false, $this->get_feed_id(), 'yfym' );
		if ( $adult === 'yes' ) {
			$result_xml .= new Get_Paired_Tag( 'adult', 'true' );
		}

		/* end общие параметры */
		do_action( 'yfym_before_offers', $this->get_feed_id() );

		$result_xml = apply_filters(
			'y4ym_f_before_offers',
			$result_xml,
			[ 
				'rules' => $yfym_yml_rules
			],
			$this->get_feed_id()
		);

		/* индивидуальные параметры товара */
		$result_xml .= new Get_Open_Tag( 'offers' );
		if ( class_exists( 'WOOCS' ) ) {
			global $WOOCS;
			$WOOCS->reset_currency();
		}
		do_action( 'yfym_before_offers', $this->get_feed_id() );

		return $result_xml;
	}

	/**
	 * Get YML list of categories 
	 * 
	 * @param string $result_xml
	 * 
	 * @return string
	 */
	function get_categories( $result_xml = '' ) {
		$categories_yml = '';
		$all_parent_flag = false;
		$all_parent_flag = apply_filters( 'y4ym_f_all_parent_flag', $all_parent_flag, $this->get_feed_id() );
		$args_terms_arr = [ 
			'hide_empty' => false,
			'product_type' => 'product',
			'taxonomy' => 'product_cat'
		];
		$args_terms_arr = apply_filters( 'yfym_args_terms_arr_filter', $args_terms_arr, $this->get_feed_id() );
		$terms = get_terms( $args_terms_arr );
		$count = count( $terms );
		if ( $count > 0 ) {
			foreach ( $terms as $term ) {
				$skip_flag_category = false;
				$skip_flag_category = apply_filters(
					'y4ym_f_skip_flag_category',
					$skip_flag_category,
					[ 
						'terms' => $terms,
						'term' => $term,
					],
					$this->get_feed_id()
				);
				if ( true === $skip_flag_category ) {
					continue;
				}
				if ( $term->parent == 0 || true === $all_parent_flag ) {
					// у категории НЕТ родительской категории или настройками задано делать все родительскими
					$categories_attr_arr = [ 
						'id' => $term->term_id
					];
					$categories_attr_arr = apply_filters(
						'y4ym_f_categories_attr_arr',
						$categories_attr_arr,
						[ 
							'terms' => $terms,
							'term' => $term,
						],
						$this->get_feed_id()
					);
					$categories_yml .= new Get_Paired_Tag( 'category', $term->name, $categories_attr_arr );
				} else {
					// у категории ЕСТЬ родительская категория
					$categories_attr_arr = [ 
						'id' => $term->term_id,
						'parentId' => $term->parent
					];
					$categories_attr_arr = apply_filters(
						'y4ym_f_categories_attr_arr',
						$categories_attr_arr,
						[ 
							'terms' => $terms,
							'term' => $term,
						],
						$this->get_feed_id()
					);
					$categories_yml .= new Get_Paired_Tag( 'category', $term->name, $categories_attr_arr );
				}
			}
		}

		$result_xml .= new Get_Open_Tag( 'categories' );
		$result_xml .= $categories_yml;
		$result_xml = apply_filters( 'yfym_append_categories_filter', $result_xml, $this->get_feed_id() );
		$result_xml .= new Get_Closed_Tag( 'categories' );

		return $result_xml;
	}

	/**
	 * Summary of get_ids_in_xml
	 * 
	 * @param string $file_content
	 * 
	 * @return array
	 */
	protected function get_ids_in_xml( $file_content ) {
		/**
		 * $file_content - содержимое файла (Обязательный параметр)
		 * Возвращает массив в котором ключи - это id товаров в БД WordPress, попавшие в фид
		 */
		$res_arr = [];
		$file_content_string_arr = explode( PHP_EOL, $file_content );
		for ( $i = 0; $i < count( $file_content_string_arr ) - 1; $i++ ) {
			$r_arr = explode( ';', $file_content_string_arr[ $i ] );
			$res_arr[ $r_arr[0] ] = '';
		}
		return $res_arr;
	}

	protected function get_feed_body( $result_xml = '' ) {
		$yfym_file_ids_in_xml = urldecode( yfym_optionGET( 'yfym_file_ids_in_xml', $this->get_feed_id(), 'set_arr' ) );
		$file_content = file_get_contents( $yfym_file_ids_in_xml );
		$ids_in_xml_arr = $this->get_ids_in_xml( $file_content );

		$name_dir = YFYM_SITE_UPLOADS_DIR_PATH . '/yfym/feed' . $this->get_feed_id();

		foreach ( $ids_in_xml_arr as $key => $value ) {
			$product_id = (int) $key;
			$filename = $name_dir . '/' . $product_id . '.tmp';
			$result_xml .= file_get_contents( $filename );
		}

		yfym_optionUPD( 'yfym_count_products_in_feed', count( $ids_in_xml_arr ), $this->get_feed_id(), 'yes', 'set_arr' );
		// товаров попало в фид - count($ids_in_xml_arr);

		return $result_xml;
	}

	/**
	 * Summary of get_feed_footer
	 * 
	 * @param string $result_xml
	 * 
	 * @return string
	 */
	protected function get_feed_footer( $result_xml = '' ) {
		$result_xml .= $this->get_feed_body( $result_xml );

		$result_xml .= new Get_Closed_Tag( 'offers' );
		$result_xml = apply_filters( 'yfym_after_offers_filter', $result_xml, $this->get_feed_id() );
		$result_xml .= new Get_Closed_Tag( 'shop' );
		$result_xml .= new Get_Closed_Tag( 'yml_catalog' );

		yfym_optionUPD( 'yfym_date_sborki_end', current_time( 'Y-m-d H:i' ), $this->get_feed_id(), 'yes', 'set_arr' );

		return $result_xml;
	}

	/**
	 * Get feed ID
	 * 
	 * @return string
	 */
	protected function get_feed_id() {
		return $this->feed_id;
	}

	/**
	 * Summary of onlygluing
	 * 
	 * @return void
	 */
	public function onlygluing() {
		$result_xml = $this->get_feed_header();
		/* создаем файл или перезаписываем старый удалив содержимое */
		$result = $this->write_file( $result_xml, 'w+' );
		if ( true !== $result ) {
			new YFYM_Error_Log( sprintf(
				'FEED № %1$s; $this->write_file вернула ошибку! $result = %2$s; Файл: %3$s; Строка: %4$s',
				$this->get_feed_id(),
				$result,
				'class-generation-xml.php',
				__LINE__
			) );
		}

		yfym_optionUPD( 'yfym_status_sborki', '-1', $this->get_feed_id() );
		$whot_export = yfym_optionGET( 'yfym_whot_export', $this->get_feed_id(), 'set_arr' );

		$result_xml = '';
		$step_export = -1;
		$prod_id_arr = [];

		if ( $whot_export === 'vygruzhat' ) {
			$args = [ 
				'post_type' => 'product',
				'post_status' => 'publish',
				'posts_per_page' => $step_export, // сколько выводить товаров
				// 'offset' => $offset,
				'relation' => 'AND',
				'orderby' => 'ID',
				'fields' => 'ids',
				'meta_query' => [ 
					[ 
						'key' => 'vygruzhat',
						'value' => 'on'
					]
				]
			];
		} else { //  if ($whot_export == 'all' || $whot_export == 'simple')
			$args = [ 
				'post_type' => 'product',
				'post_status' => 'publish',
				'posts_per_page' => $step_export, // сколько выводить товаров
				// 'offset' => $offset,
				'relation' => 'AND',
				'orderby' => 'ID',
				'fields' => 'ids'
			];
		}

		$args = apply_filters( 'yfym_query_arg_filter', $args, $this->get_feed_id() );
		new YFYM_Error_Log( sprintf(
			'FEED № %1$s; Быстрая сборка. $whot_export = %2$s; Файл: %3$s; Строка: %4$s',
			$this->get_feed_id(),
			$whot_export,
			'class-generation-xml.php',
			__LINE__
		) );
		new YFYM_Error_Log( $args );
		new YFYM_Error_Log( sprintf(
			'FEED № %1$s; NOTICE: onlygluing до запуска WP_Query RAM %2$s Кб; Файл: %3$s; Строка: %4$s',
			$this->get_feed_id(),
			round( memory_get_usage() / 1024, 1 ),
			'class-generation-xml.php',
			__LINE__
		) );
		$featured_query = new \WP_Query( $args );
		new YFYM_Error_Log( sprintf(
			'FEED № %1$s; NOTICE: onlygluing после запуска WP_Query RAM %2$s Кб; Файл: %3$s; Строка: %4$s',
			$this->get_feed_id(),
			round( memory_get_usage() / 1024, 1 ),
			'class-generation-xml.php',
			__LINE__
		) );

		global $wpdb;
		if ( $featured_query->have_posts() ) {
			new YFYM_Error_Log( sprintf(
				'FEED № %1$s; Вернулось записей = %2$s; Файл: %3$s; Строка: %4$s',
				$this->get_feed_id(),
				count( $featured_query->posts ),
				'class-generation-xml.php',
				__LINE__
			) );
			for ( $i = 0; $i < count( $featured_query->posts ); $i++ ) {
				/**
				 *	если не юзаем 'fields'  => 'ids'
				 *	$prod_id_arr[$i]['ID'] = $featured_query->posts[$i]->ID;
				 *	$prod_id_arr[$i]['post_modified_gmt'] = $featured_query->posts[$i]->post_modified_gmt;
				 */
				$cur_id = $featured_query->posts[ $i ];
				$prod_id_arr[ $i ]['ID'] = $cur_id;
				$res = $wpdb->get_results( $wpdb->prepare( "SELECT post_modified_gmt FROM $wpdb->posts WHERE id=%d", $cur_id ), ARRAY_A );
				$prod_id_arr[ $i ]['post_modified_gmt'] = $res[0]['post_modified_gmt'];
				// get_post_modified_time('Y-m-j H:i:s', true, $featured_query->posts[$i]);
			}
			wp_reset_query(); /* Remember to reset */
			unset( $featured_query ); // чутка освободим память
		}
		if ( ! empty( $prod_id_arr ) ) {
			new YFYM_Error_Log( sprintf(
				'FEED № %1$s; NOTICE: %2$s; Файл: %3$s; Строка: %4$s',
				$this->get_feed_id(),
				'onlygluing передала управление this->gluing',
				'class-generation-xml.php',
				__LINE__
			) );
			$this->gluing( $prod_id_arr );
		}

		// если постов нет, пишем концовку файла
		$result_xml = $this->get_feed_footer();
		$result = $this->write_file( $result_xml, 'a' );
		new YFYM_Error_Log( sprintf(
			'FEED № %1$s; %2$s; Файл: %3$s; Строка: %4$s',
			$this->get_feed_id(),
			'Файл фида готов. Осталось только переименовать временный файл в основной',
			'class-generation-xml.php',
			__LINE__
		) );
		$res_rename = $this->rename_feed_file();
		$this->archiving( $res_rename );

		$this->stop();
	} // end function onlygluing()

	/**
	 * Перименовывает временный файл фида в основной
	 * 
	 * @return array|false
	 */
	private function rename_feed_file() {
		new YFYM_Error_Log( sprintf(
			'FEED № %1$s; Cтартовала $this->rename_feed_file; Файл: %2$s; Строка: %3$s',
			$this->get_feed_id(),
			'class-generation-xml.php',
			__LINE__
		) );

		$feed_file_meta = new YFYM_Feed_File_Meta( $this->get_feed_id() );
		$file_feed_name = $feed_file_meta->get_feed_filename();

		// /home/site.ru/public_html/wp-content/uploads/feed-yml-0.xml
		$feed_basedir_old = urldecode( common_option_get( 'yfym_file_file', false, $this->get_feed_id(), 'yfym' ) );

		// /home/site.ru/public_html/wp-content/uploads/feed-yml-0.xml
		// ? надо придумать как поулчить урл загрузок конкретного блога, например, используя BLOGUPLOADDIR
		$feed_basedir_new = sprintf(
			'%1$s/%2$s.%3$s', YFYM_SITE_UPLOADS_DIR_PATH, $file_feed_name, $feed_file_meta->get_feed_extension()
		);

		// https://site.ru/wp-content/uploads/feed-yml-2.xml
		$feed_url_new = sprintf(
			'%1$s/%2$s.%3$s', YFYM_SITE_UPLOADS_URL, $file_feed_name, $feed_file_meta->get_feed_extension()
		);

		$file_name = $file_feed_name . "." . $feed_file_meta->get_feed_extension();
		$file_name_zip = $file_feed_name . ".zip";

		new YFYM_Error_Log( sprintf(
			'FEED № %1$s; $feed_basedir_old = %2$s; Файл: %3$s; Строка: %4$s',
			$this->get_feed_id(),
			$feed_basedir_old,
			'class-generation-xml.php',
			__LINE__
		) );

		new YFYM_Error_Log( sprintf(
			'FEED № %1$s; $feed_basedir_new = %2$s; Файл: %3$s; Строка: %4$s',
			$this->get_feed_id(),
			$feed_basedir_new,
			'class-generation-xml.php',
			__LINE__
		) );

		if ( false === rename( $feed_basedir_old, $feed_basedir_new ) ) {
			new YFYM_Error_Log( sprintf(
				'FEED № %1$s; Не могу переименовать файл из %2$s в %3$s; Файл: %4$s; Строка: %5$s',
				$this->get_feed_id(),
				$feed_basedir_old,
				$feed_basedir_new,
				'class-generation-xml.php',
				__LINE__
			) );
			return false;
		} else {
			yfym_optionUPD( 'yfym_file_url', urlencode( $feed_url_new ), $this->get_feed_id(), 'yes', 'set_arr' );
			new YFYM_Error_Log( sprintf(
				'FEED № %1$s; Файл успешно переименован из %2$s в %3$s; Файл: %4$s; Строка: %5$s',
				$this->get_feed_id(),
				$feed_basedir_old,
				$feed_basedir_new,
				'class-generation-xml.php',
				__LINE__
			) );

			return [ 
				'file_name_zip' => $file_name_zip,
				'file_name' => $file_name,
				'file_url' => $feed_url_new,
				'file_basedir' => $feed_basedir_new,
			];
		}
	}

	/**
	 * Summary of archiving
	 * 
	 * @param mixed $res_rename
	 * 
	 * @return void
	 */
	private function archiving( $res_rename ) {
		$archive_to_zip = common_option_get( 'yfym_archive_to_zip', false, $this->get_feed_id(), 'yfym' );
		if ( $archive_to_zip === 'enabled' && is_array( $res_rename ) ) {
			new YFYM_Error_Log( sprintf( 'FEED №%1$s; %2$s; Файл: %3$s; Строка: %4$s',
				$this->get_feed_id(),
				'Приступаем к архивированию файла;',
				'class-yfym-generation-xml.php',
				__LINE__
			) );
			$zip = new ZipArchive();
			$zip->open(
				YFYM_SITE_UPLOADS_DIR_PATH . '/' . $res_rename['file_name_zip'],
				ZipArchive::CREATE | ZipArchive::OVERWRITE
			);
			$zip->addFile( $res_rename['file_basedir'], $res_rename['file_name'] );
			$zip->close();
			yfym_optionUPD(
				'yfym_file_url',
				urlencode( YFYM_SITE_UPLOADS_URL . '/' . $res_rename['file_name_zip'] ),
				$this->get_feed_id(),
				'yes',
				'set_arr'
			);
			new YFYM_Error_Log( sprintf( 'FEED №%1$s; %2$s; Файл: %3$s; Строка: %4$s',
				$this->get_feed_id(),
				'Архивирование успешно;',
				'class-yfym-generation-xml.php',
				__LINE__
			) );
		}
	}
}