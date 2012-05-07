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

            $_SESSION['state'] = $this;
        }

        function endState() {
            echo "hallo";
            GameEventManager::getInstance()->dispatchEvent(new ChangeViewEvent(new PlayStateView()));
            //next state übergeben
            GameEventManager::getInstance()->dispatchEvent(new ChangeStateEvent(new PlayState() /*,session_id()*/));
        }

        function getApplicationStateType() {
            return self::ApplicationStateType;
        }

        public static function ajaxRequest() {

            if(isset($_GET[session_name()])) {

                session_id($_GET[session_name()]);

                if(isset($_SESSION['IEventManager'])) {

                    // TODO change this to useable data
                    $eventManager = $_SESSION['IEventManager'];
                    //$eventManager->dispatchEvent(new UpdateViewEvent("bla"));

                    if(isset($_GET['endState'])) {
                        echo $_GET['endState'];
                        if($_GET['endState'] == "Map") {
                            $_SESSION['state']->endState();
                        }
                        else{
                            echo "waweaw";
                        }
                    }

                }

            }
        }

    }

    if(isset($_GET['handle'])) {
        MapState::ajaxRequest();
    }

?>
 
