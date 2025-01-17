<?php
/**
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 *
 * @link       http://www.orionorigin.com
 * @since      1.0.0
 *
 * @package    Vpc
 * @subpackage Vpc/includes
 */

/**
 * Description of class-mb-confog
 *
 * @author HL
 */
class VPC_Config {

	/**
	 * The ID of configuration.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $id    The configurator id.
	 */
	public $id;

	/**
	 * The setting of configuration.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      object $settings    The configurator setting.
	 */
	public $settings;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $config_id  The configurator id.
	 */
	public function __construct( $config_id ) {
		if ( $config_id ) {
			$this->id       = $config_id;
			$this->settings = get_post_meta( $config_id, 'vpc-config', true );
		}
	}

	/**
	 * Register the config custom post type
	 */
	public function register_cpt_config() {

		$labels = array(
			'name'               => _x( 'Configuration', 'vpc' ),
			'singular_name'      => _x( 'Configuration', 'vpc' ),
			'add_new'            => _x( 'New configuration', 'vpc' ),
			'add_new_item'       => _x( 'New configuration', 'vpc' ),
			'edit_item'          => _x( 'Edit configuration', 'vpc' ),
			'new_item'           => _x( 'New configuration', 'vpc' ),
			'view_item'          => _x( 'View configuration', 'vpc' ),
			'not_found'          => _x( 'No configuration found', 'vpc' ),
			'not_found_in_trash' => _x( 'No configuration in the trash', 'vpc' ),
			'menu_name'          => _x( 'Product Builder', 'vpc' ),
			'all_items'          => _x( 'Configurations', 'vpc' ),
		);

		$args = array(
			'labels'              => $labels,
			'hierarchical'        => false,
			'description'         => 'Configurations',
			'supports'            => array( 'title' ),
			'public'              => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => false,
			'publicly_queryable'  => false,
			'exclude_from_search' => true,
			'has_archive'         => false,
			'query_var'           => false,
			'can_export'          => true,
			'menu_icon'           => VPC_URL . 'admin/images/vpc-dashicon.svg',
		);
		register_post_type( 'vpc-config', $args );
	}

	/**
	 * Adds the metabox for the config CPT
	 */
	public function get_config_metabox() {

		$screens = array( 'vpc-config' );

		foreach ( $screens as $screen ) {

			add_meta_box(
				'vpc-config-preview-box',
				__( 'Preview', 'vpc' ),
				array( $this, 'get_config_preview_page' ),
				$screen
			);

			add_meta_box(
				'vpc-config-settings-box',
				__( 'Configuration settings', 'vpc' ),
				array( $this, 'get_config_settings_page' ),
				$screen
			);
			// @codingStandardsIgnoreStart
			/*
			  add_meta_box(
			  'vpc-config-conditional-rules-box', __( 'Conditional rules', 'vpc' ), array( $this, 'get_config_conditional_rules_page' ), $screen
			  ); */
			// @codingStandardsIgnoreEnd

		}
	}

	/**
	 * Configuration CPT metabox callback
	 */
	public function get_config_settings_page() {
		wp_enqueue_media();
		global $vpc_settings;
		$product_link = get_proper_value( $vpc_settings, 'product-link', 'No' );
		$config_id    = get_the_ID();
		?>
	<div class='block-form'>
		<!--<div class="postbox" id="vpc-options-container">-->
		<?php
		$skin_begin = array(
			'type' => 'sectionbegin',
			'id'   => 'vpc-skin-container',
		);
		$skins_arr  = apply_filters(
			'vpc_configuration_skins',
			array(
				'VPC_Default_Skin'       => __( 'Default', 'vpc' ),
				'VPC_Right_Sidebar_Skin' => __( 'Right Sidebar skin', 'vpc' ),
			)
		);

		$components_skins = apply_filters(
			'vpc_components_skins',
			array(
				'VPC_Default_Skin' => array(
					'checkbox' => __( 'Checkbox', 'vpc' ),
					'radio'    => __( 'Radio', 'vpc' ),
				),
			)
		);

		$components_skins_dropdowns = $this->get_skin_components_dropdowns_contents( $components_skins );

		$skins = array(
			'title'   => __( 'Skin', 'vpc' ),
			'name'    => 'vpc-config[skin]',
			'type'    => 'select',
			'options' => $skins_arr,
			'default' => '',
			'desc'    => __( 'Editor look and feel.', 'vpc' ),
		);

		$components_default_aspect = array(
			'title'   => __( 'Components default aspect', 'vpc' ),
			'name'    => 'vpc-config[components-aspect]',
			'type'    => 'select',
			'options' => array(
				'opened' => __( 'Opened', 'vpc' ),
				'closed' => __( 'Closed', 'vpc' ),
			),
			'default' => '',
			'desc'    => __( 'Wether or not all components in the configuration should be opened or closed when the editor is loaded.', 'vpc' ),
		);

		$components_aspect_on_click = array(
			'title'   => __( 'Components behavior on click', 'vpc' ),
			'name'    => 'vpc-config[components-behavior-on-click]',
			'type'    => 'select',
			'options' => array(
				'leave-others-opened' => __( 'Leave others components opened', 'vpc' ),
				'close-others'        => __( 'Close others components', 'vpc' ),
			),
			'default' => '',
			'desc'    => __( 'What to do when the user clicks on a component.', 'vpc' ),
		);
		$config_description         = array(
			'title'   => __( 'Configurator description', 'vpc' ),
			'name'    => 'vpc-config[config-desc]',
			'id'      => 'config-desc-editor',
			'type'    => 'texteditor',
			'desc'    => __( 'Description of configurator on the configurator page to help the customer understand what to do', 'vpc' ),
			'default' => '',
		);

		$form_builder_selector = $this->create_form_builder_selector();
		$skin_end              = array( 'type' => 'sectionend' );
		$skin_settings         = apply_filters(
			'vpc_skins_settings',
			array(
				$skin_begin,
				$skins,
				$components_default_aspect,
				$components_aspect_on_click,
				$config_description,
				$form_builder_selector,
				$skin_end,
			)
		);
		echo wp_kses( o_admin_fields( $skin_settings ), get_allowed_tags() );
		?>
		<script>
		var vpc_components_skins =<?php echo wp_json_encode( $components_skins_dropdowns ); ?>;
		</script>
		<!--</div>-->
		<?php
		$begin        = array(
			'type' => 'sectionbegin',
			'id'   => 'vpc-config-container',
		);
		$component_id = array(
			'title' => __( 'ID', 'vpc' ),
			'name'  => 'component_id',
			'type'  => 'text',
			'class' => 'vpc-component-id',
		);
		$cname        = array(
			'title' => __( 'Name', 'vpc' ),
			'name'  => 'cname',
			'type'  => 'text',
			'class' => 'vpc-cname',
			'desc'  => __( 'Component name', 'vpc' ),
		);

		$cbehaviour = array(
			'title'   => __( 'Behaviour', 'vpc' ),
			'name'    => 'behaviour',
			'type'    => 'select',
			'options' => vpc_get_behaviours(),
			'class'   => 'vpc-behaviour',
			'default' => 'radio',
		);

		$c_image = array(
			'title'    => __( 'Icon', 'vpc' ),
			'name'     => 'cimage',
			'url_name' => 'cimage_url',
			'type'     => 'image',
			'set'      => 'Set',
			'remove'   => 'Remove',
			'desc'     => __( 'Component icon', 'vpc' ),
		);

		$options             = vpc_get_options_fields( $config_id );
		$component_index     = array(
			'title'   => __( 'Index', 'vpc' ),
			'name'    => 'c_index',
			'type'    => 'number',
			'class'   => 'vpc-component',
			'default' => '1',
		);
		$duplicate_component = array(
			'title' => __( 'Duplicate', 'vpc' ),
			'name'  => 'duplicate_component',
			'type'  => 'button',
			'class' => 'button duplicate',
		);

		$components = apply_filters(
			'vpc_components_fields',
			array(
				'title'           => __( 'Components', 'vpc' ),
				'name'            => 'vpc-config[components]',
				'type'            => 'repeatable-fields',
				'id'              => 'vpc-config-components-table',
				'fields'          => array( $component_id, $cname, $c_image, $cbehaviour, $options, $component_index, $duplicate_component ),
				'desc'            => __( 'Component options', 'vpc' ),
				'row_class'       => 'vpc-component-row',
				'ignore_desc_col' => true,
				'class'           => 'striped',
				'add_btn_label'   => __( 'Add component', 'vpc' ),
			),
			$config_id
		);

		$end      = array( 'type' => 'sectionend' );
		$settings = apply_filters(
			'vpc_components_settings',
			array(
				$begin,
				$components,
				$end,
			)
		);
		echo wp_kses( o_admin_fields( $settings ), get_allowed_tags() );
		global $o_row_templates;
		?>
	</div>
	<script>
		var o_rows_tpl =<?php echo wp_json_encode( $o_row_templates ); ?>;
	</script>
		<?php
	}

	/**
	 * Create form builder selector.
	 */
	private function create_form_builder_selector() {
		$form_builder_selector = array();
		if ( class_exists( 'Ofb' ) ) {
			global $wpdb;
			$ofb       = array( 'None' => 'None' );
			$resultats = $wpdb->get_results( "SELECT ID,post_title From {$wpdb->prefix}posts Where post_type = 'ofb' And post_status = 'publish'", OBJECT_K ); //phpcs:ignore
			if ( $resultats ) {
				foreach ( $resultats as $index => $data ) {
					$ofb[ $data->ID ] = $data->post_title;
				}
			}
			$form_builder_selector = array(
				'title'   => __( 'selects the form to display on the configurator', 'vpc' ),
				'name'    => 'vpc-config[ofb_id]',
				'type'    => 'select',
				'options' => $ofb,
				'default' => 'None',
				'class'   => 'chosen_ofb',
				'desc'    => __( 'selects the form to display on the configurator', 'vpc' ),
			);
		}
		return $form_builder_selector;
	}

	/**
	 * Get configuration preview page.
	 */
	public function get_config_preview_page() {
		global $vpc_settings;
		$products       = array();
		$config_id      = get_the_ID();
		$config_metas   = get_post_meta( $config_id, 'vpc-config', true );
		$components     = get_proper_value( $config_metas, 'components', array() );
		$product_link   = get_proper_value( $vpc_settings, 'product-link', 'No' );
		$preview_images = array();
		if ( is_array( $config_metas ) ) {
			$config_metas = array();
		}
		if ( 'Yes' === $product_link && class_exists( 'Woocommerce' ) ) {
			$products = vpc_get_all_products_for_select2_array();
		}
		?>
	<div id="vpc-preview">
		<?php
		foreach ( $components as $component ) {

			if ( isset( $component['options'] ) && is_array( $component['options'] ) ) {
				foreach ( $component['options'] as $option ) {
					$is_default   = get_proper_value( $option, 'default', false );
					$option_image = get_proper_value( $option, 'image', '' );
					if ( $is_default && ! empty( $option_image ) ) {
						$option_image                                 = o_get_proper_image_url( $option_image );
						$preview_images[ $component['component_id'] ] = $option_image;

						break;
					}
				}
			}
		}
		?>
	</div>
	<script>
		var vpc_preview_images = <?php echo wp_json_encode( $preview_images ); ?>;
		var vpc_products = [<?php echo wp_kses( implode( ',', $products ),get_allowed_tags() ); ?>];
	</script>
		<?php
		do_action( 'vpc_after_preview' );
	}

	/**
	 * Saves the meta
	 *
	 * @param type $post_id  The post id.
	 */
	public function save_config( $post_id ) {
		$meta_key = 'vpc-config';
		if ( isset( $_POST[ $meta_key ] ) ) {  //phpcs:ignore

			$new_metas = $_POST[ $meta_key ]; //phpcs:ignore
			$old_metas = get_post_meta( $post_id, $meta_key, true );
			foreach ( $new_metas['components'] as $i => $component ) {
				if ( isset( $component['options'] ) ) {
					continue;
				}
				$searched_component                       = vpc_find_component_by_id( $old_metas, $component['component_id'] );
				$new_metas['components'][ $i ]['options'] = $searched_component['options'];
			}
			if ( isset( $old_metas['conditional_rules'] ) ) {
				$new_metas['conditional_rules'] = $old_metas['conditional_rules'];
			}
			if ( isset( $old_metas['new_rules'] ) ) {
				$new_metas['new_rules'] = $old_metas['new_rules'];
			}
			$new_metas = vpc_fill_missing_components_and_options_names( $new_metas );
			update_post_meta( $post_id, $meta_key, $new_metas );
		}
	}

	/**
	 * Get skin components dropdowns contents.
	 *
	 * @param array $components_skins  All skin name.
	 */
	private function get_skin_components_dropdowns_contents( $components_skins ) {
		$components_skins_dropdowns = array();
		foreach ( $components_skins as $skin_class => $skin_data ) {
			$html = '';
			foreach ( $skin_data as $skin_name => $skin_label ) {
				$html .= "<option value='$skin_name'>$skin_label</option>";
			}
			$components_skins_dropdowns[ $skin_class ] = $html;
		}

		return $components_skins_dropdowns;
	}

	/**
	 * Get all configuration.
	 */
	public function get_all() {
		$args      = array(
			'post_type'   => 'vpc-config',
			'post_status' => 'publish',
			'nopaging'    => true,
		);
		$lists     = get_posts( $args );
		$lists_arr = array();
		foreach ( $lists as $list ) {
			$lists_arr[ $list->ID ] = $list->post_title;
		}
		return $lists_arr;
	}

	/**
	 * Get configuration conditional rules page.
	 */
	public function get_config_conditional_rules_page() {
		$current_configuration = get_post_meta( get_the_ID(), 'vpc-config', true );
		$this->pc_active_part  = $current_configuration;
		// Localize conditional rules builder datas.
		// Todo: get saved rules.
		$wvpc_cl_trigger = array(
			'on_selection'   => __( 'Is selected', 'vpc' ),
			'on_deselection' => __( 'is deselected', 'vpc' ),
		);

		$wvpc_cl_scope = array(
			'option'              => __( 'Option', 'vpc' ),
			'component'           => __( 'Component', 'vpc' ),
			'group_per_component' => __( 'Group per component', 'vpc' ),
			'groups'              => __( 'All groups', 'vpc' ),
		);

		$wvpc_cl_action = array(
			'show'   => __( 'Show', 'vpc' ),
			'hide'   => __( 'Hide', 'vpc' ),
			'select' => __( 'Select', 'vpc' ),
		);

		$wvpc_cl_group_container_tpl = '<div class = "wvpc-rules-group-container">'
		. ' <div>  '
		. '<table class="wvpc-rules-table widefat"><tbody>' . $this->wvpc_set_group_rule_tpl_head() . '{rule-group}</tbody></table> '
		. ' </div> '
		. '{enable-reverse-cb}'
		. '<div class = "remove remove-group"> <a class=" button wvpc-remove-rule">' . __( 'Remove rule', 'vpc' ) . '</a></div></div>';

		$wvpc_admin_data = array(
			'wvpc_conditional_rule_container'     => $this->wvpc_set_conditional_rules_container_tpl(),
			'wvpc_conditional_rule_tpl'           => $this->wvpc_get_conditionnal_rule_tpl(),
			'wvpc_conditional_rule_tpl_first_row' => $this->wvpc_get_conditionnal_rule_tpl( true ),
			'wvpc_cl_group_container_tpl'         => $wvpc_cl_group_container_tpl,
			// @codingStandardsIgnoreStart 
			// 'wvpc_conditional_rules' => $conditional_rules,  
			// @codingStandardsIgnoreEnd
			'wvpc_cl_trigger'                     => $wvpc_cl_trigger,
			'wvpc_cl_scope'                       => $wvpc_cl_scope,
			'wvpc_cl_action'                      => $wvpc_cl_action,
			'current_configuration'               => $current_configuration,
		);
		?>
	<script>
		var wvpc_cond_rules_data = <?php echo wp_json_encode( $wvpc_admin_data ); ?>;
	</script>

	<div class="wvpc-conditional-rule-wrap">
		<?php
		$wvpc_conditional_rule_container = $this->wvpc_set_conditional_rules_container_tpl();
		echo wp_kses( $wvpc_conditional_rule_container, get_allowed_tags() );
		?>
	</div>
		<?php
	}

	/**
	 * Set conditional rules container template.
	 */
	public function wvpc_set_conditional_rules_container_tpl() {

		$conditional_rules_is_checked = '';
		if ( ! empty( $this->pc_active_part ) ) {

			if ( isset( $this->pc_active_part['conditional_rules'] ) && isset( $this->pc_active_part['conditional_rules']['enable_rules'] ) && 'enabled' === $this->pc_active_part['conditional_rules']['enable_rules'] ) {
				$conditional_rules_is_checked = 'checked="checked"';
			}
		}

		ob_start()
		?>
	<div id="grid-container" class="wvpc-conditional-logic-main-container">
		<div class='block-form'>
		<div class="wvpc-conditional-logic-form">
			<table class="wp-list-table widefat fixed pages">
			<tbody>
				<tr>
				<td class='label'>
					<?php esc_html_e( 'Enable Conditional Logic', 'vpc' ); ?>
					<div class='desc'>

					</div>
				</td>
				<td class='grid-src-type'>
					<input type="checkbox" name="vpc-config[conditional_rules][enable_rules]" class="wvpc_enable_conditional_logic" value="enabled" <?php echo esc_attr( $conditional_rules_is_checked ); ?>/>
				</td>
				</tr>

				<?php
				if ( isset( $this->pc_active_part['conditional_rules']['enable_rules'] ) && 'enabled' === $this->pc_active_part['conditional_rules']['enable_rules'] ) {
					?>
					<tr class="wvpc-conditional-logic-container wvpc-wvpc-conditional-logic-tr">
					<?php
				} else {
					?>
					<tr class="wvpc-conditional-logic-container wvpc-wvpc-conditional-logic-tr" style="display: none;">
					<?php
				}
				?>

				<td class='label'>
					<?php esc_html_e( 'Rules', 'vpc' ); ?>
					<div class='desc'>

					</div>
				</td>
				<td>
					<div class='wvpc-rules-table-container'>
					{rules-editor}

					</div>
					<a class="button wvpc-add-group">Add rule</a>
				</td>
				</tr>
			</tbody>
			</table>
		</div>
		</div>
	</div>

		<?php
		$wvpc_conditional_rule_container = ob_get_contents();
		ob_end_clean();
		return $wvpc_conditional_rule_container;
	}

	// Conditionnal rule editor: single rule template.
	/**
	 * Get conditionnal rule template.
	 *
	 * @param bool $is_first_row  Verify template row position.
	 */
	public function wvpc_get_conditionnal_rule_tpl( $is_first_row = false ) {

		ob_start();
		?>
	<tr data-id="rule_{rule-group-index}" class="wvpc-rules-table-tr">


		<?php
		if ( $is_first_row ) {
			?>
			<td class="wvpc-shared-td">
			{wvpc-extraction-group-action}
			</td>
			<td class="wvpc-shared-td">
			{wvpc-extraction-group-scope}
			</td>
			<td class="wvpc-shared-td">
			<select name="vpc-config[conditional_rules][groups][{rule-group-index}][result][apply_on]" id="wvpc-group_{rule-group-index}_rule_{rule-index}_apply_on" class="select wvpc-extraction-group-apply_on">
				{wvpc-extraction-group-apply_on}
			</select>
			</td>
			<?php
		}
		?>
		<td class="vpc-cl-option-td">
		<?php esc_html_e( 'IF', 'vpc' ); ?>
		<select name="vpc-config[conditional_rules][groups][{rule-group-index}][rules][{rule-index}][option]" id="wvpc-group_{rule-group-index}_rule_{rule-index}_option" class="select wvpc-extraction-group-option">
			{wvpc-extraction-group-option}
		</select>
		</td>
		<td>
		{wvpc-extraction-group-trigger}
		</td>

		<td class="add">
		<a class="wvpc-add-rule" data-group='{rule-group-index}'>
			<?php // phpcs:ignore // _e("add","vpc"); ?>
		</a>
		</td>

		<td class="remove">
		<a class="wvpc-remove-rule acf-button-remove"></a>
		</td>
	</tr>
		<?php
		$rule_tpl = ob_get_contents();
		ob_end_clean();
		return $rule_tpl;
	}

	/**
	 * Set conditionnal group rule template header.
	 */
	public function wvpc_set_group_rule_tpl_head() {
		ob_start();
		?>
	<tr>
		<th>
		<?php esc_html_e( 'Action', 'vpc' ); ?>
		</th>
		<th>
		<?php esc_html_e( 'Scope', 'vpc' ); ?>
		</th>
		<th>
		<?php esc_html_e( 'Apply on', 'vpc' ); ?>
		</th>
		<th>
		<?php esc_html_e( 'Option', 'vpc' ); ?>
		</th>
		<th>
		<?php esc_html_e( 'Status', 'vpc' ); ?>
		</th>
		<th></th>
	</tr>
		<?php
		$group_rule_head = ob_get_contents();
		ob_end_clean();
		return $group_rule_head;
	}

	/**
	 * Adds new tabs in the product page
	 */
	public function get_product_tab_label() {
		?>
	<li class="vpc-config-selection"><a href="#vpc_config_data"><?php esc_html_e( 'Configuration', 'vpc' ); ?></a></li>
		<?php
	}
	/**
	 * Get product variations.
	 *
	 * @param string $product_id  Product id.
	 */
	private function get_product_variations( $product_id ) {
		$product        = wc_get_product( $product_id );
		$variations_arr = array();
		$variations     = $product->get_available_variations();
		foreach ( $variations as $variation ) {
			$variation_id                    = $variation['variation_id'];
			$variations_arr[ $variation_id ] = array();
			$attributes                      = $variation['attributes'];
			$attributes_str                  = '';
			foreach ( $attributes as $attribute ) {
				array_push( $variations_arr[ $variation_id ], $attribute );
			}
		}
		return $variations_arr;
	}

	/**
	 * Get product tab datas.
	 */
	public function get_product_tab_data() {
		$id      = get_the_ID();
		$product = wc_get_product( $id );

		$args        = array(
			'post_type' => 'vpc-config',
			'nopaging'  => true,
		);
		$configs     = get_posts( $args );
		$configs_ids = array( '' => 'None' );
		foreach ( $configs as $config ) {
			$configs_ids[ $config->ID ] = $config->post_title;
		}
		?>
	<div id="vpc_config_data" class="panel woocommerce_options_panel">
		<?php
		if ( 'variable' === $product->get_type() ) {
			$variations_arr = $this->get_product_variations( $id );
			foreach ( $variations_arr as $variation_id => $attributes ) {
				if ( ! is_array( $attributes ) ) {
					continue;
				}
				$attributes_str = implode( ' ', $attributes );

				$this->get_product_tab_row( $variation_id, $configs_ids, $attributes_str );
			}
		} else {
			$this->get_product_tab_row( $id, $configs_ids, 'Configuration' );
		}
		?>

	</div>
		<?php
	}

	/**
	 * Get product configuration selector.
	 */
	public function get_product_config_selector() {
		$id = get_the_ID();

		$args        = array(
			'post_type' => 'vpc-config',
			'nopaging'  => true,
		);
		$configs     = get_posts( $args );
		$configs_ids = array( '' => 'None' );
		foreach ( $configs as $config ) {
			$configs_ids[ $config->ID ] = $config->post_title;
		}
		?>
	<div id="vpc_config_data" class="show_if_simple">
		<?php
		$this->get_product_tab_row( $id, $configs_ids, 'Configuration' );
		?>
	</div>
		<?php
	}

	/**
	 * Set variables product configuration form.
	 *
	 * @param string $loop            The loop.
	 * @param string $variation_data  Product variation data.
	 * @param object $variation       Variation of product.
	 */
	public function wvpc_variable_fields( $loop, $variation_data, $variation ) {
		$id = $variation->ID;
		// phpcs:ignore // $wpb_product_configurator = self::get_product_config($variation_post_id);
		// phpcs:ignore // $id=  get_the_ID();

		$args        = array(
			'post_type' => 'vpc-config',
			'nopaging'  => true,
		);
		$configs     = get_posts( $args );
		$configs_ids = array( '' => 'None' );
		foreach ( $configs as $config ) {
			$configs_ids[ $config->ID ] = $config->post_title;
		}
		?>
	<tr>
		<td>
		<?php
		// @codingStandardsIgnoreStart
		// woocommerce_wp_radio(array(
		// 'name' => 'wvpc-variation-meta['.$variation_post_id.'][product_config]',
		// 'class' => 'wpb_radio_field',
		// 'id' => 'wpb_product_configurator_'.$variation_post_id,
		// 'options' => $this->get_configuration_list(),
		// 'label' => __('Product Configurator List: ', 'wpb'),
		// 'value' => $wpb_product_configurator,
		// 'wrapper_class' => 'wpb_radio_field_cont',
		//
		// ))
		// @codingStandardsIgnoreEnd
		$this->get_product_tab_row( $id, $configs_ids, 'Configuration' );
		?>
		</td>
	</tr>
		<?php
	}

	/**
	 * Get product tab row.
	 *
	 * @param string $pid         The product ID.
	 * @param string $configs_ids The configuration ID.
	 * @param string $title       The row title.
	 */
	private function get_product_tab_row( $pid, $configs_ids, $title ) {
		$pages_ids = get_all_pages_exclude_woocommerce_pages();
		$begin     = array(
			'type' => 'sectionbegin',
			'id'   => 'vpc-config-data',
		);

		$configurations = array(
			'title'   => $title,
			'name'    => "vpc-config[$pid][config-id]",
			'type'    => 'select',
			'options' => $configs_ids,
		);

		$edit_link_for_configuration_page = array(
			'title'   => 'Edit page',
			'name'    => "vpc-config[$pid][config-edit-link]",
			'type'    => 'text',
			'type'    => 'select',
			'options' => $pages_ids,
			'default' => '',
			'desc'    => __( 'Page where this product will edit.', 'vpc' ),
		);

		$end      = array( 'type' => 'sectionend' );
		$settings = apply_filters(
			'vpc_product_tab_settings',
			array(
				$begin,
				$configurations,
				$edit_link_for_configuration_page,
				$end,
			),
			$pid,
			$configs_ids,
			$title
		);

		echo "<div class='vpc-product-config-row'>" . wp_kses( o_admin_fields( $settings ), get_allowed_tags() ) . '</div>';
	}

	/**
	 * Filter component by name.
	 */
	public function get_components_by_name() {
		$components = array();
		foreach ( $this->settings['components'] as $component ) {
			$options = array();
			foreach ( $component['options'] as $option ) {
				$options[ $option['name'] ] = $option;
			}
			$components[ $component['cname'] ] = $options;
		}

		return $components;
	}

	/**
	 * Save new field for product variation.
	 *
	 * @param string $variation_id The product variation ID.
	 */
	public function save_variation_settings_fields( $variation_id ) {
		$meta_key = 'vpc-config';
		if ( isset( $_POST[ $meta_key ] ) ) { // phpcs:ignore
			$variation = wc_get_product( $variation_id );
			if ( 'WC_Product_Variation' === get_class( $variation ) ) {
				$product_id = $variation->get_parent_id();
			} else {
				$product_id = $variation_id;
			}

			// Careful this hooks only send the updated data, not the complete form.
			$old_metas = get_post_meta( $product_id, $meta_key, true );
			if ( empty( $old_metas ) ) {
				$old_metas = array();
			}
			$new_metas = array_replace( $old_metas, $_POST[ $meta_key ] ); // phpcs:ignore
			update_post_meta( $product_id, $meta_key, $new_metas );
		}
	}

	/**
	 * Save product configuration.
	 *
	 * @param string $root_id The configuration ID.
	 */
	public function save_product_configuration( $root_id ) {
		$meta_key = 'vpc-config';
		if ( isset( $_POST[ $meta_key ] ) ) { // phpcs:ignore
			$old_metas = get_post_meta( $root_id, $meta_key, true );
			if ( empty( $old_metas ) ) {
				$old_metas = array();
			}
			$new_metas = array_replace( $old_metas, $_POST[ $meta_key ] ); // phpcs:ignore
			update_post_meta( $root_id, $meta_key, $new_metas );
		}
	}

	/**
	 * Filters the column headers for a list table on a specific screen.
	 *
	 * @param string $defaults The column header labels keyed by column ID.
	 */
	public function add_rules_columns( $defaults ) {
		$defaults['vpc_manage_rules'] = __( 'Manage rules', 'vpc' );
		return $defaults;
	}

	/**
	 * Fires for each custom column of a specific post type in the Posts list table.
	 *
	 * @param string $column_name The name of the column to display.
	 * @param int    $id          The current post ID.
	 */
	public function get_rules_columns_values( $column_name, $id ) {

		if ( 'vpc_manage_rules' === $column_name ) {
			$url = admin_url( "edit.php?post_type=vpc-config&page=manage-conditional-rules&config-id=$id" );
			echo wp_kses( "<a class='button button-primary button-large ' href='$url'>" . __( 'Manage conditional rules', 'vpc' ) . '</a>', get_allowed_tags() );
		}
	}

}
