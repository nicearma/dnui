<?php


class CheckerImageGallery extends CheckerImageAbstract{

    function checkImage($id, $src, $optionDNUI)
    {

        if ($optionDNUI->isDraftCheck()) {
            $sql = "SELECT id FROM " . $this->databaseDNUI->getPrefix() . "posts  WHERE  post_content is not null and post_content!=''  and post_type not in ('attachment','nav_menu_item','revision') and post_status !='draft' AND post_content LIKE '%[gallery%'; ";
        } else {
            $sql = "SELECT id FROM " . $this->databaseDNUI->getPrefix() . "posts  WHERE  post_content is not null and post_content!=''  and post_type not in ('attachment','nav_menu_item') and post_content LIKE '%[gallery%'";
        }

        $result = $this->databaseDNUI->getDb()->get_results($sql, "ARRAY_A");

        $info = array();
        foreach ($result as $idPost) {
            $galleries = get_post_galleries($idPost['id'], false);
            foreach ($galleries as $gallery) {
                $idsImage = explode(',', $gallery['ids']);
                foreach ($idsImage as $id) {
                    if (!array_key_exists($id, $info)) {
                        $info[$id] = array('sizes' => array());
                    }
                    $size = "original";
                    if (array_key_exists('size', $gallery)) {
                        $size = $gallery['size'];
                    }
                    if (!in_array($size, $info[$id]['sizes'])) {
                        $info[$id]['sizes'][] = $size;
                    }
                }
            }
        }

        return $info;
    }

}