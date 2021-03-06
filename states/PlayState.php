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

        private $interestsActivatedByHuman;

        private $decisionCounter;
        private $humanPayoffCounter;

        private $decisionPhase;

        function init() {
            $this->incidentGenerator = new IncidentGenerator();
            $_SESSION['incidentGenerator'] = $this->incidentGenerator;

            $this->playerList = $_SESSION['activePlayers'];
            $this->numberOfPlayers = count($this->playerList);
            $this->nextPlayerCounter = 0;
            $this->interestsActivatedByHuman = false;
            $this->bankList = array();
            for($i = 0; $i < $this->numberOfPlayers; $i++){

                $bank = null;
                if($i == 0){
                    $bank = new Bank($this->playerList[$i]->getCountry(), Bank::PAY_OFF); // da war deposit
                }
                else{
                    $bank = new Bank($this->playerList[$i]->getCountry(), Bank::DEPOSIT); // da war deposit
                }
                array_push($this->bankList, $bank);

            }
            $this->bankList = Bank::getBankList();

            $_SESSION['state'] = $this;

            $this->speculationValues = getSpeculationValues();

            $this->decisionCounter = 0;
            $this->humanPayoffCounter = 0;

            $this->decisionPhase ="payOff";

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

                    $originalPayment = new Payment();
                    $originalPayment->setValue($activePayment->getValue());
                    $originalPayment->setCurrency($activeRegion->getCountry()->getName());
                    $originalPayment->setCurrencyTranslation($activeRegion->getCountry()->getName());

                    $playerId = $activeRegion->getPlayerId();

                    $enemyRegion = $map->getRegion($enemyId);
                    $enemyPayment = $enemyRegion->getPayment();

                    $enemyOriginalPayment = new Payment();
                    $enemyOriginalPayment->setValue($enemyPayment->getValue());
                    $enemyOriginalPayment->setCurrency($enemyRegion->getCountry()->getName());
                    $enemyOriginalPayment->setCurrencyTranslation($enemyRegion->getCountry()->getName());
            
                    $enemyPlayerId = $enemyRegion->getPlayerId();
                    $enemyCountryName = $enemyRegion->getCountry()->getName();

                    $speculationValue = $this->speculationValues[mt_rand(0,count($this->speculationValues)-1)];

                    $hasPlayerWon = $activePayment->isBuyable($enemyPayment, $speculationValue);


                    // for calculation info
                    $playerTranslation = $this->playerList[$playerId]->getCountry()->getPayment()->getCurrencyTranslation();
                    $enemyTranslation = $this->playerList[$enemyPlayerId]->getCountry()->getPayment()->getCurrencyTranslation();

                    $specificTranslation = 1/$playerTranslation * $enemyTranslation;

                    //$calculation = ($originalPayment->getValue() - 2*BASIC_CAPITAL_REGION) * $specificTranslation * $speculationValue;
                    $firstCalculation = (($originalPayment->getValue() * $speculationValue) - (2*BASIC_CAPITAL_REGION) );
                    $calculation = $firstCalculation * $playerTranslation * $specificTranslation;
                    $enemyCurrency = $enemyPayment->getCurrency();
                    $playerCurrency = $originalPayment->getCurrency();

                    $specificTranslation = number_format($specificTranslation, 2);
                    $calculation = number_format($calculation, 2);
                    $basicCapital = number_format((BASIC_CAPITAL_REGION * $playerTranslation),2);
        
                    if($hasPlayerWon){

                        $activePayment->setValue( ($activePayment->getValue() * $speculationValue) );

                        $purchasePayment = $activeRegion->buyRegion($enemyRegion);
                        
                        $this->bankList[$enemyPlayerId]->deposit($purchasePayment, true);

                        $enemyBankCapital = $this->bankList[$enemyPlayerId]->getCapital();

                        $humanWon = false;

                        if($playerId == 0){
                            $allHumanPlayerRegions = $map->getRegionsForPlayerId(0);

                            // Checks if human player owns all regions/won
                            if(count($allHumanPlayerRegions) == NUM_OF_REGIONS){
                                $humanWon = true;
                            }
                        }

                        $this->handleResponse(array("attackCountry" => true,
                                                    "spendMoney" => false,
                                                    "nextPlayer" => false,
                                                    "playerId" => $playerId,
                                                    "humanWon" => $humanWon,
                                                    "playerCountry" => $this->playerList[$playerId]->getCountry()->getName(),
                                                    "calculation" => array("originalPayment" => $originalPayment->__toString(),
                                                                            "translation" => $specificTranslation,
                                                                            "firstCalculation" => $firstCalculation,
                                                                            "calculation" => $calculation,
                                                                            "basicCapital" => $basicCapital,
                                                                            "ownCurrency" => $playerCurrency,
                                                                            "enemyCurrency" => $enemyCurrency),
                                                    "activeRegion" => array("hasWon"=> $hasPlayerWon,
                                                                            "payment" => $activePayment->__toString(),
                                                                            "regionId" => $regionId,
                                                                            "ventureValue" => $speculationValue),
                                                    "enemyRegion" => array(
                                                                            "regionId" => $enemyId,
                                                                            "regionOfPlayer" => $enemyRegion->getPlayerId(),
                                                                            "countryColor" => $enemyRegion->getColor(),
                                                                            "payment" => $enemyPayment->__toString(),
                                                                            "enemyOriginalPayment" => $enemyOriginalPayment->__toString()),

                                                    "enemyBank" => array("bankName" => $enemyCountryName."Bank",
                                                                        "bankCapital" => $enemyBankCapital)
                                                ), $this->interestsActivatedByHuman);

                    }
                    else{
                        //echo json_encode( array("activeRegion" => array("hasWon"=> $hasPlayerWon)));
                        $activePayment->setValue(BASIC_CAPITAL_REGION);
                        $this->handleResponse(array("attackCountry" => true,
                                                    "spendMoney" => false,
                                                    "nextPlayer" => false,
                                                    "playerId" => $playerId,
                                                    "playerCountry" => $this->playerList[$playerId]->getCountry()->getName(),
                                                    "enemyRegion" => array("regionId" => $enemyId,
                                                                           "enemyOriginalPayment" => $enemyOriginalPayment->__toString()),
                                                    "calculation" => array("originalPayment" => $originalPayment->__toString(),
                                                                        "translation" => $specificTranslation,
                                                                        "firstCalculation" => $firstCalculation,
                                                                        "calculation" => $calculation,
                                                                        "basicCapital" => $basicCapital,
                                                                        "ownCurrency" => $playerCurrency,
                                                                         "enemyCurrency" => $enemyCurrency),
                                                    "activeRegion" => array("hasWon"=> $hasPlayerWon,
                                                                            "payment" => $activePayment->__toString(),
                                                                            "regionId" => $regionId,
                                                                            "ventureValue" => $speculationValue)), $this->interestsActivatedByHuman);
                    }
                }
        
        function spendMoney($regionId, $action){
                    header('Content-type: application/json');
        
                    $map = $_SESSION['map'];
                    $regions = $map->getRegions();

                    $playerId = $regions[$regionId]->getPlayerId();

                    $updateInfo = false;

                    if($action == "payOff" ||  (isset($_GET['bankstate']) && trim($_GET['bankstate']) == Bank::PAY_OFF)  ) {
                        // if there is enough money on the bank
                        if($this->bankList[$playerId]->getPlainCapital() >= BASIC_CAPITAL_REGION){
                            $updateInfo = true;
                        }
                        $this->bankList[$playerId]->payOff($regions[$regionId]->getPayment());
                        $action = "payOff";

                    }
                   else if($action == "deposit" ||  (isset($_GET['bankstate']) && trim($_GET['bankstate']) == Bank::DEPOSIT)  ) {
                       if($regions[$regionId]->getPayment()->getValue() >= (2*BASIC_CAPITAL_REGION)){
                           $updateInfo = true;
                       }
                       $this->bankList[$playerId]->deposit($regions[$regionId]->getPayment(), false);
                       $action = "deposit";
                    }

                    $country = $regions[$regionId]->getCountry();
                    $paymentValue = $regions[$regionId]->getPayment()->__toString();


                    $bankCapital = $this->bankList[$playerId]->getCapital();

                    if($playerId == 0 && $_SESSION['activePlayers'][$playerId]->getPlayRound() <= PAYOFF_ROUNDS){
        
                        if($this->humanPayoffCounter < PAYOFF_REGIONS_PER_ROUND-1){
                            $this->humanPayoffCounter++;
                        }
                        else {
                            $this->humanPayoffCounter = 0;
                        }
                    }
                    else {
                        $this->humanPayoffCounter = null;
                    }

                    $this->handleResponse(array("attackCountry" => false,
                                                "spendMoney" => true,
                                                "nextPlayer" => false,
                                                "playerId" => $playerId,
                                                "activeRegion"=> $regionId,
                                                "action" => $action,
                                                "numberOfHumanPayoffs" => $this->humanPayoffCounter,
                                                "updateInfo" => $updateInfo,
                                                "payment"     => array("value"    => $paymentValue,
                                                                      "currency" => $country->getPayment()->getCurrency()),
                                                "bankCapital" => $bankCapital,
                                                "bankName" => $country->getName()."Bank"), $this->interestsActivatedByHuman);
        }

        function nextPlayer() {
            header('Content-type: application/json');

            $this->playerList[$this->nextPlayerCounter]->setPlayerState(IPlayer::INACTIVE);

            $this->updateInterestBaseForPlayer();

            $nextPlayerId = $this->getNextPlayerId();

            $this->playerList[$nextPlayerId]->setPlayerState(IPlayer::ACTIVE);

            $this->decisionPhase = "payOff";

            $this->handleResponse(array("attackCountry" => false,
                                   "spendMoney" => false,
                                   "nextPlayer" => true,
                                   "nextPlayerId" => $nextPlayerId,
                                    "playerCountry" => $this->playerList[$nextPlayerId]->getCountry()->getName(),
                                   ), true);
        }

        private function getNextPlayerId(){
            $this->previousPlayerId = $this->nextPlayerCounter;
            do{
                $this->nextPlayerCounter++;
                $this->nextPlayerCounter = $this->nextPlayerCounter % $this->numberOfPlayers;

            }while($this->playerList[$this->nextPlayerCounter]->getPlayerState() == IPlayer::GAME_OVER );

            $this->playerList[$this->nextPlayerCounter]->updatePlayRound();

            return $this->nextPlayerCounter;
        }

        function activateAI($aiPlayerId) {

            if($this->playerList[$aiPlayerId]->getPlayerState() != IPlayer::GAME_OVER){

               foreach($this->bankList as $bank){
                    $bank->setState(Bank::DEPOSIT); // da war deposit
               }

               $map = $_SESSION['map'];

                /*
                 * Look if Human Player has lost
                 */
                $allHumanPlayerRegions = $map->getRegionsForPlayerId(0);

                if(count($allHumanPlayerRegions) == 0){
                    $this->playerList[0]->setPlayerState(IPlayer::GAME_OVER);
                    $this->handleResponse(array("humanLost" => true),false);
                    return;
                }
                /*
                 *
                 */

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

                    // at the beginning, there is only payoff possible for every player
                    if($aiPlayer->getPlayRound() <= PAYOFF_ROUNDS ){
                        $decision = $aiPlayer->makeInitPayOff($allAiPlayerRegions, $regions);
                    }
                    else {
                        switch($this->decisionPhase){
                            case "payOff":
                                $decision = $aiPlayer->payOffDecision($allAiPlayerRegions, $regions);
                                break;
                            case "attack":
                                $decision = $aiPlayer->attackDecision($allAiPlayerRegions, $regions);
                                break;
                            case "deposit":
                                $decision = $aiPlayer->depositDecision($allAiPlayerRegions, $regions);
                                break;
                            case "nextPlayer":
                                $decision = null;
                                break;
                        }
                    }

                    if($decision != null){
                        $this->doDecision($aiPlayerId, $decision);
                    }
                    else {
                        $this->nextPlayer();
                    }
                }
            }
            else {
                $this->nextPlayer();
            }
        }

        public function doDecision($aiPlayerId, $decision){

            if (array_key_exists("initPayOff", $decision)){
                $this->bankList[$aiPlayerId]->setState(Bank::PAY_OFF);
                if($this->decisionCounter < PAYOFF_REGIONS_PER_ROUND){
                    $this->decisionCounter++;
                    $this->spendMoney($decision["initPayOff"], "payOff");
                }
                else {
                    $this->decisionCounter = 0;
                    $this->nextPlayer();
                }

            }

            else if(array_key_exists("payOff", $decision)) {
                $this->bankList[$aiPlayerId]->setState(Bank::PAY_OFF);
                $this->spendMoney($decision["payOff"], "payOff");

                if($decision["nextPhase"]){
                    $this->decisionPhase = "attack";
                }
            }
            else if(array_key_exists("attack", $decision)){
                $this->decisionPhase = "attack";
                $this->bankList[$aiPlayerId]->setState(Bank::ATTACK);
                $this->tryToBuyRegion($decision["actualRegionId"], $decision["attack"]);

                if($decision["nextPhase"]){
                    $this->decisionPhase = "deposit";
                }
            }
            else if(array_key_exists("deposit", $decision)) {
                $this->decisionPhase = "deposit";
                $this->bankList[$aiPlayerId]->setState(Bank::DEPOSIT);
                $this->spendMoney($decision["deposit"], "deposit");

                if($decision["nextPhase"]){
                    $this->decisionPhase = "nextPlayer";
                }
            }

        }

        private function updateInterestBaseForPlayer(){
            $player = $this->playerList[$this->nextPlayerCounter];
            if($player->getPlayerState() != IPlayer::GAME_OVER){

                $this->bankList[$this->nextPlayerCounter]->updateInterestBase($player->getPlayRound());
            }
        }


        public function setInterestsActivatedByHuman($byHuman){
            $this->interestsActivatedByHuman = $byHuman;
        }


        public function getInterestsActivatedByHuman(){
            return $this->interestsActivatedByHuman;
        }

        public static function ajaxRequest() {
       
                   if(!isset($_SESSION)) {
                       session_start();
                   }
       
                   if(isset($_GET[session_name()])) {
       
                       session_id($_GET[session_name()]);
       
                       if(isset($_GET['getNeighbours'])) {
       
                           header('Content-type: application/json');
       
                           $regionId = trim($_GET['getNeighbours']);
       
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

                           $_SESSION['state']->updateInterestBaseForPlayer(); //for human player

                           $nextPlayerId = $_SESSION['state']->getNextPlayerId();

                           $_SESSION['state']->setInterestsActivatedByHuman(true);
                           $_SESSION['state']->activateAI($nextPlayerId);
                       }

                       if(isset($_GET['newAIRequest'])){
                           $nextPlayerCounter = $_SESSION['state']->getNextPlayerCounter();
                           //$numberOfPlayers = count($_SESSION['activePlayers']);
                           //$nextPlayerId = $nextPlayerCounter % $numberOfPlayers;
                            $nextPlayerId = $nextPlayerCounter;
                           if($nextPlayerId != 0) {
                                $_SESSION['state']->setInterestsActivatedByHuman(false);;
                                $_SESSION['state']->activateAI($nextPlayerId);
                           }
                           else {
                               $_SESSION['state']->bankList[$nextPlayerId]->setState(Bank::PAY_OFF);
                               $_SESSION['state']->handleResponse(array("humanPlayer" => true, "round" =>  $_SESSION['state']->playerList[$nextPlayerId]->getPlayRound()), false);
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

        private function handleResponse($json, $withInterest){

           if(isset($this->incident)){
               $json['incident'] = $this->incident;
           }
           unset($this->incident);

           if($withInterest){
               foreach($this->bankList as $bank){
                    $bank->setState(Bank::DEPOSIT);
               }
               $interests = Bank::getInterests($this->nextPlayerCounter, $this->playerList[$this->nextPlayerCounter]->getPlayRound());
               if(isset($interests)){
                   $json['interests'] = $interests;

               }
           }

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
 
