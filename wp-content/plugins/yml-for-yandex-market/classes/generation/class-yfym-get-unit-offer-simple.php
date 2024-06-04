<?php
/**
 * Get unit for Simple Products 
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
 * @param       
 *
 * @depends                 classes:    YFYM_Get_Unit_Offer
 *                          traits:     
 *                          methods:    
 *                          functions:  common_option_get
 *                          constants:  
 *                          options:    
 */
defined( 'ABSPATH' ) || exit;

class YFYM_Get_Unit_Offer_Simple extends YFYM_Get_Unit_Offer {
	use YFYM_T_Common_Get_CatId;
	use YFYM_T_Common_Skips;

	use YFYM_T_Simple_Get_Age;
	use YFYM_T_Simple_Get_Amount;
	use YFYM_T_Simple_Get_Barcode;
	use YFYM_T_Simple_Get_Cargo_Types;
	use YFYM_T_Simple_Get_CategoryId;
	use YFYM_T_Simple_Get_Condition;
	use YFYM_T_Simple_Get_Count;
	use YFYM_T_Simple_Get_Country_Of_Orgin;
	use YFYM_T_Simple_Get_Credit_Template;
	use YFYM_T_Simple_Get_Currencyid;
	use YFYM_T_Simple_Get_Delivery_Options;
	use YFYM_T_Simple_Get_Delivery;
	use YFYM_T_Simple_Get_Description;
	use YFYM_T_Simple_Get_Dimensions;
	use YFYM_T_Simple_Get_Disabled;
	use YFYM_T_Simple_Get_Downloadable;
	use YFYM_T_Simple_Get_Enable_Auto_Discounts;
	use YFYM_T_Simple_Get_Expiry;
	use YFYM_T_Simple_Get_Id;
	use YFYM_T_Simple_Get_Instock;
	use YFYM_T_Simple_Get_Keywords;
	use YFYM_T_Simple_Get_Manufacturer_Warranty;
	use YFYM_T_Simple_Get_Manufacturer;
	use YFYM_T_Simple_Get_Market_Sku;
	use YFYM_T_Simple_Get_Min_Price;
	use YFYM_T_Simple_Get_Min_Quantity;
	use YFYM_T_Simple_Get_Model;
	use YFYM_T_Simple_Get_Name;
	use YFYM_T_Simple_Get_Offer_Tag;
	use YFYM_T_Simple_Get_Outlets;
	use YFYM_T_Simple_Get_Params;
	use YFYM_T_Simple_Get_Period_Of_Validity_Days;
	use YFYM_T_Simple_Get_Pickup_Options;
	use YFYM_T_Simple_Get_Pickup;
	use YFYM_T_Simple_Get_Picture;
	use YFYM_T_Simple_Get_Premium_Price;
	use YFYM_T_Simple_Get_Price;
	use YFYM_T_Simple_Get_Price_Rrp;
	use YFYM_T_Simple_Get_Recommend_Stock_Data;
	use YFYM_T_Simple_Get_Sales_Notes;
	use YFYM_T_Simple_Get_Shop_Sku;
	use YFYM_T_Simple_Get_Step_Quantity;
	use YFYM_T_Simple_Get_Store;
	use YFYM_T_Simple_Get_Supplier;
	use YFYM_T_Simple_Get_Tn_Ved_Codes;
	use YFYM_T_Simple_Get_Url;
	use YFYM_T_Simple_Get_Vat;
	use YFYM_T_Simple_Get_Vendor;
	use YFYM_T_Simple_Get_Vendorcode;
	use YFYM_T_Simple_Get_Video;
	use YFYM_T_Simple_Get_Weight;

	/**
	 * Summary of generation_product_xml
	 * 
	 * @param string $result_xml
	 * 
	 * @return string
	 */
	public function generation_product_xml( $result_xml = '' ) {
		$this->set_category_id();
		// $this->feed_category_id = $this->get_catid();
		$this->get_skips();

		$yfym_yml_rules = common_option_get( 'yfym_yml_rules', false, $this->get_feed_id(), 'yfym' );
		switch ( $yfym_yml_rules ) {
			case "yandex_market":
				$result_xml = $this->adv();
				break;
			case "yandex_direct":
				$result_xml = $this->direct();
				break;
			case "yandex_webmaster":
				$result_xml = $this->yandex_webmaster();
				break;
			case "products_and_offers":
				$result_xml = $this->products_and_offers();
				break;
			case "single_catalog":
				$result_xml = $this->single_catalog();
				break;
			case "dbs":
				$result_xml = $this->dbs();
				break;
			case "sales_terms":
				$result_xml = $this->sales_terms();
				break;
			case "beru":
				$result_xml = $this->old();
				break;
			case "all_elements":
				$result_xml = $this->all_elements();
				break;
			case "ozon":
				$result_xml = $this->ozon();
				break;
			case "sbermegamarket":
				$result_xml = $this->sbermegamarket();
				break;
			case "vk":
				$result_xml = $this->vk();
				break;
			default:
				$result_xml = $this->all_elements();
		}

		$result_xml = apply_filters( 'yfym_append_simple_offer_filter', $result_xml, $this->product, $this->feed_id );
		$result_xml = apply_filters(
			'y4ym_f_append_simple_offer',
			$result_xml,
			[ 
				'product' => $this->product,
				'feed_category_id' => $this->get_feed_category_id()
			],
			$this->feed_id
		);
		$result_xml .= '</offer>' . PHP_EOL;
		return $result_xml;
	}

	/**
	 * Summary of adv
	 * 
	 * @see 
	 * 
	 * @param string $result_xml - Optional
	 * 
	 * @return string
	 */
	private function adv( $result_xml = '' ) {
		$result_xml .= $this->get_tags( 'adv', $result_xml );
		return $result_xml;
	}

	/**
	 * Summary of direct
	 * 
	 * @see https://yandex.ru/support/direct/feeds/requirements.html#requirements__market-feed
	 * 
	 * @param string $result_xml - Optional
	 * 
	 * @return string
	 */
	private function direct( $result_xml = '' ) {
		$result_xml .= $this->get_tags( 'direct', $result_xml );
		return $result_xml;
	}

	/**
	 * Summary of single_catalog
	 * 
	 * @see 
	 * 
	 * @param string $result_xml - Optional
	 * 
	 * @return string
	 */
	private function single_catalog( $result_xml = '' ) {
		$result_xml .= $this->get_tags( 'single_catalog', $result_xml );
		return $result_xml;
	}

	/**
	 * Summary of dbs
	 * 
	 * @see https://yandex.ru/support/marketplace/assortment/files/index.html
	 *      https://yandex.ru/support/marketplace/tools/elements/offer-general.html
	 * 
	 * @param string $result_xml - Optional
	 * 
	 * @return string
	 */
	private function dbs( $result_xml = '' ) {
		$result_xml .= $this->get_tags( 'dbs', $result_xml );
		return $result_xml;
	}

	/**
	 * Summary of sales_terms
	 * 
	 * @see 
	 * 
	 * @param string $result_xml - Optional
	 * 
	 * @return string
	 */
	private function sales_terms( $result_xml = '' ) {
		$result_xml .= $this->get_tags( 'sales_terms', $result_xml );
		return $result_xml;
	}

	private function old( $result_xml = '' ) {
		$result_xml .= $this->all_elements();

		return $result_xml;
	}

	/**
	 * Summary of sbermegamarket
	 * 
	 * @see 
	 * 
	 * @param string $result_xml - Optional
	 * 
	 * @return string
	 */
	private function sbermegamarket( $result_xml = '' ) {
		$result_xml .= $this->get_tags( 'sbermegamarket', $result_xml );
		$result_xml .= $this->get_delivery_options( 'shipment-options', '', 'sbermegamarket' );
		$result_xml .= $this->get_outlets( 'outlets', '', 'sbermegamarket' );
		return $result_xml;
	}

	/**
	 * Summary of vk
	 * 
	 * @see 
	 * 
	 * @param string $result_xml - Optional
	 * 
	 * @return string
	 */
	private function vk( $result_xml = '' ) {
		$result_xml .= $this->get_tags( 'vk', $result_xml );
		return $result_xml;
	}

	/**
	 * Summary of all_elements
	 * 
	 * @see 
	 * 
	 * @param string $result_xml - Optional
	 * 
	 * @return string
	 */
	private function all_elements( $result_xml = '' ) {
		//	$result_xml .= $this->get_id();
		$result_xml .= $this->get_tags( 'all_elements', $result_xml );
		return $result_xml;
	}

	/**
	 * Summary of ozon
	 * 
	 * @see 
	 * 
	 * @param string $result_xml - Optional
	 * 
	 * @return string
	 */
	private function ozon( $result_xml = '' ) {
		$result_xml .= $this->get_tags( 'ozon', $result_xml );
		return $result_xml;
	}

	/**
	 * Summary of yandex_webmaster
	 * 
	 * @see https://yandex.ru/support/products/features.html
	 * 
	 * @param string $result_xml - Optional
	 * 
	 * @return string
	 */
	private function yandex_webmaster( $result_xml = '' ) {
		$result_xml .= $this->get_tags( 'yandex_webmaster', $result_xml );
		return $result_xml;
	}

	/**
	 * Summary of products_and_offers
	 * 
	 * @see https://yandex.ru/support/products/partners.html
	 * 
	 * @param string $result_xml - Optional
	 * 
	 * @return string
	 */
	private function products_and_offers( $result_xml = '' ) {
		$result_xml .= $this->get_tags( 'products_and_offers', $result_xml );
		return $result_xml;
	}
}