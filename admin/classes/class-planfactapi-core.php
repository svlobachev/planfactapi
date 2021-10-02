<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://github.com/svlobachev/planfactapi
 * @since      1.0.0
 *
 * @package    Planfactapi
 * @subpackage Planfactapi/admin/partials
 */

class Planfact_API_core{

    function remote_request_to_planfact($user_nicename, $user_email, $user_phone) {
        global $wpdb;
        $table_name = $wpdb->get_blog_prefix() . 'planfactapi_settings';
        $result = $wpdb->get_results( "SELECT * FROM $table_name", ARRAY_A);
        foreach ( $result as $key => $row ) {
            if($row['name'] == 'api_key')$api_key = $row['value'];
        }
        $url = 'https://api.planfact.io/api/v1/';
        $link = $url.'businesses';
//        $user_nicename = str_replace(' ','_', trim($user_nicename));
        $data = [
                "email" => $user_email,
                "countryIso2Code"=>  "Russia",
                "phoneNumber"=>  $user_phone,
                "firstName"=>  $user_nicename,
                "partnerApiKey"=>  $api_key
                ];
        $response = wp_remote_request($link, array(
            'headers'     => array('Content-Type' => 'application/json; charset=utf-8'),
            'body'        => json_encode($data),
            'method'      => 'POST',
            'data_format' => 'body',
        ));
        $obj_response = json_decode($response['body'] );
        return $obj_response ;
    }

    function settings() {
        global $wpdb;
        $table_name = $wpdb->get_blog_prefix() . 'planfactapi_settings';
        $result = $wpdb->get_results( "SELECT * FROM $table_name", ARRAY_A);
        foreach ( $result as $key => $row ) {
            if($row['name'] == 'api_key')$api_key = $row['value'];
        }

        if(@$_POST['planfactapi_key']) $api_key = $_POST['planfactapi_key'];
        if(empty($api_key) || !isset($api_key)) $api_key = '';// поставим значение по умолчанию
        ?><div class="wrap">
        <h2><?php _e('Настройки') ?> Planfact API </h2>
        <form method="post" enctype="multipart/form-data" action="">
            <?php
            ?><br /><br />
            <?php _e('Ваш ПланФакт api_key?') ?>

            <label><input type="text" name="planfactapi_key" maxlength="100" size="45" value="<?php echo $api_key ?>"></label>

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
            }
            _e('Данные обновлены.');
        }
    }
}