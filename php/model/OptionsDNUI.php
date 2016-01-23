<?php

/**
 * Description of Option
 *
 * @author Nicolas
 */
class OptionsDNUI  implements JsonSerializable {

    public $version="2.0";
    public $updateInServer=true;
    public $backup=true;
    public $showUsedImage=false;
    public $admin=true;
    public $ignoreSizes=array();
    public $showIgnoreSizes=true;
    public $galleryCheck=true;
    public $draftCheck=true;
    public $numberPage=1;
    public $imageShowInPage=50;
    public $order=0;

    /**
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @param string $version
     */
    public function setVersion($version)
    {
        $this->version = $version;
    }

    /**
     * @return boolean
     */
    public function isUpdateInServer()
    {
        return $this->updateInServer;
    }

    /**
     * @param boolean $updateInServer
     */
    public function setUpdateInServer($updateInServer)
    {
        if(is_bool($updateInServer)){
            $this->updateInServer = $updateInServer;
        }

    }

    /**
     * @return boolean
     */
    public function isBackup()
    {
        return $this->backup;
    }

    /**
     * @param boolean $backup
     */
    public function setBackup($backup)
    {
        if(is_bool($backup)){
            $this->backup = $backup;
        }

    }

    /**
     * @return boolean
     */
    public function isShowUsedImage()
    {
        return $this->showUsedImage;
    }

    /**
     * @param boolean $showUsedImage
     */
    public function setShowUsedImage($showUsedImage)
    {
        if(is_bool($showUsedImage)){
            $this->showUsedImage = $showUsedImage;
        }
    }

    /**
     * @return boolean
     */
    public function isAdmin()
    {
        return $this->admin;
    }

    /**
     * @param boolean $admin
     */
    public function setAdmin($admin)
    {
        if(is_bool($admin)){
            $this->admin = $admin;
        }


    }

    /**
     * @return array
     */
    public function getIgnoreSizes()
    {
        return $this->ignoreSizes;
    }

    /**
     * @param array $ignoreSizes
     */
    public function setIgnoreSizes($ignoreSizes)
    {
        $this->ignoreSizes = $ignoreSizes;
    }

    /**
     * @return boolean
     */
    public function isShowIgnoreSizes()
    {
        return $this->showIgnoreSizes;
    }

    /**
     * @param boolean $showIgnoreSizes
     */
    public function setShowIgnoreSizes($showIgnoreSizes)
    {
        if(is_bool($showIgnoreSizes)){
            $this->showIgnoreSizes = $showIgnoreSizes;
        }

    }

    /**
     * @return boolean
     */
    public function isGalleryCheck()
    {
        return $this->galleryCheck;
    }

    /**
     * @param boolean $galleryCheck
     */
    public function setGalleryCheck($galleryCheck)
    {
        if(is_bool($galleryCheck)){
            $this->galleryCheck  = $galleryCheck;
        }

    }

    /**
     * @return boolean
     */
    public function isDraftCheck()
    {
        return $this->draftCheck;
    }

    /**
     * @param boolean $draftCheck
     */
    public function setDraftCheck($draftCheck)
    {
        if(is_bool($draftCheck)){
            $this->draftCheck = $draftCheck;
        }

    }

    /**
     * @return int
     */
    public function getNumberPage()
    {

        return $this->numberPage;
    }

    /**
     * @param int $numberPage
     */
    public function setNumberPage($numberPage)
    {
        if(!is_numeric($numberPage)||$numberPage<1){
            $numberPage=1;
        }
        $this->numberPage = $numberPage;
    }

    /**
     * @return int
     */
    public function getImageShowInPage()
    {
        return $this->imageShowInPage;
    }

    /**
     * @param int $imageShowInPage
     */
    public function setImageShowInPage($imageShowInPage)
    {
        if(!is_numeric($imageShowInPage)||$imageShowInPage<1){
            $imageShowInPage=1;
        }
        $this->imageShowInPage = $imageShowInPage;
    }

    /**
     * @return int
     */
    public function getOrder()
    {

        return $this->order;
    }

    /**
     * @param int $order
     */
    public function setOrder($order)
    {
        if(!is_int($order)){
            $order=0;
        }
        $this->order = $order;
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }


}
