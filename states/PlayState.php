<?php
    require_once("../config/config.php");
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


        public static function ajaxRequest() {

            if(!isset($_SESSION)){
                session_start();
            }

            if(isset($_GET[session_name()])) {

                session_id($_GET[session_name()]);

                if(isset($_GET['getNeigbours'])){

                    header('Content-type: application/json');

                    $regionId = trim($_GET['getNeigbours']);

                    $map = $_SESSION['map'];
                    $regions = $map->getRegions();

                    $neighbours = $regions[$regionId]->getNeighbours();

                    echo json_encode(array("activeRegion"=>$regionId ,"neighbours"=>$neighbours));
                }

            }
        }
    }

    if(isset($_GET['handle']) && trim($_GET['handle']) == "PlayState") {

        PlayState::ajaxRequest();
    }

?>
 
