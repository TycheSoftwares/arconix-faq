=== Arconix FAQ ===
Contributors: jgardner03, tychesoftwares, shasvat
Tags: arconix, faq, toggle, accordion, faq plugin, frequently asked questions
Requires at least: 3.8
Tested up to: 6.5.5
Stable tag: trunk

Arconix FAQ provides an easy way to add FAQ items to your website.

== Description ==

Add an easy-to-create, stylish FAQ section to your website. Display your frequently asked questions using the supplied shortcode (`[faq]`) and show/hide them via an animated, jQuery toggle or accordion box.
The FAQ's can be displayed in groups by tagging them during the FAQ item's creation. They can also be loaded closed or open, and for long FAQ's, there's a checkbox to add a "return to top" link at the bottom.

[Live Demo](http://demo.tychesoftwares.com/faq/faq-plugin-demo/)
[Documentation](https://www.tychesoftwares.com/docs/docs/faq/)

> <strong>Easy to use and looks great</strong> Tried four other big name FAQ plugins before this one. All the others had show stopper issues. This one just worked and looks great.
> [jmiezitis](https://wordpress.org/support/topic/easy-to-use-and-looks-great-17/)

= Features =
* Custom Post-Type driven
* jQuery toggle or accordion display when using the shortcode
* Can be displayed individually, or in FAQ groups by using the "group" taxonomy

= Some of our Pro plugins =
1. **[Deposits plugin for WooCommerce](https://www.tychesoftwares.com/store/premium-plugins/deposits-for-woocommerce/?utm_source=wprepo&utm_medium=link&utm_campaign=FAQPlugin "Deposits plugin for WooCommerce")**

2. **[Order Delivery Date Pro for WooCommerce](https://www.tychesoftwares.com/store/premium-plugins/order-delivery-date-for-woocommerce-pro-21/?utm_source=wprepo&utm_medium=otherprolink&utm_campaign=FAQPlugin "Order Delivery Date Pro for WooCommerce")**

3. **[Abandoned Cart Pro for WooCommerce](https://www.tychesoftwares.com/store/premium-plugins/woocommerce-abandoned-cart-pro/?utm_source=wprepo&utm_medium=link&utm_campaign=FAQPlugin "Abandoned Cart Pro for WooCommerce")**

4. **[Booking & Appointment Plugin for WooCommerce](https://www.tychesoftwares.com/store/premium-plugins/woocommerce-booking-plugin/?utm_source=wprepo&utm_medium=link&utm_campaign=FAQPlugin "Booking & Appointment Plugin for WooCommerce")**

5. **[Product Delivery Date Pro for WooCommerce](https://www.tychesoftwares.com/store/premium-plugins/product-delivery-date-pro-for-woocommerce/?utm_source=wprepo&utm_medium=link&utm_campaign=FAQPlugin "Product Delivery Date Pro for WooCommerce")**



= Some of our other free plugins =

1. **[Order Delivery Date for WooCommerce - Lite](https://wordpress.org/plugins/order-delivery-date-for-woocommerce/ "Order Delivery Date for WooCommerce - Lite")**

2. **[Abandoned Cart for WooCommerce](https://wordpress.org/plugins/woocommerce-abandoned-cart/ "Abandoned Cart for WooCommerce")**

3. **[Product Delivery Date for WooCommerce - Lite](https://wordpress.org/plugins/product-delivery-date-for-woocommerce-lite/ "Product Delivery Date for WooCommerce � Lite")**

4. **[WooCommerce Print Invoice & Delivery Note](https://wordpress.org/plugins/woocommerce-delivery-notes/ "WooCommerce Print Invoice & Delivery Note")**

5. **[Order Delivery Date for WP e-Commerce](https://wordpress.org/plugins/order-delivery-date/ "Order Delivery Date for WP e-Commerce")**

6. **[Prevent Customers To Cancel WooCommerce Orders](https://wordpress.org/plugins/woo-prevent-cancel-order/ "Prevent Customers To Cancel WooCommerce Orders")**

7. **[WooCommerce Coupons by Categories and Tags](https://wordpress.org/plugins/woo-coupons-by-categories-and-tags/ "WooCommerce Coupons by Categories and Tags")**

8. **[Arconix Shortcodes](https://wordpress.org/plugins/arconix-shortcodes/ "Arconix Shortcodes")**

9. **[Arconix Flexslider](https://wordpress.org/plugins/arconix-flexslider/ "Arconix Flexslider")**

10. **[Arconix Portfolio](https://wordpress.org/plugins/arconix-portfolio/ "Arconix Portfolio")**

11. **[Arconix Testimonials](https://wordpress.org/plugins/arconix-testimonials/ "Arconix Testimonials")**

12. **[Export WordPress Menus](https://wordpress.org/plugins/wp-export-menus/ "Export WordPress Menus")**

== Installation ==

You can download and install Arconix FAQ using the built in WordPress plugin installer. If you download the plugin manually, make sure the files are uploaded to `/wp-content/plugins/arconix-faq/`.

Activate Arconix-FAQ in the "Plugins" admin panel using the "Activate" link.

== Upgrade Notice ==

Upgrade normally via your WordPress admin -> Plugins panel.

== Frequently Asked Questions ==

= Quick and dirty - how do I display my FAQ's? =
Use the `[faq]` shortcode in a widget or on a post/page. This will output the FAQ's using the default settings (Ascending order by Title in a Toggle configuration). If you'd like to use a different order, consult the [Documentation](https://www.tychesoftwares.com/docs/docs/faq/) for assistance.

= How do I enable the accordion display mode? =
Add `style="accordion"` to the shortcode, e.g. `[faq style="accordion"]`

= Where can I find more information on how to use the plugin?  =
* Visit the [documentation](https://www.tychesoftwares.com/docs/docs/faq/) for assistance

= The toggle or accordion isn't working. What can I do? =
While you can certainly start a thread in the [support forum](https://wordpress.org/support/plugin/arconix-faq), there are some troubleshooting steps you can take beforehand to help speed up the process.
1. Check to make sure the javascripts are loading correctly. Load the faq page in your browser and view your page's source. Look for jQuery and Arconix FAQ JS files there. If you don't see the Arconix FAQ JS file, then your theme's `header.php` file is likely missing `<?php wp_head(); ?>`, which is necessary for the operation of mine and many other plugins.
2. Check to make sure only one copy of jQuery is being loaded. Many times conflicts arise when themes or plugins load jQuery incorrectly, causing the script to be loaded multiple times in multiple versions. In order to find the offending item, start by disabling your plugins one by one until you find the problem. If you've disabled all your plugins, try switching to a different them, such as twentyten or twentytwelve to see if the problem is with your theme. Once you've found the problem, contact the developer for assistance getting the issue resolved.

= I need help =
Check out the WordPress [support forum](https://wordpress.org/support/plugin/arconix-faq)

= I have a great idea for your plugin! =
That's fantastic! Feel free to open an issue or submit a pull request over at [Github](https://github.com/vishalck/arconix-faq/), or you can contact me through [Twitter](https://twitter.com/tychesoftwares), [Facebook](https://www.facebook.com/tychesoftwares/) or my [Website](https://www.tychesoftwares.com)

== Screenshots ==
1. Post Type in WordPress navigation list
2. Post Type Admin display
3. Grouping and Toggling display

== Changelog ==

= 1.9.5 =
* Fix - Added a nonce check for Cross Site Request Forgery (CSRF) vulnerability on reset button.

= 1.9.4 =
* Tweak - Update compatibility with WordPress 6.5.
* Tweak - Update compatibility with WooCommerce 8.7.
* Fix - Cross Site Request Forgery (CSRF) vulnerability.

= 1.9.3 =
* Fix - Cross Site Request Forgery (CSRF) vulnerability.

= 1.9.2 =
* Compatibility with WordPress 5.5

= 1.9.1 =
* Fixed an issue where CRON for tracking was running every minute.

= 1.9 =
* Updated the CMB library to its latest version.
* Optimized the code with respect to WordPress Coding Standards.
 
= 1.8.3 =
* We have temporarily removed the dashboard widget from the plugin.

= 1.8.2 =
* Usage Tracking has been added in the plugin. It provides an option to allow tracking of the non-sensitive data of our plugin from the website. You can read more about it [here](https://www.tychesoftwares.com/docs/docs/faq/usage-tracking/).

= 1.8.1 =
* Bug Fixed - The style='accordion' for the faq shortcode was broken. This has been fixed.

= 1.8.0 =
* The plugin is now GDPR compliant.
* Bug Fixed – Some notices of debug log file are fixed.

= 1.7.0 =
* You can now exclude certain groups from the FAQs using the exclude_group attribute. The value of this attribute should be the slug of the group.
* Javascript and CSS files from the plugin will only be included when the shortcode is used on any page.
* You can now hide the titles of the groups using the hide_title attribute for the [faq] shortcode. The value should be true if you want to hide the titles else false.

= 1.6.1 =
Fixed a bug which caused the FAQ Group descriptions to output incorrectly when using the accordion style

= 1.6.0 =
* Prepared the plugin for [translations](https://make.wordpress.org/plugins/2015/09/01/plugin-translations-on-wordpress-org/) (yay!)
* Anchor links to group headers are now supported. The format is `faq-group-slug` -- aka mysite.com/faq/#faq-group-slug
* Allow users to now specify a single FAQ for display
* Fixed a bug which caused some of the FAQ-specific CSS to be output improperly

= 1.5.2 =
* Fixed a bug which caused an extra div to be output, breaking some site layouts.
* Fixed a typo when saving/updating FAQ's

= 1.5.1 =
Fixed a FAQ title display bug

= 1.5.0 =
* Added the ability to display the FAQ's in a single list even if groups are in use.
* FAQ's can now be displayed in a jQuery-powered accordion if desired
* A few other minor backend improvements and fixes.

= 1.4.3 =
Fixes a parameter bug that prevented users from restricting the number of FAQ's returned for display

= 1.4.2 =
Fix undefined variable bug that would show up when no FAQ groups were used

= 1.4.1 =
Updated the metabox script to the latest release. This resolves some content editor bugs that may pop up in certain configurations.

= 1.4.0 =
* Updated plugin to be better integrated with WP 3.8 dashboard
* Added support for 'menu order', which allows for custom ordering of FAQ items without the need for another plugin. Supports custom ordering within groups as well.
* Other miscellaneous improvements and bug fixes

= 1.3.2 =
Fixed a syntax error

= 1.3.1 =
* Resolves a conflict with themes from WooThemes that prevented users from inserting media into the editor
* Fixes an error with the Return to Top link introduced in 1.3.0

= 1.3.0 =
* Updated toggle script to be compatible with WordPress 3.6
* Added another checkbox to the FAQ creation screen which allows the user to set the initial state of the FAQ (closed or open)
* For compatibility reasons, the javascript file is loading in the page header as opposed to the footer
* The dashboard widget will now only load for administrator users
* Misc fixes and improvements

= 1.2.0 =
* Added a "Return to Top" meta box option for each FAQ. Checking that box will display a link (text overridable via filter) that will return the user to the top of that item
* Supports linking directly to specific FAQ's via the FAQ title. View the [documentation](http://arcnx.co/afwiki) for guidance
* Added additional CSS classes to each FAQ for increased styling options
* Added filters to allow users to prevent the CSS, JS or dashboard widget from loading
* Refactored defaults code to make it easier for power users to override defaults setting

= 1.1.1 =
minor fix to allow the user to specify a FAQ group they'd like to display

= 1.1 =
* Added a "group" taxonomy for displaying the FAQ items in groups
* Updated the CSS with more inline documentation
* fixed a couple minor bugs

= 1.0.5 =
* fixed a critical bug in the shortcode

= 1.0.4 =
* fixed a display bug which caused all faq's to be loaded before any content

= 1.0.3 =
* Show all FAQs and no longer paginate (overridable in the shortcode)
* fix broken dashboard widget

= 1.0.2 =
* version bump to add readme.txt and screenshots

= 1.0.1 =
* Initial Release
