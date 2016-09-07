<?php



/**
 * Description of Option
 *
 * @author nicearma
 */
class OptionsRestDNUI
{


 	function __construct()
    {
        //set_error_handler(array('ErrorHandlerDNUI', 'errorHandler'));
    }
    public function read()
    {
        $optionsDNUI = OptionsRestDNUI::readOptions();
        die( json_encode($optionsDNUI));
    }

    public function update()
    {
        $optionsJson = json_decode(file_get_contents('php://input'));
        $optionsDNUI = ConvertOptionsDNUI::convertOptionJsonToOptionDNUI($optionsJson);
        update_option('dnui_options', serialize($optionsDNUI));
        die();
    }


    public static function readOptions()
    {
        $optionsDNUI = get_option("dnui_options");
        if (empty($optionsDNUI)) {
            $optionsDNUI = new OptionsDNUI();
        } else {
            $optionsDNUI = unserialize($optionsDNUI);
            $optionsDNUI = ConvertOptionsDNUI::convertOldTONew($optionsDNUI);
        }
        return $optionsDNUI;
    }

    public function restore()
    {

        $optionsDNUI = new OptionsDNUI();
        update_option('dnui_options', serialize($optionsDNUI));
        die( json_encode($optionsDNUI));
    }

    public function haveWooCommerce()
    {
        $haveWC = OptionsRestDNUI::havePlugin('woocommerce');

        die(json_encode($haveWC));
    }

    public static function  havePlugin($pluginName)
    {
        $havePlugin = false;
        foreach (apply_filters('active_plugins', get_option('active_plugins')) as $plugin) {
            if (strpos($pluginName, $plugin) == !false) {
                $havePlugin = true;
                break;
            }
        }
        return $havePlugin;
    }
}
