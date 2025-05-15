=== To Title Case ===
Contributors: rmccue
Tags: title, case, uppercase, capitalize, capitalise
Requires at least: 2.0
Tested up to: 2.9
Stable tag: trunk

Automatically convert post titles on-the-fly, using Kroc Camen's port of John Gruber's title case.

== Description ==

Automatically convert post titles on-the-fly, using
[Kroc Camen's port](http://camendesign.com/code/title-case) of
[John Gruber's title case](http://daringfireball.net/2008/05/title_case). All
most all of the code was written by Kroc Camen, so he deserves the credit.
Credit also to David Gouch and John Gruber, who wrote the
[Javascript port](http://individed.com/code/to-title-case/) and original Perl
versions respectively.

== Installation ==

1. Upload `to-title-case.php` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Done! Go to your site and notice the new spiffy titles.

This should technically work on any WordPress installation with the ability to
install plugins, as `the_title` has been around since before it.

== Frequently Asked Questions ==

= Who wrote this? =

The original version was written by John Gruber for use on his site,
[Daring Fireball](http://daringfireball.net). David Gouch then wrote a
[Javascript port](http://individed.com/code/to-title-case/), which improved on
the original script by handling some edge cases. Kroc Camen then [ported it to
PHP](http://camendesign.com/code/title-case). Finally, Ryan McCue (that's me)
wrote the WordPress adaptation.

Confused yet? :)

= What if I find a bug? =

If the bug is a WordPress specific bug, contact me via
[my website](http://ryanmccue.info/). If the bug is to do with title-casing
itself, contact [Kroc Camen](http://camendesign.com/).

== Changelog ==

= 1.0.2
Ryan: Use Unicode characters correctly.

= 1.0.1 =
Kroc: Add `\p{L}` to get accented letters working.

= 1.0 =
* Original version.