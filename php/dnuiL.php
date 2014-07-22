<?php

//  Check in the database if the image is used in post_content
function DNUI_checkImageDB($ImageName, $postId) {
    global $wpdb;
    $sql = "SELECT COUNT(*) FROM " . $wpdb->prefix . "posts WHERE  post_parent in (SELECT post_parent FROM " . $wpdb->prefix . "posts WHERE id=" . $postId . " ) and post_content LIKE '%/$ImageName%'=";
    $wpdb->get_results($sql, "ARRAY_A");
    if ($result[0]['COUNT(*)'] > 0) {
        return true;
    } else {
        $sql = "SELECT COUNT(*) FROM " . $wpdb->prefix . "posts  WHERE post_content LIKE '%/$ImageName%' limit 0,1";
    }

    $result = $wpdb->get_results($sql, "ARRAY_A");
    return $result[0]['COUNT(*)'] > 0;
}

//check all image in database who have the condition Type of post 'attachment'  Type of MIME = 'image'and give all the sizes 
function DNUI_getImages($i, $max, $order) {
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
    // var_dump($result);
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

    return DNUI_checkList($images);
}

function DNUI_getAllPost($id) {
    global $wpdb;
    $sql = 'SELECT * FROM ' . $wpdb->prefix . 'posts where id=' . $id . ';';
    return $wpdb->get_results($sql, "ARRAY_A");
}

function DNUI_getAllPostMeta($id) {
    global $wpdb;
    $sql = 'SELECT * FROM ' . $wpdb->prefix . 'postmeta where post_id=' . $id . ';';
    return $wpdb->get_results($sql, "ARRAY_A");
}

//Make update of wp_postmeta with a vector sizes serialized the one image 
function DNUI_updateImages($value, $id) {
    global $wpdb;
    $value = str_replace("'", "''", $value);
    $sql = 'update ' . $wpdb->prefix . "postmeta set meta_value='" . ($value) . "' where meta_id='$id'";
    return $wpdb->query($sql);
}

function DNUI_checkList($images) {
    foreach ($images as $key => $image) {
        $images[$key]['meta_value']["use"] = DNUI_checkImageDB($image['meta_value']["file"], $image['id']);
        foreach ($image['meta_value']['sizes'] as $keyS => $imageS) {
            clearstatcache();
            $images[$key]['meta_value']['sizes'][$keyS]["use"] = DNUI_checkImageDB($imageS["file"], $image['id']);

            if ($images[$key]['meta_value']['sizes'][$keyS]["use"]) {
                $images[$key]['meta_value']["use"] = true;
            }
        }
    }
    return $images;
}

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
                $backupInfo["posts"] = DNUI_getAllPost($imageToDelete["id"]);
                $backupInfo["postMeta"] = DNUI_getAllPostMeta($imageToDelete["id"]);
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

function DNUI_copy($source, $dest) {
    if (file_exists($source)) {
        copy($source, $dest);
    }
}

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
            $wpdb->insert($wpdb->prefix . "posts", $posts);
        }
        foreach ($backupInfo["postMeta"] as $postMeta) {
            $wpdb->insert($wpdb->prefix . "postmeta", $postMeta);
        }

        foreach ($fileImages as $image) {
            rename($basePlugin . $id["backup"] . '/' . $image, $backupInfo["dirBase"] . $image);
        }
        @unlink($fileBackup);
        rmdir($basePlugin . $id["backup"] . '/');
    }
}

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

function DNUI_scan_dir($dirBase) {
    return array_diff(scandir($dirBase), array('..', '.'));
}

?>