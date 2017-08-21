<?php
/**
 * Class to handle the output of the FAQ items
 *
 * @since   1.2.0
 */
class Arconix_FAQ_Display {

    /**
     * Array of query defaults
     *
     * @since   1.6.0
     * @access  protected
     * @var     array       $defaults    Plugin query defaults
     */
    protected $defaults;

    /**
     * Initialize the class and set its properties.
     *
     * @since   1.6.0
     */
    public function __construct() {
        $this->defaults = array(
            'p'                 => '',
            'order'             => 'ASC',
            'orderby'           => 'title',
            'skip_group'        => false,
            'style'             => 'toggle',
            'posts_per_page'    => -1,
            'nopaging'          => true,
            'group'             => ''
        );
    }

    /**
     * Get plugin query defaults
     *
     * @since   1.6.0
     * @return  array       filterable query defaults
     */
    public function getdefaults() {
        return apply_filters( 'arconix_faq_defaults', $this->defaults );
    }

    /**
     * Get our FAQ data
     *
     * @since   1.2.0
     * @version 1.6.1
     *
     * @param   array   $args
     * @param   bool    $echo   Echo or Return the data
     *
     * @return  string          FAQ information for display
     */
    public function loop( $args, $echo = false ) {
        // Merge incoming args with the class defaults
        $args = wp_parse_args( $args, $this->getdefaults() );

        // Container
        $html = '';

        // Get the taxonomy terms assigned to all FAQs
        $terms = get_terms( 'group' );

        // Are we skipping the group check?
        $skip_group = $args['skip_group'];

        // Do we have an accordion?
        $args['style'] == 'accordion' ? $accordion = true : $accordion = false;


        // If there are any terms being used, loop through each one to output the relevant FAQ's, else just output all FAQs
        if ( ! empty( $terms ) && $skip_group == false && empty( $args['p'] ) ) {

            foreach ( $terms as $term ) {

                // If a user sets a specific group in the params, that's the only one we care about
                $group = $args['group'];
                if ( isset( $group ) && $group != '' && $term->slug != $group )
                    continue;

                // Set up our standard query args.
                $query_args = array(
                    'post_type'         => 'faq',
                    'order'             => $args['order'],
                    'orderby'           => $args['orderby'],
                    'posts_per_page'    => $args['posts_per_page'],
                    'tax_query'         => array(
                        array(
                            'taxonomy'  => 'group',
                            'field'     => 'slug',
                            'terms'     => array( $term->slug ),
                            'operator'  => 'IN'
                        )
                    )
                );

                // New query just for the tax term we're looping through
                $q = new WP_Query( $query_args );

                if ( $q->have_posts() ) {

                    $html .= '<h3 id="faq-' . $term->slug . '" class="arconix-faq-term-title arconix-faq-term-' . $term->slug . '">' . $term->name . '</h3>';

                    // If the term has a description, show it
                    if ( $term->description )
                        $html .= '<p class="arconix-faq-term-description">' . $term->description . '</p>';

                    // Output the accordion wrapper if that style has been set
                    if ( $accordion )
                        $html .= '<div class="arconix-faq-accordion-wrap">';

                    // Loop through the rest of the posts for the term
                    while ( $q->have_posts() ) : $q->the_post();

                        if ( $accordion )
                            $html .= $this->accordion_output();
                        else
                            $html .= $this->toggle_output();

                    endwhile;

                    // Close the accordion wrapper if necessary
                    if ( $accordion )
                        $html .= '</div>';

                } // end have_posts()

                wp_reset_postdata();
            } // end foreach
        } // End if( $terms )
        else { // If $terms is blank (faq groups aren't in use) or $skip_group is true

            // Set up our standard query args.
            $q = new WP_Query( array(
                'post_type'         => 'faq',
                'p'                 => $args['p'],
                'order'             => $args['order'],
                'orderby'           => $args['orderby'],
                'posts_per_page'    => $args['posts_per_page']
            ) );


            if ( $q->have_posts() ) {

                if ( $accordion )
                    $html .= '<div class="arconix-faq-accordion-wrap">';

                while ( $q->have_posts() ) : $q->the_post();

                    if ( $accordion )
                        $html .= $this->accordion_output();
                    else
                        $html .= $this->toggle_output();

                endwhile;

                if ( $accordion )
                    $html .= '</div>';
            } // end have_posts()

            wp_reset_postdata();
        }

        // Allow complete override of the FAQ content
        $html = apply_filters( 'arconix_faq_return', $html, $args );

        if ( $echo === true )
            echo $html;
        else
            return $html;
    }

    /**
     * Output the FAQs in an accordion style
     *
     * @since   1.5.0
     * @version 1.6.0
     * @param   bool    $echo       echo or return the results
     * @return  string  $html     FAQs in an accordion configuration
     */
    private function accordion_output( $echo = false ) {
        $html = '';

        // Set up our anchor link
        $link = 'faq-' . sanitize_html_class( get_the_title() );

        $html .= '<div id="faq-' . get_the_id() . '" class="arconix-faq-accordion-title">';
        $html .= get_the_title() . '</div>';
        $html .= '<div id="' . $link . '" class="arconix-faq-accordion-content">' . apply_filters( 'the_content', get_the_content() );
        $html .= $this->return_to_top( $link );
        $html .= '</div>';

        // Allows a user to completely overwrite the output
        $html = apply_filters( 'arconix_faq_accordion_output', $html );

        if ( $echo === true )
            echo $html;
        else
            return $html;
    }

    /**
     * Output the FAQs in a toggle style
     *
     * @since   1.5.0
     * @param   bool    $echo       echo or return the results
     * @return  string  $html     FAQs in a toggle configuration
     */
    private function toggle_output( $echo = false ) {
        $html = '';

        // Grab our metadata
        $lo = get_post_meta( get_the_id(), '_acf_open', true );

        // If Open on Load checkbox is true
        $lo == true ? $lo = ' faq-open' : $lo = ' faq-closed';

        // Set up our anchor link
        $link = 'faq-' . sanitize_html_class( get_the_title() );

        $html .= '<div id="faq-' . get_the_id() . '" class="arconix-faq-wrap">';
        $html .= '<div id="' . $link . '" class="arconix-faq-title' . $lo . '">' . get_the_title() . '</div>';
        $html .= '<div class="arconix-faq-content' . $lo . '">' . apply_filters( 'the_content', get_the_content() );

        $html .= $this->return_to_top( $link );

        $html .= '</div>'; // faq-content
        $html .= '</div>'; // faq-wrap

        // Allows a user to completely overwrite the output
        $html = apply_filters( 'arconix_faq_toggle_output', $html );

        if ( $echo === true )
            echo $html;
        else
            return $html;
    }

    /**
     * Provide a hyperlinked url to return to the top of the current FAQ
     *
     * @since   1.5.0
     * @param   string  $link       The faq link to be hyperlinked
     * @param   bool    $echo       Echo or return the results
     * @return  string  $html     Hyperlinked "Return to Top" link
     */
    private function return_to_top( $link, $echo = false ) {
        $html = '';

        // Grab our metadata
        $rtt = get_post_meta( get_the_id(), '_acf_rtt', true );

        // If Return to Top checkbox is true
        if ( $rtt && $link ) {
            $rtt_text = __( 'Return to Top', 'arconix-faq' );
            $rtt_text = apply_filters( 'arconix_faq_return_to_top_text', $rtt_text );

            $html .= '<div class="arconix-faq-to-top"><a href="#' . $link . '">' . $rtt_text . '</a></div>';
        }

        if ( $echo === true )
            echo $html;
        else
            return $html;
    }

}
