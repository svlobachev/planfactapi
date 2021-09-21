<?php

/**
 * Fired during plugin activation
 *
 * @link       https://github.com/svlobachev/planfactapi
 * @since      1.0.0
 *
 * @package    Planfactapi
 * @subpackage Planfactapi/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Planfactapi
 * @subpackage Planfactapi/includes
 * @author     Sergei Lobachev <gsvlobachev@gmail.com>
 */
class Planfactapi_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

        // Пишем лог ошибок при активации которые нужно исправить
        // в противном случае при активации может появиться неприятная надпись типа:
        // "Плагин создал х символов неожиданного вывода при активации."
        file_put_contents( plugin_dir_path( __DIR__ ). 'activation.log', ob_get_contents() );

	}

}