=== Easy Modal ===
Contributors: danieliser
Author URI: http://wizardinternetsolutions.com
Plugin URI: https://easy-modal.com
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=PQTG2JYUKSLFW
Tags: modal,modal box,modal form,modal window,popup,popup box,popup form,popup window,ajax forms
Requires at least: 3.2
Tested up to: 3.6
Stable tag: 1.2.2
Easily create & style modals with any content. Theme editor to quickly style your modals. Add forms, social media boxes, videos, slideshows & more.

== Description ==
This plugin allows you to easily add unlimited Modal windows with just about any content. It accepts shortcodes and has been tested to work with Contact Form 7 custom forms using ajax submission.
 
= Core Features =
* Unlimited Customizable Modals
* Visual Theme Editor
* Responsive or Custom Width Modals
* 19 Customizable Animations
* Customize close options per modal.
* Open a modal from within another modal
* Load modals on every page or just certain pages.
* Quickly add modals to any highlighted text in editor.
* Allows custom JS functions to open & close modals.

= Pro Features =
* Premium Support*
* Unlimited Themes
* Disable close icon
* Ajax Login, Registration & Forgot Your Password Modals
* Force User Login Modal ( Per Page / Post or Site Wide )
* Set Modal to Auto Open ( Per Page / Post and/or Site Wide )
* Set Modal to Open on Exit ( Per Page / Post and/or Site Wide )
* Export / Import Settings, Modals & Themes
= Premium Support =
* Premium User Support Forum
* Expedited Feature Requests
* Setup Assistance


If you like the plugin please rate it.

[Easy Modal Page](http://easy-modal.com "Easy Modal Page - Info, Demo and Discussion") - Info, Demo and Feature Discussion

[Wizard Internet Solutions](http://wizardinternetsolutions.com "Website Design & Development") - Developers Site

To be notified of plugin updates, [follow us on Twitter](http://twitter.com/EasyModal "Wizard Internet Solutions on Twitter")!

== Installation ==
1. Upload `Easy-Modal` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Create and customize a modal in the Easy Modal settings page.
4. Customize the theme to match your website on the theme settings page.
5. Copy and Add ``eModal-#` class from the modals settings page to the object you want to make activate the modal window. Will work on divs, links, list elements and just about anything else.

== Changelog ==
= v1.2.2 =
* Added filter to add meta boxes and em options to custom post types.

= v1.2.1 =
* Fixes compatibility issues with Ultimate TinyMCE Plugin.
* Added plugin update notes to plugin page when updates are available.

= v1.2.0.9 =
* Fixed CSS z-index issues ( set modal z-index to 999, and overlay to 998 to make sure they are above other elements )
* Fixed an issue with upgrading from previous versions.

= v1.2.0.4 =
* Fixed data migration issue ( wasn't setting sites to sitewide )
* Added filters for modal content. Use add_filter('em_modal_content', 'your_custom_function'); function your_custom_function($content);
= v1.2.0.2 =
* Fixed issue of undefined array key.
= v1.2.0.1 =
* Fixed issue that caused wp editor to not load with certain themes.
= v1.2 =
* Code has been rewritten from ground up, JS, admin panels etc.
* Added animations
* Added responsive modals
* Added several additional settings.
= v1.0.2 =
* Fix for installation glitch.
= v1.0.0 =
* Release v1.0.0 Is a was rebuilt from the ground up. Features Include:
* Unlimited Modals
* Lighter Filesizes for Faster Loading
* Auto Centers no matter what the content
* Recenters on window resize/move
= v0.9.0.11 =
* Bug Fix in Settings page color picker.
= v0.9.0.10 =
* Bug Fix in CSS Fixes Form scrolling only when needed.
= v0.9.0.9 =
* Bug Fix in CSS Fixes Form scrolling.
= v0.9.0.8 =
* Bug Fix in JS (Missing " fixed)
= v0.9.0.7 =
* Bug Fix in JS (Affected loading of content into window)
= v0.9.0.6 =
* Bug Fix in JS (Affected WordPress versions below 3.1)
= 0.9.0.5 =
* Bug Fix in JS (Affected IE7).
= v0.9.0.4 =
* Added "Default" Theme for Modal windows. Includes CF7 Styles and Inline AJAX Styleing. See Screenshots.
* Default Options Tweaked for better OOB Experience.
* Added Version to WP Options table to provide better update functionality.
= v0.9.0.3 =
* Overlay Click to Close Option
* Auto Position Option
* Position Top Option
* Position Left Option
* Auto Resize Option
= v0.9.0.2 =
* Added Overlay Color Picker.
= v0.9.0.1 =
* Added Height & Width options.
= v0.9 =
* Initial Release

== Upgrade Notice ==
= v1.2.1 = 
* Fixes compatibility issues with Ultimate TinyMCE Plugin.
= v1.0.0 =
* This is a new build your settings will be reset.
= v0.9.0.4 =
* Options will be overwritten with default options.
= 0.9 =
* Initial Release