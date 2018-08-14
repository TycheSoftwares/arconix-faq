<?php
/**
 * Class covers the administrative side of the plugin
 *
 * @since 1.4.0
 */
class Arconix_FAQ_Admin {

    /**
     * The version of this plugin.
     *
     * @since   1.6.0
     * @access  private
     * @var     string      $version    The vurrent version of this plugin.
     */
    private $version;

    /**
     * The directory path to this plugin.
     *
     * @since   1.6.0
     * @access  private
     * @var     string      $dir    The directory path to this plugin
     */
    private $dir;

    /**
     * The url path to this plugin.
     *
     * @since   1.6.0
     * @access  private
     * @var     string      $url    The url path to this plugin
     */
    private $url;


    /**
     * Initialize the class and set its properties.
     *
     * @since   1.4.0
     * @version 1.7.0
     * @param   string      $version    The version of this plugin.
     */
    public function __construct( $version ) {
        
        $this->version = $version;
        $this->dir = trailingslashit( plugin_dir_path( __FILE__ ) );
        $this->url = trailingslashit( plugin_dir_url( __FILE__ ) );

        register_activation_hook( __FILE__,         array( $this, 'activation' ) );
        register_deactivation_hook( __FILE__,       array( $this, 'deactivation' ) );

        add_action( 'init',                         array( $this, 'content_types' ) );
        add_action( 'wp_enqueue_scripts',           array( $this, 'enq_scripts' ) );
        add_action( 'admin_enqueue_scripts',        array( $this, 'enq_admin_scripts' ) );
        add_action( 'manage_posts_custom_column',   array( $this, 'column_action' ) );
        /**
         * We have commented this, because we will create a feed from our site and then we can enable this widget.
         */
        // add_action( 'dashboard_glance_items',       array( $this, 'at_a_glance' ) );
        // add_action( 'wp_dashboard_setup',           array( $this, 'dashboard_widget' ) );

        add_filter( 'manage_faq_posts_columns',     array( $this, 'columns_filter' ) );
        add_filter( 'post_updated_messages',        array( $this, 'messages' ) );
        add_filter( 'cmb_meta_boxes',               array( $this, 'metaboxes' ) );
        add_action( 'add_meta_boxes_faq',           array( $this, 'add_faq_metabox' ) );

        add_shortcode( 'faq',                       array( $this, 'faq_shortcode' ) );

        $faq_is_admin = is_admin();
        if ( true === $faq_is_admin ) {
            add_action( 'admin_init',                    array( $this, 'faq_admin_actions' ) );
            add_filter( 'ts_deativate_plugin_questions', array( $this, 'faq_deactivate_add_questions' ), 10, 1 );

            add_filter( 'ts_tracker_data',               array( $this, 'faq_ts_add_plugin_tracking_data' ), 10, 1 );
            add_filter( 'ts_tracker_opt_out_data',       array( $this, 'faq_get_data_for_opt_out' ), 10, 1 );
            add_action( 'admin_menu',                    array( $this, 'faq_admin_menu' ), 100 );
        }
    }

    /**
     * Runs on plugin activation
     *
     * @since 1.2.0
     */
    function activation() {
        $this->content_types();
        flush_rewrite_rules();
    }

    /**
     * Runs on plugin deactivation
     *
     * @since 1.2.0
     */
    function deactivation() {
        global $wpdb;
        delete_option( 'faq_pro_welcome_page_shown' );
        delete_option( 'faq_pro_welcome_page_shown_time' );
        delete_option( 'faq_allow_tracking' );
        delete_option( 'faq_ts_tracker_last_send' );

        $sql_table_user_meta_cart = "DELETE FROM `" . $wpdb->prefix . "usermeta` WHERE meta_key LIKE  '%faq_%'";
        $wpdb->get_results( $sql_table_user_meta_cart );
        flush_rewrite_rules();
    }

    /**
     * Register the post_type and taxonomy
     *
     * @since 1.2.0
     */
    function content_types() {
        $defaults = $this->defaults();
        register_post_type( $defaults['post_type']['slug'], $defaults['post_type']['args'] );
        register_taxonomy( $defaults['taxonomy']['slug'], $defaults['post_type']['slug'],  $defaults['taxonomy']['args'] );
    }

    /**
     * Define the defaults used in the registration of the post type and taxonomy
     *
     * @since  1.2.0
     * @return array $defaults
     */
    function defaults() {
        // Establishes plugin registration defaults for post type and taxonomy
        $defaults = array(
            'post_type' => array(
                'slug'  => 'faq',
                'args'  => array(
                    'labels' => array(
                        'name'                  => __( 'FAQ',                       'arconix-faq' ),
                        'singular_name'         => __( 'FAQ',                       'arconix-faq' ),
                        'add_new'               => __( 'Add New',                   'arconix-faq' ),
                        'add_new_item'          => __( 'Add New Question',          'arconix-faq' ),
                        'edit'                  => __( 'Edit',                      'arconix-faq' ),
                        'edit_item'             => __( 'Edit Question',             'arconix-faq' ),
                        'new_item'              => __( 'New Question',              'arconix-faq' ),
                        'view'                  => __( 'View FAQ',                  'arconix-faq' ),
                        'view_item'             => __( 'View Question',             'arconix-faq' ),
                        'search_items'          => __( 'Search FAQ',                'arconix-faq' ),
                        'not_found'             => __( 'No FAQs found',             'arconix-faq' ),
                        'not_found_in_trash'    => __( 'No FAQs found in Trash',    'arconix-faq' )
                    ),
                    'public'            => true,
                    'query_var'         => true,
                    'menu_position'     => 20,
                    'menu_icon'         => 'dashicons-editor-help',
                    'has_archive'       => false,
                    'supports'          => array( 'title', 'editor', 'revisions', 'page-attributes' ),
                    'rewrite'           => array( 'with_front' => false )
                )
            ),
            'taxonomy' => array(
                'slug' => 'group',
                'args' => array(
                    'labels' => array(
                        'name'                          => __( 'Groups',                                'arconix-faq' ),
                        'singular_name'                 => __( 'Group',                                 'arconix-faq' ),
                        'search_items'                  => __( 'Search Groups',                         'arconix-faq' ),
                        'popular_items'                 => __( 'Popular Groups',                        'arconix-faq' ),
                        'all_items'                     => __( 'All Groups',                            'arconix-faq' ),
                        'parent_item'                   => null,
                        'parent_item_colon'             => null,
                        'edit_item'                     => __( 'Edit Group' ,                           'arconix-faq' ),
                        'update_item'                   => __( 'Update Group',                          'arconix-faq' ),
                        'add_new_item'                  => __( 'Add New Group',                         'arconix-faq' ),
                        'new_item_name'                 => __( 'New Group Name',                        'arconix-faq' ),
                        'separate_items_with_commas'    => __( 'Separate groups with commas',           'arconix-faq' ),
                        'add_or_remove_items'           => __( 'Add or remove groups',                  'arconix-faq' ),
                        'choose_from_most_used'         => __( 'Choose from the most used groups',      'arconix-faq' ),
                        'menu_name'                     => __( 'Groups',                                'arconix-faq' ),
                    ),
                    'hierarchical'              => false,
                    'show_ui'                   => true,
                    'update_count_callback'     => '_update_post_term_count',
                    'query_var'                 => true,
                    'rewrite'                   => array( 'with_front' => false )
                )
            )
        );

        
        return apply_filters( 'arconix_faq_defaults', $defaults );
    }

    /**
     * Create the post type metabox
     *
     * @since   1.2.0
     * @param   array $meta_boxes
     * @return  array $meta_boxes
     */
    function metaboxes( $meta_boxes ) {
        $meta_boxes['faq_settings'] =
            apply_filters( 'arconix_faq_metabox', array(
                'id'            => 'faq_settings',
                'title'         => __( 'FAQ Setting', 'arconix-faq' ),
                'pages'         => array( 'faq' ),
                'context'       => 'side',
                'priority'      => 'default',
                'show_names'    => false,
                'fields'        => array(
                    array(
                        'id'    => '_acf_rtt',
                        'name'  => __( 'Show Return to Top', 'arconix-faq' ),
                        'desc'  => __( 'Enable a "Return to Top" link at the bottom of this FAQ. The link will return the user to the top of this specific question', 'arconix-faq' ),
                        'type'  => 'checkbox'
                    ),
                    array(
                        'id'    => '_acf_open',
                        'name'  => __( 'Load FAQ Open', 'arconix-faq' ),
                        'desc'  => __( 'Load this FAQ in the open state (default is closed). This is not available when using the accordion configuration', 'arconix-faq' ),
                        'type'  => 'checkbox'
                    )
                )
            )
        );

        return $meta_boxes;
    }

     /**
     * Load Scripts when FAQs are there on page
     *
     * @since   1.7.0
     * @version 1.7.0
     */
     function load_scripts() {
        // Set our JS to load
        wp_enqueue_script( 'arconix-faq-js' );

         /**
         * Load the CSS necessary for the accordion script
         *
         * If you plan on adding a filter to use a different jQuery UI theme, it's highly recommended
         * you reference the $wp_scripts global as well as the $ui variable to make sure we load the CSS
         * for the version of jQuery WordPress loads
         */
        if( apply_filters( 'pre_register_arconix_faq_jqui_css', true ) ) {
            global $wp_scripts;

            // get registered script object for jquery-ui
            $ui = $wp_scripts->query( 'jquery-ui-core' );

            $css_args = apply_filters( 'arconix_jqueryui_css_reg', array(
                'url' =>  $this->url . 'css/themes/smoothness/jquery.ui.theme.css',
                'ver' => $this->version,
                'dep' => false
            ) );

            wp_enqueue_style( 'arconix-faq', $this->url . 'css/arconix-faq.css', false, $this->version );

            wp_enqueue_style( 'jquery-ui-smoothness', $css_args['url'], $css_args['dep'], $css_args['ver'] );
        }

        // Load the CSS - Check the theme directory first, the parent theme (if applicable) second, otherwise load the plugin file
        if( apply_filters( 'pre_register_arconix_faq_css', true ) ) {
            if( file_exists( get_stylesheet_directory() . '/arconix-faq.css' ) )
                wp_enqueue_style( 'arconix-faq', get_stylesheet_directory_uri() . '/arconix-faq.css', false, $this->version );
            elseif( file_exists( get_template_directory() . '/arconix-faq.css' ) )
                wp_enqueue_style( 'arconix-faq', get_template_directory_uri() . '/arconix-faq.css', false, $this->version );
            else
                wp_enqueue_style( 'arconix-faq', $this->url . 'css/arconix-faq.css', false, $this->version );
        }
     }

    /**
     * Display FAQs
     *
     * @since   0.9
     * @version 1.2.0
     * @param   array $atts
     */
    function faq_shortcode( $atts, $content = null ) {
        
        $this->load_scripts();

        // Translate 'all' to nopaging = true ( for backward compatibility)
        if( isset( $atts['showposts'] ) ) {
            if( $atts['showposts'] != "all" and $atts['showposts'] > 0 ) {
                $atts['posts_per_page'] = $atts['showposts'];
            }
        }

        $f = new Arconix_FAQ_Display;

        return $f->loop( $atts );
    }

    /**
     * Register the necessary Javascript and CSS, which can be overridden in a couple different ways.
     *
     * If you would like to bundle the Javacsript or CSS funtionality into another file and prevent either of the plugin's
     * JS or CSS from loading at all, return false to whichever of the pre_register filters you wish to override
     *
     * @example add_filter( 'pre_register_arconix_faq_js', '__return_false' );
     *
     * If you'd like to use your own JS or CSS file, you can copy the arconix-faq.js or arconix-faq.css files to the
     * root of your theme's folder. That will be loaded in place of the plugin's version, which means you can modify
     * it to your heart's content and know the file will be safe when the plugin is updated in the future.
     *
     * @since   1.2.0
     * @version 1.6.0
     */
    function enq_scripts() {
        // Register the javascript - Check the theme directory first, the parent theme (if applicable) second, otherwise load the plugin file
        if ( apply_filters( 'pre_register_arconix_faq_js', true ) ) {
            if( file_exists( get_stylesheet_directory() . '/arconix-faq.js' ) )
                wp_register_script( 'arconix-faq-js', get_stylesheet_directory_uri() . '/arconix-faq.js', array( 'jquery-ui-accordion' ), $this->version );
            elseif( file_exists( get_template_directory() . '/arconix-faq.js' ) )
                wp_register_script( 'arconix-faq-js', get_template_directory_uri() . '/arconix-faq.js', array( 'jquery-ui-accordion' ), $this->version );
            else
                wp_register_script( 'arconix-faq-js', $this->url . 'js/arconix-faq.js', array( 'jquery-ui-accordion' ), $this->version );
        }

        // /**
        //  * Load the CSS necessary for the accordion script
        //  *
        //  * If you plan on adding a filter to use a different jQuery UI theme, it's highly recommended
        //  * you reference the $wp_scripts global as well as the $ui variable to make sure we load the CSS
        //  * for the version of jQuery WordPress loads
        //  */
        // if( apply_filters( 'pre_register_arconix_faq_jqui_css', true ) ) {
        //     global $wp_scripts;

        //     // get registered script object for jquery-ui
        //     $ui = $wp_scripts->query( 'jquery-ui-core' );

        //     $css_args = apply_filters( 'arconix_jqueryui_css_reg', array(
        //         'url' => '//ajax.googleapis.com/ajax/libs/jqueryui/' . $ui->ver . '/themes/smoothness/jquery-ui.min.css',
        //         'ver' => $ui->ver,
        //         'dep' => false
        //     ) );

        //     wp_enqueue_style( 'jquery-ui-smoothness', $css_args['url'], $css_args['dep'], $css_args['ver'] );
        // }

        // // Load the CSS - Check the theme directory first, the parent theme (if applicable) second, otherwise load the plugin file
        // if( apply_filters( 'pre_register_arconix_faq_css', true ) ) {
        //     if( file_exists( get_stylesheet_directory() . '/arconix-faq.css' ) )
        //         wp_enqueue_style( 'arconix-faq', get_stylesheet_directory_uri() . '/arconix-faq.css', false, $this->version );
        //     elseif( file_exists( get_template_directory() . '/arconix-faq.css' ) )
        //         wp_enqueue_style( 'arconix-faq', get_template_directory_uri() . '/arconix-faq.css', false, $this->version );
        //     else
        //         wp_enqueue_style( 'arconix-faq', $this->url . 'css/arconix-faq.css', false, $this->version );
        // }

    }

    /**
     * Includes admin scripts. Use the pre_register filter if you'd like to prevent the file from being loaded
     *
     * @since 1.2.0
     */
    function enq_admin_scripts() {
        if( apply_filters( 'pre_register_arconix_faq_admin_css', true ) )
            wp_enqueue_style( 'arconix-faq-admin', $this->url . 'css/admin.css', false, $this->version );
    }

    /**
     * Change the Post Updated messages
     *
     * @since   0.9
     * @version 1.5.2
     *
     * @global  stdObj  $post
     * @global  int     $post_ID
     * @param   array   $messages
     * @return  array   $messages
     */
    function messages( $messages ) {
        global $post, $post_ID;
        $post_type = get_post_type( $post_ID );

        $obj = get_post_type_object( $post_type );
        $singular = $obj->labels->singular_name;

        $messages[$post_type] = array(
            0  => '', // Unused. Messages start at index 1.
            1  => sprintf( __( $singular . ' updated. <a href="%s">View ' . strtolower( $singular ) . '</a>', 'arconix-faq' ), esc_url( get_permalink( $post_ID ) ) ),
            2  => __( 'Custom field updated.', 'arconix-faq' ),
            3  => __( 'Custom field deleted.', 'arconix-faq' ),
            4  => __( $singular . ' updated.', 'arconix-faq' ),
            5  => isset( $_GET['revision'] ) ? sprintf( __( $singular . ' restored to revision from %s', 'arconix-faq' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
            6  => sprintf( __( $singular . ' published. <a href="%s">View ' . strtolower( $singular ) . '</a>', 'arconix-faq' ), esc_url( get_permalink( $post_ID ) ) ),
            7  => __( 'Page saved.' ),
            8  => sprintf( __( $singular . ' submitted. <a target="_blank" href="%s">Preview ' . strtolower( $singular ) . '</a>', 'arconix-faq' ), esc_url( add_query_arg( 'preview', 'true', get_permalink( $post_ID ) ) ) ),
            9  => sprintf( __( $singular . ' scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview ' . strtolower( $singular ) . '</a>', 'arconix-faq' ), date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink( $post_ID ) ) ),
            10 => sprintf( __( $singular . ' draft updated. <a target="_blank" href="%s">Preview ' . strtolower( $singular ) . '</a>', 'arconix-faq' ), esc_url( add_query_arg( 'preview', 'true', get_permalink( $post_ID ) ) ) ),
        );

        return $messages;
    }

    /**
     * Choose the specific columns we want to display
     *
     * @param   array   $columns
     * @return  string  $columns
     * @since   0.9
     * @version 1.2
     */
    function columns_filter( $columns ) {
        $columns = array(
            "cb"            => '<input type="checkbox" />',
            "title"         => __( 'FAQ Title', 'arconix-faq' ),
            "faq_content"   => __( 'Answer', 'arconix-faq' ),
            'faq_groups'    => __( 'Group', 'arconix-faq' ),
            'faq_shortcode' => __( 'Shortcode', 'arconix-faq' ),
            "date"          => __( 'Date', 'arconix-faq' )
        );

        return $columns;
    }

    /**
     * Filter the data that shows up in the columns we defined above
     *
     * @global  stdObj $post
     * @param   array $column
     * @since   0.9
     * @version 1.1
     */
    function column_action( $column ) {
        global $post;

        switch( $column ) {
            case "faq_content":
                the_excerpt();
                break;
            case "faq_groups":
            
                echo get_the_term_list( $post->ID, 'group', '', ', ', '' );
                break;
            case "faq_shortcode":
                printf( '[faq p=%d]', get_the_ID() );
                break;
            default:
                break;
        }
    }

    /**
     * Add the Post type to the "At a Glance" Dashboard Widget
     *
     * @since   1.0
     * @version 1.4.0
     */
    function at_a_glance() {
        $glancer = new Gamajo_Dashboard_Glancer;
        $glancer->add( 'faq' );
    }

    /**
     * Adds a widget to the dashboard.
     *
     * @since   1.0.3
     * @version 1.2.0
     */
    function dashboard_widget() {
        if( apply_filters( 'pre_register_arconix_faq_dashboard_widget', true ) and current_user_can( 'manage_options' ) )
            wp_add_dashboard_widget( 'ac-faq', 'Arconix FAQ', array( $this, 'dashboard_widget_output' ) );
    }

    /**
     * Add a widget to the dashboard
     *
     * @since   1.0
     * @version 1.2.0
     */
    function dashboard_widget_output() {
        echo '<div class="rss-widget">';

        wp_widget_rss_output( array(
            'url'           => 'http://arconixpc.com/tag/arconix-faq/feed', // feed url
            'title'         => 'Arconix FAQ', // feed title
            'items'         => 3, // how many posts to show
            'show_summary'  => 1, // display excerpt
            'show_author'   => 0, // display author
            'show_date'     => 1 // display post date
        ) );

        ?>  <div class="acf-widget-bottom"><ul>;
                <li><a href="http://arcnx.co/afwiki" class="af-docs"><?php _e( 'Documentation', 'arconix-faq'); ?></a></li>
                <li><a href="http://arcnx.co/afhelp" class="af-help"><?php _e( 'Support Forum', 'arconix-faq'); ?></a></li>
                <li><a href="http://arcnx.co/aftrello" class="af-dev"><?php _e( 'Dev Board', 'arconix-faq'); ?></a></li>
                <li><a href="http://arcnx.co/afsource" class="af-source"><?php _e( 'Source Code', 'arconix-faq'); ?></a></li>
            </ul></div></div>
        <?php
    }

    /**
     * Adds a metabox to the FAQ creation screen.
     *
     * This metabox shows the shortcode with the post_id for users to display
     * just that faq on a post, page or other applicable location
     *
     * @since   1.6.0
     */
    public function add_faq_metabox() {
        add_meta_box( 'arconix-faq-box', __( 'FAQ Shortcode', 'arconix-faq' ), array( $this, 'faq_box' ), 'faq', 'side' );
    }

    /**
     * Output for the faq shortcode metabox. Creates a readonly inputbox that outputs the faq shortcode
     * plus the $post_id
     *
     * @since   1.6.0
     *
     * @global  int     $post_ID    ID of the current post
     */
    public function faq_box() {
        global $post_ID;
        ?>
        <p class="howto">
            <?php _e( 'To display this question, copy the code below and paste it into your post, page, text widget or other content area.', 'arconix-faq' ); ?>
        </p>
        <p><input type="text" value="[faq p=<?php echo $post_ID; ?>]" readonly="readonly" class="widefat wp-ui-text-highlight code"></p>
        <?php
    }

    /**
     * It will add the deactivation question for the plugin.
     */
    public function faq_deactivate_add_questions ( $faq_deactivate_questions ) {
        $faq_deactivate_questions = array(
            0 => array(
                'id'                => 4, 
                'text'              => __( "I'm not able to link my one FAQ to another FAQ.", "arconix-faq" ),
                'input_type'        => '',
                'input_placeholder' => ''
                ), 
            1 =>  array(
                'id'                => 5,
                'text'              => __( "The styling of the plugin does not work with my theme.", "arconix-faq" ),
                'input_type'        => 'textfield',
                'input_placeholder' => 'Which Theme?'
            ),
            2 => array(
                'id'                => 6,
                'text'              => __( "The plugin is not compatible with another plugin.", "arconix-faq" ),
                'input_type'        => 'textfield',
                'input_placeholder' => 'Which plugin?'
            ),
            3 => array(
                'id'                => 7,
                'text'              => __( "This plugin is not useful to me.", "arconix-faq" ),
                'input_type'        => '',
                'input_placeholder' => ''
            )

        );
        return $faq_deactivate_questions;
    }

    public static function faq_get_group () {

        global $wpdb;
        $faq_query = "SELECT ID FROM `{$wpdb->prefix}posts` WHERE post_type='faq' AND post_status = 'publish'";
        $faq_posts = $wpdb->get_results($faq_query);

        $faq_group_array = array();
        foreach ($faq_posts as $faq_posts_key => $faq_posts_value ) {
            $faq_id = $faq_posts_value->ID;
            
            $faq_group = wp_get_object_terms( (int)$faq_id, 'group' );
            if ( count ($faq_group) > 0 ) {
                foreach ( $faq_group as $faq_group_key => $faq_group_value ){
                    $faq_group_array [ $faq_id ] [] = $faq_group_value->name;
                }
            }
        }
          
        return $faq_group_array;
          
    }

    public static function faq_get_total_count () {
        global $wpdb;

        $total_faq_query = "SELECT COUNT('ID') as total_faq FROM `{$wpdb->prefix}posts` WHERE post_type='faq' AND post_status = 'publish'";
        $total_count_result     = $wpdb->get_results($total_faq_query);

        $total_faq = 0 ;
        if ( count ( $total_count_result ) > 0 && isset ( $total_count_result [0] ) ) {
            $total_faq = $total_count_result [0]->total_faq;
        }
        return $total_faq;
    }

    /**
         * Plugin's data to be tracked when Allow option is choosed.
         *
         * @hook ts_tracker_data
         *
         * @param array $data Contains the data to be tracked.
         *
         * @return array Plugin's data to track.
         * 
         */

        public static function faq_ts_add_plugin_tracking_data ( $data ) {
            if ( isset( $_GET[ 'faq_tracker_optin' ] ) && isset( $_GET[ 'faq_tracker_nonce' ] ) && wp_verify_nonce( $_GET[ 'faq_tracker_nonce' ], 'faq_tracker_optin' ) ) {

                $plugin_data[ 'ts_meta_data_table_name' ] = 'ts_tracking_faq_meta_data';
                $plugin_data[ 'ts_plugin_name' ]		  = 'Arconix FAQ';
                /**
                 * Add Plugin data
                 */
                $plugin_data[ 'faq_count' ]               = self::faq_get_total_count();
                $plugin_data[ 'faq_group' ]               = serialize ( self::faq_get_group() );
                $plugin_data[ 'faq_plugin_version' ]      = $this->version;
                
                $plugin_data[ 'faq_allow_tracking' ]      = get_option ( 'faq_allow_tracking' );
                $data[ 'plugin_data' ]                    = $plugin_data;
            }
            return $data;
        }


        /**
         * Tracking data to send when No, thanks. button is clicked.
         *
         * @hook ts_tracker_opt_out_data
         *
         * @param array $params Parameters to pass for tracking data.
         *
         * @return array Data to track when opted out.
         * 
         */
        public static function faq_get_data_for_opt_out ( $params ) {
            $plugin_data[ 'ts_meta_data_table_name']   = 'ts_tracking_faq_meta_data';
            $plugin_data[ 'ts_plugin_name' ]		   = 'Arconix FAQ';
            
            // Store count info
            $params[ 'plugin_data' ]  				   = $plugin_data;
            
            return $params;
        }

        /**
         * Admin actions
         */
        public function faq_admin_actions() {
            
            /**
             * We need to store the plugin version in DB, so we can show the welcome page and other contents.
             */
            $faq_version_in_db = get_option( 'faq_version' ); 
            if ( $faq_version_in_db != $this->version ){
                update_option( 'faq_version', $this->version );
            }
            
            /**
             * It is used when plugin is activated. It will add the update time and then show the notices.
             */
            do_action ( 'faq_activate' );

        }

        /**
         * Add Faq and support page
         */
        public function faq_admin_menu () {
            do_action ('faq_add_submenu');
        }

}