<?php if (!defined('ABSPATH')) {exit;}
/**
* Traits Group_Id for variable products
*
* @author		Maxim Glazunov
* @link			https://icopydoc.ru/
* @since		1.0.0
*
* @return 		$result_xml (string)
*
* @depends		class:	Get_Paired_Tag
*				methods: add_skip_reason
*				functions: 
*/

trait YFYM_T_Variable_Get_Group_Id {
	public function get_group_id() {
		$product = $this->product;
		$result_xml_id = $product->get_id();
		$result_xml_id = new Get_Paired_Tag('group_id', $result_xml_id);
		return $result_xml_id;
	}
}
?>