<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ConvertWordpressToDNUI
 *
 * @author Nicolas
 */
class ConvertWordpressToDNUI
{


    public static function convertIdsToImagesDNUI($imagesIds)
    {

        $images = array();

        if (!empty($imagesIds)) {

            $base = wp_upload_dir();

            foreach ($imagesIds as $key => $imageId) {

                $imageDNUI = ConvertWordpressToDNUI::convertIdToImageDNUI($imageId["id"]);
                if(!empty($imageDNUI)){
                    array_push($images, $imageDNUI);
                }

            }


        }
        return $images;

    }

    public static function convertIdToImageDNUI($imageId)
    {
        $imageDNUI=array();
        $attachment = wp_get_attachment_metadata($imageId);
        if (!empty($attachment)&&array_key_exists('file', $attachment)) { //TODO: something is not right, see way, this was found in my production server


            $baseDirs = explode("/", $attachment["file"]);
            $name = array_pop($baseDirs);
            $uploadDir = implode("/", $baseDirs);
            $imageDNUI = new ImageDNUI($imageId, $name, 'original', $attachment['file'], $attachment['width'] . 'x' . $attachment['height']);

            if (array_key_exists('sizes', $attachment)) {
                foreach ($attachment['sizes'] as $nameSize => $size) {
                    $imageSizeDNUI = new ImageSizeDNUI($size['file'], $nameSize, $size['width'] . 'x' . $size['height'], $uploadDir . '/' . $size['file']);
                    $imageDNUI->addImageSize($imageSizeDNUI);
                }
            };
        }

        return $imageDNUI;

    }


}
