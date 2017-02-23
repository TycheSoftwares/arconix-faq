/*
ARCONIX FAQ JS
--------------------------

PLEASE DO NOT make modifications to this file directly as it will be overwritten on update.
Instead, save a copy of this file to your theme directory. It will then be loaded in place
of the plugin's version and will maintain your changes on upgrade
*/
jQuery(document).ready(function(){
    
    // If the user sets the style to "accordion"
    jQuery('.arconix-faq-accordion-wrap').accordion( {
        collapsible: true,
        active: false,
        heightStyle: "content"
    });
    
    // Users sent to specific FAQ's will start with them open
    if(window.location.hash) {
        var hash = window.location.hash.substring(1);
        jQuery('#' + hash).addClass('faq-open').removeClass('faq-closed').next('.arconix-faq-content').addClass('faq-open').removeClass('faq-closed');
        
        // [WIP] This section not working properly
        jQuery('.arconix-faq-accordion-wrap').each( function() {
            this.find('#' + hash).trigger('click');
        })        
        //jQuery('.arconix-faq-accordion-wrap').find('a[href*='+ hash + ']').closest('.arconix-faq-accordion-content').trigger('click');
    }
    
    // This looks at the initial state of each content area, and hide content areas that are closed
    jQuery('.arconix-faq-content').each( function() {
        if( jQuery(this).hasClass('faq-closed')) {
            jQuery(this).hide();
        }
    });

    // This runs when a Toggle Title is clicked. It changes the CSS and then runs the animation
    jQuery('.arconix-faq-title').each( function() {
        jQuery(this).click(function() {
            var toggleContent = jQuery(this).next('.arconix-faq-content');

            jQuery(this).toggleClass('faq-open').toggleClass('faq-closed');
            toggleContent.toggleClass('faq-open').toggleClass('faq-closed');
            toggleContent.slideToggle();
        });
    });

    
});