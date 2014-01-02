<?php
defined( 'ABSPATH' ) OR exit;

if( !defined( 'WPE_DIRNAME' ) ) define( 'WPE_DIRNAME', dirname( dirname( __FILE__ ) ) );
if( !defined( 'WPE_URLPATH' ) ) define( 'WPE_URLPATH', WP_PLUGIN_URL . "/" . plugin_basename( WPE_DIRNAME ) . "/" );

// Environments
if ( !defined( 'WPE_ENV_LOCAL' ) ) define( 'WPE_ENV_LOCAL', 'local' );
if ( !defined( 'WPE_ENV_STAGING' ) ) define( 'WPE_ENV_STAGING', 'staging' );
if ( !defined( 'WPE_ENV_PROD' ) ) define( 'WPE_ENV_PROD', 'prod' );

?>