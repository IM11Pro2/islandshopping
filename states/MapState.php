<?php
    require_once("../config/config.php");
    class MapState implements IApplicationState {

        const ApplicationStateType = "MapState";
        private $playerList;

        private $map;

        function init() {
            $this->createPlayer();
            $this->createEnemyPlayers();
            $this->playerList = $_SESSION['activePlayers'];
            $this->map = new Map($this->playerList);
            $this->map->randomizeRegions();

            $_SESSION['state'] = $this;
        }

        //create HumanPlayer
        function createPlayer() {
            $player = new HumanPlayer();
            $playerCountry = new Country();
            $playerPayment = new Payment();

            $playerCountry->setName($_SESSION['player']);
            $playerCountry->setColor($player->getPlayerId());

            $playerPayment->setCurrency($playerCountry->getName());
            $playerPayment->setCurrencyTranslation($playerCountry->getName());
            $playerPayment->setValue(START_CAPITAL_COUNTRY);

            $playerCountry->setPayment($playerPayment);

            $player->setCountry($playerCountry);
            array_push($_SESSION['activePlayers'], $player);
        }

        //create ArtificialIntelligence
        function createEnemyPlayers() {

            foreach($_SESSION['enemies'] as $enemyC) {
                $enemy = new ArtificialIntelligence();
                $enemyCountry = new Country();
                $enemyPayment = new Payment();

                $enemyCountry->setName($enemyC);
                $enemyCountry->setColor($enemy->getPlayerId());

                $enemyPayment->setCurrency($enemyCountry->getName());
                $enemyPayment->setCurrencyTranslation($enemyCountry->getName());
                $enemyPayment->setValue(START_CAPITAL_COUNTRY);

                $enemyCountry->setPayment($enemyPayment);

                $enemy->setCountry($enemyCountry);
                array_push($_SESSION['activePlayers'], $enemy);
            }
        }

        function endState() {
            GameEventManager::getInstance()->dispatchEvent(new ChangeViewEvent(new PlayStateView()));
            //next state übergeben
            GameEventManager::getInstance()->dispatchEvent(new ChangeStateEvent(new PlayState() /*,session_id()*/));
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

                if(isset($_SESSION['IEventManager'])) {

                    // TODO change this to useable data
                    $eventManager = $_SESSION['IEventManager'];
                    //$eventManager->dispatchEvent(new UpdateViewEvent("bla"));

                    if(isset($_GET['endState'])) {
                        if($_GET['endState'] == "Map") {
                            $_SESSION['state']->endState();
                        }
                    }

                }

            }
        }

    }

    if(isset($_GET['handle']) && trim($_GET['handle']) == "MapState") {

        MapState::ajaxRequest();
    }

?>
 
