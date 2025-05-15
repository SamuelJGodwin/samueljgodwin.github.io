=== Search & Replace Everything by WPCode - Find and Replace Media, Text, Links, and More ===
Contributors: WPbeginner, smub, gripgrip, wpcodeteam
Tags: search, replace, database, media, search replace
Requires at least: 5.5
Tested up to: 6.7
Requires PHP: 7.0
Stable tag: 1.0.7
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Search and Replace everything in WordPress. Easily find and replace media, images, text, links and more with a single click using a simple user interface.

== Description ==

= Powerful Search & Replace for WordPress =

Search & Replace Everything by WPCode enables you to efficiently manage your website's content directly from the WordPress admin. This tool is essential for site migrations, content updates, or any situation where batch find and replace text and image replacements are needed.

With an intuitive interface, you can perform detailed find and replace operations across your entire database. Select specific tables, toggle case sensitivity, and preview changes before committing. Serialized data is fully supported. Designed to handle large websites, this plugin operates smoothly without the need for external tools.

= Features Include =

* **Text Replacement** - Find and replace text across multiple database tables with support for serialized data and options for case-sensitive search.
* **Replace Image** - Directly replace images from the media library, automatically regenerating thumbnails to ensure visual consistency.
* **Replace Media** - Manage and replace media files of various formats across your entire site.
* **Preview Changes** - Always see a "dry-run" preview of the changes to ensure accuracy before applying them.
* **Large Sites Supported** - Optimized for performance, capable of handling large databases efficiently.
* **Table Selection** - Choose specific tables to search and replace text, ensuring that only the necessary data is affected.

**Introducing Search & Replace Everything Pro**

While Search & Replace Everything offers many powerful features for free, we have also created a Pro version that includes advanced features to further improve your workflows like the ability to **undo** Search & Replace operations and replacing images directly from the Gutenberg editor. [Click here to purchase Search & Replace Everything Pro now!](https://library.wpcode.com/sr-pricing?utm_source=wprepo&utm_medium=link&utm_campaign=srliteplugin)

= Use Cases =

* **Site Migrations** - Quickly update URLs or any site data when moving your site.
* **Content Updates** - Easily replace outdated information or bulk update content across posts, pages, and custom post types.
* **Image Management** - Replace outdated images and avoid duplicate uploads.
* **Media Updates** - Replace media files in any format across your site.

= Tips for Using Search & Replace =

**Backup Your Database** - While the plugin is safe to use, it's always a good idea to make a backup before making changes.

Common mistakes to avoid when replacing text:

* Partial Matches - Ensure that you're not replacing partial matches that could affect unintended content. For example, replacing "cat" could affect "category" or "concatenate".
* Case Sensitivity - Be mindful of case sensitivity when replacing text. If you're looking to replace "Cat" with "Dog", ensure that you're not affecting "cat" or "CAT".
* URL Replacements - Always use the same format for both the search and replace values. For example if your search term has a trailing slash, ensure that the replacement term also has a trailing slash. E.g., "example.com/" to "example.net/" instead of "example.com/" to "example.net".

= Getting Started =

After installing Search & Replace Everything by WPCode, look for the new menu item under `WP Admin > Tools > WP Search & Replace`. From there, you can start managing your content and perform find and replace operations for text or images across your entire website.
== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/search-replace-wpcode` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress.
3. Use the interface under `WP Admin > Tools > WP Search & Replace` to start managing your content.

== Screenshots ==

1. Find and Replace text screen.
2. Preview text changes side by side.
3. Replace media from the plugin interface.
4. Quickly Replace Image from Media Library.
5. Replace image preview.
6. Replace image from media details page.

== Frequently Asked Questions ==

= Can I preview changes before applying them? =
Yes, you can preview all changes before applying them in an intuitive interface that highlights the differences.

= Do I need to make a backup before using this plugin? =
While the plugin is designed to be safe, it's always a good idea to make a backup before making any changes to your database.

= Can I undo a replace operation? =
Currently, all operations are final. Please ensure to review the changes preview carefully before applying changes, and it's always useful to have a backup. In our Pro version, we offer an undo feature for operations made with the Pro plugin.

= Can I replace text in specific tables only? =
Yes, you can select specific tables to search and replace text before starting the operation.

= Are large websites supported? =
Yes, the plugin is optimized for large databases and provides a smooth experience without the need for external tools.

= Can I replace images from the media library? =
Yes, you can replace images from the media library and get a preview of the new image. Thumbnails are automatically regenerated to ensure visual consistency.

= Can I replace any type of media files? =
Yes, you can find and replace media files of various formats across your entire site.

== Changelog ==

= 1.0.7 =
* Tweak: You can now drag & drop files in the media replacement page.
* Tweak: We improved feedback for search & replace operations on large sites.

= 1.0.6 =
* Tweak: We improved the way we handle a search & replace operation that involves changing the site URL.
* Fix: We fixed some text-domains that were not correctly set for better translation support.

= 1.0.5 =
* Fix: Adjusted spacing for footer that was overlapping with the content on some browsers.

= 1.0.4 =
* New: Updated the footer links on the plugin pages for more clarity.

= 1.0.3 =
* New: Added help links to the plugin.

= 1.0.2 =
* New: We enabled media replacements for all types of files, not just images.
* New: You can now search table names for easier selection.

= 1.0.1 =
* Fix: Fixed function name typos in the admin.

= 1.0.0 =
* Initial release.
