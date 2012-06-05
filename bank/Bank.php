<?php
    require_once('../config/config.php');
    class Bank {


        const PAY_OFF = "PayOffState";
        const ATTACK = "AttackState";
        const DEPOSIT = "Deposit";

        private $capital;

        private $bankState;
        private $bankStateList;
        private $type;

        private $country;

        function __construct(ICountry $country, $initState) {
            $this->country = $country;

            $this->capital = $country->getPayment();
            $this->capital->setValue(START_CAPITAL_COUNTRY);

            $this->bankStateList = array(
                self::PAY_OFF => new PayOffState(),
                self::ATTACK => new AttackState(),
                self::DEPOSIT => new DepositState()
            );

            $this->setState($initState);
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
            return $this->capital->__toString();
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