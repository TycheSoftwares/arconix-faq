<?php
/**
 * Class for Registering Custom Taxonomies
 * 
 * New Taxonomies are added by instantiating the class and passing the necessary arguments
 * to the 'add' function.
 * 
 * @license GPL-2.0+
 * @version 1.0.0
 */
class Arconix_Taxonomy_Register {
    
    /**
     * Taxonomy Name
     * 
     * @since   1.0.0
     * @var     string          $taxonomy_name      Single name of the taxonomy to register.
     */
    protected $taxonomy_name;
	
	/**
     * Post Type Name
     * 
     * @since   1.0.0
     * @var		string			$post_type_name     Name of the Custom Post Type the taxonomy is to be registered to.
     */
	protected $post_type_name;
	
	/**
     * Holds the singular name of the taxonomy. This is a human
     * friendly name, capitalized with spaces.
     *
     * @since   1.0.0
     * @var		string			$singular			Taxonomy singular name. 
     */
	protected $singular;
    
    /**
     * Holds the plural name of the taxonomy. This is a human
     * friendly name, capitalized with spaces.
     *
     * @since   1.0.0
     * @var		string			$plural				Taxonomy plural name.
     */
	protected $plural;
	
	/**
     * Taxonomy registration labels.
     *
     * @since   1.0.0
     * @var		array			$labels				Taxonomy registration labels.
     */
	protected $labels;
	
	/**
     * Remaining arguments for Taxonomy registration.
     *
     * @since   1.0.0
     * @var		array			$settings			Taxonomy registration settings.
     */
	protected $settings;

	/**
     * Constructor
     * 
     * Load Necessary WordPress hooks to register the taxonomy.
     * 
     * @since   1.0.0
     */
	public function __construct() {		
		add_action( 'init',		array( $this, 'register' ),		20 );
	}
	
	/**
     * Add a Taxonomy to register
     * 
     * @since   1.0.0
     * @param   string|array    $taxonomy_names         Name of the taxonomy to register as a string or an array of names 
     *                                                  containing the taxonomy name along with singular and plural forms
     * @param	string          $post_type_name         Name of the post type to link to the taxonomy or if left null the 
     *                                                  taxonomy will be registered only
     * @param	array           $settings               Additional taxonomy registration settings
     *                                                  (see https://codex.wordpress.org/Function_Reference/register_taxonomy#Arguments )
     * @return	void                                    Return early if no taxonomy name was provided
     */
    public function add( $taxonomy_names, $post_type_name = null, $settings = array() ) {
		// Bail if the taxonomy_name hasn't been set
		if ( !isset( $taxonomy_names ) )
			return;
		
		$this->set_taxonomy_names( $taxonomy_names );
        $this->post_type_name = $post_type_name;
        $this->labels = $this->set_labels();
		$this->settings = $this->set_settings( $settings );
	}
	
	/**
     * Register the Taxonomy
     * 
     * Creates an array of the labels and settings class vars and then registers the taxonomy.
     * 
     * @since   1.0.0
     */
	public function register() {
		// Array of the labels and settings for the CPT
        $args = array_merge( $this->settings, $this->labels );

        register_taxonomy( $this->taxonomy_name, $this->post_type_name, $args );
        
        // Better to be safe than sorry according to WP wiki
        if ( isset( $this->post_type_name ) )
            register_taxonomy_for_object_type( $this->taxonomy_name, $this->post_type_name );
	}
	
	/**
     * Assign the Taxonomy registration settings.
     * 
     * If no array is passed then the only setting will be to make the taxonomy show in the admin.
     * 
     * @since   1.0.0
     * @param	array           $settings               Taxonomy settings
     * @return	array                                   Array of taxonomy settings merged with defaults
     */
	protected function set_settings( $settings = array() ) {
		// Set the tax to show in the post type admin column by default
		$defaults = array(
			'show_admin_column' => true,
		);
		
		// Combine the default settings with the incoming settings and return
		return array_replace_recursive( $defaults, $settings );
	}
	
	/**
     * Set the taxonomy labels.
     * 
     * @since   1.0.0
     * @return	array           $labels                 Taxonomy labels
     */
	protected function set_labels() {
        
		$singular = $this->singular;
		$plural = $this->plural;
		
		$labels = array( 'labels' => array (
			'name'                  => sprintf( __( '%s', 'arconix-faq' ), $plural ),
			'singular_name'         => sprintf( __( '%s', 'arconix-faq' ), $singular ),
			'menu_name'             => sprintf( __( '%s', 'arconix-faq' ), $plural ),
			'all_items'             => sprintf( __( '%s', 'arconix-faq' ), $plural ),
			'add_new'               => __( 'Add New', 'arconix-faq' ),
			'add_new_item'          => sprintf( __( 'Add New %s', 'arconix-faq' ), $singular ),
			'edit_item'             => sprintf( __( 'Edit %s', 'arconix-faq' ), $singular ),
			'new_item'              => sprintf( __( 'New %s', 'arconix-faq' ), $singular ),
			'view_item'             => sprintf( __( 'View %s', 'arconix-faq' ), $singular ),
			'search_items'          => sprintf( __( 'Search %s', 'arconix-faq' ), $plural ),
			'not_found'             => sprintf( __( 'No %s found', 'arconix-faq' ), $plural ),
			'not_found_in_trash'	=> sprintf( __( 'No %s found in Trash', 'arconix-faq' ), $plural ),
			'parent_item_colon'     => sprintf( __( 'Parent %s:', 'arconix-faq' ), $singular ),
            'parent_item'           => sprintf( __( 'Parent %s', 'arconix-faq' ), $singular ),
		) );
        
		return $labels;
	}
	
	/**
     * Set the Taxonomy names
     * 
     * @since   1.0.0
     * @param	string|array	$taxonomy_names        Name of the taxonomy or array of taxonomy conditional names.
     */
	protected function set_taxonomy_names( $taxonomy_names ) {
		// Check if all the post_type_names have been supplied
		if ( is_array( $taxonomy_names ) ) {
			
            // Set the taxonomy name
			$this->taxonomy_name = $taxonomy_names['taxonomy_name'];
            
			$name_types = array( 'singular', 'plural', 'slug' );
			
			// Loop through types of names and assign the correct value
			foreach ( $name_types as $name) {
				
				// If the type has been set by the user
				if ( isset( $taxonomy_names[$name] ) ) {
					// Use that setting
					$this->$name = $taxonomy_names[$name];
				}
                else {
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
		} 
        else {
            // $post_type_name is just a string so we must generate the other names
			$this->taxonomy_name = $taxonomy_names;
			$this->singular = $this->get_singular();
			$this->plural = $this->get_plural();
		}
	}
	

	/**
     * Get singular
     *
     * Returns the human friendly singular name.
     * 
     * @since   1.0.0
     * @param	string			$name				The slug name you want to unpluralize.
     * @return	string								The friendly singular name.
     */
	protected function get_singular( $name = null ) {
		// If no name is passed the post_type_name is used.
		if ( !isset( $name ) ) {
            
            $name = $this->taxonomy_name;
                        
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
     * @param	string			$name				The slug name you want to pluralize.
     * @return	string								The friendly pluralized name.
     */
	protected function get_plural( $name = null ) {
		// If no name is passed the taxonomy_names is used.
		if ( !isset( $name ) )
			$name = $this->get_singular( $this->taxonomy_name );
		
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
     * @param	string			$name				The name you want to make friendly.
     * @return	string								The human friendly name.
     */
	protected function get_human_friendly( $name = null ) {
		// If no name is passed the taxonomy_name is used.
		if ( !isset( $name ) )
			$name = $this->taxonomy_name;
		
		// Return human friendly name.
		return ucwords( strtolower( str_replace( "-", " ", str_replace( "_", " ", $name ) ) ) );
	}

}