<?php
require_once("../config.php");
require_once("IApplicationState.php");

class MenuState implements IApplicationState {

    private $playerCountry;

    function init()
    {

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

}

?>
 
