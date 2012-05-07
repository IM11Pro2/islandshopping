<?php
    //require_once("../config.php");
    //require_once("IApplicationState.php");

    class PlayState implements IApplicationState {

        const ApplicationStateType = "PlayState";

        //private $bank = new Bank();

        function init() {
            // TODO: Implement init() method.
        }

        function endState() {
            GameEventManager::getInstance()->dispatchEvent(new ChangeViewEvent(new EndOfPlayStateView()));
            //next state übergeben
            GameEventManager::getInstance()->dispatchEvent(new ChangeStateEvent(new EndOfPlayState() /*,session_id()*/));
        }

        function getApplicationStateType() {
            return self::ApplicationStateType;
        }

    }

?>
 
