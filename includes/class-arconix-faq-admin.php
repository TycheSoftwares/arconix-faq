<?php
class Arconix_FAQ_Admin {
    /**
     * Constructor
     *
     * @since 1.0
     * @version 1.4.0
     */
    function __construct() {
        $this->constants();

        register_activation_hook( __FILE__,         array( $this, 'activation' ) );
        register_deactivation_hook( __FILE__,       array( $this, 'deactivation' ) );

        add_action( 'init',                         array( $this, 'content_types' ) );
        add_action( 'init',                         array( $this, 'init' ), 9999 );
        add_action( 'wp_enqueue_scripts',           array( $this, 'enq_scripts' ) );
        add_action( 'admin_enqueue_scripts',        array( $this, 'enq_admin_scripts' ) );
        add_action( 'manage_posts_custom_column',   array( $this, 'column_action' ) );
        add_action( 'dashboard_glance_items',       array( $this, 'at_a_glance' ) );
        add_action( 'wp_dashboard_setup',           array( $this, 'dashboard_widget' ) );

        add_filter( 'manage_faq_posts_columns',     array( $this, 'columns_filter' ) );
        add_filter( 'post_updated_messages',        array( $this, 'messages' ) );
        add_filter( 'cmb_meta_boxes',               array( $this, 'metaboxes' ) );

        add_shortcode( 'faq',                       array( $this, 'faq_shortcode' ) );

    }

    /**
     * Defines constants used by the plugin.
     *
     * @since   1.2.0
     * @version 1.4.0
     */
    function constants() {
        define( 'ACFAQ_VERSION',          '1.5.2' );
        define( 'ACFAQ_URL',              trailingslashit( plugin_dir_url( __FILE__ ) ) );
        define( 'ACFAQ_DIR',              trailingslashit( plugin_dir_path( __FILE__ ) ) );
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
                        'name'                  => __( 'FAQ',                       'acf' ),
                        'singular_name'         => __( 'FAQ',                       'acf' ),
                        'add_new'               => __( 'Add New',                   'acf' ),
                        'add_new_item'          => __( 'Add New Question',          'acf' ),
                        'edit'                  => __( 'Edit',                      'acf' ),
                        'edit_item'             => __( 'Edit Question',             'acf' ),
                        'new_item'              => __( 'New Question',              'acf' ),
                        'view'                  => __( 'View FAQ',                  'acf' ),
                        'view_item'             => __( 'View Question',             'acf' ),
                        'search_items'          => __( 'Search FAQ',                'acf' ),
                        'not_found'             => __( 'No FAQs found',             'acf' ),
                        'not_found_in_trash'    => __( 'No FAQs found in Trash',    'acf' )
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
                        'name'                          => __( 'Groups',                                'acf' ),
                        'singular_name'                 => __( 'Group',                                 'acf' ),
                        'search_items'                  => __( 'Search Groups',                         'acf' ),
                        'popular_items'                 => __( 'Popular Groups',                        'acf' ),
                        'all_items'                     => __( 'All Groups',                            'acf' ),
                        'parent_item'                   => null,
                        'parent_item_colon'             => null,
                        'edit_item'                     => __( 'Edit Group' ,                           'acf' ),
                        'update_item'                   => __( 'Update Group',                          'acf' ),
                        'add_new_item'                  => __( 'Add New Group',                         'acf' ),
                        'new_item_name'                 => __( 'New Group Name',                        'acf' ),
                        'separate_items_with_commas'    => __( 'Separate groups with commas',           'acf' ),
                        'add_or_remove_items'           => __( 'Add or remove groups',                  'acf' ),
                        'choose_from_most_used'         => __( 'Choose from the most used groups',      'acf' ),
                        'menu_name'                     => __( 'Groups',                                'acf' ),
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
     * @param array $meta_boxes
     * @return array $meta_boxes
     * @since 1.2.0
     */
    function metaboxes( $meta_boxes ) {
        $metabox = array(
            'id'            => 'faq-setting',
            'title'         => __( 'FAQ Setting', 'acf' ),
            'pages'         => array( 'faq' ),
            'context'       => 'side',
            'priority'      => 'default',
            'show_names'    => false,
            'fields'        => array(
                array(
                    'id'    => '_acf_rtt',
                    'name'  => __( 'Show Return to Top', 'acf' ),
                    'desc'  => __( 'Enable a "Return to Top" link at the bottom of this FAQ. The link will return the user to the top of this specific question', 'acf' ),
                    'type'  => 'checkbox'
                ),
                array(
                    'id'    => '_acf_open',
                    'name'  => __( 'Load FAQ Open', 'acf' ),
                    'desc'  => __( 'Load this FAQ in the open state (default is closed). This is not available when using the accordion configuration', 'acf' ),
                    'type'  => 'checkbox'
                )
            )
        );

        $meta_boxes[] = $metabox;

        return $meta_boxes;
    }

    /**
     * Loads the MetaBox and Dashboard At a Glance classes
     *
     * @since  0.9.0
     * @version  1.4.0
     */
    function init() {
        if( ! class_exists( 'cmb_Meta_Box' ) )
            require_once( ACFAQ_DIR . 'metabox/init.php' );

        if ( ! class_exists( 'Gamajo_Dashboard_Glancer' ) )
            require_once( ACFAQ_DIR . 'class-gamajo-dashboard-glancer.php');
    }

    /**
     * Display FAQs
     *
     * @param type $atts
     * @since 0.9
     * @version 1.2.0
     */
    function faq_shortcode( $atts, $content = null ) {
        // Set our JS to load
        wp_enqueue_script( 'arconix-faq-js' );

        // Translate 'all' to nopaging = true ( for backward compatibility)
        if( isset( $atts['showposts'] ) ) {
            if( $atts['showposts'] != "all" and $atts['showposts'] > 0 ) {
                $atts['posts_per_page'] = $atts['showposts'];
            }
        }

        $f = new Arconix_FAQ;

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
     * @since 1.2.0
     * @version 1.5.0
     */
    function enq_scripts() {
        // Register the javascript - Check the theme directory first, the parent theme (if applicable) second, otherwise load the plugin file
        if ( apply_filters( 'pre_register_arconix_faq_js', true ) ) {
            if( file_exists( get_stylesheet_directory() . '/arconix-faq.js' ) )
                wp_register_script( 'arconix-faq-js', get_stylesheet_directory_uri() . '/arconix-faq.js', array( 'jquery-ui-accordion' ), ACFAQ_VERSION );
            elseif( file_exists( get_template_directory() . '/arconix-faq.js' ) )
                wp_register_script( 'arconix-faq-js', get_template_directory_uri() . '/arconix-faq.js', array( 'jquery-ui-accordion' ), ACFAQ_VERSION );
            else
                wp_register_script( 'arconix-faq-js', ACFAQ_URL . 'js/arconix-faq.js', array( 'jquery-ui-accordion' ), ACFAQ_VERSION );
        }

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
                'url' => '//ajax.googleapis.com/ajax/libs/jqueryui/' . $ui->ver . '/themes/smoothness/jquery-ui.min.css',
                'ver' => $ui->ver,
                'dep' => false
            ) );

            wp_enqueue_style( 'jquery-ui-smoothness', $css_args['url'], $css_args['dep'], $css_args['ver'] );
        }

        // Load the CSS - Check the theme directory first, the parent theme (if applicable) second, otherwise load the plugin file
        if( apply_filters( 'pre_register_arconix_faq_css', true ) ) {
            if( file_exists( get_stylesheet_directory() . '/arconix-faq.css' ) )
                wp_enqueue_style( 'arconix-faq', get_stylesheet_directory_uri() . '/arconix-faq.css', false, ACFAQ_VERSION );
            elseif( file_exists( get_template_directory() . '/arconix-faq.css' ) )
                wp_enqueue_style( 'arconix-faq', get_template_directory_uri() . '/arconix-faq.css', false, ACFAQ_VERSION );
            else
                wp_enqueue_style( 'arconix-faq', ACFAQ_URL . 'css/arconix-faq.css', false, ACFAQ_VERSION );
        }

    }

    /**
     * Includes admin scripts. Use the pre_register filter if you'd like to prevent the file from being loaded
     *
     * @since 1.2.0
     */
    function enq_admin_scripts() {
        if( apply_filters( 'pre_register_arconix_faq_admin_css', true ) )
            wp_enqueue_style( 'arconix-faq-admin', ACFAQ_URL . 'css/admin.css', false, ACFAQ_VERSION );
    }

    /**
     * Change the Post Updated messages
     *
     * @since   0.9
     * @version 1.5.2
     *
     * @global  stdObj $post
     * @global  int $post_ID
     * @param   array $messages
     * @return  array $messages
     */
    function messages( $messages ) {
        global $post, $post_ID;
        $post_type = get_post_type( $post_ID );

        $obj = get_post_type_object( $post_type );
        $singular = $obj->labels->singular_name;

        $messages[$post_type] = array(
            0  => '', // Unused. Messages start at index 1.
            1  => sprintf( __( $singular . ' updated. <a href="%s">View ' . strtolower( $singular ) . '</a>' ), esc_url( get_permalink( $post_ID ) ) ),
            2  => __( 'Custom field updated.' ),
            3  => __( 'Custom field deleted.' ),
            4  => __( $singular . ' updated.' ),
            5  => isset( $_GET['revision'] ) ? sprintf( __( $singular . ' restored to revision from %s' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
            6  => sprintf( __( $singular . ' published. <a href="%s">View ' . strtolower( $singular ) . '</a>' ), esc_url( get_permalink( $post_ID ) ) ),
            7  => __( 'Page saved.' ),
            8  => sprintf( __( $singular . ' submitted. <a target="_blank" href="%s">Preview ' . strtolower( $singular ) . '</a>' ), esc_url( add_query_arg( 'preview', 'true', get_permalink( $post_ID ) ) ) ),
            9  => sprintf( __( $singular . ' scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview ' . strtolower( $singular ) . '</a>' ), date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink( $post_ID ) ) ),
            10 => sprintf( __( $singular . ' draft updated. <a target="_blank" href="%s">Preview ' . strtolower( $singular ) . '</a>' ), esc_url( add_query_arg( 'preview', 'true', get_permalink( $post_ID ) ) ) ),
        );

        return $messages;
    }

    /**
     * Choose the specific columns we want to display
     *
     * @param array $columns
     * @return string $columns
     * @since 0.9
     * @version 1.2
     */
    function columns_filter( $columns ) {
        $columns = array(
            "cb"            => '<input type="checkbox" />',
            "title"         => __( 'FAQ Title', 'acf' ),
            "faq_content"   => __( 'Answer', 'acf' ),
            'faq_groups'    => __( 'Group', 'acf' ),
            "date"          => __( 'Date', 'acf' )
        );

        return $columns;
    }

    /**
     * Filter the data that shows up in the columns we defined above
     *
     * @global type $post
     * @param type $column
     * @since 0.9
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
            default:
                break;
        }
    }

    /**
     * Add the Post type to the "At a Glance" Dashboard Widget
     *
     * @since 1.0
     * @version  1.4.0
     */
    function at_a_glance() {
        $glancer = new Gamajo_Dashboard_Glancer;
        $glancer->add( 'faq' );
    }

    /**
     * Adds a widget to the dashboard.
     *
     * @since 1.0.3
     * @version 1.2
     */
    function dashboard_widget() {
        if( apply_filters( 'pre_register_arconix_faq_dashboard_widget', true ) and current_user_can( 'manage_options' ) )
            wp_add_dashboard_widget( 'ac-faq', 'Arconix FAQ', array( $this, 'dashboard_widget_output' ) );
    }

    /**
     * Add a widget to the dashboard
     *
     * @since 1.0
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

        echo '<div class="acf-widget-bottom"><ul>
                  <li><a href="http://arcnx.co/afwiki" class="af-docs">Documentation</a></li>
                  <li><a href="http://arcnx.co/afhelp" class="af-help">Support Forum</a></li>
                  <li><a href="http://arcnx.co/aftrello" class="af-dev">Dev Board</a></li>
                  <li><a href="http://arcnx.co/afsource" class="af-source">Source Code</a></li>
              </ul></div></div>';
    }

}