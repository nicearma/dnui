<?php



class CheckersDNUI
{

    private $checkers = [];

    function __construct($databaseDNUI)
    {
        new CheckerImagePost($databaseDNUI,$this);
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