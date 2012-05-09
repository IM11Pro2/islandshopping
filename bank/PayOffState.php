<?php
    class PayOffState implements IBankState{

        public function depositMoney(IPayment $capital){
            $capital->addValue(BASIC_CAPITAL_REGION);
        }

        public function payOffMoney(IPayment $capital) {

            if(($capital->getValue() - BASIC_CAPITAL_REGION) > 0){
                return BASIC_CAPITAL_REGION;
            }
            else{
                return 0;
            }

        }

        public function chargeInterest() {
            // TODO: Implement chargeInterest() method.
        }
    }
