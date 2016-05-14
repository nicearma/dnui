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

	public function haveWooCommerce(){
        $haveWC=array("active"=>in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) );
        die(json_encode($haveWC));
    }

}
