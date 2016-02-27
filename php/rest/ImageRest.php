<?php

add_action('wp_ajax_dnui_count_image', 'dnui_count_image');

function dnui_count_image()
{
    $imageRest = new ImageRest();
    $imageRest->countImage();
}

add_action('wp_ajax_dnui_get_all_by_options_image', 'dnui_get_all_by_options_image');

function dnui_get_all_by_options_image()
{
    $imageRest = new ImageRest();
    $imageRest->readByOptions();
}


add_action('wp_ajax_dnui_get_galleries_image', 'dnui_get_galleries_image');

function dnui_get_galleries_image()
{
    $imageRest = new ImageRest();
    $imageRest->readGalleries();
}

add_action('wp_ajax_dnui_get_shortcodes_image', 'dnui_get_shortcodes_image');

function dnui_get_shortcodes_image()
{
    $imageRest = new ImageRest();
    $imageRest->readShortCodes();
}


add_action('wp_ajax_dnui_verify_status_by_id_image', 'dnui_verify_status_by_id_image');

function dnui_verify_status_by_id_image()
{
    $imageRest = new ImageRest();
    $imageRest->verifyStatusById();
}


add_action('wp_ajax_dnui_get_sizes', 'dnui_get_sizes');

function dnui_get_sizes()
{
    $imageRest = new ImageRest();
    $imageRest->getSizes();
}


add_action('wp_ajax_dnui_delete_by_id_and_size_image', 'dnui_delete_by_id_and_size_image');

function dnui_delete_by_id_and_size_image()
{
    $imageRest = new ImageRest();
    $imageRest->deleteByIdAndSize();
}

/**
 * Description of Image
 *
 * @author nicearma
 */
class ImageRest
{

    private $databaseDNUI;
    private $optionsDNUI;

    function __construct()
    {
        $this->databaseDNUI = new DatabaseDNUI();
        $this->optionsDNUI = OptionsRest::readOptions();

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
       
        echo json_encode($result);
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
