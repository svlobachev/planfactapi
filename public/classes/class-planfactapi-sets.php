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
class Planfactapi_public_sets
{
    function art_feedback_scripts() {

        // Обрабтка полей формы
        wp_enqueue_script( 'jquery-form' );

//         Подключаем файл скрипта
        wp_enqueue_script(
            'feedback',
//            get_stylesheet_directory_uri() . '/js/feedback.js',
            array( 'jquery' ),
            1.0,
            true
        );

        // Задаем данные обьекта ajax
        wp_localize_script(
            'feedback',
            'feedback_object',
            array(
                'url'   => admin_url( 'admin-ajax.php' ),
                'nonce' => wp_create_nonce( 'feedback-nonce' ),
            )
        );

    }
}

