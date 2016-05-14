<?php


/**
 * Description of Backup
 *
 * @author nicearma
 */
class BackupRestDNUI
{

    private $databaseDNUI;
    private $optionsDNUI;


    function __construct()
    {

        $this->databaseDNUI = new DatabaseDNUI();
        $this->optionsDNUI = OptionsRestDNUI::readOptions();
        //set_error_handler(array('ErrorHandlerDNUI', 'errorHandler'));
    }

    public function readAll()
    {

        die(json_encode(HelperDNUI::scanDir(HelperDNUI::backupDir()), JSON_FORCE_OBJECT));
    }

    public function deleteAll()
    {
        //TODO: complete this option
        @rmdir(HelperDNUI::backupDir());
        clearstatcache();
        BackupRestDNUI::makeBackupFolder();
    }

    public function deleteById()
    {
        $json = json_decode(file_get_contents('php://input'), true);

        $backupId = $json['id'];
        if (!is_numeric($backupId)) {
            //nothing to do, this case is not possible but for security reason
            return;
        }

        $statusBackup = new StatusBackupDNUI();
        $backupDir = HelperDNUI::backupDir();
        $backupIdPath = $backupDir . '/' . $backupId . '/';
        $backFiles = HelperDNUI::scanDir($backupIdPath, 1);
        foreach ($backFiles as $file) {
            @unlink($backupIdPath . $file);
        }

        @rmdir($backupIdPath);
        clearstatcache();
        if (file_exists($backupIdPath)) {
            $statusBackup->setInServer(-4); //can not be deteleted
        } else {
            $statusBackup->setInServer(3);
        }

        die(json_encode($statusBackup));
    }

    public function make()
    {

        $json = json_decode(file_get_contents('php://input'), true);

        $imageId = $json['id'];

        if (!is_numeric($imageId)) {
            //nothing to do, this case is not possible but for security reason
            return;
        }

        $sizeNames = $json['sizeNames'];

        if (!is_array($sizeNames)) {
            //nothing to do, this case is not possible but for security reason
            return;
        }

        $statusBySizes = array();

        $imageDNUI = ConvertWordpressToDNUI::convertIdToImageDNUI($imageId);

        $backupDir = HelperDNUI::backupDir();
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

            foreach ($sizeNames as $sizeName) {

                if ($sizeName == 'original') {
                    $originalPath = $basedir . '/' . $imageDNUI->getSrcOriginalImage();
                    $imageTempBackupPath = $tmpBackupDirImage . $imageDNUI->getName();
                    if (file_exists($originalPath)) {
                        copy($originalPath, $imageTempBackupPath);
                    }

                    foreach ($imageDNUI->getImageSizes() as $imageSize) {
                        $originalPath = $basedir . '/' . $imageSize->getSrcSizeImage();
                        $imageTempBackupPath = $tmpBackupDirImage . $imageSize->getName();
                        if (file_exists($originalPath)) {
                            copy($originalPath, $imageTempBackupPath);
                        }

                    }
                } else {
                    $imageSizes = $imageDNUI->getImageSizes();
                    $originalPath = $basedir . '/' . $imageSizes[$sizeName]->getSrcSizeImage();
                    $imageTempBackupPath = $tmpBackupDirImage . $imageSizes[$sizeName]->getName();
                    if (file_exists($originalPath)) {
                        copy($originalPath, $imageTempBackupPath);
                    }
                }
                $statusBySizes[$sizeName] = new StatusDNUI();
                $statusBySizes[$sizeName]->setUsed(5); //5-> backup made
                $statusBySizes[$sizeName]->setInServer(5); //in backup folder
            }

        } else {
            foreach ($sizeNames as $sizeName) {
                $statusBySizes[$sizeName] = new StatusDNUI();
                $statusBySizes[$sizeName]->setUsed(-5); //-5-> backup error
                $statusBySizes[$sizeName]->setInServer(-5); //in backup folder error

            }

        }

        die(json_encode($statusBySizes));
    }

    public function restoreBackup()
    {

        $json = json_decode(file_get_contents('php://input'), true);
        $backupId = $json['id'];

        if (!is_numeric($backupId)) {
            //nothing to do, this case is not possible but for security reason
            return;
        }

        $statusBackup = new StatusBackupDNUI();

        $backupDir = HelperDNUI::backupDir();
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
            if (!file_exists($basedir . '/' . $backupInfo["uploadDir"] . '/' . $image)) {
                if (file_exists($backupDir . '/' . $backupId . '/' . $image)) {
                    rename($backupDir . '/' . $backupId . '/' . $image, $basedir . '/' . $backupInfo["uploadDir"] . '/' . $image);
                }
            }

        }

        $statusBackup->setInServer(2); //2 -> in the upload folder

        die(json_encode($statusBackup));
    }


    public static function  makeBackupFolder()
    {
        $statusBackup = new StatusBackupDNUI();
        $backupDir = HelperDNUI::backupDir();
        if (!file_exists($backupDir)) {
            mkdir($backupDir, 0755, true);
            if (!file_exists($backupDir)) {
                $statusBackup->setInServer(-3); //-3 -> can not be created
            } else {
                $statusBackup->setInServer(1); // 1 -> exists
            }
        }

        die(json_encode($statusBackup));
    }

    public static function  existsBackupFolder()
    {

        $statusBackup = new StatusBackupDNUI();

        if (HelperDNUI::backupFolderExist()) {
            $statusBackup->setInServer(1); // 1 -> exists
        } else {
            $statusBackup->setInServer(0); // 0 -> not exists
        }

        die(json_encode($statusBackup));
    }


}

