<?php
/**
*
* @author nicearma
*/
class StatusBackupDNUI implements JsonSerializable
{


/*
* -4 backup id folder can not be delete
* -3 => can not be delete backup folder
* -2 => status unknow
*  0 => backup folder not exists
*  1 => backup folder exist
*  2 => moved to upload folder (restore option)
*  3 => backup id folder have been deleted
*  4 => Restoring...
*  5 => Deleting...
*  6 => Restored and deleted
*  7 => Restored and deleting...
*/
public $inServer = -2;


/**
* @return int
*/
public function getInServer()
{
return $this->inServer;
}

/**
* @param int $inServer
*/
public function setInServer($inServer)
{
$this->inServer = $inServer;
}


public function jsonSerialize()
{
return get_object_vars($this);
}
}


