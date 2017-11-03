<?php
use Mauris\Utility;

// exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// check if class already exists
if ( ! class_exists( 'acf_field_nric' ) ) :

	class acf_field_nric extends acf_field {

		/*
		*  __construct
		*
		*  This function will setup the field type data
		*
		*  @type	function
		*  @date	5/03/2014
		*  @since	5.0.0
		*
		*  @param	n/a
		*  @return	n/a
		*/

		function __construct( $settings ) {

			/*
			*  name (string) Single word, no spaces. Underscores allowed
			*/

			$this->name = 'nric';


			/*
			*  label (string) Multiple words, can include spaces, visible when selecting a field type
			*/

			$this->label = __( 'NRIC/FIN', 'acf-nric' );


			/*
			*  category (string) basic | content | choice | relational | jquery | layout | CUSTOM GROUP NAME
			*/

			$this->category = 'basic';


			/*
			*  defaults (array) Array of default settings which are merged into the field object. These are used later in settings
			*/

			$this->defaults = array(
				'default_value' => '',
				'allow_fin'     => 1,
				'placeholder'   => '',
			);


			/*
			*  l10n (array) Array of strings that are used in JavaScript. This allows JS strings to be translated in PHP and loaded via:
			*  var message = acf._e('nric', 'error');
			*/

			$this->l10n = array(
				'error' => __( 'Error! Please enter a higher value', 'acf-nric' ),
			);


			/*
			*  settings (array) Store plugin settings (url, path, version) as a reference for later use with assets
			*/

			$this->settings = $settings;


			// do not delete!
			parent::__construct();

		}

		/*
		*  render_field_settings()
		*
		*  Create extra settings for your field. These are visible when editing a field
		*
		*  @type	action
		*  @since	3.6
		*  @date	23/01/13
		*
		*  @param	$field (array) the $field being edited
		*  @return	n/a
		*/

		function render_field_settings( $field ) {

			/*
			*  acf_render_field_setting
			*
			*  This function will create a setting for your field. Simply pass the $field parameter and an array of field settings.
			*  The array of settings does not require a `value` or `prefix`; These settings are found from the $field array.
			*
			*  More than one setting can be added by copy/paste the above code.
			*  Please note that you must also have a matching $defaults value for the field name (font_size)
			*/

			acf_render_field_setting( $field, array(
				'label'        => __( 'Allow FIN', 'acf-nric' ),
				'instructions' => '',
				'type'         => 'true_false',
				'name'         => 'allow_fin',
			) );

			// default_value
			acf_render_field_setting( $field, array(
				'label'        => __( 'Default Value', 'acf' ),
				'instructions' => __( 'Appears when creating a new post', 'acf' ),
				'type'         => 'text',
				'name'         => 'default_value',
			) );

			// placeholder
			acf_render_field_setting( $field, array(
				'label'        => __( 'Placeholder Text', 'acf' ),
				'instructions' => __( 'Appears within the input', 'acf' ),
				'type'         => 'text',
				'name'         => 'placeholder',
			) );

		}

		/*
		*  render_field()
		*
		*  Create the HTML interface for your field
		*
		*  @param	$field (array) the $field being rendered
		*
		*  @type	action
		*  @since	3.6
		*  @date	23/01/13
		*
		*  @param	$field (array) the $field being edited
		*  @return	n/a
		*/

		function render_field( $field ) {

			$atts = array();
			$o    = array( 'id', 'class', 'name', 'value', 'placeholder' );
			$e    = '';

			// append atts
			foreach ( $o as $k ) {

				$atts[ $k ] = $field[ $k ];

			}

			// render
			$e .= '<div class="acf-input-wrap">';
			$e .= '<input type="text" ' . acf_esc_attr( $atts ) . ' />';
			$e .= '</div>';

			// return
			echo $e;

		}

		/**
		 * validate_value()
		 *
		 *  This filter is used to perform validation on the value prior to saving.
		 *  All values are validated regardless of the field's required setting. This allows you to validate and return
		 *  messages to the user if the value is not correct
		 *
		 * @type    filter
		 * @date     11/02/2014
		 * @since    5.0.0
		 *
		 * @param $valid
		 * @param $value
		 * @param $field
		 * @param $input
		 *
		 * @return bool|mixed|string
		 */

		function validate_value( $valid, $value, $field, $input ) {

			$value = trim( $value );

			// Do not allow FIN. FG are foreigner letters.
			if ( ! $field[ 'allow_fin' ] && preg_match( '/^[FG]\d{7}[A-Z]$/i', $value, $matches ) === 1 ) {
				return $valid = __( 'FIN is not allowed.', 'acf-nric' );
			}

			// Check format and validate ID
			if ( preg_match( '/^[STFG]\d{7}[A-Z]$/i', $value, $matches ) === 0 || Utility\NricUtility::validate( $value ) === false ) {
				return $valid = __( 'Please enter a valid identification number.', 'acf-nric' );
			}

			// return
			return $valid;
		}

		/**
		 *  update_value()
		 *
		 *  This filter is applied to the $value before it is saved in the db
		 *
		 * @type    filter
		 * @since     3.6
		 * @date      23/01/13
		 *
		 * @param    $value   (mixed) the value found in the database
		 * @param    $post_id (mixed) the $post_id from which the value was loaded
		 * @param    $field   (array) the field array holding all the field options
		 *
		 * @return    $value
		 */

		function update_value( $value, $post_id, $field ) {

			return strtoupper( $value );

		}

	}

// initialize
	new acf_field_nric( $this->settings );

// class_exists check
endif;
