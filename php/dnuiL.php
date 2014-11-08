<?php

/**
 * Check if the image is use, the first check is if the post parent of 
 * the image is using this image (this seems to be the more comment way) 
 * if not used by this, the plugin will check in the other plugin, this can be improved
 * @global type $wpdb
 * @param type $ImageName
 * @param type $postId
 * @return type
 */

function DNUI_checkImageDB($ImageName, $postId) {
    global $wpdb;
    $sql = "SELECT id FROM " . $wpdb->prefix . "posts WHERE  post_parent in (SELECT post_parent FROM " . $wpdb->prefix . "posts WHERE id=" . $postId . " ) and post_content LIKE '%/$ImageName%'";
    $wpdb->get_results($sql, "ARRAY_A");
    $result = $wpdb->get_results($sql, "ARRAY_A");
    if (!empty($result)) {
        return $result;
    } else {
        $sql = "SELECT id FROM " . $wpdb->prefix . "posts  WHERE post_content LIKE '%/$ImageName%' limit 0,1";
    }
    $result = $wpdb->get_results($sql, "ARRAY_A");
    return $result;
}
function DNUI_checkImageServer($ImageSrc){
   return file_exists($ImageSrc);
    
}
/**
 * Find where the image is used, only if some one whant to no this information
 * @global type $wpdb
 * @param type $ImageName
 * @return type
 */
function DNUI_getWhereIsUsed($ImageName) {
    global $wpdb;
    $sql = "SELECT id FROM " . $wpdb->prefix . "posts  WHERE post_content LIKE '%/$ImageName%'";
    $result = $wpdb->get_results($sql, "ARRAY_A");
    return $result;
}

/**
 * Get all images in the database who have the condition Type of post 'attachment'  and Type of MIME = 'image'. This will give all the sizes to
 * @global type $wpdb
 * @param type $i
 * @param type $max
 * @param type $order
 * @return type
 */

function DNUI_getImages($i, $max, $order,$checkGallery) {
    global $wpdb;

    $sql = 'SELECT id FROM ' . $wpdb->prefix . 'posts, ' . $wpdb->prefix . "postmeta where post_type='attachment' and post_mime_type like  'image%' and " . $wpdb->prefix . "posts.id=" . $wpdb->prefix . "postmeta.post_id and " . $wpdb->prefix . 'postmeta.meta_key=\'_wp_attachment_metadata\' ';
    $last = ' ORDER BY  `' . $wpdb->prefix . 'postmeta`.`meta_id`';
    if ($order == 0) {
        $last.=' ASC ';
    } else {
        $last.=' DESC ';
    }
    $sql.=$last . ' LIMIT ' . ($i * $max) . ", $max";

    $result = $wpdb->get_results($sql, "ARRAY_A");
    $images = array();
    
    if (!empty($result)) {

        $base = wp_upload_dir();

        $base = $base['baseurl'];

        foreach ($result as $key => $value) {
            //  var_dump($value);
            $images[$key]["id"] = $value["id"];
            $images[$key]['meta_value'] = wp_get_attachment_metadata($value["id"]);
            //the result of meta_value is serialized 
            $imp = explode("/", $images[$key]['meta_value']["file"]);
            $images[$key]['meta_value']["file"] = array_pop($imp);
            $folder = implode("/", $imp);
            $images[$key]['base'] = "$base/$folder";
        }
    }
    
    if($checkGallery){
      $infoGalleries=   DNUI_getInfoImageFormPostsWithGallery();
    }else{
      $infoGalleries=array();
    }
  

    return DNUI_checkList($images,$infoGalleries);
}

/**
 * Get the row, this is use for the backup 
 * @global type $wpdb
 * @param type $id
 * @return type
 */

function DNUI_getRowPost($id) {
    global $wpdb;
    $sql = 'SELECT * FROM ' . $wpdb->prefix . 'posts where id=' . $id . ';';
    return $wpdb->get_results($sql, "ARRAY_A");
}

/**
 * This is use for the backup file
 * @global type $wpdb
 * @param type $id
 * @return type
 */
function DNUI_getRowPostMeta($id) {
    global $wpdb;
    $sql = 'SELECT * FROM ' . $wpdb->prefix . 'postmeta where post_id=' . $id . ';';
    return $wpdb->get_results($sql, "ARRAY_A");
}

/**
 * Get all port with some gallery, this will help to show what image is used in some post, for the moment is only one notification
 * @global type $wpdb
 * @return type
 */

function DNUI_getInfoImageFormPostsWithGallery() {
    global $wpdb;
    $sql = "SELECT id FROM " . $wpdb->prefix . "posts  WHERE post_content LIKE '%[gallery%' and post_type='post'; ";
    $result=$wpdb->get_results($sql, "ARRAY_A");
    $info=array();
    foreach ($result as $id) {
       $galleries=  get_post_galleries($id['id'],false);
       foreach ($galleries as $gallery) {
           $idsImage=  explode(',',$gallery['ids']);
           foreach($idsImage as $idImage){
                if(!array_key_exists($idImage, $info)){
                    $info[$idImage]=array('sizes'=>array());
                }
                $size="original";
                if(array_key_exists('size', $gallery)){
                   $size= $gallery['size'];
                }
                if(!in_array($size, $info[$idImage]['sizes'])){
                    $info[$idImage]['sizes'][]=$size;
                }
           }
          
       }
       
     
    }

    return $info;
}
/**
 * Update the information about the image and their sizes
 * @global type $wpdb
 * @param type $value
 * @param type $id
 * @return type
 */

function DNUI_updateImages($value, $id) {
    global $wpdb;
    $value = str_replace("'", "''", $value);
    $sql = 'update ' . $wpdb->prefix . "postmeta set meta_value='" . ($value) . "' where meta_id='$id'";
    return $wpdb->query($sql);
}

/**
 * Check if the list of image is used or not, some information will be added to the array
 * @param type $images
 * @return boolean
 */
function DNUI_checkList($images,$InfoGalleries) {
    
    foreach ($images as $key => $image) {
        
        $idPost = DNUI_checkImageDB($image['meta_value']["file"], $image['id']);
        if (!empty($idPost)) {
            $images[$key]['meta_value']["use"] = true;
            $images[$key]['meta_value']["url"] =get_permalink($idPost[0]['id']);
        }
        else {
            $images[$key]['meta_value']["use"] = false;
        }
        //var_dump($image);
        $images[$key]['meta_value']["exits"]=DNUI_checkImageServer($image['base'].'/'.$image['meta_value']["file"]);
        
        foreach ($image['meta_value']['sizes'] as $keyS => $imageS) {
            clearstatcache();
            $idPost = DNUI_checkImageDB($imageS["file"], $image['id']);
            if (!empty($idPost)) {
                $imageS["use"] = true;
                $imageS["url"] = get_permalink($idPost[0]['id']);
            } else {
                $imageS["use"] = false;
            }

            if ($imageS["use"]) {
                $images[$key]['meta_value']["use"] = true;
            }
            $imageS["exits"]=DNUI_checkImageServer($image['meta_value']["file"].$imageS["file"]);
        }
        
       
        if(array_key_exists($images[$key]['id'], $InfoGalleries)) {
            
            if(in_array('original', $InfoGalleries[$images[$key]['id']])){
                 $images[$key]['meta_value']["use"] = true;
                $InfoGalleries= array_diff(array('original'),$InfoGalleries[$images[$key]['id']]);
            }
            foreach($InfoGalleries[$images[$key]['id']]['sizes'] as $size) {
                $images[$key]['meta_value']["use"] = true;
                $images[$key]['meta_value']['sizes'][$size]["use"] = true;
            }
           
        }
        
        
    }
    return $images;
}

/**
 * Delete all image given the array ids 
 * @param type $imagesToDelete
 * @param type $options
 * @return array
 */
function DNUI_delete($imagesToDelete, $options) {
    $updateInServer = $options['updateInServer'];
    $backup = $options["backup"];
    $base = wp_upload_dir();
    $base = $base['basedir'];
    $errors = array();
    $basePlugin = plugin_dir_path(__FILE__) . '../backup/';
    foreach ($imagesToDelete as $key => $imageToDelete) {
        clearstatcache();

        $image = wp_get_attachment_metadata($imageToDelete["id"]);
        $tmp = explode("/", $image["file"]);
        $imageName = array_pop($tmp);
        $folder = implode("/", $tmp);
        if ($backup) {

            $dirBase = $base . '/' . $folder . '/';
            $dirBackup = $basePlugin . '/' . $imageToDelete["id"];
            $backupExist = file_exists($dirBackup . '/' . $imageToDelete["id"] . '.backup');
            if (!$backupExist) {
                $backupInfo = array();
                $backupInfo["dirBase"] = $dirBase;
                $backupInfo["posts"] = DNUI_getRowPost($imageToDelete["id"]);
                $backupInfo["postMeta"] = DNUI_getRowPostMeta($imageToDelete["id"]);
                if (!file_exists($dirBackup)) {
                    mkdir($dirBackup, 0755, true);
                }
            }
        }

        if ($imageToDelete["toDelete"][0] == "original") {
            if ($backup) {
                $dirImage = $dirBase . $imageName;
                DNUI_copy($dirImage, $dirBackup . '/' . $imageName);
                foreach ($image["sizes"] as $size => $imageSize) {
                    $dirImage = $dirBase . $imageSize["file"];
                    DNUI_copy($dirImage, $dirBackup . '/' . $imageSize["file"]);
                }
            }
            wp_delete_attachment($imageToDelete["id"]);
        } else {


            foreach ($imageToDelete["toDelete"] as $keyS => $imageS) {
                $size = $image["sizes"][$imageS];
                clearstatcache();
                $dirImage = $dirBase . $size["file"];
                if (!empty($size)) {

                    if (!file_exists($dirImage)) {
                        if ($updateInServer) {
                            unset($image["sizes"][$imageS]);
                        }
                    } else {
                        if ($backup) {
                            DNUI_copy($dirImage, $dirBackup . '/' . $size["file"]);
                        }
                        if (@unlink($dirImage)) {
                            unset($image["sizes"][$imageS]);
                        } else {
                            $errors . push('Problem with ' . $size["file"] . " try toe active the option for delete the image if not exist");
                        }
                    }
                }
                wp_update_attachment_metadata($imageToDelete["id"], $image);
            }
        }
        if ($backup) {
            if (!$backupExist) {
                file_put_contents($dirBackup . '/' . $imageToDelete["id"] . '.backup', serialize($backupInfo));
            }
        }
    }
    return $errors;
}
/**
 * Copy one image from source to destination, used for the backup
 * @param type $source
 * @param type $dest
 */
function DNUI_copy($source, $dest) {
    if (file_exists($source)) {
        copy($source, $dest);
    }
}
/**
 * Get all folders and file in it
 * @param type $dir
 * @param type $type
 * @return array
 */
function DNUI_get_all_dir_or_files($dir, $type) {
    if ($type == 0) {
        return DNUI_get_dirs(array($dir));
    } else if ($type == 1) {
        $out = array();
        $arrayDirOrFile = DNUI_scan_dir($dir);
        foreach ($arrayDirOrFile as $key => $value) {
            if (!is_dir($filename)) {
                array_push($out, $filename);
            }
        }
        return $out;
    }
    return array();
}
/**
 * Scan the backup folder, getting all folder (the id), the text file with the serialized array
 * @return array
 */
function DNUI_get_backup() {
    $basePlugin = plugin_dir_path(__FILE__) . '../backup/';
    $urlBase = plugin_dir_url(__FILE__) . '../backup/';

    $out = array();
    $backups = DNUI_scan_dir($basePlugin);
    foreach ($backups as $backup) {
        $file = DNUI_scan_dir($basePlugin . $backup);
        array_push($out, array('id' => $backup, 'urlBase' => $urlBase, 'files' => $file));
    }
    return $out;
}

/**
 * Delete all backup folders
 * @return type
 */
function DNUI_cleanup_backup() {
    $basePlugin = plugin_dir_path(__FILE__) . '../backup/';

    $backups = DNUI_scan_dir($basePlugin);
    $transform = array();
    foreach ($backups as $id) {
        array_push($transform, array("backup" => $id));
    }
    DNUI_delete_backup($transform);

    return;
}
/**
 * Delete one folder backup
 * @param type $ids
 * @return type
 */
function DNUI_delete_backup($ids) {
    $basePlugin = plugin_dir_path(__FILE__) . '../backup/';
    foreach ($ids as $id) {
        $backFiles = DNUI_scan_dir($basePlugin . $id["backup"] . '/');
        foreach ($backFiles as $file) {
            @unlink($basePlugin . $id["backup"] . '/' . $file);
        }
        rmdir($basePlugin . $id["backup"] . '/');
    }
    return;
}
/**
 * Restore the backup given the id
 * @global type $wpdb
 * @param type $ids
 */
function DNUI_restore_backup($ids) {
    global $wpdb;
    $basePlugin = plugin_dir_path(__FILE__) . '../backup/';

    foreach ($ids as $id) {
        $backFiles = DNUI_scan_dir($basePlugin . $id["backup"] . '/', 1);
        $fileImages = preg_grep("/^(?!.*\.backup)/", $backFiles);
        $fileBackup = $basePlugin . $id["backup"] . '/' . array_pop(preg_grep("/^(.*\.backup)/", $backFiles));
        //var_dump($fileBackup);
        $backupInfo = unserialize(file_get_contents($fileBackup));
        foreach ($backupInfo["posts"] as $posts) {
            $wpdb->replace($wpdb->prefix . "posts", $posts);
        }
        foreach ($backupInfo["postMeta"] as $postMeta) {
            $wpdb->replace($wpdb->prefix . "postmeta", $postMeta);
        }

        foreach ($fileImages as $image) {
            rename($basePlugin . $id["backup"] . '/' . $image, $backupInfo["dirBase"] . $image);
        }
        @unlink($fileBackup);
        rmdir($basePlugin . $id["backup"] . '/');
    }
}

/**
 * Only the folders
 * @param type $dirBase
 * @return array
 */
function DNUI_get_dirs($dirBase) {

    $out = array();
    foreach ($dirBase as $dir) {
        array_push($out, $dir);
        $result = glob($dir . '/*', GLOB_ONLYDIR);
        if (!empty($result)) {
            $result2 = DNUI_get_dirs($result);
            foreach ($result2 as $value) {
                array_push($out, $value);
            }
        }
    }
    return $out;
}
/**
 * Cleanup the .. and . of folder
 * @param type $dirBase
 * @return type
 */
function DNUI_scan_dir($dirBase) {
    return array_diff(scandir($dirBase), array('..', '.'));
}

?>