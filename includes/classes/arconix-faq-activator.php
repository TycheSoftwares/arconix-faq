<?php
/**
 * Activator class for FAQ Plugin
 * 
 * @package     WordPress
 * @subpackage  Arconix FAQ
 * @author      John Gardner
 * @link        http://arconixpc.com/plugins/arconix-faq
 * @license     GPL-2.0+
 * @since       1.7.0
 */
class Arconix_FAQ_Activator {

	public static function activate( $wp = '4.6', $php = '5.3' ) {

		global $wp_version;

		if( version_compare( $wp_version, $wp, '<' ) && version_compare( PHP_VERSION, $php, '<' ) ) {
			$string = sprintf( __( 'This plugin requires either WordPress 4.6 or PHP 5.3. You are running versions %s and %s, respectively', 
			'arconix-faq' ), $wp_version , PHP_VERSION );

			deactivate_plugins( basename( __FILE__ ) );

			wp_die( $string, __( 'Plugin Activation Error', 'arconix-faq' ), array( 'response' => 200, 'back_link' => TRUE ) );
		
		}
	}

}
