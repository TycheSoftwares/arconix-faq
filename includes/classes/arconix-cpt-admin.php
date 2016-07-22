<?php

/**
 * Abstract Class helps build out the Admin interface for the Custom Post Type
 * 
 * @license GPL-2.0+
 * @version 1.0.0
 */
abstract class Arconix_CPT_Admin {

    /**
     * Post Type Name
     *
     * @since   1.0.0
     * @var		string			$post_type_name		Name of the Custom Post Type.
     */
    protected $post_type_name;

    /**
     * Textdomain used for translation.
     *
     * @since   1.0.0
     * @var		string			$textdomain			Used for i18n.
     */
    protected $textdomain;

    /**
     * Constructor
     * 
     * @since   1.0.0
     * @param	string			$post_type_name		Name of the Custom Post Type
     * @param	string          $textdomain         For i18n
     */
    public function __construct( $post_type_name, $textdomain = 'default' ) {
        if ( !isset( $post_type_name ) )
            return;

        $this->post_type_name = $post_type_name;
        $this->textdomain     = $textdomain;

        $this->init();
    }

    /**
     * Defines which columns will be displayed on the Post Type Edit screen
     * 
     * @since   1.0.0
     */
    abstract public function columns_define( $columns );

    /**
     * Sets the value of each column to be displayed on the Post Type Edit screen
     * 
     * @since   1.0.0
     */
    abstract public function column_value( $column );

    /**
     * Get our hooks into WordPress
     * 
     * This method should be overridden when adding new functionality in an extended class. When
     * doing so, ensure this method is called at the bottom of the overridden method via parent::init();
     * 
     * @since   1.0.0
     */
    public function init() {
        add_action( 'manage_posts_custom_column', array( $this, 'column_value' ) );
        add_action( 'dashboard_glance_items', array( $this, 'at_a_glance' ) );

        add_filter( 'manage_edit-' . $this->post_type_name . '_columns', array( $this, 'columns_define' ) );
        add_filter( 'post_updated_messages', array( $this, 'updated_messages' ) );
        add_filter( 'bulk_post_updated_messages', array( $this, 'bulk_updated_messages' ), 10, 2 );
    }

    /**
     * Change Post Updated messages.
     *
     * Internal function that modifies the custom post type names in updated messages.
     *
     * @since   1.0.0
     * @param	array			$messages			An array of post updated messages
     */
    public function updated_messages( $messages ) {
        // Get properties of the post type being configured
        $obj = get_post_type_object( $this->post_type_name );

        $singular = $obj->labels->singular_name;
        $post     = get_post();


        $messages[ $this->post_type_name ] = array(
            0  => '',
            1  => sprintf( __( '%s updated.', $this->textdomain ), $singular ),
            2  => __( 'Custom field updated.', $this->textdomain ),
            3  => __( 'Custom field deleted.', $this->textdomain ),
            4  => sprintf( __( '%s updated.', $this->textdomain ), $singular ),
            5  => isset( $_GET[ 'revision' ] ) ? sprintf( __( '%2$s restored to revision from %1$s', $this->textdomain ), wp_post_revision_title( (int) $_GET[ 'revision' ], false ), $singular ) : false,
            6  => sprintf( __( '%s updated.', $this->textdomain ), $singular ),
            7  => sprintf( __( '%s saved.', $this->textdomain ), $singular ),
            8  => sprintf( __( '%s submitted.', $this->textdomain ), $singular ),
            9  => sprintf( __( '%2$s scheduled for: <strong>%1$s</strong>.', $this->textdomain ), date_i18n( __( 'M j, Y @ G:i', $this->textdomain ), strtotime( $post->post_date ) ), $singular ),
            10 => sprintf( __( '%s draft updated.', $this->textdomain ), $singular ),
        );
        return $messages;
    }

    /**
     * Change Bulk updated messages
     *
     * Internal function that modifies the custom post type names in bulk updated messages
     *
     * @since   1.0.0
     * @param	array			$messages			An array of bulk updated messages
     */
    public function bulk_updated_messages( $bulk_messages, $bulk_counts ) {
        // Get properties of the post type being configured
        $obj = get_post_type_object( $this->post_type_name );

        $singular = $obj->labels->singular_name;
        $plural   = $obj->labels->name;

        $bulk_messages[ $this->post_type_name ] = array(
            'updated'   => _n( '%s ' . $singular . ' updated.', '%s ' . $plural . ' updated.', $bulk_counts[ 'updated' ] ),
            'locked'    => _n( '%s ' . $singular . ' not updated, somebody is editing it.', '%s ' . $plural . ' not updated, somebody is editing them.', $bulk_counts[ 'locked' ] ),
            'deleted'   => _n( '%s ' . $singular . ' permanently deleted.', '%s ' . $plural . ' permanently deleted.', $bulk_counts[ 'deleted' ] ),
            'trashed'   => _n( '%s ' . $singular . ' moved to the Trash.', '%s ' . $plural . ' moved to the Trash.', $bulk_counts[ 'trashed' ] ),
            'untrashed' => _n( '%s ' . $singular . ' restored from the Trash.', '%s ' . $plural . ' restored from the Trash.', $bulk_counts[ 'untrashed' ] ),
        );

        return $bulk_messages;
    }

    /**
     * Add the Post type to the "At a Glance" Dashboard Widget
     * 
     * Requires the Gamajo Dashboard Glancer class.
     * @see https://github.com/GaryJones/Gamajo-Dashboard-Glancer
     * 
     * @since   1.0.0
     */
    public function at_a_glance() {
        // Get properties of the post type being configured
        $obj = get_post_type_object( $this->post_type_name );

        // Gamajo class must exist and post type must be public
        if ( class_exists( 'Gamajo_Dashboard_Glancer' ) && $obj->public == 1 ) {
            $glancer = new Gamajo_Dashboard_Glancer;
            $glancer->add( $this->post_type_name );
        }
    }

}
