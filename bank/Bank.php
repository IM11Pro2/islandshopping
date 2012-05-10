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

        function __construct(ICountry $country, $initState) {

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
            $_SESSION['bank'] = $this;
        }

        public function setState($state){
            $this->type = $state;
            $this->bankState = $this->bankStateList[$state];
        }

        public function getState(){
            return $this->type;
        }

        public function deposit(){
            $this->bankState->depositMoney($this->capital);
        }

        public function payOff(){
            return $this->bankState->payOffMoney($this->capital);
        }

        public function chargeInterest(){
            $this->bankState->chargeInterest();
        }

        public function getPlainCapital(){
            return $this->capital->getValue();
        }

        public function getCapital(){
            return $this->capital->getValue() * $this->capital->getCurrencyTranslation();
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
            $_SESSION['bank']->setState($_GET['bankstate']);
        }
    }

?>