<?php
    require_once('../config/config.php');
    class Bank {


        const PAY_OFF = "PayOffState";
        const ATTACK = "AttackState";
        const DEPOSIT = "DEPOSIT";

        private $basicCapitalRegion;
        private $capital; // to do capital as payment

        private $bankState;
        private $bankStateList;
        private $type;

        private $country;

        function __construct(ICountry $country, $initState) {
            $this->country = $country;

            $this->capital = $country->getPayment();
            $this->capital->setValue(START_CAPITAL_COUNTRY);

            //$this->basicCapitalRegion = BASIC_CAPITAL_REGION;



            $this->bankStateList = array(
                self::PAY_OFF => new PayOffState(),
                self::ATTACK => new AttackState(),
                self::DEPOSIT => new DepositState(),

            );
            $this->type = $initState;
            $this->bankState = $this->bankStateList[$initState];
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

        public function placeMoney($price){
            $this->capital->addValue($price);
        }


        public function payOff(IPayment $entryPayment){

            $entryPayment->addValue($this->bankState->payOffMoney($this->capital));
        }

        public function chargeInterest(){
            $this->bankState->chargeInterest();
        }

        public function getPlainCapital(){
            return $this->capital->getValue();
        }

        public function setPlainCapital($value){
            $this->capital->setValue($value);
        }

        public function getCapital(){
            return $this->capital->getValue() * $this->capital->getCurrencyTranslation();
        }

        public function getCountry(){
            return $this->country;
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