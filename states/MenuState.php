<?php
require_once("../config/config.php");
//require_once("./states/IApplicationState.php");
//require_once("./eventmanagement/GameEventManager.php");
/*
require_once("./eventmanagement/IEventManager.php");
require_once("./events/UpdateViewEvent.php");
require_once("./ajax/AjaxResponse.php");*/
/*
function __autoload($class) {
    include_once $class . ".php";
}*/



   // if(isset($_GET['id'])){
/*        require_once("../config.php");
        require_once("./IApplicationState.php");
        require_once("../eventmanagement/GameEventManager.php");
        require_once("../eventmanagement/IEventManager.php");
        require_once("../events/UpdateViewEvent.php");
        require_once("../ajax/AjaxResponse.php");*/
   // }
class MenuState implements IApplicationState {

    private $playerCountry;
    private $countryArray;

    function __construct(){

    }

    function init()
    {
        $this->countryArray = getCountriesArray();

        foreach($this->countryArray as $country => $role) {
                if ($role == 1) {
                    $this->playerCountry = $this->countryArray[$country];
                }
        }

        GameEventManager::getInstance()->dispatchEvent(new UpdateViewEvent($this));

        if(isset($_SESSION['IEventManager'])){
            echo "Session gesetzt: ";//.$_SESSION['IEventManager']."<br />";
        }
    }





    function endState()
    {
        // TODO: Implement endState() method.
    }

    function getApplicationStateType()
    {
        // TODO: Implement getApplicationStateType() method.
    }





    public function setPlayerCountry($playerCountry)
    {
        $this->playerCountry = $playerCountry;
    }

    public function getPlayerCountry()
    {
        return $this->playerCountry;
    }



    public static function ajaxTest(){

        /*
        header("content-type: application/json");

        if(isset($_GET['handle'])) $id = $_GET['handle'];

        $message = "You got an AJAX response via JSONP from another site!";

        $a = array('id' => $id, 'message'=>$message);
        echo $_GET['callback']. '('. json_encode($a) . ')';

        */

        if(isset($_GET[session_name()])){
            session_id($_GET[session_name()]);
            echo "session id ".$_GET[session_name()];

            if(isset($_SESSION['IEventManager'])){
              // TODO change this to useable data
              //GameEventManager::getInstance()->dispatchEvent(new UpdateViewEvent("bla"));
                print_r($_SESSION['IEventManager']);

                echo "Session Ajax gesetzt ";//.$_SESSION['IEventManager']."<br />";
            }



        }

    }


}

if(isset($_GET['handle'])){
    session_start();
    MenuState::ajaxTest();

}
?>
 
