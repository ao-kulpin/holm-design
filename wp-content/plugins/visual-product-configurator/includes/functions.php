<?php

/**
 * Functions file
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link  http://www.orionorigin.com
 * @since 1.0.0
 *
 * @package    Vpc
 * @subpackage Vpc/includes
 */

/**
 * Get product configuration.
 *
 * @param string $product_id The product id.
 */
function get_product_config($product_id)
{
    $ids         = get_product_root_and_variations_ids($product_id);
    $config_meta = get_post_meta($ids['product-id'], 'vpc-config', true);
    $configs     = get_proper_value($config_meta, $product_id, array());
    $config_id   = get_proper_value($configs, 'config-id', false);
    $config_id   = apply_filters('vpc_get_product_config', $config_id);

    if (!$config_id || empty($config_id)) {
        return false;
    }
    $config_obj = new VPC_Config($config_id);
    return $config_obj;
}

/**
 * Get configurator price container.
 *
 * @param string $product_id The product id.
 */
function vpc_get_price_container($product_id)
{
    if (is_admin() && !is_ajax()) {
        return;
    }

    $price_container_html = '';
    $price_container_html = '
  <div id="vpc-price-container">
  <span class="vpc-price-label" style="font-weight: normal;color:#768e9d">' . __('Total:', 'vpc') . '</span>
  <span id="vpc-price"></span>
  </div>';
    $price_container_html = apply_filters('vpc_config_price_container', $price_container_html, $product_id);

    echo wp_kses($price_container_html, get_allowed_tags());
}

/**
 * Get configurator product id.
 */
function get_vpc_current_product_id()
{
    global $vpc_product_id;

    if ($vpc_product_id) {
        return $vpc_product_id;
    } else {
        $vpc_product_id = get_query_var('vpc-pid', false);
        if ($vpc_product_id) {
            return $vpc_product_id;
        } else {
            $product_id = false;
            return $product_id;
        }
    }
}

/**
 * Apply taxes on price if needed.
 *
 * @param string $price   The product price.
 * @param string $product Current product.
 */
function vpc_apply_taxes_on_price_if_needed($price, $product)
{
    $qty = 1;
    if (class_exists('Woocommerce')) {
        return ('incl' === get_option('woocommerce_tax_display_shop') && false !== $product) ? wc_get_price_including_tax(
            $product,
            array(
                'qty'   => $qty,
                'price' => $price,
            )
        ) : $price;
    } else {
        return $price;
    }
}

/**
 * Get configurator action buttons array.
 *
 * @param string $product_id The product id.
 */
function vpc_get_action_buttons_arr($product_id)
{
    $product_price = 0;
    $rate          = 0;
    global $WOOCS;  // phpcs:ignore
    if (class_exists('Woocommerce')) {
        $product               = wc_get_product($product_id);
        $untaxed_product_price = $product->get_price();
        $product_price         = vpc_apply_taxes_on_price_if_needed($untaxed_product_price, $product);
    }
    if ($WOOCS) { // phpcs:ignore
        $currencies = $WOOCS->get_currencies(); // phpcs:ignore
        $rate       = $currencies[$WOOCS->current_currency]['rate']; // phpcs:ignore
        // phpcs:ignore // $product_price= $product_price * $currencies[$WOOCS->current_currency]['rate'];
        // phpcs:ignore // $product_price = number_format($product_price, 2, $WOOCS->decimal_sep, '');
    }
    $add_to_cart = array(
        'id'         => 'vpc-add-to-cart',
        'label'      => __('Add to cart', 'vpc'),
        'class'      => 'add-to-cart-button-text-color',
        'attributes' => array(
            'data-pid'           => $product_id,
            'data-price'         => $product_price,
            'data-currency-rate' => $rate,
        ),
    );
    // @codingStandardsIgnoreStart
    $cid = '';
    if (isset($_GET['cid'])) {
        $cid = $_GET['cid'];
    }

    // $save = array(
    // "id"=>"vpc-save-config",
    // "label"=>__("Save", "vpc"),
    // "class"=>"",
    // "attributes"=>array(
    // "data-cid"=>$cid,
    // ),
    // "requires_login"=>true,
    // "visible_admin"=>false
    // );
    // @codingStandardsIgnoreEnd

    $buttons = array(
        // $save,
        $add_to_cart,
    );
    return apply_filters('vpc_action_buttons', $buttons, $product_id);
}

/**
 * Get configurator action buttons.
 *
 * @param string $product_id The product id.
 */
function vpc_get_action_buttons($product_id)
{
    if (!$product_id) {
        return;
    }
    $buttons = vpc_get_action_buttons_arr($product_id);
    ob_start();
    vpc_get_quantity_container($product_id);

    foreach ($buttons as $button) {
        if (!isset($button['requires_login'])) {
            $button['requires_login'] = false;
        }
        if (!isset($button['visible_admin'])) {
            $button['visible_admin'] = true;
        }
        if (!isset($button['attributes'])) {
            $button['attributes'] = array();
        }

        if (!is_user_logged_in() && $button['requires_login']) {
            continue;
        } elseif (is_admin() && !is_ajax() && !$button['visible_admin']) {
            continue;
        }
        // Custom attribute handling.
        $custom_attributes = array();

        foreach ($button['attributes'] as $attribute => $attribute_value) {
            $custom_attributes[] = esc_attr($attribute) . '=' . esc_attr($attribute_value);
        }
?>
        <button id="<?php echo esc_attr($button['id']); ?>" class="<?php echo esc_attr($button['class']); ?>" <?php echo esc_attr(implode(' ', $custom_attributes)); ?>>
            <?php echo esc_attr($button['label']); ?>
        </button>

    <?php
    }
    ?>
<?php
    $output = ob_get_contents();
    ob_end_clean();
    return apply_filters('vpc_action_buttons_html', $output, $product_id);
}

/**
 * Get configurator multiple views images sizes.
 *
 * @param object $settings Configurator setting.
 */
function get_configurator_multiple_views_images_sizes($settings)
{
    $find     = 'No';
    $width    = 0;
    $height   = 0;
    $view_arr = vpc_mva_get_config_views($settings);
    if (isset($settings['components']) && is_array($settings['components'])) {
        foreach ($settings['components'] as $components) {
            if (isset($components['options']) && !empty($components['options'])) {
                foreach ($components['options'] as $options) {
                    foreach ($view_arr as $view_id => $view) {
                        $view = sanitize_title($view);
                        if (isset($options['view_' . $view_id]) && !empty($options['view_' . $view_id])) {
                            $img                  = o_get_proper_image_url($options['view_' . $view_id]);
                            $img                  = str_replace(site_url() . '/', ABSPATH, $img);
                            list($width, $height) = getimagesize($img);
                            $find                 = 'Yes';
                            break;
                        } elseif ((isset($options['view_' . $view]) && !empty($options['view_' . $view]))) {
                            $img                  = o_get_proper_image_url($options['view_' . $view]);
                            $img                  = str_replace(site_url() . '/', ABSPATH, $img);
                            list($width, $height) = getimagesize($img);
                            $find                 = 'Yes';
                            break;
                        }
                    }
                    if ('Yes' === $find) {
                        break;
                    }
                }
            }
            if ('Yes' === $find) {
                break;
            }
        }
    }
    return array(
        'width'  => $width,
        'height' => $height,
    );
}

/**
 * Get configurator single view images sizes.
 *
 * @param object $settings Configurator setting.
 */
function get_configurator_single_view_image_sizes($settings)
{
    $find   = 'No';
    $width  = 0;
    $height = 0;
    if (is_array($settings['components'])) {
        foreach ($settings['components'] as $components) {
            foreach ($components['options'] as $options) {
                if (isset($options['image']) && !empty($options['image'])) {
                    $img                  = o_get_proper_image_url($options['image']);
                    $img                  = str_replace(site_url() . '/', ABSPATH, $img);
                    list($width, $height) = getimagesize($img);
                    $find                 = 'Yes';
                    break;
                }
            }
            if ('Yes' === $find) {
                break;
            }
        }
    }
    return array(
        'width'  => $width,
        'height' => $height,
    );
}

/**
 * Get configurator images sizes.
 *
 * @param object $settings Configurator setting.
 */
function get_config_images_size($settings)
{

    if (isset($settings['multi-views']) && 'Yes' === $settings['multi-views']) {
        $results = get_configurator_multiple_views_images_sizes($settings);
    } else {
        $results = get_configurator_single_view_image_sizes($settings);
    }
    return $results;
}

/**
 * Register the stylesheets for the public-facing side of the site.
 */
function vpc_enqueue_core_scripts()
{
    wp_enqueue_script('oriontip-script', VPC_URL . 'public/libs/oriontip/oriontip.js', array('jquery'), VPC_VERSION, false);
    wp_enqueue_script('oimageload', VPC_URL . 'public/js/oimageload.js', array('jquery'), VPC_VERSION, false);
    wp_enqueue_script('vpc-fabric', VPC_URL . 'public/js/fabric.min.js', array('jquery'), VPC_VERSION, false);
    // phpcs:ignore // wp_enqueue_script( 'wp-js-hooks', VPC_URL . 'public/js/wp-js-hooks.min.js', array( 'jquery' ), VPC_VERSION, false );
    wp_enqueue_script('wp-serializejson', VPC_URL . 'public/js/jquery.serializejson.min.js', array('jquery'), VPC_VERSION, false);
    wp_enqueue_script('core-js', VPC_URL . 'public/js/core-js.js', array('jquery'), VPC_VERSION, false);
    do_action('vpc_enqueue_core_scripts');
}

/**
 * Register the stylesheets for the public-facing side of the site.
 */
function vpc_enqueue_core_styles()
{
    wp_enqueue_style('oriontip-style', VPC_URL . 'public/libs/oriontip/oriontip.css', array(), VPC_VERSION, 'all');
    wp_enqueue_style('o-flexgrid', VPC_URL . 'admin/css/flexiblegs.css', array(), VPC_VERSION, 'all');
    do_action('vpc_enqueue_core_styles');
}

/**
 * Register the stylesheets for the public-facing side of the skin.
 *
 * @param string $skin_name The skin name.
 */
function vpc_skins_enqueue_styles_scripts($skin_name)
{
    if (isset($skin_name) && ('VPC_Right_Sidebar_Skin' === $skin_name || 'VPC_Default_Skin' === $skin_name)) {
        if ('VPC_Right_Sidebar_Skin' === $skin_name) {
            wp_enqueue_style('vpc-right-sidebar-skin', VPC_URL . 'public/css/vpc-right-sidebar-skin.css', array(), VPC_VERSION, 'all');
        }
        wp_enqueue_style('vpc-default-skin', VPC_URL . 'public/css/vpc-default-skin.css', array(), VPC_VERSION, 'all');
        wp_enqueue_style('FontAwesome', VPC_URL . 'public/css/font-awesome.min.css', array(), VPC_VERSION, 'all');
        wp_enqueue_script('vpc-default-skin', VPC_URL . 'public/js/vpc-default-skin.js', array('jquery', 'vpc-public'), VPC_VERSION, false);
        wp_localize_script('vpc-default-skin', 'ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));
    }
    do_action('vpc_skins_enqueue_styles_scripts', $skin_name);
}

/**
 * Returns the loader html according to chosen loader option
 */
function vpc_get_configurator_loader()
{
    global $vpc_settings;
    $content       = '';
    $loader_option = get_proper_value($vpc_settings, 'hide-loader', 'Yes');

    if ('No' === $loader_option) {
        $content = '
    <div id="vpc-loader-container">
    <div>
    <div class="loadingio-spinner-gear-e4tpyed78c8"><div class="ldio-bv4uhcnsv1h">
    <div><div></div><div></div><div></div><div></div><div></div><div></div></div>
    </div></div>
    <span>' . __('Loading configurator...', 'vpc') . '</span>
    </div>
    </div>
    ';
    }

    echo wp_kses($content, get_allowed_tags());
}

/**
 * Get configurator quantity container.
 *
 * @param string $product_id The product id.
 */
function vpc_get_quantity_container($product_id)
{
    global $vpc_settings;
    if (is_admin() && !is_ajax()) {
        return;
    }
    $action_qtity_box    = get_proper_value($vpc_settings, 'hide-qty', 'Yes');
    $purchase_properties = vpc_get_purchase_properties($product_id);
    $qty                 = 1;
    if (isset($_GET['qty'])) { // phpcs:ignore
        if (is_numeric($purchase_properties['max']) && $_GET['qty'] > $purchase_properties['max']) { // phpcs:ignore
            $qty = $purchase_properties['max'];
        } else {
            $qty = $_GET['qty']; // phpcs:ignore
        }
    } else {
        $qty = $purchase_properties['min_to_purchase'];
    }
    if ('No' === $action_qtity_box) {
        $style = '';
    } else {
        $style = 'display:none;';
    }
    ob_start();
?>
    <div id="vpc-qty-container" class="" style="<?php echo esc_attr($style); ?>">
        <input type="button" value="&#xf068;" class="minus">
        <input id="vpc-qty" type="number" step="<?php echo esc_attr($purchase_properties['step']); ?>" value="<?php echo esc_attr($qty); ?>" min="<?php echo esc_attr($purchase_properties['min']); ?>" max="<?php echo esc_attr($purchase_properties['max']); ?>">
        <input type="button" value="&#xf067;" class="plus">
    </div>
<?php
    $content = ob_get_contents();
    $content = apply_filters('vpc.qtity_box', $content, $action_qtity_box, $qty); // phpcs:ignore
    ob_end_clean();
    echo wp_kses($content, get_allowed_tags());
}

/**
 * Returns the minimum and maximum order quantities.
 *
 * @param  int $product_id The product id.
 * @return type
 */
function vpc_get_purchase_properties($product_id)
{
    $variation_id                    = 0;
    $product_root_and_variations_ids = get_product_root_and_variations_ids($product_id);
    if ($product_root_and_variations_ids) {
        if (isset($product_root_and_variations_ids['product-id']) && !empty($product_root_and_variations_ids['product-id'])) {
            $variation_id = $product_root_and_variations_ids['product-id'];
        } elseif (isset($product_root_and_variations_ids['variation-id']) && !empty($product_root_and_variations_ids['variation-id'])) {
            $variation_id = $product_root_and_variations_ids['variation-id'];
        }
    }

    if ($variation_id) {
        $defined_min_qty = get_post_meta($variation_id, 'variation_minimum_allowed_quantity', true);
        $defined_max_qty = get_post_meta($variation_id, 'variation_maximum_allowed_quantity', true);
    } else {
        $defined_min_qty = 1;
        $defined_max_qty = get_post_meta($variation_id, 'variation_maximum_allowed_quantity', true);
    }
    $product = wc_get_product($variation_id);
    $step    = apply_filters('woocommerce_quantity_input_step', '1', $product);
    $min_qty = apply_filters('woocommerce_quantity_input_min', $defined_min_qty, $product);

    if (!$defined_max_qty) {
        $defined_max_qty = apply_filters('woocommerce_quantity_input_max', $product->backorders_allowed() ? '' : $product->get_stock_quantity(), $product);
    }

    $min_to_purchase = $min_qty;
    if (!$min_qty) {
        $min_to_purchase = 1;
    }

    $defaults = array(
        'max_value' => $defined_max_qty,
        'min_value' => $min_qty,
        'step'      => $step,
    );
    $args     = apply_filters('woocommerce_quantity_input_args', wp_parse_args(array(), $defaults), $product);

    return array(
        'min'             => (isset($args['min_value']) && !empty($args['min_value'])) ? $args['min_value'] : 1,
        'min_to_purchase' => (isset($args['min_value']) && !empty($args['min_value'])) ? $args['min_value'] : 1,
        'max'             => (isset($args['max_value']) && !empty($args['max_value'])) ? $args['max_value'] : 'undefined',
        'step'            => (isset($args['step']) && !empty($args['step'])) ? $args['step'] : 1,
    );
}

/**
 * Get product root and variations ids.
 *
 * @param int $id The variation id.
 */
function get_product_root_and_variations_ids($id)
{
    $product_id   = 0;
    $variation_id = 0;
    $variation    = array();
    if (class_exists('WooCommerce')) {
        $variable_product = wc_get_product($id);
        if (!$variable_product) {
            return false;
        }

        if (vpc_woocommerce_version_check()) {
            $product_type = $variable_product->product_type;
        } else {
            $product_type = $variable_product->get_type();
        }

        if ('simple' === $product_type) {
            $product_id = $id;
        } else {
            if (vpc_woocommerce_version_check()) {
                $variation  = $variable_product->variation_data;
                $product_id = $variable_product->parent->id;
            } else {
                $variation  = $variable_product->get_data();
                $product_id = $variable_product->get_parent_id();
            }
            $variation_id = $id;
        }
    }
    return array(
        'product-id'   => $product_id,
        'variation-id' => $variation_id,
        'variation'    => $variation,
    );
}

/**
 * Returns the rules organized per options and another array with the triggers for reverse rules
 *
 * @param  array $rules_groups Conditionnal rule group.
 * @return array
 */
function get_reorganized_rules($rules_groups)
{
    $rules_per_options      = array();
    $reverse_rules_triggers = array();
    if (is_array($rules_groups) && !empty($rules_groups)) {
        foreach ($rules_groups as $group_index => $rules) {
            if (isset($rules['rules'])) {
                foreach ($rules['rules'] as $rule_index => $rule) {
                    if (!isset($rules_per_options[$rule['option']])) {
                        $rules_per_options[$rule['option']] = array();
                    }
                    array_push($rules_per_options[$rule['option']], $rules_groups);

                    if (isset($rules['apply_reverse']) && 'on' === $rules['apply_reverse'] && !in_array($rule['option'], $reverse_rules_triggers, true)) {
                        array_push($reverse_rules_triggers, $rule['option']);
                    }
                }
            }
        }
    }
    return array(
        'per-option'       => $rules_per_options,
        'reverse-triggers' => $reverse_rules_triggers,
    );
}

/**
 * Extract option id from options array.
 *
 * @param array $option Component options.
 */
function vpc_extract_ids_from_options_array($option)
{
    return $option['option_id'];
}

/**
 * Sort options by group.
 *
 * @param array $options Component options.
 */
function sort_options_by_group($options)
{
    $sorted_options = array();
    foreach ($options as $option) {
        if (!isset($sorted_options[$option['group']])) {
            $sorted_options[$option['group']] = array();
        }
        array_push($sorted_options[$option['group']], $option);
    }
    $merged = call_user_func_array('array_merge', array_values($sorted_options));

    return array_merge($merged);
}
// @codingStandardsIgnoreStart
// function get_user_saved_configs($user_id = false) {
// global $wpdb;
// $configs_arr = array();
// if (!$user_id)
// $user_id = get_current_user_id();
// $sql = "select umeta_id, meta_value from $wpdb->usermeta where user_id=$user_id and meta_key='vpc-config'";
// $results = $wpdb->get_results($sql);
//
// foreach ($results as $config) {
// $configs_arr[$config->umeta_id] = unserialize($config->meta_value);
// }
//
// return $configs_arr;
// }
//
// function get_config_templates($config_id)
// {
// $args = array(
// "post_type" => "vpc-template",
// "nopaging" => true,
// array(
// 'key' => "vpc-config",
// 'value' => '"config-id";s:'.strlen($config_id).':"'.$config_id.'"',
// 'compare' => "LIKE"
// )
// );
// $templates = get_posts($args);
//
// return $templates;
// }
// @codingStandardsIgnoreEnd

/**
 * Extract option id from options array.
 *
 * @param string $product_id      Product id.
 * @param bool   $saved_config_id Save configuration id.
 * @param bool   $template_id     Template id.
 */
function vpc_get_configuration_url($product_id, $saved_config_id = false, $template_id = false)
{
    global $vpc_settings;
    $config_page_id = get_proper_value($vpc_settings, 'config-page');
    if (!$config_page_id) {
        return false;
    }
    if (function_exists('icl_object_id')) {
        $config_page_id = icl_object_id($config_page_id, 'page', false, ICL_LANGUAGE_CODE);
    }

    // phpcs:ignore // if(function_exists('pll_the_languages'))
    // phpcs:ignore // $config_page_id =pll_get_post($config_page_id);

    $design_url = get_permalink($config_page_id);

    if ($product_id) {
        // $query = parse_url($design_url, PHP_URL_QUERY);
        // Returns a string if the URL has parameters or NULL if not
        $use_pretty_url = apply_filters('vpc_use_pretty_url', true);
        if (get_option('permalink_structure') && $use_pretty_url) {
            if ('/' !== substr($design_url, -1)) {
                $design_url .= '/';
            }
            // phpcs:ignore // $design_url.='?vpc-pid=' . $product_id;
            $design_url .= 'configure/' . $product_id . '/';
            if ($saved_config_id) {
                $design_url .= "?cid=$saved_config_id";
            } elseif ($template_id) {
                $design_url .= "?tid=$template_id";
            }
        } else {
            $url_args = array('vpc-pid' => $product_id);
            if ($saved_config_id) {
                $url_args['cid'] = $saved_config_id;
            } elseif ($template_id) {
                $url_args['tid'] = $template_id;
            }
            $design_url = add_query_arg($url_args, $design_url);
        }
    }
    $design_url = apply_filters('vpc_design_url', $design_url, $product_id);
    return $design_url;
}

/**
 * Function to extract configuration images.
 *
 * @param array  $saved_config    Saved configuration.
 * @param object $original_config Original configuration.
 */
function vpc_extract_configuration_images($saved_config, $original_config)
{
    $components_by_names = $original_config->get_components_by_name();
    $output              = '';

    foreach ($saved_config as $saved_component_name => $saved_options) {
        $original_options = $components_by_names[$saved_component_name];
        if (!is_array($saved_options)) {
            $saved_options = array($saved_options);
        }

        foreach ($saved_options as $saved_option) {
            $original_option = get_proper_value($original_options, $saved_option);
            $img_id          = get_proper_value($original_option, 'image');
            if ($img_id) {
                $img_url = o_get_proper_image_url($img_id);
                $img_alt = get_post_meta($img_id, '_wp_attachment_image_alt', true);
                $output .= "<img src='$img_url' alt='$img_alt'>";
            }
        }
    }

    return $output;
}

/**
 * Function to get default behaviours.
 */
function vpc_get_behaviours()
{
    $behaviours_arr = apply_filters(
        'vpc_configuration_behaviours',
        array(
            'radio'    => __('Single choice', 'vpc'),
            'checkbox' => __('Multiple choices', 'vpc'),
            'dropdown' => __('Dropdown', 'vpc'),
        )
    );
    return $behaviours_arr;
}

/**
 * Function to check if product is configurable.
 *
 * @param string $metas Saved metas datas.
 */
function vpc_is_configurable($metas)
{
    return (!empty($metas['config-id']));
}

/**
 * Function to check if product is configurable.
 *
 * @param string $id Product id.
 */
function vpc_product_is_configurable($id)
{
    $metas   = get_post_meta($id, 'vpc-config', true);
    $product = 0;
    if (class_exists('WooCommerce')) {
        $product = wc_get_product($id);
    }
    if (!$product) {
        return false;
    }
    $class_name = get_class($product);
    if ('WC_Product_Variable' === $class_name) {
        $variations = $product->get_available_variations();
        foreach ($variations as $variation) {
            $variation_id        = $variation['variation_id'];
            $variation_metas     = get_proper_value($metas, $variation_id, false);
            $variation_config_id = isset($variation_metas['config-id']) ? $variation_metas['config-id'] : '';
            if (!empty($variation_config_id)) {
                return true;
            } else {
                return false;
            }
        }
    } elseif ('WC_Product_Variation' === $class_name) {
        $parent_id       = $product->get_parent_id();
        $metas           = get_post_meta($parent_id, 'vpc-config', true);
        $variation_metas = get_proper_value($metas, $id, false);
        if (isset($variation_metas['config-id']) && !empty($variation_metas['config-id'])) {
            return true;
        } else {
            return false;
        }
    } else {
        $configs   = get_proper_value($metas, $id, array());
        $config_id = get_proper_value($configs, 'config-id', false);
        if (!empty($config_id)) {
            return true;
        } else {
            return false;
        }
    }
}

/**
 * Function to get recap from cart item.
 *
 * @param array $data Cart item data.
 */
function get_recap_from_cart_item($data)
{
    if (empty($data) || !is_array($data)) {
        return array();
    }
    // @codingStandardsIgnoreStart
    // $merged_with_keys=array(
    // 'product_id',
    // 'variation_id',
    // 'variation',
    // 'quantity',
    // 'data',
    // 'line_tax',
    // 'line_total',
    // 'line_subtotal',
    // 'line_subtotal_tax',
    // 'line_tax_data',
    // 'addons');
    // $output=array_diff_key($data,array_flip($merged_with_keys));
    // @codingStandardsIgnoreEnd
    $output = array();
    if (isset($data['visual-product-configuration']) && !empty($data['visual-product-configuration'])) {
        $output = $data['visual-product-configuration'];
        if (isset($output['canvas_data']) && !class_exists('Vpc_Cta') && !class_exists('Vpc_Upload')) {
            unset($output['canvas_data']);
        }
        foreach ($output as $key => $value) {
            if (is_string($key) && strstr($key, '-color-selector')) {
                unset($output[$key]);
            }
        }
    }

    return $output;
}

/**
 * Function to get canvas data from cart item.
 *
 * @param array $data Cart item data.
 */
function get_canvas_data_from_cart_item($data)
{
    if (empty($data) || !is_array($data)) {
        return array();
    }
    $output = array();
    if (isset($data['vpc-canvas-datas']) && !empty($data['vpc-canvas-datas'])) {
        $output = $data['vpc-canvas-datas'];
    }

    return $output;
}

/**
 * Function to get order item configuration.
 *
 * @param array $images All images.
 * @param bool  $path   Return path or not.
 * @param bool  $url    Return url or not.
 */
function merge_pictures($images, $path = false, $url = false)
{

    $tmp_dir         = uniqid();
    $upload_dir      = wp_upload_dir();
    $generation_path = $upload_dir['basedir'] . '/VPC';
    $generation_url  = $upload_dir['baseurl'] . '/VPC';
    $main_width      = '';
    $main_height     = '';
    if (wp_mkdir_p($generation_path)) {
        $output_file_path = $generation_path . "/$tmp_dir.png";
        $output_file_url  = $generation_url . "/$tmp_dir.png";

        foreach ($images as $imgs) {

            $imgs = str_replace(site_url() . '/', ABSPATH, $imgs);
            if (file_exists($imgs)) {
                list($width, $height) = getimagesize($imgs);
                if (exif_imagetype($imgs) === IMAGETYPE_JPEG) {
                    $img = imagecreatefromjpeg($imgs);
                } elseif (exif_imagetype($imgs) === IMAGETYPE_PNG) {
                    $img = imagecreatefrompng($imgs);
                }
                imagealphablending($img, true);
                imagesavealpha($img, true);
                if (isset($output_img)) {
                    $x = ($main_width - $width) / 2;
                    $y = ($main_height - $height) / 2;
                    imagecopy($output_img, $img, $x, $y, 0, 0, $width, $height);
                } else {
                    $main_width  = $width;
                    $main_height = $height;
                    $output_img  = $img;
                    imagealphablending($output_img, true);
                    imagesavealpha($output_img, true);
                    imagecopymerge($output_img, $img, 10, 12, 0, 0, 0, 0, 100);
                }
            }
        }
        if (isset($output_img)) {
            imagepng($output_img, $output_file_path);
            imagedestroy($output_img);
        }
        if ($path) {
            return $output_file_path;
        }
        if ($url) {
            return $output_file_url;
        }
    } else {
        return false;
    }
}

/**
 * Function to get price format.
 */
function vpc_get_price_format()
{
    $currency_pos = get_option('woocommerce_currency_pos');
    $format       = '%s%v';

    switch ($currency_pos) {
        case 'left':
            $format = '%s%v';
            break;
        case 'right':
            $format = '%v%s';
            break;
        case 'left_space':
            $format = '%s %v';
            break;
        case 'right_space':
            $format = '%v %s';
            break;
        default:
            $format = '%s%v';
            break;
    }
    return $format;
}

/**
 * Function to get order item configuration.
 *
 * @param string $item Order item.
 */
function vpc_get_order_item_configuration($item)
{
    if (isset($item['vpc-original-config'])) {
        if (vpc_woocommerce_version_check()) {
            $original_config = unserialize($item['vpc-original-config']); // phpcs:ignore
        } else {
            $original_config = $item['vpc-original-config'];
        }
    } else {
        if ($item['variation_id']) {
            $product_id = $item['variation_id'];
        } else {
            $product_id = $item['product_id'];
        }

        $original_config_obj = get_product_config($product_id);
        $original_config     = $original_config_obj->settings;
    }

    return $original_config;
}

/**
 * Function to get discount rate.
 *
 * @param string $product_id Product id.
 */
function vpc_get_discount_rate($product_id)
{
    $discount_rate = 0;
    // WAD compatibility.
    if (function_exists('Woocommerce') && function_exists('wad_get_product_price')) {
        // Price without the discounts.
        $product_obj      = wc_get_product($product_id);
        $discounted_price = $product_obj->get_price();
        $original_price   = wad_get_product_price($product_obj);
        if ($original_price > 0) {
            $discount_rate = 1 - $discounted_price / $original_price;
        }
    }

    return $discount_rate;
}

/**
 * Function to check woocommerce version.
 *
 * @param string $version Woocommerce version.
 */
function vpc_woocommerce_version_check($version = '3.0.0')
{
    if (function_exists('WC') && (version_compare(WC()->version, $version, '<'))) {
        return true;
    }
    return false;
}

/**
 * Function to get configurator description.
 *
 * @param array $config Configuration data.
 */
function get_configurator_description($config)
{
    return (isset($config['config-desc'])) ? nl2br($config['config-desc']) : '';
}

/**
 * Function to verify current admin screen.
 */
function is_vpc_admin_screen()
{
    $screen            = get_current_screen();
    $is_correct_screen = false;
    if (isset($screen->base) && isset($screen->post_type) && ('vpc-config' === $screen->post_type || 'vpc-text-component' === $screen->post_type || 'vpc-upload-component' === $screen->post_type || 'vpc-rqa-form-data' === $screen->post_type || 'ofb' === $screen->post_type || 'product' === $screen->post_type || 'shop_order' === $screen->post_type || false !== strpos($screen->base, 'vpc') || false !== strpos($screen->post_type, 'vpc'))) {
        $is_correct_screen = true;
    }
    return apply_filters('vpc_admin_screen', $is_correct_screen, $screen);
}

/**
 * Function to get form data from cart item.
 *
 * @param string $item_content Cart item content.
 */
function get_form_data_from_cart_item($item_content)
{
    if (is_array($item_content) || is_object($item_content)) {
        foreach ($item_content as $content_key => $content) {
            if ('form_data' === $content_key) {
                return $content;
            }
        }
    }
}

/**
 * Function to sanitize array.
 *
 * @param array $arr File link.
 */
function vpc_array_sanitize($arr)
{
    $new_arr = array();
    foreach ($arr as $key => $value) {
        $new_arr[$key] = (is_array($value) ? vpc_array_sanitize($value) : sanitize_text_field(esc_html($value)));
    }
    return $new_arr;
}

/**
 * Function to load xml from url.
 *
 * @param string $url File link.
 */
function vpc_load_xml_from_url($url)
{
    if (function_exists('curl_init')) {
        $ch = curl_init($url); // phpcs:ignore
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // phpcs:ignore
        curl_setopt($ch, CURLOPT_HEADER, 0); // phpcs:ignore
        curl_setopt($ch, CURLOPT_TIMEOUT, 10); // phpcs:ignore
        $notifier_data = curl_exec($ch); // phpcs:ignore
        curl_close($ch); // phpcs:ignore
    }
    if (!$notifier_data) {
        $notifier_data = file_get_contents($url); // phpcs:ignore
    }
    if ($notifier_data) {
        if (strpos((string) $notifier_data, '<notifier>') === false) {
            $notifier_data = '<?xml version="1.0" encoding="UTF-8"?><notifier><latest>1.0</latest><changelog></changelog></notifier>';
        }
    }
    $xml = simplexml_load_string($notifier_data);
    return $xml;
}

/**
 * Function to get options field.
 *
 * @param string $config_id Configuration id.
 */
function vpc_get_options_fields($config_id)
{
    global $vpc_settings;
    $product_link = get_proper_value($vpc_settings, 'product-link', 'No');
    $o_image      = array(
        'title'     => __('Image', 'vpc'),
        'name'      => 'image',
        'url_name'  => 'image_url',
        'type'      => 'image',
        'set'       => 'Set',
        'remove'    => 'Remove',
        'class'     => 'vpc-option-img',
        'col_class' => 'vpc-image-column',
    );

    $o_icon         = array(
        'title'     => __('Icon', 'vpc'),
        'name'      => 'icon',
        'url_name'  => 'icon_url',
        'type'      => 'image',
        'set'       => 'Set',
        'remove'    => 'Remove',
        'col_class' => 'vpc-icon-column',
    );
    $option_product = '';
    $option_price   = 0;
    $option_name    = array(
        'title'     => __('Name', 'vpc'),
        'name'      => 'name',
        'type'      => 'text',
        'class'     => 'vpc-option-name',
        'col_class' => 'vpc-option-name-column',
    );

    $option_group = array(
        'title'     => __('Group', 'vpc'),
        'name'      => 'group',
        'type'      => 'text',
        'class'     => 'vpc-option-group',
        'col_class' => 'vpc-option-group-column',
    );

    $option_desc = array(
        'title' => __('Description', 'vpc'),
        'name'  => 'desc',
        'type'  => 'textarea',
    );

    $option_id = array(
        'title' => __('ID', 'vpc'),
        'name'  => 'option_id',
        'type'  => 'text',
        'class' => 'vpc-option-id',
    );
    if (class_exists('WooCommerce')) {
        $option_price = array(
            'title'             => __('Price', 'vpc'),
            'name'              => 'price',
            'type'              => 'number',
            'custom_attributes' => array('step' => 'any'),
            'col_class'         => 'vpc-option-price-column',
        );
        // @codingStandardsIgnoreStart
        // $args = array(
        // "post_type" => "product",
        // "nopaging" => true,
        // );
        // if($product_link=="Yes"){
        // $products_arr = get_posts($args);
        // $products=array(""=>"None");
        // foreach ($products_arr as $product)
        // {
        // $product_obj=wc_get_product($product);
        // $product_class=  get_class($product_obj);
        //
        // if($product_class == "WC_Product_Variable")
        // {
        // $variations = $product_obj->get_available_variations();
        // foreach ($variations as $variation) {
        // $attributes_str = implode(", ", $variation["attributes"]);
        // $products[$variation["variation_id"]]=$product->post_title."($attributes_str)";
        // }
        // }
        // else
        // $products[$product->ID]=$product->post_title;
        // }
        // @codingStandardsIgnoreEnd
        if ('Yes' === $product_link) {
            $option_product = array(
                'title'     => __('Product / variation ID', 'vpc'),
                'name'      => 'product',
                'type'      => 'text',
                'tip'       => 'yes',
                'class'     => 'vpc-product-selector',
                'col_class' => 'vpc-product-selector-column',
            );
        }
        // }
    }
    $option_default   = array(
        'title'     => __('Default', 'vpc'),
        'name'      => 'default',
        'type'      => 'radio',
        'options'   => array(1 => ''),
        'class'     => 'default-config',
        'col_class' => 'vpc-default-column',
        'tip'       => 'yes',
    );
    $duplicate_option = array(
        'title' => __('Duplicate', 'vpc'),
        'name'  => 'duplicate_option',
        'type'  => 'button',
        'class' => 'button duplicate_option',
    );
    $options          = apply_filters(
        'vpc_components_options_fields',
        array(
            'title'         => __('Options', 'vpc'),
            'name'          => 'options',
            'type'          => 'repeatable-fields',
            'class'         => 'striped',
            'fields'        => array_filter(array($option_id, $option_group, $option_name, $option_desc, $o_icon, $o_image, $option_price, $option_product, $option_default, $duplicate_option)),
            'desc'          => __('Component options', 'vpc'),
            'row_class'     => 'vpc-option-row',
            'popup'         => true,
            'lazyload'      => true,
            'popup_button'  => __('Manage options', 'vpc'),
            'popup_title'   => __('Options', 'vpc'),
            'add_btn_label' => __('Add option', 'vpc'),
        ),
        $config_id
    );
    return $options;
}

/**
 * Function to find component by id.
 *
 * @param array  $metas        Saved configuration datas.
 * @param string $component_id Group id.
 */
function vpc_find_component_by_id($metas, $component_id)
{
    $searched_component = array();
    foreach ($metas['components'] as $i => $component) {
        if ($component['component_id'] === $component_id) {
            $searched_component = $component;
            break;
        }
    }
    return $searched_component;
}

/**
 * Function to find options by group id.
 *
 * @param array  $metas    Saved configuration datas.
 * @param string $group_id Group id.
 */
function vpc_find_options_by_group_id($metas, $group_id)
{
    $searched_options = array();
    foreach ($metas['components'] as $i => $component) {
        foreach ($component['options'] as $option) {
            $current_group_id = vpc_get_group_id($component['component_id'], $option['group']);
            if ($current_group_id === $group_id) {
                array_push($searched_options, $option);
            }
        }
        // If after we find some options on this round, no need to look further.
        if (!empty($searched_options)) {
            break;
        }
    }
    return $searched_options;
}

/**
 * Function to get new reorder components.
 *
 * @param array $new_components Old components.
 * @param array $old_components New components.
 */
function vpc_get_new_reorder_components($new_components, $old_components)
{
    foreach ($old_components as $key => $value) {
        $exist = array_key_exists('component_id', $value);
        if (!$exist) {
            $i = count($new_components);
            if (isset($new_components[$i - 1]['options'])) {
                if (isset($value['options']) && !empty($value['options'])) {
                    foreach ($value['options'] as $key_option => $option) {
                        $new_components[$i - 1]['options'][] = $option;
                    }
                }
            } else {
                $new_components[$i - 1]['options'] = array();
                if (isset($value['options']) && !empty($value['options'])) {
                    foreach ($value['options'] as $key_option => $option) {
                        $new_components[$i - 1]['options'][] = $option;
                    }
                }
            }
        } else {
            $new_components[] = $value;
        }
    }
    return $new_components;
}

/**
 * Function to get components and options organized for select2.
 *
 * @param array $new_rules Old conditionnal rules.
 * @param array $old_rules New conditionnal rules.
 */
function vpc_get_new_reorder_conditionals_rules($new_rules, $old_rules)
{
    foreach ($old_rules as $key => $value) {
        $new_rules[] = $value;
    }
    return $new_rules;
}

/**
 * Function to get components and options organized for select2.
 *
 * @param array $metas Saved configuration datas.
 */
function vpc_get_components_and_options_organized_for_select2($metas)
{
    $organized_data = array();
    foreach ($metas['components'] as $i => $component) {
        array_push($organized_data, "{id: '" . $component['component_id'] . "',text: '" . esc_attr($component['cname']) . "',class: 'vpc-select2-component'}");
        $listed_groups = array();
        foreach ($component['options'] as $option) {
            $group = $option['group'];

            $no_group_name = __('No group', 'vpc');
            if (empty($group)) {
                $group = $no_group_name;
            }
            if (!in_array($group, $listed_groups, true) && $group !== $no_group_name) {
                $group_id = vpc_get_group_id($component['component_id'], $group);
                array_push($listed_groups, esc_attr($group));
                array_push($organized_data, "{id: '" . $group_id . "',text: '" . esc_attr($component['cname']) . ' => ' . esc_attr($group) . "',class: 'vpc-select2-group'}");
            }

            array_push($organized_data, "{id: '" . $option['option_id'] . "',text: '" . esc_attr($component['cname']) . ' => ' . esc_attr($group) . ' => ' . esc_attr($option['name']) . "',class: 'vpc-select2-option'}");
        }
    }
    return $organized_data;
}

/**
 * Function to check user product configuration.
 *
 * @param string $component_id Component id.
 * @param string $group_name   The group name.
 */
function vpc_get_group_id($component_id, $group_name)
{
    return 'group-' . sanitize_title($component_id . '-' . $group_name);
}

// @codingStandardsIgnoreStart
/*
function get_vpc_all_configurators_pages_ids(){
  global $wpdb;
  $pages_ids=array();
  $query = "SELECT ID, post_title FROM ".$wpdb->posts." WHERE post_content LIKE '%[vpc product%' AND post_status = 'publish'";
  $results = $wpdb->get_results ($query);
  foreach ( $results as $result ) {
  array_push($pages_ids,$result->ID);
  }
  $query = "SELECT ID, post_title FROM ".$wpdb->posts." WHERE post_content LIKE '%[wpb_builder%' AND post_status = 'publish'";
  $results = $wpdb->get_results ($query);
  foreach ( $results as $result ) {
  array_push($pages_ids,$result->ID);
  }
  $vpc_options=get_option('vpc-options');
  $pages_ids[] = get_proper_value($vpc_options, "config-page");
  if(is_product() && vpc_product_is_configurable(get_the_ID()))
  $pages_ids[] = get_the_ID();
  return $pages_ids;
  }
 */
// @codingStandardsIgnoreEnd

/**
 * Function to check user product configuration.
 *
 * @param string $prod_id Product id.
 */
function vpc_check_user_product_config($prod_id)
{
    $metas = get_user_meta(get_current_user_id(), 'user_configs', true);
    $i     = 0;
    foreach ($metas as $id => $meta) {
        if ($meta['prod_id'] === $prod_id) {
            $i++;
        }
    }
    return $i;
}

/**
 * This function get information (name, license key, admin url) on each product (vpc and these add-ons).
 *
 * @return array $licences Array containing for each product (vpc and add-ons), the official name of the product, its license key and its admin url
 */
function vpc_get_vpc_and_all_addons_licenses()
{
    global $vpc_settings;
    $vpc_settings = get_option('vpc-options');
    $licences     = array();
    if (class_exists('vpc')) {
        $start_urls      = 'edit.php?post_type=vpc-config&page=vpc-manage-settings';
        $licences['vpc'] = array(
            'purchase-code' => get_proper_value($vpc_settings, 'purchase-code', ''),
            'name'          => '',
            'url'           => admin_url($start_urls),
        );

        if (class_exists('vpc_rqa')) {
            $licences['vpc-request-a-quote-add-on'] = array(
                'purchase-code' => get_proper_value($vpc_settings, 'purchase-code-request-a-quote-add-on', ''),
                'name'          => 'Request a quote add on',
                'url'           => admin_url($start_urls . '&section=vpc-email-container'),
            );
        }
        if (class_exists('vpc_mva')) {
            $licences['vpc-multiple-views-add-on'] = array(
                'purchase-code' => get_proper_value($vpc_settings, 'purchase-code-multiple-views-add-on', ''),
                'name'          => 'Multiple views add on',
                'url'           => admin_url($start_urls . '&section=vpc-mva-container'),
            );
        }
        if (class_exists('vpc_cta')) {
            $licences['vpc-custom-text-add-on'] = array(
                'purchase-code' => get_proper_value($vpc_settings, 'purchase-code-custom-text-add-on', ''),
                'name'          => 'Custom text add on',
                'url'           => admin_url($start_urls . '&section=vpc-cta-container'),
            );
        }
        if (class_exists('vpc_ssa')) {
            $licences['vpc-social-share-add-on'] = array(
                'purchase-code' => get_proper_value($vpc_settings, 'purchase-code-social-share-add-on', ''),
                'name'          => 'Social Share add on',
                'url'           => admin_url($start_urls . '&section=ssa-global-container'),
            );
        }
        if (class_exists('vpc_upload')) {
            $licences['vpc-upload-image-add-on'] = array(
                'purchase-code' => get_proper_value($vpc_settings, 'purchase-code-upload-image-add-on', ''),
                'name'          => 'Upload Image add on',
                'url'           => admin_url($start_urls . '&section=vpc-upload-container'),
            );
        }
        if ((class_exists('Ofb'))) {
            $licences['vpc-form-builder-add-on'] = array(
                'purchase-code' => get_proper_value($vpc_settings, 'purchase-code-form-builder-add-on', ''),
                'name'          => 'Form Builder add on',
                'url'           => admin_url($start_urls . '&section=vpc-ofb-container'),
            );
        }
        if ((class_exists('vpc_sfla'))) {
            $licences['vpc-save-for-later-add-on'] = array(
                'purchase-code' => get_proper_value($vpc_settings, 'purchase-code-save-for-later-add-on', ''),
                'name'          => 'Save For Later add on',
                'url'           => admin_url($start_urls . '&section=vpc-sfla-container'),
            );
        }
        if ((class_exists('vpc_sci'))) {
            $licences['vpc-save-custom-image-add-on'] = array(
                'purchase-code' => get_proper_value($vpc_settings, 'purchase-code-save-custom-image-add-on', ''),
                'name'          => 'Save Configuration Image add on',
                'url'           => admin_url($start_urls . '&section=vpc-sci-container'),
            );
        }
        if ((class_exists('vpc_os'))) {
            $licences['vpc-ouando-skin'] = array(
                'purchase-code' => get_proper_value($vpc_settings, 'purchase-code-ouando-skin', ''),
                'name'          => 'Ouando Skin',
                'url'           => admin_url($start_urls . '&section=vpc-os-container'),
            );
        }
        if ((class_exists('vpc_msl'))) {
            $licences['vpc-modern-skin'] = array(
                'purchase-code' => get_proper_value($vpc_settings, 'purchase-code-modern-skin', ''),
                'name'          => 'Modern Skin',
                'url'           => admin_url($start_urls . '&section=vpc-msl-container'),
            );
        }
        if ((class_exists('vpc_lns'))) {
            $licences['vpc-lom-nava-skin'] = array(
                'purchase-code' => get_proper_value($vpc_settings, 'purchase-code-lom-nava-skin', ''),
                'name'          => 'Lom Nava Skin',
                'url'           => admin_url($start_urls . '&section=vpc-lns-container'),
            );
        }
    }
    return $licences;
}

/**
 * This function allows to activate the license key for each product (vpc and these add-ons).
 *
 * @return array $licences
 */
function vpc_activate_vpc_and_all_addons_licenses()
{
    $site_url = get_site_url();
    $licences = vpc_get_vpc_and_all_addons_licenses();
    foreach ($licences as $key => $value) {
        if (isset($value['purchase-code']) && !empty($value['purchase-code'])) {
            if (!get_option($key . '-license-key')) {
                $purchase_code = $value['purchase-code'];
                $url           = 'https://orionorigin.com/service/olicenses/v1/license/?purchase-code=' . $purchase_code . '&siteurl=' . rawurlencode($site_url);
                $args          = array('timeout' => 60);
                $response      = wp_remote_get($url, $args);
                if (is_wp_error($response)) {
                    $error_message                  = $response->get_error_message();
                    $licences[$key . '-checking'] = "Something went wrong: $error_message";
                } else {
                    if (isset($response['body'])) {
                        $answer = $response['body'];
                    }
                    if (is_array(json_decode($answer, true))) {
                        $data       = json_decode($answer, true);
                        $answer_key = $data['key'];
                        update_option($key . '-license-key', $answer_key);
                        $licences[$key][$key . '-checking'] = 'Activation successfully completed.';
                        $licences[$key][$key . '-status']   = true;
                    } else {
                        $licences[$key][$key . '-checking'] = $answer;
                        $licences[$key][$key . '-status']   = false;
                    }
                }
            } else {
                $licences[$key][$key . '-checking'] = __('Your plugin is already active.', 'vpc');
                $licences[$key][$key . '-status']   = false;
            }
        } else {
            $licences[$key][$key . '-checking'] = __("Purchase code not found. Please, set your purchase code in the plugin's settings.", 'vpc');
            $licences[$key][$key . '-status']   = false;
        }
    }
    set_transient('vpc-checking', 'valid', 1 * WEEK_IN_SECONDS);
    return $licences;
}

/**
 * This function return error message when the license is not active.
 *
 * @param  bool $status The status.
 * @return string $message The message.
 */
function vpc_get_error_message_license_is_not_active($status)
{
    $message = '';
    if (!$status) {
        $message = '<h2>' . __('You have not activated your license yet. Please, activate it in order to use this plugin.', 'vpc') . '</h2>';
    }
    return $message;
}

/**
 * Function to get the preview image url.
 *
 * @param  array $texts_and_uploads_canvas_images All text canvas images datas.
 * @return string                               The preview's generated image url.
 */
function vpc_get_merged_image($texts_and_uploads_canvas_images)
{
    $urls = vpc_get_all_images_to_merge($texts_and_uploads_canvas_images);
    return $urls;
}

/**
 * Function to order array.
 *
 * @param array $arrays Array data.
 */
function reorder_array_in_new_array($arrays)
{
    $new_arr = array();
    foreach ($arrays as $arr) {
        if (is_array($arr)) {
            foreach ($arr as $ar) {
                array_push($new_arr, $ar);
            }
        } else {
            array_push($new_arr, $arr);
        }
    }
    return $new_arr;
}

/**
 * Function to get the first element of array.
 *
 * @param array $array_data Array data.
 */
function vpc_get_first_element_of_array($array_data)
{
    if (isset($array_data) && !empty($array_data)) {
        foreach ($array_data as $key => $value) {
            return $value;
        }
    }
}

/**
 * Function to get the preview images.
 *
 * @param array $texts_and_uploads_canvas_images All text canvas images datas.
 */
function vpc_get_all_images_to_merge($texts_and_uploads_canvas_images)
{
    $preview_image_merged = $texts_and_uploads_canvas_images['preview_imgs_merged'];
    $preview_images       = json_decode(stripslashes($preview_image_merged));
    $all_imgs             = array();
    if (isset($preview_images)) {
        foreach ($preview_images as $image_key => $canvas_data) {
            $all_imgs[$image_key] = vpc_generate_canvas_image($canvas_data, false, true);
        }
    }
    return $all_imgs;
}

/**
 * Function to generate canvas image.
 *
 * @param string $canvas_image Canvas image datas.
 * @param array  $path         Saved path.
 * @param array  $url          Saved url.
 */
function vpc_generate_canvas_image($canvas_image, $path = false, $url = false)
{
    $id              = uniqid();
    $upload_dir      = wp_upload_dir();
    $generation_path = $upload_dir['basedir'] . '/VPC';
    $generation_url  = $upload_dir['baseurl'] . '/VPC';
    if (wp_mkdir_p($generation_path)) {
        $final_file_path = $generation_path . '/canvas_image' . $id . '.png';
        $final_file_url  = $generation_url . '/canvas_image' . $id . '.png';
        $unencoded       = base64_decode($canvas_image); // phpcs:ignore
        $fp              = fopen($final_file_path, 'w'); // phpcs:ignore
        fwrite($fp, $unencoded); // phpcs:ignore
        fclose($fp); // phpcs:ignore
        if ($path) {
            return $final_file_path;
        }
        if ($url) {
            return $final_file_url;
        }
    } else {
        return false;
    }
}

/**
 * Function to sanitize an array.
 *
 * @param  array $data Unsanitized array.
 * @return array        Sanitized array.
 */
function vpc_sanitize_array($data)
{
    if (is_array($data)) {
        foreach ((array) $data as $k => $v) {
            if (is_array($v)) {
                $data[$k] = vpc_sanitize_array($v);
            } else {
                $data[$k] = sanitize_text_field($v);
            }
        }
    } else {
        $data = sanitize_text_field($data);
    }
    return $data;
}

/**
 * Update ajax loading option.
 */
function update_ajax_loading_option()
{
    $options     = get_option('vpc-options');
    $ajax_option = get_proper_value($options, 'ajax-loading');
    if (isset($ajax_option) && 'Yes' === $ajax_option) {
        $options['ajax-loading'] = 'No';
    }
    update_option('vpc-options', $options);
}

/**
 * Function to generate component and option name when there are empty .
 *
 * @param  array $metas New configuration data.
 * @return array
 */
function vpc_fill_missing_components_and_options_names($metas)
{
    foreach ($metas['components'] as $i => $component) {

        if (isset($metas['components'][$i]['cname']) && '' === $metas['components'][$i]['cname']) {
            $metas['components'][$i]['cname'] = $metas['components'][$i]['component_id'];
        }
        if (isset($metas['components'][$i]['options'])) {
            foreach ($metas['components'][$i]['options'] as $j => $option) {
                if (empty($metas['components'][$i]['options'][$j]['name'])) {
                    $metas['components'][$i]['options'][$j]['name'] = $metas['components'][$i]['options'][$j]['option_id'];
                }
            }
        }
    }
    return $metas;
}

/**
 * Function to fix the length of a string.
 *
 * @param  array $matches New data to fix.
 * @return string         Fixed string lenght.
 */
function vpc_fix_serialized_string_length($matches)
{
    $string       = $matches[2];
    $right_length = strlen($string); // yes, strlen even for UTF-8 characters, PHP wants the mem size, not the char count.
    return 's:' . $right_length . ':"' . $string . '";';
}

/**
 * Function removed special characters in the name;
 *
 * @param  string $name The string value.
 * @return type $name
 */
function vpc_remove_special_characters($name)
{
    if(is_string($name))
       $name = stripslashes(trim($name, '"'));
   
        return $name;
}

/**
 * Get all products for select2 array.
 */
function vpc_get_all_products_for_select2_array()
{
    $args         = array(
        'post_type'   => 'product',
        'nopaging'    => true,
        'post_status' => 'publish',
    );
    $products_arr = get_posts($args);
    $products     = array("{id: '',text: 'None'}");
    foreach ($products_arr as $product) {
        $product_obj   = wc_get_product($product);
        $product_class = get_class($product_obj);

        if ('WC_Product_Variable' === $product_class) {
            $variations = $product_obj->get_children();
            foreach ($variations as $variation_id) {
                $variation = new WC_Product_Variation($variation_id);
                $title     = implode(' - ', $variation->get_variation_attributes());
                $title     = $product->post_title . ' - ' . $title;
                $title     = addslashes($title);
                // phpcs:ignore // $title     = get_the_title( $variation_id );
                // phpcs:ignore // $title     = preg_replace( '/&#8211;/', '-', $title );
                array_push($products, "{id: $variation_id,text: '$title'}");
            }
        } else {
            $title = addslashes($product->post_title);
            array_push($products, "{id: $product->ID,text: '$title'}");
        }
    }

    return $products;
}

/**
 * Function to generate component and option name when there are empty .
 *
 * @param  string $product_id     The product ID.
 * @param  string $linked_product The linked product ID.
 * @param  string $option_price   The option price.
 * @return array
 */
function vpc_apply_wad_discount_on_linked_product_option($product_id, $linked_product, $option_price)
{
    global $wad_discounts;
    $discount_option_price = $option_price;
    $config                = get_product_config($product_id);
    if (class_exists('Wad')) {
        foreach ($wad_discounts['product'] as $discount_id => $discount_obj) {
            if ($discount_obj->is_applicable($product_id)) {
                if ($linked_product) {
                    $discount_option_price = (float) $option_price - (float) $discount_obj->get_discount_amount($option_price);
                }
            }
        }
    }
    return $discount_option_price;
}

/**
 * Function to generate component and option name when there are empty .
 *
 * @param  string $product_id   The product ID.
 * @param  string $option_price The option price.
 * @return array
 */
function vpc_apply_wad_discount_on_addon_option($product_id, $option_price)
{
    global $wad_discounts;
    $discount_option_price = $option_price;
    $config                = get_product_config($product_id);
    if (class_exists('Wad')) {
        foreach ($wad_discounts['product'] as $discount_id => $discount_obj) {
            if ($discount_obj->is_applicable($product_id)) {
                $discount_option_price = (float) $option_price - (float) $discount_obj->get_discount_amount($option_price);
            }
        }
    }
    return $discount_option_price;
}

/**
 * Function to get the price before the discount .
 *
 * @param  string $product_id The product ID.
 * @param  string $price      The product price.
 * @return float
 */
function vpc_get_price_before_discount($product_id, $price)
{
    global $wad_discounts;
    $new_price = $price;
    $config    = get_product_config($product_id);
    if (class_exists('Wad')) {
        foreach ($wad_discounts['product'] as $discount_id => $discount_obj) {
            $apply_discount = $discount_obj->is_applicable($product_id);
            if ($apply_discount) {
                $wad_setting = $discount_obj->get_settings();
                if ('percentage-off-pprice' === $wad_setting['action']) {
                    $rate      = $wad_setting['percentage-or-fixed-amount'] / 100;
                    $new_price = $price / (1 - $rate);
                }
                if ('fixed-amount-off-pprice' === $wad_setting['action']) {
                    $rate      = $wad_setting['percentage-or-fixed-amount'];
                    $new_price = $rate + $new_price;
                }
            }
        }
    }
    return $new_price;
}

/**
 * Function to get the option price before the discount .
 *
 * @param  string $product_id     The product ID.
 * @param  string $linked_product The linked product ID.
 * @param  string $price          The product price.
 * @param  bool   $statut         If cart or not.
 * @return float
 */
function vpc_get_opt_price_before_dicount_in_cart($product_id, $linked_product, $price, $statut)
{
    global $wad_discounts;
    $new_option_price = $price;
    if (class_exists('Wad')) {
        foreach ($wad_discounts['product'] as $discount_id => $discount_obj) {
            $apply_discount = $discount_obj->is_applicable($product_id);
            if ($apply_discount) {
                $wad_setting = $discount_obj->get_settings();
                if (true === $statut) {
                    if ('percentage-off-pprice' === $wad_setting['action']) {
                        $new_option_price = $price;
                    }
                    if ('fixed-amount-off-pprice' === $wad_setting['action']) {
                        if (0 === $price) {
                            $new_option_price = $price;
                        } else {
                            $new_option_price = vpc_apply_wad_discount_on_linked_product_option($product_id, $linked_product, $price);
                        }
                    }
                } elseif (false === $statut) {
                    if ('percentage-off-pprice' === $wad_setting['action']) {
                        $new_option_price = $price;
                    }
                    if ('fixed-amount-off-pprice' === $wad_setting['action']) {
                        $new_option_price = $price;
                    }
                }
            }
        }
    }
    return $new_option_price;
}

/**
 * Function to get the option price before the discount .
 *
 * @param  string $product_id The product ID.
 * @param  string $price      The product price.
 * @param  bool   $statut     If cart or not.
 * @return float
 */
function vpc_get_addon_price_before_dicount_in_cart($product_id, $price, $statut)
{
    global $wad_discounts;
    $opt_price = $price;
    if (class_exists('Wad')) {
        if (true === $statut) {
            foreach ($wad_discounts['product'] as $discount_id => $discount_obj) {
                $apply_discount = $discount_obj->is_applicable($product_id);
                if ($apply_discount) {
                    $wad_setting = $discount_obj->get_settings();
                    if ('percentage-off-pprice' === $wad_setting['action']) {
                        $opt_price = $price;
                    }
                    if ('fixed-amount-off-pprice' === $wad_setting['action']) {
                        $opt_price = vpc_apply_wad_discount_on_addon_option($product_id, $price);
                    }
                }
            }
        } elseif (false === $statut) {
            $opt_price = $price;
        }
    }
    return $opt_price;
}

/**
 * Function to apply wad discount on not linked option .
 *
 * @param  string $product_id   The product ID.
 * @param  string $option_price The option price.
 * @return array
 */
function vpc_apply_wad_discount_on_option($product_id, $option_price)
{
    global $wad_discounts;
    $new_option_price = $option_price;
    if (class_exists('Wad')) {
        foreach ($wad_discounts['product'] as $discount_id => $discount_obj) {
            if ($discount_obj->is_applicable($product_id)) {
                $new_option_price = $option_price - $discount_obj->get_discount_amount($option_price);
            }
        }
    }
    return $new_option_price;
}

/**
 * Function to get wad discount for the add to cart.
 *
 * @param  string $product_id The product ID.
 * @param  float  $price      The option price.
 * @return float
 */
function vpc_get_wad_discount($product_id, $price)
{
    global $wad_discounts;
    $new_price = $price;
    if (class_exists('Wad')) {
        foreach ($wad_discounts['product'] as $discount_id => $discount_obj) {
            if ($discount_obj->is_applicable($product_id)) {
                $current_setting = $discount_obj->get_settings();
                if ('percentage-off-pprice' === $current_setting['action']) {
                    $rate      = $current_setting['percentage-or-fixed-amount'] / 100;
                    $new_price = $price / (1 - $rate);
                }
                if ('fixed-amount-off-pprice' === $current_setting['action']) {
                    $rate      = $current_setting['percentage-or-fixed-amount'];
                    $new_price = $rate + $new_price;
                }
            }
        }
    }
    return $new_price;
}

/**
 * Function to get option price in the cart.
 *
 * @param  string $product_id   The product ID.
 * @param  string $option_price The option price.
 * @return float
 */
function vpc_get_wad_discount_for_opt_in_cart($product_id, $option_price)
{
    global $wad_discounts;
    $new_option_price = $option_price;
    if (class_exists('Wad')) {
        foreach ($wad_discounts['product'] as $discount_id => $discount_obj) {
            if ($discount_obj->is_applicable($product_id)) {
                $current_setting = $discount_obj->get_settings();
                if ('percentage-off-pprice' === $current_setting['action']) {
                    $new_option_price = $option_price;
                }
                if ('fixed-amount-off-pprice' === $current_setting['action']) {
                    $new_option_price = $option_price - $discount_obj->get_discount_amount($option_price);
                }
            }
        }
    }
    return $new_option_price;
}

/**
 * Function to get all pages exclude woocommerce pages
 *
 * @param  bool $product_page If is product page or not.
 * @return type
 */
function get_all_pages_exclude_woocommerce_pages($product_page = false)
{
    $args  = array(
        'post_type' => 'page',
        'nopaging'  => true,
        'exclude'   => get_woocommerce_page(),
    );
    $pages = get_posts($args);
    if (true === $product_page) {
        $pages_ids = array('', 'None');
    } else {
        $pages_ids = array('');
    }

    foreach ($pages as $page) {
        $pages_ids[$page->ID] = $page->post_title;
    }
    return $pages_ids;
}

/**
 * Get Woocommerce Page.
 *
 * @return array
 */
function get_woocommerce_page()
{
    $woocommerce_page = array();
    if (class_exists('WooCommerce')) {
        array_push($woocommerce_page, get_option('woocommerce_shop_page_id'));
        array_push($woocommerce_page, get_option('woocommerce_cart_page_id'));
        array_push($woocommerce_page, get_option('woocommerce_checkout_page_id'));
        array_push($woocommerce_page, get_option('woocommerce_myaccount_page_id'));
        array_push($woocommerce_page, get_option('woocommerce_terms_page_id'));
    }
    return $woocommerce_page;
}
