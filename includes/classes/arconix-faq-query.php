<?php
/**
 * Query Class which extends WP_Query for FAQ's
 *
 * @author      John Gardner
 * @link        http://arconixpc.com/plugins/arconix-faq
 * @license     GPLv2 or later
 * @since       1.7.0
 */
class Arconix_FAQ_Query extends WP_Query {
    
    /**
     * Constructor
     * 
     * Takes incoming arguments and parses them against basic defaults. If $term_slug
     * has a value it adds a taxonomy query component, then passes it up to WP_Query
     * for further execution.
     * 
     * @since   1.7.0
     * @param   array       $args           Incoming query arguments
     * @param   string      $term_slug      Taxonomy term slug to query against
     */
    public function __construct( $args = array(), $term_slug = '' ) {
        
        // Form our taxonomy query args (if avail)
        $tax_args = $this->tax_query_args( $term_slug );
        
        // Merge with incoming and default args
        $args = wp_parse_args( $args, array(
            'post_type'         => 'faq',
            'posts_per_page'    => -1,
            'no_found_rows'     => true,
            'order'             => 'ASC',
            'orderby'           => 'title',
        ) );
        
        if ( $tax_args ) {
            $args += $tax_args;
        }
        
        parent::__construct( $args );
    }
    
    /**
     * Taxonomy Query Setup
     * 
     * Build our taxonomy query if it is being called for.
     * 
     * @since   1.7.0
     * @param   string      $slug       Taxonomy term slug to pass into the query
     * @return  array                   Empty array if $slug is empty or completed taxonomy query array
     */
    protected function tax_query_args( $slug ) {
        if ( empty( $slug ) ) 
            return;
        
        $tax_query = array( 'tax_query' => array ( 
                array(
                    'taxonomy'  => 'group',
                    'field'     => 'slug',
                    'terms'     => array( $slug ),
                    'operator'  => 'IN'
                ) 
            ) );
        
        return $tax_query;
    }

}
