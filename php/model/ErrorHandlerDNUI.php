<?php
//Change this in the version 3.0, catching error from other plugin :/
/*
class ErrorHandlerDNUI
{


public static function errorHandler($errno, $errstr, $errfile, $errline)
{
   $errorStatus=new RestResponseDNUI();
   $errorStatus->setHttpStatus(400);
   $errorStatus->setData(array('errno'=>$errno,'errstr'=>$errstr,'errfile'=>$errfile,'errline'=>$errline));
   echo json_encode($errorStatus);
        wp_die();

    //return true;
}


}

*/