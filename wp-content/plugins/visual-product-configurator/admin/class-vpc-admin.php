<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://www.orionorigin.com
 * @since      1.0.0
 *
 * @package    Vpc
 * @subpackage Vpc/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Vpc
 * @subpackage Vpc/admin
 * @author     ORION <help@orionorigin.com>
 */
class VPC_Admin
{

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $plugin_name       The name of this plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct($plugin_name, $version)
	{

		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles()
	{
		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Vpc_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Vpc_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		if (is_vpc_admin_screen()) {
			wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/vpc-admin.css', array(), VPC_VERSION, 'all');
			wp_enqueue_style('o-flexgrid', plugin_dir_url(__FILE__) . 'css/flexiblegs.css', array(), VPC_VERSION, 'all');
			wp_enqueue_style('o-ui', plugin_dir_url(__FILE__) . 'css/UI.css', array(), VPC_VERSION, 'all');
			wp_enqueue_style('o-tooltip', VPC_URL . 'public/css/tooltip.min.css', array(), VPC_VERSION, 'all');
			wp_enqueue_style('o-bs-modal-css', VPC_URL . 'admin/js/modal/modal.min.css', array(), VPC_VERSION, 'all');
			if (class_exists('WooCommerce')) {
				wp_enqueue_style('woocommerce_admin_styles', WC()->plugin_url() . '/assets/css/admin.css', array(), WC()->version);
			}
		}
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts()
	{
		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Vpc_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Vpc_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		if (is_vpc_admin_screen()) {
			wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/vpc-admin.js', array('jquery'), VPC_VERSION, false);
			wp_localize_script($this->plugin_name, 'ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));
			wp_enqueue_script('jquery');
			wp_enqueue_script('jquery-ui-core');
			wp_enqueue_script('o-admin', plugin_dir_url(__FILE__) . 'js/o-admin.js', array('jquery', 'jquery-ui-sortable'), VPC_VERSION, false);
			wp_localize_script('o-admin', 'home_url', array(home_url('/')));
			wp_enqueue_script('o-tooltip', VPC_URL . 'public/js/tooltip.min.js', array('jquery'), VPC_VERSION, false);
			// phpcs:ignore // wp_enqueue_script("o-lazyload", VPC_URL . 'admin/js/jquery.lazyload.min.js', array('jquery'), VPC_VERSION, false);
			wp_enqueue_script('o-modal-js', VPC_URL . 'admin/js/modal/modal.min.js', array('jquery'), VPC_VERSION, false);
			wp_enqueue_script('jquery-serializejson', VPC_URL . 'public/js/jquery.serializejson.min.js', array('jquery'), VPC_VERSION, false);
			if (is_plugin_active('woocommerce/woocommerce.php')) {
				wp_enqueue_script('select2', WC()->plugin_url() . '/assets/js/select2/select2.full.min.js', array('jquery'), '4.0.3', false);
			}
			// wp_enqueue_style( 'select2' );
			// Set string translation for js scripts.
			$string_translations = array(
				'reverse_cb_label'          => __('Enable reverse rule', 'vpc'),
				'group_conditions_relation' => __('Conditions relationship', 'vpc'),
			);
			wp_localize_script($this->plugin_name, 'string_translations', $string_translations);
		}
	}

	/**
	 * Added a custom column.
	 *
	 * @param array $columns The screen columns array.
	 */
	public function get_vpc_screen_layout_columns($columns)
	{
		$columns['vpc-config'] = 1;
		return $columns;
	}

	/**
	 * Added configuration screen layout.
	 */
	public function get_vpc_config_screen_layout()
	{
		return 1;
	}

	/**
	 * Added a metabox order
	 *
	 * @param array $order The user order.
	 */
	public function metabox_order($order)
	{
		$order['advanced'] = 'vpc-config-preview-box,vpc-config-settings-box,vpc-config-conditional-rules-box,submitdiv';
		return $order;
	}

	/**
	 * Builds all the plugin menu and submenu
	 */
	public function get_menu()
	{
		$parent_slug = 'edit.php?post_type=vpc-config';
		if (class_exists('Ofb')) {
			add_submenu_page('edit.php?post_type=vpc-config', __('Form Builder', 'vpc'), __('Form Builder', 'vpc'), 'manage_product_terms', 'edit.php?post_type=ofb', false);
		}
		add_submenu_page($parent_slug, __('Settings', 'vpc'), __('Settings', 'vpc'), 'manage_product_terms', 'vpc-manage-settings', array($this, 'get_vpc_settings_page'));
		add_submenu_page($parent_slug, __('Getting Started', 'vpc'), __('Getting Started', 'vpc'), 'manage_product_terms', 'vpc-getting-started', array($this, 'get_vpc_getting_started_page'));
		add_submenu_page(null, __('Manage conditional rules', 'vpc'), __('Manage conditional rules', 'vpc'), 'manage_product_terms', 'manage-conditional-rules', array($this, 'get_conditional_rules_page'));
	}

	/**
	 * Create settings tabs
	 *
	 * @param array $base_settings Base setting content.
	 */
	public function create_tabs_by_addon($base_settings)
	{
		$section_begin = array();
		$section_end   = array();

		foreach ($base_settings as $key => $value) {
			if ('sectionbegin' === $value['type']) {
				array_push($section_begin, $key);
			} else {
				if ('sectionend' === $value['type']) {
					array_push($section_end, $key);
				}
			}
		}
		$tabs_group = array();
		foreach ($section_begin as $key => $value) {
			if (!isset($section_end[$key])) {
				$section_end[$key] = 0;
			}
			$length = ($section_end[$key] - $value) + 1;
			$new    = array_slice($base_settings, $value, $length);
			if (!empty($new)) {
				$tabs_group[$new[0]['id']] = $new;
			}
		}

		return $tabs_group;
	}

	/**
	 * Add settings tabs contents
	 *
	 * @param array  $group          Addon template.
	 * @param string $active_tab     Active tab.
	 * @param string $active_onglet  Active onglet.
	 */
	public function vpc_create_settings_tabs_contents($group, $active_tab, $active_onglet)
	{
		if (isset($group) && !empty($group) && $active_tab === $active_onglet) {
?> <div class="vpc-addons">
				<?php
				echo wp_kses(o_admin_fields($group), get_allowed_tags());
				?>
			</div>
			<?php
		}
	}

	/**
	 * Create settings tabs headers.
	 *
	 * @param array  $tabs_group     Addon template.
	 * @param string $active_tab     Active tab.
	 */
	public function vpc_create_settings_tabs_header($tabs_group, $active_tab)
	{
		if (isset($tabs_group) && !empty($tabs_group)) {
			foreach ($tabs_group as $group_key => $group_value) {
				if ('vpc-options-container' === $group_key) {
			?>
					<a href="<?php echo esc_url(admin_url('edit.php?post_type=vpc-config&page=vpc-manage-settings')); ?>" class="nav-tab <?php echo 'vpc-manage-settings' === $active_tab ? 'nav-tab-active' : ''; ?>"><?php esc_html_e('Visual Products Configurator', 'vpc'); ?></a>
				<?php
				} elseif ('vpc-email-container' === $group_key) {
				?>
					<a href="<?php echo esc_url(admin_url('edit.php?post_type=vpc-config&page=vpc-manage-settings&section=vpc-email-container')); ?>" class="nav-tab <?php echo 'vpc-email-container' === $active_tab ? 'nav-tab-active' : ''; ?>"><?php esc_html_e('Request a quote Addon', 'vpc'); ?></a>
				<?php
				} elseif ('vpc-ofb-container' === $group_key) {
				?>
					<a href="<?php echo esc_url(admin_url('edit.php?post_type=vpc-config&page=vpc-manage-settings&section=vpc-ofb-container')); ?>" class="nav-tab <?php echo 'vpc-ofb-container' === $active_tab ? 'nav-tab-active' : ''; ?>"><?php esc_html_e('Form Builder Addon', 'vpc'); ?></a>
				<?php
				} elseif ('vpc-os-container' === $group_key) {
				?>
					<a href="<?php echo esc_url(admin_url('edit.php?post_type=vpc-config&page=vpc-manage-settings&section=vpc-os-container')); ?>" class="nav-tab <?php echo 'vpc-os-container' === $active_tab ? 'nav-tab-active' : ''; ?>"><?php esc_html_e('Ouando Skin', 'vpc'); ?></a>
				<?php
				} elseif ('vpc-msl-container' === $group_key) {
				?>
					<a href="<?php echo esc_url(admin_url('edit.php?post_type=vpc-config&page=vpc-manage-settings&section=vpc-msl-container')); ?>" class="nav-tab <?php echo 'vpc-msl-container' === $active_tab ? 'nav-tab-active' : ''; ?>"><?php esc_html_e('Modern Skin', 'vpc'); ?></a>
				<?php
				} elseif ('vpc-lns-container' === $group_key) {
				?>
					<a href="<?php echo esc_url(admin_url('edit.php?post_type=vpc-config&page=vpc-manage-settings&section=vpc-lns-container')); ?>" class="nav-tab <?php echo 'vpc-lns-container' === $active_tab ? 'nav-tab-active' : ''; ?>"><?php esc_html_e('Lom Nava Skin', 'vpc'); ?></a>
				<?php
				} elseif ('vpc-cta-container' === $group_key) {
				?>
					<a href="<?php echo esc_url(admin_url('edit.php?post_type=vpc-config&page=vpc-manage-settings&section=vpc-cta-container')); ?>" class="nav-tab <?php echo 'vpc-cta-container' === $active_tab ? 'nav-tab-active' : ''; ?>"><?php esc_html_e('Custom Text Add On', 'vpc'); ?></a>
				<?php
				} elseif ('vpc-mva-container' === $group_key) {
				?>
					<a href="<?php echo esc_url(admin_url('edit.php?post_type=vpc-config&page=vpc-manage-settings&section=vpc-mva-container')); ?>" class="nav-tab <?php echo 'vpc-mva-container' === $active_tab ? 'nav-tab-active' : ''; ?>"><?php esc_html_e('Multiple Views Addon', 'vpc'); ?></a>
				<?php
				} elseif ('vpc-sfla-container' === $group_key) {
				?>
					<a href="<?php echo esc_url(admin_url('edit.php?post_type=vpc-config&page=vpc-manage-settings&section=vpc-sfla-container')); ?>" class="nav-tab <?php echo 'vpc-sfla-container' === $active_tab ? 'nav-tab-active' : ''; ?>"><?php esc_html_e('Save For Later Addon', 'vpc'); ?></a>
				<?php
				} elseif ('vpc-upload-container' === $group_key) {
				?>
					<a href="<?php echo esc_url(admin_url('edit.php?post_type=vpc-config&page=vpc-manage-settings&section=vpc-upload-container')); ?>" class="nav-tab <?php echo 'vpc-upload-container' === $active_tab ? 'nav-tab-active' : ''; ?>"><?php esc_html_e('Upload Image', 'vpc'); ?></a>
				<?php
				} elseif ('vpc-sci-container' === $group_key) {
				?>
					<a href="<?php echo esc_url(admin_url('edit.php?post_type=vpc-config&page=vpc-manage-settings&section=vpc-sci-container')); ?>" class="nav-tab <?php echo 'vpc-sci-container' === $active_tab ? 'nav-tab-active' : ''; ?>"><?php esc_html_e('Save Configuration Image Add-on', 'vpc'); ?></a>
				<?php
				} elseif ('vpc-dcod-container' === $group_key) {
				?>
					<a href="<?php echo esc_url(admin_url('edit.php?post_type=vpc-config&page=vpc-manage-settings&section=vpc-dcod-container')); ?>" class="nav-tab <?php echo 'vpc-dcod-container' === $active_tab ? 'nav-tab-active' : ''; ?>"><?php esc_html_e('Defined Currencies and Options Details', 'vpc'); ?></a>
			<?php
				} else {
					do_action('vpc_add_settings_onglet', $tabs_group, $group_key, $group_value, $active_tab);
				}
			}
		}
	}

	/**
	 * Create of body settings tabs.
	 *
	 * @param array  $tabs_group     Addon template.
	 * @param string $active_tab     Active tab.
	 */
	public function vpc_create_settings_tabs_body($tabs_group, $active_tab)
	{
		if (isset($tabs_group) && !empty($tabs_group)) {

			foreach ($tabs_group as $group_key => $group_value) {
				if (strstr($group_key, 'vpc-options')) {
					$this->vpc_create_settings_tabs_contents($group_value, $active_tab, 'vpc-manage-settings');
				} elseif (strstr($group_key, 'vpc-cta')) {
					$this->vpc_create_settings_tabs_contents($group_value, $active_tab, 'vpc-cta-container');
				} elseif (strstr($group_key, 'vpc-email') || strstr($group_key, 'vpc-rqa')) {
					$this->vpc_create_settings_tabs_contents($group_value, $active_tab, 'vpc-email-container');
				} elseif (strstr($group_key, 'vpc-mva')) {
					$this->vpc_create_settings_tabs_contents($group_value, $active_tab, 'vpc-mva-container');
				} elseif (strstr($group_key, 'vpc-sfla')) {
					$this->vpc_create_settings_tabs_contents($group_value, $active_tab, 'vpc-sfla-container');
				} elseif (strstr($group_key, 'vpc-upload')) {
					$this->vpc_create_settings_tabs_contents($group_value, $active_tab, 'vpc-upload-container');
				} elseif (strstr($group_key, 'vpc-sci')) {
					$this->vpc_create_settings_tabs_contents($group_value, $active_tab, 'vpc-sci-container');
				} elseif (strstr($group_key, 'vpc-dcod')) {
					$this->vpc_create_settings_tabs_contents($group_value, $active_tab, 'vpc-dcod-container');
				} elseif (strstr($group_key, 'vpc-ofb')) {
					$this->vpc_create_settings_tabs_contents($group_value, $active_tab, 'vpc-ofb-container');
				} elseif (strstr($group_key, 'vpc-os')) {
					$this->vpc_create_settings_tabs_contents($group_value, $active_tab, 'vpc-os-container');
				} elseif (strstr($group_key, 'vpc-msl')) {
					$this->vpc_create_settings_tabs_contents($group_value, $active_tab, 'vpc-msl-container');
				} elseif (strstr($group_key, 'vpc-lns')) {
					$this->vpc_create_settings_tabs_contents($group_value, $active_tab, 'vpc-lns-container');
				} else {
					do_action('vpc_add_settings_body', $tabs_group, $group_key, $group_value, $active_tab);
				}
			}
		}
		global $o_row_templates;
	}

	/**
	 * Settings page.
	 */
	public function get_vpc_settings_page()
	{
		$old_data = get_option('vpc-options');

		// Get the section or the page name.
		$session = '';
		if (isset($_GET['section'])) { // phpcs:ignore
			$session = $_GET['section']; // phpcs:ignore
		} elseif (isset($_GET['page'])) { // phpcs:ignore
			$session = $_GET['page']; // phpcs:ignore
		}

		if ((isset($_POST['vpc-options']) && !empty($_POST['vpc-options']))) { // phpcs:ignore
			$datas = $_POST['vpc-options']; // phpcs:ignore

			// Affecter une valeur par défaut lorsqu'une case des options social share est décochée.
			if (isset($session) && 'ssa-global-container' === $session) {
				if (!isset($datas['facebook'])) {
					$datas['facebook'] = '0';
				}
				if (!isset($datas['twitter'])) {
					$datas['twitter'] = '0';
				}
				if (!isset($datas['pinterest'])) {
					$datas['pinterest'] = '0';
				}
				if (!isset($datas['googleplus'])) {
					$datas['googleplus'] = '0';
				}
				if (!isset($datas['whatsapp'])) {
					$datas['whatsapp'] = '0';
				}
				if (!isset($datas['mail'])) {
					$datas['mail'] = '0';
				}
			}

			if (is_array($old_data)) {
				$new_datas = array_merge($old_data, $datas);
			} else {
				$new_datas = $datas;
			}
			update_option('vpc-options', $new_datas);
			$vpc        = new Vpc();
			$vpc_public = new VPC_Public($vpc->get_plugin_name(), $vpc->get_version());
			$vpc_public->init_globals();
			$license_activation_result = vpc_activate_vpc_and_all_addons_licenses();
			?>
			<div id="activation-success-notice" class="notice notice-info is-dismissible" style="display:block;">
				<?php
				foreach ($license_activation_result as $key => $value) {
					if ('Activation successfully completed.' !== $value[$key . '-checking']) {
				?>
						<div><strong> <?php esc_html_e('Visual products configurator ', 'vpc'); ?> <?php echo esc_html($value['name']); ?> </strong> : <?php echo esc_html($value[$key . '-checking']); ?></div>
				<?php
					}
				}
				?>
			</div>
		<?php
			global $wp_rewrite;
			$wp_rewrite->flush_rules();
		}
		?>
		<div class="wrap woocommerce wc_addons_wrap">
			<h1><?php esc_html_e('Visual Products Configurator Settings', 'vpc'); ?></h1>
			<form method="POST" action="" class="mg-top">
				<div class="postbox" id="vpc-options-container">
					<?php
					$begin              = array(
						'type'  => 'sectionbegin',
						'id'    => 'vpc-options-container',
						'table' => 'options',
					);
					$pages_ids          = get_all_pages_exclude_woocommerce_pages();
					$configuration_page = array(
						'title'   => __('Configuration page', 'vpc'),
						'name'    => 'vpc-options[config-page]',
						'type'    => 'select',
						'options' => $pages_ids,
						'default' => '',
						'desc'    => __('Page where all products are configured.', 'vpc'),
					);

					$automatically_append = array(
						'title'   => __('Manage the configuration page', 'vpc'),
						'name'    => 'vpc-options[manage-config-page]',
						'type'    => 'radio',
						'options' => array(
							'Yes' => 'Yes',
							'No'  => 'No',
						),
						'default' => 'Yes',
						'desc'    => __('If Yes, the plugin will handle the content of the configuration page. If No, use the shortcode [wpb_builder] to display the configurator INSIDE the configuration page.', 'vpc'),
					);

					$cart_actions_arr = array(
						'none'                     => __('None', 'vpc'),
						'refresh'                  => __('Refresh', 'vpc'),
						'redirect'                 => __('Redirect to cart page', 'vpc'),
						'redirect_to_product_page' => __('Redirect to product page', 'vpc'),
					);

					$cart_actions_arr = apply_filters('vpc_cart_actions_arr', $cart_actions_arr);

					$hide_loader = array(
						'title'   => __('Hide configurator loader', 'vpc'),
						'name'    => 'vpc-options[hide-loader]',
						'type'    => 'radio',
						'options' => array(
							'Yes' => 'Yes',
							'No'  => 'No',
						),
						'default' => 'Yes',
						'desc'    => __('Would you like the configurator loader to be hidden?', 'vpc'),
					);

					$hide_qty_box = array(
						'title'   => __('Hide quantity box', 'vpc'),
						'name'    => 'vpc-options[hide-qty]',
						'type'    => 'radio',
						'options' => array(
							'Yes' => 'Yes',
							'No'  => 'No',
						),
						'default' => 'Yes',
						'desc'    => __('Hide quantity box on configurator page?', 'vpc'),
					);

					$hide_wc_add_to_cart_btn = array(
						'title'   => __('Hide woocommerce add to cart button ', 'vpc'),
						'name'    => 'vpc-options[hide-wc-add-to-cart]',
						'type'    => 'radio',
						'options' => array(
							'Yes' => 'Yes',
							'No'  => 'No',
						),
						'default' => 'Yes',
						'desc'    => __('Should the plugin hide the default woocommerce add to cart button on configurables products?', 'vpc'),
					);

					$hide_build_your_own_btn = array(
						'title'   => __('Hide Build your own button on shop page', 'vpc'),
						'name'    => 'vpc-options[hide-build-your-own]',
						'type'    => 'radio',
						'options' => array(
							'Yes' => 'Yes',
							'No'  => 'No',
						),
						'default' => 'No',
						'desc'    => __('Should the plugin hide the build your own button on shop page?', 'vpc'),
					);

					$hide_wc_add_to_cart_btn_on_shop_page = array(
						'title'   => __('Hide woocommerce add to cart button on shop page', 'vpc'),
						'name'    => 'vpc-options[hide-wc-add-to-cart-on-shop-page]',
						'type'    => 'radio',
						'options' => array(
							'Yes' => 'Yes',
							'No'  => 'No',
						),
						'default' => 'No',
						'desc'    => __('Should the plugin hide the default woocommerce add to cart button on shop page?', 'vpc'),
					);

					$hide_secondary_product_in_cart = array(
						'title'   => __('Hide linked products cart page ', 'vpc'),
						'name'    => 'vpc-options[hide-wc-secondary-product-in-cart]',
						'type'    => 'radio',
						'options' => array(
							'Yes' => 'Yes',
							'No'  => 'No',
						),
						'default' => 'Yes',
						'desc'    => __('Should the plugin hide the products linked to the options in the cart page?', 'vpc'),
					);

					$hide_options_selected_in_cart = array(
						'title'   => __('Hide options selected in cart', 'vpc'),
						'name'    => 'vpc-options[hide-options-selected-in-cart]',
						'type'    => 'radio',
						'options' => array(
							'Yes' => 'Yes',
							'No'  => 'No',
						),
						'default' => 'No',
						'desc'    => __('Should the plugin hide the options selected in the cart page?', 'vpc'),
					);

					$action_in_cart = array(
						'title'   => __('Action after addition to cart', 'vpc'),
						'name'    => 'vpc-options[action-after-add-to-cart]',
						'type'    => 'select',
						'options' => $cart_actions_arr,
						'default' => '',
						'desc'    => __('What should happen once the customer adds the configured product to the cart.', 'vpc'),
					);
					// @codingStandardsIgnoreStart
					/*
			 $ajax_load = array(
			  'title'   => __( 'Ajax Loading', 'vpc' ),
			  'name'    => 'vpc-options[ajax-loading]',
			  'type'    => 'radio',
			  'options' => array(
			  'Yes' => 'Yes',
			  'No'  => 'No',
			  ),
			  'default' => 'No',
			  'desc'    => __( 'Load the editor via ajax. If enabled, this will speed up configuration page load by building configurator after the configuration page is fully loaded.', 'vpc' ),
			  ); */
					// @codingStandardsIgnoreEnd
					$active_follow_scroll_desktop = array(
						'title'   => __('Scroll follow', 'vpc'),
						'name'    => 'vpc-options[follow-scroll-desktop]',
						'type'    => 'radio',
						'options' => array(
							'Yes' => 'Yes',
							'No'  => 'No',
						),
						'default' => 'Yes',
						'desc'    => __('Gives the preview the ability to follow scroll so that it can always remain visible.', 'vpc'),
					);

					$active_follow_scroll_mobile = array(
						'title'   => __('Scroll follow on mobile', 'vpc'),
						'name'    => 'vpc-options[follow-scroll-mobile]',
						'type'    => 'radio',
						'options' => array(
							'Yes' => 'Yes',
							'No'  => 'No',
						),
						'default' => 'No',
						'desc'    => __('If enabled, the preview scroll follow will remain active on mobile.', 'vpc'),
					);

					$option_view_name_tooltip = array(
						'title'   => __('View option name on tooltip', 'vpc'),
						'name'    => 'vpc-options[view-name]',
						'type'    => 'radio',
						'options' => array(
							'Yes' => 'Yes',
							'No'  => 'No',
						),
						'default' => 'Yes',
						'desc'    => __('If enabled, the option name will be seen on the option tooltip', 'vpc'),
					);

					$option_view_price_tooltip = array(
						'title'   => __('View option price on tooltip', 'vpc'),
						'name'    => 'vpc-options[view-price]',
						'type'    => 'radio',
						'options' => array(
							'Yes' => 'Yes',
							'No'  => 'No',
						),
						'default' => 'Yes',
						'desc'    => __('If enabled, the option price will be seen on the option tooltip', 'vpc'),
					);
					$product_link              = array(
						'title'   => __('Links products options', 'vpc'),
						'name'    => 'vpc-options[product-link]',
						'type'    => 'radio',
						'options' => array(
							'Yes' => 'Yes',
							'No'  => 'No',
						),
						'default' => 'No',
						'desc'    => __('Do you want to link options to products?', 'vpc'),
					);

					$store_original_config    = array(
						'title'   => __('Store original configuration data in orders', 'vpc'),
						'name'    => 'vpc-options[store-original-configs]',
						'type'    => 'radio',
						'options' => array(
							'Yes' => 'Yes',
							'No'  => 'No',
						),
						'default' => 'Yes',
						'desc'    => __('If enabled, the plugin will store a snapshot of the original configuration data in the orders table everytime an order is made.', 'vpc'),
					);
					$image_configured_in_mail = array(
						'title'   => __('Display the configured image in the order mail', 'vpc'),
						'name'    => 'vpc-options[img-merged-mail]',
						'type'    => 'radio',
						'options' => array(
							'Yes' => 'Yes',
							'No'  => 'No',
						),
						'default' => 'Yes',
						'desc'    => __('Do you want to add the configured image to the order mail', 'vpc'),
					);
					$select_first_option      = array(
						'title'   => __('Select automatically the first element of the component when rules are applied', 'vpc'),
						'name'    => 'vpc-options[select-first-elem]',
						'type'    => 'radio',
						'options' => array(
							'Yes' => 'Yes',
							'No'  => 'No',
						),
						'default' => 'Yes',
						'desc'    => __('Enable/Disable the automatic selection of the first element of the component when the rules are applied', 'vpc'),
					);

					$license_key           = array(
						'title'   => __('License key', 'vpc'),
						'name'    => 'vpc-options[purchase-code]',
						'type'    => 'text',
						'desc'    => __('Licence key received after your purchase. <a href="https://orionorigin.com/my-account/orders/" target="blank">Where is my licence key</a>?', 'vpc'),
						'default' => '',
					);
					$default_option_width  = array(
						'title'   => __('Default skin icon width', 'vpc'),
						'name'    => 'vpc-options[default-icon-width]',
						'type'    => 'number',
						'default' => 25,
					);
					$default_option_height = array(
						'title'   => __('Default skin icon height', 'vpc'),
						'name'    => 'vpc-options[default-icon-height]',
						'type'    => 'number',
						'default' => 25,
					);
					$default_options_size  = array(
						'title'   => __('Skin icons size', 'vpc'),
						'name'    => 'vpc-options[default-icons-size]',
						'type'    => 'groupedfields',
						'fields'  => array($default_option_width, $default_option_height),
						'default' => '',
						'desc'    => __('Set default skin icons size.', 'vpc'),
					);

					$end           = array('type' => 'sectionend');
					$base_settings = apply_filters(
						'vpc_global_settings',
						array(
							$begin,
							$license_key,
							$configuration_page,
							$hide_loader,
							$hide_qty_box,
							$hide_wc_add_to_cart_btn,
							$hide_wc_add_to_cart_btn_on_shop_page,
							$hide_secondary_product_in_cart,
							$hide_options_selected_in_cart,
							$hide_build_your_own_btn,
							$automatically_append,
							$active_follow_scroll_desktop,
							$active_follow_scroll_mobile,
							$action_in_cart,
							// $ajax_load,
							$option_view_name_tooltip,
							$product_link,
							$option_view_price_tooltip,
							$store_original_config,
							$image_configured_in_mail,
							$select_first_option,
							$default_options_size,
							$end,
						)
					);

					$tabs_group = $this->create_tabs_by_addon($base_settings);
					$active_tab = isset($_GET['section']) ? $_GET['section'] : 'vpc-manage-settings'; // phpcs:ignore
					global $o_row_templates;
					?>
					<nav class="nav-tab-wrapper woo-nav-tab-wrapper">
						<?php
						$this->vpc_create_settings_tabs_header($tabs_group, $active_tab);
						?>
					</nav>
					<div class="vpc-getting-started addons-featured">
						<?php
						$this->vpc_create_settings_tabs_body($tabs_group, $active_tab);
						?>
					</div>
				</div>
				<script>
					var o_rows_tpl = <?php echo wp_json_encode($o_row_templates); ?>;
				</script>
				<input type="submit" class="button button-primary button-large" value="Save">
			</form>
		</div>
		<?php
	}

	/**
	 * Checks if the database needs to be upgraded
	 */
	public function run_vpc_db_updates_requirements()
	{
		// Checks db structure for v2.0.
		$vpc_db_version = get_option('vpc-db-version');
		if (empty($vpc_db_version)) {
			$vpc_db_version = 0;
		}
		if ($vpc_db_version < 2) {
			$new_db_version = 2;
		?>
			<div class="updated" id="vpc-updater-container">
				<h2><?php esc_html_e('Visual Product Configurator for WooCommerce database update required.', 'vpc'); ?></h2>
				<div>
					<ul>
						<li>
							<h3><?php esc_html_e('Conditional rules', 'vpc'); ?></h3>
							<ul>
								<li><?php esc_html_e('Instead of managing the rules from the configuration page, we moved the entire feature to a separate page accessible using the <strong>Manage conditional rules</strong> button from <strong>Product Builder > Configurations</strong>.', 'vpc'); ?></li>
							</ul>
						</li>
						<li>
							<h3><?php esc_html_e('Custom text add-on', 'vpc'); ?></h3>
							<ul>
								<li><?php esc_html_e("From now, in order to create a text input option, you'll first have to create a text component in <strong>Product Builder > Text components</strong>", 'vpc'); ?></li>
								<li><?php esc_html_e('If you click on the Run Updater button below, the plugin will recreate all custom text components based on your data.', 'vpc'); ?></li>
							</ul>
						</li>
					</ul>

					<input type="button" value="<?php esc_html_e('Run the updater', 'vpc'); ?>" id="vpc-run-updater" class="button button-primary" />
					<div class="loading" style="display:none;"></div>
				</div>
			</div>
			<style>
				#vpc-updater-container {
					padding: 3px 17px;
					/*font-size: 13px;*/
					line-height: 36px;
					margin-left: 0px;
					border-left: 5px solid #e14d43 !important;
				}

				#vpc-updater-container.done {
					border-color: #7ad03a !important;
				}

				#vpc-run-updater {
					background: #e14d43;
					border-color: #d02a21;
					color: #fff;
					-webkit-box-shadow: inset 0 1px 0 #ec8a85, 0 1px 0 rgba(0, 0, 0, .15);
					box-shadow: inset 0 1px 0 #ec8a85, 0 1px 0 rgba(0, 0, 0, .15);
					text-shadow: none;
				}

				#vpc-run-updater:focus,
				#vpc-run-updater:hover {
					background: #dd362d;
					border-color: #ba251e;
					color: #fff;
					-webkit-box-shadow: inset 0 1px 0 #e8756f;
					box-shadow: inset 0 1px 0 #e8756f;
				}

				.loading {
					background: url("<?php echo esc_attr(VPC_URL); ?>/admin/images/spinner.gif") 10% 10% no-repeat transparent;
					background-size: 111%;
					width: 32px;
					height: 40px;
					display: inline-block;
				}
			</style>
			<script>
				//jQuery('.loading').hide();
				jQuery('#vpc-run-updater').click('click', function() {
					var ajax_url = "<?php echo esc_url(admin_url('admin-ajax.php')); ?>";
					if (confirm("It is strongly recommended that you backup your database before proceeding. Are you sure you wish to run the updater now")) {
						jQuery('.loading').show();
						jQuery.post(
							ajax_url, {
								action: 'run_updater',
								new_db_version: <?php echo esc_attr($new_db_version); ?>
							},
							function(data) {
								jQuery('.loading').hide();
								jQuery('#vpc-updater-container').html(data);
								jQuery('#vpc-updater-container').addClass("done");
							}
						);
					}

				});
			</script>
		<?php
		}
	}

	/**
	 * Run the updater
	 */
	public function run_vpc_updater()
	{
		ob_start();
		$new_db_version = get_proper_value($_POST, 'new_db_version', ''); // phpcs:ignore
		if (2 === intval($new_db_version)) {
			// We migrate the custom text data.
			$this->migrate_custom_text_data();

			esc_html_e('Done!', 'vpc');
			update_option('vpc-db-version', 2);
		} else {
			echo esc_html('No migration callback available for DB version: ' . $new_db_version);
		}
		$output = ob_get_contents();
		ob_end_clean();
		echo wp_kses($output, get_allowed_tags());
		die();
	}

	/**
	 * Migrate custom text data
	 */
	public function migrate_custom_text_data()
	{
		global $wpdb;
		$sql                = 'select distinct post_id from ' . $wpdb->postmeta . ' where meta_value like "%\"behaviour\";s:4:\"text\"%"';
		$configs_to_migrate = $wpdb->get_col($sql); // phpcs:ignore
		if (!empty($configs_to_migrate)) {
			foreach ($configs_to_migrate as $config_id) {
				$config_metas = get_post_meta($config_id, 'vpc-config', true);
				$config_title = get_the_title($config_id);
				foreach ($config_metas['components'] as $i => $component) {
					if ('text' === $component['behaviour']) {
						continue;
					}
					foreach ($component['options'] as $j => $option) {
						$text_top       = get_proper_value($option, 'text-top');
						$text_left      = get_proper_value($option, 'text-left');
						$angle          = get_proper_value($option, 'angle');
						$font_size      = get_proper_value($option, 'size');
						$max_char       = get_proper_value($option, 'max_char');
						$content_width  = get_proper_value($option, 'text-content-width');
						$content_height = get_proper_value($option, 'text-content-height');
						$use_box        = 'no';
						if ($content_width || $content_height) {
							$use_box = 'yes';
						}

						$text_component_data = array(
							'post_title'  => $config_title . ' - ' . $component['cname'] . ' - ' . $option['name'],
							'post_status' => 'publish',
							'post_type'   => 'vpc-text-component',
						);
						$text_component_id   = wp_insert_post($text_component_data);

						$metas = array(
							'top'         => $text_top,
							'left'        => $text_left,
							'angle'       => $angle,
							'font-size'   => $font_size,
							'chars-limit' => $max_char,
							'use-box'     => $use_box,
							'box-width'   => $content_width,
							'box-height'  => $content_height,
						);
						update_post_meta($text_component_id, 'vpc-text-component', $metas);
						$config_metas['components'][$i]['options'][$j]['text-component'] = $text_component_id;
						update_post_meta($config_id, 'vpc-config', $config_metas);
					}
				}
			}
		}
	}

	/**
	 *  Add configurable product column in  product tables
	 *
	 * @param array $defaults  Default product colomn.
	 */
	public function get_product_columns($defaults)
	{
		$defaults['configuration'] = __('Configurable', 'vpc');
		return $defaults;
	}

	/**
	 *  Add configurable product column in  product tables
	 *
	 * @param array $column_name  Product colomn name.
	 * @param array $id           Product id.
	 */
	public function get_products_columns_values($column_name, $id)
	{
		if ('configuration' === $column_name) {
			$is_configurable = vpc_product_is_configurable($id);
			if ($is_configurable) {
				esc_html_e('Yes', 'vpc');
			} else {
				esc_html_e('No', 'vpc');
			}
		}
	}

	/**
	 *  Get max input var value.
	 */
	public function get_max_input_vars_php_ini()
	{
		$total_max_normal = ini_get('max_input_vars');
		$msg              = __('Your max input var is', 'vpc') . '<strong>' . $total_max_normal . '</strong>' . __('but this page contains', 'vpc') . '<strong>{nb}</strong>' . __('fields. You may experience a lost of data after saving. In order to fix this issue, please increase', 'vpc') . '<strong>' . __('the max_input_vars', 'vpc') . '</strong>' . __('value in your php.ini file', 'vpc');
		?>
		<script type="text/javascript">
			var o_max_input_vars = <?php echo esc_attr($total_max_normal); ?>;
			var o_max_input_msg = "<?php echo esc_attr($msg); ?>";
		</script>
	<?php
	}

	/**
	 *  Getting started page
	 */
	public function get_vpc_getting_started_page()
	{
	?>
		<h1 class="">
			<?php esc_html_e('About Visual Products Configurator', 'vpc'); ?>
		</h1>
		<?php
		$active_tab = isset($_GET['section']) ? $_GET['section'] : 'vpc-getting-started'; // phpcs:ignore
		?>
		<div class="wrap woocommerce wc_addons_wrap">
			<nav class="nav-tab-wrapper woo-nav-tab-wrapper">
				<a href="<?php echo esc_url(admin_url('edit.php?post_type=vpc-config&page=vpc-getting-started')); ?>" class="nav-tab <?php echo 'vpc-getting-started' === $active_tab ? 'nav-tab-active' : ''; ?>"><?php esc_html_e('Browse our extensions', 'vpc'); ?></a>
				<a href="<?php echo esc_url(admin_url('edit.php?post_type=vpc-config&page=vpc-getting-started&section=vpc-tutorials')); ?>" class="nav-tab <?php echo 'vpc-tutorials' === $active_tab ? 'nav-tab-active' : ''; ?>"><?php esc_html_e('Videos tutorials', 'vpc'); ?></a>
				<a href="<?php echo esc_url(admin_url('edit.php?post_type=vpc-config&page=vpc-getting-started&section=vpc-hire-us')); ?>" class="nav-tab <?php echo 'vpc-hire-us' === $active_tab ? 'nav-tab-active' : ''; ?>"><?php esc_html_e('Hire us', 'vpc'); ?></a>
				<a href="<?php echo esc_url(admin_url('edit.php?post_type=vpc-config&page=vpc-getting-started&section=vpc-about-orion')); ?>" class="nav-tab <?php echo 'vpc-about-orion' === $active_tab ? 'nav-tab-active' : ''; ?>"><?php esc_html_e('About us', 'vpc'); ?></a>
				<a href="<?php echo esc_url(admin_url('edit.php?post_type=vpc-config&page=vpc-getting-started&section=vpc-support')); ?>" class="nav-tab <?php echo 'vpc-support' === $active_tab ? 'nav-tab-active' : ''; ?>"><?php esc_html_e('Support', 'vpc'); ?></a>
			</nav>
			<div class="vpc-getting-started addons-featured">
				<?php
				if ('vpc-getting-started' === $active_tab) {
				?>
					<div class="vpc-addons">
						<div class="vpc-getting-started-title">
							<h3>Add more features to your product configurator to create the sales machine</h3>
						</div>
						<div class="addons-banner-block-items">
							<div class="addons-banner-block-item vpc-addon">
								<div class="addons-banner-block-item-icon">
									<img class="addons-img" src="<?php echo esc_attr(VPC_URL); ?>/admin/images/addons/custom_text.svg" alt="Custom Text addon" />
								</div>
								<div class="addons-banner-block-item-content">
									<h3>Custom Text</h3>
									<p>Allows the customer to add a custom text with a custom color and font to the preview area which will be sent with his order.</p>
									<div class="vpc-addons-buttons">
										<a class="addons-button addons-button-solid" href="https://orionorigin.com/docs/visual-products-configurator/custom-text/" target="_blank">
											Documentation
										</a>
										<a class="addons-button addons-button-solid live-preview" href="https://www.orionorigin.com/product/custom-text-add-on/" target="_blank">
											View all add-ons
										</a>
										<!--<a class="addons-button addons-button-solid" href="https://codecanyon.net/item/visual-product-configurator-custom-text-add-on/21098606?s_rank=4?ref=orionorigin" target="_blank">
						$25
					</a>-->
									</div>
								</div>
							</div>

							<div class="addons-banner-block-item vpc-addon">
								<div class="addons-banner-block-item-icon">
									<img src="<?php echo esc_attr(VPC_URL); ?>/admin/images/addons/multiple_view.svg" alt="multi views addon" />
								</div>
								<div class="addons-banner-block-item-content">
									<h3>Multiple Views</h3>
									<p>Allow the customer to see his custom product under multiple views and angles, which are configured by the shop manager.</p>

									<div class="vpc-addons-buttons">
										<a class="addons-button addons-button-solid" href="https://orionorigin.com/docs/visual-products-configurator/multiple-views/" target="_blank">
											Documentation
										</a>
										<a class="addons-button addons-button-solid live-preview" href="https://www.orionorigin.com/product/multiple-views-add-on/" target="_blank">
											View all add-ons
										</a>
										<!--<a class="addons-button addons-button-solid" href="https://codecanyon.net/item/visual-product-configurator-multiple-views-addon/21098558?s_rank=5?ref=orionorigin" target="_blank">
						$28
					</a>-->

									</div>
								</div>
							</div>

							<div class="addons-banner-block-item vpc-addon">
								<div class="addons-banner-block-item-icon">
									<img src="<?php echo esc_attr(VPC_URL); ?>/admin/images/addons/save_for_later.svg" alt="Save for Later addon" />
								</div>
								<div class="addons-banner-block-item-content">
									<h3>Save For Later</h3>
									<p>Gives the users the possibility to save their personalized products for future usage in their account.</p>
									<div class="vpc-addons-buttons">
										<a class="addons-button addons-button-solid" href="https://orionorigin.com/docs/visual-products-configurator/save-for-later/" target="_blank">
											Documentation
										</a>
										<a class="addons-button addons-button-solid live-preview" href="https://www.orionorigin.com/product/save-for-later-add-on/" target="_blank">
											View all add-ons
										</a>
										<!--<a class="addons-button addons-button-solid" href="https://codecanyon.net/item/visual-product-configurator-save-for-later-addon/21098722?s_rank=1?ref=orionorigin" target="_blank">
						$25
					</a>-->
									</div>
								</div>
							</div>

							<div class="addons-banner-block-item vpc-addon">
								<div class="addons-banner-block-item-icon">
									<img src="<?php echo esc_attr(VPC_URL); ?>/admin/images/addons/request_a_quote.svg" alt="Request a quote addon" />
								</div>
								<div class="addons-banner-block-item-content">
									<h3>Request A Quote</h3>
									<p>Allows the customer to request a quote about a customized product and purchase later if needed.</p>
									<div class="vpc-addons-buttons">
										<a class="addons-button addons-button-solid" href="https://orionorigin.com/docs/visual-products-configurator/request-a-quote/" target="_blank">
											Documentation
										</a>
										<a class="addons-button addons-button-solid live-preview" href="https://www.orionorigin.com/product/request-a-quote-add-on/" target="_blank">
											View all add-ons
										</a>
										<!--<a class="addons-button addons-button-solid" href="https://codecanyon.net/item/visual-products-configurator-request-a-quote-addon/21098694?s_rank=2?ref=orionorigin" target="_blank">
						$25
					</a>-->
									</div>
								</div>
							</div>

							<div class="addons-banner-block-item vpc-addon">
								<div class="addons-banner-block-item-icon">
									<img src="<?php echo esc_attr(VPC_URL); ?>/admin/images/addons/upload_image.svg" alt="Upload image addon" />
								</div>
								<div class="addons-banner-block-item-content">
									<h3>Custom Image Upload</h3>
									<p>Allows the customer to upload one or multiple pictures on his custom product which will show up on the preview area.</p>

									<div class="vpc-addons-buttons">
										<a class="addons-button addons-button-solid" href="https://orionorigin.com/docs/visual-products-configurator/custom-image-upload/" target="_blank">
											Documentation
										</a>
										<a class="addons-button addons-button-solid live-preview" href="https://www.orionorigin.com/product/custom-upload-add-on/" target="_blank">
											View all add-ons
										</a>
										<!--<a class="addons-button addons-button-solid" href="https://codecanyon.net/item/visual-product-configurator-upload-image/21098653?s_rank=3?ref=orionorigin" target="_blank">
						$28
					</a>-->
									</div>
								</div>
							</div>
							<div class="addons-banner-block-item vpc-addon">
								<div class="addons-banner-block-item-icon">
									<img src="<?php echo esc_attr(VPC_URL); ?>/admin/images/addons/save_preview.svg" alt="Save preview addon" />
								</div>
								<div class="addons-banner-block-item-content">
									<h3>Save preview</h3>
									<p>Allow your customers to download the flattened image of their designs for use outside the product builder.</p>

									<div class="vpc-addons-buttons">
										<a class="addons-button addons-button-solid" href="https://orionorigin.com/docs/visual-products-configurator/save-preview/" target="_blank">
											Documentation
										</a>
										<a class="addons-button addons-button-solid live-preview" href="https://www.orionorigin.com/product/visual-product-configurator-save-configuration-image-add-on/" target="_blank">
											View all add-ons
										</a>
										<!--<a class="addons-button addons-button-solid" href="https://codecanyon.net/item/visual-product-configurator-save-preview-addon/21881361?s_rank=1" target="_blank">
						$25
					</a>-->
									</div>
								</div>
							</div>
							<div class="addons-banner-block-item vpc-addon">
								<div class="addons-banner-block-item-icon">
									<img src="<?php echo esc_attr(VPC_URL); ?>/admin/images/addons/form_builder.svg" alt="Form builder addon" />
								</div>
								<div class="addons-banner-block-item-content">
									<h3>Form Builder</h3>
									<p>A form builder designed to work as add-on for ORION extensions only, not as an independant form builder plugin.</p>

									<div class="vpc-addons-buttons">
										<a class="addons-button addons-button-solid" href="https://orionorigin.com/docs/visual-products-configurator/form-builder/" target="_blank">
											Documentation
										</a>
										<a class="addons-button addons-button-solid live-preview" href="https://www.orionorigin.com/product/form-builder-add-on/" target="_blank">
											View all add-ons
										</a>
										<!--<a class="addons-button addons-button-solid" href="https://codecanyon.net/item/visual-product-configurator-form-builder-addon/21872047?s_rank=1" target="_blank">
						$28
					</a>-->
									</div>
								</div>
							</div>
							<div class="addons-banner-block-item vpc-addon">
								<div class="addons-banner-block-item-icon">
									<img src="<?php echo esc_attr(VPC_URL); ?>/admin/images/addons/social_sharing.svg" alt="Social Share addon" />
								</div>
								<div class="addons-banner-block-item-content">
									<h3>Social Share</h3>
									<p>Allows your customers to share their configured products to facebook; twitter, pinterest, google,whatsapp and by mail.</p>

									<div class="vpc-addons-buttons">
										<a class="addons-button addons-button-solid" href="https://orionorigin.com/docs/visual-products-configurator/social-share/" target="_blank">
											Documentation
										</a>
										<a class="addons-button addons-button-solid live-preview" href="https://www.orionorigin.com/product/social-share-add-on/" target="_blank">
											View all add-ons
										</a>
										<!--<a class="addons-button addons-button-solid" href="https://codecanyon.net/item/visual-product-configurator-social-sharing-addon/22094775?s_rank=1">
						$25
					</a>-->
									</div>
								</div>
							</div>
						</div>
					</div>
					<br><br>

					<div class="vpc-addons">
						<div class="vpc-getting-started-title">
							<h3>Make your configurator more beautiful than ever using new layouts</h3>
						</div>

						<div class="addons-banner-block-items">
							<div class="addons-banner-block-item vpc-addon">
								<div class="addons-banner-block-item-icon">
									<img class="addons-img" src="<?php echo esc_attr(VPC_URL); ?>/admin/images/addons/lom-nava.svg" alt="Lom-nava skin" />
								</div>
								<div class="addons-banner-block-item-content">
									<h3>Lom-Nava Skin</h3>
									<p>A beautiful mutliple steps skin that will instantly enhance the look and feel of your configurator.</p>
									<div class="vpc-addons-buttons">
										<a class="addons-button addons-button-solid" href="https://orionorigin.com/docs/visual-products-configurator/skins/" target="_blank">
											Documentation
										</a>
										<a class="addons-button addons-button-solid live-preview" href="https://www.orionorigin.com/product/lom-nava-skin/" target="_blank">
											View all add-ons
										</a>
										<!--<a class="addons-button addons-button-solid" href="https://codecanyon.net/item/lom-nava-skin-for-visual-product-configurator/21124537?s_rank=3" target="_blank">
						$25
					</a>-->
									</div>
								</div>
							</div>
							<div class="addons-banner-block-item vpc-addon">
								<div class="addons-banner-block-item-icon">
									<img class="addons-img" src="<?php echo esc_attr(VPC_URL); ?>/admin/images/addons/ouando.svg" alt="Ouando skin" />
								</div>
								<div class="addons-banner-block-item-content">
									<h3>Ouando Skin</h3>
									<p>A beautiful slideshows skin that will instantly reveal and complete the look and feel of your configurator.</p>
									<div class="vpc-addons-buttons">
										<a class="addons-button addons-button-solid" href="https://orionorigin.com/docs/visual-products-configurator/skins/" target="_blank">
											Documentation
										</a>
										<a class="addons-button addons-button-solid live-preview" href="https://www.orionorigin.com/product/ouando-skin/" target="_blank">
											View all add-ons
										</a>
										<!--<a class="addons-button addons-button-solid" href="https://codecanyon.net/user/orionorigin/portfolio" target="_blank">
						$28
					</a>-->
									</div>
								</div>
							</div>
							<div class="addons-banner-block-item vpc-addon">
								<div class="addons-banner-block-item-icon">
									<img class="addons-img" src="<?php echo esc_attr(VPC_URL); ?>/admin/images/addons/modern.svg" alt="Modern skin" />
								</div>
								<div class="addons-banner-block-item-content">
									<h3>Modern Skin</h3>
									<p>The new default skin that will instantly reveal and complete the look and feel of your configurator.</p>
									<div class="vpc-addons-buttons">
										<a class="addons-button addons-button-solid" href="https://orionorigin.com/docs/visual-products-configurator/skins/" target="_blank">
											Documentation
										</a>
										<a class="addons-button addons-button-solid live-preview" href="https://www.orionorigin.com/product/modern-skin/" target="_blank">
											View all add-ons
										</a>
										<!--<a class="addons-button addons-button-solid" href="https://codecanyon.net/user/orionorigin/portfolio" target="_blank">
						$28
					</a>-->
									</div>
								</div>
							</div>
						</div>
					</div>

				<?php
				}

				if ('vpc-tutorials' === $active_tab) {
				?>
					<div class="vpc-tutorials">
						<div class="postbox" id="youtube-video-container">
							<div class="videos_youtube">
								<!--<iframe src="https://www.youtube.com/embed/2auCs0EBqjE?list=PLC9GLMXokPgXW3mYmXYJc-QstNGgF173d" frameborder="0" allowfullscreen></iframe>-->
								<iframe width="1440" height="480" src="https://www.youtube.com/embed/kvq9yD2IKX0" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>


							</div>
						</div>
					</div>
				<?php
				}

				if ('vpc-about-orion' === $active_tab) {
				?>
					<div>
						<h3>Our other plugins</h3>
					</div>

					<div class="vpc-about-us pubs">
						<div class="pub-plugin vpc-gs-half vpc-block">
							<div class="vpc-addon-section-title-container vpc-addon-section-description">
								<h2 class="vpc-addon-section-title"><?php esc_html_e('Woocommerce Product Designer', 'vpc'); ?></h2>
								<p class="vpc-addon-section-subtitle">
									<?php esc_html_e('A powerful web to print solution which helps your customers design or customize logos, shirts, business cards and any prints before the order.', 'vpc'); ?>
								</p>

								<a class="button" href="https://designersuiteforwp.com/products/woocommerce-product-designer/" target="_blank"><?php esc_html_e('From: $61', 'vpc'); ?></a>
							</div>
						</div>

						<div class="pub-plugin vpc-gs-half wad-block">
							<div class="vpc-addon-section-title-container vpc-addon-section-description">
								<h2 class="vpc-addon-section-title"><?php esc_html_e('Conditional Discounts for WooCommerce', 'vpc'); ?></h2>
								<p class="vpc-addon-section-subtitle">
									<?php
									esc_html_e(
										'Conditional Discounts for WooCommerce is a groundbreaking extension <br> that helps you manage bulk
						or wholesale pricing, customers roles or groups based offers, or....',
										'vpc'
									);
									?>
								</p>

								<a class="button" href="https://discountsuiteforwp.com/" target="_blank"><?php esc_html_e('From: $60', 'vpc'); ?></a>
							</div>
						</div>

					</div>

					<div class="clearfix"></div>
					<br><br><br>

					<div class="vpc-about-us pubs">
						<div class="pub-plugin vpc-gs-half wpd-block" style="background:url('<?php echo esc_url(VPC_URL); ?>/admin/images/Kandi.png')">
							<div class="vpc-addon-section-title-container vpc-addon-section-description">
								<a class="button" href="https://designersuiteforwp.com/kandi-custom-phone-case-designer/features/" target="_blank"><?php esc_html_e('From: $99', 'vpc'); ?></a>
							</div>
						</div>
						<div class="pub-plugin vpc-gs-half wpd-block" style="background:url('<?php echo esc_url(VPC_URL); ?>/admin/images/Nati.png')">
							<div class="vpc-addon-section-title-container vpc-addon-section-description">
								<a class="button" href="https://designersuiteforwp.com/nati-custom-lettering-designer/features/" target="_blank"><?php esc_html_e('From: $99', 'vpc'); ?></a>
							</div>
						</div>
					</div>

					<div class="clearfix"></div>
					<br><br><br>

					<div class="vpc-about-us pubs">
						<div class="pub-plugin vpc-gs-half wpd-block" style="background:url('<?php echo esc_attr(VPC_URL); ?>/admin/images/Ouidah.png')">
							<div class="vpc-addon-section-title-container vpc-addon-section-description">
								<a class="button" href="https://designersuiteforwp.com/ouidah-woocommerce-product-designer/features/" target="_blank"><?php esc_html_e('From: $61', 'vpc'); ?></a>
							</div>
						</div>
						<div class="pub-plugin vpc-gs-half wpd-block" style="background:url('<?php echo esc_attr(VPC_URL); ?>/admin/images/Seme.png')">
							<div class="vpc-addon-section-title-container vpc-addon-section-description">
								<a class="button" href="https://designersuiteforwp.com/seme-custom-signs-designer/features/" target="_blank"><?php esc_html_e('From: $99', 'vpc'); ?></a>
							</div>
						</div>
					</div>

					<div class="clearfix"></div>
					<br><br><br>

				<?php
				}
				if ('vpc-hire-us' === $active_tab) {
				?>
					<div class="vpc-getting-started-title">
						<h4>You will find some examples of how Visual Product Configurator may be used <a href="https://demos.configuratorsuiteforwp.com/">here</a> . <br>Note, it does not cover 100% of functionality, so feel free to contact us for more details if necessary.</h4>
					</div>
					<div>
						<iframe src="https://app.hellobonsai.com/f/fccd611d221a027?embed=true&hide_header=true" frameborder="0" width="100%" height="100%" style="border: none; min-height: 600px"></iframe>
					</div>
				<?php
				}
				if ('vpc-support' === $active_tab) {
				?>
					<div class="vpc-getting-started-title">
						<h4>Can’t seem to solve the issue using our detailed <a href="https://orionorigin.com/docs/visual-products-configurator/">documentation</a> ? Let us help you. : <a href="https://www.orionorigin.com/contact/">here</a>
					</div>
				<?php
				}
				?>

				<!---->
				<div class="rating-block">
					<a href="https://wordpress.org/support/plugin/visual-products-configurator-for-woocommerce/reviews/#new-post">
						<span class="rating">
							<?php esc_html_e('If you like ', 'vpc'); ?><span><?php esc_html_e('Visual Product Configurator', 'vpc'); ?></span> <?php esc_html_e('please leave us a', 'vpc'); ?> <img src="<?php echo esc_attr(VPC_URL); ?>/admin/images/rating.png" alt='Vpc rating image'> <?php esc_html_e('rating. A huge thanks in advance!', 'vpc'); ?>
						</span>
					</a>
				</div>

			</div>
			<!--End first container-->

		</div>
		<!--End global getting-started-page container-->

		<?php
	}

	/**
	 * Redirects the plugin to the about page after the activation
	 */
	public function vpc_redirect()
	{
		if (get_option('vpc_do_activation_redirect', false)) {
			delete_option('vpc_do_activation_redirect');
			wp_safe_redirect(admin_url('edit.php?post_type=vpc-config&page=vpc-getting-started'));
		}
	}

	/**
	 *  Checks suscribe email.
	 */
	public function vpc_subscribe()
	{
		$email = $_POST['email']; // phpcs:ignore

		if (preg_match('#^[\w.-]+@[\w.-]+\.[a-z]{2,6}$#i', $email)) {
			$url      = 'https://orionorigin.com/service/osubscribe/v1/subscribe/?email=' . $email;
			$args     = array('timeout' => 60);
			$response = wp_remote_get($url, $args);

			if (is_wp_error($response)) {
				$error_message = $response->get_error_message();
				echo esc_html("Something went wrong: $error_message");
				die();
			}
			if (isset($response['body'])) {
				$answer = $response['body'];
				if ('true' === $answer) {
					update_option('o-vpc-subscribe', 'subscribed');
					echo esc_html($answer);
				} else {
					echo esc_html($answer);
				}

				die();
			}
		} else {
			echo 'Please enter a valid email address';
			die();
		}
	}

	/**
	 * Hide notice
	 */
	public function vpc_hide_notice()
	{
		set_transient('vpc-hide-notice', 'hide', 2 * WEEK_IN_SECONDS);
		echo 'ok';
		die();
	}

	/**
	 * Woocommerce notice
	 */
	public function require_woocommerce_notice()
	{
		if (!class_exists('WooCommerce')) {
		?>
			<div class="notice vpc-notice-error">
				<p><b>Visual Product Configurator: </b><?php esc_html_e('WooCommerce is not installed on your website. You will not be able to use the features of the plugin.', 'vpc'); ?> <a class="button" href="<?php echo esc_url(admin_url()) . 'plugins.php'; ?>"><?php esc_html_e('Go to plugins page', 'vpc'); ?></a></p>

			</div>
			<?php
			return;
		}
	}

	/**
	 * This function displays a notice for each product (vpc and these add-ons) when the product key is not specified in the settings.
	 */
	public function get_inactivated_licenses_notices()
	{
		$licences = vpc_get_vpc_and_all_addons_licenses();
		foreach ($licences as $value) {
			if (empty($value['purchase-code'])) {
			?>
				<div class="notice vpc-notice-error notice-error">
					<p><b>Visual Product Configurator <?php echo !empty($value['name']) ? ' - ' . esc_html($value['name']) : ''; ?>: </b><?php esc_html_e('No licence key found in the settings. Please click', 'vpc'); ?> <a href="<?php esc_attr($value['url']); ?>"><?php esc_html_e('here.', 'vpc'); ?></a> <?php esc_html_e('to define one.', 'vpc'); ?></p>
					<p></p>
				</div>
		<?php
			}
		}
	}

	/**
	 * This function periodically checks the validity of vpc licenses and all active add ons.
	 */
	public function o_verify_validity()
	{
		if (is_admin() && get_transient('vpc-checking') !== 'valid') {
			$site_url = get_site_url();
			$licences = vpc_get_vpc_and_all_addons_licenses();
			foreach ($licences as $key => $value) {
				if (!get_option($key . '-license-key') && isset($value['purchase-code']) && !empty($value['purchase-code'])) {
					$purchase_code = $value['purchase-code'];
					$url           = 'https://orionorigin.com/service/olicenses/v1/checking/?license-key=' . $purchase_code . '&siteurl=' . rawurlencode($site_url);
					$args          = array('timeout' => 60);
					$response      = wp_remote_get($url, $args);
					if (!is_wp_error($response)) {
						if (isset($response['body']) && 403 === intval($response['body'])) {
							delete_option($key . '-license-key');
						}
					}
				} elseif (!get_option($key . '-license-key')) {
					delete_option($key . '-license-key');
				}
			}
		}
		set_transient('vpc-checking', 'valid', 1 * WEEK_IN_SECONDS);
	}

	/**
	 * Runs the new version check and upgrade process
	 *
	 * @return \VPC_Updater
	 */
	public function vpc_get_updater()
	{
		do_action('vpc_before_init_updater');
		require_once VPC_DIR . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'updaters' . DIRECTORY_SEPARATOR . 'class-vpc-updater.php';
		$updater = new VPC_Updater();
		$updater->init();
		require_once VPC_DIR . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'updaters' . DIRECTORY_SEPARATOR . 'class-vpc-updating-manager.php';
		$updater->set_update_manager(new VPC_Updating_Manager(VPC_VERSION, $updater->version_url(), VPC_MAIN_FILE));
		do_action('vpc_after_init_updater');
		return $updater;
	}

	/**
	 * Ignore plugin subscribe notice.
	 */
	public function vpc_plugin_subscribe_notice_ignore()
	{
		global $current_user;

		global $current_user;

		$user_id = $current_user->ID;

		if (isset($_GET['vpc-new-ignore-notice'])) { // phpcs:ignore

			add_user_meta($user_id, 'vpc-new-ignore-notice', 'true', true);
		}
	}

	/**
	 * Show or hide paginate.
	 */
	public function vpc_show_or_hide_paginate()
	{
		if (isset($_POST['paginate'])) { // phpcs:ignore
			if (isset($_COOKIE['o-data'])) {
				$_COOKIE['o-data']['paginate'] = $_POST['paginate']; // phpcs:ignore
				echo 'completed';
			}
		}
		die();
	}

	/**
	 * Ignore plugin subscribe notice.
	 */
	public function get_options_by_component_id()
	{
		$config_id            = $_POST['config_id']; // phpcs:ignore
		$component_id         = $_POST['component_id']; // phpcs:ignore
		$component_name       = $_POST['component_name']; // phpcs:ignore
		$options_names_prefix = str_replace('[component_id]', '', $component_name);
		$vpc_config_meta      = get_post_meta($config_id, 'vpc-config', true);
		$component_options    = $this->get_options_data_by_component_id($vpc_config_meta, $component_id);
		$options_fields       = vpc_get_options_fields($config_id);
		ob_start();
		o_get_repeatable_field_table_rows($options_fields, $component_options);
		$options_table_rows             = ob_get_clean();
		$properly_prefixed_options_rows = str_replace('options[', "$options_names_prefix" . '[options][', $options_table_rows);
		echo wp_kses($properly_prefixed_options_rows, get_allowed_tags());

		die();
	}

	/**
	 * Function to get options datas by component id.
	 *
	 * @param  array  $metas         The metas datas.
	 * @param  string $component_id  The component id.
	 */
	private function get_options_data_by_component_id($metas, $component_id)
	{
		$components = get_proper_value($metas, 'components', array());
		foreach ($components as $component) {
			if ($component_id === $component['component_id']) {
				return $component['options'];
			}
		}
		return false;
	}

	/**
	 * Function to duplicate component.
	 */
	public function duplicate_component()
	{
		$config_id    = $_POST['config_id']; // phpcs:ignore
		$component_id = $_POST['component_id']; // phpcs:ignore
		$metas        = get_post_meta($config_id, 'vpc-config', true);

		$new_component = array();
		foreach ($metas['components'] as $i => $component) {
			if ($component['component_id'] === $component_id) {
				$new_component                 = $component;
				$new_component['component_id'] = uniqid('component-');
				$new_component['cname']        = $component['cname'] . ' copy';
				foreach ($new_component['options'] as $j => $option) {
					$new_component['options'][$j]['option_id'] = uniqid('option-');
				}
				break;
			}
		}

		if (!empty($new_component)) {
			array_push($metas['components'], $new_component);
			update_post_meta($config_id, 'vpc-config', $metas);
		}

		die();
	}

	/**
	 * Get Conditionals rules Page.
	 */
	public function get_conditional_rules_page()
	{
		if (isset($_GET['config-id'])) {  // phpcs:ignore
			$config_id = $_GET['config-id']; // phpcs:ignore
		} else {
			esc_html_e('No configuration ID provided', 'vpc');
		}
		$config = new VPC_Config($config_id);
		$this->save_config_rules($config_id);
		$current_configuration = get_post_meta($config_id, 'vpc-config', true);
		?>
		<script>
			var configurations = <?php echo wp_json_encode($current_configuration); ?>;
		</script>
		<form method="POST" action="" class="mg-top">
			<?php
			$config->pc_active_part = $current_configuration;
			// Localize conditional rules builder datas
			// Todo: get saved rules.
			$wvpc_cl_trigger = array(
				'on_selection'   => __('Is selected', 'vpc'),
				'on_deselection' => __('is deselected', 'vpc'),
			);

			$wvpc_cl_scope = array(
				'option'              => __('Option', 'vpc'),
				'component'           => __('Component', 'vpc'),
				'group_per_component' => __('Group per component', 'vpc'),
				'groups'              => __('All groups', 'vpc'),
			);

			$wvpc_cl_action = array(
				'show'   => __('Show', 'vpc'),
				'hide'   => __('Hide', 'vpc'),
				'select' => __('Select', 'vpc'),
			);

			$wvpc_cl_group_container_tpl = '<div class = "wvpc-rules-group-container">'
				. ' <div>  '
				. '<table class="wvpc-rules-table widefat"><tbody>' . $config->wvpc_set_group_rule_tpl_head() . '{rule-group}</tbody></table> '
				. ' </div> '
				. '{enable-reverse-cb}'
				. '<div class = "remove remove-group"> <a class=" button wvpc-remove-rule">' . __('Remove rule', 'vpc') . '</a></div></div>';

			$wvpc_admin_data = array(
				'wvpc_conditional_rule_container'     => $config->wvpc_set_conditional_rules_container_tpl(),
				'wvpc_conditional_rule_tpl'           => $config->wvpc_get_conditionnal_rule_tpl(),
				'wvpc_conditional_rule_tpl_first_row' => $config->wvpc_get_conditionnal_rule_tpl(true),
				'wvpc_cl_group_container_tpl'         => $wvpc_cl_group_container_tpl,
				// phpcs:ignore // 'wvpc_conditional_rules' => $conditional_rules,
				'wvpc_cl_trigger'                     => $wvpc_cl_trigger,
				'wvpc_cl_scope'                       => $wvpc_cl_scope,
				'wvpc_cl_action'                      => $wvpc_cl_action,
				'current_configuration'               => $current_configuration,
			);
			?>
			<script>
				var wvpc_cond_rules_data = <?php echo wp_json_encode($wvpc_admin_data); ?>;
			</script>

			<div class="wvpc-conditional-rule-wrap">
				<?php
				$wvpc_conditional_rule_container = $config->wvpc_set_conditional_rules_container_tpl();
				echo wp_kses($wvpc_conditional_rule_container, get_allowed_tags());
				?>
			</div>

			<input type="submit" class="button button-primary button-large" value="Save">
		</form>
<?php
	}

	/**
	 * Function to save configuration rules.
	 *
	 * @param  int $config_id  The ID of a configuration post.
	 */
	private function save_config_rules($config_id)
	{
		if (isset($_POST['vpc-config']) && isset($_POST['vpc-config']['conditional_rules'])) { // phpcs:ignore
			$old_metas                      = get_post_meta($config_id, 'vpc-config', true);
			$old_metas['conditional_rules'] = $_POST['vpc-config']['conditional_rules']; // phpcs:ignore
			update_post_meta($config_id, 'vpc-config', $old_metas);
		}
	}

	/**
	 * Function to fix the serialization of postmeta.
	 *
	 * @param  array $postmeta A postmeta datas.
	 * @param  int   $post_id  The ID of a post.
	 * @param  array $post     A post datas.
	 */
	public function vpc_fix_serialized_data($postmeta, $post_id, $post)
	{
		$metas = $postmeta;
		if (isset($metas) && !empty($metas)) {
			foreach ($metas as $key => $meta) {
				if (isset($meta['key']) && 'vpc-config' === $meta['key']) {
					$unserialized = maybe_unserialize($meta['value']);
					if (false === $unserialized) {
						if (!preg_match('/^[aOs]:/', $meta['value'])) {
							return $meta['value'];
						}
						if (@unserialize($meta['value']) !== false) { // phpcs:ignore
							return $meta['value'];
						}
						if (preg_match('/\n\s+/', $meta['value']) === 1) {
							$meta['value'] = preg_replace('/\n\s+/', '|||', $meta['value']);
							$meta['value'] = preg_replace("%\n%", '', $meta['value']);
						} elseif (preg_match('/\n/', $meta['value']) === 1) {
							$meta['value'] = preg_replace("%\n%", '|||', $meta['value']);
						}
						// doublequote exploding.
						$data     = preg_replace('%";%', 'µµµ', $meta['value']);
						$tab      = explode('µµµ', $data);
						$new_data = '';
						foreach ($tab as $line) {
							$new_data .= preg_replace_callback('%\bs:(\d+):"(.*)%', 'vpc_fix_serialized_string_length', $line);
						}
						$postmeta[$key]['value'] = $new_data;
					}
				}
			}
		}
		return $postmeta;
	}

	/**
	 * Function to fix the value of index 'views' in the postmeta array.
	 *
	 * @param  int    $post_id The ID of a post.
	 * @param  string $key     The key of a meta.
	 * @param  string $value   The value of a meta.
	 */

	public function fix_views_structure_after_import($post_id, $key, $value)
	{
		$new_metas = get_post_meta($post_id, 'vpc-config', true);
		$old_metas = get_post_meta($post_id, 'vpc-config', true);
		if (isset($old_metas) && !empty($old_metas)) {
			if (isset($old_metas['views']) && strstr($old_metas['views'], '|||')) {
				$views_array = explode('|||', $old_metas['views']);
				if (isset($views_array) && !empty($views_array)) {
					$views = '';
					foreach ($views_array as $index => $value) {
						if (0 === $index) {
							$views .= $value;
						} elseif ($index > 0) {
							$views .= PHP_EOL . $value;
						}
					}
					$new_metas['views'] = $views;
					update_post_meta($post_id, 'vpc-config', $new_metas);
				}
			}
		}
	}

	/**
	 * Hide custom order item meta.
	 *
	 * @param array $metas Order item meta.
	 */
	public function hide_order_item_meta($metas)
	{
		array_push($metas, 'vpc-is-secondary-product');
		return $metas;
	}

	/**
	 * Add class to hide secondary product
	 *
	 * @param string $class   Class name.
	 * @param type   $item    Order item.
	 * @param type   $order   The order.
	 * @return string
	 */
	public function add_custom_class_in_admin_order($class, $item, $order)
	{
		global $vpc_settings;
		$hide_secondary_product_in_cart = get_proper_value($vpc_settings, 'hide-wc-secondary-product-in-cart', 'Yes');
		if (isset($item['vpc-is-secondary-product']) && 'Yes' === $hide_secondary_product_in_cart) {
			$class .= 'secondary_product';
		}
		return $class;
	}
}
