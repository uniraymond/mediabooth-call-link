<?php
/**
 * Created by Media Booth.
 * User: Raymond F.
 * Date: 2017-10-17
 */
?>

<?php
/**
 * @package MediaboothCallLinkPlugin
 */

use Inc\Option;

add_action('admin_menu', 'register_mcl_page');
add_action('admin_init', 'mcl_options_init');

$mcl_options = Option::mcl_get_options();

$mcl_options['active'] = isset($mcl_options['action']) ? 1 : 0;
$mcl_options['classic'] = isset($mcl_options['classic']) ? 1 : 0;

$plugin_title = apply_filters('mcl_plugin_title', 'Mediabooth Call Link');

$mcl_updated = Option::set_basic_options();

function register_mcl_page() {
    global $plugin_title;
    $page = add_submenu_page('options-general.php', $plugin_title, $plugin_title, 'manage_options', 'mediabooth-call-link', 'mcl_admin_setting_page');
    add_action('admin_print_styles-' . $page , 'mcl_admin_style');;
}

function mcl_admin_style() {
    wp_enqueue_style('mcl_style');
}

function mcl_options_init() {
    register_setting('mcl_options', 'mcl');
    wp_register_style('mcl_style', MCL_PLUGIN_DIR . 'admin/css/mcl_admin.css', false, MCL_VERSION, 'all');
}

function mcl_admin_setting_page() {
    global $mcl_options;
    global $plugin_title;
    global $mcl_updated;
?>

    <div class="wrap">
        <h1>Media Booth Call Link <span class="version">v.<?php echo MCL_VERSION;?></span></h1>
        <?php
        if (!$mcl_options['activity'] == 1) {
            echo "<div class='notice-error notice'><p>The call link plugin is currently <b>inactive</b>.</p></div>";
        }
        ?>
    </div>

<form method="post" action="./options.php" class="mcl-container">
    <?php settings_fields('mcl_options'); ?>
    <table class="form-table">
        <tr valign="top">
            <th scope="row">Link Status:</th>
            <td class="activated">
                <input id="activated" name="mcl[activity]" type="checkbox" value="1" <?php checked('1', $mcl_options['activity']); ?> /> <label title="Enable" for="activated">Enabled</label> &nbsp; &nbsp;
            </td>
        </tr>
        <tr valign="top"><th scope="row">Phone number:</th>
            <td><input type="text" name="mcl[number]" value="<?php echo $mcl_options['number']; ?>" /></td>
        </tr>
        <tr valign="top"><th scope="row">Position</th>
            <td class="appearance">
                <div class="radio-item">
                    <input type="radio" id="appearance1" name="mcl[appearance]" value="right" <?php checked('right', $mcl_options['appearance']); ?>>
                    <label title="right" for="appearance1">Right corner</label>
                </div>
                <div class="radio-item">
                    <input type="radio" id="appearance2" name="mcl[appearance]" value="left" <?php checked('left', $mcl_options['appearance']); ?>>
                    <label title="left" for="appearance2">Left corner</label>
                </div>
                <div class="radio-item">
                    <input type="radio" id="appearance3" name="mcl[appearance]" value="middle" <?php checked('middle', $mcl_options['appearance']); ?>>
                    <label title="middle" for="appearance3">Center bottom</label>
                </div>
                <div class="radio-item">
                    <input type="radio" id="appearance4" name="mcl[appearance]" value="full" <?php checked('full', $mcl_options['appearance']); ?>>
                    <label title="full" for="appearance4">Full bottom</label>
                </div>
            </td>
        </tr>
        <tr valign="top" class="appearance">
            <th scope="row">Limit appearance:</th>
            <td>
                <input type="text" name="mcl[show]" value="<?php echo $mcl_options['show']; ?>" />
                <p class="description">Enter IDs of the posts &amp; pages the Call Now Button should appear on (leave blank for all).</p>
            </td>
        </tr>
        <!-- custom setup working hours -->
        <tr valign="top" class="classic">
            <th scope="row">Working Date:</th>
            <td>
                <div class="date-picker">
                    <input id="classic" name="mcl[monday]" type="text" value="<?php echo $mcl_options['monday']; ?>" placeholder="Working Hours hh:mm"/>
                    <label title="Monday Working Hours" for="monday">Monday Working Hours</label>
                </div>
                <div class="date-picker">
                    <input id="classic" name="mcl[tuesday]" type="text" value="<?php echo $mcl_options['tuesday']; ?>" placeholder="Working Hours hh:mm" />
                    <label title="Tuesday Working Hours" for="tuesday">Tuesday Working Hours</label>
                </div>
                <div class="date-picker">
                    <input id="classic" name="mcl[wednesday]" type="text" value="<?php echo $mcl_options['wednesday']; ?>" placeholder="Working Hours hh:mm" />
                    <label title="Wednesday Working Hours" for="wednesday">Wednesday Working Hours</label>
                </div>
                <div class="date-picker">
                    <input id="classic" name="mcl[thursday]" type="text" value="<?php echo $mcl_options['thursday']; ?>" placeholder="Working Hours hh:mm" />
                    <label title="Thursday Working Hours" for="thursday">Thursday Working Hours</label>
                </div>
                <div class="date-picker">
                    <input id="classic" name="mcl[friday]" type="text" value="<?php echo $mcl_options['friday']; ?>" placeholder="Working Hours hh:mm" />
                    <label title="Friday Working Hours" for="friday">Friday Working Hours</label>
                </div>
                <div class="date-picker">
                    <input id="classic" name="mcl[saturday]" type="text" value="<?php echo $mcl_options['saturday']; ?>" placeholder="Working Hours hh:mm" />
                    <label title="Saturday Working Hours" for="saturday">Saturday Working Hours</label>
                </div>
                <div class="date-picker">
                    <input id="classic" name="mcl[sunday]" type="text" value="<?php echo $mcl_options['sunday']; ?>" placeholder="Working Hours hh:mm" />
                    <label title="Sunday Working Hours" for="monday">Sunday Working Hours</label>
                </div>
            </td>
        </tr>
        <tr valign="top" class="classic">
            <th scope="row">Holiday Date:</th>
            <td>
                <div class="date-picker">
                    <textarea id="classic" name="mcl[holiday]" type="text" placeholder="Holidays date"><?php echo $mcl_options['holiday']; ?></textarea>
                    <label title="Holidays" for="holiday">Holidays</label>
                </div>
            </td>
        </tr>
        <tr valign="top" class="classic">
            <th scope="row">After Hour Email:</th>
            <td>
                <div class="after_email">
                    <input id="classic" name="mcl[emailafterhour]" type="email" value="<?php echo $mcl_options['emailafterhour']; ?>" />
                    <label title="After Hours Callback Email" for="emailafterhour">After Hours Callback Email</label>
                </div>
            </td>
        </tr>
    </table>
    <input type="hidden" name="mcl[version]" value="<?php echo MCL_VERSION; ?>" />
    <p class="submit"><input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" /></p>
</form>
<?php
}
?>

<?php

function mcl_get_scripts() {
    if (is_admin()) {
        return;
    }

    wp_deregister_script('jquery');
    wp_register_script('jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js', false, '1.8.1');
    wp_enqueue_script('jquery');

    wp_deregister_script('jquerymagnificpopupjs');
    wp_register_script('jquerymagnificpopupjs', 'https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/jquery.magnific-popup.min.js', false, '1.1.0');
    wp_enqueue_script('jquerymagnificpopupjs');

    wp_deregister_style('jquerymagnificpopupcss');
    wp_register_style('jquerymagnificpopupcss', 'https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/magnific-popup.min.css', false, '1.1.0');
    wp_enqueue_script('jquerymagnificpopupcss');

}
add_action('init', 'mcl_get_scripts');
?>