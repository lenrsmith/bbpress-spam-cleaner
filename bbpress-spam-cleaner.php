<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * Dashboard. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://stickybitsoftware.com
 * @since             0.1.1
 * @package           bbPress_Spam_Cleaner
 * @author 			  Leonard Smith
 * @license  		  GPL-2.0+
 * @copyright 		  2014 Leonard Smith
 *
 * @wordpress-plugin
 * Plugin Name:       bbPress Spam Cleaner
 * Plugin URI:        http://stickybitsoftware.com/bbpress-spam-cleaner-uri/
 * Description:       bbPress Spam Cleaner identifies spam posts in existing forums and marks them as such.
 * Version:           0.0.1
 * Author:            Leonard R. Smith, Jr.
 * Author URI:        http://stickybitsoftware.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-bbpress-spam-cleaner-activator.php
 */
function activate_plugin_name() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-bbpress-spam-cleaner-activator.php';
	bbPress_Spam_Cleaner_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-bbpress-spam-cleaner-deactivator.php
 */
function deactivate_plugin_name() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-bbpress-spam-cleaner-deactivator.php';
	bbPress_Spam_Cleaner_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_bbpress_spam_cleaner' );
register_deactivation_hook( __FILE__, 'deactivate_bbpress_spam_cleaner' );

/**
 * The core plugin class that is used to define internationalization,
 * dashboard-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-bbpress-spam-cleaner.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    0.0.1
 */
function run_plugin_name() {

	$plugin = new bbPress_Spam_Cleaner();
	$plugin->run();

}
run_plugin_name();
