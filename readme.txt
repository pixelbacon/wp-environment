=== Plugin Name ===
Contributors: pixelbacon
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=K3AUXUPR4GSHN
Tags: development, environments
Requires at least: 3.0.1
Tested up to: 3.9 alpha
Stable tag: 4.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Associate $_SERVER['HTTP_HOST'] values with environments for local development, staging, pre-production, and production.

== Description ==

Sometimes you need to predefine your environments based on the URL when multiple people development a Wordpress site. Or sometimes you need only your staging site to output some debugging information, but don't want to have multiple versions of the same file across different subversion/git branches. This plugin offers a way to have DRY methods built in to your theme or plugin to do such things a bit easier.

The plugin also offers up straight PHP code that you can use in wp-config that will not interfere with the plugin when it's loaded.

== Installation ==

1. Upload `WP-Environment` folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Check options page for documentation.

== Frequently Asked Questions ==

= Why? =

When switching between environments sometimes you want to make sure everyone is on the same page, that staging sends out debugging information but pre-production doesn't, etc. This plugin helps that.

= How does it detect the URL =

The plugin uses switch methods on the $_SERVER['HTTP_HOST'] and sees if the value matches any of the environments you have set up in the plugin's options page.