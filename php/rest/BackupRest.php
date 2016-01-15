<?php

$backupRest = new BackupRest();

add_action('wp_ajax_dnui_get_all_backup', array($backupRest, 'readAll'));
add_action('wp_ajax_dnui_delete_all_backup', array($backupRest, 'deleteAll'));
add_action('wp_ajax_dnui_delete_by_id_backup', array($backupRest, 'deleteById'));
add_action('wp_ajax_dnui_make_backup', array($backupRest, 'make'));
add_action('wp_ajax_dnui_restore_backup', array($backupRest, 'restoreBackup'));
add_action('wp_ajax_dnui_make_backup_folder_backup', array($backupRest, 'makeBackupFolder'));
add_action('wp_ajax_dnui_exists_backup_folder_backup', array($backupRest, 'existsBackupFolder'));



/**
 * Description of Backup
 *
 * @author Nicolas
 */
class BackupRest
{

    private $databaseDNUI;
    private $optionsDNUI;


    function __construct()
    {

        $this->databaseDNUI = new DatabaseDNUI();
        $this->optionsDNUI = OptionsRest::readOptions();

    }

    public function readAll()
    {
        echo json_encode(HelperDNUI::scanDir(BackupRest::backupDir()));
         wp_die();
    }

    public function deleteAll()
    {
        //TODO: complete this option
        @rmdir(BackupRest::backupDir());
        clearstatcache();
        BackupRest::makeBackupFolder();
    }

    public function deleteById()
    {
        $backupId = $_GET['id'];
        $statusBackup = new StatusBackupDNUI();
        $backupDir=BackupRest::backupDir();
        $backupIdPath=$backupDir . '/' . $backupId.'/' ;
        $backFiles = HelperDNUI::scanDir($backupIdPath, 1);
        foreach($backFiles as $file){
            @unlink($backupIdPath.$file);
        }

        @rmdir ($backupIdPath);
        clearstatcache();
        if(file_exists($backupIdPath)){
            $statusBackup->setInServer(-4); //can not be deteleted
        }else{
            $statusBackup->setInServer(3);
        }
        echo json_encode($statusBackup);
         wp_die();
    }

    public function make()
    {
        $status = new StatusDNUI();
        //TODO: add message -> error is not writible etc, use status object
        $imageId = $_GET['id'];
        $sizeName = $_GET['sizeName'];
        $imageDNUI = ConvertWordpressToDNUI::convertIdToImageDNUI($imageId);

        $backupDir = BackupRest::backupDir();
        $uploadDir = wp_upload_dir();
        $basedir = $uploadDir['basedir'];

        if (is_writable($backupDir)) {
            $tmpBackupDirImage = $backupDir . '/' . $imageId . '/';
            if (!file_exists($tmpBackupDirImage)) {
                mkdir($tmpBackupDirImage, 0755, true);
            }

            $backupInfo = array();
            $uploadDirs = explode("/", $imageDNUI->getSrcOriginalImage());
            array_pop($uploadDirs);
            $uploadDir = implode("/", $uploadDirs);
            $backupInfo["uploadDir"] = $uploadDir;
            $backupInfo["posts"] = $this->databaseDNUI->getRowPost($imageId);
            $backupInfo["postMeta"] = $this->databaseDNUI->getRowPostMeta($imageId);

            $tmpBackupFileImage = $tmpBackupDirImage . $imageId . '.backup';
            if (!file_exists($tmpBackupFileImage)) {
                file_put_contents($tmpBackupFileImage, serialize($backupInfo));
            }

            if ($sizeName == 'original') {
                $originalPath=$basedir . '/' . $imageDNUI->getSrcOriginalImage();
                $imageTempBackupPath = $tmpBackupDirImage . $imageDNUI->getName();
                if (file_exists($originalPath)) {
                    copy($originalPath, $imageTempBackupPath);
                }

                foreach ($imageDNUI->getImageSizes() as $imageSize) {
                    $originalPath=$basedir . '/' .$imageSize->getSrcSizeImage();
                    $imageTempBackupPath = $tmpBackupDirImage . $imageSize->getName();
                    if (file_exists($originalPath)) {
                        copy($originalPath, $imageTempBackupPath);
                    }

                }
            } else {
                $originalPath=$basedir . '/' . $imageDNUI->getImageSizes()[$sizeName]->getSrcSizeImage();
                $imageTempBackupPath = $tmpBackupDirImage . $imageDNUI->getImageSizes()[$sizeName]->getName();
                if (file_exists($originalPath)) {
                    copy($originalPath, $imageTempBackupPath);
                }
            }

            $status->setUsed(4); //4-> backup made
            $status->setInServer(4); //in backup folder
        } else {
            $status->setInServer(-4); //error folder backup, is not writible
            $status->setUsed(-4); //error folder backup, is not writible
        }

        echo json_encode($status);
         wp_die();
    }

    public function restoreBackup()
    {
        $statusBackup = new StatusBackupDNUI();
        $backupId = $_GET['id'];
        $backupDir = BackupRest::backupDir();
        $backFiles = HelperDNUI::scanDir($backupDir . '/' . $backupId . '/', 1);
        $fileImages = preg_grep("/^(?!.*\\.backup)/", $backFiles);
        $fileBackup = preg_grep("/^(.*\\.backup)/", $backFiles);
        $fileBackupFile = $backupDir . '/' . $backupId . '/' . array_pop($fileBackup);

        $uploadDir = wp_upload_dir();
        $basedir = $uploadDir['basedir'];

        $backupInfo = unserialize(file_get_contents($fileBackupFile));
        foreach ($backupInfo["posts"] as $posts) {
            $this->databaseDNUI->getDb()->replace($this->databaseDNUI->getPrefix() . "posts", $posts);
        }
        foreach ($backupInfo["postMeta"] as $postMeta) {
            $this->databaseDNUI->getDb()->replace($this->databaseDNUI->getPrefix() . "postmeta", $postMeta);
        }

        foreach ($fileImages as $image) {
            if(!file_exists($basedir.'/'. $backupInfo["uploadDir"].'/' . $image)){
                if(file_exists($backupDir.'/'.$backupId . '/' . $image)){
                    rename($backupDir.'/'.$backupId . '/' . $image, $basedir.'/'. $backupInfo["uploadDir"].'/' . $image);
                }
            }

        }

        $statusBackup->setInServer(2); //2 -> in the upload folder
        echo json_encode($statusBackup);
         wp_die();
    }


    public static function  makeBackupFolder()
    {
        $statusBackup = new StatusBackupDNUI();
        $backupDir = BackupRest::backupDir();
        if (!file_exists($backupDir)) {
            mkdir($backupDir, 0755, true);
            if (!file_exists($backupDir)) {
                $statusBackup->setInServer(-3); //-3 -> can not be created
            }else{
                $statusBackup->setInServer(1); // 1 -> exists
            }
        }
        echo json_encode($statusBackup);
         wp_die();
    }

    public static function  existsBackupFolder()
    {
        $statusBackup = new StatusBackupDNUI();
        $backupDir = BackupRest::backupDir();

        if (file_exists($backupDir)) {
            $statusBackup->setInServer(1); // 1 -> exists
        }else{
            $statusBackup->setInServer(0); // 0 -> not exists
        }
        echo json_encode($statusBackup);
         wp_die();
    }

    public static function backupDir()
    {
        $uploadDir = wp_upload_dir();
        $basedir = $uploadDir['basedir'];
        $backupDir = $basedir . '/' . 'dnui_backups';
        return $backupDir;
    }

}



class StatusBackupDNUI implements JsonSerializable{


    /*
     * -4 backup id folder can not be delete
     * -3 => can not be delete backup folder
     * -2 => status unknow
     *  0 => backup folder not exists
     *  1 => backup folder exist
     *  2 => moved to upload folder (restore option)
     *  3 => backup id folder have been deleted
     *  4 => Restoring...
     *  5 => Deleting...
     *
     */
    private $inServer=-2;


    /**
     * @return int
     */
    public function getInServer()
    {
        return $this->inServer;
    }

    /**
     * @param int $inServer
     */
    public function setInServer($inServer)
    {
        $this->inServer = $inServer;
    }





    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}


