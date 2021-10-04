<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://github.com/svlobachev/planfactapi
 * @since      1.0.0
 *
 * @package    Planfactapi
 * @subpackage Planfactapi/admin/classes/
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Planfactapi
 * @subpackage Planfactapi/admin/classes/
 * @author     Sergei Lobachev <gsvlobachev@gmail.com>
 */
class Planfactapi_menu
{

    public function add_plugin_admin_menu() {

        /*
         * Add a settings page for this plugin to the Settings menu.
        */
        add_options_page(
            'My plugin and Base Options Functions Setup',
            'Planfact',
            'manage_options',
            'Settings',
            array($this, 'display_plugin_settings_page')
        );
    }

    //скрываем из общего меню Settings, останутся только ссылки в установщике плагинов
    function remove_menu_setting_links() {
        remove_submenu_page( 'options-general.php', 'Settings');
    }

    /**
     * Add settings action link to the plugins page.
     */

    public function add_action_links( $links ) {
        //Добавим ссылки в инсталятор плагинов
        $settings_link = array(
            '<a href="' . admin_url( 'options-general.php?page=Settings' ) . '">' . __('Настройки', 'Settings') . '</a>',
        );
        return array_merge(  $settings_link, $links);
    }
    public function display_plugin_settings_page() {
        // страница фейс с настройками плагина
        $obj = new Planfact_API_core();
        $obj->settings();
    }
}

