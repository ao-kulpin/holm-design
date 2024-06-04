<?php
/**
 * Fired during plugin updating
 *
 * @link  http://www.orionorigin.com
 * @since 1.0.0
 *
 * @package    Vpc
 * @subpackage Vpc/includes/updaters
 */

/**
 * Check plugin update.
 */
class VPC_Updater
{
    /**
     * Plugin version url.
     *
     * @var $version_url
     */
    protected $version_url = 'https://orionorigin.com/static/vpc/vpc-updater.xml';
    /**
     * Plugin name.
     *
     * @var $title
     */
    public $title = ORION_PLUGIN_NAME;
    /**
     * Auto update satut.
     *
     * @var $auto_updater
     */
    protected $auto_updater = false;
    /**
     * Upgrade manager satut.
     *
     * @var $upgrade_manager
     */
    protected $upgrade_manager = false;
    /**
     * Plugin frame.
     *
     * @var $version_url
     */
    protected $iframe = false;

    /**
     * Add plugin upgrade hook.
     */
    public function init()
    {
        add_filter('upgrader_package_options', array( $this, 'upgrade_package' ));
        add_filter('upgrader_pre_download', array( $this, 'upgrade_filter' ), 10, 4);
        add_action('upgrader_process_complete', array( $this, 'remove_temporary_dir' ));
    }

    /**
     * Setter for manager updater.
     *
     * @param VPC_Updating_Manager $updater This Class name.
     */
    public function set_update_manager( VPC_Updating_Manager $updater )
    {
        $this->auto_updater = $updater;
    }

    /**
     * Getter for manager updater.
     *
     * @return VPC_Updating_Manager
     */
    public function update_manager()
    {
        return $this->auto_updater;
    }

    /**
     * Get url for version validation
     *
     * @return string
     */
    public function version_url()
    {
        return $this->version_url;
    }

    /**
     * Downloads new VC from Envato marketplace and unzips into temporary directory.
     *
     * @param  bool   $reply   The response.
     * @param  string $package The package file name.
     * @param  object $updater The WP_Upgrader instance.
     * @return mixed|string|WP_Error
     */
    public function upgrade_filter( $reply, $package, $updater )
    {
        global $wp_filesystem;
        if (( isset($updater->skin->plugin) && VPC_MAIN_FILE === $updater->skin->plugin ) 
            || ( isset($updater->skin->plugin_info) && htmlspecialchars_decode($updater->skin->plugin_info['Name']) === $this->title )
        ) {
            $updater->strings['download_from_servers'] = __('Downloading package from ORION servers...', 'vpc');
            $updater->skin->feedback('download_from_servers');
            $package_filename = 'Visual-products-configurator.zip';
            $res              = $updater->fs_connect(array( WP_CONTENT_DIR ));

            if (! $res ) {
                return new WP_Error('no_credentials', __("Error! Can't connect to filesystem", 'vpc'));
            }
            $options = get_option('vpc-options');
            if (isset($options['purchase-code']) && '' !== $options['purchase-code'] ) {
                $license_key = $options['purchase-code'];
            } else {
                return new WP_Error('no_credentials', __('A license key is required to receive automatic updates', 'vpc') . ' (<a href="https://orionorigin.com/guide/how-to-add-my-license/" target="_blank">' . __('Learn more', 'vpc') . '</a>). ' . __('Please visit', 'vpc') . ' <a href="https://orionorigin.com/my-account/orders/" target="blank">' . __('your account', 'vpc') . '</a> ' . __('to retrieve it.', 'vpc') . ' <a href="' . admin_url('admin.php?page=vpc-manage-settings') . ' target="_blank">Settings</a> ' . __('to activate your Visual Products Configurator.', 'vpc'));
            }

            $args        = array( 'timeout' => 600 );
            $site_url    = get_site_url();
            $plugin_name = ORION_PLUGIN_NAME;
            $url         = 'https://orionorigin.com/service/olicenses/v1/checking/?license-key=' . rawurlencode($license_key) . '&siteurl=' . rawurlencode($site_url) . '&name=' . rawurlencode($plugin_name);
            $response    = wp_remote_get($url, $args);
            if (! is_wp_error($response) ) {
                if (isset($response['body']) && intval(200 === intval($response['body'])) ) {
                    $json = wp_remote_get($this->download_url($this->title), $args);
                    if (is_wp_error($json) ) {
                        return $json->get_error_message();
                    }
                    if (isset($json['body']) ) {
                        $answer = $json['body'];
                    }

                    $result = array();

                    if (is_array(json_decode($answer, true)) ) {
                        $result = json_decode($answer, true);
                    } else {
                        return new WP_Error('no_file', __('Error! No file found. Please contact the plugin owners.', 'vpc'));
                    }

                    if (! isset($result['download_url']) ) {
                        return new WP_Error('no_file', __('Error! No file found. Please contact the plugin owners.', 'vpc'));
                    }

                    $download_file = download_url($result['download_url']);
                    if (is_wp_error($download_file) ) {
                        return $download_file;
                    }
                    $uploads_dir_obj = wp_upload_dir();
                    $upgrade_folder  = $uploads_dir_obj['basedir'] . '/vpc-and-addons/vpc_package';
                    if (! is_dir($upgrade_folder) ) {
                        mkdir($upgrade_folder, 0777, true);
                    }
                    // We rename the tmp file to a zip file.
                    $new_zipname = str_replace('.tmp', '.zip', $download_file);
                    rename($download_file, $new_zipname);
                    // The upgrade is in the unique directory inside the upgrade folder.
                    $new_version = "$upgrade_folder/$package_filename";
                    $result      = copy($new_zipname, $new_version);
                    if ($result && is_file($new_version) ) {
                        return $new_version;
                    }
                    return new WP_Error('no_credentials', __('Error on unzipping package', 'vpc'));
                } else {
                    return new WP_Error('network_error', __('Wrong license key provided. Please verify and try again.', 'vpc'));
                }
            } else {
                return $response->get_error_message();
            }
        }
        return $reply;
    }
    /**
     * Filters the package options before running an update.
     *
     * @param array $options Options used by the upgrader.
     */
    public function upgrade_package( $options )
    {
        $vpc = $options['hook_extra'];
        if (isset($vpc['plugin']) && ( 'visual-product-configurator/vpc.php' === $vpc['plugin'] ) ) {
            $vpc_options = get_option('vpc-options');
            if (isset($vpc_options['purchase-code']) && '' !== $vpc_options['purchase-code'] ) {
                $license_key = $vpc_options['purchase-code'];
            } else {
                return new WP_Error('no_credentials', __('A license key is required to receive automatic updates', 'vpc') . ' (<a href="https://orionorigin.com/guide/how-to-add-my-license/" target="_blank">' . __('Learn more', 'vpc') . '</a>). ' . __('Please visit', 'vpc') . ' <a href="https://orionorigin.com/my-account/orders/" target="blank">' . __('your account', 'vpc') . '</a> ' . __('to retrieve it.', 'vpc') . ' <a href="' . admin_url('admin.php?page=vpc-manage-settings') . ' target="_blank">Settings</a> ' . __('to activate your Visual Products Configurator.', 'vpc'));
            }

            $args        = array( 'timeout' => 600 );
            $site_url    = get_site_url();
            $plugin_name = ORION_PLUGIN_NAME;
            $url         = 'https://orionorigin.com/service/olicenses/v1/checking/?license-key=' . rawurlencode($license_key) . '&siteurl=' . rawurlencode($site_url) . '&name=' . rawurlencode($plugin_name);
            $response    = wp_remote_get($url, $args);
            if (! is_wp_error($response) ) {
                if (isset($response['body']) && 200 === intval($response['body']) ) {
                    $json = wp_remote_get($this->download_url($this->title), $args);
                    if (is_wp_error($json) ) {
                        return $json->get_error_message();
                    }
                    if (isset($json['body']) ) {
                        $answer = $json['body'];
                    }

                    $result = array();

                    if (is_array(json_decode($answer, true)) ) {
                        $result = json_decode($answer, true);
                    } else {
                        return new WP_Error('no_file', __('Error! No file found. Please contact the plugin owners.', 'vpc'));
                    }

                    if (! isset($result['download_url']) ) {
                        return new WP_Error('no_file', __('Error! No file found. Please contact the plugin owners.', 'vpc'));
                    }

                    $options['package'] = download_url($result['download_url']);
                    if (is_wp_error($options['package']) ) {
                        return $options;
                    }
                } else {
                    return new WP_Error('network_error', __('Wrong license key provided. Please verify and try again.', 'vpc'));
                }
            } else {
                return $response->get_error_message();
            }
        }
        return $options;
    }

    /**
     * Remove plugin package temporary directory.
     */
    public function remove_temporary_dir()
    {
        global $wp_filesystem;
        if (is_dir($wp_filesystem->wp_content_dir() . 'uploads/vpc-and-addons/vpc_package') ) {
            $wp_filesystem->delete($wp_filesystem->wp_content_dir() . 'uploads/vpc-and-addons/vpc_package', true);
        }
    }

    /**
     * The update download url.
     *
     * @param string $title The plugin namr.
     */
    protected function download_url( $title )
    {
        $site_url      = get_site_url();
        $purchase_code = '';
        $options       = get_option('vpc-options');
        if (isset($options['purchase-code']) && '' !== $options['purchase-code'] ) {
            $purchase_code = $options['purchase-code'];
        }
        return 'https://orionorigin.com/service/oupdater/v1/update/?name=' . rawurlencode($title) . '&purchase-code=' . $purchase_code . '&siteurl=' . rawurlencode($site_url);
    }

}
