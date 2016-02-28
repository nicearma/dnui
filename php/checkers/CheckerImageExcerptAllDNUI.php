<?php

/**
 *
 * @author nicearma
 */
class CheckerImageExcerptAllDNUI extends CheckerImageAbstractDNUI
{

    function checkImage($id, $src, $optionDNUI)
    {

    	if ($optionDNUI->isExcerptCheck()) {
			
			if ($optionDNUI->isDraftCheck()) {
		            $sql = "SELECT id FROM " .$this->databaseDNUI->getPrefix() . "posts  WHERE post_excerpt is not null and post_excerpt!=''  and post_type not in ('attachment','nav_menu_item') and post_excerpt LIKE '%/$src%' limit 0,1";
		    }else{
		        	$sql = "SELECT id FROM " . $this->databaseDNUI->getPrefix() . "posts  WHERE post_excerpt is not null and post_excerpt!=''  and post_type not in ('attachment','nav_menu_item','revision') and post_status !='draft' and post_excerpt LIKE '%/$src%' limit 0,1";
		    }

	        return $this->databaseDNUI->getDb()->get_results($sql, "ARRAY_A");
    	}

    	return array();
    }

}


