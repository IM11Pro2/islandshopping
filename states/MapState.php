<?php
require_once("../config.php");
require_once("IApplicationState.php");

class MapState implements IApplicationState {

    const ApplicationStateType = "MapState";
    //private $map = new Map();

    function init()
    {
        // TODO: Implement init() method.
    }

    function endState()
    {
        // TODO: Implement endState() method.
    }

    function getApplicationStateType()
    {
        return self::ApplicationStateType;
    }

}

?>
 
