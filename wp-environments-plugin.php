<?php

defined( 'ABSPATH' ) OR exit;
/**
 * Environments
 *
 * @package Environments
 *
 * @global object $wpdb
 *
 * @author  Mike Minor
 * @version 0.0.1r1
 */
/*
Plugin Name: Environments
Plugin URI: https://github.com/pixelbacon/wp-environment
Description: Sets constants for local, staging, and production environments based on your input.
Version: 0.0.1r1
Author: (Mike Minor)
Author URI: http://www.pixelbacon.com

*/

// Constants
require_once( dirname( __FILE__ ) . '/lib/constants.php' );


class WP_Environments
{
    var $namespace = "wp-environments";
    var $friendly_name = "Environments";
    var $version = "0.0.1r1";
    var $defaults = array (
        'local_domains'      => array ( 'localhost' ),
        'staging_domains'    => array (),
        'production_domains' => array ()
    );

    /**
     * Constructor function. Sets up option name, loads php files in lib directory, and sets up hooks.
     */
    function __construct()
    {
        // Name of the option_value to store plugin options in
        $this->option_name = $this->namespace . '--options';

        // Load all library files used by this plugin
        $libs = glob( WPE_DIRNAME . '/lib/*.php' );
        foreach ( $libs as $lib ) {
            include_once( $lib );
        }

        if ( is_admin() ):
            $this->admin_options_errors = array ();
        endif;

        $this->_add_hooks();
    }


//-----------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------
//  Guts
//-----------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------
    /**
     * Whether or not the server is a local development environment.
     * @return bool
     */
    public function isDev()
    {
        return WPE_ENV == WPE_ENV_DEV;
    }

    /**
     * Whether or not the server is in a staging environment.
     * @return bool
     */
    public function isStaging()
    {
        return WPE_ENV == WPE_ENV_STAGING;
    }

    /**
     * Whether or not the server is in a production environment.
     * @return bool
     */
    public function isProd()
    {
        return WPE_ENV == WPE_ENV_PROD;
    }


//-----------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------
//  STATIC METHODS
//-----------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------
    /**
     * Hook into register_activation_hook action
     */
    static function activate()
    {
    }

    /**
     * Hook into register_deactivation_hook action
     */
    static function deactivate()
    {
    }


//-----------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------
//  HOOKS
//-----------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------
    /**
     * Adds hooks needed for working within the flow of Wordpress.
     *
     * @uses add_action
     */
    private function _add_hooks()
    {
        // Options page for configuration
        add_action( 'admin_menu', array ( &$this, 'admin_menu' ) );

        // Route requests for form processing in Options
        add_action( 'init', array ( &$this, 'route' ) );

        // Add a settings link next to the "Deactivate" link on the plugin listing page
        add_filter( 'plugin_action_links', array ( &$this, 'admin_plugin_action_links' ), 10, 2 );
    }


//-----------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------
//  OPTIONS
//-----------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------

    /**
     * Retrieve the stored plugin option or the default if no user specified value is defined
     *
     * @param string $option_name The name of the option you'd like to get.
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
            wp_die( 'You do not have sufficient permissions to access this page.' );

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

    /**
     * Admin - Enqueue JS file(s).
     *
     * @uses wp_register_style()
     * @uses wp_enqueue_style()
     */
    function admin_enqueue_scripts()
    {
        wp_register_script( "{$this->namespace}-js-admin", WPE_URLPATH . "/js/admin.js", array ( 'jquery' ), $this->version, true );
        wp_enqueue_script( "{$this->namespace}-js-admin" );
    }

    /**
     * Admin - Enqueue CSS file(s).
     *
     * @uses wp_register_style()
     * @uses wp_enqueue_style()
     */
    function admin_enqueue_styles()
    {
        wp_register_style( "{$this->namespace}-css-admin", WPE_URLPATH . "/css/admin.css", array (), $this->version, 'screen' );
        wp_enqueue_style( "{$this->namespace}-css-admin" );
    }

    /**
     * Hook into plugin_action_links filter
     *
     * Adds a "Settings" link next to the "Deactivate" link in the plugin listing page
     * when the plugin is active.
     *
     * @param object $links An array of the links to show, this will be the modified variable
     * @param string $file  The name of the file being processed in the filter
     *
     * @return array Links after after adding the plugin's Settings link.
     */
    function admin_plugin_action_links( $links, $file )
    {
        if ( $file == plugin_basename( WPE_DIRNAME . '/' . basename( __FILE__ ) ) ) {
            $old_links = $links;
            $new_links = array (
                "settings" => '<a href="options-general.php?page=' . $this->namespace . '">' . __( 'Settings' ) . '</a>'
            );
            $links     = array_merge( $new_links, $old_links );
        }

        return $links;
    }


    /**
     * The admin section options page rendering method
     *
     * @uses current_user_can()
     */
    function admin_options_page()
    {
        if ( !current_user_can( 'manage_options' ) ) {
            require( WPE_DIRNAME . "/views/admin_no_options.php" );

            return;
        }

        $page_title = $this->friendly_name . ' - Options';
        $namespace  = $this->namespace;

        // Retrieve admin errors since we use wp_safe_redirect to show this page.
        $this->admin_options_errors = isset( $_GET[ $this->namespace . '-errors' ] ) ? $_GET[ $this->namespace . '-errors' ] : array ();

        include( WPE_DIRNAME . "/views/admin_options.php" );
    }

    /**
     * Process update page form submissions
     *
     * @uses Doejo_TwitterCards::sanitize()
     * @uses wp_redirect()
     * @uses wp_verify_nonce()
     */
    private function _admin_options_update()
    {
        // Verify submission for processing using wp_nonce
        if ( wp_verify_nonce( $_REQUEST[ '_wpnonce' ], "{$this->namespace}-update-options" ) ) {
            $data = array ();

            /*
             * Loop through each POSTed value and sanitize it to protect against malicious code. Please
             * note that rich text (or full HTML fields) should not be processed by this function and
             * dealt with directly.
             */

            foreach ( $_POST[ 'data' ] as $key => &$val ) {
                // Assume the value is not clean until we actually process it.
                $isValid = false;

                $data[ $key ] = $this->_sanitize( $val );

                switch ( $key ) {
                    default:
                        {
                        $isValid = true;
                        break;
                        }
                }

                // If the value is not valid, unset it from the array so we don't save it.
                if ( !$isValid ):
                    array_push( $this->admin_options_errors, $this->_get_error_code( $key ) );
                    unset( $data[ $key ] );
                endif;
            }

            update_option( $this->option_name, $data );

            $_POST[ '_wp_http_referer' ] = add_query_arg( $this->namespace . '-errors', $this->admin_options_errors, $_POST[ '_wp_http_referer' ] );
            if ( count( $this->admin_options_errors ) === 0 ):
                $_POST[ '_wp_http_referer' ] = add_query_arg( 'message', '1' );
            endif;

            wp_safe_redirect( $_POST[ '_wp_http_referer' ] );
            exit;
        }
    }

    /**
     * Sanitize data
     *
     * @param mixed $str The data to be sanitized
     *
     * @uses wp_kses()
     * @return mixed The sanitized version of the data
     */
    private function _sanitize( $str )
    {
        if ( !function_exists( 'wp_kses' ) ):
            require_once( ABSPATH . 'wp-includes/kses.php' );
        endif;

        global $allowedposttags;
        global $allowedprotocols;

        if ( is_string( $str ) ) {
            $str = wp_kses( $str, $allowedposttags, $allowedprotocols );
        } elseif ( is_array( $str ) ) {
            $arr = array ();
            foreach ( (array) $str as $key => $val ) {
                $arr[ $key ] = $this->_sanitize( $val );
            }
            $str = $arr;
        }

        return $str;
    }

    /**
     * Returns a string based on the key passed. For generic errors only.
     *
     * @param $key
     *
     * @return string
     */
    private function _get_error_code( $key )
    {
        switch ( $key ) {
            default:
                {
                return 'Generic Error...';
                break;
                }
        }
    }


//-----------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------
//  ROUTING
//-----------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------

    /**
     * Route the user based off of environment conditions
     *
     * This function will handling routing of form submissions to the appropriate
     * form processor.
     *
     * @uses WP_Environments->_admin_options_update()
     */
    function route()
    {
        $uri      = $_SERVER[ 'REQUEST_URI' ];
        $protocol = isset( $_SERVER[ 'HTTPS' ] ) ? 'https' : 'http';
        $hostname = $_SERVER[ 'HTTP_HOST' ];
        $url      = "{$protocol}://{$hostname}{$uri}";
        $is_post  = (bool) ( strtoupper( $_SERVER[ 'REQUEST_METHOD' ] ) == "POST" );

        // Check if a nonce was passed in the request
        if ( isset( $_REQUEST[ '_wpnonce' ] ) ) {
            $nonce = $_REQUEST[ '_wpnonce' ];

            // Handle POST requests
            if ( $is_post ) {
                if ( wp_verify_nonce( $nonce, "{$this->namespace}-update-options" ) ) {
                    $this->_admin_options_update();
                }
            } // Handle GET requests
            else {

            }
        }
    }


//-----------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------
//  UTILITIES
//-----------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------
    public function clean_array( &$array )
    {
        return array_slice( array_filter( $array ), 0 );
    }


//-----------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------
//  SINGLETON INSTANCE
//-----------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------

    /**
     * Initialization function to hook into the WordPress init action
     *
     * Instantiates the class on a global variable and sets the class, actions
     * etc. up for use.
     */
    static function instance()
    {
        global $WP_Environments;

        // Only instantiate the Class if it hasn't been already
        if ( !isset( $WP_Environments ) ) $WP_Environments = new WP_Environments();
    }
}

if ( !isset( $WP_Environments ) ) {
    WP_Environments::instance();
}


if ( !function_exists( 'WPE_isDev' ) ):
    /**
     * Whether or not the server is a local development environment.
     * @return bool
     */
    function WPE_isDev()
    {
        return $WP_Environments->isDev();
    }
endif;

if ( !function_exists( 'WPE_isStaging' ) ):
    /**
     * Whether or not the server is in a staging environment.
     * @return bool
     */
    function WPE_isStaging()
    {
        return $WP_Environments->isStaging();
    }
endif;

if ( !function_exists( 'WPE_isProd' ) ):
    /**
     * Whether or not the server is in a production environment.
     * @return bool
     */
    function WPE_isProd()
    {
        return $WP_Environments->isProd();
    }
endif;

register_activation_hook( __FILE__, array ( 'WP_Environments', 'activate' ) );
register_deactivation_hook( __FILE__, array ( 'WP_Environments', 'deactivate' ) );

?>