<?php

/**
 * Description of Option
 *
 * @author nicearma
 */
class OptionsDNUI implements JsonSerializable
{

    public $version;
    public $updateInServer;
    public $backup;
    public $showUsedImage;
    public $admin;
    public $ignoreSizes;
    public $showIgnoreSizes;
    public $galleryCheck;
    public $shortCodeCheck;
    public $excerptCheck;
    public $postMetaCheck;
    public $draftCheck;
    public $numberPage;
    public $imageShowInPage;
    public $order;
    public $maxSize;
    public $debug;

    function __construct()
    {
        $this->version = "2.0";
        $this->updateInServer = true;
        //default active if the backup folder exist
        $this->backup = HelperDNUI::backupFolderExist();
        $this->showUsedImage = false;
        $this->admin = true;
        $this->ignoreSizes = array();
        $this->showIgnoreSizes = true;
        $this->galleryCheck = true;
        $this->shortCodeCheck = true;
        $this->excerptCheck = true;
        $this->postMetaCheck = true;
        $this->draftCheck = true;
        $this->numberPage = 1;
        $this->imageShowInPage = 50;
        $this->order = 0;
        $this->maxSize=8;
        $this->debug=false;
    }


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
        if (is_bool($updateInServer)) {
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
        if (is_bool($backup)) {
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
        if (is_bool($showUsedImage)) {
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
        if (is_bool($admin)) {
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
        if (is_bool($showIgnoreSizes)) {
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
        if (is_bool($galleryCheck)) {
            $this->galleryCheck = $galleryCheck;
        }

    }

    /**
     * @return boolean
     */
    public function isShortCodeCheck()
    {
        return $this->shortCodeCheck;
    }

    /**
     * @param boolean $shortCodeCheck
     */
    public function setShortCodeCheck($shortCodeCheck)
    {
        if (is_bool($shortCodeCheck)) {
            $this->shortCodeCheck = $shortCodeCheck;
        }
    }

    /**
     * @return boolean
     */
    public function isExcerptCheck()
    {
        return $this->excerptCheck;
    }

    /**
     * @param boolean $excerptCheck
     */
    public function setExcerptCheck($excerptCheck)
    {
        if (is_bool($excerptCheck)) {
            $this->excerptCheck = $excerptCheck;
        }

    }

    /**
     * @return boolean
     */
    public function isPostMetaCheck()
    {
        return $this->postMetaCheck;
    }

    /**
     * @param boolean $postMetaCheck
     */
    public function setPostMetaCheck($postMetaCheck)
    {
        if (is_bool($postMetaCheck)) {
            $this->postMetaCheck = $postMetaCheck;
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
        if (is_bool($draftCheck)) {
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
        if (!is_numeric($numberPage) || $numberPage < 1) {
            $numberPage = 1;
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
        if (!is_numeric($imageShowInPage) || $imageShowInPage < 1) {
            $imageShowInPage = 1;
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
        if (!is_int($order)) {
            $order = 0;
        }
        $this->order = $order;
    }


    /**
     * @return int
     */
    public function getMaxSize()
    {

        return $this->maxSize;
    }

    /**
     * @param int $maxSize
     */
    public function setMaxSize($maxSize)
    {
        if (!is_int($maxSize)) {
            $maxSize = 8;
        }
        $this->maxSize = $maxSize;
    }

    /**
     * @return boolean
     */
    public function isDebug()
    {
        return $this->debug;
    }

    /**
     * @param boolean $debug
     */
    public function setDebug($debug)
    {
        return $this->debug=$debug;
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }


}
