<?php

class ErrorHandlerDNUIPRO
{


public static function errorHandler($errno, $errstr, $errfile, $errline)
{
   $errorStatus=new RestResponseDNUIPRO();
   $errorStatus->setHttpStatus(400);
   $errorStatus->setData(array('errno'=>$errno,'errstr'=>$errstr,'errfile'=>$errfile,'errline'=>$errline));
   echo json_encode($errorStatus);
        wp_die();

    //return true;
}


}

