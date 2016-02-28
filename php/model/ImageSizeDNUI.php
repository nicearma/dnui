<?php


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
