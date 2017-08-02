=== ECT SEO ===
Contributors: etemplates
Donate link: http://www.ecommercetemplates.com/donations.asp
Tags: Wordpress ecommerce, ecommerce, online store, sell products, shopping cart, wordpress store, wordpress shopping cart, ecommerce software, seo, meta description, title tag, search engine friendly, search engine optimization
Requires at least: 3
Tested up to: 4.7.1
Stable tag: 1.5
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Add meta description tags and titles to all of your Ecommerce Templates shopping cart store pages.

== Description ==

This plugin is for Ecommerce Templates shopping cart with WordPress only and allows you to add meta description tags and titles to the store generated pages. This means you can choose your own unique content for the meta tags and titles on pages like affiliate.php, cart.php, search.php whilst also allowing for unique and relevant descriptions and titles on dynamic store pages like categories.php, products.php and proddetail.php.

Please note, the ECT Add to Cart plug-in is only available for Ecommerce Templates shopping cart software WordPress integration.

For more details, screenshots and information please visit [Ecommerce Templates for WordPress](http://www.ecommercetemplates.com/wordpress-ecommerce.asp).

**Key Features**

* Simply generate meta description tags and titles for all your store pages
* Dynamically generate tags for dynamic pages based on their content
* Works in conjunction with WordPress generated titles and meta tags for non-store pages
* Title and meta description tags will be unique and relevant to page content

**Notes**

Please note there are Advanced Settings available but these do require some coding changes. If you need to know more about this feature, please do get in touch.

The ECT SEO plug-in is only available for Ecommerce Templates shopping cart software WordPress integration.

**View Demo Store**

We have set up a [demo store](http://ectwp.com/) using the Responsive theme and Ecommerce Templates shopping cart where you will also find examples of the [ECT SEO plugin](http://ectwp.com/plugins/).

**Support**

If you have any problems with generating the meta description tags and titles please post your support questions here on the WordPress support forum. Any questions about the shopping cart or WordPress integration can be posted on the Ecommerce Templates support forum. 
If you have a problem with the plug-in please don't just give it a bad rating or review without seeking our help first.

**Plug-in resources**

[Plug-in home page](http://www.ecommercetemplates.com/wordpress/wp-plugins.asp) - [Shopping cart home page](http://www.ecommercetemplates.com/wordpress-ecommerce.asp) - [Demo](http://ectwp.com/)

== Installation ==

1. Unzip and upload the folder 'ect-seo' to the '/wp-content/plugins/' directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Go to Settings and enter the meta description tags and titles for the various store pages.
1. Go to Appearance > Editor and choose header.php. Find the WordPress theme's title tag which will look something like this
<title><?php wp_title( '|', true, 'right' ); ?></title>
and replace it with
<?php echo do_shortcode('[ect_seo]')?>
1. Save header.php and check your title and meta description tags on your store pages.
1. If there is no title code in header.php, enter the shortcode just before <?php wp_head(); ?>

== Frequently asked questions ==

= Can I use this plug-in with any shopping cart software or WordPress site? =

NO, this plug-in can only be used with [Ecommerce Templates shopping cart software](http://www.ecommercetemplates.com/wordpress-ecommerce.asp) using the WordPress integration.

= Will this work for non-store pages? =

Non-store pages will use the regular set up for titles and meta descriptions.

= Why can't I enter my own title for the products, detail and categories pages? =

These pages are dynamic and created from the database. As such the meta content and title is taken from elements such as the product name, description, id and section.

== Screenshots ==

1. Title and meta description fields

== Changelog ==

= 1.0 =
* Initial Release. June 18th 2013.
= 1.1 =
* Compatible with all non-store pages and 3.8. December 18th 2013.
= 1.2 =
* Now compatible with user defined title and meta description tags as well as ordering the elements in the title. April 17th 2015.
= 1.3 =
* Title and meta description tags for home page. April 23rd 2015.
= 1.4 =
* Support for header files with no title code. November 28 2016.
= 1.5 =
* Code fixes and version update. January 26 2017.