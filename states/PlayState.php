<?php
    require_once("../config/config.php");

    class PlayState implements IApplicationState {

        const ApplicationStateType = "PlayState";

        private $bank;

        function init() {
            $playerList = $_SESSION['activePlayers'];
            $this->bank = new Bank($playerList[0]->getCountry(), Bank::ATTACK);

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

        function attackCountry($attackingCountryId, $enemyId){
            header('Content-type: application/json');

            $regionId = $attackingCountryId;

            $map = $_SESSION['map'];
            $regions = $map->getRegions();

            $activeRegion = $regions[$regionId];
            $activePayment = $activeRegion->getPayment();

            $enemyRegion = $regions[$enemyId];
            $enemyPayment = $enemyRegion->getPayment();

            $hasPlayerWon = $activePayment->isBuyable($enemyPayment);

            if($hasPlayerWon){
                //  basis value muss noch abgezogen werden
                $activeRegion->occupyRegion($enemyRegion);
                $hallo = json_encode( array("activeRegion" => array("hasWon"=> $hasPlayerWon,
                                                                    "paymentValue" => $activePayment->getValue(),
                                                                    "currencyTranslation" => $activePayment->getCurrencyTranslation(),
                                                                    "regionId" => $regionId),
                                        "enemyRegion" => array(
                                            "regionId" => $enemyId,
                                            "regionOfPlayer" => $enemyRegion->getPlayerId(),
                                            "countryColor" => $enemyRegion->getColor(),
                                            "paymentValue" => $enemyPayment->getValue(),
                                            "currencyTranslation" => $enemyPayment->getCurrencyTranslation())
                                        ));

                echo $hallo;
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

                $_SESSION['bank']->payOff($regions[$regionId]->getPayment());

           // }
/*            else if(trim($_GET['bankstate']) == Bank::DEPOSIT) {

                $_SESSION['bank']->deposit($regions[$regionId]->getPayment());
            }*/

            $country = $regions[$regionId]->getCountry();
            $paymentValue = $regions[$regionId]->getPayment()->getValue() * $regions[$regionId]->getPayment()->getCurrencyTranslation();

            $bankCapital = $_SESSION['bank']->getCapital();

            echo json_encode(array("activeRegion"=> $regionId,
                                   "payment"     => array("value"    => $paymentValue,
                                                          "currency" => $country->getPayment()->getCurrency()),
                                   "bankCapital" => $bankCapital));
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

                    print_r($decision);

                    if(array_key_exists("attack", $decision)){
                        //$_SESSION['state']->attackCountry($decision[0], $decision[1]);
                    }
                    else if (array_key_exists("payOff", $decision)){
                        $_SESSION['state']->spendMOney($decision["payOff"]);
                    }
                }
            }
        }
    }



    if(isset($_GET['handle']) && trim($_GET['handle']) == "PlayState") {

        PlayState::ajaxRequest();
    }

?>
 
