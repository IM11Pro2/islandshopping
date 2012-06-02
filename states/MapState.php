<?php
    require_once("../config/config.php");
    class MapState implements IApplicationState {

        const ApplicationStateType = "MapState";
        private $playerList;
        private $colorArray;

        private $map;

        function init() {
            $this->colorArray = getColorArray();

            $this->createPlayer();
            $this->createEnemyPlayers();

            $this->playerList = $_SESSION['activePlayers'];
            $this->map = new Map($this->playerList);
            $this->map->randomizeRegions();


            $_SESSION['state'] = $this;
        }

        //create HumanPlayer
        function createPlayer() {
            $player = new HumanPlayer($_SESSION['playerCountryName'], $this->colorArray);

            array_push($_SESSION['activePlayers'], $player);
        }

        //create ArtificialIntelligence
        function createEnemyPlayers() {

            foreach($_SESSION['enemyCountryNames'] as $enemyCountryName) {
                $enemy = new ArtificialIntelligence($enemyCountryName, $this->colorArray);

                array_push($_SESSION['activePlayers'], $enemy);
            }
        }

        function randomizeMap() {
            $this->map->randomizeRegions();
            GameEventManager::getInstance()->dispatchEvent(new UpdateViewEvent());
        }

        function endState() {
            //next state übergeben
            GameEventManager::getInstance()->dispatchEvent(new ChangeStateEvent(new PlayState()));
            GameEventManager::getInstance()->dispatchEvent(new ChangeViewEvent(new PlayStateView()));
        }

        function getApplicationStateType() {
            return self::ApplicationStateType;
        }

        public static function ajaxRequest() {

            if(!isset($_SESSION)) {
                session_start();
            }

            if(isset($_GET[session_name()])) {

                session_id($_GET[session_name()]);


                if(isset($_GET['endState'])) {
                    if($_GET['endState'] == "Map") {
                        $_SESSION['state']->endState();
                    }
                }

                if(isset($_GET['randomizeMap'])) {
                    $_SESSION['state']->randomizeMap();
                }


            }
        }

    }

    if(isset($_GET['handle']) && trim($_GET['handle']) == "MapState") {
        MapState::ajaxRequest();
    }

?>
 
