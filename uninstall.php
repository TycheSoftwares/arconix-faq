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

//get data from wp_posts table where 'post_type' is 'faq'. So thet we can delete that data.
$post_data = get_posts( array( 'post_type' => 'faq') );
  
//wp_delete_post need post_id and boolean value to delete each post.  
foreach( $post_data as $each_post ) {
	// Delete each post.
	wp_delete_post( $each_post->ID, true );
	// Set to False if you want to send them to Trash.
}
