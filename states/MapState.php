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
                        echo "da";
                        $_SESSION['state']->endState();
                    }

                }

            }
        }

    }

    if(isset($_GET['handle'])) {
        MapState::ajaxRequest();
    }

?>
 
