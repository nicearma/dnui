<?php
/**
 *
 * @author nicearma
 */
class ConvertOptionsDNUI
{


    public static function convertOldTONew($optionsOld)
    {

        if (is_array($optionsOld) && array_key_exists('version', $optionsOld)) {
            if ($optionsOld['version'] == '1.5.2') {
                $optionsDNUI = ConvertOptionsDNUI::convert1_5to2_0($optionsOld);
            } else {
                $optionsDNUI = new OptionsDNUI();
            }
        } else if (empty($optionsOld)) {
            $optionsDNUI = new OptionsDNUI();
        } else {
            $optionsDNUI = ConvertOptionsDNUI::convertOptionJsonToOptionDNUI($optionsOld);
        }

        if ($optionsDNUI->getVersion() != '2.0') {
            //TODO: nothing for the moment
        }

        return $optionsDNUI;
    }

    public static function convert1_5to2_0($option1_5)
    {
        $optionDNUI = new OptionsDNUI();
        $optionDNUI->setNumberPage($option1_5['page']);
        $optionDNUI->setImageShowInPage($option1_5['cantInPage']);
        $optionDNUI->setDraftCheck($option1_5['without']);
        $optionDNUI->setUpdateInServer($option1_5['updateInServer']);
        $optionDNUI->setOrder($option1_5['order']);
        $optionDNUI->setShowUsedImage($option1_5['show']);
        $optionDNUI->setShowIgnoreSizes($option1_5['showIgnore']);
        $optionDNUI->setAdmin($option1_5['admin']);
        $optionDNUI->setGalleryCheck($option1_5['galleryCheck']);
        return $optionDNUI;

    }

    public static function convertOptionJsonToOptionDNUI($optionJson)
    {
        $optionsDNUI = new OptionsDNUI();
        $optionsDNUI->setUpdateInServer($optionJson->updateInServer);
        $optionsDNUI->setBackup($optionJson->backup);
        $optionsDNUI->setShowUsedImage($optionJson->showUsedImage);
        $optionsDNUI->setAdmin($optionJson->admin);

        if (is_array($optionJson->ignoreSizes)) {
            $optionsDNUI->setIgnoreSizes($optionJson->ignoreSizes);
        }

        $optionsDNUI->setShowIgnoreSizes($optionJson->showIgnoreSizes);

        $optionsDNUI->setGalleryCheck($optionJson->galleryCheck);
        $optionsDNUI->setShortCodeCheck($optionJson->shortCodeCheck);
        $optionsDNUI->setExcerptCheck($optionJson->excerptCheck);
        $optionsDNUI->setPostMetaCheck($optionJson->postMetaCheck);
        $optionsDNUI->setDraftCheck($optionJson->draftCheck);
        $optionsDNUI->setNumberPage($optionJson->numberPage);
        $optionsDNUI->setImageShowInPage($optionJson->imageShowInPage);
        $optionsDNUI->setOrder($optionJson->order);
 		$optionsDNUI->setMaxSize($optionJson->maxSize);
        $optionsDNUI->setDebug($optionJson->debug);

        return $optionsDNUI;

    }


}
