<?php

defined( 'ABSPATH' ) OR exit;
/*
Plugin Name: Wordpress Environments
Plugin URI: https://github.com/pixelbacon/wp-environment
Description: Puts a widget in to the sidebar that shows the latest in the single post's section.
Version: 0.0.1r0
Author: Mike Minor
Author URI: http://www.pixelbacon.com

*/
// Include constants file
require_once( dirname( __FILE__ ) . '/lib/constants.php' );

class Doejo_BackSectionPreview
{
    var $namespace = "mminor-environments";
    var $friendly_name = "Environments";
    var $version = "0.0.1r0";
    var $defaults = array (
        'local_domains'      => array (),
        'staging_domains'    => array (),
        'production_domains' => array ()
    );

    /**
     * Constructor function for plugin.
     */
    function __construct()
    {
        $this->option_name = $this->namespace . '--options';

        $libs = glob( DBSPW_PLUGIN_DIRNAME . '/lib/*.php' );
        foreach ( $libs as $lib ) {
            include_once( $lib );
        }
        $this->_add_hooks();
    }


//-----------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------
//  STATIC METHODS
//-----------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------
    /**
     * Hook into register_activation_hook action
     *
     * Put code here that needs to happen when your plugin is first activated (database
     * creation, permalink additions, etc.)
     */
    static function activate()
    {
        // Do activation actions
        // global $wp_rewrite;
        // $this->flush_rewrite_rules();
    }

    /**
     * Hook into register_deactivation_hook action
     *
     * Put code here that needs to happen when your plugin is deactivated
     */
    static function deactivate()
    {
        // Do deactivation actions
    }


    /**
     * Adds hooks needed for working within the flow of Wordpress.
     *
     * @uses add_action
     */
    private function _add_hooks()
    {

    }


//-----------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------
//  OPTIONS
//-----------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------
    /**
     * Retrieve the stored plugin option or the default if no user specified value is defined
     *
     * @param string $option_name The name of the TrialAccount option you wish to retrieve
     *
     * @uses get_option()
     * @return mixed Returns the option value or false(boolean) if the option is not found
     */
    public function get_option( $option_name )
    {
        // Load option values if they haven't been loaded already
        if ( !isset( $this->options ) || empty( $this->options ) ):
            $this->options = get_option( $this->option_name, $this->defaults );
        endif;

        // print_r( $this->options );

        if ( isset( $this->options[ $option_name ] ) ) {
            return $this->options[ $option_name ]; // Return user's specified option value
        } elseif ( isset( $this->defaults[ $option_name ] ) ) {
            return $this->defaults[ $option_name ]; // Return default option value
        }

        return false;
    }


//-----------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------
//  ADMIN
//-----------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------
    /**
     * Define the admin menu options for this plugin
     *
     * @uses add_action()
     * @uses add_options_page()
     */
    function admin_menu()
    {
        if ( !current_user_can( 'manage_options' ) ):
            // wp_die('You do not have sufficient permissions to access this page.');
            return;
        endif;

        $page_hook = add_options_page(
            $this->friendly_name,
            $this->friendly_name,
            'administrator',
            $this->namespace,
            array ( &$this, 'admin_options_page' )
        );

        $this->admin_options_errors = array ();

        // Add print scripts and styles action based off the option page hook
        add_action( 'admin_print_scripts-' . $page_hook, array ( &$this, 'admin_enqueue_scripts' ) );
        add_action( 'admin_print_styles-' . $page_hook, array ( &$this, 'admin_enqueue_styles' ) );
    }
}

?>