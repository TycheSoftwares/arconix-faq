<?php
/**
 * Public-facing functionality of the plugin.
 * 
 * Handles the registration of scripts and styles as well as the shortcode registration and related output.
 * 
 * @author      John Gardner
 * @link        http://arconixpc.com/plugins/arconix-faq
 * @license     GPLv2 or later
 * @since       1.4.0
 */
class Arconix_FAQ_Public {

    /**
     * The url path to this plugin.
     *
     * @since   1.4.0
     * @access  private
     * @var     string      $url        The url path to this plugin
     */
    private $url;

    /**
     * Initialize the class
     */
    public function __construct() {
        $this->url = trailingslashit( plugin_dir_url( dirname( __FILE__ ) ) );
    }    
    
    /**
     * Get our hooks into WordPress
     *
     * @since   1.4.0
     */
    public function init() {
        add_action( 'wp_enqueue_scripts',           array( $this, 'styles' ) );
        add_action( 'wp_enqueue_scripts',           array( $this, 'scripts' ) );

        add_shortcode( 'faq',                       array( $this, 'faq_shortcode' ) );
    }

    
    /**
     * Register the necessary CSS, which can be overridden in a couple different ways.
     *
     * If you would like to bundle the CSS funtionality into another file and prevent either of the plugin's
     * CSS from loading at all, add theme support whichever of the files you wish to override
     *
     * @exmaple add_theme_support( 'arconix-faq', 'jquery-ui' );
     * @example add_theme_support( 'arconix-faq', 'css' );
     *
     * If you'd like to use your own JS or CSS file, you can copy the arconix-faq.js or arconix-faq.css files to the
     * root of your theme's folder. That will be loaded in place of the plugin's version, which means you can modify
     * it to your heart's content and know the file will be safe when the plugin is updated in the future.
     *
     * @since   1.4.0
     */
    public function styles() {
        /**
         * Load the CSS necessary for the accordion script
         *
         * If you plan on adding a filter to use a different jQuery UI theme, it's highly recommended
         * you reference the $wp_scripts global as well as the $ui variable to make sure we load the CSS
         * for the version of jQuery WordPress loads
         */
        if ( ! current_theme_supports( 'arconix-faq', 'jquery-ui' ) && apply_filters( 'pre_register_arconix_faq_jqui_css', true ) ) {
            
            global $wp_scripts;

            // Get registered script object for jquery-ui
            $ui = $wp_scripts->query( 'jquery-ui-core' );

            $css_args = apply_filters( 'arconix_jqueryui_css_reg', array(
                'url' => '//ajax.googleapis.com/ajax/libs/jqueryui/' . $ui->ver . '/themes/smoothness/jquery-ui.min.css',
                'ver' => $ui->ver,
                'dep' => false
            ) );

            wp_enqueue_style( 'jquery-ui-smoothness', $css_args['url'], $css_args['dep'], $css_args['ver'] );
        }

        // Load the CSS - Check the theme directory first, the parent theme (if applicable) second, otherwise load the plugin file
        if ( ! current_theme_supports( 'arconix-faq', 'css' ) && apply_filters( 'pre_register_arconix_faq_css', true ) ) {
            if( file_exists( get_stylesheet_directory() . '/arconix-faq.css' ) )
                wp_enqueue_style( 'arconix-faq', get_stylesheet_directory_uri() . '/arconix-faq.css', false, Arconix_FAQ_Plugin::version );
            elseif( file_exists( get_template_directory() . '/arconix-faq.css' ) )
                wp_enqueue_style( 'arconix-faq', get_template_directory_uri() . '/arconix-faq.css', false, Arconix_FAQ_Plugin::version );
            else
                wp_enqueue_style( 'arconix-faq', $this->url . 'css/arconix-faq.css', false, Arconix_FAQ_Plugin::version );
        }
    }
    
    /**
     * Register the necessary Javascript, which can be overridden in a couple different ways.
     *
     * If you would like to bundle the Javacsript funtionality into another file and prevent either of the plugin's
     * JS from loading at all, add theme support whichever of the files you wish to override
     *
     * @example add_theme_support( 'arconix-faq', 'js' );
     *
     * If you'd like to use your own JS or CSS file, you can copy the arconix-faq.js or arconix-faq.css files to the
     * root of your theme's folder. That will be loaded in place of the plugin's version, which means you can modify
     * it to your heart's content and know the file will be safe when the plugin is updated in the future.
     *
     * @since   1.4.0
     */
    public function scripts() {
        if ( ! current_theme_supports( 'arconix-faq', 'js' ) && apply_filters( 'pre_register_arconix_faq_js', true ) ) {
            if( file_exists( get_stylesheet_directory() . '/arconix-faq.js' ) )
                wp_register_script( 'arconix-faq-js', get_stylesheet_directory_uri() . '/arconix-faq.js', array( 'jquery-ui-accordion' ), Arconix_FAQ_Plugin::version );
            elseif( file_exists( get_template_directory() . '/arconix-faq.js' ) )
                wp_register_script( 'arconix-faq-js', get_template_directory_uri() . '/arconix-faq.js', array( 'jquery-ui-accordion' ), Arconix_FAQ_Plugin::version );
            else
                wp_register_script( 'arconix-faq-js', $this->url . 'js/arconix-faq.js', array( 'jquery-ui-accordion' ), Arconix_FAQ_Plugin::version );
        }
    }
   
    /**
     * FAQ Shortcode Handler
     *
     * @since   0.9.0
     * @param   array   $atts   Shortcode settings
     * @return  string          HTML output of FAQs
     */
    public function faq_shortcode( $atts, $content = null ) {
        // Set our JS to load
        wp_enqueue_script( 'arconix-faq-js' );

        // Translate 'all' to nopaging = true ( for backward compatibility)
        if( isset( $atts['showposts'] ) ) {
            if( $atts['showposts'] != "all" and $atts['showposts'] > 0 ) {
                $atts['posts_per_page'] = $atts['showposts'];
            }
        }

        $f = new Arconix_FAQ_Display;

        return $f->loop( $atts );
    }

}
