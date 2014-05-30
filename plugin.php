<?php
/**
 * Plugin Name: Arconix FAQ
 * Plugin URI: http://arconixpc.com/plugins/arconix-faq
 * Description: Plugin to handle the display of FAQs
 *
 * Version: 1.4.2
 *
 * Author: John Gardner
 * Author URI: http://arconixpc.com/
 *
 * License: GNU General Public License v2.0
 * License URI: http://www.opensource.org/licenses/gpl-license.php
 */


require_once( plugin_dir_path( __FILE__ ) . 'includes/class-arconix-faq.php' );
require_once( plugin_dir_path( __FILE__ ) . 'includes/class-arconix-faq-admin.php' );

new Arconix_FAQ_Admin;