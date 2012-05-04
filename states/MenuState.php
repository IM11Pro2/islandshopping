<?php
require_once("../config/config.php");
class MenuState implements IApplicationState {

    private $playerCountry;
    private $countryArray;

    function __construct(){

    }

    function init()
    {
        $this->countryArray = getCountriesArray();

        foreach($this->countryArray as $country => $role) {
                if ($role == PLAYER_VALUE) {
                    $this->playerCountry = $country;
                }
        }

        GameEventManager::getInstance()->dispatchEvent(new UpdateViewEvent($this));
        $_SESSION['state'] = $this;
    }

    function endState()
    {
        echo "endState()";
        $eventmanager = $_SESSION['IEventManager'];
        $eventmanager->dispatchEvent(new ChangeViewEvent(new MapStateView()));
        //next state übergeben
        $eventmanager->dispatchEvent(new ChangeStateEvent(new MapState()/*,session_id()*/));
    }

    function getApplicationStateType()
    {
        // TODO: Implement getApplicationStateType() method.
    }

    public function setPlayerCountry($playerCountry)
    {
        /* Runs through country array and sets old playerCountry to 0 and the new one to 1 */
        foreach($this->countryArray as $country => $role) {
            if ($role == PLAYER_VALUE) {
                $this->countryArray[$country] = 0;
            }
            if ($country == $playerCountry) {
                $this->countryArray[$country] = PLAYER_VALUE;
            }
        }

        $this->playerCountry = $playerCountry;
        echo "setPlayerCountry: ". $this->playerCountry;
    }

    public function getPlayerCountry()
    {
        return $this->playerCountry;
    }

/*    public function updateEnemyCountries()
        {
            foreach($this->countryArray as $country => $role) {
                if ($role == PLAYER_VALUE) {
                    $this->countryArray[$country] = 0;
                }
            }
        }*/

    public static function ajaxRequest(){

        if(isset($_GET[session_name()])){

            session_id($_GET[session_name()]);

            if(isset($_SESSION['IEventManager'])){

              // TODO change this to useable data
                $eventManager = $_SESSION['IEventManager'];
                $eventManager->dispatchEvent(new UpdateViewEvent("bla"));
            }

            if(isset($_GET['playercountry'])){
                $_SESSION['state']->setPlayerCountry($_GET['playercountry']);
            }

            if(isset($_GET['endState'])){
                $_SESSION['state']->endState();
            }
        }
    }
}

if(isset($_GET['handle']) || isset($_GET['playercountry'])){
    session_start();
    MenuState::ajaxRequest();
}
?>
