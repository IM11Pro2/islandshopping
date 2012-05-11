<?php
    require_once("../config/config.php");

    class PlayState implements IApplicationState {

        const ApplicationStateType = "PlayState";

        private $bank;

        function init() {
            $playerList = $_SESSION['activePlayers'];
            $this->bank = new Bank($playerList[0]->getCountry(), Bank::ATTACK);
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

                    header('Content-type: application/json');

                    $regionId = trim($_GET['getCountry']);

                    $map = $_SESSION['map'];
                    $regions = $map->getRegions();

                    if(trim($_GET['bankstate']) == Bank::PAY_OFF) {

                        $_SESSION['bank']->payOff($regions[$regionId]->getPayment());

                    }
                    else if(trim($_GET['bankstate']) == Bank::DEPOSIT) {

                        $_SESSION['bank']->deposit($regions[$regionId]->getPayment());
                    }

                    $country = $regions[$regionId]->getCountry();
                    $paymentValue = $regions[$regionId]->getPayment()->getValue() * $regions[$regionId]->getPayment()->getCurrencyTranslation();

                    $bankCapital = $_SESSION['bank']->getCapital();

                    echo json_encode(array("activeRegion"=> $regionId,
                                           "payment"     => array("value"    => $paymentValue,
                                                                  "currency" => $country->getPayment()->getCurrency()),
                                           "bankCapital" => $bankCapital));

                }
            }
        }
    }

    if(isset($_GET['handle']) && trim($_GET['handle']) == "PlayState") {

        PlayState::ajaxRequest();
    }

?>
 
