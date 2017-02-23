<?php

/**
 * Class for Registering Custom Post Types
 * 
 * New Post Types are added by instantiating the class and passing the necessary arguments
 * to the 'add' function. Users should search/replace 'textdomain' with their own plugin-specific domain
 *
 * @license GPL-2.0+
 * @version 1.0.0
 */
class Arconix_CPT_Register {

    /**
     * Post Type Name
     *
     * @since   1.0.0
     * @var     string      $post_type_name     Name of the Custom Post Type.
     */
    protected $post_type_name;

    /**
     * Holds the singular name of the post type. This is a human
     * friendly name, capitalized with spaces.
     *
     * @since   1.0.0
     * @var     string      $singular       Post type singular name. 
     */
    protected $singular;

    /**
     * Holds the plural name of the post type. This is a human
     * friendly name, capitalized with spaces.
     *
     * @since   1.0.0
     * @var     string      $plural     Post type plural name.
     */
    protected $plural;

    /**
     * Custom Post Type registration labels.
     *
     * @since   1.0.0
     * @var     array       $labels     Post Type registration labels.
     */
    protected $labels;

    /**
     * Additional settings for post type registration.
     *
     * @since   1.0.0
     * @var     array       $settings       Post Type registration settings.
     */
    protected $settings;

    /**
     * Constructor
     * 
     * Load Necessary WordPress hooks to register the custom post type.
     * 
     * @since   1.0.0
     */
    public function __construct() {
        add_action( 'init', array( $this, 'register' ), 20 );
    }

    /**
     * Add a new Custom Post Type
     * 
     * @since   1.0.0
     * @param   string|array	$post_type_names        Single post type name or array of post type names
     *                                              	including singular and plural options
     * @param   array           $settings               Array of additional post type registration settings
     *                                                  (see https://codex.wordpress.org/Function_Reference/register_post_type#Arguments )
     * @return  void                                    Return early if no valid post types were provided
     */
    public function add( $post_type_names, $settings = array() ) {
        // Bail if the post type name hasn't been set
        if ( !isset( $post_type_names ) )
            return;

        $this->set_post_type_names( $post_type_names );
        $this->labels   = $this->set_labels();
        $this->settings = $this->set_settings( $settings );
    }

    /**
     * Register the Custom Post Type
     * 
     * Creates an array of the labels and settings class vars and then registers the custom post type.
     * 
     * @since   1.0.0
     */
    public function register() {
        // Array of the labels and settings for the CPT
        $args = array_merge( $this->settings, $this->labels );

        // Register our new custom post type
        register_post_type( $this->post_type_name, $args );

        flush_rewrite_rules();
    }

    /**
     * Assign the Custom Post Type registration settings
     * 
     * @since   1.0.0
     * @param   array       $settings       Post Type settings. Default is a public post type
     * @return  array                       Array of Post Type settings merged with defaults
     */
    protected function set_settings( $settings = array() ) {
        // Set the post type to public by default
        $defaults = array(
            'public' => true,
        );

        // Combine the default settings with the incoming settings and return
        return array_replace_recursive( $defaults, $settings );
    }

    /**
     * Set the Custom Post Type labels.
     * 
     * @since   1.0.0
     * @return  array       $labels     Post Type labels
     */
    protected function set_labels() {

        $singular = $this->singular;
        $plural   = $this->plural;

        $labels = array( 'labels' => array(
                'name'               => sprintf( __( '%s', 'arconix-faq' ), $plural ),
                'singular_name'      => sprintf( __( '%s', 'arconix-faq' ), $singular ),
                'menu_name'          => sprintf( __( '%s', 'arconix-faq' ), $plural ),
                'all_items'          => sprintf( __( '%s', 'arconix-faq' ), $plural ),
                'add_new'            => __( 'Add New', 'arconix-faq' ),
                'add_new_item'       => sprintf( __( 'Add New %s', 'arconix-faq' ), $singular ),
                'edit_item'          => sprintf( __( 'Edit %s', 'arconix-faq' ), $singular ),
                'new_item'           => sprintf( __( 'New %s', 'arconix-faq' ), $singular ),
                'view_item'          => sprintf( __( 'View %s', 'arconix-faq' ), $singular ),
                'search_items'       => sprintf( __( 'Search %s', 'arconix-faq' ), $plural ),
                'not_found'          => sprintf( __( 'No %s found', 'arconix-faq' ), $plural ),
                'not_found_in_trash' => sprintf( __( 'No %s found in Trash', 'arconix-faq' ), $plural ),
                'parent_item_colon'  => sprintf( __( 'Parent %s:', 'arconix-faq' ), $singular )
            ) );

        return $labels;
    }

    /**
     * Set the Custom Post Type names
     * 
     * Uses the user-defined name, if available, and falls back to generating the name
     * programatically if not.
     * 
     * @since   1.0.0
     * @param   string|array	$post_type_names        Name of the post type or array of post type conditional names.
     */
    protected function set_post_type_names( $post_type_names ) {
        // Check if all the post_type_names have been supplied
        if ( is_array( $post_type_names ) ) {

            // Set the base post type name
            $this->post_type_name = $post_type_names[ 'post_type_name' ];

            $name_types = array( 'singular', 'plural' );

            // Loop through types of names and assign the correct value
            foreach ( $name_types as $name ) {

                // If the type has been set by the user
                if ( isset( $post_type_names[ $name ] ) ) {
                    // Use that setting
                    $this->$name = $post_type_names[ $name ];
                } else {
                    // Otherwise set the names ourselves
                    switch ( $name ) {
                        case 'singular':
                            $this->singular = $this->get_singular();
                            break;

                        case 'plural':
                            $this->plural = $this->get_plural();
                            break;

                        default:
                            break;
                    }
                }
            }
        } else {
            // $post_type_name is just a string so we must generate the other names
            $this->post_type_name = $post_type_names;
            $this->singular       = $this->get_singular();
            $this->plural         = $this->get_plural();
        }
    }

    /**
     * Get singular
     *
     * Returns the human friendly singular name.
     *
     * @since   1.0.0
     * @param   string      $name       The name you want to unpluralize.
     * @return  string                  The friendly singular name.
     */
    protected function get_singular( $name = null ) {
        // If no name is passed the post_type_name is used.
        if ( !isset( $name ) ) {

            $name = $this->post_type_name;

            if ( substr( $name, -1 ) == "s" )
                $name = substr( $name, 0, -1 );
        }
        else {
            $name = $this->singular;
        }

        return $this->get_human_friendly( $name );
    }

    /**
     * Get plural
     *
     * Returns the human friendly plural name.
     *
     * @since   1.0.0
     * @param   string      $name       The name you want to pluralize.
     * @return  string                  The friendly pluralized name.
     */
    protected function get_plural( $name = null ) {
        // If no name is passed the post_type_name is used.
        if ( !isset( $name ) )
            $name = $this->get_singular( $this->post_type_name );

        // Return the plural name. Add 's' to the end.
        return $this->get_human_friendly( $name ) . 's';
    }

    /**
     * Get human friendly
     *
     * Returns the human friendly name.
     *
     *    ucwords      Capitalize words
     *    strtolower   Makes string lowercase before capitalizing
     *    str_replace  Replace all instances of hyphens and underscores to spaces
     *
     * @since   1.0.0
     * @param   string      $name       The name you want to make friendly.
     * @return  string                  The human friendly name.
     */
    protected function get_human_friendly( $name = null ) {
        // If no name is passed the post_type_name is used.
        if ( !isset( $name ) )
            $name = $this->post_type_name;

        // Return human friendly name.
        return ucwords( strtolower( str_replace( "-", " ", str_replace( "_", " ", $name ) ) ) );
    }

}
