<?php
/**
 * Arconix-FAQs plugin Uninstall
 *
 * Uninstalling Arconix-FAQs delets all settings for the plugin
 *
 * @author      Tyche Softwares
 * @category    Core
 * @package     Tyche Softwares/Uninstaller
 * @version     
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}


$post_data = get_posts( array( 'post_type' => 'faq') );
    
   foreach( $post_data as $each_post ) {
     // Delete's each post.
    wp_delete_post( $each_post->ID, true );
    // Set to False if you want to send them to Trash.
   }
