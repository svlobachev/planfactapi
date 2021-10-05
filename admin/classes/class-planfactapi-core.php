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
//                "countryIso2Code"=>  "Russia",
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
}