<?php

class StatusDNUI implements JsonSerializable
{


    /* -5 => Backup error
    * -3 => Error
     * -2 => UNKNOWN
     * -1 => Asking...
     * 0 => Unused
     * 1 => Used
     * 2 => Deleted
     * 3 => Erasing
     * 4 => Making backup
     * 5 => backup made
     */
    public $used = -2;


    /*
     * -5 => Backup error
    * -2 => UNKNOWN
    * -1 => Asking...
    *  0 => Not in server
    *  1 => In server
     * 4 => Making backup
     * 5 => backup made
     *
    */
    public $inServer = -2;

    /**
     * @return int
     */
    public function getUsed()
    {
        return $this->used;
    }

    /**
     * @param int $used
     */
    public function setUsed($used)
    {
        $this->used = $used;
    }

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