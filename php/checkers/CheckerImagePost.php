<?php
/**
 * Created by PhpStorm.
 * User: Nicolas
 * Date: 11/01/2016
 * Time: 10:23
 */

class CheckerImagePost extends CheckerImageAbstract{


    function checkImage($id, $src, $optionDNUI)
    {
        //FIND in the post parent the reference, this will useful if the image is used where was uploaded
        $sql = "SELECT id FROM " .$this->databaseDNUI->getPrefix() . "posts WHERE  post_parent in (SELECT post_parent FROM " .$this->databaseDNUI->getPrefix() . "posts WHERE id=" . $id . " ) and post_content LIKE '%/$src%'";

        $result = $this->databaseDNUI->getDb()->get_results($sql, "ARRAY_A");

        if (!empty($result)) {
            return $result;
        } else if ($optionDNUI->isDraftCheck()) {
            $sql = "SELECT id FROM " .$this->databaseDNUI->getPrefix() . "posts  WHERE post_content is not null and post_content!=''  and post_type not in ('attachment','nav_menu_item','revision') and post_status !='draft' and post_content LIKE '%/$src%' limit 0,1";
        } else {
            $sql = "SELECT id FROM " .$this->databaseDNUI->getPrefix() . "posts  WHERE post_content is not null and post_content!=''  and post_type not in ('attachment','nav_menu_item') and post_content LIKE '%/$src%' limit 0,1";
        }

        return $this->databaseDNUI->getDb()->get_results($sql, "ARRAY_A");
    }
}

