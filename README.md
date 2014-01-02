[Wordpress]: http://wordpress.com

Environments [Wordpress] Plugin
=================================

WP-Environments is a wordpress plugin to set which environment your Wordpress install is in such as local, staging, and production based on domains you define for each.

After activation a new sub section in "Settings" called "Environments" will appear.


Usage
=====

Once all plugins are loaded, before the theme is loaded, the following constants and functions become available:


Functions
=========

<pre>
WPE_isLocal()
WPE_isStaging()
WPE_isProd()
</pre>


Constants
=========

<pre>
WPE_ENV_DEV 
WPE_ENV_STAGING 
WPE_ENV_PROD 
WPE_ENV
</pre>