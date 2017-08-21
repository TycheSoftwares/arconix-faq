=== Arconix FAQ ===
Contributors: jgardner03
Donate link: http://arcnx.co/acfdonation
Tags: arconix, faq, toggle, accordion, faq plugin, frequently asked questions
Requires at least: 3.8
Tested up to: 4.4
Stable tag: 1.6.1

Arconix FAQ provides an easy way to add FAQ items to your website.

== Description ==

Add an easy-to-create, stylish FAQ section to your website. Display your frequently asked questions using the supplied shortcode (`[faq]`) and show/hide them via an animated, jQuery toggle or accordion box.
The FAQ's can be displayed in groups by tagging them during the FAQ item's creation. They can also be loaded closed or open, and for long FAQ's, there's a checkbox to add a "return to top" link at the bottom.

[Live Demo](http://demo.arconixpc.com/arconix-faq)
[Documentation](http://arcnx.co/afwiki)

= Features =
* Custom Post-Type driven
* jQuery toggle or accordion display when using the shortcode
* Can be displayed individually, or in FAQ groups by using the "group" taxonomy

== Installation ==

You can download and install Arconix FAQ using the built in WordPress plugin installer. If you download the plugin manually, make sure the files are uploaded to `/wp-content/plugins/arconix-faq/`.

Activate Arconix-FAQ in the "Plugins" admin panel using the "Activate" link.

== Upgrade Notice ==

Upgrade normally via your WordPress admin -> Plugins panel.

== Frequently Asked Questions ==

= Quick and dirty - how do I display my FAQ's? =
Use the `[faq]` shortcode in a widget or on a post/page. This will output the FAQ's using the default settings (Ascending order by Title in a Toggle configuration). If you'd like to use a different order, consult the [Documentation](http://arcnx.co/afwiki) for assistance.

= How do I enable the accordion display mode? =
Add `style="accordion"` to the shortcode, e.g. `[faq style="accordion"]`

= Where can I find more information on how to use the plugin?  =
* Visit the [documentation](http://arcnx.co/afwiki) for assistance
* Tutorials on advanced plugin usage can be found at [Arconix Computers](http://arconixpc.com/tag/arconix-faq)

= The toggle or accordion isn't working. What can I do? =
While you can certainly start a thread in the [support forum](http://arcnx.co/afhelp), there are some troubleshooting steps you can take beforehand to help speed up the process.
1. Check to make sure the javascripts are loading correctly. Load the faq page in your browser and view your page's source. Look for jQuery and Arconix FAQ JS files there. If you don't see the Arconix FAQ JS file, then your theme's `header.php` file is likely missing `<?php wp_head(); ?>`, which is neccessary for the operation of mine and many other plugins.
2. Check to make sure only one copy of jQuery is being loaded. Many times conflicts arise when themes or plugins load jQuery incorrectly, causing the script to be loaded multiple times in multiple versions. In order to find the offending item, start by disabling your plugins one by one until you find the problem. If you've disabled all your plugins, try switching to a different them, such as twentyten or twentytwelve to see if the problem is with your theme. Once you've found the problem, contact the developer for assistance getting the issue resolved.

= I need help =
Check out the WordPress [support forum](http://arcnx.co/afhelp)

= I have a great idea for your plugin! =
That's fantastic! Feel free to open an issue or submit a pull request over at [Github](http://arcnx.co/afsource), or you can contact me through [Twitter](http://arcnx.co/twitter), [Facebook](http://arcnx.co/facebook) or my [Website](http://arcnx.co/1)

== Screenshots ==
1. Post Type in WordPress navigation list
2. Post Type Admin display
3. Grouping and Toggling display

== Changelog ==
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
