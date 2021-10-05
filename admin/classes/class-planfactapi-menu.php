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
        global $wpdb;
        $table_name = $wpdb->get_blog_prefix() . 'planfactapi_settings';
        $result = $wpdb->get_results( "SELECT * FROM $table_name", ARRAY_A);
        foreach ( $result as $key => $row ) {
            if($row['name'] == 'api_key')$api_key = $row['value'];
            if($row['name'] == 'mail')$mail = $row['value'];
        }

        if(@$_POST['planfactapi_key']) $api_key = $_POST['planfactapi_key'];
        if(@$_POST['planfactapi_mail']) $mail  = $_POST['planfactapi_mail'];
        if(empty($api_key) || !isset($api_key)) $api_key = '';// поставим значение по умолчанию
        ?><div class="wrap">
        <h2><?php _e('Настройки') ?> Planfact API </h2>
        <form method="post" enctype="multipart/form-data" action="">
            <?php
            ?><br /><br />

            <?php _e('Ваш ПланФакт api_key?') ?>
            <label><input type="text" name="planfactapi_key" maxlength="100" size="45" value="<?php echo $api_key ?>"></label>
            <br /><br />
            <?php _e('Ваш маил для писем о регистрации?') ?>
            <label><input type="text" name="planfactapi_mail" maxlength="100" size="45" value="<?php echo $mail ?>"></label>

            <p class="submit">
                <input type="submit" class="button-primary" value="<?php _e('Применить изменения') ?>" />
            </p>
        </form>
        </div><?php
        if(@$_POST){//если пользователь отправил данные в форме

            $result = $wpdb->get_results( "SELECT * FROM $table_name", ARRAY_A);
            foreach ( $result as $row ) {
                if($row['name'] == 'api_key') {
                    $id = $row['id'];
                    $wpdb->update( $table_name,// обновим токен
                        [ 'value' => @$_POST['planfactapi_key']],
                        [ 'id' => $id ]
                    );
                }
                if($row['name'] == 'mail') {
                    $id = $row['id'];
                    $wpdb->update( $table_name,// обновим
                        [ 'value' => @$_POST['planfactapi_mail']],
                        [ 'id' => $id ]
                    );
                }
            }
            _e('Данные обновлены.');
        }
    }
}

