<?php
/**
 *
 * @author nicearma
 */
class CheckerImagePostMetaDNUI extends CheckerImageAbstractDNUI{


    function checkImage($id, $src, $optionDNUI)
    {

    	if ($optionDNUI->isPostMetaCheck()) {
       
	        $sql = "SELECT post_id FROM " .$this->databaseDNUI->getPrefix() . "postmeta WHERE meta_key not in  ('_wp_attachment_metadata','_wp_attached_file') and meta_value LIKE '%/$src%'";

	        return  $this->databaseDNUI->getDb()->get_results($sql, "ARRAY_A");
		}

		return array();
    
    }


}

