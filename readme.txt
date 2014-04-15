=== Arconix FAQ ===
Contributors: jgardner03
Donate link: http://arcnx.co/acfdonation
Tags: arconix, faq, toggle
Requires at least: 3.8
Tested up to: 3.9
Stable tag: 1.4.0

Arconix FAQ provides an easy way to add FAQ items to your website.

== Description ==

Utilizing Custom Post Types, this plugin allows the user to easily add FAQ items to his/her website. Display them using the supplied shortcode (`[faq]`) and show/hide them via an animated, jQuery toggle box. The FAQ's can be displayed in groups by tagging them during the FAQ item's creation. They can also be loaded closed or open, and for long FAQ's, there's a checkbox to add a "return to top" link at the bottom.

= Features =
* Custom Post-Type driven
* jQuery toggle when using the shortcode
* Can be displayed in groups by using the "group" taxonomy

== Installation ==

You can download and install Arconix FAQ using the built in WordPress plugin installer. If you download the plugin manually, make sure the files are uploaded to `/wp-content/plugins/arconix-faq/`.

Activate Arconix-FAQ in the "Plugins" admin panel using the "Activate" link.

== Upgrade Notice ==

Upgrade normally via your WordPress admin -> Plugins panel.

== Frequently Asked Questions ==

= Quick and dirty - how do I display my FAQ's? =
* use the `[faq]` shortcode in a widget or on a post/page.

= Where can I find more information on how to use the plugin?  =

* Visit the plugin's [Wiki Page](http://arcnx.co/afwiki) for documentation
* Tutorials on advanced plugin usage can be found at [Arconix Computers](http://arconixpc.com/tag/arconix-faq)

= The toggle isn't working. What can I do? =

While you can certainly start a thread in the [support forum](http://arcnx.co/afhelp), there are some troubleshooting steps you can take beforehand to help speed up the process.
1. Check to make sure the javascripts are loading correctly. Load the faq page in your browser and view your page's source. Look for jQuery and Arconix FAQ JS files there. If you don't see the Arconix FAQ JS file, then your theme's `header.php` file is likely missing `<?php wp_head(); ?>`, which is neccessary for the operation of mine and many other plugins.

2. Check to make sure only one copy of jQuery is being loaded. Many times conflicts arise when themes or plugins load jQuery incorrectly, causing the script to be loaded multiple times in multiple versions. In order to find the offending item, start by disabling your plugins one by one until you find the problem. If you've disabled all your plugins, try switching to a different them, such as twentyten or twentytwelve to see if the problem is with your theme. Once you've found the problem, contact the developer for assistance getting the issue resolved.

= I need help =

Check out the WordPress [support forum](http://arcnx.co/aphelp)

= I have a great idea for your plugin! =

That's fantastic! Feel free to submit a pull request over at [Github](http://arcnx.co/afsource), add an idea to the [Trello Board](http://arcnx.co/aftrello), or you can contact me through [Twitter](http://arcnx.co/twitter), [Facebook](http://arcnx.co/facebook) or my [Website](http://arcnx.co/1)

== Screenshots ==
1. Post Type in WordPress navigation list
2. Post Type Admin display
3. Grouping and Toggling display

== Changelog ==
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