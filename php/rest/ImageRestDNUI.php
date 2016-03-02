<?php


/**
 * Description of Image
 *
 * @author nicearma
 */
class ImageRestDNUI
{

    private $databaseDNUI;
    private $optionsDNUI;

    function __construct()
    {
        $this->databaseDNUI = new DatabaseDNUI();
        $this->optionsDNUI = OptionsRestDNUI::readOptions();

    }

    public function readOne($id)
    {

    }


    public function countImage()
    {
        $count = $this->databaseDNUI->countImages();
        echo json_encode(array('0' => $count[0]['count(*)']));
        wp_die();
    }


    public function readByOptions()
    {
        if (!empty($_GET['numberPage'])) {
            $this->optionsDNUI->setNumberPage($_GET['numberPage']);
        }

        $imagesIds = $this->databaseDNUI->getImages($this->optionsDNUI->getNumberPage(), $this->optionsDNUI->getImageShowInPage(), $this->optionsDNUI->getOrder());
        $images = ConvertWordpressToDNUI::convertIdsToImagesDNUI($imagesIds);
        echo json_encode($images);
        wp_die();
    }

    public function readGalleries()
    {
        $result = ConvertWordpressToDNUI::convertIdToGalleriesSizes($this->databaseDNUI->getGalleries($this->optionsDNUI));
        if (!empty($result)) {
            echo json_encode($result);
        } else {
            echo '{}';
        }

        wp_die();
    }

    public function readShortCodes()
    {
	
        $resultContent = ConvertWordpressToDNUI::convertIdToHTMLShortCodes($this->databaseDNUI->getShortCodeContent($this->optionsDNUI));
        $resultExcerpt = array();

        if($this->optionsDNUI->isExcerptCheck()){
            $resultExcerpt = ConvertWordpressToDNUI::convertIdToHTMLShortCodes($this->databaseDNUI->getShortCodeExcerpt($this->optionsDNUI),'excerpt');
        }

        $result= array_merge($resultContent,$resultExcerpt);
       
        echo json_encode($result, JSON_OBJECT_AS_ARRAY);
        wp_die();
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

        echo json_encode($status);
        wp_die();


    }


    public function getSizes()
    {
        $nameSizes = get_intermediate_image_sizes();
        array_push($nameSizes, 'original');
        echo json_encode($nameSizes);
        wp_die();
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
        echo json_encode($this->databaseDNUI->delete($imageDNUI, $sizeNames, $this->optionsDNUI));
        wp_die();
    }


}
