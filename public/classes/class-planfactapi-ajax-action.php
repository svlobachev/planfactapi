<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://github.com/svlobachev/planfactapi
 * @since      1.0.0
 *
 * @package    Planfactapi
 * @subpackage Planfactapi/public/classes/
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Planfactapi
 * @subpackage Planfactapi/public/classes/
 * @author     Sergei Lobachev <gsvlobachev@gmail.com>
 */
class Planfactapi_public_ajax_action
{
    /**
     * Обработка скрипта
     *
     * @see https://wpruse.ru/?p=3224
     */
    function ajax_action_callback() {

        // Массив ошибок
        $err_message = array();

        // Проверяем nonce. Если проверкане прошла, то блокируем отправку
        if ( ! wp_verify_nonce( $_POST['nonce'], 'regform-nonce' ) ) {
            wp_die( 'Данные отправлены с левого адреса' );
        }

        // Проверяем на спам. Если скрытое поле заполнено или снят чек, то блокируем отправку
        if ( false === $_POST['art_anticheck'] || ! empty( $_POST['art_submitted'] ) ) {
            wp_die( 'Пока!' );
        }

        // Проверяем полей имени, если пустое, то пишем сообщение в массив ошибок
        if ( empty( $_POST['art_name'] ) || ! isset( $_POST['art_name'] ) ) {
            $err_message['name'] = 'Пожалуйста, введите ваше имя.';
        } else {
            $art_name = sanitize_text_field( $_POST['art_name'] );
        }

        // Проверяем полей емайла, если пустое, то пишем сообщение в массив ошибок
        if ( empty( $_POST['art_email'] ) || ! isset( $_POST['art_email'] ) ) {
            $err_message['email'] = 'Пожалуйста, введите адрес вашей электронной почты.';
        } elseif ( ! preg_match( '/^[[:alnum:]][+a-z0-9_.-]*@[a-z0-9.-]+\.[a-z]{2,4}$/i', $_POST['art_email'] ) ) {
            $err_message['email'] = 'Адрес электронной почты некорректный.';
        } else {
            $art_email = sanitize_email( $_POST['art_email'] );

        }
        // Проверяем полей темы письма, если пустое, то пишем сообщение по умолчанию
        if ( empty( $_POST['art_subject'] ) || ! isset( $_POST['art_subject'] ) ) {
            $art_subject = 'Зарегистрирован новый пользователь ПланФакт';
        } else {
            $art_subject = sanitize_text_field( $_POST['art_subject'] );
        }

        // Проверяем полей сообщения, если пустое, то пишем сообщение в массив ошибок
        if ( empty( $_POST['art_phone'] ) || ! isset( $_POST['art_phone'] ) ) {
            $err_message['phone'] = 'Пожалуйста, введите ваш телефон.';
        } elseif(!filter_var( $_POST[ 'art_phone' ] ,FILTER_VALIDATE_INT)){
            $err_message['phone'] = 'Пожалуйста, введите реальный номер телефона.';
        }
        else {
            $art_phone = sanitize_textarea_field( $_POST['art_phone'] );

        }        // Проверяем полей checkbox, если пустое, то пишем сообщение в массив ошибок
        if ( empty( $_POST['art_checkbox'] ) || ! isset( $_POST['art_checkbox'] ) ) {
            $err_message['checkbox'] = 'Пожалуйста, согласитесь с условиями.';
        }

        if (empty($err_message)  ) {//если нет ошибок формы то запрашиваем ПланФакт по API

            $obj = new Planfact_API_core();
            $user_planfact_regintration_info = $obj->remote_request_to_planfact($art_name, $art_email, $art_phone);
            if ($user_planfact_regintration_info->isSuccess == false) {
                $err_message['email'] = $user_planfact_regintration_info->errorMessage;
            } else {
                $apiKey = $user_planfact_regintration_info->data->apiKey;
                $businessId = $user_planfact_regintration_info->data->businessId;
            }
        }
        file_put_contents(plugin_dir_path(__DIR__) . 'debug.log', print_r($err_message, 1));
        // Проверяем массив ошибок, если не пустой, то передаем сообщение. Иначе отправляем письмо
        if ( $err_message ) {

            wp_send_json_error( $err_message );

        } else {
            // вытаскиваем из базы маил для отсылки писем о регистрации
            global $wpdb;
            $table_name = $wpdb->get_blog_prefix() . 'planfactapi_settings';
            $result = $wpdb->get_results( "SELECT * FROM $table_name", ARRAY_A);
            foreach ( $result as $key => $row ) {
                if($row['name'] == 'mail')$email_to = $row['value'];
            }

            // пишем юзера в базу
            global $wpdb;
            $table_name = $wpdb->get_blog_prefix() . 'planfactapi_users';
            $wpdb->insert( $table_name, [
                'name' => $art_name,
                'mail' => $art_email,
                'phone' => $art_phone,
                'api_key' => $apiKey,
                'bz_key' => $businessId,
                'timestamp' => time()
            ],
                [ '%s', '%s', '%s','%s', '%s', '%s'] );

//            if(TESTMODE)file_put_contents(plugin_dir_path(__DIR__) . 'debug.log', print_r($email_to, 1));
            // Если адресат не указан, то берем данные из настроек сайта
            if ( ! $email_to || $email_to == '') {
                $email_to = get_option( 'admin_email' );
            }


            $body = "Имя пользователя: $art_name" . "\r\n\r\n";
            $body .= "Телефон: $art_phone" . "\r\n\r\n";
            $body .= "Маил: $art_email" . "\r\n\r\n";
//            $body .= "BusinessId: $businessId" . "\r\n\r\n";
//            $body .= "ApiKey: $apiKey" . "\r\n\r\n";
            $headers = 'From: БезФинДир <' . $email_to . '>' . "\r\n" . 'Reply-To: ' . $email_to;

            // Отправляем письмо
            wp_mail( $email_to, $art_subject, $body, $headers );

            // Отправляем сообщение об успешной отправке
            $message_success = 'Регистрация почти завершена. Пожалуйста, проверьте  вашу почту и подтвердите регистрацию.';
            wp_send_json_success( $message_success );
        }

        // На всякий случай убиваем еще раз процесс ajax
        wp_die();

    }
}

