<?php


class RestResponseDNUI
{
	public $httpStatus;
	public $data;

 function __construct()
    {
       
        $this->httpStatus=200;
        
    }

    public function setHttpStatus($httpStatus){
		$this->httpStatus=$httpStatus;

    }

   public function getHttpStatus(){
		return $this->httpStatus;

    }


    public function setData($data){
		$this->data=$data;

    }

     public function getData(){
		return $this->data;

    }

}