<?php
    require_once("../config/config.php");

    class PlayState implements IApplicationState, IEventListener {

        const ApplicationStateType = "PlayState";

        private $bankList;
        private $incidentGenerator;
        private $incident;

        private $speculationValues;

        private $playerList;
        private $numberOfPlayers;
        private $nextPlayerCounter;

        function init() {
            $this->incidentGenerator = new IncidentGenerator();
            $_SESSION['incidentGenerator'] = $this->incidentGenerator;

            $this->playerList = $_SESSION['activePlayers'];
            $this->numberOfPlayers = count($this->playerList);
            $this->nextPlayerCounter = 0;

            $this->bankList = array();
            for($i = 0; $i < $this->numberOfPlayers; $i++){

                $bank = null;
                if($i == 0){
                    $bank = new Bank($this->playerList[$i]->getCountry(), Bank::PAY_OFF);
                }
                else{
                    $bank = new Bank($this->playerList[$i]->getCountry(), Bank::DEPOSIT);
                }
                array_push($this->bankList, $bank);

            }

            $_SESSION['state'] = $this;

            $this->speculationValues = getSpeculationValues();

            GameEventManager::getInstance()->addEventListener($this, GlobalBankEvent::TYPE);
            GameEventManager::getInstance()->addEventListener($this, GlobalRegionEvent::TYPE);
            GameEventManager::getInstance()->addEventListener($this, LocalIncidentEvent::TYPE);
        }

        function endState() {
            GameEventManager::getInstance()->dispatchEvent(new ChangeViewEvent(new EndOfPlayStateView()));
            //next state übergeben
            GameEventManager::getInstance()->dispatchEvent(new ChangeStateEvent(new EndOfPlayState()));
        }

        function getApplicationStateType() {
            return self::ApplicationStateType;
        }

        function tryToBuyRegion($attackingRegionId, $enemyId){
                    header('Content-type: application/json');

                    $regionId = $attackingRegionId;
        
                    $map = $_SESSION['map'];

                    $activeRegion = $map->getRegion($regionId);
                    $activePayment = $map->getRegion($regionId)->getPayment();

                    $playerId = $activeRegion->getPlayerId();

                    $enemyRegion = $map->getRegion($enemyId);
                    $enemyPayment = $enemyRegion->getPayment();
            
                    $enemyPlayerId = $enemyRegion->getPlayerId();
                    $enemyCountryName = $enemyRegion->getCountry()->getName();

                    $speculationValue = $this->speculationValues[mt_rand(0,count($this->speculationValues)-1)];

                    $hasPlayerWon = $activePayment->isBuyable($enemyPayment, $speculationValue);
        
                    if($hasPlayerWon){

                        $activePayment->setValue( ($activePayment->getValue() * $speculationValue) );

                        $purchasePayment = $activeRegion->buyRegion($enemyRegion);
                        
                        $this->bankList[$enemyPlayerId]->deposit($purchasePayment, true);

                        $enemyBankCapital = $this->bankList[$enemyPlayerId]->getCapital();

                        $this->handleResponse(array("attackCountry" => true,
                                                    "spendMoney" => false,
                                                    "nextPlayer" => false,
                                                    "playerId" => $playerId,
                                                    "activeRegion" => array("hasWon"=> $hasPlayerWon,
                                                                            "payment" => $activePayment->__toString(),
                                                                            "regionId" => $regionId,
                                                                            "ventureValue" => $speculationValue),
                                                    "enemyRegion" => array(
                                                                            "regionId" => $enemyId,
                                                                            "regionOfPlayer" => $enemyRegion->getPlayerId(),
                                                                            "countryColor" => $enemyRegion->getColor(),
                                                                            "payment" => $enemyPayment->__toString()),

                                                    "enemyBank" => array("bankName" => $enemyCountryName."Bank",
                                                                        "bankCapital" => $enemyBankCapital)
                                                ));

                    }
                    else{
                        //echo json_encode( array("activeRegion" => array("hasWon"=> $hasPlayerWon)));
                        $activePayment->setValue(BASIC_CAPITAL_REGION);
                        $this->handleResponse(array("attackCountry" => true,
                                                    "spendMoney" => false,
                                                    "nextPlayer" => false,
                                                    "playerId" => $playerId,
                                                    "enemyRegion" => array("regionId" => $enemyId),
                                                    "activeRegion" => array("hasWon"=> $hasPlayerWon,
                                                                            "payment" => $activePayment->__toString(),
                                                                            "regionId" => $regionId,
                                                                            "ventureValue" => $speculationValue)));
                    }
                }
        
        function spendMoney($regionId, $action){
                    header('Content-type: application/json');
        
                    $map = $_SESSION['map'];
                    $regions = $map->getRegions();

                    $playerId = $regions[$regionId]->getPlayerId();

                    if($action == "payOff" ||  (isset($_GET['bankstate']) && trim($_GET['bankstate']) == Bank::PAY_OFF)  ) {
                        $this->bankList[$playerId]->payOff($regions[$regionId]->getPayment());

                    }
                   else if($action == "deposit" ||  (isset($_GET['bankstate']) && trim($_GET['bankstate']) == Bank::DEPOSIT)  ) {
                       $this->bankList[$playerId]->deposit($regions[$regionId]->getPayment(), false);
                    }

                    $country = $regions[$regionId]->getCountry();
                    $paymentValue = $regions[$regionId]->getPayment()->__toString();


                    $bankCapital = $this->bankList[$playerId]->getCapital();
        
                    $this->handleResponse(array("attackCountry" => false,
                                            "spendMoney" => true,
                                            "nextPlayer" => false,
                                            "playerId" => $playerId,
                                            "activeRegion"=> $regionId,
                                            "action" => $action,
                                            "payment"     => array("value"    => $paymentValue,
                                                                  "currency" => $country->getPayment()->getCurrency()),
                                            "bankCapital" => $bankCapital,
                                            "bankName" => $country->getName()."Bank"));
                }

        function nextPlayer() {
            header('Content-type: application/json');

            $this->playerList[$this->nextPlayerCounter]->setPlayerState(IPlayer::INACTIVE);

            $nextPlayerId = $this->getNextPlayerId();

            $this->playerList[$nextPlayerId]->setPlayerState(IPlayer::ACTIVE);

            echo json_encode(array("attackCountry" => false,
                                   "spendMoney" => false,
                                   "nextPlayer" => true,
                                   "nextPlayerId" => $nextPlayerId));
        }

        private function getNextPlayerId(){
            do{
                $this->nextPlayerCounter++;
                $this->nextPlayerCounter = $this->nextPlayerCounter % $this->numberOfPlayers;

            }while($this->playerList[$this->nextPlayerCounter]->getPlayerState() == IPlayer::GAME_OVER );

            return $this->nextPlayerCounter;
        }

        function activateAI($aiPlayerId) {

            if($this->playerList[$aiPlayerId]->getPlayerState() != IPlayer::GAME_OVER){

               foreach($this->bankList as $bank){
                    $bank->setState(Bank::DEPOSIT);
               }

               $map = $_SESSION['map'];

               //$allAiPlayerRegions = array();

               $aiPlayer = $_SESSION['activePlayers'][$aiPlayerId];

                $allAiPlayerRegions = $map->getRegionsForPlayerId($aiPlayerId);

                if(count($allAiPlayerRegions) == 0){
                    $this->playerList[$aiPlayerId]->setPlayerState(IPlayer::GAME_OVER);
                    $this->nextPlayer();
                }
                else{
               //print_r($allEnemyRegions);

                   if(!$_SESSION['incidentGenerator']->isIncidentActive()){
                       $_SESSION['incidentGenerator']->generateIncident();
                   }
                   $regions = $map->getRegions();
                   $decision = $aiPlayer->makeDecision($allAiPlayerRegions, $regions);

                    $this->doDecision($aiPlayerId, $decision);

                }
            }
            else {
                $this->nextPlayer();
            }
        }

        public function doDecision($aiPlayerId, $decision){
            if(array_key_exists("attack", $decision)){
                $this->bankList[$aiPlayerId]->setState(Bank::ATTACK);
                $this->tryToBuyRegion($decision["actualRegionId"], $decision["attack"]);
            }
            else if(array_key_exists("payOff", $decision)) {
                $this->bankList[$aiPlayerId]->setState(Bank::PAY_OFF);
                $this->spendMoney($decision["payOff"], "payOff");
            }
            else if(array_key_exists("nextPlayer", $decision)) {
                $this->bankList[$aiPlayerId]->setState(Bank::DEPOSIT);
                $this->nextPlayer();
            }
            else if(array_key_exists("deposit", $decision)) {
                $this->bankList[$aiPlayerId]->setState(Bank::DEPOSIT);
                $this->spendMoney($decision["deposit"], "deposit");
            }
        }

        public static function ajaxRequest() {
       
                   if(!isset($_SESSION)) {
                       session_start();
                   }
       
                   if(isset($_GET[session_name()])) {
       
                       session_id($_GET[session_name()]);
       
                       if(isset($_GET['getNeigbours'])) {
       
                           header('Content-type: application/json');
       
                           $regionId = trim($_GET['getNeigbours']);
       
                           $map = $_SESSION['map'];
                           $regions = $map->getRegions();
       
                           $neighbours = $regions[$regionId]->getNeighbours();

                           echo json_encode(array("activeRegion"=> $regionId,
                                                  "neighbours"  => $neighbours));
                       }
       
                       if(isset($_GET['getCountry'])) {
                           $_SESSION['state']->spendMoney(trim($_GET['getCountry']), "");
                       }
       
                       if(isset($_GET['region']) && isset($_GET['enemy'])){
                           $_SESSION['state']->tryToBuyRegion(trim($_GET['region']), trim($_GET['enemy']));
                       }
       
                       if(isset($_GET['nextPlayer'])){
                           $nextPlayerId = $_SESSION['state']->getNextPlayerId();

                           $_SESSION['state']->activateAI($nextPlayerId);
                       }

                       if(isset($_GET['newAIRequest'])){
                           $nextPlayerCounter = $_SESSION['state']->getNextPlayerCounter();
                           $numberOfPlayers = count($_SESSION['activePlayers']);

                           $nextPlayerId = $nextPlayerCounter % $numberOfPlayers;

                           if($nextPlayerId != 0) {
                                $_SESSION['state']->activateAI($nextPlayerId);
                           }
                           else {
                            echo json_encode(array("humanPlayer" => true));
                           }
                       }
                   }
               }

        function handleEvent(IEvent $event)
        {
            if($event->getEventType() == GlobalBankEvent::TYPE ||
                $event->getEventType() == GlobalRegionEvent::TYPE ||
                $event->getEventType() == LocalIncidentEvent::TYPE
            ){

                $this->incident = $event->execute();

            }
        }

        private function handleResponse($json){


           if(isset($this->incident)){
               $json['incident'] = $this->incident;
           }
           unset($this->incident);

            echo json_encode($json);
        }

        public function setBankList($bankList) {
            $this->bankList = $bankList;
        }

        public function getBankList() {
            return $this->bankList;
        }

        public function setNextPlayerCounter($nextPlayerCounter) {
            $this->nextPlayerCounter = $nextPlayerCounter;
        }

        public function getNextPlayerCounter() {
            return $this->nextPlayerCounter;
        }
    }

           if(isset($_GET['handle']) && trim($_GET['handle']) == "PlayState") {
       
               PlayState::ajaxRequest();
           }
       
       ?>
 
