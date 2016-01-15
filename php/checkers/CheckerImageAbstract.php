<?php


abstract class CheckerImageAbstract {

    protected $databaseDNUI;
    protected $checkersDNUI;

    function __construct($databaseDNUI, $checkersDNUI)
    {
        $this->databaseDNUI = $databaseDNUI;
        $this->checkersDNUI=$checkersDNUI;
        $this->checkersDNUI->addChecker($this);
    }


    abstract function checkImage($id,$src,$optionDNUI);


}