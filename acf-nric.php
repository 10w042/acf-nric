<?php
/*
Plugin Name: Advanced Custom Fields: NRIC/FIN
Plugin URI: https://github.com/10w042/acf-nric
Description: Adds a NRIC/FIN field type to Advanced Custom Fields.
Version: 1.0.0
Author: Low Yong Zhen
Author URI: https://stargate.io
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

// exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// check if class already exists
if ( ! class_exists( 'acf_plugin_nric' ) ) :

	class acf_plugin_nric {

		/*
		*  __construct
		*
		*  This function will setup the class functionality
		*
		*  @type	function
		*  @since	1.0.0
		*
		*  @param	n/a
		*  @return	n/a
		*/

		function __construct() {

			require plugin_dir_path( __FILE__ ) . 'vendor/mauris/nric-utility.php';

			// vars
			$this->settings = array(
				'version' => '1.0.0',
				'url'     => plugin_dir_url( __FILE__ ),
				'path'    => plugin_dir_path( __FILE__ )
			);


			// set text domain
			// https://codex.wordpress.org/Function_Reference/load_plugin_textdomain
			load_plugin_textdomain( 'acf-nric', false, plugin_basename( dirname( __FILE__ ) ) . '/lang' );


			// include field
			add_action( 'acf/include_field_types', array( $this, 'include_field_types' ) ); // v5
			add_action( 'acf/register_fields', array( $this, 'include_field_types' ) ); // v4

		}

		/*
		*  include_field_types
		*
		*  This function will include the field type class
		*
		*  @type	function
		*  @since	1.0.0
		*
		*  @param	$version (int) major ACF version. Defaults to false
		*  @return	n/a
		*/

		function include_field_types( $version = false ) {

			// this plugin only supports ACF major version 5
			$version = 5;

			// include the field
			include_once( 'fields/acf-nric-v' . $version . '.php' );

		}

	}

	new acf_plugin_nric();

// class_exists check
endif;
