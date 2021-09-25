<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://github.com/svlobachev/planfactapi
 * @since      1.0.0
 *
 * @package    Planfactapi
 * @subpackage Planfactapi/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Planfactapi
 * @subpackage Planfactapi/admin
 * @author     Sergei Lobachev <gsvlobachev@gmail.com>
 */
class Planfactapi_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Planfactapi_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Planfactapi_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/planfactapi-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Planfactapi_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Planfactapi_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/planfactapi-admin.js', array( 'jquery' ), $this->version, false );

	}
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
            '<a href="' . admin_url( 'options-general.php?page=Settings' ) . '">' . __('Settings', 'Settings') . '</a>',
        );
        return array_merge(  $settings_link, $links);
    }

    /**
     * Render the settings page for this plugin.
     */

    public function display_plugin_settings_page() {
        // страница фейс с настройками плагина
        $obj = new Settings_display();
        $obj->settings();
    }

    function regform_show_fields() {

        $phone = ! empty( $_POST[ 'phone' ] ) ? $_POST[ 'phone' ] : '';
        ?>
        <p>
            <label for="phone">Телефон</label>
            <input type="text" id="phone" name="phone" class="input" value="<?php echo esc_attr( $phone ) ?>" size="12" />
        </p>
        <?php
    }

    function regform_check_fields( $errors) {
        if( empty( $_POST[ 'phone' ] ) ) {
            $errors->add( 'empty_phone', '<strong>ОШИБКА:</strong> Укажите телефон пожалуйста.' );
        }
        $obj = new Planfact_API_core();
        $user_planfact_regintration_info= $obj->remote_request_to_planfact(
                sanitize_text_field( $_POST['user_login']),
                sanitize_text_field( $_POST['user_email']),
                sanitize_text_field( $_POST[ 'phone' ] ) );



        if($user_planfact_regintration_info->isSuccess == false){
            $errors->add( 'errorMessage', "<strong>ОШИБКА:</strong> $user_planfact_regintration_info->errorMessage." );
        }
        else{
            $_POST['apiKey'] = $user_planfact_regintration_info->data->apiKey;
            $_POST['businessId'] = $user_planfact_regintration_info->data->businessId;
        }
        return $errors;
    }

    function regform_register_fields( $user_id ) {
        $obj = new Planfact_API_core();
        update_user_meta( $user_id, 'phone', sanitize_text_field( $_POST[ 'phone' ] ));
        update_user_meta( $user_id, 'apiKey', sanitize_text_field( $_POST[ 'apiKey' ] ));
        update_user_meta( $user_id, 'businessId', sanitize_text_field( $_POST[ 'businessId' ] ));
    }

    // добавляем в админку
    function regform_admin_registration_form( $operation ) {

        if ( 'add-new-user' !== $operation ) {
            // $operation может так же принимать значение 'add-existing-user' для мультисайта
            return;
        }

        $phone = ! empty( $_POST[ 'phone' ] ) ? $_POST[ 'phone' ] : '';

        ?>
        <h3>Дополнительная информация</h3>

        <table class="form-table">
            <tr class="form-field">
                <th><label for="phone">Телефон</label></th>
                <td><input id="phone" name="phone" class="input" type="text" value="<?php echo esc_attr( $phone ) ?>" /></td>
            </tr>
        </table>
        <?php
    }

    function regform_validate_fields_in_admin( $errors, $update, $user ) {

        if ( $update ) {
            return;
        }

        if( empty( $_POST[ 'phone' ] ) ) {
            $errors->add( 'empty_phone', '<strong>ОШИБКА:</strong> Укажите телефон пожалуйста.' );
        }
    }

    function regform_register_admin_fields( $user_id ) {

        update_user_meta( $user_id, 'phone', sanitize_text_field( $_POST[ 'phone' ] ) );

    }

    function regform_show_profile_fields( $user ) {

        // выводим заголовок для наших полей
        echo '<h3>Дополнительная информация</h3>';

        // поля в профиле находятся в рамметке таблиц <table>
        echo '<table class="form-table">';

        // добавляем поле телефон
        $phone = get_the_author_meta( 'phone', $user->ID );
        echo '<tr><th><label for="phone">Телефон</label></th>
        <td><input type="text" name="phone" id="phone" value="' . esc_attr( $phone ) . '" class="regular-text" /></td>
        </tr>';
        echo '</table>';
    }

    function regform_save_profile_fields( $user_id ) {
        update_user_meta( $user_id, 'phone', sanitize_text_field( $_POST[ 'phone' ] ) );
    }

    //if(TESTMODE)file_put_contents(plugin_dir_path(__DIR__) . 'debug.log', print_r($errors, 1));
}