<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://www.orionorigin.com
 * @since      1.0.0
 *
 * @package    Vpc
 * @subpackage Vpc/skins
 */

/**
 * Description of class-vpc-default-skin
 *
 * @author HL
 */
class VPC_Right_Sidebar_Skin {

	/**
	 * The ID of configuration.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      object $product    The product object.
	 */
	public $product;
	/**
	 * The ID of configuration.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $product_id    The product id.
	 */
	public $product_id;

	/**
	 * The setting of configuration.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      object $settings    The configurator setting.
	 */
	public $settings;

	/**
	 * The setting of configuration.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      object $config    The configurator datas.
	 */
	public $config;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $product_id  The configurator id.
	 * @param      string $config      The configurator datas.
	 */
	public function __construct( $product_id = false, $config = false ) {
		if ( $product_id ) {
			if ( vpc_woocommerce_version_check() ) {
				$this->product = new WC_Product( $product_id );
			} else {
				$this->product = wc_get_product( $product_id );
			}
			$this->product_id = $product_id;

			$this->config = get_product_config( $product_id );
		} elseif ( $config ) {
			$this->config = new VPC_Config( $config );
		}
	}

	/**
	 * Function to dispay configurator.
	 *
	 * @since    1.0.0
	 * @param      array $config_to_load    Save configurator datas.
	 */
	public function display( $config_to_load = array() ) {

		vpc_skins_enqueue_styles_scripts( 'VPC_Right_Sidebar_Skin' );
		ob_start();

		if ( ! $this->config || empty( $this->config ) ) {
			return __( 'No valid configuration is linked to this product. Please review.', 'vpc' );
		}

		$skin_name = get_class( $this );

		$config = $this->config->settings;

		$options_style     = '';
		$components_aspect = get_proper_value( $config, 'components-aspect', 'closed' );
		if ( 'closed' === $components_aspect ) {
			$options_style = 'display: none';
		}
		$product_id = '';
		if ( class_exists( 'Woocommerce' ) ) {
			if ( vpc_woocommerce_version_check() ) {
				$product_id = $this->product->id;
			} else {
				$product_id = $this->product->get_id();
			}
			do_action( 'vpc_before_container', $config, $product_id, $this->config->id );
		}
		$conf_desc = get_configurator_description( $config );
		$conf_desc = apply_filters( 'vpc_configurator_description', $conf_desc, $config, $product_id );
		?>
	<div id="vpc-container" class="o-wrap <?php echo esc_attr( $skin_name ); ?>" data-curr="<?php echo ( class_exists( 'Woocommerce' ) ) ? esc_attr( get_woocommerce_currency_symbol() ) : ''; ?>">
		<?php
		vpc_get_configurator_loader();
		do_action( 'vpc_before_inside_container', $config, $product_id, $this->config->id );
		?>
		<div class="o-col conf_desc"><?php echo wp_kses_post( html_entity_decode( htmlentities( $conf_desc ) ) ); ?></div>

		<div class="o-col xl-2-3 lg-2-3 md-1-1 sm-1-1" id="vpc-preview-wrap">

		<div class="default-right-skin">
			<?php
			if ( class_exists( 'Woocommerce' ) ) {
				vpc_get_price_container( $this->product->get_id() );}
			?>
		</div>
		<?php
		$preview_html = '<div id="vpc-preview"></div>';
		$preview_html = apply_filters( 'vpc_preview_container', $preview_html, $product_id, $this->config->id );
		echo wp_kses( html_entity_decode( htmlentities( $preview_html ) ), get_allowed_tags() );
		
		do_action( 'vpc_after_preview_area', $config, $product_id, $this->config->id );
		?>
		</div>

		<div class="o-col xl-1-3 lg-1-3 md-1-1 sm-1-1" id="vpc-components">
		<?php
		do_action( 'vpc_before_components', $config, $product_id );
		if ( isset( $config['components'] ) ) {
			foreach ( $config['components'] as $component_index => $component ) {
				$this->get_components_block( $component, $options_style, $config, $config_to_load );
			}
		}
		do_action( 'vpc_after_components', $config, $product_id, $config_to_load );
		?>
		</div>
		<div id="vpc-bottom-limit"></div>
		<div id="vpc-form-builder-wrap">
		<?php
		if ( class_exists( 'Ofb' ) ) {
			if ( isset( $config['ofb_id'] ) ) {
				$form_builder_id = $config['ofb_id'];
				$form            = display_form_builder( $form_builder_id, $config_to_load );
				echo wp_kses( $form, get_allowed_tags() );
			}
		}
		?>
		</div>
		<div class="vpc-action-buttons o-col xl-1-1 o-left-offset-2-3">
		<div class="o-col xl-1-1">

		<?php echo wp_kses( vpc_get_action_buttons( $this->product_id ), get_allowed_tags() ); ?>
			<?php
			if ( class_exists( 'Vpc_Sfla' ) ) {
				$save_class = new Vpc_Sfla_Public( false, VPC_SFLA_VERSION );
				echo wp_kses( $save_class->get_sfla_buttons( $config, $product_id ), get_allowed_tags() );
			}
			if ( class_exists( 'Vpc_Ssa' ) ) {
				$save = new Vpc_Ssa_Public( false, VPC_SSA_VERSION );
				echo wp_kses( $save->get_ssa_buttons( $product_id ) , get_allowed_tags());
			}
			?>
		</div>
		</div>
		<div>
		<?php
		if ( class_exists( 'Vpc_Sfla' ) ) {
			$save_class = new Vpc_Sfla_Public( false, VPC_SFLA_VERSION );
			echo wp_kses( $save_class->get_all_configs( $config, $product_id ), get_allowed_tags() );
		}
		?>
		</div>
		<div id="debug"></div>
		<div class="vpc-debug">
		<?php do_action( 'vpc_container_end', $config, $this->product_id ); ?>
		</div>
	</div>

		<?php
		do_action( 'vpc_after_container_block', $config, $this->product_id );
		$output = ob_get_contents();
		ob_end_clean();
		return $output;
	}

	/**
	 * Function to display components block.
	 *
	 * @since    1.0.0
	 * @param      array  $component        A component's datas.
	 * @param      string $options_style    Options's container style.
	 * @param      array  $config           A configuration's datas.
	 * @param      array  $config_to_load   A configuration old selected options datas.
	 */
	private function get_components_block( $component, $options_style, $config, $config_to_load = array() ) {
		global $vpc_settings, $WOOCS; // phpcs:ignore
		$skin_name = get_class( $this );
		$c_icon    = '';
		$options   = '';
		if ( isset( $component['options'] ) ) {
			$options = $component['options'];
		}
		if ( $options ) {
			$options = sort_options_by_group( $options );
		}
		$component_id = 'component_' . sanitize_title( str_replace( ' ', '', $component['cname'] ) );
		$component_id = get_proper_value( $component, 'component_id', sanitize_title( $component_id ) );

		// We make sure we have an usable behaviour.
		$handlable_behaviours = vpc_get_behaviours();
		if ( ! isset( $handlable_behaviours[ $component['behaviour'] ] ) ) {
			$component['behaviour'] = 'radio';
		}

		if ( $component['cimage'] ) {
			$img_url     = o_get_proper_image_url( $component['cimage'] );
			$o_image_alt = '';
			if ( is_numeric( $component['cimage'] ) ) {
				$img_alt = get_post_meta( $component['cimage'], '_wp_attachment_image_alt', true );
			} else {
				$img_alt = $component['cname'] . ' icon';
			}
		}
		if ( isset( $img_url ) && ! empty( $c_img_url ) ) {
			$image_resize = aq_resize( $img_url, 60, 60, true );
			$c_icon       = "<img src='" . $image_resize . "' alt='" . $img_alt . "'>";
			if ( ! $c_icon ) {
				$c_icon = $img_url;
			}
		}
		// phpcs:ignore // $c_icon = "<img src='" . o_get_proper_image_url($component["cimage"]) . "'>";

		$components_attributes_string = apply_filters( 'vpc_component_attributes', "data-component_id = $component_id", $this->product_id, $component );
		?>
	<div id = '<?php echo esc_attr( $component_id ); ?>' class="vpc-component" <?php echo esc_attr( $components_attributes_string ); ?>>

		<div class="vpc-component-header">
		<?php
		echo wp_kses( $c_icon, get_allowed_tags() ) . "<span style='display: inline-block;'><span>" . esc_html( $component['cname'] ) . '</span>';
		?>

		<span class="vpc-selected txt"><?php esc_attr_e( 'none', 'vpc' ); ?></span></span>
		<span class="vpc-selected-icon"><img width="24" src="" alt="Visual Products Configurator option selected icon image"></span>

		</div>
		<div class="vpc-options" style="<?php echo esc_attr( $options_style ); ?>">
		<?php
		do_action( 'vpc_' . $component['behaviour'] . '_begin', $component, $skin_name );
		$current_group = '';
		if ( ! is_array( $options ) || empty( $options ) ) {
			esc_attr_e( 'No option detected for the component. You need at least one option per component for the configuration to work properly.', 'vpc' );
		} else {
			$product_id = $this->product_id; // get_query_var("vpc-pid", false); // phpcs:ignore
			// WAD compatibility.
			$discount_rate = 0;
			if ( function_exists( 'vpc_get_discount_rate' ) ) {
				$discount_rate = vpc_get_discount_rate( $product_id );
			}

			foreach ( $options as $option_index => $option ) {
				// @codingStandardsIgnoreStart
				/*
				if ( '' == $option['name'] ) {
				if ( intval( $option_index ) == intval( count( $options ) - 1 ) ) {
				echo '</div>';
				}
				continue;
				} */
				// @codingStandardsIgnoreEnd
				if ( ( $option['group'] !== $current_group ) || ( 0 === $option_index ) ) {
					if ( 0 !== $option_index ) {
						if ( 'dropdown' === $component['behaviour'] ) {
							echo '</select>';
						}
						echo '</div>';
					}
					echo "<div class='vpc-group'><div class='vpc-group-name'>" . esc_html( $option['group'] ) . '</div>'; // phpcs:ignore // ."</div>";// . "<br>";
					if ( 'dropdown' === $component['behaviour'] ) {
						if ( '' !== $option['group'] ) {
							$select_name = $component['cname'] . ' ' . $option['group'];
						} else {
							$select_name = $component['cname'];
						}
						?>
				<select name="<?php echo esc_attr( $select_name ); ?>" id="<?php echo esc_attr( $component_id ); ?>">
					<option value=''><?php esc_html_e( 'Choose an option...', 'vpc' ); ?></option>
						<?php
					}
				}
				$current_group = $option['group'];
				$opt_img_id    = get_proper_value( $option, 'image' );
				$o_image       = o_get_proper_image_url( $opt_img_id );
				$o_image_alt   = '';
				if ( is_numeric( $opt_img_id ) ) {
					$o_image_alt = get_post_meta( $opt_img_id, '_wp_attachment_image_alt', true );
				} else {
					$o_image_alt = $option['name'] . ' image';
				}
				$opt_icon_id = get_proper_value( $option, 'icon' );
				$o_img_url   = o_get_proper_image_url( $opt_icon_id );
				$o_icon      = ' ';
				if ( ! empty( $o_img_url ) ) {
					$o_icon = aq_resize( $o_img_url, get_proper_value( $vpc_settings, 'default-icon-width', 25 ), get_proper_value( $vpc_settings, 'default-icon-height', 25 ), true );
				}
				if ( ! $o_icon ) {
					$o_icon = $o_img_url;
				}
				$o_name        = $component['cname'];
				$name_tooltip  = get_proper_value( $vpc_settings, 'view-name' );
				$price_tooltip = get_proper_value( $vpc_settings, 'view-price' );

				// phpcs:ignore // $input_id = uniqid();
				// phpcs:ignore // $label_id = "cb$input_id";

				$checked = '';
				if ( $config_to_load && isset( $config_to_load[ $component['cname'] ] ) ) {
					$saved_options = $config_to_load[ $component['cname'] ];
					if ( ( is_array( $saved_options ) && in_array( trim( $option['name'] ), $saved_options, true ) ) || ( trim( $option['name'] ) === trim( $saved_options ) )
					) {
						$checked = 'checked=checked';
					}
				} elseif ( isset( $option['default'] ) && 1 === intval( $option['default'] ) ) {
					$checked = 'checked=checked data-default=1';
				}

				$price = get_proper_value( $option, 'price', 0 );
				if ( strpos( $price, ',' ) ) {
					$price = floatval( str_replace( ',', '.', $price ) );
				}
				if ( '' === $price ) {
					$price = 0;
				}
				$price              = $price - $price * $discount_rate;
				$price              = vpc_apply_taxes_on_price_if_needed( $price, $this->product );
				$linked_product     = get_proper_value( $option, 'product', false );
				$formated_price_raw = 0;
				if ( $linked_product ) {
					$price = 0;
					if ( class_exists( 'Woocommerce' ) ) {
						if ( vpc_woocommerce_version_check() ) {
							$product = new WC_Product( $linked_product );
						} else {
							$product = wc_get_product( $linked_product );
						}
						if ( ! $product ) {
							continue;
						}
						$skip_option = apply_filters( 'vpc_skip_option', true, $option );
						if ( $skip_option ) {
							if ( ! $product->is_purchasable() || ( $product->managing_stock() && ! $product->is_in_stock() ) ) {
								if ( count( $options ) - 1 === $option_index ) {
									echo '</div>';
								}
								continue;
							}
						} else {
							do_action( 'vpc_action_to_skip_option', $option, $component );
						}

						// phpcs:ignore // $price     = $product->get_price();
						$price = $product->get_regular_price();
						if( $product->is_on_sale() ) 
							$price = $product->get_sale_price();
						$price = vpc_apply_taxes_on_price_if_needed( $price, $product );
					}
				}

				if ( $WOOCS ) { // phpcs:ignore
					$currencies = $WOOCS->get_currencies(); // phpcs:ignore
					$price      = $price * $currencies[ $WOOCS->current_currency ]['rate']; // phpcs:ignore
				}

				// WAD COMPATIBILITY.
				if ( function_exists( 'vpc_apply_wad_discount_on_option' ) && ! $linked_product ) {
					// Apply discount on option if applicable.
					$price = vpc_apply_wad_discount_on_option( $this->product_id, $price );
				}
				if ( function_exists( 'vpc_apply_wad_discount_on_linked_product_option' ) ) {
					$price = vpc_apply_wad_discount_on_linked_product_option( $this->product_id, $linked_product, $price );
				}

				$price = apply_filters( 'vpc_options_price', $price, $option, $component, $this );
				if ( class_exists( 'Woocommerce' ) ) {
					$formated_price_raw = wc_price( $price );
				}

				if ( apply_filters( 'vpc_option_visibility', 1, $option ) !== 1 ) {
					if ( count( $options ) - 1 === $option_index ) {
						echo '</div>';
					}
					continue;
				}

				$formated_price = wp_strip_all_tags( $formated_price_raw );
				$option_id      = 'component_' . sanitize_title( str_replace( ' ', '', $component['cname'] ) ) . '_group_' . sanitize_title( str_replace( ' ', '', $option['group'] ) ) . '_option_' . sanitize_title( str_replace( ' ', '', $option['name'] ) );
				$option_id      = get_proper_value( $option, 'option_id', $option_id );
				$comp_index     = get_proper_value( $component, 'c_index', 0 );
				if ( empty( $comp_index ) ) {
					$comp_index = 0;
				}
				$customs_datas = " data-index=$comp_index";
				$customs_datas = apply_filters( 'vpc_options_customs_datas', $customs_datas, $option, $component, $config );
				switch ( $component['behaviour'] ) {
					case 'radio':
					case 'checkbox':
						$input_type = 'radio';
						if ( 'checkbox' === $component['behaviour'] ) {
							$o_name    .= '[]';
							$input_type = 'checkbox';
						}

						if ( 'Yes' === $name_tooltip ) {
							$tooltip = $option['name'];
						} else {
							$tooltip = '';
						}
						if ( 'Yes' === $price_tooltip ) {
							if ( strpos( $formated_price, '-' ) !== false || strpos( $formated_price, '+' ) !== false ) {
								$tooltip .= " $formated_price";
							} else {
								$tooltip .= " +$formated_price";
							}
						}
						if ( ! empty( $option['desc'] ) ) {
							$tooltip .= ' (' . $option['desc'] . ')';
						}

						$label_id = "cb$option_id";
						$tooltip  = apply_filters( 'vpc_options_tooltip', $tooltip, $price, $option, $component );
						if ( ! empty( $tooltip ) ) {
							$tooltip = "data-oriontip='" . esc_attr( $tooltip ) . "'";
						}
						?>
					<div class="vpc-single-option-wrap" data-oid="<?php echo esc_attr( $option_id ); ?>" >
					<input id="<?php echo esc_attr( $option_id ); ?>" type="<?php echo esc_attr( $input_type ); ?>" name="<?php echo esc_attr( $o_name ); ?>" value="<?php echo esc_attr( $option['name'] ); ?>" data-component-id="<?php echo esc_attr( $component['component_id'] ); ?>" data-img="<?php echo esc_attr( $o_image ); ?>" data-img-alt="<?php echo esc_attr( $o_image_alt ); ?>" data-icon="<?php echo esc_attr( $o_icon ); ?>" data-price="<?php echo esc_attr( $price ); ?>" data-product="<?php echo isset( $option['product'] ) ? esc_attr( $option['product'] ) : ''; ?>" data-oid="<?php echo esc_attr( $option_id ); ?>" <?php echo esc_attr( $checked ) . ' ' . esc_attr( $customs_datas ); ?>>
					<label id="<?php echo esc_attr( $label_id ); ?>" for="<?php echo esc_attr( $option_id ); ?>" <?php echo wp_kses( $tooltip, get_allowed_tags() ); ?> class="custom"></label>
					<style>
						#<?php echo esc_attr( $label_id ); ?>:before
						{
						background-image: url("<?php echo esc_attr( $o_icon ); ?>");
						line-height: <?php echo esc_attr( get_proper_value( $vpc_settings, 'default-icon-height', 25 ) ) . 'px'; ?>;
						}
						#<?php echo esc_attr( $label_id ); ?> ,#<?php echo esc_attr( $label_id ); ?>:before
						{
						width: <?php echo esc_attr( get_proper_value( $vpc_settings, 'default-icon-width', 25 ) ) . 'px'; ?>;
						height:  <?php echo esc_attr( get_proper_value( $vpc_settings, 'default-icon-height', 25 ) ) . 'px'; ?>;
						}
					</style>
						<?php
						do_action( 'vpc_before_end_' . $component['behaviour'], $option, $o_image, $price, $option_id, $component, $skin_name, $config_to_load, $this->config->settings );
						?>
					</div>
						<?php
						break;

					case 'dropdown':
						$selected = '';
						if ( $config_to_load && ( isset( $config_to_load[ $component['cname'] ] ) || isset( $config_to_load[ $component['cname'] . ' ' . $option['group'] ] ) ) ) {
							if ( '' !== $option['group'] ) {
								$saved_options = $config_to_load[ $component['cname'] . ' ' . $option['group'] ];
							} else {
								$saved_options = $config_to_load[ $component['cname'] ];
							}
							if ( ( is_array( $saved_options ) && in_array( $option['name'], $saved_options, true ) ) || ( $option['name'] === $saved_options )
							) {
								$selected = 'selected';
							}
						} elseif ( isset( $option['default'] ) && 1 === intval( $option['default'] ) ) {
							$selected = 'selected';
						}
						?>
					<option id="<?php echo esc_attr( $option_id ); ?>" value="<?php echo esc_attr( $option['name'] ); ?>" data-component-id="<?php echo esc_attr( $component['component_id'] ); ?>" data-img="<?php echo esc_attr( $o_image ); ?>" data-img-alt="<?php echo esc_attr( $o_image_alt ); ?>" data-icon="<?php echo esc_attr( $o_icon ); ?>" data-price="<?php echo esc_attr( $price ); ?>" data-product="<?php echo isset( $option['product'] ) ? esc_attr( $option['product'] ) : ''; ?>" data-oid="<?php echo esc_attr( $option_id ); ?>" <?php echo esc_attr( $selected ) . ' ' . esc_attr( $customs_datas ); ?>>
						<?php echo esc_html( $option['name'] ); ?>
					</option>
						<?php
						break;

					default:
						// phpcs:ignore // do_action('vpc_'.$skin_name.'_' . $component["behaviour"], $component);
						do_action( 'vpc_' . $component['behaviour'], $option, $o_image, $price, $option_id, $component, $skin_name, $config_to_load, $this->config->settings );
						break;
				}
				do_action( 'vpc_each_' . $component['behaviour'] . '_end', $option, $o_image, $price, $option_id, $component, $skin_name, $config_to_load, $this->config, $option_index );

				if ( count( $options ) - 1 === intval( $option_index ) ) {
					if ( 'dropdown' === $component['behaviour'] ) {
						echo '</select>';
					}
					echo '</div>';
				}
				$current_group = $option['group'];
			}
		}

			do_action( 'vpc_' . $component['behaviour'] . '_end', $component, $this->config, $skin_name );
		?>
		</div>
	</div>
		<?php
	}

}
