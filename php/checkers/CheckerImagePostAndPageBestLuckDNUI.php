<?php
/**
 *
 * @author nicearma
 */
class CheckerImagePostAndPageBestLuckDNUI extends CheckerImageAbstractDNUI{


    function checkImage($id, $src, $optionDNUI)
    {
        //FIND in the post parent the reference, this will useful if the image is used where was uploaded
        $sql = "SELECT id FROM " .$this->databaseDNUI->getPrefix() . "posts WHERE  post_parent in (SELECT post_parent FROM " .$this->databaseDNUI->getPrefix() . "posts WHERE id=" . $id . " ) and post_content LIKE '%/$src%'";

        return  $this->databaseDNUI->getDb()->get_results($sql, "ARRAY_A");

    }
}

