<?php

class Arconix_FAQ_Shortcode {

	/**
	 * Shortcode attributes
	 * @var array
	 * @access private
	 */
	private $atts = array();

	/**
	 * Default shortcode attribute values
	 * @var array
	 * @access private
	 */
	private $defaults = array();

	/**
	 * Constructor
	 *
	 * @access public
	 * @param array $atts Shortcode attributes
	 * @return Arconix_FAQ
	 */
	function __construct( $atts ) {
		
		$defaults = array(
			'order'				=> 'ASC',
			'orderby'			=> 'title',
			'posts_per_page'	=> -1,
			'group'				=> '',			# group to display (empty for all)
			'skip_group'		=> false,		# arrange by group (true) or display all (false)
			'style'				=> 'toggle',	# accordion / toggle / list
			'faq_page'			=> ''			# slug/id of the FAQ page to link to, empty to use current page
		);

		// Filter defaults
		$this->defaults = apply_filters( 'arconix_faq_query_defaults', $defaults );

		// Parse shortcode attributes
		$this->atts = shortcode_atts( $this->defaults, $atts );
	}

	/**
	 * Get our FAQ data
	 *
	 * @access public
	 * @param boolean $echo True to echo shortcode output, false to return output
	 * @return string 
	 */
	function display( $echo = false ) {

		// HTML output container
		$html = '';

		// Get the taxonomy terms assigned to all FAQs
		$terms = get_terms( 'group' );

		// Are we skipping the group check?
		$skip_group = $this->atts['skip_group'];

		// Display style
		$style = $this->atts['style'];

		// If there are any terms being used, loop through each one to output the relevant FAQ's, else just output all FAQs
		if ( ! empty( $terms ) && $skip_group == false ) {

			foreach ( $terms as $term ) {

				// If a user sets a specific group in the params, that's the only one we care about
				$group = $this->atts['group'];
				if ( isset( $group ) && $group != '' && $term->slug != $group )
					continue;

				// New query just for the tax term we're looping through
				$q = new WP_Query( array(
					'post_type'         => 'faq',
					'order'             => $this->atts['order'],
					'orderby'           => $this->atts['orderby'],
					'posts_per_page'    => $this->atts['posts_per_page'],
					'tax_query'         => array(
						array(
							'taxonomy'  => 'group',
							'field'     => 'slug',
							'terms'     => array( $term->slug ),
							'operator'  => 'IN'
						)
					)
				) );

				if ( $q->have_posts() ) {

					// Rendered FAQ items container
					$items_html = '';

					// Loop through the rest of the posts for the term
					while ( $q->have_posts() ) { 
						$q->the_post();

						switch ( $style ) {
							case 'accordion':
								$items_html .= $this->accordion_output();
								break;
							case 'list':
								$items_html .= $this->list_output();
								break;
							case 'toggle':
							default:
								$items_html .= $this->toggle_output();
								break;
						}
					} // end while (posts loop)

					// Term title
					$html .= '<h3 class="arconix-faq-term-title arconix-faq-term-' . $term->slug . '">' . $term->name . '</h3>';

					// Term description
					$term_description = $term->description ? '<p class="arconix-faq-term-description">' . $term->description . '</p>' : '';

					// Format the html
					switch ( $style ) {
						case 'accordion':
							$html .= sprintf('<div class="arconix-faq-accordion-wrap">%2%1</div>', $items_html, $term_description);
							break;
						case 'list':
							$html .= sprintf('%2<ul class="arconix-faq-list-wrap">%1</ul>', $items_html, $term_description);
							break;
						default:
							$html .= sprintf('%2%1', $items_html, $term_description);
							break;
					}

				} // end have_posts()

				wp_reset_postdata();
			} // end foreach
		} // End if( $terms )
		else { // If $terms is blank (faq groups aren't in use) or $skip_group is true

			// Set up our standard query args.
			$q = new WP_Query( array(
				'post_type'         => 'faq',
				'order'             => $this->atts['order'],
				'orderby'           => $this->atts['orderby'],
				'posts_per_page'    => $this->atts['posts_per_page']
			) );

			if ( $q->have_posts() ) {

				// Rendered FAQ items container
				$items_html = '';

				while ( $q->have_posts() ) {
					$q->the_post();

					// Render all FAQ items
					switch ( $style ) {
						case 'accordion':
							$items_html .= $this->accordion_output();
							break;
						case 'list':
							$items_html .= $this->list_output();
							break;
						case 'toggle':
						default:
							$items_html .= $this->toggle_output();
							break;
					}
				} // end while (posts loop)

				// Format the html
				switch ( $style ) {
					case 'accordion':
						$html = sprintf('<div class="arconix-faq-accordion-wrap">%1$s</div>', $items_html);
						break;
					case 'list':
						$html = sprintf('<ul class="arconix-faq-list-wrap">%1$s</ul>', $items_html);
						break;
					default:
						$html = $items_html;
						break;
				}

			} // end have_posts()

			wp_reset_postdata();
		}

		// Allow complete override of the FAQ content
		$html = apply_filters( 'arconix_faq_return', $html );

		if ( $echo === true )
			echo $html;
		else
			return $html;
	}

	/**
	 * Accordion output
	 *
	 * @access private
	 * @return string HTML accordion view of the current FAQ item in the loop
	 */
	private function accordion_output() {

		// Set up our anchor link
		$link = 'faq-' . sanitize_html_class( get_the_title() );

		// Format output
		$html = vsprintf(
			'<div id="faq-%1$d" class="arconix-faq-accordion-title %2$s">%2$s</div>' . 
			'<div id="%3$s" class="arconix-faq-accordion-content">%4$s%5$s</div>',
			array(
				get_the_id(),
				get_the_title(),
				$link,
				apply_filters( 'the_content', get_the_content() ),
				$this->return_to_top( $link )
			)
		);

		// Allows a user to completely overwrite the output
		$html = apply_filters( 'arconix_faq_accordion_output', $html );

		return $html;
	}

	/**
	 * Toggle output
	 *
	 * @access private
	 * @return string HTML toggle view of the current FAQ item in the loop
	 */
	private function toggle_output() {

		// Grab our metadata
		$lo = get_post_meta( get_the_id(), '_acf_open', true );

		// If Open on Load checkbox is true
		$lo == true ? $lo = ' faq-open' : $lo = ' faq-closed';

		// Set up our anchor link
		$link = 'faq-' . sanitize_html_class( get_the_title() );

		// Format output
		$html = vsprintf(
			'<div id="faq-%1$d" class="arconix-faq-wrap">' .
			'	<div id="%3$s" class="arconix-faq-title %6$s">%2$s</div>' .
			'	<div class="arconix-faq-content %6$s">%4$s%5$s</div>' .
			'</div>',
			array(
				get_the_id(),
				get_the_title(),
				$link,
				apply_filters( 'the_content', get_the_content() ),
				$this->return_to_top( $link ),
				$lo
			)
		);

		// Allows a user to completely overwrite the output
		$html = apply_filters( 'arconix_faq_toggle_output', $html );

		return $html;
	}

	/**
	 * List output
	 * Displays titles with link to full faq article only. Defaults by linking
	 * to current page, use faq_page argument to set external page.
	 *
	 * @access private
	 * @return string HTML list view of the current FAQ item in the loop
	 */
	private function list_output() {

		// Anchor link
		$link = "#faq-" . get_the_id();

		// Replace anchor link if faq page slug/id is set other than the current page
		if(!empty($this->atts['faq_page'])) {
			if(is_numeric($this->atts['faq_page'])) {
				$link = sprintf('%1$s#faq-%2$d', get_permalink($this->atts['faq_page']), get_the_id());
			}
			else {
				$faq_page = get_page_by_path($this->atts['faq_page']);
				if($faq_page) {
					$link = sprintf('%1$s#faq-%2$d', get_permalink($faq_page->ID), get_the_id());
				}
			}
		}

		// Format output
		$html = vsprintf(
			'<li id="faq-%1$d" class="arconix-faq-wrap"><a href="%3$s">%2$s</a></li>',
			array(
				get_the_id(),
				get_the_title(),
				$link
			)
		);

		return $html;
	}

	/**
	 * Return to top link
	 *
	 * @access private
	 * @param string $link Link to top of FAQ item
	 * @return string Return to top link HTML
	 */
	private function return_to_top( $link ) {
		$html = '';

		// Grab our metadata
		$rtt = get_post_meta( get_the_id(), '_acf_rtt', true );

		// If Return to Top checkbox is true
		if ( $rtt && $link ) {
			$rtt_text = __( 'Return to Top', 'acf' );
			$rtt_text = apply_filters( 'arconix_faq_return_to_top_text', $rtt_text );

			$html = '<div class="arconix-faq-to-top"><a href="#' . $link . '">' . $rtt_text . '</a></div>';
		}

		return $html;
	}
}
