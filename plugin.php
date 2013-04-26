<?php
/**
 * Plugin Name: Arconix FAQ
 * Plugin URI: http://arconixpc.com/plugins/arconix-faq
 * Description: Plugin to handle the display of FAQs
 *
 * Version: 1.2.0
 *
 * Author: John Gardner
 * Author URI: http://arconixpc.com/
 *
 * License: GNU General Public License v2.0
 * License URI: http://www.opensource.org/licenses/gpl-license.php
 */

class Arconix_FAQ {
    /**
     * Constructor
     *
     * @since 1.0
     * @version 1.2.0
     */
    function __construct() {
        $this->constants();

        register_activation_hook( __FILE__,         array( $this, 'activation' ) );
        register_deactivation_hook( __FILE__,       array( $this, 'deactivation' ) );

        add_action( 'init',                         array( $this, 'content_types' ) );
        add_action( 'wp_enqueue_scripts',           array( $this, 'enq_scripts' ) );
        add_action( 'admin_enqueue_scripts',        array( $this, 'enq_admin_scripts' ) );
        add_action( 'manage_posts_custom_column',   array( $this, 'column_action' ) );
        add_action( 'right_now_content_table_end',  array( $this, 'right_now' ) );
        add_action( 'wp_dashboard_setup',           array( $this, 'dashboard_widget' ) );
        add_action( 'init',                         'arconix_faq_init_meta_boxes', 9999 );

        add_filter( 'manage_faq_posts_columns',     array( $this, 'columns_filter' ) );
        add_filter( 'post_updated_messages',        array( $this, 'messages' ) );
        add_filter( 'cmb_meta_boxes',               array( $this, 'metaboxes' ) );

        add_shortcode( 'faq',                       array( $this, 'faq_shortcode' ) );

    }

    /**
     * Defines constants used by the plugin.
     *
     * @since 1.2.0
     */
    function constants() {
        define( 'ACF_VERSION',          '1.2.0' );
        define( 'ACF_URL',              trailingslashit( plugin_dir_url( __FILE__ ) ) );
        define( 'ACF_INCLUDES_URL',     trailingslashit( ACF_URL . 'includes' ) );
        define( 'ACF_IMAGES_URL',       trailingslashit( ACF_URL . 'images' ) );
        define( 'ACF_CSS_URL',          trailingslashit( ACF_INCLUDES_URL . 'css' ) );
        define( 'ACF_DIR',              trailingslashit( plugin_dir_path( __FILE__ ) ) );
        define( 'ACF_INCLUDES_DIR',     trailingslashit( ACF_DIR . 'includes' ) );
        define( 'ACF_VIEWS_DIR',        trailingslashit( ACF_INCLUDES_DIR . 'views' ) );
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
                    'menu_icon'         => ACF_IMAGES_URL . 'faq-16x16.png',
                    'has_archive'       => false,
                    'supports'          => array( 'title', 'editor', 'revisions' ),
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
                    'rewrite'                   => array( 'slug' => 'group' )
                )
            ),
            'query' => array(
                'order'             => 'ASC',
                'orderby'           => 'title',
                'posts_per_page'    => -1,
                'nopaging'          => true,
                'group'             => '',
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
            'title'         => __( 'FAQ Setting', 'act' ),
            'pages'         => array( 'faq' ),
            'context'       => 'side',
            'priority'      => 'default',
            'show_names'    => false,
            'fields'        => array(
                array(
                    'id'    => '_acf_rtt',
                    'name'  => 'Show Return to Top',
                    'desc'  => __( 'Enable a "Return to Top" link on this FAQ', 'acf' ),
                    'type'  => 'checkbox'
                )
            )
        );

        $meta_boxes[] = $metabox;

        return $meta_boxes;
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
                $atts['nopaging'] = false;
            }
        }

        return ARCONIX_FAQ::get_faq_data( $atts );
    }

    /**
     * Get our FAQ data
     * 
     * @param  array  $args
     * @param  boolean $echo Echo or Return the data
     * @return mixed FAQ information for display
     * @since 1.2.0
     */
    function get_faq_data( $args, $echo = false ) {
        // Get our defaults
        $default_args = $this->defaults();
        $defaults = $default_args['query'];

        // Merge incoming args with the function defaults
        $args = wp_parse_args( $args, $defaults );

        // Container
        $return = '';

        // Get the taxonomy terms assigned to all FAQs 
        $terms = get_terms( $default_args['taxonomy']['slug'] );
        $count = 0;

        // If there are any terms being used, loop through each one to output the relevant FAQ's, else just output all FAQs 
        if( $terms ) {

            foreach( $terms as $term ) {
                $count ++;
                $return .= '<!-- count = ' . $count . ' and slug = '. $term->slug . ' -->';

                // If a user sets a specific group in the params, that's the only one we care about 
                $group = $args['group'];
                if( isset( $group ) and $group != '' and $term->slug != $group ) continue;

                // Set up our standard query args.
                $query_args = array(
                    'post_type'         => $default_args['post_type']['slug'],
                    'order'             => $args['order'],
                    'orderby'           => $args['orderby'],
                    'posts_per_page'    => $args['posts_per_page'],
                    'nopaging'          => $args['nopaging'],
                    'tax_query'         => array(
                        array(
                            'taxonomy'      => $default_args['taxonomy']['slug'],
                            'field'         => 'slug',
                            'terms'         => array( $term->slug ),
                            'operator'      => 'IN'
                        )
                    )
                );

                // New query just for the tax term we're looping through
                $q = new WP_Query( $query_args );

                if( $q->have_posts() ) {

                    $return .= '<h3 class="arconix-faq-term-title arconix-faq-term-' . $term->slug . '">' . $term->name . '</h3>';

                    // If the term has a description, show it
                    if( $term->description )
                        $return .= '<p class="arconix-faq-term-description">' . $term->description . '</p>';

                    // Loop through the rest of the posts for the term
                    while( $q->have_posts() ) : $q->the_post();

                        // Grab our metadata
                        $rtt = get_post_meta( get_the_id(), '_acf_rtt', true );

                        // Set up our anchor link
                        $link = 'faq-' . sanitize_title( get_the_title() );

                        $return .= '<div id="post-' . get_the_ID() . '" class="arconix-faq-wrap arconix-faq-group-' . $term->slug . '">';
                        $return .= '<div class="arconix-faq-title"><a name="' . $link . '"></a>' . get_the_title() . '</div>';
                        $return .= '<div class="arconix-faq-content">' . apply_filters( 'the_content', get_the_content() );

                        // If Return to Top checkbox is true
                        if( $rtt ) {
                            $rtt_text = __( 'Return to Top', 'acf' );
                            $rtt_text = apply_filters( 'arconix_faq_return_to_top_text', $rtt_text );

                            $return .= '<div class="arconix-faq-to-top"><a href="#' . $link . '">' . $rtt_text . '</a></div>';
                        }

                        $return .= '</div>'; // faq-content
                        $return .= '</div>'; // faq-wrap

                    endwhile;
                } // end have_posts()

                wp_reset_postdata();

            } // end foreach

        } // End if( $terms )

        else {

            // Set up our standard query args.
            $q = new WP_Query( array(
                'post_type'         => $default_args['post_type']['slug'],
                'order'             => $args['order'],
                'orderby'           => $args['orderby'],
                'posts_per_page'    => $args['posts_per_page'],
                'nopaging'          => $args['nopaging'],
            ) );


            if( $q->have_posts() ) {
                
                while( $q->have_posts() ) : $q->the_post();

                    // Grab our metadata
                    $rtt = get_post_meta( get_the_id(), '_acf_rtt', true );

                    // Set up our anchor link
                    $link = 'faq-' . sanitize_title( get_the_title() );

                    $return .= '<div id="post-' . get_the_id() . '" class="arconix-faq-wrap arconix-faq-group-' . $term->slug . '">';
                    $return .= '<div class="arconix-faq-title"><a name="' . $link . '"></a>' . get_the_title() . '</div>';
                    $return .= '<div class="arconix-faq-content">' . apply_filters( 'the_content', get_the_content() );

                    // If Return to Top checkbox is true
                    if( $rtt ) {
                        $rtt_text = __( 'Return to Top', 'acf' );
                        $rtt_text = apply_filters( 'arconix_faq_return_to_top_text', $rtt_text );

                        $return .= '<div class="arconix-faq-to-top"><a href="#' . $link . '">' . $rtt_text . '</a></div>';
                    }

                    $return .= '</div>'; // faq-content
                    $return .= '</div>'; // faq-wrap

                endwhile;

            } // end have_posts()

            wp_reset_postdata();
        
        }

        // Allow complete override of the FAQ content
        $return = apply_filters( 'arconix_faq_return', $return );

        if( $echo === true )
            echo $return;
        else
            return $return;
    }

    /**
     * Register the necessary Javascript and CSS, which can be overridden in a couple different ways.
     * 
     * If you would like to bundle the Javacsript or CSS funtionality into another file and prevent either of the plugin's
     * JS or CSS from loading at all, return false to whichever of the pre_register filters you wish to override
     * 
     * If you'd like to use your own JS or CSS file, you can copy the arconix-faq.js or arconix-faq.css files to the 
     * root of your theme's folder. That will be loaded in place of the plugin's version, which means you can modify 
     * it to your heart's content and know the file will be safe when the plugin is updated in the future.
     *
     * @since 1.2.0
     */
    function enq_scripts() {
        // Register the javascript - Check the theme directory first, the parent theme (if applicable) second, otherwise load the plugin file
        if( apply_filters( 'pre_register_arconix_faq_js', true ) ) {
            if( file_exists( get_stylesheet_directory() . '/arconix-faq.js' ) )
                wp_register_script( 'arconix-faq-js', get_stylesheet_directory_uri() . '/arconix-faq.js', array( 'jquery' ), ACF_VERSION, true );
            elseif( file_exists( get_template_directory() . '/arconix-faq.js' ) )
                wp_register_script( 'arconix-faq-js', get_template_directory_uri() . '/arconix-faq.js', array( 'jquery' ), ACF_VERSION, true );
            else
                wp_register_script( 'arconix-faq-js', ACF_INCLUDES_URL . 'arconix-faq.js', array( 'jquery' ), ACF_VERSION, true );
        }

        // Load the CSS - Check the theme directory first, the parent theme (if applicable) second, otherwise load the plugin file
        if( apply_filters( 'pre_register_arconix_faq_css', true ) ) {
            if( file_exists( get_stylesheet_directory() . '/arconix-faq.css' ) )
                wp_enqueue_style( 'arconix-faq', get_stylesheet_directory_uri() . '/arconix-faq.css', false, ACF_VERSION );
            elseif( file_exists( get_template_directory() . '/arconix-faq.css' ) )
                wp_enqueue_style( 'arconix-faq', get_template_directory_uri() . '/arconix-faq.css', false, ACF_VERSION );
            else
                wp_enqueue_style( 'arconix-faq', ACF_CSS_URL . 'arconix-faq.css', false, ACF_VERSION );
        }

    }

    /**
     * Includes admin scripts. Use the pre_register filter if you'd like to prevent the file from being loaded
     *
     * @since 1.2.0
     */
    function enq_admin_scripts() {
        if( apply_filters( 'pre_register_arconix_faq_admin_css', true ) )
            wp_enqueue_style( 'arconix-faq-admin', ACF_CSS_URL . 'admin.css', false, ACF_VERSION );
    }

    /**
     * Change the Post Updated messages
     *
     * @global type $post
     * @global type $post_ID
     * @param type $messages
     * @return type $messages
     * @since 0.9
     */
    function messages( $messages ) {
        global $post, $post_ID;

        $messages['faq'] = array(
            0 => '', // Unused. Messages start at index 1.
            1 => sprintf( __( 'FAQ updated. <a href="%s">View staff</a>' ), esc_url( get_permalink( $post_ID ) ) ),
            2 => __( 'Custom field updated.' ),
            3 => __( 'Custom field deleted.' ),
            4 => __( 'FAQ updated.' ),
            /* translators: %s: date and time of the revision */
            5 => isset( $_GET['revision'] ) ? sprintf( __( 'FAQ restored to revision from %s' ), wp_post_revision_title( ( int ) $_GET['revision'], false ) ) : false,
            6 => sprintf( __( 'FAQ published. <a href="%s">View FAQ</a>' ), esc_url( get_permalink( $post_ID ) ) ),
            7 => __( 'FAQ saved.' ),
            8 => sprintf( __( 'FAQ submitted. <a target="_blank" href="%s">Preview FAQ</a>' ), esc_url( add_query_arg( 'preview', 'true', get_permalink( $post_ID ) ) ) ),
            9 => sprintf( __( 'FAQ scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview FAQ</a>' ),
                    // translators: Publish box date format, see http://php.net/date
                    date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink( $post_ID ) ) ),
            10 => sprintf( __( 'FAQ draft updated. <a target="_blank" href="%s">Preview FAQ</a>' ), esc_url( add_query_arg( 'preview', 'true', get_permalink( $post_ID ) ) ) ),
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
     * Add the Post type to the "Right Now" Dashboard Widget
     *
     * @link http://bajada.net/2010/06/08/how-to-add-custom-post-types-and-taxonomies-to-the-wordpress-right-now-dashboard-widget
     * @since 1.0
     * @version  1.2.0
     */
    function right_now() {
        include_once( ACF_VIEWS_DIR . 'right-now.php' );
    }

    /**
     * Adds a widget to the dashboard.
     *
     * @since 1.0.3
     * @version 1.2
     */
    function dashboard_widget() {
        if( apply_filters( 'pre_register_arconix_faq_dashboard_widget', true ) )
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


function arconix_faq_init_meta_boxes() {
    if( ! class_exists( 'cmb_Meta_Box' ) )
        require_once( plugin_dir_path( __FILE__ ) . '/includes/metabox/init.php' );
}

new Arconix_FAQ;