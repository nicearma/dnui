<?php
/**
 * Description of HelperDNUI
 *
 * @author nicearma
 */
class HelperDNUI {


    /**
     * Only the folders
     * @param type $dirBase
     * @return array
     */
    public static function getDirs($dirBase) {

        $out = array();
        foreach ($dirBase as $dir) {
            array_push($out, $dir);
            $result = glob($dir . '/*', GLOB_ONLYDIR);
            if (!empty($result)) {
                $result2 = HelperDNUI::getDirs($result);
                foreach ($result2 as $value) {
                    array_push($out, $value);
                }
            }
        }
        return $out;
    }

    public static function scanDir($dirBase) {
        if(file_exists($dirBase)){
          return array_diff(scandir($dirBase), array('..', '.'));
        }
        return array();
    }

    public static function fileExist($src){
        $uploadDir = wp_upload_dir();
        if( file_exists($uploadDir['basedir'].'/'.$src)){
            return 1;
        }
        return 0;
    }

	public static function backupFolderExist(){
		return file_exists(HelperDNUI::backupDir());
	}

 public static function backupDir()
    {
        $uploadDir = wp_upload_dir();
        $basedir = $uploadDir['basedir'];
        $backupDir = $basedir . '/' . 'dnui_backups';
        return $backupDir;
    }

}
