<?php
/**
 * The file that defines the core plugin class
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
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Vpc
 * @subpackage Vpc/includes
 * @author     ORION <help@orionorigin.com>
 */
class Vpc
{

    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since  1.0.0
     * @access protected
     * @var    Vpc_Loader    $loader    Maintains and registers all hooks for the plugin.
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     *
     * @since  1.0.0
     * @access protected
     * @var    string    $plugin_name    The string used to uniquely identify this plugin.
     */
    protected $plugin_name;

    /**
     * The current version of the plugin.
     *
     * @since  1.0.0
     * @access protected
     * @var    string    $version    The current version of the plugin.
     */
    protected $version;

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since 1.0.0
     */
    public function __construct()
    {

        $this->plugin_name = 'vpc';
        $this->version     = '1.0.0';

        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();
    }

    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - Vpc_Loader. Orchestrates the hooks of the plugin.
     * - Vpc_I18n. Defines internationalization functionality.
     * - Vpc_Admin. Defines all hooks for the admin area.
     * - Vpc_Public. Defines all hooks for the public side of the site.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since  1.0.0
     * @access private
     */
    private function load_dependencies()
    {

        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        include_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-vpc-loader.php';

        /**
         * The class responsible for defining internationalization functionality
         * of the plugin.
         */
        include_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-vpc-i18n.php';

        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        include_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-vpc-admin.php';

        /**
         * The class responsible for defining all actions that occur in the public-facing
         * side of the site.
         */
        include_once plugin_dir_path(dirname(__FILE__)) . 'public/class-vpc-public.php';
        /**
         * The class responsible for configurations duplications
         */
        include_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-vpc-duplicate.php';

        $this->loader = new Vpc_Loader();
    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the Vpc_I18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since  1.0.0
     * @access private
     */
    private function set_locale()
    {

        $plugin_i18n = new Vpc_I18n();
        $plugin_i18n->set_domain($this->get_plugin_name());

        $this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
    }

    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since  1.0.0
     * @access private
     */
    private function define_admin_hooks()
    {

        $plugin_admin = new VPC_Admin($this->get_plugin_name(), $this->get_version());

        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');
        $this->loader->add_action('admin_init', $plugin_admin, 'vpc_redirect');
        $this->loader->add_filter('screen_layout_columns', $plugin_admin, 'get_vpc_screen_layout_columns');
        $this->loader->add_filter('get_user_option_screen_layout_vpc-config', $plugin_admin, 'get_vpc_config_screen_layout');
        $this->loader->add_filter('get_user_option_meta-box-order_vpc-config', $plugin_admin, 'metabox_order');
        $this->loader->add_action('admin_menu', $plugin_admin, 'get_menu');
        $this->loader->add_action('admin_notices', $plugin_admin, 'run_vpc_db_updates_requirements');
        $this->loader->add_action('wp_ajax_run_updater', $plugin_admin, 'run_vpc_updater');
        $this->loader->add_filter('manage_edit-product_columns', $plugin_admin, 'get_product_columns');
        $this->loader->add_action('manage_product_posts_custom_column', $plugin_admin, 'get_products_columns_values', 5, 2);
        $this->loader->add_action('init', $plugin_admin, 'vpc_get_updater');
        $this->loader->add_action('admin_notices', $plugin_admin, 'get_max_input_vars_php_ini');

        $config = new VPC_Config(false);
        $this->loader->add_action('init', $config, 'register_cpt_config');
        $this->loader->add_action('add_meta_boxes', $config, 'get_config_metabox');
        $this->loader->add_action('save_post_vpc-config', $config, 'save_config');
        $this->loader->add_action('save_post_product', $config, 'save_product_configuration');
        $this->loader->add_action('woocommerce_save_product_variation', $config, 'save_variation_settings_fields');
        $this->loader->add_action('save_post_vpc-template', $config, 'save_config');
     // @codingStandardsIgnoreStart
     // $this->loader->add_action( 'woocommerce_product_write_panel_tabs',$config, 'get_product_tab_label');  // phpcs:ignore
     // $this->loader->add_action( 'woocommerce_product_write_panels', $config, 'get_product_tab_data');
     // @codingStandardsIgnoreEnd
        // Set product configuration selector for simple product
        $this->loader->add_action('woocommerce_product_options_general_product_data', $config, 'get_product_config_selector');
        // Set product configuration selector for variable product.
        $this->loader->add_action('woocommerce_product_after_variable_attributes', $config, 'wvpc_variable_fields', 10, 3);
        $this->loader->add_filter('manage_edit-vpc-config_columns', $config, 'add_rules_columns');
        $this->loader->add_action('manage_vpc-config_posts_custom_column', $config, 'get_rules_columns_values', 5, 2);

        $this->loader->add_action('admin_init', $plugin_admin, 'vpc_plugin_subscribe_notice_ignore');

        // pour faire apparaitre le bouton.
        $this->loader->add_action('admin_notices', $plugin_admin, 'get_inactivated_licenses_notices');

        // envoyer un notice si woocommerce n'est pas activé.
        $this->loader->add_action('admin_notices', $plugin_admin, 'require_woocommerce_notice');

        $this->loader->add_action('wp_ajax_vpc_get_options_by_component_id', $plugin_admin, 'get_options_by_component_id');
        $this->loader->add_action('wp_ajax_vpc_duplicate_component', $plugin_admin, 'duplicate_component');

        // License key verification.
        $this->loader->add_action('init', $plugin_admin, 'o_verify_validity', 99);

        // subscription hook.
        $this->loader->add_action('wp_ajax_vpc_subscribe', $plugin_admin, 'vpc_subscribe');
        $this->loader->add_action('wp_ajax_nopriv_vpc_subscribe', $plugin_admin, 'vpc_subscribe');

        // hide subscription hook.
        $this->loader->add_action('wp_ajax_vpc_hide_notice', $plugin_admin, 'vpc_hide_notice');
        $this->loader->add_action('wp_ajax_nopriv_vpc_hide_notice', $plugin_admin, 'vpc_hide_notice');

        // Show or hide pagination option.
        $this->loader->add_action('wp_ajax_vpc_show_or_hide_paginate', $plugin_admin, 'vpc_show_or_hide_paginate');
        $this->loader->add_action('wp_ajax_nopriv_vpc_show_or_hide_paginate', $plugin_admin, 'vpc_show_or_hide_paginate');

        // Fix to the postmeta serialization for Worpress Importer plugin.
        $this->loader->add_filter('wp_import_post_meta', $plugin_admin, 'vpc_fix_serialized_data', 10, 3);
        $this->loader->add_action('import_post_meta', $plugin_admin, 'fix_views_structure_after_import', 10, 3);
        $this->loader->add_filter('woocommerce_hidden_order_itemmeta', $plugin_admin, 'hide_order_item_meta', 10, 1);
        $this->loader->add_filter('woocommerce_admin_html_order_item_class', $plugin_admin, 'add_custom_class_in_admin_order', 10, 3);
    }

    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since  1.0.0
     * @access private
     */
    private function define_public_hooks()
    {

        $plugin_public = new VPC_Public($this->get_plugin_name(), $this->get_version());
        /* General */
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');
        $this->loader->add_action('init', $plugin_public, 'init_globals');
        $this->loader->add_action('init', $plugin_public, 'register_shortcodes');
        // Variable filters.
        $this->loader->add_action('init', $plugin_public, 'set_variable_action_filters', 99);
        // Add query vars and rewrite rules.
        $this->loader->add_filter('query_vars', $plugin_public, 'add_query_vars');
        $this->loader->add_filter('init', $plugin_public, 'add_rewrite_rules', 99);
        // Product page.
        $this->loader->add_action('woocommerce_after_add_to_cart_button', $plugin_public, 'get_configure_btn');
        $this->loader->add_filter('woocommerce_loop_add_to_cart_link', $plugin_public, 'get_configure_btn_loop', 10, 3);
        // cart.
        $this->loader->add_action('wp_ajax_add_vpc_configuration_to_cart', $plugin_public, 'add_vpc_configuration_to_cart');
        $this->loader->add_action('wp_ajax_nopriv_add_vpc_configuration_to_cart', $plugin_public, 'add_vpc_configuration_to_cart');
        $this->loader->add_filter('woocommerce_cart_item_name', $plugin_public, 'get_vpc_data', 99, 3);
        $this->loader->add_action('woocommerce_before_calculate_totals', $plugin_public, 'get_cart_item_price', 10);
        $this->loader->add_filter('woocommerce_cart_item_price', $plugin_public, 'update_vpc_cart_item_price', 99, 3);
        $this->loader->add_filter( 'woocommerce_cart_item_class', $plugin_public,'add_vpc_classes_on_cart_item', 10, 3 );
        $this->loader->add_filter('woocommerce_before_mini_cart_contents', $plugin_public, 'calculate_minicart_subcartotal');
        $this->loader->add_filter('woocommerce_cart_item_thumbnail', $plugin_public, 'get_vpc_data_image', 99, 3);
        $this->loader->add_filter('woocommerce_cart_contents_count', $plugin_public, 'update_cart_contents_count');
        // Remove additionnal product on cart item deletion.
        $this->loader->add_filter('woocommerce_cart_item_removed', $plugin_public, 'vpc_remove_secondary_products');
        // prevent secondary product deletion.
        $this->loader->add_filter('woocommerce_remove_cart_item', $plugin_public, 'prevent_secondary_product_deletion');
        // Order page.
        $this->loader->add_action('woocommerce_before_order_itemmeta', $plugin_public, 'get_admin_products_metas', 10, 3);
        $this->loader->add_action('woocommerce_checkout_create_order_line_item', $plugin_public, 'add_customized_item_meta', 20, 4);
        // Emails.
        $this->loader->add_action('woocommerce_order_item_meta_start', $plugin_public, 'set_email_order_item_meta', 10, 3);
        // Hide item on cart , checkout,order.
        $this->loader->add_filter('woocommerce_display_item_meta', $plugin_public, 'hide_secondary_product_item_meta', 10, 3);
        $this->loader->add_filter('woocommerce_cart_item_visible', $plugin_public, 'hide_cart_item', 10, 3);
        $this->loader->add_filter('woocommerce_checkout_cart_item_visible', $plugin_public, 'hide_cart_item', 10, 3);
        $this->loader->add_filter('woocommerce_widget_cart_item_visible', $plugin_public, 'hide_cart_item', 10, 3);
        $this->loader->add_filter('woocommerce_order_item_visible', $plugin_public, 'hide_order_item', 10, 2);
        // ajax.
        $this->loader->add_action('wp_ajax_get_vpc_editor', $plugin_public, 'get_vpc_editor_ajax');
        $this->loader->add_action('wp_ajax_nopriv_get_vpc_editor', $plugin_public, 'get_vpc_editor_ajax');
        $this->loader->add_action('wp_ajax_get_vpc_product_qty', $plugin_public, 'get_vpc_product_qty_ajax');
        $this->loader->add_action('wp_ajax_nopriv_get_vpc_product_qty', $plugin_public, 'get_vpc_product_qty_ajax');
        // update price from form builder.
        $this->loader->add_action('wp_ajax_update_price_from_form', $plugin_public, 'update_price_from_form');
        $this->loader->add_action('wp_ajax_nopriv_update_price_from_form', $plugin_public, 'update_price_from_form');
        // Wplm.
        $this->loader->add_filter('pll_the_language_link', $plugin_public, 'get_switcher_proper_url', 10, 3);
        $this->loader->add_filter('icl_ls_languages', $plugin_public, 'get_switcher_proper_url_wpml');
        // compatibility invoice plugin.
        $this->loader->add_action('wc_pip_order_item_meta_start', $plugin_public, 'get_vpc_config_details', 10, 3);
        // Add vpc data in woocommerce print invoice.
        $this->loader->add_action('wcdn_order_item_after', $plugin_public, 'set_invoice_order_item_meta', 10, 3);
        // compability with WAD plugin.
        $this->loader->add_filter('wad_is_applicable', $plugin_public, 'check_if_wad_is_applicable', 99, 3);
        $this->loader->add_filter('wad_after_calculate_sale_price', $plugin_public, 'get_sale_price_if_linkedd_product_is_hide', 99, 3);
     // @codingStandardsIgnoreStart
     // Uploads.
     // $this->loader->add_action( 'wp_ajax_handle_picture_upload', $plugin_public, 'vpc_handle_picture_upload');
     // $this->loader->add_action( 'wp_ajax_nopriv_handle_picture_upload', $plugin_public, 'vpc_handle_picture_upload');
     // Order again
     // $this->loader->add_filter('woocommerce_order_again_cart_item_data', $plugin_public, 'set_order_again_cart_item_data',10,3);.
     // @codingStandardsIgnoreEnd
    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since 1.0.0
     */
    public function run()
    {
        $this->loader->run();
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @since  1.0.0
     * @return string    The name of the plugin.
     */
    public function get_plugin_name()
    {
        return $this->plugin_name;
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @since  1.0.0
     * @return Vpc_Loader    Orchestrates the hooks of the plugin.
     */
    public function get_loader()
    {
        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @since  1.0.0
     * @return string    The version number of the plugin.
     */
    public function get_version()
    {
        return $this->version;
    }

}
