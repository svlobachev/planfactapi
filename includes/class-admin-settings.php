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



class Settings_display{
    function settings() {

        $obj = new Planfact_API_core();
        $result =$obj->remote_request_to_planfact();
        var_dump($result);
//        echo '<pre>';
//        print_r($result);
//        echo '<pre>';
    }
}
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
