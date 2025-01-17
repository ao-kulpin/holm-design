<?php
/**
 * Duplicate vpc-configuration functionality
 *
 * @category Admin
 * @author   Orion <help@orionorigin.com>
 */

if (! defined('ABSPATH') ) {
    exit; // Exit if accessed directly.
}

if (! class_exists('VPC_Duplicate') ) :

    /**
     * WC_Admin_Duplicate_wpc-template Class
     */
    class VPC_Duplicate
    {

        /**
         * Constructor
         */
        public function __construct()
        {
            add_action('admin_action_duplicate_vpc-config', array( $this, 'duplicate_vpc_config_action' ));
            add_filter('post_row_actions', array( $this, 'dupe_link' ), 10, 2);
            add_filter('page_row_actions', array( $this, 'dupe_link' ), 10, 2);
            add_action('post_submitbox_start', array( $this, 'dupe_button' ));
        }

        /**
         * Show the "Duplicate" link in admin configurations list
         *
         * @param  array   $actions The actions.
         * @param  WP_Post $post    Post object.
         * @return array
         */
        public function dupe_link( $actions, $post )
        {
         if ( ! current_user_can( apply_filters( 'woocommerce_duplicate_vpc-config_capability', 'manage_woocommerce' ) ) ) { //phpcs:ignore
                return $actions;
            }

            if ('vpc-config' !== $post->post_type ) {
                return $actions;
            }

            $actions['duplicate'] = '<a href="' . wp_nonce_url(admin_url('edit.php?post_type=vpc-config&action=duplicate_vpc-config&amp;post=' . $post->ID), 'woocommerce-duplicate-vpc-config_' . $post->ID) . '" title="' . __('Make a duplicate from this configuration', 'woocommerce')
            . '" rel="permalink">' . __('Duplicate', 'vpc') . '</a>';

            return $actions;
        }

        /**
         * Show the dupe vpc-config link in admin
         */
        public function dupe_button()
        {
            global $post;

         if ( ! current_user_can( apply_filters( 'woocommerce_duplicate_vpc-config_capability', 'manage_woocommerce' ) ) ) { //phpcs:ignore
                return;
            }

            if (! is_object($post) ) {
                return;
            }

            if ('vpc-config' !== $post->post_type ) {
                return;
            }

         if ( isset( $_GET['post'] ) ) { //phpcs:ignore
                $notify_url = wp_nonce_url( admin_url( 'edit.php?post_type=vpc-config&action=duplicate_vpc-config&post=' . absint( $_GET['post'] ) ), 'woocommerce-duplicate-vpc-config_' . $_GET['post'] ); //phpcs:ignore
                ?>
        <div id="duplicate-action"><a class="submitduplicate duplication" href="<?php echo esc_url($notify_url); ?>"><?php esc_html_e('Copy to a new draft', 'vpc'); ?></a></div>
                <?php
            }
        }

        /**
         * Duplicate vpc-config action.
         */
        public function duplicate_vpc_config_action()
        {

            if (empty($_REQUEST['post']) ) {
                wp_die(esc_html__('No configuration to duplicate has been supplied!', 'vpc'));
            }

            // Get the original page.
            $id = isset($_REQUEST['post']) ? absint($_REQUEST['post']) : '';

            check_admin_referer('woocommerce-duplicate-vpc-config_' . $id);

            $post = $this->get_vpc_config_to_duplicate($id);

            // Copy the page and insert it.
            if (! empty($post) ) {
                $new_id = $this->duplicate_vpc_config($post);

                // If you have written a plugin which uses non-WP database tables to save
                // information about a page you can hook this action to dupe that data.
             do_action( 'woocommerce_duplicate_vpc-config', $new_id, $post );  // phpcs:ignore

                // Redirect to the edit screen for the new draft page.
                wp_safe_redirect(admin_url('post.php?action=edit&post=' . $new_id));
                exit;
            } else {
                wp_die(esc_html__('Configuration creation failed, could not find original configuration:', 'vpc') . ' ' . esc_html($id));
            }
        }

        /**
         * Function to create the duplicate of the vpc-config.
         *
         * @param  mixed  $post        The post.
         * @param  int    $parent      The post parent (default: 0).
         * @param  string $post_status The post statut (default: '').
         * @return int
         */
        public function duplicate_vpc_config( $post, $parent = 0, $post_status = '' )
        {
            global $wpdb;

            $new_post_author   = wp_get_current_user();
            $new_post_date     = current_time('mysql');
            $new_post_date_gmt = get_gmt_from_date($new_post_date);

            if ($parent > 0 ) {
                $post_parent = $parent;
                $post_status = $post_status ? $post_status : 'publish';
                $suffix      = '';
            } else {
                $post_parent = $post->post_parent;
                $post_status = $post_status ? $post_status : 'draft';
                $suffix      = ' ' . __('(Copy)', 'woocommerce');
            }

            // Insert the new configuration in the post table.
         $wpdb->insert( //phpcs:ignore
             $wpdb->posts,
             array(
             'post_author'           => $new_post_author->ID,
             'post_date'             => $new_post_date,
             'post_date_gmt'         => $new_post_date_gmt,
             'post_content'          => $post->post_content,
             'post_content_filtered' => $post->post_content_filtered,
             'post_title'            => $post->post_title . $suffix,
             'post_excerpt'          => $post->post_excerpt,
             'post_status'           => $post_status,
             'post_type'             => $post->post_type,
             'comment_status'        => $post->comment_status,
             'ping_status'           => $post->ping_status,
             'post_password'         => $post->post_password,
             'to_ping'               => $post->to_ping,
             'pinged'                => $post->pinged,
             'post_modified'         => $new_post_date,
             'post_modified_gmt'     => $new_post_date_gmt,
             'post_parent'           => $post_parent,
             'menu_order'            => $post->menu_order,
             'post_mime_type'        => $post->post_mime_type,
             )
            );

            $new_post_id = $wpdb->insert_id;

            // Copy the taxonomies.
            $this->duplicate_vpc_config_taxonomies($post->ID, $new_post_id, $post->post_type);

            // Copy the meta information.
            $this->duplicate_vpc_config_meta($post->ID, $new_post_id);

            return $new_post_id;
        }

        /**
         * Get a vpc-config from the database to duplicate
         *
         * @param  mixed $id The configuration id.
         * @return WP_Post|bool
         * @todo   Returning false? Need to check for it in...
         * @see    duplicate_vpc-config
         */
        private function get_vpc_config_to_duplicate( $id )
        {
            global $wpdb;

            $id = absint($id);

            if (! $id ) {
                return false;
            }
         $post = $wpdb->get_results( "SELECT * FROM $wpdb->posts WHERE ID=$id" ); //phpcs:ignore

            if (isset($post->post_type) && 'revision' === $post->post_type ) {
                $id   = $post->post_parent;
             $post = $wpdb->get_results( "SELECT * FROM $wpdb->posts WHERE ID=$id" ); //phpcs:ignore
            }

            return $post[0];
        }

        /**
         * Copy the taxonomies of a post to another post
         *
         * @param mixed $id        The configuration old id.
         * @param mixed $new_id    The configuration new id.
         * @param mixed $post_type The post type.
         */
        private function duplicate_vpc_config_taxonomies( $id, $new_id, $post_type )
        {

            $taxonomies = get_object_taxonomies($post_type);

            foreach ( $taxonomies as $taxonomy ) {

                $post_terms       = wp_get_object_terms($id, $taxonomy);
                $post_terms_count = count($post_terms);

                for ( $i = 0; $i < $post_terms_count; $i ++ ) {
                    wp_set_object_terms($new_id, $post_terms[ $i ]->slug, $taxonomy, true);
                }
            }
        }

        /**
         * Copy the meta information of a post to another post
         *
         * @param mixed $id     The configuration old id.
         * @param mixed $new_id The configuration new id.
         */
        private function duplicate_vpc_config_meta( $id, $new_id )
        {
            $config = get_post_meta($id, 'vpc-config', true);
            update_post_meta($new_id, 'vpc-config', $config);
        }

    }

endif;

return new VPC_Duplicate();
