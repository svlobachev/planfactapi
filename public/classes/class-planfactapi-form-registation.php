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
class Planfactapi_public_regform
{
    function art_feedback() {
        $phone_cities_codes = [
            '+7',
            '+375',
            '+994',
            '+374',
            '+995'
        ];
        ob_start();
        ?>
        <form novalidate id="add_feedback">
            <div>
                <label for="art_name"></label><input type="text" maxlength="100"  name="art_name" id="art_name" class="required art_name " placeholder="Ваше имя" value=""/>
            </div>
            <label for="art_email"></label><input type="email" maxlength="100" name="art_email" id="art_email" class="required art_email" placeholder="Ваш E-Mail" value=""/>

<!--            <input type="text" name="art_subject" id="art_subject" class="art_subject" placeholder="Тема сообщения" value=""/>-->

            <div style="display:  flex" >
                <label for="art_phone_code"></label><select id="art_phone_code" name="art_phone_code" class="required art_phone_code" >
                    ?> <?php
                    foreach ($phone_cities_codes as $city_code){
//                        if($city_code == $art_phone_code && $art_phone_code != '') $selected = 'selected';
//                        else $selected = '';
                        echo" <option name='art_phone_code' value=$city_code  >$city_code</option>";
                    }
                    ?>
                    <label for="art_phone"></label><input type="text" maxlength="12" name="art_phone" id="art_phone" placeholder="Ваш телефон" class="required art_phone"/>

            </div>


            <label for="art_anticheck"></label><input type="checkbox" name="art_anticheck" id="art_anticheck" class="art_anticheck" style="display: none !important;" value="true" checked="checked"/>

            <label for="art_submitted"></label><input type="text" name="art_submitted" id="art_submitted" value="" style="display: none !important;"/>

            <label for="art_checkbox"></label><input type="checkbox" checked="checked" id="art_checkbox" name="art_checkbox" class="art_checkbox"/>
                Я принимаю условия <a href="https://planfact.io/agreement?roistat_visit=1030595&amp;_ga=2.55426027.1170263857.1632856107-277372238.1632856107" rel="noopener noreferrer" target="_blank">Пользовательского соглашения</a>
                и
                <a href="https://planfact.io/security-policy?roistat_visit=1030595" target="_blank">Политики конфиденциальности</a>


            <input type="submit" id="submit-feedback" class="button" value="Отправить"/>


        </form>
        <?php
        return ob_get_clean();
    }
}

