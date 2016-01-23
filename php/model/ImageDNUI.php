<?php

/**
 * Description of ImageDNUI
 *
 * @author Nicearma
 */
class ImageDNUI implements JsonSerializable
{

    public $id; //the same id of the database
    public $name;
    public $sizeName;
    public $resolution;
    public $status;
    public $srcOriginalImage; //the origina src
    public $imageSizes; //the list of imageSize, see the ImageSize

    function __construct($id, $name, $sizeName, $srcOriginalImage, $resolution)
    {
        $this->id = $id;
        $this->name = $name;
        $this->sizeName = $sizeName;
        $this->srcOriginalImage = $srcOriginalImage;
        $this->resolution = $resolution;
        $this->status = new StatusDNUI();
        $this->imageSizes = array();

    }


    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getSizeName()
    {
        return $this->sizeName;
    }

    /**
     * @param mixed $sizeName
     */
    public function setSizeName($sizeName)
    {
        $this->sizeName = $sizeName;
    }


    /**
     * @return mixed
     */
    public function getResolution()
    {
        return $this->resolution;
    }

    /**
     * @param mixed $resolution
     */
    public function setResolution($resolution)
    {
        $this->resolution = $resolution;
    }

    /**
     * @return StatusDNUI
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param StatusDNUI $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }


    /**
     * @return mixed
     */
    public function getSrcOriginalImage()
    {
        return $this->srcOriginalImage;
    }

    /**
     * @param mixed $srcOriginalImage
     */
    public function setSrcOriginalImage($srcOriginalImage)
    {
        $this->srcOriginalImage = $srcOriginalImage;
    }

    /**
     * @return mixed
     */
    public function getImageSizes()
    {
        return $this->imageSizes;
    }

    /**
     * @param mixed $imageSize
     */
    public function addImageSize($imageSize)
    {
        $this->imageSizes[$imageSize->getSizeName()] = $imageSize;
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }


}


class ImageSizeDNUI implements JsonSerializable
{

    public $name;
    public $sizeName;
    public $resolution;
    public $status;
    public $srcSizeImage; //image src


    function __construct($name, $sizeName, $resolution, $srcSizeImage)
    {
        $this->name = $name;
        $this->sizeName = $sizeName;
        $this->resolution = $resolution;
        $this->status = new StatusDNUI();
        $this->srcSizeImage = $srcSizeImage;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getSizeName()
    {
        return $this->sizeName;
    }

    /**
     * @param mixed $sizeName
     */
    public function setSizeName($sizeName)
    {
        $this->sizeName = $sizeName;
    }

    /**
     * @return mixed
     */
    public function getResolution()
    {
        return $this->resolution;
    }

    /**
     * @param mixed $resolution
     */
    public function setResolution($resolution)
    {
        $this->resolution = $resolution;
    }

    /**
     * @return StatusDNUI
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param StatusDNUI $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getSrcSizeImage()
    {
        return $this->srcSizeImage;
    }

    /**
     * @param mixed $srcSizeImage
     */
    public function setSrcSizeImage($srcSizeImage)
    {
        $this->srcSizeImage = $srcSizeImage;
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }

}


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

