<?php
    require_once("../config/config.php");
    class MapState implements IApplicationState {

        const ApplicationStateType = "MapState";
        private $player;

        //private $map = new Map();

        function init() {
            /*if(isset($_SESSION['IEventManager'])) {
                $_SESSION['IEventManager']->dispatchEvent(new UpdateViewEvent($this));
            }
            else {
                echo "<br /> Nicht gesetzt";
            }
            */
        }

        function endState() {
            // TODO: Implement endState() method.
        }

        function getApplicationStateType() {
            return self::ApplicationStateType;
        }

    }

?>
 
