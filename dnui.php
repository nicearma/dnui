<?php
/*
  Plugin Name: DNUI
  Version: 2.5.7
  Plugin URI: http://www.nicearma.com/dnui-delete-not-used-image-wordpress/
  Author: Nicearma
  Author URI: http://www.nicearma.com/
  Text Domain: dnui-delete-not-used-image-wordpress
  Domain Path: /languages
  Description: Search image from the database and delete all unused images making space in your server and clean up the database from all unused images
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
    wp_register_style('dnui-css-bootstrap', plugins_url('css/bootstrap.min.css', __FILE__));

    wp_register_style('dnui-css', plugins_url('css/dnui.css', __FILE__));

    //angular dependency
    wp_register_script('dnui-angular', plugins_url('js/external/angular.min.js', __FILE__), array('jquery', 'underscore'));
    wp_register_script('dnui-angular-resource', plugins_url('js/external/angular-resource.min.js', __FILE__), array('dnui-angular'));
    wp_register_script('dnui-angular-animate', plugins_url('js/external/angular-animate.min.js', __FILE__), array('dnui-angular'));

    wp_register_script('dnui-bootstrap', plugins_url('js/external/bootstrap.min.js', __FILE__), array('dnui-angular'));

    wp_register_script('dnui-angular-ui', plugins_url('js/external/ui-bootstrap-tpls-1.2.2.min.js', __FILE__), array('dnui-angular', 'dnui-bootstrap'));

    //resources
    wp_register_script('dnui-options-resource', plugins_url('js/resource/options-resource.js', __FILE__), array('dnui-angular-resource'));
    wp_register_script('dnui-images-resource', plugins_url('js/resource/images-resource.js', __FILE__), array('dnui-angular-resource'));
    wp_register_script('dnui-backup-resource', plugins_url('js/resource/backup-resource.js', __FILE__), array('dnui-angular-resource'));

    //controller
    wp_register_script('dnui-dnui-ctrl', plugins_url('js/ctrl/dnui-ctrl.js', __FILE__), array('dnui-angular'));
    wp_register_script('dnui-options-ctrl', plugins_url('js/ctrl/options-ctrl.js', __FILE__), array('dnui-options-resource'));
    wp_register_script('dnui-images-ctrl', plugins_url('js/ctrl/images-ctrl.js', __FILE__), array('dnui-images-resource'));
    wp_register_script('dnui-backup-ctrl', plugins_url('js/ctrl/backup-ctrl.js', __FILE__), array('dnui-backup-resource'));
	wp_register_script('dnui-log-ctrl', plugins_url('js/ctrl/log-ctrl.js', __FILE__), array('dnui-dnui-ctrl'));


    //dnui principal JS
    wp_register_script('dnui-js', plugins_url('js/dnui.js', __FILE__), array('dnui-angular', 'dnui-angular-animate', 'dnui-bootstrap', 'dnui-angular-ui'));
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
	wp_enqueue_style('dnui-css-bootstrap');
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
 	wp_enqueue_script('dnui-log-ctrl');
}

/* Display our administration screen */
function DNUI_display_menu()
{
    ?>

    <div ng-app="dnuiPlugin">

        <div ng-controller="DnuiCtrl">

            <uib-tabset>

                <uib-tab  heading="<?php _e('Warning','dnui-delete-not-used-image-wordpress'); ?>">
                    <h1>
                        <?php _e('WARNING ABOUT THIS PLUGIN','dnui-delete-not-used-image-wordpress'); ?>
                    </h1>
                    <?php include_once 'html/warning.php'; ?>
                </uib-tab>

                <uib-tab select='tabImages()' heading="<?php _e('Images','dnui-delete-not-used-image-wordpress'); ?>">
                    <h1>
                        <?php _e('DNUI search unused/used image in database','dnui-delete-not-used-image-wordpress'); ?>
                    </h1>
                    <?php include_once 'html/images.php'; ?>
                </uib-tab>

                <uib-tab select='tabBackups()' heading="<?php _e('Backups','dnui-delete-not-used-image-wordpress'); ?>">
                    <h1>
                        <?php _e('DNUI backup','dnui-delete-not-used-image-wordpress'); ?>
                    </h1>
                    <?php include_once 'html/backup.php'; ?>

                </uib-tab>

                <uib-tab select='tabOptions()' heading="<?php _e('Options','dnui-delete-not-used-image-wordpress'); ?>">
                    <h1>
                        <?php _e('DNUI options','dnui-delete-not-used-image-wordpress'); ?>
                    </h1>
                    <?php include_once 'html/options.php'; ?>
                </uib-tab>

                <uib-tab select='tabLogs()' heading="<?php _e('Logs','dnui-delete-not-used-image-wordpress'); ?>">
                    <h1>
                        <?php _e('DNUI Logs','dnui-delete-not-used-image-wordpress'); ?>
                    </h1>
                    <?php include_once 'html/log.php'; ?>
                </uib-tab>

            </uib-tabset>
        </div>
    </div>

<?php

}

add_action('plugins_loaded', 'dnui_load_textdomain');
function dnui_load_textdomain() {
    load_plugin_textdomain( 'dnui', false, dirname( plugin_basename(__FILE__) ) . '/languages/' );
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
/*
	if (!class_exists('ErrorHandlerDNUI')) {
        include_once 'php/model/ErrorHandlerDNUI.php';
    }
*/
 	if (!class_exists('RestResponseDNUI')) {
        include_once 'php/model/RestResponseDNUI.php';
    }

    if (!class_exists('HelperDNUI')) {
        include_once 'php/helpers/HelperDNUI.php';
    }

	if (!class_exists('OptionsDNUI')) {
        include_once 'php/model/OptionsDNUI.php';
    }
	if (!class_exists('DatabaseDNUI')) {
        include_once 'php/model/DatabaseDNUI.php';
    }
	if (!class_exists('ImageDNUI')) {
        include_once 'php/model/ImageDNUI.php';
    }
	if (!class_exists('StatusBackupDNUI')) {
        include_once 'php/model/StatusBackupDNUI.php';
    }
    if (!class_exists('StatusDNUI')) {
        include_once 'php/model/StatusDNUI.php';
    }
	if (!class_exists('ImageSizeDNUI')) {
        include_once 'php/model/ImageSizeDNUI.php';
    }


    if (!class_exists('ConvertOptionsDNUI')) {
        include_once 'php/converters/ConvertOptionsDNUI.php';
    }
    if (!class_exists('ConvertWordpressToDNUI')) {
        include_once 'php/converters/ConvertWordpressToDNUI.php';
    }


    if (!class_exists('OptionsRestDNUI')) {
        include_once 'php/rest/OptionsRestDNUI.php';
    }
    if (!class_exists('ImageRestDNUI')) {
        include_once 'php/rest/ImageRestDNUI.php';
    }
    if (!class_exists('BackupRestDNUI')) {
        include_once 'php/rest/BackupRestDNUI.php';
    }


    if (!class_exists('CheckerImageAbstractDNUI')) {
        include_once 'php/checkers/CheckerImageAbstractDNUI.php';
    }
    if (!class_exists('CheckerImageExcerptAllDNUI')) {
        include_once 'php/checkers/CheckerImageExcerptAllDNUI.php';
    }
    if (!class_exists('CheckerImageExcerptBestLuckDNUI')) {
        include_once 'php/checkers/CheckerImageExcerptBestLuckDNUI.php';
    }
    if (!class_exists('CheckerImagePostAndPageAllDNUI')) {
        include_once 'php/checkers/CheckerImagePostAndPageAllDNUI.php';
    }
    if (!class_exists('CheckerImagePostAndPageBestLuckDNUI')) {
        include_once 'php/checkers/CheckerImagePostAndPageBestLuckDNUI.php';
    }
    if (!class_exists('CheckerImagePostMetaDNUI')) {
        include_once 'php/checkers/CheckerImagePostMetaDNUI.php';
    }
    if (!class_exists('CheckerImagePostMetaDNUI')) {
        include_once 'php/checkers/CheckerImagePostMetaDNUI.php';
    }
    if (!class_exists('CheckersDNUI')) {
        include_once 'php/checkers/CheckersDNUI.php';
    }

    
    include_once 'php/rest/ConfRestDNUI.php';
    


}
