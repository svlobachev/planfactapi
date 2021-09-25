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
        global $wpdb; // запрашиваем БД WP
        $table_name = $wpdb->get_blog_prefix() . 'planfactapi_settings';
        $result = $wpdb->get_results( "SELECT * FROM $table_name", ARRAY_A);
        foreach ( $result as $key => $row ) {
            if($row['name'] == 'api_key')$api_key = $row['value'];
        }
        $url = 'https://api.planfact.io/api/v1/';
        $link = $url.'businesses';
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

//        stdClass Object
//        (
//            [data] => stdClass Object
//            (
//                [apiKey] => HuOltjHipb34TsJG94Irv9iHe_6SljEreQ1V_xQBonzE42IH4PrMXbkwt6zEId0YL2n72x3F8asFYHPKAjviTiAzBP4HV03ac9NKta5aeOt35k-ORdRXcUlYQwPtdjmimRv7evDhOl8s8qOsQwHFK9BosEqX4-fMGIJomQu8L6Vus_uaySJjecA0b2KWYYgt2MWxIOUDk-Us57WdVcYGENHELrV2Nrcc83-_BH9DMXdhOXH4Q8HPNOTyiDfPAz1F6_IvBwRoefRVTAVwwblwbvQ8shai-RB_gp7H1bxS-_Fkz43ULRp213sW6PH-xbA2zupbWI5NS33uWwyE8UI5U851MUxbemwg1yIHO0_DWrwdwyHlZI0NOaLqpoAZSGAvkPWVyKWj75kxmaQaGlbnZBq9LCqM9HG6J01UheN-fF1jsz4y0NycQJzhqw_yyO6jDBKA2XJ2N50wQRWKrmcUFDcQDkShV35PB7bKwjknPgaXnsS5pJWSKFvB0b3swXbV8MoZcel19Iym_q7_6fjMB44xzzE0QqkxH_uRCbkBLTFj46NNBxfUc1gqj872Sq6M6p9ke_ky3rZKqAU5Mx-aduGzTI8
//                [businessId] => 37279758-9254-4a21-95db-c7166c4e378f
//                [businessTitle] =>
//            )
//
//            [isSuccess] => 1
//            [errorMessage] =>
//            [errorCode] =>
//        )

//        http://localhost/wp-login.php?checkemail=registered
    }

}