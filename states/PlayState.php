<?php
    require_once("../config/config.php");

    class PlayState implements IApplicationState, IEventListener {

        const ApplicationStateType = "PlayState";

        private $listOfBanks;
        private $incidentGenerator;
        private $incident;

        private $ventureList;

        private $playerList;
        private $numberOfPlayers;
        private $nextPlayerCounter;

        function init() {
            $this->incidentGenerator = new IncidentGenerator();
            $_SESSION['incidentGenerator'] = $this->incidentGenerator;

            $this->playerList = $_SESSION['activePlayers'];
            $this->numberOfPlayers = count($this->playerList);
            $this->nextPlayerCounter = 0;

            $this->listOfBanks = array();
            for($i = 0; $i < $this->numberOfPlayers; $i++){

                array_push($this->listOfBanks, new Bank($this->playerList[$i]->getCountry(), Bank::PAY_OFF));

            }

            $_SESSION['listOfBanks'] = $this->listOfBanks;
            $_SESSION['state'] = $this;
            $_SESSION['nextPlayerCounter'] =  $this->nextPlayerCounter;

            $this->ventureList = getVentureValues();

            $this->nextPlayerCounter = 0;

            GameEventManager::getInstance()->addEventListener($this, GlobalBankEvent::TYPE);
            GameEventManager::getInstance()->addEventListener($this, GlobalRegionEvent::TYPE);
            GameEventManager::getInstance()->addEventListener($this, LocalIncidentEvent::TYPE);
        }

        function endState() {
            GameEventManager::getInstance()->dispatchEvent(new ChangeViewEvent(new EndOfPlayStateView()));
            //next state übergeben
            GameEventManager::getInstance()->dispatchEvent(new ChangeStateEvent(new EndOfPlayState() /*,session_id()*/));
        }

        function getApplicationStateType() {
            return self::ApplicationStateType;
        }

        function attackCountry($attackingRegionId, $enemyId){
                    header('Content-type: application/json');

                    $regionId = $attackingRegionId;
        
                    $map = $_SESSION['map'];
                    $regions = $map->getRegions();

                    // HACK!!!!!!!!!!! state wird für human player 2 mal gesetzt!!!
                    $playerId = $regions[$regionId]->getPlayerId();
                    $_SESSION['listOfBanks'][$playerId]->setState(Bank::ATTACK);

                    $activeRegion = $regions[$regionId];
                    $activePayment = $activeRegion->getPayment();
        
                    $enemyRegion = $regions[$enemyId];
                    $enemyPayment = $enemyRegion->getPayment();
            
                    $enemyPlayerId = $enemyRegion->getPlayerId();
                    $enemyCountryName = $enemyRegion->getCountry()->getName();

                    $venture = $this->ventureList[mt_rand(0,count($this->ventureList)-1)];

                    $hasPlayerWon = $activePayment->isBuyable($enemyPayment, $venture);
        
                    if($hasPlayerWon){

                        $activePayment->setValue( ($activePayment->getValue() * $venture) );

                        $purchasePrice = $activeRegion->occupyRegion($enemyRegion);
                        
                        $_SESSION['listOfBanks'][$enemyPlayerId]->placeMoney($purchasePrice);

                        $enemyBankCapital = $_SESSION['listOfBanks'][$enemyPlayerId]->getCapital();

                        $this->handleResponse(array("attackCountry" => true,
                                                    "spendMoney" => false,
                                                    "nextPlayer" => false,
                                                    "playerId" => $playerId,
                                                    "activeRegion" => array("hasWon"=> $hasPlayerWon,
                                                                            "paymentValue" => $activePayment->getValue(),
                                                                            "currencyTranslation" => $activePayment->getCurrencyTranslation(),
                                                                            "regionId" => $regionId,
                                                                            "ventureValue" => $venture),
                                                    "enemyRegion" => array(
                                                    "regionId" => $enemyId,
                                                    "regionOfPlayer" => $enemyRegion->getPlayerId(),
                                                    "countryColor" => $enemyRegion->getColor(),
                                                    "paymentValue" => $enemyPayment->getValue(),
                                                    "currencyTranslation" => $enemyPayment->getCurrencyTranslation()),
                                                    
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
                                                                            "paymentValue" => $activePayment->getValue(),
                                                                            "currencyTranslation" => $activePayment->getCurrencyTranslation(),
                                                                            "regionId" => $regionId,
                                                                            "ventureValue" => $venture)));
                    }
                }
        
        function spendMoney($regionId, $action){
                    header('Content-type: application/json');
        
                    $map = $_SESSION['map'];
                    $regions = $map->getRegions();

                    $playerId = $regions[$regionId]->getPlayerId();

                    if($action == "payOff" ||  (isset($_GET['bankstate']) && trim($_GET['bankstate']) == Bank::PAY_OFF)  ) {
                        // HACK!!!!!!!!!!! state wird für human player 2 mal gesetzt!!!
                        $_SESSION['listOfBanks'][$playerId]->setState(Bank::PAY_OFF);
                        $_SESSION['listOfBanks'][$playerId]->payOff($regions[$regionId]->getPayment());

                    }
                   else if($action == "deposit" ||  (isset($_GET['bankstate']) && trim($_GET['bankstate']) == Bank::DEPOSIT)  ) {
                       // HACK!!!!!!!!!!! state wird für human player 2 mal gesetzt!!!
                       $_SESSION['listOfBanks'][$playerId]->setState(Bank::DEPOSIT);
                       $_SESSION['listOfBanks'][$playerId]->deposit($regions[$regionId]->getPayment());
                    }

                    $country = $regions[$regionId]->getCountry();
                    $paymentValue = $regions[$regionId]->getPayment()->getValue() * $regions[$regionId]->getPayment()->getCurrencyTranslation();


                    $bankCapital = $_SESSION['listOfBanks'][$playerId]->getCapital();
        
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

            $_SESSION["nextPlayerCounter"]++;
            $this->nextPlayerCounter = $_SESSION["nextPlayerCounter"];

            $nextPlayerId = $this->nextPlayerCounter % $this->numberOfPlayers;

            echo json_encode(array("attackCountry" => false,
                                   "spendMoney" => false,
                                   "nextPlayer" => true,
                                   "nextPlayerId" => $nextPlayerId));
        }

        function activateAI($nextPlayer) {
           $map = $_SESSION['map'];
           $regions = $map->getRegions();
           $allEnemyRegions = array();

           $enemy = $_SESSION['activePlayers'][$nextPlayer];
           $enemyId = $enemy->getPlayerId();

           for($i = 0; $i < count($regions); $i++){
               if ($regions[$i]->getPlayerId() == $enemyId){
                   array_push($allEnemyRegions, $regions[$i]);
               }
           }

           //print_r($allEnemyRegions);

           if(!$_SESSION['incidentGenerator']->isIncidentActive()){
               $_SESSION['incidentGenerator']->generateIncident();
           }

           $decision = $enemy->makeDecision($allEnemyRegions, $regions);

           if(array_key_exists("attack", $decision)){
               $_SESSION['state']->attackCountry($decision["actualRegionId"], $decision["attack"]);
           }
           else if (array_key_exists("payOff", $decision)){
               $_SESSION['state']->spendMoney($decision["payOff"], "payOff");
           }
           else if (array_key_exists("nextPlayer", $decision)){
               $_SESSION['state']->nextPlayer();
           }
           else if (array_key_exists("deposit", $decision)){
              $_SESSION['state']->spendMoney($decision["deposit"], "deposit");
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
                           $_SESSION['state']->attackCountry(trim($_GET['region']), trim($_GET['enemy']));
                       }
       
                       if(isset($_GET['nextPlayer'])){
                           $_SESSION["nextPlayerCounter"]++;
                           $_SESSION['state']->activateAI(1);
                       }

                       if(isset($_GET['newAIRequest'])){

                           $nextPlayerCounter = $_SESSION["nextPlayerCounter"];
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
    }

           if(isset($_GET['handle']) && trim($_GET['handle']) == "PlayState") {
       
               PlayState::ajaxRequest();
           }
       
       ?>
 
