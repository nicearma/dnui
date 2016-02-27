<?php
/**
 *
 * @author nicearma
 */
class CheckersDNUI
{

    private $checkers = array();

    function __construct($databaseDNUI)
    {
        new CheckerImagePostAndPageBestLuck($databaseDNUI,$this);
        new CheckerImageExcerptBestLuck($databaseDNUI,$this);
        new CheckerImagePostMeta($databaseDNUI,$this);
        new CheckerImagePostAndPageAll($databaseDNUI,$this);
        new CheckerImageExcerptAll($databaseDNUI,$this);
    }

        public function addChecker($checker)
    {
        array_push($this->checkers, $checker);
    }

    public function verify($id,$src,$optionDNUI){

        for($i=0;$i<count($this->checkers);$i++){
           $result= call_user_func_array(array($this->checkers[$i], "checkImage"), array($id,$src,$optionDNUI));
            if(!empty($result)&&count($result)>0){
               return 1; //is used
            }
        }
        return 0; //is unused/not used

}

}