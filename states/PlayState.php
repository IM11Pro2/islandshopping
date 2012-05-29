<?php
    require_once("../config/config.php");

    class PlayState implements IApplicationState, IEventListener {

        const ApplicationStateType = "PlayState";

        private $listOfBanks;
        private $incidentGenerator;
        private $incident;

        private $ventureList;

        function init() {
            $this->incidentGenerator = new IncidentGenerator();
            $_SESSION['incidentGenerator'] = $this->incidentGenerator;

            $playerList = $_SESSION['activePlayers'];
            $this->listOfBanks = array();
            for($i = 0; $i < count($playerList); $i++){

                array_push($this->listOfBanks, new Bank($playerList[$i]->getCountry(), Bank::PAY_OFF));

            }

            $_SESSION['listOfBanks'] = $this->listOfBanks;
            $_SESSION['state'] = $this;

            $this->ventureList = getVentureValues();

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
                                                    "activeRegion" => array("hasWon"=> $hasPlayerWon,
                                                                            "paymentValue" => $activePayment->getValue(),
                                                                            "currencyTranslation" => $activePayment->getCurrencyTranslation(),
                                                                            "regionId" => $regionId,
                                                                            "ventureValue" => $venture)));
                    }
                }
        
        function spendMoney($regionId){
                    header('Content-type: application/json');
        
                    $map = $_SESSION['map'];
                    $regions = $map->getRegions();

                    $playerId = $regions[$regionId]->getPlayerId();

                    //if(trim($_GET['bankstate']) == Bank::PAY_OFF) {

                    $_SESSION['listOfBanks'][$playerId]->payOff($regions[$regionId]->getPayment());

                    /*}
                   else if(trim($_GET['bankstate']) == Bank::DEPOSIT) {

                        $_SESSION['listOfBanks'][0]->deposit($regions[$regionId]->getPayment());
                    }*/

                    $country = $regions[$regionId]->getCountry();
                    $paymentValue = $regions[$regionId]->getPayment()->getValue() * $regions[$regionId]->getPayment()->getCurrencyTranslation();


                    $bankCapital = $_SESSION['listOfBanks'][$playerId]->getCapital();
        
                    $this->handleResponse(array("attackCountry" => false,
                                            "spendMoney" => true,
                                            "nextPlayer" => false,
                                            "activeRegion"=> $regionId,
                                            "payment"     => array("value"    => $paymentValue,
                                                                  "currency" => $country->getPayment()->getCurrency()),
                                            "bankCapital" => $bankCapital,
                                            "bankName" => $country->getName()."Bank"));
                }

        function nextPlayer() {
            header('Content-type: application/json');

            // TODO calculate next PlayerId
            $playerId = "0";

            echo json_encode(array("attackCountry" => false,
                                        "spendMoney" => false,
                                        "nextPlayer" => true,
                "nextPlayerId" => $playerId));
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
                           $_SESSION['state']->spendMoney(trim($_GET['getCountry']));
                       }
       
                       if(isset($_GET['region']) && isset($_GET['enemy'])){
                           $_SESSION['state']->attackCountry(trim($_GET['region']), trim($_GET['enemy']));
                       }
       
                       if(isset($_GET['nextPlayer'])){

                           /* vielleicht erst nötig wenn das weiterschalten der spieler funktioniert
                           foreach($_SESSION['listOFBanks'] as $bank){
                               */
                           $_SESSION['listOFBanks'][0]->setState(Bank::DEPOSIT);
                                /*$bank->setState(Bank::DEPOSIT);
                           }*/

                           $map = $_SESSION['map'];
                           $regions = $map->getRegions();
                           $allEnemyRegions = array();
       
                           $enemy = $_SESSION['activePlayers'][1];
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
                               $_SESSION['state']->spendMoney($decision["payOff"]);
                           }
                           else if (array_key_exists("nextPlayer", $decision)){
                               $_SESSION['state']->nextPlayer();
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
 
