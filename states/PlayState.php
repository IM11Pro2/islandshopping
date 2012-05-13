<?php
    require_once("../config/config.php");

    class PlayState implements IApplicationState {

        const ApplicationStateType = "PlayState";

        private $listOfBanks;

        function init() {
            $playerList = $_SESSION['activePlayers'];
            $this->listOfBanks = array();
            for($i = 0; $i < count($playerList); $i++){

                array_push($this->listOfBanks, new Bank($playerList[$i]->getCountry(), Bank::PAY_OFF));

            }

            $_SESSION['listOfBanks'] = $this->listOfBanks;
            $_SESSION['state'] = $this;
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
        
                    $hasPlayerWon = $activePayment->isBuyable($enemyPayment);
        
                    if($hasPlayerWon){
                        //  basis value muss noch abgezogen werden
                        $purchasePrice = $activeRegion->occupyRegion($enemyRegion);
                        
                        $_SESSION['listOfBanks'][$enemyPlayerId]->placeMoney($purchasePrice);

                        $enemyBankCapital = $_SESSION['listOfBanks'][$enemyPlayerId]->getCapital();

                        echo json_encode( array("activeRegion" => array("hasWon"=> $hasPlayerWon,
                                                                            "paymentValue" => $activePayment->getValue(),
                                                                            "currencyTranslation" => $activePayment->getCurrencyTranslation(),
                                                                            "regionId" => $regionId),
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
                        echo json_encode( array("activeRegion" => array("hasWon"=> $hasPlayerWon)));
                    }
                }
        
        function spendMoney($regionId){
                    header('Content-type: application/json');
        
                    $map = $_SESSION['map'];
                    $regions = $map->getRegions();
        
                    //if(trim($_GET['bankstate']) == Bank::PAY_OFF) {
        
                        $_SESSION['listOfBanks'][0]->payOff($regions[$regionId]->getPayment());
        
                    /*}
                   else if(trim($_GET['bankstate']) == Bank::DEPOSIT) {
        
                        $_SESSION['listOfBanks'][0]->deposit($regions[$regionId]->getPayment());
                    }*/
        
                    $country = $regions[$regionId]->getCountry();
                    $paymentValue = $regions[$regionId]->getPayment()->getValue() * $regions[$regionId]->getPayment()->getCurrencyTranslation();

                    $playerId = $regions[$regionId]->getPlayerId();

                    $bankCapital = $_SESSION['listOfBanks'][$playerId]->getCapital();
        
                    echo json_encode(array("activeRegion"=> $regionId,
                                           "payment"     => array("value"    => $paymentValue,
                                                                  "currency" => $country->getPayment()->getCurrency()),
                                           "bankCapital" => $bankCapital,
                                            "bankName" => $country->getName()."Bank"));
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
                           $_SESSION['state']->spendMOney(trim($_GET['getCountry']));
                       }
       
                       if(isset($_GET['region']) && isset($_GET['enemy'])){
                           $_SESSION['state']->attackCountry(trim($_GET['region']), trim($_GET['enemy']));
                       }
       
                       if(isset($_GET['nextPlayer'])){
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
       
                           $decision = $enemy->makeDecision($allEnemyRegions, $regions);
       
                           if(array_key_exists("attack", $decision)){
                               $_SESSION['state']->attackCountry($decision["actualRegionId"], $decision["attack"]);
                           }
                           else if (array_key_exists("payOff", $decision)){
                               $_SESSION['state']->spendMoney($decision["payOff"]);
                           }
                       }
                   }
               }
           }
       
       
       
           if(isset($_GET['handle']) && trim($_GET['handle']) == "PlayState") {
       
               PlayState::ajaxRequest();
           }
       
       ?>
 
