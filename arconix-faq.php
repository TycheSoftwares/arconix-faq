<?php
/**
 * Plugin Name: Arconix FAQ
 * Plugin URI: http://arconixpc.com/plugins/arconix-faq
 * Description: Plugin to handle the display of FAQs
 *
 * Version: 1.6.1
 *
 * Author: John Gardner
 * Author URI: http://arconixpc.com/
 *
 * Text Domain: arconix-faq
 *
 * License: GPLv2 or later
 * License URI: http://www.opensource.org/licenses/gpl-license.php
 */

// Load the metabox class
require_once dirname( __FILE__ ) . '/includes/cmb2/init.php';

// Load the Glancer class
if ( ! class_exists( 'Gamajo_Dashboard_Glancer' ) )
    require_once dirname( __FILE__ ) . '/includes/classes/gamajo-dashboard-glancer.php';

// Set our plugin activation hook
register_activation_hook( __FILE__, 'activate_arconix_faq' );

function activate_arconix_faq() {
    require_once plugin_dir_path( __FILE__ ) . '/includes/classes/arconix-faq-activator.php';
    Arconix_FAQ_Activator::activate();
}

// Register the autoloader
spl_autoload_register( 'arconix_faq_autoloader' );

/**
 * Class Autoloader
 * 
 * @param	string	$class_name		Class to check to autoload
 * @return	null                    Return if it's not a valid class
 */
function arconix_faq_autoloader( $class_name ) {
	/**
	 * If the class being requested does not start with our prefix,
	 * we know it's not one in our project
	 */
	if ( 0 !== strpos( $class_name, 'Arconix_' ) ) {
		return;
	}

	$file_name = str_replace(
		array( 'Arconix_', '_' ),	// Prefix | Underscores 
		array( '', '-' ),           // Remove | Replace with hyphens
		strtolower( $class_name )	// lowercase
	);

	// Compile our path from the current location
	$file = dirname( __FILE__ ) . '/includes/classes/' . $file_name . '.php';

	// If a file is found, load it
	if ( file_exists( $file ) ) {
		require_once( $file );
	}
}

/**
 * Arconix FAQ Plugin
 *
 * This is the base class which sets the version, loads dependencies and gets the plugin running
 *
 * @since   1.7.0
 */
final class Arconix_FAQ_Plugin {
    
    /**
     * Plugin version.
     *
     * @since   1.7.0
     * @var     string	$version        Plugin version
     */
    const version = '1.7.0';
	
    /**
     * Post Type Settings
     *
     * @since   1.7.0
     * @var     array   $settings       Post Type default settings 
     */
	protected $settings;

    /**
     * Initialize the class and set its properties.
     *
     * @since   1.7.0
     */
    public function __construct() {
        $this->settings = $this->get_settings();
    }
	
    /**
     * Load the plugin instructions
     * 
     * @since   1.7.0
     */
	public function init() {
        $this->register_post_type();
        $this->register_taxonomy();
        $this->load_public();
        
        if ( is_admin() ) {
            $this->load_admin();
            $this->load_metaboxes();
        }
	}
    
    /**
     * Set up our Custom Post Type
     * 
     * @since   1.7.0
     */
    private function register_post_type() {
        $settings = $this->settings;
        
        $names = array(
            'post_type_name' => 'faq',
            'singular' => 'FAQ',
            'plural' => 'FAQs'
        );
        
        $pt = new Arconix_CPT_Register();
        $pt->add( $names, $settings['post_type']['args'] );
    }
    
    /**
     * Register the Post Type Taxonomy
     * 
     * @since   1.7.0
     */
    private function register_taxonomy() {
        $settings = $this->settings;
        
        $tax = new Arconix_Taxonomy_Register();
        $tax->add( 'group', 'faq', $settings['taxonomy']['args'] );
    }
    
    /**
     * Load the Public-facing components of the plugin
     * 
     * @since   1.7.0
     */
    private function load_public() {
        $p = new Arconix_FAQ_Public();
        
        $p->init();
    }

    /**
     * Loads the admin functionality
     *
     * @since   1.7.0
     */
    private function load_admin() {
        new Arconix_FAQ_Admin();
    }
    
    /**
     * Set up the Post Type Metabox
     * 
     * @since   1.7.0
     */
    private function load_metaboxes() {
        $m = new Arconix_FAQ_Metaboxes();
        
        $m->init();
    }

    /**
     * Get the default Post Type and Taxonomy registration settings
     * 
     * Settings are stored in a filterable array for customization purposes
     * 
     * @since   1.7.0
     * @return  array           Default registration settings
     */
	public function get_settings() {
		$settings = array(
            'post_type' => array(
                'args' => array(
                    'public'            => true,
                    'menu_position'     => 20,
                    'menu_icon'         => 'dashicons-editor-help',
                    'has_archive'       => false,
                    'supports'          => array( 'title', 'editor', 'revisions', 'page-attributes' ),
                    'rewrite'           => array( 'with_front' => false )
                )
            ),
            'taxonomy' => array(
                'args' => array(
                    'hierarchical'              => false,
                    'show_ui'                   => true,
                    'query_var'                 => true,
                    'rewrite'                   => array( 'with_front' => false )
                )
            )
        );

        return apply_filters( 'arconix_faq_defaults', $settings );
	}

}

/** Vroom vroom */
add_action( 'plugins_loaded', 'arconix_faq_run' );

function arconix_faq_run() {
    load_plugin_textdomain( 'arconix-faq' );
    
    $arconix_faq = new Arconix_FAQ_Plugin();
    $arconix_faq->init();
}