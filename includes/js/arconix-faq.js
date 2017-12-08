/*
ARCONIX FAQ JS
--------------------------

PLEASE DO NOT make modifications to this file directly as it will be overwritten on update.
Instead, save a copy of this file to your theme directory. It will then be loaded in place
of the plugin's version and will maintain your changes on upgrade
*/
jQuery(document).ready( function(){
    // If the user sets the style to "accordion"
    var $accordions = jQuery('.arconix-faq-accordion-wrap');

    $accordions.accordion({
        collapsible: true,
        active: false,
        heightStyle: "content",
        event: "click",
        beforeActivate: function(event, ui) {
            _this = this;

            $accordions.each(function(i) {
                if (this !== _this) {
                    // Close any open FAQ entries.
                    jQuery(this).accordion("option", "active", false);
                }
            });
        },
        activate: function(event, ui) {
            if (ui.newHeader.length) {
                var extraOffset = 0;

                // Handle WordPress adminbar.
                $adminbar = jQuery("#wpadminbar");
                if ($adminbar.length) {
                    extraOffset = - $adminbar.height();
                }

                // Ensure that FAQ is visible.
                jQuery('html, body').animate({
                    scrollTop: ui.newHeader.offset().top + extraOffset
                }, 'fast');
            }
        },
    });

    // Users sent to specific FAQ's will start with them open
    if (window.location.hash) {
        var $panel = jQuery('#' + window.location.hash.substring(1));
        var index  = $panel.parent().children(".ui-accordion-header").index($panel);

        // Open linked FAQ entry.
        $panel.closest(".arconix-faq-accordion-wrap").accordion("option", "active", index);
    }
});
