<?php


/**
 * Description of Image
 *
 * @author nicearma
 */
class ImageRestDNUI
{

    protected $databaseDNUI;
    protected $optionsDNUI;
    protected $checkers;

    function __construct()
    {
        $this->databaseDNUI = new DatabaseDNUI();
        $this->optionsDNUI = OptionsRestDNUI::readOptions();
		$this->checkers=new CheckersDNUI($this->databaseDNUI);
        //set_error_handler(array('ErrorHandlerDNUI', 'errorHandler'));
    
    }

    public function readOne($id)
    {

    }


    public function countImage()
    {
        $count = $this->databaseDNUI->countImages();
        die(json_encode(array('0' => $count[0]['count(*)'])));
    }


    public function readByOptions()
    {
        if (!empty($_GET['numberPage'])) {
            $this->optionsDNUI->setNumberPage($_GET['numberPage']);
        }

        $imagesIds = $this->databaseDNUI->getImages($this->optionsDNUI->getNumberPage(), $this->optionsDNUI->getImageShowInPage(), $this->optionsDNUI->getOrder());
        $images = ConvertWordpressToDNUI::convertIdsToImagesDNUI($imagesIds);
        die(json_encode($images));
    }

    public function readGalleries()
    {
        $result = ConvertWordpressToDNUI::convertIdToGalleriesSizes($this->databaseDNUI->getGalleries($this->optionsDNUI));

        if (!empty($result)) {
            $output= json_encode($result);
        } else {
            $output= '{}';
        }

        die($output);
    }

    public function readShortCodes()
    {
	
        $resultContent = ConvertWordpressToDNUI::convertIdToHTMLShortCodes($this->databaseDNUI->getShortCodeContent($this->optionsDNUI));
        $resultExcerpt = array();

        if($this->optionsDNUI->isExcerptCheck()){
            $resultExcerpt = ConvertWordpressToDNUI::convertIdToHTMLShortCodes($this->databaseDNUI->getShortCodeExcerpt($this->optionsDNUI),'excerpt');
        }

        $result= array_values(array_merge($resultContent,$resultExcerpt));

        die( json_encode($result));
    }
    
    public function verifyStatusById()
    {
        $status = array();

        $imageId = $_GET['id'];
        $imageDNUI = ConvertWordpressToDNUI::convertIdToImageDNUI($imageId);
        $checkers = new CheckersDNUI($this->databaseDNUI);
        $statusDNUI = new StatusDNUI();
        $statusDNUI->setUsed($checkers->verify($imageDNUI->getId(), $imageDNUI->getName(), $this->optionsDNUI));
        $statusDNUI->setInServer(HelperDNUI::fileExist($imageDNUI->getSrcOriginalImage()));
        $status[$imageDNUI->getId()][$imageDNUI->getSizeName()] = $statusDNUI;

        foreach ($imageDNUI->getImageSizes() as $imageSize) {
            $statusDNUI = new StatusDNUI();
            $statusDNUI->setUsed($checkers->verify($imageDNUI->getId(), $imageSize->getName(), $this->optionsDNUI));
            $statusDNUI->setInServer(HelperDNUI::fileExist($imageSize->getSrcSizeImage()));
            $status[$imageDNUI->getId()][$imageSize->getSizeName()] = $statusDNUI;
        }

        die( json_encode($status));
    }


    public function getSizes()
    {
        $nameSizes = get_intermediate_image_sizes();
        array_push($nameSizes, 'original');
        die(json_encode($nameSizes));
    }

    public function deleteByIdAndSize()
    {
        $json = json_decode(file_get_contents('php://input'), true);

        $imageId = $json['id'];
        if (!is_numeric($imageId)) {
            //nothing to do, this case is not possible but for security reason
            return;
        }

        $sizeNames = $json['sizeNames'];

        if (!is_array($sizeNames)) {
            //nothing to do, this case is not possible but for security reason
            return;
        }

        $imageDNUI = ConvertWordpressToDNUI::convertIdToImageDNUI($imageId);
        die(json_encode($this->databaseDNUI->delete($imageDNUI, $sizeNames, $this->optionsDNUI)));
    }


}
