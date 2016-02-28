<?php
/**
 *
 * @author nicearma
 */
class CheckerImagePostAndPageAllDNUI extends CheckerImageAbstractDNUI
{

    function checkImage($id, $src, $optionDNUI)
    {

    	 if ($optionDNUI->isDraftCheck()) {
            $sql = "SELECT id FROM " .$this->databaseDNUI->getPrefix() . "posts  WHERE post_content is not null and post_content!=''  and post_type not in ('attachment','nav_menu_item') and post_content LIKE '%/$src%' limit 0,1";
        }else{
        	$sql = "SELECT id FROM " . $this->databaseDNUI->getPrefix() . "posts  WHERE post_content is not null and post_content!=''  and post_type not in ('attachment','nav_menu_item','revision') and post_status !='draft' and post_content LIKE '%/$src%' limit 0,1";
        }
        
        return $this->databaseDNUI->getDb()->get_results($sql, "ARRAY_A");
    }

}