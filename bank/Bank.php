<?php
    class Bank {


        const PAY_OFF = "PayOffState";
        const ATTACK = "AttackState";
        const DEPOSIT = "DEPOSIT";

        private $basicCapitalRegion;
        private $capital; // to do capital as payment

        private $bankState;
        private $bankStateList;

        function __construct($country) {

            $this->capital = $country->getPayment();
            $this->capital->setValue(START_CAPITAL_COUNTRY);

            $this->basicCapitalRegion = BASIC_CAPITAL_REGION;



            $this->bankStateList = array(
                self::PAY_OFF => new PayOffState(),
                self::ATTACK => new AttackState(),
                self::DEPOSIT => new DepositState(),

            );

            $this->bankState = $this->bankStateList[self::PAY_OFF];

        }

        public function setState($state){
            $this->bankState = $this->bankStateList[$state];
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


    }
