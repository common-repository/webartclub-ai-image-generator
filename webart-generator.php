<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://webartclub.com
 * @since             1.0.0
 * @package           Webart_Generator
 *
 * @wordpress-plugin
 * Plugin Name:       WebArtClub AI Image Generator
 * Plugin URI:        https://webartclub.com/plugin
 * Description:       Generate website art in your website frontend.
 * Version:           1.0.0
 * Author:            Web Art Club
 * Author URI:        https://webartclub.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       webart-generator
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'WEBART_GENERATOR_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-webart-generator-activator.php
 */
function activate_webart_generator() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-webart-generator-activator.php';
	Webart_Generator_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-webart-generator-deactivator.php
 */
function deactivate_webart_generator() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-webart-generator-deactivator.php';
	Webart_Generator_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_webart_generator' );
register_deactivation_hook( __FILE__, 'deactivate_webart_generator' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-webart-generator.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_webart_generator() {

	$plugin = new Webart_Generator();
	$plugin->run();

}
run_webart_generator();
