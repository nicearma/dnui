<?php
/*
  Plugin Name: DNUI (Delete not used images)
  Version: 1.5.4
  Plugin URI: http://www.nicearma.com/delete-not-used-image-wordpress-dnui/
  Author: Nicearma
  Author URI: http://www.nicearma.com/
  Text Domain: dnui
  Description: This plugin will delete all not used images file, the plugin search all image, not referred by any post and page of wordpress
 */

/*
  Copyright (c) 2014 http://www.nicearma.com
  Released under the GPL license
  http://www.gnu.org/licenses/gpl.txt
 */

include_once 'php/dnuiL.php';

add_action('admin_init', 'DNUI_js');
//add_action('wp_enqueue_scripts', 'DNUI_js');
add_action('admin_menu', 'DNUI_option_menu');

function DNUI_option_menu() {
    $dnuiOption = unserialize(get_option("dnui_options"));
    $dnuiOptionDefault = DNUI_default();
    //For the update problem
    if (key_exists('version', $dnuiOption)) {
        if ($dnuiOption['version'] != '1.5') {
            foreach ($dnuiOption as $key => $value) {
                if (key_exists($key, $dnuiOptionDefault)) {
                    $dnuiOptionDefault[$key] = $value;
                }
            }
            DNUI_add_option($dnuiOptionDefault);
        }
    } else {
        DNUI_add_option($dnuiOptionDefault);
    }

    add_options_page('DNUI option', 'DNUI', 'activate_plugins', basename(__FILE__), 'DNUI');
}

function DNUI_js() {
    wp_register_style('dnui-css', plugins_url('css/dnui.css', __FILE__));
    //wp_register_script('dnui-js-views', plugins_url('js/dnui_views.js', __FILE__), array('backbone', 'jquery', 'jquery-ui-tabs'));
    wp_register_script('dnui-js', plugins_url('js/dnui.js', __FILE__), array('backbone', 'jquery', 'jquery-ui-tabs'));
}

function DNUI() {
    include_once 'html/backup.php';
    include_once 'html/option.php';
    include_once 'html/tableDb.php';

    add_thickbox();
    wp_enqueue_style('dnui-css');
    wp_enqueue_script('dnui-js');
    ?>

    <p><?php _e('DNUI - Delete not used/unused image') ?></p>

    <div id="dnui_general">
        <ul id="dnui_tabs_button">
            <li><a class="dnui_db" href="#dnui_tabs_db"><?php _e('Scan DATABASE','dnui') ?></a></li>
            <li><a class="dnui_bp" href="#dnui_tabs_backup"><?php _e('Backup','dnui') ?></a></li>
            <li><a class="dnui_op" href="#dnui_tabs_option"><?php _e('Option','dnui') ?></a></li>
        </ul>
        <div class="tabDetails">
            <div id="dnui_tabs_db">
                <h1><?php _e('DNUI search unused/used image in database','dnui') ?></h1>
            </div>
            <div id="dnui_tabs_backup">
                <h1><?php _e('DNUI backup','dnui') ?></h1>
            </div>
            <div id="dnui_tabs_option">
                <h1><?php _e('DNUI option','dnui') ?></h1>
            </div>
        </div>


    </div>
    <?php
}

add_action('wp_ajax_dnui_all', 'DNUI_ajax_image');

function DNUI_ajax_image() {

    $dnuiOption;
    if (!empty($_POST["option"])) {
        $dnuiOption = $_POST["option"];
    } else {
        $dnuiOption = unserialize(get_option("dnui_options"));
    }

    $validator = array_filter(DNUI_validator($dnuiOption));

    if (empty($validator)) {

        $out = DNUI_getImages($dnuiOption["page"], $dnuiOption["cantInPage"], $dnuiOption["order"], $dnuiOption['galleryCheck'],$dnuiOption['without']);
        $out = json_encode($out);
        DNUI_add_option($dnuiOption);
    } else {

        $out = json_encode($validator);
    }
    echo $out;

    die();
}

function DNUI_add_option($options) {
    $validator = array_filter(DNUI_validator($options));

    if (empty($validator)) {
        if ($options['cron'] == 0) {
            wp_clear_scheduled_hook('dnui_delete_daily');
        } else if ($options['cron'] == 1) {
            wp_clear_scheduled_hook('dnui_delete_daily');
            wp_schedule_event(time() + 1, 'daily', 'dnui_delete_backup_daily');
        }
        $options = serialize($options);
        update_option("dnui_options", $options);
    }
}

add_action('dnui_delete_backup_daily', 'DNUI_ajax_cleanup_backup');

add_action('wp_ajax_dnui_get_option', 'DNUI_get_option');

function DNUI_get_option() {
    //var_dump(unserialize(get_option("dnui_options")));
    echo json_encode(unserialize(get_option("dnui_options")));
    die();
}

function DNUI_transform_bool(&$var) {
    if ($var == 'true') {
        $var = true;
    } else {
        $var = false;
    }
}

function DNUI_validator(&$options) {
    $validator = array();
    DNUI_transform_bool($options["updateInServer"]);
    DNUI_transform_bool($options["backup"]);
    DNUI_transform_bool($options["show"]);
    DNUI_transform_bool($options["admin"]);
    DNUI_transform_bool($options["showIgnore"]);
    DNUI_transform_bool($options["galleryCheck"]);
    DNUI_transform_bool($options["without"]);

    if (!(is_numeric($options["cron"]))) {
        array_push($validator, "order is not good");
    } else {
        if (($options["cron"] == 0 || $options["cron"] == 1)) {
            $options["cron"] = intval($options["cron"]);
        }
    }

    if (!(is_numeric($options["order"]))) {
        array_push($validator, "order is not good");
    } else {
        if (($options["order"] == 0 || $options["order"] == 1)) {
            $options["order"] = intval($options["order"]);
        }
    }
    if (!(is_numeric($options["page"]) && $options["page"] >= 0)) {
        array_push($validator, "page is not good");
    } else {
        $options["page"] = intval($options["page"]);
    }
    if (!(is_numeric($options["cantInPage"]) && $options["cantInPage"] >= 0)) {
        array_push($validator, "cantInPage is not good");
    } else {
        $options["cantInPage"] = intval($options["cantInPage"]);
    }

    return $validator;
}

add_action('wp_ajax_dnui_delete', 'DNUI_ajax_delete');

function DNUI_ajax_delete() {

    if (!empty($_POST["imageToDelete"])) {
        $result = array_filter(DNUI_delete($_POST["imageToDelete"], unserialize(get_option("dnui_options"))));
        if (empty($out)) {
            $out["isOk"] = true;
        } else {
            $out["isOk"] = false;
            $out["msg"] = $result;
        }
        echo json_encode($out);
    }
    die();
}

add_action('wp_ajax_dnui_get_dirs', 'DNUI_ajax_get_dirs');

function DNUI_ajax_get_dirs() {
    $base = wp_upload_dir();
    $base = $base['basedir'];
    echo json_encode(DNUI_get_all_dir_or_files($base, 0));
    die();
}

function DNUI_install() {


    DNUI_add_option(DNUI_default());
}

function DNUI_default() {
    $option = array('version' => '1.5.2', 'page' => 0,
        'cantInPage' => 25,
        'without'=>true,
        'updateInServer' => true,
        'order' => 0,
        'show' => false,
        'showIgnore' => false,
        'admin' => false,
        'galleryCheck' => false,
        'cron' => 0,
        'ignore' => array());
    return $option;
}

add_action('wp_ajax_dnui_get_backup', 'DNUI_ajax_get_backup');

function DNUI_ajax_get_backup() {
    echo json_encode(DNUI_get_backup());
    die();
}

add_action('wp_ajax_dnui_restore_backup', 'DNUI_ajax_restore_backup');

function DNUI_ajax_restore_backup() {

    echo json_encode(DNUI_restore_backup($_POST["restore"]));
    die();
}

add_action('wp_ajax_dnui_delete_backup', 'DNUI_ajax_delete_backup');

function DNUI_ajax_delete_backup() {
    echo json_encode(DNUI_delete_backup($_POST["delet"]));
    die();
}

add_action('wp_ajax_dnui_cleanup_backup', 'DNUI_ajax_cleanup_backup');

function DNUI_ajax_cleanup_backup() {
    echo json_encode(DNUI_cleanup_backup());
    die();
}

register_activation_hook(__FILE__, 'DNUI_install');
