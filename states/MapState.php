<?php
    require_once("../config/config.php");
    class MapState implements IApplicationState {

        const ApplicationStateType = "MapState";
        private $playerList;

        private $map;

        function init() {
            $this->playerList = $_SESSION['activePlayers'];
            $this->map = new Map($this->playerList);
            $this->map->randomizeRegions();

        }

        function endState() {
            // TODO: Implement endState() method.
        }

        function getApplicationStateType() {
            return self::ApplicationStateType;
        }

    }

?>
 
