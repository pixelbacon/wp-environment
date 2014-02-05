[Wordpress]: http://wordpress.com

#Environments [Wordpress] Plugin

WP-Environments is a wordpress plugin to set which environment your Wordpress install is in such as local, staging, and production based on domains you define for each.

After activation a new sub section in "Settings" called "Environments" will appear.

[![Donate](https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif)](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=K3AUXUPR4GSHN)


## Usage

Once all plugins are loaded, before the theme is loaded, the following constants and functions become available:


#### Functions

<pre>
WPE_isLocal()
WPE_isStaging()
WPE_isPreProd()
WPE_isProd()
</pre>

#### Constants

<pre>
WPE_ENV_DEV
WPE_ENV_STAGING
WPE_ENV_PREPROD
WPE_ENV_PROD
WPE_ENV
</pre>

#### Adding Domains Programmatically

<pre>
WPE_addLocalDomain( string|array $domain )
WPE_addStagingDomain( string|array $domain )
WPE_addPreProdDomain( string|array $domain )
WPE_addProdDomain( string|array $domain )
</pre>


## Change Log

- 0.0.5r5
	- Arrays can now be used when adding domains.
	- Added pre-production environment; running on a client's staging environment for example.
	- Cleaned up code for displaying options.
	- Added Prebaked Code section if the person would like to use this in <i>wp-config.php.</i>
	- New docs.
- 0.0.4r4
	- New docs.


## Road Map

Currently there are no big plans for the plugin. It's a simple plugin... So... yea.

## Forking

This is not code golf.

