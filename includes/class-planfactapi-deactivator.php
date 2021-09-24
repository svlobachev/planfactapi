<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://github.com/svlobachev/planfactapi
 * @since      1.0.0
 *
 * @package    Planfactapi
 * @subpackage Planfactapi/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Planfactapi
 * @subpackage Planfactapi/includes
 * @author     Sergei Lobachev <gsvlobachev@gmail.com>
 */
class Planfactapi_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
        try{
            @unlink(plugin_dir_path( __DIR__ ). 'activation.log');
            @unlink(plugin_dir_path( __DIR__ ). 'debug.log');
        } catch (Exception $e) {
            if(TESTMODE) echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
    }
}
