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
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        // создадим таблицу в БД WP
        global $wpdb;
        //создаем первую таблицу
        $table_name = $wpdb->get_blog_prefix() . 'planfactapi_settings';
        $charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset} COLLATE {$wpdb->collate}";
        $sql = "CREATE TABLE {$table_name} (
	    id int(11) unsigned NOT NULL auto_increment,
	    name varchar(255) NULL,
	    value varchar(255) NULL,
	    PRIMARY KEY  (id),
	    KEY id (id)
		) {$charset_collate};";
        dbDelta( $sql );// Создать таблицу.

        // если таблица пуста вставим значение по умолчанию
        $result = $wpdb->get_results( "SELECT * FROM $table_name", ARRAY_A);
        if(empty($result)) {// если таблица пуста вставим значение по умолчанию
            $wpdb->insert( $table_name, [ 'name' => 'api_key', 'value' => 'Ваш ПланФакт api_key'], [ '%s', '%s' ] );
            $wpdb->insert( $table_name, [ 'name' => 'mail', 'value' => 'Ваш маил для писем о ргистрации'], [ '%s', '%s' ] );
        }

        $table_name = $wpdb->get_blog_prefix() . 'planfactapi_users';
        $charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset} COLLATE {$wpdb->collate}";
        $sql = "CREATE TABLE {$table_name} (
	    id int(11) unsigned NOT NULL auto_increment,
	    name varchar(255) NULL,
	    mail varchar(255) NULL,
	    phone varchar(255) NULL,
	    api_key text NULL,
	    bz_key varchar(255) NULL,
	    timestamp varchar(255) NULL,
	    PRIMARY KEY  (id),
	    KEY id (id)
		) {$charset_collate};";
        dbDelta( $sql );// Создать таблицу.

        if(TESTMODE) {
            // Пишем лог ошибок при активации которые нужно исправить
            // в противном случае при активации может появиться неприятная надпись типа:
            // "Плагин создал х символов неожиданного вывода при активации."
            // Иногда это завешивает другие плагины, например Elementor"
            file_put_contents(plugin_dir_path(__DIR__) . 'activation.log', ob_get_contents());
        }
	}
}