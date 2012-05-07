<?php
    require_once("../config/config.php");
    class MenuState implements IApplicationState {

        private $playerCountry;
        private $enemyCountries;
        private $countryArray;

        function __construct() {

        }

        function init() {
            $this->countryArray = getCountriesArray();

            foreach($this->countryArray as $country => $role) {
                if($role == PLAYER_VALUE) {
                    $this->playerCountry = $country;
                }
                else if($role == ENEMY_VALUE) {
                    $this->enemyCountries[] = $country;
                }
            }

            $_SESSION['state'] = $this;
        }

        function endState() {

            // edit die drei session eintraege hab ich noch nicht gebraucht
            $_SESSION['player'] = $this->playerCountry;
            $_SESSION['enemies'] = $this->enemyCountries;
            $_SESSION['countryArray'] = $this->countryArray;

            $_SESSION['activePlayers'] = array();
            $this->createPlayer();
            $this->createEnemyPlayers();

                        //$eventmanager = $_SESSION['IEventManager'];
            GameEventManager::getInstance()->dispatchEvent(new ChangeViewEvent(new MapStateView()));
            //next state übergeben
            GameEventManager::getInstance()->dispatchEvent(new ChangeStateEvent(new MapState() /*,session_id()*/));
        }

        //create HumanPlayer
        function createPlayer() {
            $player = new HumanPlayer();
            $playerCountry = new Country();
            $playerCountry->setName($this->playerCountry);
            $playerCountry->setColor($player->getPlayerId());
            $player->setCountry($playerCountry);
            array_push($_SESSION['activePlayers'], $player);
        }

        //create ArtificialIntelligence
        function createEnemyPlayers() {

            foreach($this->enemyCountries as $enemyC) {
                $enemy = new ArtificialIntelligence();
                $enemyCountry = new Country();
                $enemyCountry->setName($enemyC);
                $enemyCountry->setColor($enemy->getPlayerId());
                $enemy->setCountry($enemyCountry);
                array_push($_SESSION['activePlayers'], $enemy);
            }
        }

        function getApplicationStateType() {
            // TODO: Implement getApplicationStateType() method.
        }

        public function setPlayerCountry($playerCountry) {
            /* Runs through country array and sets old playerCountry to 0 and the new one to 1 */
            foreach($this->countryArray as $country => $role) {
                if($role == PLAYER_VALUE) {
                    $this->countryArray[$country] = 0;
                }
                if($country == $playerCountry) {
                    $this->countryArray[$country] = PLAYER_VALUE;
                }
            }
            $this->playerCountry = $playerCountry;
        }

        public function setEnemyCountries($enemycountry) {
            /* Runs through country array and sets old enemies to 0 and new ones to -1 */
            foreach($this->countryArray as $country => $role) {
                if($country == $enemycountry) {
                    if($this->countryArray[$country] == ENEMY_VALUE) {
                        $this->countryArray[$country] = 0;

                        $key = array_search($country, $this->enemyCountries);
                        unset ($this->enemyCountries[$key]);
                    }
                    else {
                        $this->countryArray[$country] = ENEMY_VALUE;
                        $this->enemyCountries[] = $country;
                    }
                }
            }
        }

        public function getPlayerCountry() {
            return $this->playerCountry;
        }

        public function getEnemyCountries() {
            return $this->enemyCountries;
        }

        public static function ajaxRequest() {

            if(isset($_GET[session_name()])) {

                session_id($_GET[session_name()]);

                if(isset($_SESSION['IEventManager'])) {

                    // TODO change this to useable data
                    $eventManager = $_SESSION['IEventManager'];
                    //$eventManager->dispatchEvent(new UpdateViewEvent("bla"));

                    if(isset($_GET['playercountry'])) {
                        $_SESSION['state']->setPlayerCountry($_GET['playercountry']);
                    }

                    if(isset($_GET['enemycountry'])) {
                        $_SESSION['state']->setEnemyCountries($_GET['enemycountry']);
                    }

                    if(isset($_GET['endState'])) {
                        $_SESSION['state']->endState();
                    }

                }

            }
        }
    }

    if(isset($_GET['handle'])/* || isset($_GET['playercountry'])*/) {
        session_start();
        MenuState::ajaxRequest();
    }
?>
