<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://github.com/svlobachev/planfactapi
 * @since             1.0.0
 * @package           Planfactapi
 *
 * @wordpress-plugin
 * Plugin Name:       PlanfactAPI
 * Plugin URI:        https://github.com/svlobachev/planfactapi
 * Description:       Это плагин регистрирует пользователей на вебсайте planfact.io через API.
 * Version:           1.0.0
 * Author:            Sergei Lobachev
 * Author URI:        https://github.com/svlobachev/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       planfactapi
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
define( 'TESTMODE', false );// включить, отключить режим тестирования

define( 'PLANFACTAPI_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-planfactapi-activator.php
 */
function activate_planfactapi() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-planfactapi-activator.php';
	Planfactapi_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-planfactapi-deactivator.php
 */
function deactivate_planfactapi() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-planfactapi-deactivator.php';
	Planfactapi_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_planfactapi' );
register_deactivation_hook( __FILE__, 'deactivate_planfactapi' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-planfactapi.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_planfactapi() {

	$plugin = new Planfactapi();
	$plugin->run();

}
run_planfactapi();
