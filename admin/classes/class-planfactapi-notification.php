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
class Planfactapi_notification
{
    function custom_wp_new_user_notification_email_admin( $wp_new_user_notification_email, $user, $blogname ) {

        $phone = get_the_author_meta( 'phone', $user->ID );
        $mail = $user->user_email;
//        $apiKey = get_the_author_meta( 'apiKey', $user->ID );
//        $businessId = get_the_author_meta( 'businessId', $user->ID );
        $message = "Имя пользователя: $user->user_login" . "\r\n\r\n";
        $message .= "Телефон: $phone" . "\r\n\r\n";
        $message .= "Маил: $mail" . "\r\n\r\n";
//        $message .= "ApiKey: $apiKey" . "\r\n\r\n";
//        $message .= "BusinessId: $businessId" . "\r\n\r\n";
        $wp_new_user_notification_email['message'] = $message;

        $wp_new_user_notification_email['headers'] = 'From: Сайт БезФинДир <bezfindir@domain.ext>'; // this just changes the sender name and email to whatever you want (instead of the default WordPress <wordpress@domain.ext>

        return $wp_new_user_notification_email;
    }

    function notify_only_admin( $user_id, $notify = 'admin' )
    {
        wp_send_new_user_notifications( $user_id, $notify );
    }
}

