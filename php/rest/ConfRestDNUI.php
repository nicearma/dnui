<?php


add_action('wp_ajax_dnui_get_all_backup', 'dnui_get_all_backup');

function dnui_get_all_backup()
{
    ob_clean();
    $backupRest = new BackupRestDNUI();
    $backupRest->readAll();
}

add_action('wp_ajax_dnui_delete_all_backup', 'dnui_delete_all_backup');

function  dnui_delete_all_backup()
{
    ob_clean();
    $backupRest = new BackupRestDNUI();
    $backupRest->deleteAll();
}

add_action('wp_ajax_dnui_delete_by_id_backup', 'dnui_delete_by_id_backup');

function dnui_delete_by_id_backup()
{
    ob_clean();
    $backupRest = new BackupRestDNUI();
    $backupRest->deleteById();
}


add_action('wp_ajax_dnui_make_backup', 'dnui_make_backup');

function dnui_make_backup()
{
    ob_clean();
    $backupRest = new BackupRestDNUI();
    $backupRest->make();
}


add_action('wp_ajax_dnui_restore_backup', 'dnui_restore_backup');

function dnui_restore_backup()
{
    ob_clean();
    $backupRest = new BackupRestDNUI();
    $backupRest->restoreBackup();
}


add_action('wp_ajax_dnui_make_backup_folder_backup', 'dnui_make_backup_folder_backup');

function dnui_make_backup_folder_backup()
{
    ob_clean();
    $backupRest = new BackupRestDNUI();
    $backupRest->makeBackupFolder();
}


add_action('wp_ajax_dnui_exists_backup_folder_backup', 'dnui_exists_backup_folder_backup');

function dnui_exists_backup_folder_backup()
{
    ob_clean();
    $backupRest = new BackupRestDNUI();
    $backupRest->existsBackupFolder();
}


add_action('wp_ajax_dnui_count_image', 'dnui_count_image');

function dnui_count_image()
{
    ob_clean();
    $imageRest = new ImageRestDNUI();
    $imageRest->countImage();
}

add_action('wp_ajax_dnui_get_all_by_options_image', 'dnui_get_all_by_options_image');

function dnui_get_all_by_options_image()
{
    ob_clean();
    $imageRest = new ImageRestDNUI();
    $imageRest->readByOptions();
}


add_action('wp_ajax_dnui_get_galleries_image', 'dnui_get_galleries_image');

function dnui_get_galleries_image()
{
    ob_clean();
    $imageRest = new ImageRestDNUI();
    $imageRest->readGalleries();
}

add_action('wp_ajax_dnui_get_shortcodes_image', 'dnui_get_shortcodes_image');

function dnui_get_shortcodes_image()
{
    ob_clean();
    $imageRest = new ImageRestDNUI();
    $imageRest->readShortCodes();
}


add_action('wp_ajax_dnui_verify_status_by_id_image', 'dnui_verify_status_by_id_image');

function dnui_verify_status_by_id_image()
{
    ob_clean();
    $imageRest = new ImageRestDNUI();
    $imageRest->verifyStatusById();
}


add_action('wp_ajax_dnui_get_sizes', 'dnui_get_sizes');

function dnui_get_sizes()
{
    ob_clean();
    $imageRest = new ImageRestDNUI();
    $imageRest->getSizes();
}


add_action('wp_ajax_dnui_delete_by_id_and_size_image', 'dnui_delete_by_id_and_size_image');

function dnui_delete_by_id_and_size_image()
{
    ob_clean();
    $imageRest = new ImageRestDNUI();
    $imageRest->deleteByIdAndSize();
}



add_action('wp_ajax_dnui_get_options', 'dnui_get_options');

function dnui_get_options()
{
    ob_clean();
    $optionsRest = new OptionsRestDNUI();
    $optionsRest->read();
}

add_action('wp_ajax_dnui_update_options', 'dnui_update_options');

function dnui_update_options()
{
    ob_clean();
    $optionsRest = new OptionsRestDNUI();
    $optionsRest->update();
}

add_action('wp_ajax_dnui_restore_options', 'dnui_restore_options');

function dnui_restore_options()
{
    ob_clean();
    $optionsRest = new OptionsRestDNUI();
    $optionsRest->restore();
}
add_action('wp_ajax_dnui_have_wc_options', 'dnui_have_wc_options');

function dnui_have_wc_options()
{
    ob_clean();
    $optionsRest = new OptionsRestDNUI();
    $optionsRest->haveWooCommerce();
}
