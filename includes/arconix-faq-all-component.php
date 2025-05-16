<?php
/**
 * It will Add all the Boilerplate component when we activate the plugin.
 *
 * @author  Tyche Softwares
 * @package arconix-faq/component
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
if ( ! class_exists( 'Arconix_FAQ_Component' ) ) {
	/**
	 * It will Add all the Boilerplate component when we activate the plugin.
	 */
	class Arconix_FAQ_Component {

		/**
		 * It will Add all the Boilerplate component when we activate the plugin.
		 */
		public function __construct() {

			$is_admin = is_admin();

			if ( true === $is_admin ) {

				require_once 'component/tracking-data/ts-tracking.php';
				require_once 'component/deactivate-survey-popup/class-ts-deactivation.php';

				require_once 'component/welcome-page/ts-welcome.php';
				require_once 'component/faq-support/ts-faq-support.php';

				$faq_plugin_name = self::ts_get_plugin_name();

				$faq_locale = self::ts_get_plugin_locale();

				$faq_file_name          = 'arconix-faq/plugin.php';
				$faq_plugin_prefix      = 'faq';
				$faq_lite_plugin_prefix = 'faq';
				$faq_plugin_folder_name = 'arconix-faq/';
				$faq_plugin_dir_name    = dirname( untrailingslashit( plugin_dir_path( __FILE__ ) ) ) . '/plugin.php';
				$faq_plugin_url         = dirname( untrailingslashit( plugins_url( '/', __FILE__ ) ) );

				$faq_get_previous_version = get_option( 'faq_version', '1' );

				$faq_blog_post_link = 'https://www.tychesoftwares.com/docs/docs/faq/usage-tracking/';

				$faq_plugins_page  = 'edit.php?post_type=faq';
				$faq_plugin_slug   = 'edit.php?post_type=faq';
				$faq_pro_file_name = '';

				$faq_settings_page = 'edit.php?post_type=faq';

				new FAQ_TS_tracking( $faq_plugin_prefix, $faq_plugin_name, $faq_blog_post_link, $faq_locale, $faq_plugin_url, $faq_settings_page, '', '', '', $faq_file_name );

				new FAQ_TS_Tracker( $faq_plugin_prefix, $faq_plugin_name );

				$faq_deativate = new FAQ_TS_deactivate();
				$faq_deativate->init( $faq_file_name, $faq_plugin_name );

				// $user = wp_get_current_user();

				// if ( in_array( 'administrator', (array) $user->roles ) ) {

				// new FAQ_TS_Welcome ( $faq_plugin_name, $faq_plugin_prefix, $faq_locale, $faq_plugin_folder_name, $faq_plugin_dir_name, $faq_get_previous_version );
				// }

				$ts_pro_faq = self::faq_get_faq();
				new FAQ_TS_Faq_Support( $faq_plugin_name, $faq_plugin_prefix, $faq_plugins_page, $faq_locale, $faq_plugin_folder_name, $faq_plugin_slug, $ts_pro_faq, 'faq_support_page' );
			}
		}

		 /**
		  * It will retrun the plguin name.
		  *
		  * @return string $ts_plugin_name Name of the plugin
		  */
		public static function ts_get_plugin_name() {
			$ordd_plugin_dir  = dirname( dirname( __FILE__ ) );
			$ordd_plugin_dir .= '/plugin.php';

			$ts_plugin_name = '';
			$plugin_data    = get_file_data( $ordd_plugin_dir, array( 'name' => 'Plugin Name' ) );
			if ( ! empty( $plugin_data['name'] ) ) {
				$ts_plugin_name = $plugin_data['name'];
			}
			return $ts_plugin_name;
		}

		/**
		 * It will retrun the Plugin text Domain
		 *
		 * @return string $ts_plugin_domain Name of the Plugin domain
		 */
		public static function ts_get_plugin_locale() {
			$ordd_plugin_dir  = dirname( dirname( __FILE__ ) );
			$ordd_plugin_dir .= '/plugin.php';

			$ts_plugin_domain = '';
			$plugin_data      = get_file_data( $ordd_plugin_dir, array( 'domain' => 'Text Domain' ) );
			if ( ! empty( $plugin_data['domain'] ) ) {
				$ts_plugin_domain = $plugin_data['domain'];
			}
			return $ts_plugin_domain;
		}

		/**
		 * It will contain all the FAQ which need to be display on the FAQ page.
		 *
		 * @return array $ts_faq All questions and answers.
		 */
		public static function faq_get_faq() {

			$ts_faq = array();

			$ts_faq = array(
				1 => array(
					'question' => 'How do I display my FAQ’s?',
					'answer'   => 'Use the [faq] shortcode in a widget or on a post/page. This will output the FAQ’s using the default settings (Ascending order by Title in a Toggle configuration). If you’d like to use a different order, consult the <a href="https://www.tychesoftwares.com/docs/docs/faq/?utm_source=wpfaqpage&utm_medium=link&utm_campaign=FAQ" rel="nofollow" target="_blank">Documentation</a> for assistance.',
				),
				2 => array(
					'question' => 'How do I enable the accordion display mode?',
					'answer'   => 'Add style="accordion" to the shortcode, e.g. [faq style="accordion"]',
				),
				3 => array(
					'question' => 'The toggle or accordion isn’t working. What can I do?',
					'answer'   => 'While you can certainly start a thread in the <a href="https://wordpress.org/support/plugin/arconix-faq" rel="nofollow" target="_blank">support forum</a>, there are some troubleshooting steps you can take beforehand to help speed up the process.
                        <br>
                        1. Check to make sure the javascripts are loading correctly. Load the faq page in your browser and view your page’s source. Look for jQuery and Arconix FAQ JS files there. If you don’t see the Arconix FAQ JS file, then your theme’s header.php file is likely missing <?php wp_head(); ?>, which is necessary for the operation of mine and many other plugins.
                        <br>
                        2. Check to make sure only one copy of jQuery is being loaded. Many times conflicts arise when themes or plugins load jQuery incorrectly, causing the script to be loaded multiple times in multiple versions. In order to find the offending item, start by disabling your plugins one by one until you find the problem. If you’ve disabled all your plugins, try switching to a different them, such as twentyten or twentytwelve to see if the problem is with your theme. Once you’ve found the problem, contact the developer for assistance getting the issue resolved.',
				),
			);

			return $ts_faq;
		}
	}
	$arconix_faq_component = new Arconix_FAQ_Component();
}
