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
class Planfactapi_fields
{
    // добавляем в админку
    function admin_registration_form( $operation ) {

        if ( 'add-new-user' !== $operation ) {
            // $operation может так же принимать значение 'add-existing-user' для мультисайта
            return;
        }

        $phone = ! empty( $_POST[ 'phone' ] ) ? $_POST[ 'phone' ] : '';

        ?>
        <h3>Дополнительная информация</h3>

        <table class="form-table">
            <tr class="form-field">
                <th><label for="phone">Телефон</label></th>
                <td><input id="phone" name="phone" class="input" type="text" value="<?php echo esc_attr( $phone ) ?>" /></td>
            </tr>
        </table>
        <?php
    }

    function validate_fields_in_admin( $errors, $update, $user ) {

        if ( $update ) {
            return;
        }

        if( empty( $_POST[ 'phone' ] ) ) {
            $errors->add( 'empty_phone', '<strong>ОШИБКА:</strong> Укажите телефон пожалуйста.' );
        }
    }

    function register_admin_fields( $user_id ) {

        update_user_meta( $user_id, 'phone', sanitize_text_field( $_POST[ 'phone' ] ) );

    }

    function regform_show_profile_fields( $user ) {

        // выводим заголовок для наших полей
        echo '<h3>Дополнительная информация</h3>';

        // поля в профиле находятся в рамметке таблиц <table>
        echo '<table class="form-table">';

        // добавляем поле телефон
        $phone = get_the_author_meta( 'phone', $user->ID );
        $apiKey = get_the_author_meta( 'apiKey', $user->ID );
        $businessId = get_the_author_meta( 'businessId', $user->ID );
        echo '<tr><th><label for="phone">Телефон</label></th>
        <td><input type="text" name="phone" id="phone" value="' . esc_attr( $phone ) . '" class="regular-text" /></td>
        </tr>';
        echo '<tr><th><label for="apiKey">ApiKey</label></th>
        <td><input readonly type="text" name="apiKey" id="apiKey" value="' . esc_attr( $apiKey ) . '" class="regular-text" /></td>
        </tr>';
        echo '<tr><th><label for="businessId">BusinessId</label></th>
        <td><input readonly type="text" name="businessId" id="businessId" value="' . esc_attr( $businessId ) . '" class="regular-text" /></td>
        </tr>';
        echo '</table>';
    }

    function regform_save_profile_fields( $user_id ) {
        update_user_meta( $user_id, 'phone', sanitize_text_field( $_POST[ 'phone' ] ) );
    }
}

