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
class Planfactapi_wp_regform_tuning
{
    function regform_show_fields() {
        $phone_code = ! empty( $_POST[ 'phone_code' ] ) ? $_POST[ 'phone_code' ] : '';
        $phone = ! empty( $_POST[ 'phone' ] ) ? $_POST[ 'phone' ] : '';
        $checkbox = ! empty( $_POST[ 'checkbox' ] ) ? $_POST[ 'checkbox' ] : '';
        $phone_cities_codes = [
            '+7',
            '+375',
            '+994',
            '+374',
            '+995'
        ];

        ?>

        <style>
            .phone_code {
                font-size: 15px;
                line-height: 1.33333333; /* 32px */
                width: 30%;
                border-width: 0.0625rem;
                padding: 0.1875rem 0.3125rem; /* 3px 5px */
                margin: 0 6px 16px 0;
                min-height: 40px;
                max-height: none;
            }
        </style>

        <label for="phone">Телефон</label>
        <div style="display:  flex" >
            <label for="phone_code"></label><select id="phone_code" name="phone_code" class="phone_code" >
                ?> <?php
                foreach ($phone_cities_codes as $city_code){
                    if($city_code == $phone_code && $phone_code != '') $selected = 'selected';
                    else $selected = '';
                    echo" <option name='phone_code' value=$city_code $selected >$city_code</option>";
                }
                ?>
            </select>
            <input type="text" id="phone" name="phone" class="input" value="<?php echo esc_attr( $phone ) ?>" />

        </div>

        <label for="checkbox"><input type="checkbox" checked="checked" id="checkbox" name="checkbox" class="" value="<?php echo esc_attr( $checkbox ) ?> "/>
            Я принимаю условия <a href="https://planfact.io/agreement?roistat_visit=1030595&amp;_ga=2.55426027.1170263857.1632856107-277372238.1632856107" rel="noopener noreferrer" target="_blank">Пользовательского соглашения</a>
            и
            <a href="https://planfact.io/security-policy?roistat_visit=1030595" target="_blank">Политики конфиденциальности</a>
        </label>
        <?php
    }

    function regform_check_fields( $errors) {
        if( empty( $_POST[ 'phone' ] ) ) {
            $errors->add( 'empty_phone', '<strong>ОШИБКА:</strong> Укажите телефон пожалуйста.' );
//            return $errors;
        }
        if( empty( $_POST[ 'checkbox' ]  ) ) {
            $errors->add( 'empty_checkbox', '<strong>ОШИБКА:</strong> Примите условия соглашения пожалуйста.' );
//            return $errors;
        }
        if(!filter_var( $_POST[ 'phone' ] ,FILTER_VALIDATE_INT)){
            $errors->add( 'empty_checkbox', '<strong>ОШИБКА:</strong> Введите реальный номер телефона пожалуйста.' );
//            return $errors;
        }
        $obj = new Planfact_API_core();
        $phone= $_POST[ 'phone_code' ] .$_POST[ 'phone' ];
        $user_planfact_regintration_info= $obj->remote_request_to_planfact(
            sanitize_text_field( $_POST['user_login']),
            sanitize_text_field( $_POST['user_email']),
            sanitize_text_field( $phone ) );


        if(TESTMODE)file_put_contents(plugin_dir_path(__DIR__) . 'debug.log', print_r($errors, 1));
        if($user_planfact_regintration_info->isSuccess == false){
            $errors->add( 'errorMessage', "<strong>ОШИБКА:</strong> $user_planfact_regintration_info->errorMessage." );
//            return $errors;
        }
        else{
            $_POST['apiKey'] = $user_planfact_regintration_info->data->apiKey;
            $_POST['businessId'] = $user_planfact_regintration_info->data->businessId;
        }
        return $errors;
    }

    function regform_register_fields( $user_id ) {

//        update_user_meta( $user_id, 'phone_code', sanitize_text_field( $_POST[ 'phone_code' ] ));
        update_user_meta( $user_id, 'phone', sanitize_text_field( $_POST[ 'phone_code' ].$_POST[ 'phone' ] ));
        update_user_meta( $user_id, 'apiKey', sanitize_text_field( $_POST[ 'apiKey' ] ));
        update_user_meta( $user_id, 'businessId', sanitize_text_field( $_POST[ 'businessId' ] ));


    }

    function allow_cyrillic_usernames($username, $raw_username, $strict) {// разрешаем регистрацию с кириллическим Юзернеймом

        $username = wp_strip_all_tags( $raw_username );
        $username = remove_accents( $username );
        // Kill octets
        $username = preg_replace( '|%([a-fA-F0-9][a-fA-F0-9])|', '', $username );
        $username = preg_replace( '/&.+?;/', '', $username ); // Kill entities

        // If strict, reduce to ASCII and Cyrillic characters for max portability.
        if ( $strict )
            $username = preg_replace( '|[^a-zа-я0-9 _.\-@]|iu', '', $username );

        $username = trim( $username );
        // Consolidate contiguous whitespace
        $username = preg_replace( '|\s+|', ' ', $username );


        if(TESTMODE)file_put_contents(plugin_dir_path(__DIR__) . 'debug.log', print_r($username, 1));


        return $username;
    }

    //if(TESTMODE)file_put_contents(plugin_dir_path(__DIR__) . 'debug.log', print_r($errors, 1));
}

