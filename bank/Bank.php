<?php
    require_once('../config/config.php');
    class Bank {


        const PAY_OFF = "PayOffState";
        const ATTACK = "AttackState";
        const DEPOSIT = "Deposit";

        //static $INTEREST_QUEUE = array();
        //static $LIST_OF_BANKS = array(); // is needed too calculate interests for AI
        private $capital;
        private $interestBase;

        private $bankState;
        private $bankStateList;
        private $type;

        private $country;
        private $isCalculatingInterest;

        function __construct(ICountry $country, $initState) {
            $this->country = $country;
            $this->isCalculatingInterest = false;
            $this->capital = $country->getPayment();
            $this->capital->setValue(START_CAPITAL_COUNTRY);
            $this->interestBase = START_CAPITAL_COUNTRY;

            if(!isset($_SESSION['bankQueue'])){
                $_SESSION['bankQueue'] = array();
            }
            $_SESSION['bankQueue'][$country->getName()] = array();

            $this->bankStateList = array(
                self::PAY_OFF => new PayOffState(),
                self::ATTACK => new AttackState(),
                self::DEPOSIT => new DepositState()
            );

            $this->setState($initState);

            if(!isset($_SESSION['bankList'])){
                $_SESSION['bankList'] = array();
            }
            array_push($_SESSION['bankList'], $this);
            //$_SESSION['bankList'] = self::$LIST_OF_BANKS;
            //$_SESSION['bankQueue'] = self::$INTEREST_QUEUE;
        }

        public function setState($state){
            $this->type = $state;
            $this->bankState = $this->bankStateList[$state];
        }

        public function getState(){
            return $this->type;
        }

        public function deposit(IPayment $entryPayment, $isPurchase){

            if($isPurchase){
                $entryPayment->reduceValue( $this->bankState->depositMoney($this->capital, $entryPayment->getValue()) );
            }
            else{

                if(($entryPayment->getValue()-BASIC_CAPITAL_REGION) >= BASIC_CAPITAL_REGION){

                    $entryPayment->reduceValue( $this->bankState->depositMoney($this->capital, BASIC_CAPITAL_REGION) );

                }
            }
        }


        public function payOff(IPayment $regionPayment){

            $moneyToAdd = $this->bankState->payOffMoney($this->capital);
            $regionPayment->addValue( $moneyToAdd );
        }

        /*
        public function chargeInterest($playRound){
                    if($playRound > 1){
                        $interest = $this->bankState->chargeInterest($this->interestBase);
                        $this->capital->addValue($interest);


                        $interestPayment = new Payment();
                        $interestPayment->setValue($interest);
                        $interestPayment->setCurrency($this->country->getName());
                        $interestPayment->setCurrencyTranslation($this->country->getName());

                        return $interestPayment;
                    }

        }*/

        private function chargeInterest($interestBase){


            $interest = $this->bankState->chargeInterest($interestBase);
            $this->capital->addValue($interest);

            $interestPayment = new Payment();
            $interestPayment->setValue($interest);
            $interestPayment->setCurrency($this->country->getName());
            $interestPayment->setCurrencyTranslation($this->country->getName());


            return array("interest" => $interestPayment->__toString(),
                              "bankName"  => $this->getCountry()->getName()."Bank",
                               "countryName" => $this->getCountry()->getName(),
                                "color" => $this->getCountry()->getColor(),
                              "bankCapital" => $this->getCapital() /*, Just for debugging
                                "InterestBase" => $interestBase */);



        }

        public static function getInterests($playerId, $playRound){
            if($playRound > 1){

                $pendingInteresstPayments = array();

                $count = 0;
                $offset = 1;
                //$_SESSION['bankQueue'];

                $loopsBeforeBreak = $playerId+1;

                if($playerId == 0){
                    $loopsBeforeBreak = count($_SESSION['bankQueue']);
                }


                //for($i = 0; $i < ($playerId+1); $i++){
                foreach($_SESSION['bankQueue'] as $countryInterest){


                   if($count == 0 && $playerId == 0){
                        $offset = 1;
                    }
                    else if($count > 0 && $playerId == 0){
                        $offset = 2;
                    }


                    //if(isset($countryInterest[$i])){
                       //$countryInterest = $_SESSION['bankQueue'][$i];

                        // index gesetzt, einzahlung aus der alten runde
                        if(isset($countryInterest[($playRound-$offset)])){
                            // berechnung der Zinsen
                            $interestData = $_SESSION['bankList'][$count]->chargeInterest( $countryInterest[($playRound-$offset)] );
                            array_push($pendingInteresstPayments, $interestData);
                            unset($countryInterest[($playRound-$offset)]);
                            $countryName = $_SESSION['bankList'][$count]->getCountry()->getName();
                            unset($_SESSION['bankQueue'][$countryName][($playRound-$offset)]);
                        }
                   //}
                    $count++;
                    if($count >= $loopsBeforeBreak){
                        break;
                    }
                }

                //$_SESSION['bankQueue'] = $queue;
                return $pendingInteresstPayments;
            }
        }

        public function getPlainCapital(){
            return $this->capital->getValue();
        }

        public function setPlainCapital($value){
            $this->capital->setValue($value);
        }

        public function getCapital(){
            return $this->capital->__toString();
        }

        public function getCountry(){
            return $this->country;
        }

        public function updateInterestBase($playRound){
          // if($this->isCalculatingInterest){

            $this->interestBase = $this->capital->getValue();

           // $queue = $_SESSION['bankQueue'];
            $_SESSION['bankQueue'][$this->country->getName()][$playRound] = $this->interestBase;
            //$_SESSION['bankQueue'] = $queue;
           /*}
           else{
                //$this->interestBase = 0;
                $this->isCalculatingInterest = true;
            }*/
        }

        public function getInterestBase(){
            return ($this->interestBase * $this->capital->getCurrencyTranslation())." ".$this->capital->getCurrency();
        }

        public function isCalculatingInterest(){
            return $this->isCalculatingInterest;
        }

        public function setCalculatingInterest($isCalculating){
            $this->isCalculatingInterest = $isCalculating;
        }

        public static function getBankList(){
            //return self::$LIST_OF_BANKS;
            return $_SESSION['bankList'];
        }
    }

    if(isset($_GET['handle']) && trim($_GET['handle']) == "bank"){

        if(!isset($_SESSION)) {
            session_start();
        }

        if(isset($_GET[session_name()])) {

            session_id($_GET[session_name()]);
        }

        if(isset($_GET['bankstate'])){
            $bankList = $_SESSION['state']->getBankList();
            $bankList[0]->setState($_GET['bankstate']);
        }
    }

?>