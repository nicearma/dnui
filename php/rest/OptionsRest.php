<?php

add_action('wp_ajax_dnui_get_options', 'dnui_get_options');

function dnui_get_options(){
    $optionsRest = new OptionsRest();
    $optionsRest->read();
}
add_action('wp_ajax_dnui_update_options', 'dnui_update_options');

function dnui_update_options(){
    $optionsRest = new OptionsRest();
    $optionsRest->update();
}

/**
 * Description of Option
 *
 * @author Nicolas
 */
class OptionsRest
{


    public function read()
    {
        $optionsDNUI=OptionsRest::readOptions();
        echo json_encode($optionsDNUI);
         wp_die();
    }

    public function update()
    {
        $optionsJson = json_decode(file_get_contents('php://input'));
        //TODO: cast or validate object
        $optionsDNUI= ConvertOptions::convertOptionJsonToOptionDNUI($optionsJson);
        update_option('dnui_options', serialize($optionsDNUI));
        wp_die();
    }


    public static function readOptions(){
        $optionsDNUI=get_option("dnui_options");
        if(empty($optionsDNUI)){
            $optionsDNUI = new OptionsDNUI();
        }else{
            $optionsDNUI=unserialize($optionsDNUI);
            $optionsDNUI = ConvertOptions::convertOldTONew($optionsDNUI);
        }
        return $optionsDNUI;
    }


}
