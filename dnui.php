<?php
/*
  Plugin Name: DNUI (Delete not used images)
  Version: 2.3
  Plugin URI: http://www.nicearma.com/delete-not-used-image-wordpress-dnui/
  Author: Nicearma
  Author URI: http://www.nicearma.com/
  Text Domain: dnui
  Description: This plugin will delete all not used images file, the plugin search all image, not referred by any post of wordpress
 */

/*
  Copyright (c) 2016 http://www.nicearma.com
  Released under the GPL license
  http://www.gnu.org/licenses/gpl.txt
 */

add_action('admin_init', 'DNUI_admin_js');

add_action('admin_menu', 'DNUI_admin_menu');

function DNUI_admin_js()
{
    wp_register_style('dnui-css', plugins_url('css/dnui.css', __FILE__));

    //angular dependency
    wp_register_script('dnui-angular', plugins_url('js/external/angular.min.js', __FILE__), array('jquery', 'underscore'));
    wp_register_script('dnui-angular-resource', plugins_url('js/external/angular-resource.min.js', __FILE__), array('dnui-angular'));
    wp_register_script('dnui-angular-animate', plugins_url('js/external/angular-animate.min.js', __FILE__), array('dnui-angular'));

    //extra dependency
    wp_register_script('dnui-bootstrap-tabs', plugins_url('js/external/bootstrap-tabs.min.js', __FILE__), array('dnui-angular'));
    wp_register_script('dnui-bootstrap-modal', plugins_url('js/external/bootstrap-modal.min.js', __FILE__), array('dnui-angular'));

    wp_register_script('dnui-angular-ui', plugins_url('js/external/angular-ui.js', __FILE__), array('dnui-angular', 'dnui-bootstrap-tabs', 'dnui-bootstrap-modal'));

    //resources
    wp_register_script('dnui-options-resource', plugins_url('js/resource/options-resource.js', __FILE__), array('dnui-angular-resource'));
    wp_register_script('dnui-images-resource', plugins_url('js/resource/images-resource.js', __FILE__), array('dnui-angular-resource'));
    wp_register_script('dnui-backup-resource', plugins_url('js/resource/backup-resource.js', __FILE__), array('dnui-angular-resource'));

    //controller
    wp_register_script('dnui-dnui-ctrl', plugins_url('js/ctrl/dnui-ctrl.js', __FILE__), array('dnui-angular'));
    wp_register_script('dnui-options-ctrl', plugins_url('js/ctrl/options-ctrl.js', __FILE__), array('dnui-options-resource'));
    wp_register_script('dnui-images-ctrl', plugins_url('js/ctrl/images-ctrl.js', __FILE__), array('dnui-images-resource'));
    wp_register_script('dnui-backup-ctrl', plugins_url('js/ctrl/backup-ctrl.js', __FILE__), array('dnui-backup-resource'));

    //dnui principal JS
    wp_register_script('dnui-js', plugins_url('js/dnui.js', __FILE__), array('dnui-angular', 'dnui-angular-animate', 'dnui-bootstrap-tabs', 'dnui-angular-ui'));
}


function DNUI_admin_menu()
{

    /* Add our plugin submenu and administration screen */
    $page_hook_suffix = add_submenu_page('tools.php', // The parent page of this submenu
        __('DNUI Delete not used image', 'dnui'), // The submenu title
        __('DNUI Delete not used image', 'dnui'), // The screen title
        'activate_plugins', // The capability required for access to this submenu
        'dnui', // The slug to use in the URL of the screen
        'DNUI_display_menu' // The function to call to display the screen
    );

    /*
      * Use the retrieved $page_hook_suffix to hook the function that links our script.
      * This hook invokes the function only on our plugin administration screen,
      * see: http://codex.wordpress.org/Administration_Menus#Page_Hook_Suffix
      */
    add_action('admin_print_scripts-' . $page_hook_suffix, 'DNUI_admin_scripts');

}

function DNUI_admin_scripts()
{

    wp_enqueue_style('dnui-css');

    /* Link our already registered script to a page */
    wp_enqueue_script('dnui-js');

    //include resources
    wp_enqueue_script('dnui-options-resource');
    wp_enqueue_script('dnui-images-resource');
    wp_enqueue_script('dnui-backup-resource');

    //include controllers
    wp_enqueue_script('dnui-dnui-ctrl');
    wp_enqueue_script('dnui-options-ctrl');
    wp_enqueue_script('dnui-images-ctrl');
    wp_enqueue_script('dnui-backup-ctrl');
}

/* Display our administration screen */
function DNUI_display_menu()
{
    ?>

    <div ng-app="dnuiPlugin">

        <div ng-controller="DnuiCtrl">

            <uib-tabset>

                <uib-tab  heading="<?php _e('Warning', 'dnui') ?>">
                    <h1>
                        <?php _e('WARNING ABOUT THIS PLUGIN', 'dnui') ?>
                    </h1>
                    <?php include_once 'html/warning.php'; ?>
                </uib-tab>

                <uib-tab select='tabImages()' heading="<?php _e('Images', 'dnui') ?>">
                    <h1>
                        <?php _e('DNUI search unused/used image in database', 'dnui') ?>
                    </h1>
                    <?php include_once 'html/images.php'; ?>
                </uib-tab>

                <uib-tab select='tabBackups()' heading="<?php _e('Backups', 'dnui') ?>">
                    <h1>
                        <?php _e('DNUI backup', 'dnui') ?>
                    </h1>
                    <?php include_once 'html/backup.php'; ?>

                </uib-tab>

                <uib-tab select='tabOptions()' heading="<?php _e('Options', 'dnui') ?>">
                    <h1>
                        <?php _e('DNUI options', 'dnui') ?>
                    </h1>
                    <?php include_once 'html/options.php'; ?>
                </uib-tab>
            </uib-tabset>
        </div>
    </div>

<?php

}

//
//function DNUI_activate() {
//    BackupRest::makeBackupFolder();
//}
//
//
//
//register_activation_hook( __FILE__, 'DNUI_activate' );
if (is_admin()) {

    include_once 'php/php5_3/JsonSerializable.php';
    include_once 'php/helpers/HelperDNUI.php';

    include_once 'php/model/OptionsDNUI.php';
    include_once 'php/model/DatabaseDNUI.php';
    include_once 'php/model/ImageDNUI.php';

    include_once 'php/converters/ConvertOptions.php';
    include_once 'php/converters/ConvertWordpressToDNUI.php';

    include_once 'php/rest/OptionsRest.php';
    include_once 'php/rest/ImageRest.php';
    include_once 'php/rest/BackupRest.php';

    include_once 'php/checkers/CheckerImageAbstract.php';
    include_once 'php/checkers/CheckerImagePostAndPageBestLuck.php';
    include_once 'php/checkers/CheckerImagePostAndPageAll.php';
    include_once 'php/checkers/CheckerImagePostAndPageDraft.php';
    include_once 'php/checkers/CheckersDNUI.php';

}