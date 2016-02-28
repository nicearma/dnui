<?php
/**
 *
 * @author nicearma
 */
class CheckerImageExcerptBestLuckDNUI extends CheckerImageAbstractDNUI{


    function checkImage($id, $src, $optionDNUI)
    {
      
      	if ($optionDNUI->isExcerptCheck()) {
	        $sql = "SELECT id FROM " .$this->databaseDNUI->getPrefix() . "posts WHERE  post_excerpt in (SELECT post_parent FROM " .$this->databaseDNUI->getPrefix() . "posts WHERE id=" . $id . " ) and post_excerpt LIKE '%/$src%'";


	        return  $this->databaseDNUI->getDb()->get_results($sql, "ARRAY_A");
		}
		return array();
    }
}

