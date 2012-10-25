<?php
/**
 * Plugin Name: Arconix FAQ
 * Plugin URI: http://arconixpc.com/plugins/arconix-faq
 * Description: Plugin to handle the display of FAQs
 *
 * Version: 1.1.1
 *
 * Author: John Gardner
 * Author URI: http://arconixpc.com/
 *
 * License: GNU General Public License v2.0
 * License URI: http://www.opensource.org/licenses/gpl-license.php
 */

register_activation_hook( __FILE__, 'arconix_faq_activation' );
/**
 * This function runs on plugin activation. It checks for the existence of the post-type
 * and creates it otherwise.
 *
 * @since 1.0
 * @version 1.1
 */
function arconix_faq_activation() {

    if ( ! post_type_exists( 'faq' ) ) {
        arconix_faq_setup();
        global $_arconix_faq;
        $_arconix_faq->create_post_type();
        $_arconix_faq->create_taxonomy();
    }
    flush_rewrite_rules();

}

add_action( 'after_setup_theme', 'arconix_faq_setup' );
/**
 * Initialize the plugin.
 *
 * Include the libraries, define global variables, instantiate the classes.
 *
 * @since 1.0
 */
function arconix_faq_setup() {
    global $_arconix_faq;

    define( 'ACF_URL', plugin_dir_url( __FILE__ ) );
    define( 'ACF_VERSION', '1.1.1');

    /** Includes */
    require_once( dirname( __FILE__ ) . '/includes/class-faq.php' );

    /** Instantiate */
    $_arconix_faq = new Arconix_FAQ;

}

?>