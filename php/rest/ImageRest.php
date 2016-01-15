<?php

$imageRest = new ImageRest();


add_action('wp_ajax_dnui_count_image', array($imageRest, 'countImage'));

add_action('wp_ajax_dnui_get_all_by_options_image', array($imageRest, 'readByOptions'));
add_action('wp_ajax_dnui_verify_status_by_id_image', array($imageRest, 'verifyStatusById'));
add_action('wp_ajax_dnui_get_sizes', array($imageRest, 'GetSizes'));
add_action('wp_ajax_dnui_delete_by_id_and_size_image', array($imageRest, 'deleteByIdAndSize'));

/**
 * Description of Image
 *
 * @author Nicolas
 */
class ImageRest
{

    private $databaseDNUI;
    private $optionsDNUI;


    //this will be call for verify all image, "function call" http://php.net/manual/en/function.call-user-func-array.php
    private $verificator = array();

    function __construct()
    {
        $this->databaseDNUI = new DatabaseDNUI();
        //TODO: change this for get option
        $this->optionsDNUI = OptionsRest::readOptions();

    }

    public function readOne($id)
    {

    }


    public function countImage()
    {
        echo json_encode(array('0'=>$this->databaseDNUI->countImages()[0]['count(*)']));
         wp_die();
    }


    public function readByOptions()
    {
        if(!empty($_GET['numberPage'])){
            $this->optionsDNUI->setNumberPage( $_GET['numberPage']);
        }

        $imagesIds = $this->databaseDNUI->getImages($this->optionsDNUI->getNumberPage(), $this->optionsDNUI->getImageShowInPage(), $this->optionsDNUI->getOrder());
        $images = ConvertWordpressToDNUI::convertIdsToImagesDNUI($imagesIds);
        echo json_encode($images);
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
        $statusDNUI->setInServer(HelperDNUI::file_exist($imageDNUI->getSrcOriginalImage()));
        $status[$imageDNUI->getId()][$imageDNUI->getSizeName()] = $statusDNUI;

        foreach ($imageDNUI->getImageSizes() as $imageSize) {
            $statusDNUI = new StatusDNUI();
            $statusDNUI->setUsed($checkers->verify($imageDNUI->getId(), $imageSize->getName(), $this->optionsDNUI));
            $statusDNUI->setInServer(HelperDNUI::file_exist($imageSize->getSrcSizeImage()));
            $status[$imageDNUI->getId()][$imageSize->getSizeName()] = $statusDNUI;
        }

        echo json_encode($status);
         wp_die();


    }


    public function getSizes()
    {

        echo json_encode(get_intermediate_image_sizes());
         wp_die();
    }

    public function deleteByIdAndSize()
    {
        $imageId = $_GET['id'];
        $sizeName = $_GET['sizeName'];
        $imageDNUI = ConvertWordpressToDNUI::convertIdToImageDNUI($imageId);

        echo json_encode($this->databaseDNUI->delete($imageDNUI, $sizeName, $this->optionsDNUI));
         wp_die();
    }


}
