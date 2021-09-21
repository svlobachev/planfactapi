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

    function remote_request_to_planfact() {
        $url = 'https://api.planfact.io/api/v1/';
        $link = $url.'businesses';
        $api_key = 'VuH3ENUjSrDnuvssxouAT5KuAvWyZFJe8pB67w8k3MRoYV8Bmc3aX7PeU6ZUzL4JKAoYCgPkwyDc76DE6xRYmZmpaxOs8Y7b';
        $data = [
                "email" => "6gsvlobachev@gmail.com",
                "countryIso2Code"=>  "Russia",
                "phoneNumber"=>  "+79525687080",
                "firstName"=>  "Sergei",
                "partnerApiKey"=>  $api_key
                ];
        $response = wp_remote_request($link, array(
            'headers'     => array('Content-Type' => 'application/json; charset=utf-8'),
            'body'        => json_encode($data),
            'method'      => 'POST',
            'data_format' => 'body',
        ));
        $arr_response = json_decode($response['body'], ARRAY_A);
        return $arr_response ;
    }
}