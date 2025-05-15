=== My Simple Space ===
Contributors: mannweb
Tags: disk space, database size
Requires at least: 4.6.0
Tested up to: 6.6
Stable tag: 1.2.9
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Disk Space, Database and Memory Usage in the dashboard.

== Description ==

Display the total size space usage as well:

*   wp-content total size
*   wp-content/plugins size
*   wp-content/themes size
*   wp-content/uploads size
*   database size
*   Total available memory / used memory
*   PHP Version and OS (32/64 bit)

== Installation ==

Simply download, install and activate. Then a widget with information will be added to your dashboard and memory information in the footer of every admin page.

e.g.

1. Upload `my-simple-space` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

= How is diskspace calculated? =

The plugin cycles through the provided path to calculate the total space used for that particular path. The wp-content size includes the plugins, themes and upload folders, but also other folders under wp-content.

== Screenshots ==

1. The dashboard widget

== Changelog ==

= 1.2.9 =
* Corrected error in reverted \FilesystemIterator::FOLLOW_SYMLINKS. Switched to \FilesystemIterator::SKIP_DOTS, as following symlinks causes errors on some hosting platforms.

= 1.2.8 =
* Adds wp-content if outside ABSPATH, which was added in 1.2.7, to the Entire Site calculation, as WP_CONTENT_DIR is configurable.

= 1.2.7 =
* Updated code to use WP_CONTENT_DIR and WP_PLUGIN_DIR, as these can be changed by the site owner, to get correct paths.
* Reverted \FilesystemIterator::FOLLOW_SYMLINKS back to \FilesystemIterator::CURRENT_AS_FILEINFO on line 227, as it does not fix anything.
* Stripped code to ensure trailing slash on path for mss_dir_size function as its unnecessary

= 1.2.6 =
* Reverted \FilesystemIterator::CURRENT_AS_FILEINFO back to \FilesystemIterator::FOLLOW_SYMLINKS on line 227 to attempt correcting a bug from 1.2.5.
* Added code to ensure trailing slash on path for mss_dir_size function

= 1.2.5 =
* Adjusted \FilesystemIterator::FOLLOW_SYMLINKS on line 227 to \FilesystemIterator::CURRENT_AS_FILEINFO, as it returned the same folder size for wp-content, themes & uploads.
* Stripping the 2 path variable lines on 220 & 221 (reverting change made in 1.2.4)

= 1.2.4 =
* Fixed issue in the mss_dir_size function which caused a fatal exception when it tries to access a file path, instead of a folder path. Thanks @idempotent for pointing this out.
* Added \FilesystemIterator::FOLLOW_SYMLINKS to the inner RecursiveDirectoryIterator inside the mss_dir_size, which hopefully speeds up the calculation.

= 1.2.3 =
* Slight code cleanup.

= 1.2.2 =
* Ensure widget is only ever run on the dashboard page.

= 1.2.1 =
* Cast both $memory_usage and $memory_limit to int, before rounding on line 127 in my-simple-space.php to fix debugging error.

= 1.2.0 =
* Removed a few extra strange characters.

= 1.1.9 =
* Cleared transient creation bug. Transient is now set prior to returning value. Transient was not being created.
* Replaced custom size calculation function with WP's builtin size_format function. Reduces code size.

= 1.1.8 =
* Replaced folder calculation function, should increase speed

= 1.1.7 =
* Corrected badly named functions
* Applied speed boost, using transients earlier in output

= 1.1.6 =
* Corrected what broke the plugin in 1.1.5

= 1.1.5 =
* Updated to only run in the admin area, to increase frontend performance

= 1.1.4 =
* Updated to show compatible with WP 4.6

= 1.1.3 =
* Transitioned prior cache effort to using transients instead. Prior caching efforts did not work. Items are now cached for 60 minutes.

= 1.1.2 =
* Corrected error caused by adding in cache setup.

= 1.1.1 =
* Folder sizes are now cached for 60 minutes, to reduce overhead.

= 1.1.0 =
* Added check to exclude folders not readable. Hides errors.

= 1.0.9 =
* Made plugin more translatable.

= 1.0.8 =
* Updated to show works with 4.4.1
* Updated admin footer to use in_admin_footer() instead of rebuilding footer with admin_footer_text().

= 1.0.7 =
* Fix for sites that return the home_path as /. Gets the absolute path using ABSPATH instead.

= 1.0.6 =
* 1.0.5 update killed data for sites not using WordPress in a subfolder. This is now fixed.

= 1.0.5 =
* Corrected double folder for sites using WordPress in a subfolder.

= 1.0.4 =
* Found issue with closing columns on the widget. This was an HTML code issue, not closing the div tag, which caused other widgets below to be absorbed into the same widget box.

= 1.0.3 =
* General housekeeping to clean up plugin files, including plugin information.
* Moved some of items around and added in wp-admin and wp-includes.

= 1.0.2 =
* Removed hard coded paths and replaced with dynamic paths.

= 1.0.1 =
* Rewrote database calculation to make use of $wpdb, rather than mysql calls, which broke in some instances.

= 1.0.0 =
* Initial Release

== Upgrade Notice ==

= 1.1.3 =
* Paths are now cached as transients instead. Items are now properly cached for 60 minutes, after access.

= 1.0.8 =
* Updated the way information is added to admin footer. Much cleaner method.

= 1.0.5 =
* Changed the way plugin works with get_home_path. If site is in a subfolder, subfolder is stripped to avoid double entry, resulting in error messages.

= 1.0.2 =
* Hard coded paths were removed and instead setup to pull based on your WordPress installation.
