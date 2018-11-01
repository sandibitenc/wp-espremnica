<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Espremnica_Export
 * @subpackage Espremnica_Export/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Espremnica_Export
 * @subpackage Espremnica_Export/public
 * @author     Your Name <email@example.com>
 */
class Espremnica_Export_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $espremnica_export    The ID of this plugin.
	 */
	private $espremnica_export;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $espremnica_export       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $espremnica_export, $version ) {

		$this->espremnica_export = $espremnica_export;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Espremnica_Export_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Espremnica_Export_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->espremnica_export, plugin_dir_url( __FILE__ ) . 'css/espremnica-export-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Espremnica_Export_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Espremnica_Export_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->espremnica_export, plugin_dir_url( __FILE__ ) . 'js/espremnica-export-public.js', array( 'jquery' ), $this->version, false );

	}

}
