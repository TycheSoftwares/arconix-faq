<?php
/**
 * Create the metaboxes for the FAQ Creation Screen
 * 
 * @author      John Gardner
 * @link        http://arconixpc.com/plugins/arconix-testimonials
 * @license     GPLv2 or later
 * @since       1.2.0
 */
class Arconix_FAQ_Metaboxes {
    
    /**
     * Get our hooks into WordPress
     * 
     * @since   1.2.0
     */
    public function init() {
        add_action( 'cmb2_admin_init',    array( $this, 'cmb2') );        
        add_action( 'add_meta_boxes',     array( $this, 'shortcode_metabox' ) );
    }

    /**
     * Define the Metabox and its fields.
     *
     * @since   1.2.0
     */
    public function cmb2() {
        // Initiate the metabox
        $cmb = new_cmb2_box( array(
            'id'            => 'faq_settings',
            'title'         => __( 'FAQ Settings', 'arconix-faq' ),
            'object_types'  => array( 'faq' ),
            'context'       => 'side',
            'priority'      => 'default',
            'show_names'    => true
        ) );

        // Add the Link Type field
        $cmb->add_field( array(
            'id'    => '_acf_rtt',
            'name'  => __( 'Show Return to Top', 'arconix-faq' ),
            'desc'  => __( 'Enable a "Return to Top" link at the bottom of this FAQ. The link will return the user to the top of this specific question', 'arconix-faq' ),
            'type'  => 'checkbox'
        ) );
        
        $cmb->add_field( array(
            'id'    => '_acf_open',
            'name'  => __( 'Load FAQ Open', 'arconix-faq' ),
            'desc'  => __( 'Load this FAQ in the open state (default is closed). This is not available when using the accordion configuration', 'arconix-faq' ),
            'type'  => 'checkbox'
        ) );
    }
        
    /**
     * Adds a metabox to the FAQ creation screen.
     *
     * This metabox shows the shortcode with the post_id for users to display
     * just that faq on a post, page or other applicable location
     *
     * @since   1.6.0
     */
    public function shortcode_metabox() {
        add_meta_box( 'arconix-faq-box', __( 'FAQ Shortcode', 'arconix-faq' ), array( $this, 'faq_box' ), 'faq', 'side' );
    }

    /**
     * Output for the faq shortcode metabox. 
     * 
     * Creates a readonly inputbox that outputs the faq shortcode
     * plus the $post_id
     *
     * @since   1.6.0
     * @global  int     $post_ID    ID of the current post
     */
    public function faq_box() {
        global $post_ID;
        ?>
        <p class="howto">
            <?php _e( 'To display this question, copy the code below and paste it into your post, page, text widget or other content area.', 
            'arconix-faq' ); ?>
        </p>
        <p><input type="text" value="[faq p=<?php echo $post_ID; ?>]" readonly="readonly" class="widefat wp-ui-text-highlight code"></p>
        <?php
    }
}