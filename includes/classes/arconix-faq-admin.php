<?php
/**
 * Class covers the administrative side of the plugin
 *
 * @author      John Gardner
 * @link        http://arconixpc.com/plugins/arconix-faq
 * @license     GPLv2 or later
 * @since       1.4.0
 */
class Arconix_FAQ_Admin extends Arconix_CPT_Admin {

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
     * @param   string      $version    The version of this plugin.
     */
    public function __construct() {
        $this->url = trailingslashit( plugin_dir_url( dirname( __FILE__ ) ) );

        parent::__construct( 'faq', Arconix_FAQ_Plugin::textdomain );
    }
    
    /**
     * Get our hooks into WordPress
     * 
     * Overrides the parent function so we can add our class-specific hooks
     * 
     * @since   1.2.0
     */  
    public function init() {
        add_action( 'admin_enqueue_scripts',        array( $this, 'admin_scripts' ) );
        add_action( 'wp_dashboard_setup',           array( $this, 'dashboard_widget' ) );
        
        parent::init();
    }
    
    /**
     * Includes admin scripts.
     * 
     * To prevent the file from being loaded, add support to your theme
     * 
     * @example add_theme_support( 'arconix-faq', 'admin-css' );
     *
     * @since 1.2.0
     */
    function admin_scripts() {
        if ( ! current_theme_supports( 'arconix-faq', 'admin-css' ) && apply_filters( 'pre_register_arconix_faq_admin_css', true ) )
            wp_enqueue_style( 'arconix-faq-admin', $this->url . 'css/admin.css', false, Arconix_FAQ_Plugin::version );
    }

    /**
     * Choose the specific columns we want to display
     *
     * @since   0.9
     * @param   array   $columns    Existing column array
     * @return  string              New array of columns
     */
    function columns_define( $columns ) {
        
        $answer    = array( 'faq_content'   => __( 'Answer', Arconix_FAQ_Plugin::textdomain ) );
        $shortcode = array( 'faq_shortcode' => __( 'Shortcode', Arconix_FAQ_Plugin::textdomain ) );

        $columns = array_slice( $columns, 0, 2, true ) + $answer    + array_slice( $columns, 2, NULL, true );
        $columns = array_slice( $columns, 0, 3, true ) + $shortcode + array_slice( $columns, 3, NULL, true );

        return apply_filters( 'arconix_faq_admin_column_define', $columns );
    }

    /**
     * Filter the data that shows up in the columns we defined above
     *
     * @since   0.9
     * @global  stdObj  $post       WP Post Object
     * @param   array   $column     Column to populate value
     */
    function column_value( $column ) {
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
     * Adds a widget to the dashboard.
     *
     * @since   1.0.3
     */
    function dashboard_widget() {
        if( apply_filters( 'pre_register_arconix_faq_dashboard_widget', true ) and current_user_can( 'manage_options' ) )
            wp_add_dashboard_widget( 'ac-faq', 'Arconix FAQ', array( $this, 'dashboard_widget_output' ) );
    }

    /**
     * Add a widget to the dashboard
     *
     * @since   1.0.3
     */
    public function dashboard_widget_output() {
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
                <li><a href="http://arcnx.co/afwiki" class="af-docs">
                    <?php _e( 'Documentation', Arconix_FAQ_Plugin::textdomain ); ?></a></li>
                <li><a href="http://arcnx.co/afhelp" class="af-help">
                    <?php _e( 'Support Forum', Arconix_FAQ_Plugin::textdomain ); ?></a></li>
                <li><a href="http://arcnx.co/afsource" class="af-source">
                    <?php _e( 'Source Code', Arconix_FAQ_Plugin::textdomain ); ?></a></li>
            </ul></div></div>
        <?php
    }

    

}